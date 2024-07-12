<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">SASE</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ Request::routeIs('dashboardSuperadmin') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboardSuperadmin') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item {{ Request::routeIs('manajemen-user-index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('manajemen-user-index') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>User</span></a>
            </li>

            <li
                class="nav-item {{ Request::routeIs('Kategori-Surat-Masuk.index', 'Kategori-Surat-Keluar.index', 'Kategori-Arsip-Surat-Masuk.index', 'Kategori-Arsip-Surat-Keluar.index') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-list"></i>
                    <span>Category Surat</span>
                </a>
                <div id="collapseTwo"
                    class="collapse {{ Request::routeIs('Kategori-Surat-Masuk.index', 'Kategori-Surat-Keluar.index', 'Kategori-Arsip-Surat-Masuk.index', 'Kategori-Arsip-Surat-Keluar.index') ? 'show' : '' }}"
                    aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Category Surat:</h6>
                        <a class="collapse-item {{ Request::routeIs('Kategori-Surat-Masuk.index') ? 'active' : '' }}"
                            href="{{ route('Kategori-Surat-Masuk.index') }}">
                            <i class="fas fa-minus"></i> Surat Masuk
                        </a>
                        <a class="collapse-item {{ Request::routeIs('Kategori-Surat-Keluar.index') ? 'active' : '' }}"
                            href="{{ route('Kategori-Surat-Keluar.index') }}">
                            <i class="fas fa-minus"></i> Surat Keluar
                        </a>
                        <a class="collapse-item {{ Request::routeIs('Kategori-Arsip-Surat-Masuk.index') ? 'active' : '' }}"
                            href="{{ route('Kategori-Arsip-Surat-Masuk.index') }}">
                            <i class="fas fa-minus"></i> Surat Masuk Arsip
                        </a>
                        <a class="collapse-item {{ Request::routeIs('Kategori-Arsip-Surat-Keluar.index') ? 'active' : '' }}"
                            href="{{ route('Kategori-Arsip-Surat-Keluar.index') }}">
                            <i class="fas fa-minus"></i> Surat Keluar Arsip
                        </a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-envelope-open-text"></i>
                    <span>Surat Masuk</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-envelope-open"></i>
                    <span>Surat Keluar</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-share"></i>
                    <span>Surat Disposisi</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-archive"></i>
                    <span>Surat Masuk Arsip</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-archive"></i>
                    <span>Surat Keluar Arsip</span></a>
            </li>

            <!-- Tambahkan tautan "Logout" di luar div dengan class "collapse" -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" data-toggle="modal" data-target="#customLogoutModal">
                    <i class="fas fa-fw fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>





            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            {{-- <!-- Sidebar Message -->
            <div class="sidebar-card d-none d-lg-flex">
                <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="...">
                <p class="text-center mb-2"><strong>SB Admin Pro</strong> is packed with premium features, components,
                    and more!</p>
                <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Upgrade to
                    Pro!</a>
            </div> --}}
            <li class="nav-item">
                {{-- <a class="nav-link" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <p>
                        Logout
                    </p>
                </a> --}}
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>

        </ul>
        <!-- End of Sidebar -->

        <div class="modal fade" id="customLogoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    </div>
                </div>
            </div>
        </div>
</body>
