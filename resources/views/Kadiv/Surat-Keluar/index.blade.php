@extends('Kadiv.new_layouts.main')

@section('container')
    @if (session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <script>
                setTimeout(function() {
                    document.getElementById('logout-form').submit();
                }, 5000);
            </script>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Surat Keluar</h6>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <select id="filter-kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name_jenis_surat_keluar }}</option>
                        @endforeach
                    </select>
                    <small style="font-size: 10px">Kategori Surat</small>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="OutgoingLetterTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomer Surat Keluar</th>
                            <th>Nomer IDX</th>
                            <th>Kategori Surat Keluar</th>
                            <th>Tanggal Surat Keluar</th>
                            <th>Status Surat Keluar</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Surat Masuk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Nama Penerima Surat:</h6>
                            <p id="detailPenerima"></p>
                            <h6>Nomer Surat:</h6>
                            <p id="detailNomerSurat"></p>
                            <h6>Nomer IDX:</h6>
                            <p id="detailNomerIdx"></p>
                            <h6>Kategori Surat:</h6>
                            <p id="detailKategoriSurat"></p>
                            <h6>Tanggal Surat:</h6>
                            <p id="detailTanggalSurat"></p>
                            <h6>Status Surat:</h6>
                            <span id="detailStatusSurat" class="badge"></span>
                        </div>
                        <div class="col-md-6">
                            <h6>File Surat:</h6>
                            <iframe id="detailFileSurat" src="" width="100%" height="500px"></iframe>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Confirm Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div id="success-accepted" class="alert alert-success" style="display:none;">Surat Berhasil Disetujui
                </div>
                <div id="success-rejected" class="alert alert-danger" style="display:none;">Surat Berhasil Ditolak
                </div>
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmMessage">Apakah Anda yakin?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="confirmActionBtn" class="btn btn-primary">Ya</button>
                </div>
            </div>
        </div>
    </div>

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
                    <p id="errorMessageContent" class="fw-bold">AKSES DIBATASI.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" id="closeErrorModalFooter" class="btn btn-secondary"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script>
        $(document).ready(function() {
            var OutgoingLetterTable = $('#OutgoingLetterTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('surat-keluar-list-kadiv') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: function(d) {
                        d._token = '{{ csrf_token() }}';
                        d.kategori = $('#filter-kategori').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nomer_surat_keluar',
                        name: 'nomer_surat_keluar'
                    },
                    {
                        data: 'nomer_surat_keluark_idx',
                        name: 'nomer_surat_keluark_idx'
                    },
                    {
                        data: 'category.name_jenis_surat_keluar',
                        name: 'category.name_jenis_surat_keluar'
                    },
                    {
                        data: 'tanggal_surat_keluar',
                        name: 'tanggal_surat_keluar',
                        render: function(data, type, row) {
                            return moment(data).format('DD-MM-YYYY');
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            let badgeClass = '';
                            if (data === 'Sent') {
                                badgeClass = 'badge bg-info';
                            } else if (data === 'Archived') {
                                badgeClass = 'badge bg-success';
                            }
                            return `<span class="${badgeClass}">${data}</span>`;
                        }
                    },
                    {
                        data: 'uuid',
                        name: 'uuid',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            var showUrl = '{{ route('surat-keluar.show.kadiv', ':uuid') }}';
                            showUrl = showUrl.replace(':uuid', row.uuid);

                            // Determine which buttons to show based on status
                            let buttons = `
                        <button data-uuid="${row.uuid}" class="btn icon btn-sm btn-info showDetailBtn">
                            <i class="bi bi-eye"></i>
                        </button>`;
                            return buttons;
                        },
                    },
                ],
                autoWidth: false,
                drawCallback: function(settings) {
                    $('a').tooltip();
                }
            });

            $('#filter-kategori').change(function() {
                OutgoingLetterTable.ajax.reload();
            });

            $('#OutgoingLetterTable').on('click', '.showDetailBtn', function() {
                var uuid = $(this).data('uuid');
                var detailUrl = '{{ route('surat-keluar.show.kadiv', ':uuid') }}';
                detailUrl = detailUrl.replace(':uuid', uuid);

                $.ajax({
                    url: detailUrl,
                    type: 'GET',
                    success: function(response) {
                        $('#detailPengirim').text(response.nama_penerima);
                        $('#detailNomerSurat').text(response.nomer_surat_keluar);
                        $('#detailNomerIdx').text(response.nomer_surat_keluark_idx);
                        $('#detailKategoriSurat').text(response.category
                            .name_jenis_surat_masuk);
                        $('#detailTanggalSurat').text(moment(response.tanggal_surat_keluar)
                            .format('DD-MM-YYYY'));
                        // Set the status badge for status surat
                        let statusBadgeClass = '';
                        if (response.status === 'Sent') {
                            statusBadgeClass = 'badge bg-info';
                        } else if (response.status === 'Archived') {
                            statusBadgeClass = 'badge bg-success';
                        }
                        $('#detailStatusSurat').attr('class', statusBadgeClass).text(response
                            .status);
                        var fileUrl =
                            `{{ asset('storage') }}/${response.file.replace('public/', '')}`;
                        $('#detailFileSurat').attr('src', fileUrl);
                        $('#detailModal').modal('show');
                    },
                    error: function(xhr) {
                        alert('Gagal mengambil data detail.');
                    }
                });
            });
        });
    </script>
@endsection
