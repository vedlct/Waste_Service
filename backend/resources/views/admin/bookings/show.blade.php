@extends('layouts.app')

@section('title', 'Booking ' . $booking->reference)
@section('eyebrow', 'Operations')
@section('page-title', 'Booking ' . $booking->reference)
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Bookings', 'url' => route('admin.bookings.index')],
    ['label' => $booking->reference],
])

@section('content')
@if ($outsideCoverage)
    <div class="alert alert-warning border-0 rounded-4 d-flex gap-3 align-items-start mb-4">
        <i class="bi bi-geo-alt-fill fs-4"></i>
        <div>
            <div class="fw-black">This postcode is outside the listed coverage areas</div>
            <div class="small mb-0">The booking was still accepted. Check the job is workable before confirming, or add the area under Coverage Areas.</div>
        </div>
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-7">
        @component('admin.partials.panel')
            @component('admin.partials.page-header', [
                'eyebrow' => 'Collection',
                'title' => $booking->collection_date?->format('l, d M Y') ?? 'No collection date',
                'description' => $booking->paymentOptionLabel() . ' · ' . ($booking->notice_minutes ? $booking->notice_minutes . ' minutes notice' : 'No notice preference'),
            ])
                @slot('actions')
                    @include('admin.bookings.partials.status', [
                        'label' => $booking->statusLabel(),
                        'badge' => config('bookings.statuses.' . $booking->status . '.badge', 'muted'),
                    ])
                @endslot
            @endcomponent

            <dl class="row">
                <dt class="col-sm-4 text-muted fw-normal">Saturday collection</dt>
                <dd class="col-sm-8">{{ $booking->is_saturday_collection ? 'Yes' : 'No' }}</dd>

                <dt class="col-sm-4 text-muted fw-normal">Restricted access</dt>
                <dd class="col-sm-8">{{ str($booking->restricted_access ?: 'not answered')->headline() }}</dd>

                <dt class="col-sm-4 text-muted fw-normal">Surcharge acknowledged</dt>
                <dd class="col-sm-8">{{ $booking->access_surcharge_acknowledged ? 'Yes' : 'No' }}</dd>

                <dt class="col-sm-4 text-muted fw-normal">Submitted</dt>
                <dd class="col-sm-8">{{ $booking->submitted_at?->format('d M Y, h:i A') ?? 'Not submitted' }}</dd>

                <dt class="col-sm-4 text-muted fw-normal">Confirmed</dt>
                <dd class="col-sm-8 mb-0">{{ $booking->confirmed_at?->format('d M Y, h:i A') ?? 'Not confirmed' }}</dd>
            </dl>

            @foreach ([
                'access_restrictions' => 'Access restrictions',
                'large_items' => 'Large items',
                'collection_notes' => 'Collection notes',
            ] as $field => $label)
                @if ($booking->$field)
                    <div class="border-top mt-4 pt-4">
                        <p class="small fw-bold text-uppercase text-primary mb-2">{{ $label }}</p>
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $booking->$field }}</p>
                    </div>
                @endif
            @endforeach
        @endcomponent

        @component('admin.partials.table-card', ['class' => 'p-4 mt-4'])
            @slot('header')
                @component('admin.partials.page-header', [
                    'eyebrow' => 'Priced at booking time',
                    'title' => 'Booking lines',
                    'description' => 'These are snapshots. Later catalogue edits never change them.',
                    'class' => 'mb-3',
                ])
                @endcomponent
            @endslot

            <table class="table table-hover w-100">
                <thead>
                    <tr>
                        <th>Line</th>
                        <th>Type</th>
                        <th class="text-end">Unit</th>
                        <th class="text-end">Qty</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($booking->items as $item)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $item->name }}</div>
                                @if ($item->catalogue_sku)
                                    <div class="small text-muted">{{ $item->catalogue_sku }}</div>
                                @endif
                            </td>
                            <td>{{ $item->lineTypeLabel() }}</td>
                            <td class="text-end">{{ \App\Support\Money::format($item->unit_price_pence) }}</td>
                            <td class="text-end">{{ $item->quantity }}</td>
                            <td class="text-end fw-bold">{{ \App\Support\Money::format($item->line_total_pence) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end text-muted fw-normal">Subtotal</th>
                        <th class="text-end">{{ \App\Support\Money::format($booking->subtotal_pence) }}</th>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-end text-muted fw-normal">Extra charges</th>
                        <th class="text-end">{{ \App\Support\Money::format($booking->extra_charges_pence) }}</th>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-end text-muted fw-normal">VAT included</th>
                        <th class="text-end">{{ \App\Support\Money::format($booking->vat_pence) }}</th>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-end">Total</th>
                        <th class="text-end">{{ \App\Support\Money::format($booking->total_pence) }}</th>
                    </tr>
                </tfoot>
            </table>
        @endcomponent
    </div>

    <div class="col-lg-5">
        @component('admin.partials.panel')
            <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
                @csrf
                @method('PUT')

                <div class="metric-icon mb-3"><i class="bi bi-clipboard-check"></i></div>
                <h2 class="h5 fw-black mb-1">Workflow</h2>
                <p class="text-muted mb-4">The customer's details and the priced lines are kept exactly as booked.</p>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold" for="status">Booking status</label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                            @foreach (config('bookings.statuses') as $value => $status)
                                <option value="{{ $value }}" @selected(old('status', $booking->status) === $value)>{{ $status['label'] }}</option>
                            @endforeach
                        </select>
                        @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                        <ul class="list-unstyled small text-muted mt-2 mb-0">
                            @foreach (config('bookings.statuses') as $status)
                                <li class="d-flex gap-2 mb-1">
                                    <span class="fw-bold text-nowrap">{{ $status['label'] }}:</span>
                                    <span>{{ $status['description'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @php($notifyingStatuses = collect(config('bookings.statuses'))->filter(fn ($status) => ! empty($status['notifies_customer']))->map(fn ($status) => $status['label'])->implode(', '))
                    <div class="col-12">
                        <div class="form-check">
                            <input type="hidden" name="notify_customer" value="0">
                            <input class="form-check-input" type="checkbox" id="notify_customer" name="notify_customer" value="1" @checked(old('notify_customer', true))>
                            <label class="form-check-label fw-bold" for="notify_customer">Email the customer about a status change</label>
                        </div>
                        <div class="form-text">
                            Only sent when the status actually changes to {{ $notifyingStatuses }}, and only if customer status emails are on in Site Settings &rarr; Notifications.
                            @if ($booking->billingAddress?->email)
                                Goes to {{ $booking->billingAddress->email }}.
                            @else
                                This booking has no customer email.
                            @endif
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold" for="payment_status">Payment status</label>
                        <select id="payment_status" name="payment_status" class="form-select @error('payment_status') is-invalid @enderror" required>
                            @foreach (config('bookings.payment_statuses') as $value => $status)
                                <option value="{{ $value }}" @selected(old('payment_status', $booking->payment_status) === $value)>{{ $status['label'] }}</option>
                            @endforeach
                        </select>
                        @error('payment_status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold" for="admin_notes">Internal notes</label>
                        <textarea id="admin_notes" name="admin_notes" rows="4" class="form-control @error('admin_notes') is-invalid @enderror" maxlength="5000">{{ old('admin_notes', $booking->adminNotes()) }}</textarea>
                        <div class="form-text">Only visible in admin. Never shown to the customer.</div>
                        @error('admin_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <p class="small text-muted mb-0">The submitted and confirmed dates follow the status automatically.</p>
                    </div>
                </div>

                <div class="d-flex flex-wrap justify-content-between gap-2 border-top mt-4 pt-4">
                    <a class="btn btn-outline-tee" href="{{ route('admin.bookings.index') }}">Back</a>
                    <button class="btn btn-tee" type="submit"><i class="bi bi-check2 me-1"></i>Save</button>
                </div>
            </form>
        @endcomponent

        @foreach ([
            ['address' => $booking->billingAddress, 'title' => 'Billing address', 'icon' => 'receipt'],
            ['address' => $booking->collectionAddress, 'title' => 'Collection address', 'icon' => 'truck'],
        ] as $block)
            @if ($block['address'])
                @component('admin.partials.panel', ['class' => 'p-4 mt-4'])
                    <div class="metric-icon mb-3"><i class="bi bi-{{ $block['icon'] }}"></i></div>
                    <h2 class="h5 fw-black mb-3">{{ $block['title'] }}</h2>
                    <div class="fw-bold">{{ $block['address']->full_name }}</div>
                    @if ($block['address']->company)
                        <div class="text-muted">{{ $block['address']->company }}</div>
                    @endif
                    <address class="text-muted mt-2 mb-3">
                        {!! implode('<br>', array_map('e', $block['address']->lines())) !!}
                    </address>
                    <dl class="row small mb-0">
                        @if ($block['address']->email)
                            <dt class="col-4 text-muted fw-normal">Email</dt>
                            <dd class="col-8"><a class="text-decoration-none" href="mailto:{{ $block['address']->email }}">{{ $block['address']->email }}</a></dd>
                        @endif
                        @if ($block['address']->phone)
                            <dt class="col-4 text-muted fw-normal">Phone</dt>
                            <dd class="col-8"><a class="text-decoration-none" href="tel:{{ $block['address']->phone }}">{{ $block['address']->phone }}</a></dd>
                        @endif
                        @if ($block['address']->mobile)
                            <dt class="col-4 text-muted fw-normal">Mobile</dt>
                            <dd class="col-8 mb-0"><a class="text-decoration-none" href="tel:{{ $block['address']->mobile }}">{{ $block['address']->mobile }}</a></dd>
                        @endif
                    </dl>
                @endcomponent
            @endif
        @endforeach

        @component('admin.partials.panel', ['class' => 'p-4 mt-4'])
            <div class="metric-icon mb-3"><i class="bi bi-credit-card"></i></div>
            <h2 class="h5 fw-black mb-3">Payments</h2>
            @if ($booking->payments->isEmpty())
                <p class="text-muted mb-0">No payment records yet. Provider integration arrives in the payments phase.</p>
            @else
                <ul class="list-unstyled mb-0">
                    @foreach ($booking->payments as $payment)
                        <li class="d-flex justify-content-between gap-3 border-bottom py-2">
                            <span>
                                <span class="fw-bold">{{ \App\Support\Money::format($payment->amount_pence) }}</span>
                                <span class="small text-muted d-block">{{ $payment->provider ?: 'Manual' }} &middot; {{ str($payment->status)->headline() }}</span>
                            </span>
                            <span class="small text-muted">{{ $payment->paid_at?->format('d M Y') ?? 'Pending' }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        @endcomponent
    </div>
</div>
@endsection
