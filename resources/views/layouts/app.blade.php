<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hotel Management System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #4e73df;
            --primary-dark: #3a56b7;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
            --sidebar-width: 250px;
            --header-height: 70px;
            --footer-height: 60px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        #sidebar {
            position: fixed;
            width: var(--sidebar-width);
            height: 100vh;
            background: #2c3e50; /* Dark blue color as shown in the image */
            color: white;
            transition: all 0.3s;
            z-index: 1000;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        #sidebar .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        #sidebar .hotel-logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
        }

        #sidebar .hotel-logo img {
            width: 40px;
            height: 40px;
        }

        #sidebar .sidebar-brand {
            font-size: 1.1rem;
            font-weight: 500;
            margin-top: 10px;
            line-height: 1.4;
        }

        #sidebar .category-title {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.5);
            padding: 15px 20px 5px;
            font-weight: 600;
        }

        #sidebar ul.components {
            padding: 0;
        }

        #sidebar ul li a {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.9rem;
            border-left: 3px solid transparent;
        }

        #sidebar ul li a .menu-item {
            display: flex;
            align-items: center;
        }

        #sidebar ul li a .menu-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        #sidebar ul li a:hover,
        #sidebar ul li a.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            border-left: 3px solid white;
        }

        #sidebar ul li a .dropdown-indicator {
            font-size: 0.8rem;
        }

        #sidebar ul li ul.collapse {
            background: rgba(0, 0, 0, 0.1);
        }

        #sidebar ul li ul.collapse li a {
            padding: 8px 20px 8px 50px;
            font-size: 0.85rem;
            border-left: none;
        }

        #sidebar ul li ul.collapse li a:hover,
        #sidebar ul li ul.collapse li a.active {
            background: rgba(255, 255, 255, 0.05);
            border-left: none;
        }

        #sidebar ul li ul.collapse li a .menu-item i {
            font-size: 0.5rem;
            margin-right: 8px;
        }

        /* Add a turquoise highlight for the active menu section */
        #sidebar ul li a.active,
        #sidebar ul li a[aria-expanded="true"] {
            border-left: 3px solid #1abc9c;
        }

        /* Add a subtle transition for dropdown arrows */
        #sidebar ul li a[aria-expanded="true"] .dropdown-indicator {
            transform: rotate(180deg);
            transition: transform 0.3s;
        }

        #sidebar ul li a .dropdown-indicator {
            transition: transform 0.3s;
        }

        #sidebar .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 15px;
            background: rgba(0, 0, 0, 0.1);
            font-size: 0.8rem;
        }

        /* Content Styles */
        #content {
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            margin-left: var(--sidebar-width);
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
        }

        /* Header Styles */
        #header {
            height: var(--header-height);
            background: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            display: flex;
            align-items: center;
            padding: 0 25px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        #header .navbar-search {
            width: 30%;
        }

        #header .navbar-search .input-group {
            border-radius: 20px;
            overflow: hidden;
        }

        #header .navbar-search .form-control {
            border-radius: 20px 0 0 20px;
            border: 1px solid #d1d3e2;
            font-size: 0.85rem;
        }

        #header .navbar-search .btn {
            border-radius: 0 20px 20px 0;
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        #header .navbar-nav .nav-item {
            position: relative;
        }

        #header .navbar-nav .nav-link {
            color: var(--secondary-color);
            font-size: 0.85rem;
            padding: 0 0.75rem;
        }

        #header .navbar-nav .nav-link:hover {
            color: var(--primary-color);
        }

        #header .navbar-nav .dropdown-menu {
            position: absolute;
            right: 0;
            left: auto;
            font-size: 0.85rem;
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border-radius: 0.35rem;
        }

        #header .navbar-nav .dropdown-menu .dropdown-item {
            font-weight: 400;
            padding: 0.5rem 1rem;
        }

        #header .navbar-nav .dropdown-menu .dropdown-item:hover {
            background-color: var(--light-color);
        }

        #header .navbar-nav .dropdown-menu .dropdown-item i {
            margin-right: 0.5rem;
            color: var(--secondary-color);
        }

        #header .navbar-nav .dropdown-menu .dropdown-divider {
            border-top: 1px solid #e3e6f0;
        }

        /* Main Content Styles */
        #main {
            flex: 1;
            padding: 25px;
            padding-bottom: calc(var(--footer-height) + 25px);
        }

        /* Footer Styles */
        #footer {
            height: var(--footer-height);
            background: white;
            border-top: 1px solid #e3e6f0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 25px;
            position: fixed;
            bottom: 0;
            width: calc(100% - var(--sidebar-width));
            z-index: 99;
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }

        .card .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.35rem;
        }

        /* Button Styles */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        /* Badge Styles */
        .badge {
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            #sidebar {
                margin-left: calc(var(--sidebar-width) * -1);
            }

            #sidebar.active {
                margin-left: 0;
            }

            #content {
                width: 100%;
                margin-left: 0;
            }

            #content.active {
                margin-left: var(--sidebar-width);
                width: calc(100% - var(--sidebar-width));
            }

            #footer {
                width: 100%;
            }

            #footer.active {
                width: calc(100% - var(--sidebar-width));
            }

            #sidebarCollapse {
                display: block;
            }
        }

        /* Toggle Button */
        #sidebarCollapse {
            display: none;
            background: transparent;
            border: none;
            color: var(--secondary-color);
            font-size: 1.5rem;
            cursor: pointer;
            margin-right: 15px;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>

    @yield('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <div class="hotel-logo">
                    <img src="{{ asset('images/hotel-logo.svg') }}" alt="Hotel Logo" onerror="this.src='https://via.placeholder.com/40?text=HOTEL'">
                </div>
                <div class="sidebar-brand">
                    Welcome to Our Hotel,<br>
                    <strong>HOTEL</strong>
                </div>
            </div>

            <div class="category-title">GENERAL</div>

            <ul class="list-unstyled components">
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <div class="menu-item">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#roleSubmenu" data-bs-toggle="collapse" class="{{ request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'active' : '' }}">
                        <div class="menu-item">
                            <i class="fas fa-user-tag"></i> Role
                        </div>
                        <i class="fas fa-chevron-down dropdown-indicator"></i>
                    </a>
                    <ul class="collapse list-unstyled" id="roleSubmenu">
                        @can('view-role')
                        <li>
                            <a href="{{ route('roles.index') }}">
                                <div class="menu-item">
                                    <i class="fas fa-circle fa-xs"></i> All Roles
                                </div>
                            </a>
                        </li>
                        @endcan
                        @can('create-role')
                        <li>
                            <a href="{{ route('roles.create') }}">
                                <div class="menu-item">
                                    <i class="fas fa-circle fa-xs"></i> Add New Role
                                </div>
                            </a>
                        </li>
                        @endcan
                        @can('view-permission')
                        <li>
                            <a href="{{ route('permissions.index') }}">
                                <div class="menu-item">
                                    <i class="fas fa-circle fa-xs"></i> All Permissions
                                </div>
                            </a>
                        </li>
                        @endcan
                        @can('create-permission')
                        <li>
                            <a href="{{ route('permissions.create') }}">
                                <div class="menu-item">
                                    <i class="fas fa-circle fa-xs"></i> Add New Permission
                                </div>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                <li>
                    <a href="#bookingSubmenu" data-bs-toggle="collapse" class="{{ request()->routeIs('bookings.*') ? 'active' : '' }}">
                        <div class="menu-item">
                            <i class="fas fa-calendar-check"></i> Booking
                        </div>
                        <i class="fas fa-chevron-down dropdown-indicator"></i>
                    </a>
                    <ul class="collapse list-unstyled" id="bookingSubmenu">
                        <li>
                            <a href="{{ route('bookings.index') }}">
                                <div class="menu-item">
                                    <i class="fas fa-circle fa-xs"></i> All Bookings
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('bookings.create') }}">
                                <div class="menu-item">
                                    <i class="fas fa-circle fa-xs"></i> Add New Booking
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#reportSubmenu" data-bs-toggle="collapse" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <div class="menu-item">
                            <i class="fas fa-chart-bar"></i> Report Hotel
                        </div>
                        <i class="fas fa-chevron-down dropdown-indicator"></i>
                    </a>
                    <ul class="collapse list-unstyled" id="reportSubmenu">
                        <li>
                            <a href="{{ route('reports.index') }}">
                                <div class="menu-item">
                                    <i class="fas fa-circle fa-xs"></i> Overview
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('reports.occupancy') }}">
                                <div class="menu-item">
                                    <i class="fas fa-circle fa-xs"></i> Occupancy Report
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('reports.revenue') }}">
                                <div class="menu-item">
                                    <i class="fas fa-circle fa-xs"></i> Revenue Report
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#paymentSubmenu" data-bs-toggle="collapse" class="{{ request()->routeIs('payments.*') ? 'active' : '' }}">
                        <div class="menu-item">
                            <i class="fas fa-credit-card"></i> Payment
                        </div>
                        <i class="fas fa-chevron-down dropdown-indicator"></i>
                    </a>
                    <ul class="collapse list-unstyled" id="paymentSubmenu">
                        <li>
                            <a href="{{ route('payments.index') }}">
                                <div class="menu-item">
                                    <i class="fas fa-circle fa-xs"></i> All Payments
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#roomSubmenu" data-bs-toggle="collapse" class="{{ request()->routeIs('rooms.*') || request()->routeIs('room-types.*') ? 'active' : '' }}">
                        <div class="menu-item">
                            <i class="fas fa-bed"></i> Room Management
                        </div>
                        <i class="fas fa-chevron-down dropdown-indicator"></i>
                    </a>
                    <ul class="collapse list-unstyled" id="roomSubmenu">
                        <li>
                            <a href="{{ route('rooms.index') }}">
                                <div class="menu-item">
                                    <i class="fas fa-circle fa-xs"></i> All Rooms
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rooms.create') }}">
                                <div class="menu-item">
                                    <i class="fas fa-circle fa-xs"></i> Add New Room
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('room-types.index') }}">
                                <div class="menu-item">
                                    <i class="fas fa-circle fa-xs"></i> Room Types
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#amenitiesSubmenu" data-bs-toggle="collapse">
                        <div class="menu-item">
                            <i class="fas fa-concierge-bell"></i> Amenities
                        </div>
                        <i class="fas fa-chevron-down dropdown-indicator"></i>
                    </a>
                    <ul class="collapse list-unstyled" id="amenitiesSubmenu">
                        <li>
                            <a href="#">
                                <div class="menu-item">
                                    <i class="fas fa-circle fa-xs"></i> All Amenities
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="menu-item">
                                    <i class="fas fa-circle fa-xs"></i> Add New Amenity
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#customerSubmenu" data-bs-toggle="collapse" class="{{ request()->routeIs('guests.*') ? 'active' : '' }}">
                        <div class="menu-item">
                            <i class="fas fa-users"></i> Customer
                        </div>
                        <i class="fas fa-chevron-down dropdown-indicator"></i>
                    </a>
                    <ul class="collapse list-unstyled" id="customerSubmenu">
                        <li>
                            <a href="{{ route('guests.index') }}">
                                <div class="menu-item">
                                    <i class="fas fa-circle fa-xs"></i> All Customers
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('guests.create') }}">
                                <div class="menu-item">
                                    <i class="fas fa-circle fa-xs"></i> Add New Customer
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#staffSubmenu" data-bs-toggle="collapse" class="{{ request()->routeIs('staff.*') ? 'active' : '' }}">
                        <div class="menu-item">
                            <i class="fas fa-user-tie"></i> Staff
                        </div>
                        <i class="fas fa-chevron-down dropdown-indicator"></i>
                    </a>
                    <ul class="collapse list-unstyled" id="staffSubmenu">
                        <li>
                            <a href="{{ route('staff.index') }}">
                                <div class="menu-item">
                                    <i class="fas fa-circle fa-xs"></i> All Staff
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('staff.create') }}">
                                <div class="menu-item">
                                    <i class="fas fa-circle fa-xs"></i> Add New Staff
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <div class="category-title">MENU</div>
        </nav>

        <!-- Content -->
        <div id="content">
            <!-- Header -->
            <nav id="header">
                <button type="button" id="sidebarCollapse">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search for..." aria-label="Search">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>

                <div class="ms-auto d-flex align-items-center">
                    <!-- Notifications Dropdown -->
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell fa-fw"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                3+
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="alertsDropdown">
                            <h6 class="dropdown-header">Notifications Center</h6>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="me-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-file-alt text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">December 12, 2023</div>
                                    <span>A new monthly report is ready to download!</span>
                                </div>
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="me-3">
                                    <div class="icon-circle bg-success">
                                        <i class="fas fa-donate text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">December 7, 2023</div>
                                    $290.29 has been deposited into your account!
                                </div>
                            </a>
                            <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                        </div>
                    </div>

                    <!-- User Dropdown -->
                    <div class="nav-item dropdown ms-3">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="me-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name ?? 'Admin User' }}</span>
                            <img class="img-profile rounded-circle" src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Admin User' }}&background=4e73df&color=ffffff" width="32" height="32">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
                                Profile
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i>
                                Settings
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-list fa-sm fa-fw me-2 text-gray-400"></i>
                                Activity Log
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main id="main">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer id="footer">
                <div class="text-center">
                    <span>Copyright &copy; Hotel Management System {{ date('Y') }}</span>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').toggleClass('active');
                $('#content').toggleClass('active');
                $('#footer').toggleClass('active');
            });

            // Close dropdown when clicking outside
            $(document).click(function(e) {
                var container = $(".navbar-nav");
                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    if ($('.navbar-nav .dropdown-menu').hasClass('show')) {
                        $('.navbar-nav .dropdown-toggle').dropdown('toggle');
                    }
                }
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
