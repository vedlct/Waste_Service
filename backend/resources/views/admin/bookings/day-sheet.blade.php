@extends('layouts.print')

@section('title', 'Day sheet '.$date->format('D j M Y'))

@section('content')
<div class="no-print d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.bookings.index') }}"><i class="bi bi-arrow-left me-1"></i>Bookings</a>
    <form method="GET" action="{{ route('admin.bookings.day-sheet') }}" class="d-flex gap-2 align-items-center">
        <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.bookings.day-sheet', ['date' => $date->copy()->subDay()->toDateString()]) }}" title="Previous day"><i class="bi bi-chevron-left"></i></a>
        <input type="date" name="date" value="{{ $date->toDateString() }}" class="form-control form-control-sm" aria-label="Collection date">
        <button class="btn btn-secondary btn-sm" type="submit">Show</button>
        <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.bookings.day-sheet', ['date' => $date->copy()->addDay()->toDateString()]) }}" title="Next day"><i class="bi bi-chevron-right"></i></a>
    </form>
    <button type="button" class="btn btn-primary btn-sm" id="print-sheet"><i class="bi bi-printer me-1"></i>Print</button>
</div>

<header class="d-flex justify-content-between align-items-end border-bottom pb-2 mb-3">
    <div>
        <div class="label">Collection day sheet</div>
        <h1 class="h3 fw-bold mb-0">{{ $date->format('l j F Y') }}</h1>
    </div>
    <div class="text-end">
        <div>{{ $bookings->count() }} job(s)</div>
        <div class="fw-bold">Outstanding: {{ \App\Support\Money::format($amountToCollect) }}</div>
    </div>
</header>

@forelse ($bookings as $booking)
    @php($billing = $booking->billingAddress)
    @php($site = $booking->collectionAddress ?? $billing)
    @php($outstanding = $booking->outstandingPence())
    <section class="job">
        <div class="d-flex justify-content-between align-items-start gap-3">
            <div>
                <h2>{{ $loop->iteration }}. {{ $site?->postcode ?? 'No postcode' }} &middot; {{ $booking->reference }}</h2>
                <div>{{ $booking->statusLabel() }}{{ $booking->notice_minutes ? ' · '.$booking->notice_minutes.' min notice call' : '' }}</div>
            </div>
            <div class="text-end">
                <div class="label">{{ $booking->paymentOptionLabel() }}</div>
                @if ($outstanding > 0)
                    <div class="fs-5 fw-bold">Collect {{ \App\Support\Money::format($outstanding) }}</div>
                    @if ($booking->payment_option === 'pay_now')
                        <div class="small">Should have been paid online: check with the office first.</div>
                    @endif
                @else
                    <div class="fs-5 fw-bold">Paid</div>
                @endif
            </div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-sm-6">
                <div class="label">Collect from</div>
                <div>{!! implode('<br>', array_map('e', $site?->lines() ?? [])) !!}</div>
            </div>
            <div class="col-sm-6">
                <div class="label">Customer</div>
                <div class="fw-bold">{{ $billing?->full_name }}</div>
                @foreach (array_filter([$billing?->phone, $billing?->mobile]) as $number)
                    <div><a href="tel:{{ $number }}">{{ $number }}</a></div>
                @endforeach
            </div>
        </div>

        <div class="mt-2">
            <div class="label">To collect</div>
            <ul class="mb-0">
                @foreach ($booking->items->where('line_type', '!=', 'extra_charge') as $item)
                    <li>{{ $item->quantity }} x {{ $item->name }}</li>
                @endforeach
            </ul>
        </div>

        @if ($booking->restricted_access === 'yes' && $booking->access_restrictions)
            <div class="warning mt-2"><strong>Access:</strong> {{ $booking->access_restrictions }}</div>
        @endif
        @if ($booking->large_items)
            <div class="mt-2"><strong>Large items:</strong> {{ $booking->large_items }}</div>
        @endif
        @if ($booking->collection_notes)
            <div class="mt-2"><strong>Notes:</strong> {{ $booking->collection_notes }}</div>
        @endif
        @if ($booking->adminNotes())
            <div class="mt-2"><strong>Office notes:</strong> {{ $booking->adminNotes() }}</div>
        @endif
    </section>
@empty
    <p class="text-muted">No confirmed or submitted collections on this day.</p>
@endforelse
@endsection

@push('scripts')
<script @nonce>
    document.getElementById('print-sheet').addEventListener('click', function () {
        window.print();
    });
</script>
@endpush
