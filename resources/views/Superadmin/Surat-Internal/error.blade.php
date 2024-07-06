<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="errorModalLabel">Error</h5>
                <button type="button" id="closeErrorModal" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p id="errorMessageContent" class="fw-bold">File Not Found.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" id="closeErrorModalFooter" class="btn btn-secondary"
                    data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS for the Modal -->
<style>
    .modal-header.bg-danger {
        background-color: #dc3545;
    }

    .btn-close-white {
        filter: invert(1);
    }

    .modal-body .alert {
        margin-bottom: 1rem;
    }

    .modal-body p {
        font-size: 1.1rem;
        color: #dc3545;
    }

    .modal-footer .btn {
        padding: 0.5rem 2rem;
    }
</style>
