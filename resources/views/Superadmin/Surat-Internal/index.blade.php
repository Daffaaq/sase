@extends('Superadmin.layouts.index')

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
            <h6 class="m-0 font-weight-bold text-primary">Surat Internal Management</h6>
        </div>
        <div class="card-body">
            <button id="createSuratBtn" class="btn btn-success float-right mb-3">
                <i class="fas fa-plus"></i> Create Surat Internal
            </button>
            <div class="table-responsive">
                <table class="table table-bordered" id="suratTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 15%;">Nomor Surat</th>
                            <th style="width: 15%;">Nomor Surat IDX</th>
                            <th style="width: 20%;">Nama File</th>
                            <th style="width: 15%;">Status File</th>
                            <th style="width: 30%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('Superadmin.Surat-Internal.create')
    @include('Superadmin.Surat-Internal.confirm')
    @include('Superadmin.Surat-Internal.error')
    @include('Superadmin.Surat-Internal.edit')

    <script>
        $(document).ready(function() {
            var dataMaster = $('#suratTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('sin.list') }}',
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
                        data: 'no_surat',
                        name: 'no_surat'
                    },
                    {
                        data: 'no_surat_idx',
                        name: 'no_surat_idx'
                    },
                    {
                        data: 'nama_file',
                        name: 'nama_file'
                    },
                    {
                        data: 'status_letter',
                        name: 'status_letter',
                        render: function(data, type, row, meta) {
                            return '<span class="badge badge-info">' + data + '</span>';
                        }
                    },
                    {
                        data: 'file',
                        name: 'file',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            var filePath = '/storage/' + data; // Adjust this path as necessary
                            return `
                        <button data-id="${row.id}" class="btn btn-xs btn-warning editSuratBtn" title="Edit Surat">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button data-id="${row.id}" class="btn btn-xs btn-success sendSuratBtn" title="Send Surat">
                            <i class="fa fa-paper-plane"></i>
                        </button>
                        <button data-id="${row.id}" class="btn btn-xs btn-danger archiveSuratBtn" title="Archive Surat">
                            <i class="fa fa-archive"></i>
                        </button>
                        <button onclick="viewFile('${filePath}')" class="btn btn-xs btn-info" title="View File">
                            <i class="fa fa-eye"></i>
                        </button>
                        <a href="${filePath}" target="_blank" class="btn btn-xs btn-secondary" title="Download File">
                            <i class="fa fa-download"></i>
                        </a>`;
                        }
                    }
                ],
                autoWidth: false,
                drawCallback: function(settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            $('#createSuratBtn').click(function() {
                $('#suratForm')[0].reset();
                $('#suratModalLabel').text('Create Surat Internal');
                $('#suratModal').modal('show');
            });

            $('#suratTable').on('click', '.editSuratBtn', function() {
                var id = $(this).data('id');
                $.get('/dashboardSuperadmin/surat-internal/edit/' + id, function(response) {
                    var data = response.data;
                    // Set ID surat di input hidden
                    $('#suratId').val(id);
                    // Update URL action dari form dengan ID surat
                    $('#suratFormEdit').attr('action',
                        '/dashboardSuperadmin/surat-internal/update/' + id);
                    // Buka modal
                    $('#editSuratModal').modal('show');
                }).fail(function(jqXHR) {
                    $('#errorModal').modal('show');
                });
            });


            $('#suratTable').on('click', '.archiveSuratBtn', function() {
                var id = $(this).data('id');
                // Populate modal with surat data if needed
                $('#confirmDeleteBtn').data('id', id);
                $('#confirmModal').modal('show');
            });

            $('#confirmDeleteBtn').click(function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '/dashboardSuperadmin/Surat/destroy/' + id,
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

            $('#suratForm').submit(function(e) {
                e.preventDefault();
                var actionUrl = $(this).attr('action');
                var formData = new FormData(this);
                console.log(formData);
                if (!formData.has('_token')) {
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                }
                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        $('#success-message').text(result.message).show();
                        setTimeout(function() {
                                $('#success-message').fadeOut('slow', function() {
                                    $('#suratModal').modal('hide');
                                });
                            },
                            3000
                        ); // Show the success message for 3 seconds before closing the modal
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

            $('#suratFormEdit').submit(function(e) {
                e.preventDefault();
                var actionUrl = $(this).attr('action');
                var formData = new FormData(this);
                console.log(formData);
                if (!formData.has('_token')) {
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                }
                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        $('#success-message').text(result.message).show();
                        setTimeout(function() {
                                $('#success-message').fadeOut('slow', function() {
                                    $('#editSuratModal').modal('hide');
                                });
                            },
                            3000
                        ); // Show the success message for 3 seconds before closing the modal
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

            $('#closemodal').click(function() {
                $('#suratModal').modal('hide');
            });

            $('#closeConfirmModal, #cancelDelete').click(function() {
                $('#confirmModal').modal('hide');
            });

            $('#closeErrorModal, #closeErrorModalFooter').click(function() {
                $('#errorModal').modal('hide');
            });
        });

        function viewFile(filePath) {
            window.open(filePath, '_blank');
        }
    </script>
@endsection
