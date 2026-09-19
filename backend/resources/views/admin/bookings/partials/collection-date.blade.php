<div>
    <div class="fw-bold">{{ $booking->collection_date?->format('d M Y') ?? 'Not set' }}</div>
    @if ($booking->is_saturday_collection)
        <span class="status-pill role"><i class="bi bi-calendar-event"></i>Saturday</span>
    @endif
</div>
