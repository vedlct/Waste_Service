<div class="d-inline-flex gap-2">
    <a class="btn btn-sm btn-outline-tee" href="{{ route('admin.enquiries.show', $enquiry) }}" title="Open enquiry"><i class="bi bi-box-arrow-in-right"></i></a>
    <button
        class="btn btn-sm btn-outline-danger"
        type="button"
        title="Delete enquiry"
        data-delete-action="{{ route('admin.enquiries.destroy', $enquiry) }}"
        data-delete-title="Delete the enquiry from {{ $enquiry->name }}?"
        data-delete-message="The enquiry is soft deleted, so it can be restored from the database if needed."
    >
        <i class="bi bi-trash3"></i>
    </button>
</div>
