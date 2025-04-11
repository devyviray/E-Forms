<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="canonical" href="https://preview.keenthemes.com/metronic/demo2/features/icons/svg.html">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
<link href="https://preview.keenthemes.com/metronic/theme/html/demo2/dist/assets/plugins/global/plugins.bundle.css?v=7.2.9" rel="stylesheet" type="text/css">
    <link href="https://preview.keenthemes.com/metronic/theme/html/demo2/dist/assets/plugins/custom/prismjs/prismjs.bundle.css?v=7.2.9" rel="stylesheet" type="text/css">
    <link href="https://preview.keenthemes.com/metronic/theme/html/demo2/dist/assets/css/style.bundle.css?v=7.2.9" rel="stylesheet" type="text/css">
    <link rel="shortcut icon" href="https://preview.keenthemes.com/metronic/theme/html/demo2/dist/assets/media/logos/favicon.ico">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700');
        body{
            font-family: 'Poppins', sans-serif !important;
        }
    </style>
</head>

<body id="kt_body" style="background-image: url(https://preview.keenthemes.com/metronic/theme/html/demo2/dist/assets/media/bg/bg-10.jpg)" class="quick-panel-right demo-panel-right offcanvas-right header-fixed subheader-enabled highlighter-context">
    <div id="app">
        <div class="d-flex flex-column flex-root">
            <div class="d-flex flex-row flex-column-fluid page">
                <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">

                    <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
                        <div class="subheader py-2 py-lg-12  subheader-transparent " id="kt_subheader">
                            <div class=" container  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                                <div class="d-flex align-items-center flex-wrap mr-1">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center font-weight-bold my-2">
                                            <a href="{{ url('/login') }}" class="opacity-75 hover-opacity-100">
                                                <i class="fa-solid fa-house-chimney text-white icon-1x"></i>
                                            </a>
                                            <span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>
                                            <a href="" class="text-white text-hover-white opacity-75 hover-opacity-100">MasterData </a>
                                            <span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>
                                            <a href="" class="text-white text-hover-white opacity-75 hover-opacity-100">Version Release </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column-fluid">
                            <div class=" container ">
                                @yield('content')
                            </div>
                        </div>
                    </div>

                    <div class="footer bg-white py-4 d-flex flex-lg-column " id="kt_footer">
                        <div class=" container  d-flex flex-column flex-md-row align-items-center justify-content-between">
                            <div class="text-dark order-2 order-md-1">
                                <span class="text-muted font-weight-bold mr-2">2025©</span>
                                <a href="http://keenthemes.com/metronic" target="_blank" class="text-dark-75 text-hover-primary">QMD-EFORMS</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="kt_scrolltop" class="scrolltop">
        <span class="svg-icon">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                <title>Stockholm-icons / Navigation / Up-2</title>
                <desc>Created with Sketch.</desc>
                <defs></defs>
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                    <rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1"></rect>
                    <path d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z" fill="#000000" fill-rule="nonzero"></path>
                </g>
            </svg>
        </span>
    </div>

    <script>
        var KTAppSettings = {
            "breakpoints": {
                "sm": 576,
                "md": 768,
                "lg": 992,
                "xl": 1200,
                "xxl": 1200
            },
            "colors": {
                "theme": {
                    "base": {
                        "white": "#ffffff",
                        "primary": "#6993FF",
                        "secondary": "#E5EAEE",
                        "success": "#1BC5BD",
                        "info": "#8950FC",
                        "warning": "#FFA800",
                        "danger": "#F64E60",
                        "light": "#F3F6F9",
                        "dark": "#212121"
                    },
                    "light": {
                        "white": "#ffffff",
                        "primary": "#E1E9FF",
                        "secondary": "#ECF0F3",
                        "success": "#C9F7F5",
                        "info": "#EEE5FF",
                        "warning": "#FFF4DE",
                        "danger": "#FFE2E5",
                        "light": "#F3F6F9",
                        "dark": "#D6D6E0"
                    },
                    "inverse": {
                        "white": "#ffffff",
                        "primary": "#ffffff",
                        "secondary": "#212121",
                        "success": "#ffffff",
                        "info": "#ffffff",
                        "warning": "#ffffff",
                        "danger": "#ffffff",
                        "light": "#464E5F",
                        "dark": "#ffffff"
                    }
                },
                "gray": {
                    "gray-100": "#F3F6F9",
                    "gray-200": "#ECF0F3",
                    "gray-300": "#E5EAEE",
                    "gray-400": "#D6D6E0",
                    "gray-500": "#B5B5C3",
                    "gray-600": "#80808F",
                    "gray-700": "#464E5F",
                    "gray-800": "#1B283F",
                    "gray-900": "#212121"
                }
            },
            "font-family": "Poppins"
        };
    </script>

    <script src="https://preview.keenthemes.com/metronic/theme/html/demo2/dist/assets/plugins/global/plugins.bundle.js?v=7.2.9"></script>
    <script src="https://preview.keenthemes.com/metronic/theme/html/demo2/dist/assets/plugins/custom/prismjs/prismjs.bundle.js?v=7.2.9"></script>
    <script src="https://preview.keenthemes.com/metronic/theme/html/demo2/dist/assets/js/scripts.bundle.js?v=7.2.9"></script>
    <script src="{{ asset('js/all.js') }}"></script>


    <div style="position: absolute; z-index: 2147483647; left: 0px; top: 0px;"></div>
    <div></div>
    <style>
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css');
        @import url('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.7.2/font/bootstrap-icons.min.css');
        @import url("bootstrap-icons/font/bootstrap-icons.css");
    </style>
</body>

</html>
