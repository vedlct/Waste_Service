@php($blocked = $category->items_count > 0)
<div class="d-inline-flex gap-2">
    <a class="btn btn-sm btn-outline-tee" href="{{ route('admin.service-items.index', ['category' => $category->id]) }}" title="View items"><i class="bi bi-list-ul"></i></a>
    <a class="btn btn-sm btn-outline-tee" href="{{ route('admin.price-categories.edit', $category) }}" title="Edit category"><i class="bi bi-pencil-square"></i></a>
    <button
        class="btn btn-sm btn-outline-danger"
        type="button"
        title="{{ $blocked ? 'Move or delete its items first' : 'Delete category' }}"
        data-delete-action="{{ route('admin.price-categories.destroy', $category) }}"
        data-delete-title="Delete {{ $category->name }}?"
        data-delete-message="This permanently removes the price category."
        @disabled($blocked)
    >
        <i class="bi bi-trash3"></i>
    </button>
</div>
