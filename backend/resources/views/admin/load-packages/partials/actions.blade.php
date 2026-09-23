<div class="d-inline-flex gap-2">
    <a class="btn btn-sm btn-outline-tee" href="{{ route('admin.load-packages.edit', $package) }}" title="Edit package"><i class="bi bi-pencil-square"></i></a>
    <button
        class="btn btn-sm btn-outline-danger"
        type="button"
        title="Delete package"
        data-delete-action="{{ route('admin.load-packages.destroy', $package) }}"
        data-delete-title="Delete {{ $package->name }}?"
        data-delete-message="The package is soft deleted, so existing bookings keep their price snapshot."
    >
        <i class="bi bi-trash3"></i>
    </button>
</div>
