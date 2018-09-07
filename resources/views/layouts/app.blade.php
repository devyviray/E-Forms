<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <script>window.laravel = { csrfToken: {{ csrf_token() }} }</script> --}}
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Styles -->
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <div class="wrapper">
            <div class="sidebar" data-image="../assets/img/sidebar-5.jpg">
                <!--
                    Tip 1: You can change the color of the sidebar using: data-color="purple | blue | green | orange | red"
                    
                    Tip 2: you can also add an image using data-image tag
                -->
                <div class="sidebar-wrapper">
                    <div class="logo">
                        <a href="http://www.creative-tim.com" class="simple-text">
                            Creative Tim
                        </a>
                    </div>
                    <ul class="nav">
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ route('home') }}">
                                <i class="nc-icon nc-chart-pie-35"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li>
                            <div class="nav-link">
                                <i class="nc-icon nc-notes"></i>
                                <p>Forms</p>
                            </div>
                            <ul style="list-style-type: none;">
                                <li>
                                    <a class="nav-link" href="{{ route('drdr') }}">
                                        <p>Drdr</p>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('ddr') }}">
                                        <p>Ddr</p>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('ccir') }}">
                                        <p>Ccir</p>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('ncn') }}">
                                        <p>Ncn</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            @role('administrator,mr') 
                            <div class="nav-link" href="./table.html">
                                <i class="nc-icon nc-circle-09"></i>
                                <p>Users</p>
                            </div>
                            <ul style="list-style-type: none;">
                                <li>
                                    <a class="nav-link" href="{{ route('users') }}">
                                        <p>All user</p>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('roles') }}">
                                        <p>Roles</p>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('permissions') }}">
                                        <p>Permisions</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <div class="nav-link" href="./typography.html">
                                <i class="nc-icon nc-paper-2"></i>
                                <p>Master Data</p>
                            </div>
                            <ul style="list-style-type: none;">
                                <li>
                                    <a class="nav-link" href="{{ route('companies') }}">
                                        <p>Company</p>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('departments') }}">
                                        <p>Department</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endrole
                        <li class="nav-item active active-pro">
                            <a class="nav-link active" href="upgrade.html">
                                <i class="nc-icon nc-alien-33"></i>
                                <p>Upgrade to PRO</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="main-panel">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg " color-on-scroll="500">
                    <div class=" container-fluid  ">
                        <a class="navbar-brand" href="#pablo"> Dashboard </a>
                        @role('administrator') 
                            <h5> administrator logged in</h5>
                        @endrole

                        @role('mr') 
                        <h5> MR logged in</h5>
                         @endrole
                        @role('approver') 
                        <h5> approver logged in</h5>
                        @endrole

                        @role('reviewer') 
                        <h5> reviewer logged in</h5>
                        @endrole

                        @role('requester') 
                        <h5> requester logged in</h5>
                        @endrole

                        @role('notified') 
                        <h5> notified logged in</h5>
                        @endrole
                        
                        @role('moderator') 
                        <h5> moderator logged in</h5>
                        @endrole
                        <button href="" class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-bar burger-lines"></span>
                            <span class="navbar-toggler-bar burger-lines"></span>
                            <span class="navbar-toggler-bar burger-lines"></span>
                        </button>
                        <div class="collapse navbar-collapse justify-content-end" id="navigation">
                            <ul class="nav navbar-nav mr-auto">
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-toggle="dropdown">
                                        <i class="nc-icon nc-palette"></i>
                                        <span class="d-lg-none">Dashboard</span>
                                    </a>
                                </li>
                                <li class="dropdown nav-item">
                                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                                        <i class="nc-icon nc-planet"></i>
                                        <span class="notification">5</span>
                                        <span class="d-lg-none">Notification</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <a class="dropdown-item" href="#">Notification 1</a>
                                        <a class="dropdown-item" href="#">Notification 2</a>
                                        <a class="dropdown-item" href="#">Notification 3</a>
                                        <a class="dropdown-item" href="#">Notification 4</a>
                                        <a class="dropdown-item" href="#">Another notification</a>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nc-icon nc-zoom-split"></i>
                                        <span class="d-lg-block">&nbsp;Search</span>
                                    </a>
                                </li>
                            </ul>
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="#pablo">
                                        <span class="no-icon">Account</span>
                                    </a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="no-icon">Dropdown</span>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something</a>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                        <div class="divider"></div>
                                        <a class="dropdown-item" href="#">Separated link</a>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    <span class="no-icon">Log out</span>
                                </a>
                                
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
            <div class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            <footer class="footer">
                <div class="container">
                    <nav>
                        <ul class="footer-menu">
                        </ul>
                        <p class="copyright text-center">
                            ©
                        </p>
                    </nav>
                </div>
            </footer>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/all.js') }}"></script>
</body>
</html>
