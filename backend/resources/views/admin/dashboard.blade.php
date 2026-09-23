@extends('layouts.app')

@section('title', 'Dashboard')
@section('eyebrow', 'Overview')
@section('page-title', 'Dashboard')
@php($breadcrumbs = [['label' => 'Dashboard']])

@push('styles')
<style>
    .attention-card { display:block; text-decoration:none; color:inherit; transition:transform .15s ease, box-shadow .15s ease; }
    .attention-card:hover { transform:translateY(-2px); box-shadow:0 20px 44px rgba(17,34,77,.12); color:inherit; }
    .attention-card.needs-action { border-color:rgba(244,185,66,.7); background:#fffbef; }
    .attention-card.needs-action .metric-icon { color:#8a5b00; background:#fff0c7; }
</style>
@endpush

@section('content')
@if (count($attention) > 0)
    <div class="d-flex align-items-baseline justify-content-between mb-2">
        <h2 class="h5 fw-black mb-0">Waiting on you</h2>
        <span class="small text-muted">Click a card to open the list that resolves it.</span>
    </div>
    <div class="row g-3">
        @foreach ($attention as $card)
            <div class="col-12 col-sm-6 col-xl-4">
                <a href="{{ $card['url'] }}" class="metric-card attention-card {{ $card['value'] > 0 ? 'needs-action' : '' }}">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="metric-icon"><i class="bi bi-{{ $card['icon'] }}"></i></div>
                        @if ($card['value'] > 0)
                            <span class="status-pill role">Needs action</span>
                        @else
                            <span class="status-pill active"><i class="bi bi-check2"></i>Clear</span>
                        @endif
                    </div>
                    <div class="display-6 fw-black mt-3">{{ number_format($card['value']) }}</div>
                    <div class="fw-bold">{{ $card['label'] }}</div>
                    <div class="small text-muted">{{ $card['hint'] }}</div>
                </a>
            </div>
        @endforeach
    </div>
@endif

<div class="row g-4 mt-1">
    @if ($upcomingBookings !== null)
        <div class="{{ $openEnquiries !== null ? 'col-xl-7' : 'col-12' }}">
            @component('admin.partials.table-card', ['class' => 'p-4 h-100'])
                @slot('header')
                    @component('admin.partials.page-header', [
                        'eyebrow' => 'Next '.$upcomingDays.' days',
                        'title' => 'Upcoming collections',
                        'class' => 'mb-3',
                    ])
                        @slot('actions')
                            <a class="btn btn-outline-tee btn-sm" href="{{ route('admin.bookings.index', ['scope' => 'upcoming']) }}">All upcoming</a>
                        @endslot
                    @endcomponent
                @endslot

                @if ($upcomingBookings->isEmpty())
                    @include('admin.partials.empty-state', [
                        'icon' => 'calendar2-check',
                        'title' => 'Nothing booked yet',
                        'message' => 'Collections in the next '.$upcomingDays.' days will appear here.',
                    ])
                @else
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Booking</th>
                                <th>Postcode</th>
                                <th>Status</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($upcomingBookings as $booking)
                                <tr>
                                    <td class="text-nowrap">
                                        <div class="fw-bold">{{ $booking->collection_date?->format('D j M') }}</div>
                                        @if ($booking->is_saturday_collection)
                                            <span class="small text-muted">Saturday</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="fw-bold text-decoration-none" href="{{ route('admin.bookings.show', $booking) }}">{{ $booking->reference }}</a>
                                        <div class="small text-muted">{{ $booking->billingAddress?->full_name }}</div>
                                    </td>
                                    <td>{{ $booking->collectionAddress?->postcode ?? $booking->billingAddress?->postcode }}</td>
                                    <td>
                                        @include('admin.bookings.partials.status', [
                                            'label' => $booking->statusLabel(),
                                            'badge' => config('bookings.statuses.'.$booking->status.'.badge', 'muted'),
                                        ])
                                    </td>
                                    <td class="text-end fw-bold">{{ \App\Support\Money::format($booking->total_pence) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @endcomponent
        </div>
    @endif

    @if ($openEnquiries !== null)
        <div class="{{ $upcomingBookings !== null ? 'col-xl-5' : 'col-12' }}">
            @component('admin.partials.table-card', ['class' => 'p-4 h-100'])
                @slot('header')
                    @component('admin.partials.page-header', [
                        'eyebrow' => 'Website contact form',
                        'title' => 'Open enquiries',
                        'class' => 'mb-3',
                    ])
                        @slot('actions')
                            <a class="btn btn-outline-tee btn-sm" href="{{ route('admin.enquiries.index') }}">All enquiries</a>
                        @endslot
                    @endcomponent
                @endslot

                @if ($openEnquiries->isEmpty())
                    @include('admin.partials.empty-state', [
                        'icon' => 'inbox',
                        'title' => 'Inbox clear',
                        'message' => 'No enquiries are waiting for a reply.',
                    ])
                @else
                    <ul class="list-unstyled mb-0">
                        @foreach ($openEnquiries as $enquiry)
                            <li class="d-flex justify-content-between gap-3 border-bottom py-2">
                                <span class="min-w-0">
                                    <a class="fw-bold text-decoration-none" href="{{ route('admin.enquiries.show', $enquiry) }}">{{ $enquiry->name }}</a>
                                    <span class="small text-muted d-block text-truncate">
                                        {{ $enquiry->service_label ?: 'General enquiry' }}
                                        &middot; {{ $enquiry->assignee?->name ?? 'Unassigned' }}
                                    </span>
                                </span>
                                <span class="small text-muted text-nowrap">{{ $enquiry->created_at?->diffForHumans() }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            @endcomponent
        </div>
    @endif
</div>

<h2 class="h5 fw-black mt-4 mb-2">Catalogue</h2>
<div class="row g-3">
    @foreach ($catalogue as $stat)
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card">
                <div class="metric-icon"><i class="bi bi-{{ $stat['icon'] }}"></i></div>
                <div class="display-6 fw-black mt-3">{{ number_format($stat['value']) }}</div>
                <div class="text-muted fw-semibold">{{ $stat['label'] }}</div>
            </div>
        </div>
    @endforeach
</div>
@endsection
