<div class="d-inline-flex gap-2">
    <a class="btn btn-sm btn-outline-tee" href="{{ route('admin.extra-charges.edit', $charge) }}" title="Edit charge"><i class="bi bi-pencil-square"></i></a>
    <button
        class="btn btn-sm btn-outline-danger"
        type="button"
        title="Delete charge"
        data-delete-action="{{ route('admin.extra-charges.destroy', $charge) }}"
        data-delete-title="Delete {{ $charge->name }}?"
        data-delete-message="This permanently removes the extra charge."
    >
        <i class="bi bi-trash3"></i>
    </button>
</div>
