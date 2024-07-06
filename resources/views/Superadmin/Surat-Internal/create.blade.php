<div class="modal fade" id="suratModal" tabindex="-1" aria-labelledby="suratModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="suratModalLabel">Upload Surat Internal</h5>
                <button type="button" id="closemodal" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-message text-center p-2">
                    <div class="alert alert-warning">Mohon untuk mengunggah file dalam format PDF atau DOC (disarankan
                        PDF)</div>
                </div>
                <form id="suratForm" action="{{ route('manajemen-letter-internal-store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div id="success-message" class="alert alert-success" style="display:none;">Data Berhasil Dikirim
                    </div>
                    <div id="error-message" class="alert alert-danger" style="display:none;"></div>
                    <div id="file-size-warning" class="alert alert-danger" style="display:none;">File size exceeds 5MB.
                        Please upload a smaller file.</div>
                    <div id="file-type-warning" class="alert alert-danger" style="display:none;">Invalid file type.
                        Please upload a PDF or DOC file.</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="file" class="form-label">File Surat</label>
                                <input type="file" class="form-control" id="file" name="file"
                                    accept=".pdf,.doc,.docx" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                        <div class="col-md-6">
                            <div id="file-preview" style="display: none;">
                                <h5>File Preview:</h5>
                                <embed id="file-preview-content" style="width: 100%; height: 400px;"
                                    type="application/pdf">
                                <div id="word-preview-content"
                                    style="width: 100%; height: 400px; overflow-y: auto; white-space: pre-wrap;"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#file').on('change', function() {
            var file = this.files[0];
            var maxSize = 5 * 1024 * 1024; // 5MB in bytes
            var validTypes = ['application/pdf', 'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ]; // Valid MIME types

            console.log("Selected file size: " + file.size); // Debugging log
            console.log("Selected file type: " + file.type); // Debugging log

            if (file) {
                if (file.size > maxSize) {
                    $('#file-size-warning').show();
                    $('#file-type-warning').hide();
                    $('#file-preview').hide();
                    $('#file-preview-content').attr('src', '');
                    $('#word-preview-content').hide();
                    return;
                } else {
                    $('#file-size-warning').hide();
                }

                if (!validTypes.includes(file.type)) {
                    $('#file-type-warning').show();
                    $('#file-size-warning').hide();
                    $('#file-preview').hide();
                    $('#file-preview-content').attr('src', '');
                    $('#word-preview-content').hide();
                    return;
                } else {
                    $('#file-type-warning').hide();
                }

                var fileURL = URL.createObjectURL(file);
                console.log("File URL: " + fileURL); // Debugging log

                if (file.type === 'application/pdf') {
                    $('#file-preview-content').attr('src', fileURL).show();
                    $('#word-preview-content').hide();
                } else {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#word-preview-content').text(e.target.result).show();
                        $('#file-preview-content').hide();
                    };
                    reader.onerror = function(error) {
                        console.log("FileReader error: " + error); // Debugging log
                    };
                    reader.readAsText(file);
                }

                $('#file-preview').show();
            } else {
                $('#file-preview').hide();
                $('#file-preview-content').attr('src', '');
                $('#word-preview-content').hide();
            }
        });
    });
</script>
