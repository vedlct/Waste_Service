@php($blocked = $category->services_count > 0 || $category->children_count > 0)
<div class="d-inline-flex gap-2">
    <a class="btn btn-sm btn-outline-tee" href="{{ route('admin.service-categories.edit', $category) }}" title="Edit category"><i class="bi bi-pencil-square"></i></a>
    <button
        class="btn btn-sm btn-outline-danger"
        type="button"
        title="{{ $blocked ? 'Used by services or sub categories' : 'Delete category' }}"
        data-delete-action="{{ route('admin.service-categories.destroy', $category) }}"
        data-delete-title="Delete {{ $category->name }}?"
        data-delete-message="This permanently removes the service category."
        @disabled($blocked)
    >
        <i class="bi bi-trash3"></i>
    </button>
</div>
