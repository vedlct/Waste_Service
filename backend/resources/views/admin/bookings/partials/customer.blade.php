@php($address = $booking->billingAddress)
<div>
    <div class="fw-bold">{{ $address?->full_name ?: 'Unknown customer' }}</div>
    @if ($address?->email)
        <a class="small text-decoration-none d-block" href="mailto:{{ $address->email }}">{{ $address->email }}</a>
    @endif
    @if ($address?->postcode)
        <div class="small text-muted">{{ $address->postcode }}</div>
    @endif
</div>
