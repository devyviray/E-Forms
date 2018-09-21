<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Styles -->
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <style>
        .sidebar:after, body>.navbar-collapse:after{
            background: none;
            background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));
        }
        .space-left{
            padding-left: 49px;
        }
        .error{
            color: #a94442;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="wrapper">
            <div class="sidebar">
                <!--
                    Tip 1: You can change the color of the sidebar using: data-color="purple | blue | green | orange | red"
                    
                    Tip 2: you can also add an image using data-image tag
                -->
                <div class="sidebar-wrapper">
                    <div class="logo">
                        <span class="simple-text"> E-FORMS </span>
                    </div>
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">
                                <i class="nc-icon nc-chart-pie-35"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li>
                            <a data-toggle="collapse" href="#Forms" class="collapsed" aria-expanded="false">
                                <div class="nav-link">
                                    <i class="nc-icon nc-notes"></i>
                                    <p>Forms</p>
                                </div>
                            </a>
                            <div class="collapse space-left" id="Forms" style="">
                                <ul class="nav" style="list-style-type: none;">
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
                            </div>
                        </li>
                        <li>
                        @role('notified') 
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('notified') }}">
                                <i class="nc-icon nc-circle-09"></i>
                                <p>Notified</p>
                            </a>
                        </li>
                        @endrole
                        </li>
                        <li>
                        @role('administrator,mr') 
                        <a data-toggle="collapse" href="#Users" class="collapsed" aria-expanded="false">
                                <div class="nav-link">
                                    <i class="nc-icon nc-circle-09"></i>
                                    <p>Users</p>
                                </div>
                            </a>
                            <div class="collapse space-left" id="Users">
                                <ul class="nav" style="list-style-type: none;">
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
                            </div>
                        </li>
                        <li>
                            <a data-toggle="collapse" href="#MasterData" class="collapsed" aria-expanded="false">
                                <div class="nav-link">
                                    <i class="nc-icon nc-paper-2"></i>
                                    <p>Master Data</p>
                                </div>
                            </a>
                            <div class="collapse space-left" id="MasterData" style="">
                                <ul class="nav" style="list-style-type: none;">
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
                            </div>
                        </li>
                        @endrole
                        <li class="nav-item active active-pro">
                            <a class="nav-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                <i class="nc-icon nc-tap-01"></i>
                                <span class="no-icon">Log out</span>
                            </a>
                        
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="main-panel">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg " color-on-scroll="500">
                    <div class=" container-fluid  ">
                        <span class="navbar-brand">{{ $location }}</span>
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
