<!-- Send Surat Modal -->
<div class="modal fade" id="sendSuratModal" tabindex="-1" role="dialog" aria-labelledby="sendSuratModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="sendSuratForm" action="{{ route('send.surat') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="sendSuratModalLabel">Send Surat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="surat_id" id="surat_id">
                    <div class="form-group">
                        <label for="pegawai">Pegawai</label>
                        <select class="form-control" id="pegawai" name="pegawai">
                            <!-- Options will be loaded dynamically -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="judul">Judul</label>
                        <input type="text" class="form-control" id="judul" name="judul" required>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send Surat</button>
                </div>
            </form>
        </div>
    </div>
</div>
