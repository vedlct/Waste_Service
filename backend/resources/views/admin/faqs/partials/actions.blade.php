<div class="d-inline-flex gap-2">
    <a class="btn btn-sm btn-outline-tee" href="{{ route('admin.faqs.edit', $faq) }}" title="Edit FAQ"><i class="bi bi-pencil-square"></i></a>
    <button
        class="btn btn-sm btn-outline-danger"
        type="button"
        title="Delete FAQ"
        data-delete-action="{{ route('admin.faqs.destroy', $faq) }}"
        data-delete-title="Delete this FAQ?"
        data-delete-message="This permanently removes the question and answer."
    >
        <i class="bi bi-trash3"></i>
    </button>
</div>
