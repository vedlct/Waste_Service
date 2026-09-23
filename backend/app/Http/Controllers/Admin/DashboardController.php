<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ContactEnquiry;
use App\Models\CoverageArea;
use App\Models\LoadPackage;
use App\Models\Page;
use App\Models\Review;
use App\Models\Service;
use App\Models\ServiceItem;
use Illuminate\Http\Request;

/**
 * The admin landing page: what is waiting on the office, then a snapshot of the catalogue.
 * Every section is limited to the modules the signed-in role can open.
 */
class DashboardController extends Controller
{
    private const UPCOMING_DAYS = 14;

    public function __invoke(Request $request)
    {
        $user = $request->user();
        $can = fn (string $module): bool => $user->can('access-module', $module);

        return view('admin.dashboard', [
            'attention' => $this->attention($can),
            'upcomingBookings' => $can('bookings') ? $this->upcomingBookings() : null,
            'openEnquiries' => $can('enquiries') ? $this->openEnquiries() : null,
            'catalogue' => [
                ['label' => 'Published services', 'value' => Service::query()->published()->count(), 'icon' => 'boxes'],
                ['label' => 'Bookable items', 'value' => ServiceItem::query()->active()->count(), 'icon' => 'tags-fill'],
                ['label' => 'Live reviews', 'value' => Review::query()->published()->count(), 'icon' => 'star-fill'],
                ['label' => 'Coverage areas', 'value' => CoverageArea::query()->active()->count(), 'icon' => 'geo-alt-fill'],
            ],
            'upcomingDays' => self::UPCOMING_DAYS,
        ]);
    }

    /**
     * Cards for work that is waiting, each linking to the filtered list that resolves it.
     *
     * @return array<int, array<string, mixed>>
     */
    private function attention(callable $can): array
    {
        $cards = [];

        if ($can('bookings')) {
            $cards[] = [
                'label' => 'Bookings to confirm',
                'value' => Booking::query()->withStatus('submitted')->count(),
                'icon' => 'calendar-check',
                'url' => route('admin.bookings.index', ['status' => 'submitted']),
                'hint' => 'Submitted online and waiting for the office.',
            ];
            $cards[] = [
                'label' => 'Awaiting payment',
                'value' => Booking::query()->awaitingPayment()->count(),
                'icon' => 'hourglass-split',
                'url' => route('admin.bookings.index', ['scope' => 'awaiting_payment']),
                'hint' => 'Pay-now drafts; chase these by hand.',
            ];
        }

        if ($can('enquiries')) {
            $cards[] = [
                'label' => 'Unassigned enquiries',
                'value' => ContactEnquiry::query()->open()->unassigned()->count(),
                'icon' => 'envelope-exclamation',
                'url' => route('admin.enquiries.index', ['scope' => 'unassigned']),
                'hint' => 'Open, with nobody replying yet.',
            ];
        }

        if ($can('reviews')) {
            $cards[] = [
                'label' => 'Reviews to moderate',
                'value' => Review::query()->awaitingModeration()->count(),
                'icon' => 'star-half',
                'url' => route('admin.reviews.index', ['status' => 'pending']),
                'hint' => 'Hidden from the website until published.',
            ];
        }

        if ($can('pricing')) {
            $cards[] = [
                'label' => 'Placeholder prices',
                'value' => ServiceItem::query()->withPricingStatus('placeholder')->count()
                    + LoadPackage::query()->withPricingStatus('placeholder')->count(),
                'icon' => 'exclamation-triangle',
                'url' => route('admin.service-items.index', ['pricing_status' => 'placeholder']),
                'hint' => 'Provisional prices to confirm before launch.',
            ];
        }

        if ($can('pages')) {
            $cards[] = [
                'label' => 'Pages missing SEO',
                'value' => Page::query()
                    ->where('is_indexable', true)
                    ->where(fn ($query) => $query->whereNull('meta_description')
                        ->orWhere('meta_description', '')
                        ->orWhere('meta_description', PageController::PLACEHOLDER_DESCRIPTION))
                    ->count(),
                'icon' => 'search',
                'url' => route('admin.pages.index'),
                'hint' => 'No real search description yet.',
            ];
        }

        if ($can('coverage')) {
            $cards[] = [
                'label' => 'Areas without a postcode',
                'value' => CoverageArea::query()->whereNull('postcode_prefix')->count(),
                'icon' => 'signpost-split',
                'url' => route('admin.coverage-areas.index', ['flag' => 'missing_postcode']),
                'hint' => 'Needed for the postcode coverage check.',
            ];
        }

        return $cards;
    }

    private function upcomingBookings()
    {
        return Booking::query()
            ->upcoming()
            ->whereDate('collection_date', '<=', now()->addDays(self::UPCOMING_DAYS)->toDateString())
            ->with(['billingAddress:id,booking_id,first_name,last_name,postcode', 'collectionAddress:id,booking_id,postcode'])
            ->orderBy('collection_date')
            ->limit(10)
            ->get(['id', 'reference', 'status', 'payment_status', 'collection_date', 'is_saturday_collection', 'total_pence']);
    }

    private function openEnquiries()
    {
        return ContactEnquiry::query()
            ->open()
            ->with('assignee:id,name')
            ->latest()
            ->limit(6)
            ->get(['id', 'name', 'service_label', 'status', 'assigned_to', 'created_at']);
    }
}
