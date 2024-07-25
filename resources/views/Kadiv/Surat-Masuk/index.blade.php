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
                    <small style="font-size: 10px">Sifat Surat</small>
                </div>
                <div class="col-md-4">
                    <select id="filter-kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name_jenis_surat_masuk }}</option>
                        @endforeach
                    </select>
                    <small style="font-size: 10px">Kategori Surat</small>
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
                            <th>Status Balasan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
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
                            <h6>Nama Pengirim Surat:</h6>
                            <p id="detailPengirim"></p>
                            <h6>Nomer Surat:</h6>
                            <p id="detailNomerSurat"></p>
                            <h6>Nomer IDX:</h6>
                            <p id="detailNomerIdx"></p>
                            <h6>Kategori Surat:</h6>
                            <p id="detailKategoriSurat"></p>
                            <h6>Sifat Surat:</h6>
                            <p id="detailSifatSurat"></p>
                            <h6>Tanggal Surat:</h6>
                            <p id="detailTanggalSurat"></p>
                            <h6>Status Surat:</h6>
                            <span id="detailStatusSurat" class="badge"></span>
                            <h6>Status Disposisi:</h6>
                            <span id="detailStatusDisposisi" class="badge"></span>
                            <h6>Status Balasan:</h6>
                            <span id="detailStatusBalasan" class="badge"></span>
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
                    <button type="button" id="closeErrorModal" class="btn-close btn-close-white"
                        data-bs-dismiss="modal" aria-label="Close"></button>
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

    <!-- Upload Outgoing Letter Modal -->
    <div class="modal fade" id="uploadOutgoingLetterModal" tabindex="-1"
        aria-labelledby="uploadOutgoingLetterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="uploadOutgoingLetterForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadOutgoingLetterModalLabel">Upload Outgoing Letter</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan">
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">File</label>
                            <input type="file" class="form-control" id="file" name="file" required>
                        </div>
                        <div class="mb-3">
                            <label for="category_surat_id" class="form-label">Category Surat ID</label>
                            <select class="form-select" id="category_surat_id" name="category_surat_id" required>
                                <option value="" disabled selected>Pilih Kategori Surat</option>
                                @foreach ($categoryOutgoingLetters as $category)
                                    <option value="{{ $category->id }}">{{ $category->name_jenis_surat_keluar }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button id="sendActionBtn" type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
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
                        name: 'tanggal_surat_masuk',
                        render: function(data, type, row) {
                            return moment(data).format('DD-MM-YYYY');
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            let badgeClass = '';
                            if (data === 'Pending') {
                                badgeClass = 'badge bg-info';
                            } else if (data === 'Approved') {
                                badgeClass = 'badge bg-success';
                            } else if (data === 'Rejected') {
                                badgeClass = 'badge bg-danger';
                            }
                            return `<span class="${badgeClass}">${data}</span>`;
                        }
                    },
                    {
                        data: 'disposition_status',
                        name: 'disposition_status',
                        render: function(data, type, row) {
                            let badgeClass = '';
                            if (data === 'Pending') {
                                badgeClass = 'badge bg-info';
                            } else if (data === 'Disposition Sent') {
                                badgeClass = 'badge bg-success';
                            }
                            return `<span class="${badgeClass}">${data}</span>`;
                        }
                    },
                    {
                        data: 'status_sent',
                        name: 'status_sent',
                        render: function(data, type, row) {
                            if (data) {
                                return `<span class="badge bg-success">Sent</span>`;
                            } else {
                                return `<span class="badge bg-warning">Not Sent</span>`;
                            }
                        }
                    },
                    {
                        data: 'uuid',
                        name: 'uuid',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            var showUrl = '{{ route('surat-masuk.show.kadiv', ':uuid') }}';
                            showUrl = showUrl.replace(':uuid', row.uuid);

                            // Determine which buttons to show based on status
                            let buttons = `
                        <button data-uuid="${row.uuid}" class="btn icon btn-sm btn-info showDetailBtn">
                            <i class="bi bi-eye"></i>
                        </button>`;

                            if (!row.status_sent) {
                                if (row.status === 'Pending') {
                                    buttons += `
                                <button data-uuid="${row.uuid}" class="btn icon btn-sm btn-success acceptBtn">
                                    <i class="bi bi-check"></i>
                                </button>
                                <button data-uuid="${row.uuid}" class="btn icon btn-sm btn-danger rejectBtn">
                                    <i class="bi bi-x"></i>
                                </button>`;
                                } else if (row.status === 'Approved') {
                                    buttons += `
                                <button data-uuid="${row.uuid}" class="btn icon btn-sm btn-danger rejectBtn">
                                    <i class="bi bi-x"></i>
                                </button>
                                <button data-uuid="${row.uuid}" class="btn icon btn-sm btn-primary sendLetterBtn">
                                    <i class="bi bi-send"></i>
                                </button>`;
                                } else if (row.status === 'Rejected') {
                                    buttons += `
                                <button data-uuid="${row.uuid}" class="btn icon btn-sm btn-success acceptBtn">
                                    <i class="bi bi-check"></i>
                                </button>`;
                                }
                            }

                            return buttons;
                        },
                    },
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

            // Show detail button click handler
            $('#incomingLetterTable').on('click', '.showDetailBtn', function() {
                var uuid = $(this).data('uuid');
                var detailUrl = '{{ route('surat-masuk.show.kadiv', ':uuid') }}';
                detailUrl = detailUrl.replace(':uuid', uuid);

                $.ajax({
                    url: detailUrl,
                    type: 'GET',
                    success: function(response) {
                        $('#detailPengirim').text(response.nama_pengirim);
                        $('#detailNomerSurat').text(response.nomer_surat_masuk);
                        $('#detailNomerIdx').text(response.nomer_surat_masuk_idx);
                        $('#detailKategoriSurat').text(response.category
                            .name_jenis_surat_masuk);
                        $('#detailSifatSurat').text(response.sifat.name_sifat);
                        $('#detailTanggalSurat').text(moment(response.tanggal_surat_masuk)
                            .format('DD-MM-YYYY'));

                        // Set the status badge for status surat
                        let statusBadgeClass = '';
                        if (response.status === 'Pending') {
                            statusBadgeClass = 'badge bg-info';
                        } else if (response.status === 'Approved') {
                            statusBadgeClass = 'badge bg-success';
                        } else if (response.status === 'Rejected') {
                            statusBadgeClass = 'badge bg-danger';
                        }
                        $('#detailStatusSurat').attr('class', statusBadgeClass).text(response
                            .status);

                        // Set the status badge for status disposisi
                        let dispositionBadgeClass = '';
                        if (response.disposition_status === 'Pending') {
                            dispositionBadgeClass = 'badge bg-info';
                        } else if (response.disposition_status === 'Disposition Sent') {
                            dispositionBadgeClass = 'badge bg-success';
                        }
                        $('#detailStatusDisposisi').attr('class', dispositionBadgeClass).text(
                            response.disposition_status);

                        // Set the status badge for status balasan
                        let balasanBadgeClass = '';
                        if (response.outgoing_letter) {
                            balasanBadgeClass = 'badge bg-success';
                            $('#detailStatusBalasan').attr('class', balasanBadgeClass).text(
                                'Sent');
                        } else {
                            balasanBadgeClass = 'badge bg-warning';
                            $('#detailStatusBalasan').attr('class', balasanBadgeClass).text(
                                'Not Sent');
                        }

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

            // Send letter button click handler
            $('#incomingLetterTable').on('click', '.sendLetterBtn', function() {
                var uuid = $(this).data('uuid');
                var sendUrl = '{{ route('outgoing-letter.upload', ':uuid') }}';
                sendUrl = sendUrl.replace(':uuid', uuid);
                $('#uploadOutgoingLetterForm').attr('action', sendUrl);
                $('#uploadOutgoingLetterModal').modal('show');
            });

            $('#uploadOutgoingLetterForm').submit(function(e) {
                e.preventDefault(); // Prevent the default form submission

                var formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#uploadOutgoingLetterModal').modal('hide');
                        alert(response.message);
                        incomingLetterTable.ajax.reload();
                    },
                    error: function(xhr) {
                        var response = xhr.responseJSON;
                        if (response) {
                            var errorMessages = response.message;
                            if (response.errors) {
                                for (var key in response.errors) {
                                    errorMessages += '\n' + response.errors[key].join('\n');
                                }
                            }
                            alert(errorMessages);
                        } else {
                            alert('An unknown error occurred.');
                        }
                    }
                });
            });
        });
    </script>
@endsection
