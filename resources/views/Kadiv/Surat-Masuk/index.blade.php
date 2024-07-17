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
            <h6 class="m-0 font-weight-bold text-primary">Daftar Surat Masuk</h6>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <select id="filter-sifat" class="form-select">
                        <option value="">Semua Sifat</option>
                        @foreach ($sifats as $sifat)
                            <option value="{{ $sifat->id }}">{{ $sifat->name_sifat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select id="filter-kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name_jenis_surat_masuk }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="incomingLetterTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomer Surat</th>
                            <th>Nomer IDX</th>
                            <th>Kategori Surat</th>
                            <th>Sifat Surat</th>
                            <th>Tanggal Surat</th>
                            <th>Status Surat</th>
                            <th>Status Disposisi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Confirm Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div id="success-accepted" class="alert alert-success" style="display:none;">Surat Berhasil Disetujui</div>
                <div id="success-rejected" class="alert alert-success" style="display:none;">Surat Berhasil Ditolak</div>
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

    <script>
        $(document).ready(function() {
            var incomingLetterTable = $('#incomingLetterTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('surat-masuk-list-kadiv') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: function(d) {
                        d._token = '{{ csrf_token() }}';
                        d.sifat = $('#filter-sifat').val();
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
                        data: 'nomer_surat_masuk',
                        name: 'nomer_surat_masuk'
                    },
                    {
                        data: 'nomer_surat_masuk_idx',
                        name: 'nomer_surat_masuk_idx'
                    },
                    {
                        data: 'category.name_jenis_surat_masuk',
                        name: 'category.name_jenis_surat_masuk'
                    },
                    {
                        data: 'sifat.name_sifat',
                        name: 'sifat.name_sifat'
                    },
                    {
                        data: 'tanggal_surat_masuk',
                        name: 'tanggal_surat_masuk'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'disposition_status',
                        name: 'disposition_status'
                    },
                    {
                        data: 'uuid',
                        name: 'uuid',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return `
                            <button data-uuid="${row.uuid}" class="btn icon btn-sm btn-success acceptBtn">
                                <i class="bi bi-check"></i>
                            </button>
                            <button data-uuid="${row.uuid}" class="btn icon btn-sm btn-danger rejectBtn">
                                <i class="bi bi-x"></i>
                            </button>`;
                        }
                    }
                ],
                autoWidth: false,
                drawCallback: function(settings) {
                    $('a').tooltip();
                }
            });
            $('#filter-sifat, #filter-kategori').change(function() {
                incomingLetterTable.ajax.reload();
            });
            let actionUrl;
            let actionType;

            // Accept button click handler
            $('#incomingLetterTable').on('click', '.acceptBtn', function() {
                var uuid = $(this).data('uuid');
                actionUrl = '{{ route('surat-masuk.accept.kadiv', ':uuid') }}'.replace(':uuid', uuid);
                actionType = 'accept';
                $('#confirmMessage').text('Apakah Anda yakin ingin menerima surat ini?');
                $('#confirmModal').modal('show');
            });

            // Reject button click handler
            $('#incomingLetterTable').on('click', '.rejectBtn', function() {
                var uuid = $(this).data('uuid');
                actionUrl = '{{ route('surat-masuk.reject.kadiv', ':uuid') }}'.replace(':uuid', uuid);
                actionType = 'reject';
                $('#confirmMessage').text('Apakah Anda yakin ingin menolak surat ini?');
                $('#confirmModal').modal('show');
            });

            $('#confirmActionBtn').click(function() {
                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (actionType === 'accept') {
                            $('#success-accepted').text(response.message).show();
                        } else {
                            $('#success-rejected').text(response.message).show();
                        }
                        setTimeout(function() {
                                if (actionType === 'accept') {
                                    $('#success-accepted').fadeOut('slow', function() {
                                        $('#confirmModal').modal('hide');
                                    });
                                } else {
                                    $('#success-rejected').fadeOut('slow', function() {
                                        $('#confirmModal').modal('hide');
                                    });
                                }
                            },
                            3000
                            ); // Show the success message for 3 seconds before closing the modal
                        incomingLetterTable.ajax.reload();
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.message);
                        $('#confirmModal').modal('hide');
                    }
                });
            });
        });
    </script>
@endsection
