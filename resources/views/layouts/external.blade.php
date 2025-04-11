<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Document</title>
    <style>
        @import url('https://preview.keenthemes.com/metronic/theme/html/demo2/dist/assets/css/style.bundle.css?v=7.2.9');
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap');
        body{
            font-family: 'Roboto', sans-serif !important;
        }
        .swal2-popup {
            justify-items: center;
        }
    </style>
</head>
<body>
    <div id="app">
        @yield('content')
    </div>
<script src="{{ asset('js/all.js') }}"></script>
</body>
</html>