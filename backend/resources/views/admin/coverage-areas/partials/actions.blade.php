<div class="d-inline-flex gap-2">
    <a class="btn btn-sm btn-outline-tee" href="{{ route('admin.coverage-areas.edit', $area) }}" title="Edit area"><i class="bi bi-pencil-square"></i></a>
    <button
        class="btn btn-sm btn-outline-danger"
        type="button"
        title="Delete area"
        data-delete-action="{{ route('admin.coverage-areas.destroy', $area) }}"
        data-delete-title="Delete {{ $area->name }}?"
        data-delete-message="This permanently removes the coverage area."
    >
        <i class="bi bi-trash3"></i>
    </button>
</div>
