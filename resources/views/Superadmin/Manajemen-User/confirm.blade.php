<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Delete</h5>
                <button type="button" id="closeConfirmModal" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="delete-success-message" class="alert alert-success" style="display:none;">Data Berhasil Dihapus
                </div>
                <p>Are you sure you want to delete this user?</p>
                <p><strong>Name:</strong> <span id="deleteUserName"></span></p>
                <p><strong>Email:</strong> <span id="deleteUserEmail"></span></p>
                <p><strong>Username:</strong> <span id="deleteUserUsername"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" id="cancelDelete" class="btn btn-secondary"
                    data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
