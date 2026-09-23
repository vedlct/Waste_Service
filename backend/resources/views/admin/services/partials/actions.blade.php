<div class="d-inline-flex gap-2">
    <a class="btn btn-sm btn-outline-tee" href="{{ route('admin.services.blocks.index', $service) }}" title="Content blocks"><i class="bi bi-layout-text-window-reverse"></i></a>
    <a class="btn btn-sm btn-outline-tee" href="{{ route('admin.services.edit', $service) }}" title="Edit service"><i class="bi bi-pencil-square"></i></a>
    <button
        class="btn btn-sm btn-outline-danger"
        type="button"
        title="Delete service"
        data-delete-action="{{ route('admin.services.destroy', $service) }}"
        data-delete-title="Delete {{ $service->name }}?"
        data-delete-message="The service is soft deleted, so its bookings and history stay intact."
    >
        <i class="bi bi-trash3"></i>
    </button>
</div>
