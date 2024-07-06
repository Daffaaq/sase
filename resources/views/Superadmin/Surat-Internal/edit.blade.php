<div class="modal fade" id="editSuratModal" tabindex="-1" aria-labelledby="suratModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="suratModalLabel">Edit Surat Internal</h5>
                <button type="button" id="closemodal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-message text-center p-2">
                    <div class="alert alert-warning">Mohon untuk mengunggah file dalam format PDF atau DOC (disarankan PDF)</div>
                </div>
                <form id="suratFormEdit" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="suratId">
                    <div id="success-message" class="alert alert-success" style="display:none;">Data Berhasil Dikirim</div>
                    <div id="error-message" class="alert alert-danger" style="display:none;"></div>
                    <div id="file-size-warning" class="alert alert-danger" style="display:none;">File size exceeds 5MB. Please upload a smaller file.</div>
                    <div id="file-type-warning" class="alert alert-danger" style="display:none;">Invalid file type. Please upload a PDF or DOC file.</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="file" class="form-label">File Surat</label>
                                <input type="file" class="form-control" id="file" name="file" accept=".pdf,.doc,.docx">
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                        <div class="col-md-6">
                            <div id="file-preview" style="display: none;">
                                <h5>File Preview:</h5>
                                <embed id="file-preview-content" style="width: 100%; height: 400px;" type="application/pdf">
                                <div id="word-preview-content" style="width: 100%; height: 400px; overflow-y: auto; white-space: pre-wrap;"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>