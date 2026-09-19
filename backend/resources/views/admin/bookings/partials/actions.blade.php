<div class="d-inline-flex gap-2">
    <a class="btn btn-sm btn-outline-tee" href="{{ route('admin.bookings.show', $booking) }}" title="Open booking"><i class="bi bi-box-arrow-in-right"></i></a>
    <button
        class="btn btn-sm btn-outline-danger"
        type="button"
        title="Delete booking"
        data-delete-action="{{ route('admin.bookings.destroy', $booking) }}"
        data-delete-title="Delete {{ $booking->reference }}?"
        data-delete-message="The booking is soft deleted, so its reference and payment history stay resolvable."
    >
        <i class="bi bi-trash3"></i>
    </button>
</div>
