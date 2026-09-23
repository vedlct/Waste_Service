<div class="d-inline-flex gap-2">
    <a class="btn btn-sm btn-outline-tee" href="{{ route('admin.service-items.edit', $item) }}" title="Edit item"><i class="bi bi-pencil-square"></i></a>
    <button
        class="btn btn-sm btn-outline-danger"
        type="button"
        title="Delete item"
        data-delete-action="{{ route('admin.service-items.destroy', $item) }}"
        data-delete-title="Delete {{ $item->name }}?"
        data-delete-message="The item is soft deleted, so existing booking lines keep their price snapshot."
    >
        <i class="bi bi-trash3"></i>
    </button>
</div>
