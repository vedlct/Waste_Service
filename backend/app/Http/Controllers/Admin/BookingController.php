<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\BuildsAdminDataTables;
use App\Http\Controllers\Admin\Concerns\FlashesMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\CoverageArea;
use App\Support\CsvCell;
use App\Support\Money;
use App\Support\WebsiteNotifier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookingController extends Controller
{
    use BuildsAdminDataTables;
    use FlashesMessages;

    public function index()
    {
        return view('admin.bookings.index', [
            'awaitingPaymentCount' => Booking::query()->awaitingPayment()->count(),
            'submittedCount' => Booking::query()->withStatus('submitted')->count(),
            'upcomingCount' => Booking::query()->upcoming()->count(),
        ]);
    }

    public function data(Request $request)
    {
        $query = $this->filteredQuery($request)
            ->with(['billingAddress:id,booking_id,first_name,last_name,email,phone,postcode'])
            ->withCount('items')
            ->select([
                'id', 'reference', 'status', 'payment_status', 'payment_option',
                'collection_date', 'is_saturday_collection', 'total_pence', 'created_at',
            ]);

        return $this->adminDataTable($query)
            // Replaces the default column search, so a postcode or phone number finds the booking.
            ->filter(fn ($builder) => $builder->search((string) $request->input('search.value', '')))
            ->addColumn('booking', fn (Booking $booking) => $this->renderAdminPartial('admin.bookings.partials.reference', compact('booking')))
            ->addColumn('customer', fn (Booking $booking) => $this->renderAdminPartial('admin.bookings.partials.customer', compact('booking')))
            ->editColumn('status', fn (Booking $booking) => $this->renderAdminPartial('admin.bookings.partials.status', [
                'label' => $booking->statusLabel(),
                'badge' => config('bookings.statuses.'.$booking->status.'.badge', 'muted'),
            ]))
            ->editColumn('payment_status', fn (Booking $booking) => $this->renderAdminPartial('admin.bookings.partials.status', [
                'label' => $booking->paymentStatusLabel(),
                'badge' => config('bookings.payment_statuses.'.$booking->payment_status.'.badge', 'muted'),
            ]))
            ->editColumn('collection_date', fn (Booking $booking) => $this->renderAdminPartial('admin.bookings.partials.collection-date', compact('booking')))
            ->editColumn('total_pence', fn (Booking $booking) => '<span class="fw-bold">'.e(Money::format($booking->total_pence)).'</span>')
            ->editColumn('created_at', fn (Booking $booking) => $this->formatAdminDate($booking->created_at, 'd M Y, h:i A'))
            ->addColumn('actions', fn (Booking $booking) => $this->renderAdminPartial('admin.bookings.partials.actions', compact('booking')))
            ->rawColumns(['booking', 'customer', 'status', 'payment_status', 'collection_date', 'total_pence', 'actions'])
            ->toJson();
    }

    public function show(Booking $booking)
    {
        $booking->load(['items', 'billingAddress', 'collectionAddress', 'payments', 'service:id,name']);

        return view('admin.bookings.show', [
            'booking' => $booking,
            'outsideCoverage' => $this->isOutsideCoverage($booking),
        ]);
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $data = $request->validated();
        $notes = $data['admin_notes'] ?? null;
        $notifyCustomer = (bool) ($data['notify_customer'] ?? false);
        $previousStatus = $booking->status;
        unset($data['admin_notes'], $data['notify_customer']);

        $metadata = $booking->metadata ?? [];
        $metadata['admin_notes'] = filled($notes) ? $notes : null;

        $booking->update($data + $booking->workflowTimestamps($data['status']) + [
            'metadata' => array_filter($metadata, fn ($value): bool => $value !== null) ?: null,
        ]);

        if ($notifyCustomer) {
            WebsiteNotifier::bookingStatusChanged($booking, $previousStatus);
        }

        return $this->success(
            redirect()->route('admin.bookings.show', $booking),
            'Booking updated successfully.',
        );
    }

    public function destroy(Booking $booking)
    {
        // Soft deleted so the reference and its payment history stay resolvable.
        $booking->delete();

        return $this->success(redirect()->route('admin.bookings.index'), 'Booking deleted successfully.');
    }

    /**
     * One printable sheet per collection day for the crew: where to go, who to call, what
     * to take, access problems, and how much to collect on the doorstep.
     */
    public function daySheet(Request $request)
    {
        $date = Carbon::make($request->query('date'))?->startOfDay() ?? now()->startOfDay();

        $bookings = Booking::query()
            ->whereDate('collection_date', $date->toDateString())
            // A draft is not a confirmed job and a cancelled one is not happening.
            ->whereNotIn('status', ['draft', 'cancelled'])
            ->with(['items', 'billingAddress', 'collectionAddress'])
            ->orderBy('notice_minutes')
            ->orderBy('id')
            ->get();

        return view('admin.bookings.day-sheet', [
            'date' => $date,
            'bookings' => $bookings,
            'amountToCollect' => $bookings->sum(fn (Booking $booking): int => $booking->outstandingPence()),
        ]);
    }

    /**
     * CSV of the bookings matching the list's filters, plus an optional collection date range.
     */
    public function export(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $query = $this->filteredQuery($request)
            ->search((string) $request->query('search', ''))
            ->when($validated['from'] ?? null, fn ($builder, $from) => $builder->whereDate('collection_date', '>=', $from))
            ->when($validated['to'] ?? null, fn ($builder, $to) => $builder->whereDate('collection_date', '<=', $to))
            ->with(['billingAddress', 'collectionAddress'])
            ->orderBy('collection_date')
            ->orderBy('id');

        $filename = 'bookings-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($query): void {
            $out = fopen('php://output', 'w');
            // Excel needs the byte order mark to read the pound sign correctly.
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'Reference', 'Status', 'Payment status', 'Payment option', 'Collection date', 'Saturday',
                'Customer', 'Email', 'Phone', 'Mobile', 'Collection postcode', 'Collection address',
                'Subtotal', 'Extra charges', 'VAT included', 'Total', 'Placed',
            ]);

            $query->chunk(200, function ($bookings) use ($out): void {
                foreach ($bookings as $booking) {
                    $billing = $booking->billingAddress;
                    $site = $booking->collectionAddress ?? $billing;

                    fputcsv($out, array_map([CsvCell::class, 'safe'], [
                        $booking->reference,
                        $booking->statusLabel(),
                        $booking->paymentStatusLabel(),
                        $booking->paymentOptionLabel(),
                        $booking->collection_date?->format('Y-m-d'),
                        $booking->is_saturday_collection ? 'Yes' : 'No',
                        $billing?->full_name,
                        $billing?->email,
                        $billing?->phone,
                        $billing?->mobile,
                        $site?->postcode,
                        implode(', ', $site?->lines() ?? []),
                        Money::toPounds($booking->subtotal_pence),
                        Money::toPounds($booking->extra_charges_pence),
                        Money::toPounds($booking->vat_pence),
                        Money::toPounds($booking->total_pence),
                        $booking->created_at?->format('Y-m-d H:i'),
                    ]));
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * The status, payment and scope filters shared by the list and the export.
     */
    private function filteredQuery(Request $request): Builder
    {
        return Booking::query()
            ->when($request->filled('status'), fn ($builder) => $builder->where('status', $request->string('status')->toString()))
            ->when($request->filled('payment_status'), fn ($builder) => $builder->where('payment_status', $request->string('payment_status')->toString()))
            ->when($request->input('scope') === 'awaiting_payment', fn ($builder) => $builder->awaitingPayment())
            ->when($request->input('scope') === 'upcoming', fn ($builder) => $builder->upcoming());
    }

    /**
     * Postcodes are never rejected at submit; the office is warned here instead, so a
     * job just outside the listed areas can still be taken on.
     */
    private function isOutsideCoverage(Booking $booking): bool
    {
        $postcode = $booking->collectionAddress?->postcode ?? $booking->billingAddress?->postcode;

        if (blank($postcode)) {
            return false;
        }

        return ! CoverageArea::query()->active()->matchingPostcode($postcode)->exists();
    }
}
