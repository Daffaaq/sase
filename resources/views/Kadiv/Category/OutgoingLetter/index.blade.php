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
                }, 5000); // 5000 milliseconds = 5 seconds
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
            <h6 class="m-0 font-weight-bold text-primary">Kategori Surat Keluar Management</h6>
        </div>
        <div class="card-body">
            <button id="createCategoryBtn" class="btn btn-success float-right mb-3">
                <i class="fas fa-plus"></i> Create Kategori Surat Keluar
            </button>
            <div class="table-responsive">
                <table class="table table-bordered" id="categoryTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori Surat Keluar</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">Create Kategori Surat Keluar</h5>
                    <button type="button" id="closeCategoryModal" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="categoryForm" action="{{ route('Kategori-Surat-Keluar-Kadiv.store') }}" method="POST">
                        @csrf
                        <div id="success-message" class="alert alert-success" style="display:none;">Data Berhasil Dikirim
                        </div>
                        <div id="error-message" class="alert alert-danger" style="display:none;"></div>
                        <div class="mb-3">
                            <label for="name_jenis_surat_keluar" class="form-label">Nama Kategori Surat Keluar</label>
                            <input type="text" class="form-control" id="name_jenis_surat_keluar"
                                name="name_jenis_surat_keluar" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
                    <p>Are you sure you want to delete this category?</p>
                    <p><strong>Nama Kategori Surat:</strong> <span id="deleteCategoryName"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="cancelDelete" class="btn btn-secondary"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
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

    <script>
        $(document).ready(function() {
            var dataMaster = $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('kategori-surat-keluar-list-kadiv') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}'
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name_jenis_surat_keluar',
                        name: 'name_jenis_surat_keluar'
                    },
                    {
                        data: 'uuid',
                        name: 'uuid',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return `
                                <button data-uuid="${row.uuid}" class="btn icon btn-sm btn-warning editCategoryBtn">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button data-uuid="${row.uuid}" data-name="${row.name_jenis_surat_keluar}" class="btn icon btn-sm btn-danger deleteCategoryBtn">
                                    <i class="bi bi-trash"></i>
                                </button>`;
                        }
                    }
                ],
                autoWidth: false,
                drawCallback: function(settings) {
                    $('a').tooltip();
                }
            });

            $('#createCategoryBtn').click(function() {
                $('#categoryForm')[0].reset();
                $('#categoryModalLabel').text('Create Kategori Surat Keluar');
                $('#categoryModal').modal('show');
            });

            $('#categoryTable').on('click', '.editCategoryBtn', function() {
                var uuid = $(this).data('uuid');
                var url = '{{ route('Kategori-Surat-Keluar-Kadiv.edit', ':uuid') }}';
                url = url.replace(':uuid', uuid);

                $.get(url, function(response) {
                    var data = response.data;
                    $('#categoryModalLabel').text('Edit Kategori Surat Keluar');
                    var updateUrl = '{{ route('Kategori-Surat-Keluar-Kadiv.update', ':uuid') }}';
                    updateUrl = updateUrl.replace(':uuid', uuid);
                    $('#categoryForm').attr('action', updateUrl);
                    $('#categoryForm').append('<input type="hidden" name="_method" value="PUT">');
                    $('#name_jenis_surat_keluar').val(data.name_jenis_surat_keluar);
                    $('#categoryModal').modal('show');
                }).fail(function(jqXHR) {
                    $('#errorModal').modal('show');
                });
            });

            $('#categoryTable').on('click', '.deleteCategoryBtn', function() {
                var uuid = $(this).data('uuid');
                var name = $(this).data('name');

                // Populate modal with category data
                $('#deleteCategoryName').text(name);
                $('#confirmDeleteBtn').data('uuid', uuid);
                $('#confirmModal').modal('show');
            });

            $('#confirmDeleteBtn').click(function() {
                var uuid = $(this).data('uuid');
                var url = '{{ route('Kategori-Surat-Keluar-Kadiv.destroy', ':uuid') }}';
                url = url.replace(':uuid', uuid);
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        $('#delete-success-message').text(result.message).show();
                        setTimeout(function() {
                                $('#delete-success-message').fadeOut('slow', function() {
                                    $('#confirmModal').modal('hide');
                                });
                            },
                            3000
                        ); // Show the success message for 3 seconds before closing the modal
                        dataMaster.ajax.reload();
                    },
                    error: function() {
                        $('#errorModal').modal('show');
                    }
                });
            });
            $('#categoryForm').submit(function(e) {
                e.preventDefault();
                var actionUrl = $(this).attr('action');
                $.ajax({
                    url: actionUrl,
                    type: $(this).find('input[name="_method"]').val() || 'POST',
                    data: $(this).serialize(),
                    success: function(result) {
                        $('#success-message').text(result.message).show();
                        setTimeout(function() {
                                $('#success-message').fadeOut('slow', function() {
                                    $('#categoryModal').modal('hide');
                                });
                            },
                            3000
                        ); // Show the success message for 5 seconds before closing the modal
                        dataMaster.ajax.reload();
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        $.each(errors, function(key, value) {
                            errorMessage += value + '\n';
                        });
                        $('#error-message').html(errorMessage).show();
                        setTimeout(function() {
                            $('#error-message').fadeOut('slow');
                        }, 5000);
                    }
                });
            });

            $('#closeCategoryModal').click(function() {
                $('#categoryModal').modal('hide');
            });

            $('#closeConfirmModal').click(function() {
                $('#confirmModal').modal('hide');
            });

            $('#cancelDelete').click(function() {
                $('#confirmModal').modal('hide');
            });

            $('#closeErrorModalFooter').click(function() {
                $('#errorModal').modal('hide');
            });

            $('#closeErrorModal').click(function() {
                $('#errorModal').modal('hide');
            });
        });
    </script>
@endsection
