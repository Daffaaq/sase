<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Mazer Admin Dashboard</title>
    <link rel="shortcut icon" href="{{ asset('assets/compiled/svg/favicon.svg') }}" type="image/x-icon" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <link href="{{ asset('assets/compiled/css/app.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/compiled/css/app-dark.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/compiled/css/iconly.css') }}" rel="stylesheet" />
</head>

<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    <div id="app">
        <div id="sidebar">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo">
                            <a href="{{ route('dashboardSuperadmin') }}">
                                <img src="{{ asset('assets/compiled/svg/logo.svg') }}" alt="Logo" srcset="" />
                            </a>
                        </div>
                        <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20"
                                height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                                <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                        opacity=".3"></path>
                                    <g transform="translate(-210 -1)">
                                        <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                        <circle cx="220.5" cy="11.5" r="4"></circle>
                                        <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2">
                                        </path>
                                    </g>
                                </g>
                            </svg>
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input me-0" type="checkbox" id="toggle-dark"
                                    style="cursor: pointer" />
                                <label class="form-check-label"></label>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                aria-hidden="true" role="img" class="iconify iconify--mdi" width="20"
                                height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                                </path>
                            </svg>
                        </div>
                        <div class="sidebar-toggler x">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i
                                    class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>
                        <li class="sidebar-item {{ Request::routeIs('dashboardKadiv') ? 'active' : '' }}">
                            <a href="{{ route('dashboardKadiv') }}" class="sidebar-link">
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li
                            class="sidebar-item has-sub {{ Request::routeIs('Kategori-Surat-Masuk-Kadiv.index', 'Kategori-Surat-Keluar-Kadiv.index', 'Kategori-Arsip-Surat-Masuk-Kadiv.index', 'Kategori-Arsip-Surat-Out-Kadiv.index') ? 'active' : '' }}">
                            <a href="javascript:void(0)" class="sidebar-link">
                                <i class="bi bi-stack"></i>
                                <span>Category Surat</span>
                            </a>
                            <ul class="submenu">
                                <li
                                    class="submenu-item {{ Request::routeIs('Kategori-Surat-Masuk-Kadiv.index') ? 'active' : '' }}">
                                    <a href="{{ route('Kategori-Surat-Masuk-Kadiv.index') }}"
                                        class="submenu-link">Surat
                                        Masuk</a>
                                </li>
                                <li
                                    class="submenu-item {{ Request::routeIs('Kategori-Surat-Keluar-Kadiv.index') ? 'active' : '' }}">
                                    <a href="{{ route('Kategori-Surat-Keluar-Kadiv.index') }}"
                                        class="submenu-link">Surat
                                        Keluar</a>
                                </li>
                                <li
                                    class="submenu-item {{ Request::routeIs('Kategori-Arsip-Surat-Masuk-Kadiv.index') ? 'active' : '' }}">
                                    <a href="{{ route('Kategori-Arsip-Surat-Masuk-Kadiv.index') }}"
                                        class="submenu-link">Surat Masuk Arsip</a>
                                </li>
                                <li
                                    class="submenu-item {{ Request::routeIs('Kategori-Arsip-Surat-Out-Kadiv.index') ? 'active' : '' }}">
                                    <a href="{{ route('Kategori-Arsip-Surat-Out-Kadiv.index') }}"
                                        class="submenu-link">Surat Keluar Arsip</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('surat-masuk-index-kadiv') }}" class="sidebar-link">
                                <i class="bi bi-envelope-paper"></i>
                                <span>Surat Masuk</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('surat-keluar-index-kadiv') }}" class="sidebar-link">
                                <i class="bi bi-envelope-open"></i>
                                <span>Surat Keluar</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="index.html" class="sidebar-link">
                                <i class="bi bi-send"></i>
                                <span>Surat Disposisi</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="index.html" class="sidebar-link">
                                <i class="bi bi-archive"></i>
                                <span>Surat Masuk Arsip</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="index.html" class="sidebar-link">
                                <i class="bi bi-archive-fill"></i>
                                <span>Surat Keluar Arsip</span>
                            </a>
                        </li>
                        <!-- Other Sidebar Items -->
                        <li class="sidebar-item">
                            <a href="#"
                                onclick="event.preventDefault(); localStorage.removeItem('activeSidebarUrl'); document.getElementById('logout-form').submit();"
                                class="sidebar-link logout-link">
                                <i class="bi bi-box-arrow-left"></i>
                                <span>Log Out</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="main">
            @yield('breadcrumbs')
            <hr style="border: 0; height: 1px; background: #dee2e6; margin: 1rem 0;">
            @yield('container')
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/dashboard.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Function to load page content via AJAX
            function loadPage(url) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        // Update the main content with loaded content
                        $('#main').html($(data).find('#main').html());
                        // Update the URL in the browser address bar
                        window.history.pushState({}, '', url);
                        // Save active URL to localStorage
                        localStorage.setItem('activeSidebarUrl', url);
                        // Update active state for sidebar based on loaded page
                        updateSidebar(url);
                    },
                    error: function(xhr) {
                        console.log("Error loading page");
                    }
                });
            }

            // Function to update sidebar active state
            function updateSidebar(url) {
                // Remove active class from all sidebar items
                $('.sidebar-item').removeClass('active');
                $('.submenu-item').removeClass('active');

                // Add active class to sidebar item corresponding to the current URL
                $('.sidebar-link[href="' + url + '"]').closest('.sidebar-item').addClass('active');
                $('.submenu-link[href="' + url + '"]').closest('.submenu-item').addClass('active');
            }

            // Handle sidebar link clicks
            $(document).on('click', '.sidebar-link, .submenu-link', function(e) {
                if ($(this).hasClass('logout-link')) {
                    // If the link is the logout link, don't use AJAX
                    return true;
                }
                // Prevent default link behavior
                e.preventDefault();
                // Get the URL from the link
                var url = $(this).attr('href');
                // Load the page content via AJAX
                loadPage(url);
            });

            // Handle back/forward browser navigation
            window.addEventListener('popstate', function() {
                // Load the page content based on current URL
                loadPage(location.pathname);
            });

            // Initial update of sidebar based on current URL or localStorage
            var initialUrl = localStorage.getItem('activeSidebarUrl') || location.pathname;
            updateSidebar(initialUrl);
            if (initialUrl !== location.pathname) {
                loadPage(initialUrl);
            }
        });
    </script>

</body>

</html>
