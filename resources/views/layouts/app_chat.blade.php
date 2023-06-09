
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
{{--<head>--}}
{{--    <meta charset="utf-8">--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1">--}}

{{--    <!-- CSRF Token -->--}}
{{--    <meta name="csrf-token" content="{{ csrf_token() }}">--}}

{{--    <title>{{ config('app.name', 'Laravel') }}</title>--}}

{{--    <!-- Scripts -->--}}
{{--    <script src="{{ asset('js/app.js') }}" defer></script>--}}

{{--    <!-- Fonts -->--}}
{{--    <link rel="dns-prefetch" href="//fonts.gstatic.com">--}}
{{--    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">--}}

{{--    <!-- Styles -->--}}
{{--    <link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
{{--</head>--}}


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Start css -->
    <!-- Switchery css -->
    <link href="{{ asset('assets/plugins/switchery/switchery.min.css') }}" rel="stylesheet" />
    <!-- jvectormap css -->
    <link href="{{ asset('assets/plugins/jvectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />
    <!-- Datepicker css -->
    <link href="{{ asset('assets/plugins/datepicker/datepicker.min.css') }}" rel="stylesheet" />

    <!-- DataTables css -->
    <link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive Datatable css -->
    <link href="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>

    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/flag-icon.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css"/>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>


    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">


    <style type="text/css">
        .message p{
            border-radius: 10px;
            padding: 10px 20px 10px 8px;
            margin-top: 5px;
            display: inline-block;
            width: auto;
            margin: 0px;
        }
        .message-send p{
            background: #e0e3e6;
            color: #2f2d2d;
        }
        .message-send{
            text-align: right;
            margin-top: 5px;
        }
        .message-receive p{
            background: #435f7a;
            color: #f5f5f5;
        }
        .message-receive{
            margin-top: 5px;
        }

        .scrollable {
            overflow: hidden;
            overflow-y: scroll;
            height: calc(100vh - 25vh);
        }
        .message-input{
            border: none;
            border-radius: 0px;
            background: #f2f2f2;
        }

    </style>
    <!-- Styles -->
{{--    <link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
<!-- Scripts -->
    {{--    @vite(['resources/sass/app.scss', 'resources/js/app.js'])--}}
</head>
<body>
<div id="app">
    <div class="leftbar">
        <!-- Start Sidebar -->
    @include('includes.auth_sidebar')
    <!-- End Sidebar -->
    </div>
    <div class="rightbar">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
