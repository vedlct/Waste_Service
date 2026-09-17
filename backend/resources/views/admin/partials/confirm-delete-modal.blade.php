<div class="modal fade" id="delete-confirmation-modal" tabindex="-1" aria-labelledby="delete-confirmation-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <div>
                    <p class="small fw-bold text-uppercase text-danger mb-1">Confirm delete</p>
                    <h2 class="modal-title h5 fw-black" id="delete-confirmation-title">Delete this record?</h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-0" id="delete-confirmation-message">This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-tee" data-bs-dismiss="modal">Cancel</button>
                <form id="delete-confirmation-form" method="POST" action="#">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-3 fw-bold">
                        <i class="bi bi-trash3 me-1"></i>
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
