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
            <h6 class="m-0 font-weight-bold text-primary">Users Management</h6>
        </div>
        <div class="card-body">
            <button id="createUserBtn" class="btn btn-success float-right mb-3">
                <i class="fas fa-plus"></i> Create Users
            </button>
            <div class="table-responsive">
                <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pengguna</th>
                            <th>Email Pengguna</th>
                            <th>Role Pengguna</th>
                            <th>Status Pengguna</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('Superadmin.Manajemen-User.create')
    @include('Superadmin.Manajemen-User.confirm')

    <script>
        $(document).ready(function() {
            var dataMaster = $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('users.list') }}',
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
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return `
                        <button data-id="${data}" class="btn btn-xs btn-warning editUserBtn">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button data-id="${data}" class="btn btn-xs btn-danger deleteUserBtn">
                            <i class="fa fa-trash"></i>
                        </button>`;
                        }
                    }
                ],
                autoWidth: false,
                drawCallback: function(settings) {
                    $('a').tooltip();
                }
            });

            $('#createUserBtn').click(function() {
                $('#userForm')[0].reset();
                $('#userModalLabel').text('Create User');
                $('#userModal').modal('show');
            });

            $('#usersTable').on('click', '.editUserBtn', function() {
                var id = $(this).data('id');
                $.get('/dashboardSuperadmin/Users/edit/' + id, function(data) {
                    $('#userModalLabel').text('Edit User');
                    $('#userForm').attr('action', '/dashboardSuperadmin/Users/update/' + id);
                    $('#userForm').append('<input type="hidden" name="_method" value="PUT">');
                    $('#name').val(data.name);
                    $('#username').val(data.username);
                    $('#email').val(data.email);
                    $('#role').val(data.role);
                    $('#status').val(data.status);
                    $('#userModal').modal('show');
                });
            });

            $('#usersTable').on('click', '.deleteUserBtn', function() {
                var id = $(this).data('id');
                $('#confirmDeleteBtn').data('id', id);
                $('#confirmModal').modal('show');
            });

            $('#confirmDeleteBtn').click(function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '/dashboardSuperadmin/Users/destroy/' + id,
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
                        3000); // Show the success message for 3 seconds before closing the modal
                        dataMaster.ajax.reload();
                    }
                });
            });

            $('#userForm').submit(function(e) {
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
                                    $('#userModal').modal('hide');
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
            $('#closemodal').click(function() {
                $('#userModal').modal('hide');
            });
            $('#closeConfirmModal').click(function() {
                $('#confirmModal').modal('hide');
            });

            $('#cancelDelete').click(function() {
                $('#confirmModal').modal('hide');
            });
        });
    </script>
@endsection
