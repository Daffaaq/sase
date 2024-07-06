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
            <h6 class="m-0 font-weight-bold text-primary">Surat Management</h6>
        </div>
        <div class="card-body">
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
                            let badgeClass = 'badge-info'; // Default class

                            switch (data) {
                                case 'surat_in':
                                    badgeClass = 'badge-success';
                                    break;
                                case 'surat_out':
                                    badgeClass = 'badge-danger';
                                    break;
                                case 'surat_internal':
                                    badgeClass = 'badge-info';
                                    break;
                                case 'surat_pegawai':
                                    badgeClass = 'badge-primary';
                                    break;
                            }

                            return '<span class="badge ' + badgeClass + '">' + data.replace('_',
                                ' ') + '</span>';
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
        });

        function viewFile(filePath) {
            window.open(filePath, '_blank');
        }
    </script>
@endsection
