<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>Power Line Monitoring @yield('title')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />


    @yield("head")

    <link rel="shortcut icon" href="{{ mix('images/logo.png') }}" />

    <link href="{{ mix('css/config/bootstrap.min.css') }}" rel="stylesheet" type="text/css"
        id="bs-default-stylesheet" />
    <link href="{{ mix('css/config/app.min.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

    <link href="{{ mix('css/config/bootstrap-dark.min.css') }}" rel="stylesheet" type="text/css"
        id="bs-dark-stylesheet" />
    <link href="{{ mix('css/config/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet" />

    <link rel="stylesheet" href="{{ mix('css/icons.min.css') }}" type="text/css" />

    <script src="{{ mix('js/app.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

</head>

<body data-layout="" data-sidebar-width="boxed">
    <div id="preloader">
        <div id="status">
            <div class="spinner">Loading...</div>
        </div>
    </div>
    @include('sweetalert::alert')


    <div id="wrapper">
        <!-- Topbar Start -->
        <div class="navbar-custom">
            <div class="container-fluid">
                <ul class="list-unstyled topnav-menu float-end mb-0">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="d-none d-lg-inline-block">
                            <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen"
                                href="#">
                                <i class="fe-maximize noti-icon"></i>
                            </a>
                        </li>
                        <li class="dropdown notification-list topbar-dropdown">
                            <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light"
                                data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                                aria-expanded="false">
                                <img src="{{ mix('images/user-3.jpg') }}" alt="user-image" class="rounded-circle">
                                <span class="pro-user-name ms-1">
                                    {{ ucwords(Auth::user()->name) }}<i class="mdi mdi-chevron-down"></i>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                                <!-- item-->
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">Welcome !</h6>
                                </div>

                                <!-- item-->
                                <a href="/profile" class="dropdown-item notify-item">
                                    <i class="fe-user"></i>
                                    <span>My Account</span>
                                </a>

                                <!-- item-->
                                <a href="/user" class="dropdown-item notify-item">
                                    <i class="fe-lock"></i>
                                    <span>Password</span>
                                </a>

                                <div class="dropdown-divider"></div>

                                <!-- item-->
                                <a href="" class="dropdown-item notify-item"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    <i class="fe-log-out"></i>
                                    <span>Logout</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>

                            </div>
                        </li>


                        <li class="">
                            <a href="#" class="nav-link right-bar-toggle">
                                <i class="fe-settings noti-icon"></i>
                            </a>
                        </li>
                    @endguest

                </ul>

                <!-- LOGO -->
                <div class="logo-box">
                    <a href="/" class="logo logo-dark text-center">
                        <span class="logo-sm">
                            <img src="{{ mix('images/PLMS.png') }}" alt="" height="25">
                            <span class="logo-lg-text-light"></span>
                        </span>
                        <span class="logo-lg">
                            <img src="{{ mix('images/PLMS.png') }}" alt="" height="34">
                            <span class="logo-lg-text-light"></span>
                        </span>
                    </a>

                    <a href="/" class="logo logo-light text-center">
                        <span class="logo-sm">
                            <img src="{{ mix('images/PLMS-WHITE.png') }}" alt="" height="25">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ mix('images/PLMS-WHITE.png') }}" alt="" height="33">
                            {{-- <span class="text-muted ps-1 fs-5">Power Line Monitoring</span> --}}
                        </span>
                    </a>
                </div>
                <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                    @auth
                        <li>
                            <button class="button-menu-mobile waves-effect waves-light">
                                <i class="fe-menu"></i>
                            </button>
                        </li>

                    @endauth

                    @if (!Auth::check())
                        <li class="dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-light" href="/incidents" role="button"
                                aria-haspopup="false" aria-expanded="false">
                                Incidents
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- end Topbar -->
        @auth
            <div class="left-side-menu">
                <div class="h-100" data-simplebar>
                    <div id="sidebar-menu">
                        <ul id="side-menu">
                            <li class="menu-title">Power Line Monitoring</li>
                            <li>
                                <a href="/">
                                    <i class="fe-home"></i>
                                    <span> Home </span>
                                </a>
                            </li>
                            <li>
                                <a type="button" id="toggle-btn" data-toggle="collapse" data-target="#sidebarEmail"
                                    aria-expanded="false">
                                    <i data-feather="clipboard"></i>
                                    <span class="badge bg-success rounded-pill float-end">2</span>
                                    <span> Past Incidents </span>
                                    {{-- <span class="menu-arrow"></span> --}}
                                </a>
                                <div id="sidebarEmail" class="collapse">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="/incidents">
                                                <i class="fe-corner-down-right"></i>
                                                &nbsp;View
                                            </a>
                                        </li>
                                        <li>

                                            <a href="/incidents/create">
                                                <i class="fe-corner-down-right"></i>
                                                &nbsp;Create
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <script>
                                $(document).ready(function() {
                                    $("#toggle-btn").click(function() {
                                        $("#sidebarEmail").collapse('toggle');
                                    });
                                });
                            </script>
                            <li>
                                <a href="{{ URL::to('lineman') }}">
                                    <i data-feather="users"></i>
                                    <span> Accounts </span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ URL::to('units') }}">
                                    <i class="text-muted mdi mdi-sim"></i>
                                    <span> Units </span>
                                </a>
                            </li>

                            <li>
                                <a href="/dispatch">
                                    <i class="text-muted mdi mdi-car-arrow-right"></i>
                                    <span> Dispatch </span>
                                </a>
                            </li>
                        </ul>

                    </div>
                    <!-- End Sidebar -->

                    <div class="clearfix"></div>

                </div>
                <!-- Sidebar -left -->
            </div>
        @endauth

        @auth

            <div class="content-page p-0">
                <div class="content">
                    <div class="container-fluid p-0">
                        @yield("content")
                    </div>
                </div>
            </div>


        @endauth

        @if (!Auth::check())
            <div style="margin-top: 70px;">
                @yield('content')
            </div>
        @endif
    </div>

    <div class="right-bar">
        <div data-simplebar class="h-100">
            <div class="tab-pane active" id="settings-tab" role="tabpanel">
                <h6 class="fw-medium px-3 m-0 py-2 font-13 text-uppercase bg-light">
                    <span class="d-block py-1">Theme Settings</span>
                </h6>

                <div class="p-3">
                    <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">Color Scheme</h6>
                    <div class="form-check form-switch mb-1">
                        <input type="checkbox" class="form-check-input" name="color-scheme-mode" value="light"
                            id="light-mode-check" checked />
                        <label class="form-check-label text-dark" for="light-mode-check">Light Mode <i
                                class="ps-1 fas fa-cloud-sun text-warning"></i></label>
                    </div>

                    <div class="form-check form-switch mb-1">
                        <input type="checkbox" class="form-check-input" name="color-scheme-mode" value="dark"
                            id="dark-mode-check" />
                        <label class="form-check-label text-dark " for="dark-mode-check">Dark Mode <i
                                class="ps-2 fas fa-moon"></i></label>
                    </div>

                    <!-- size -->
                    <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">Left Sidebar Size</h6>

                    <div class="form-check form-switch mb-1">
                        <input type="checkbox" class="form-check-input" name="leftsidebar-size" value="default"
                            id="default-size-check" checked />
                        <label class="form-check-label" for="default-size-check">Default</label>
                    </div>

                    <div class="form-check form-switch mb-1">
                        <input type="checkbox" class="form-check-input" name="leftsidebar-size" value="condensed"
                            id="condensed-check" />
                        <label class="form-check-label" for="condensed-check">Condensed <small>(Extra Small
                                size)</small></label>
                    </div>

                    <div class="d-grid mt-4">
                        <button class="btn btn-primary" id="resetBtn">Reset to Default</button>
                    </div>

                </div>

            </div>
        </div>

    </div> <!-- end slimscroll-menu-->
    {{-- <style>
        @media (max-width: 780px) {
            body {
                overflow: auto !important;
                max-height: 100vh !important;
            }
        }

    </style> --}}

    @auth
        <script src="{{ mix('js/button-theme-settings.js') }}"></script>
    @endauth
    <div class="rightbar-overlay"></div>
    <script src="{{ mix('js/vendor.min.js') }}"></script>
    <script src="{{ asset('libs/moment/min/moment.min.js') }}"></script>
    {{-- <script src="{{mix('js/pages/jquery.todo.js')}}"></script> --}}
    <script src="{{ mix('js/app.min.js') }}"></script>
    @yield('script')

</body>

</html>
