<!doctype html>
<html lang="en">
    <head>
        <title>@yield('title', config('app.name', 'Laravel'))</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

        <link rel="icon" href="favicon.ico" type="image/x-icon">
        <!-- VENDOR CSS -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/font-awesome/css/font-awesome.min.css') }}">

        <!-- MAIN CSS -->
        <link rel="stylesheet" href="{{ asset('css/main.css') }}">
        <link rel="stylesheet" href="{{ asset('css/color_skins.css') }}">

        <style>
            .cursor {
                cursor: pointer;
            }
            input[type="number"]::-webkit-outer-spin-button,
            input[type="number"]::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
            input[type="number"] {
                -moz-appearance: textfield;
            }
            .z-index-999 {
                z-index: 999!important;
            }
            .text-bold {
                font-weight: bold!important;
            }
        </style>
        @yield('header')
    </head>
    <body class="theme-cyan">

        <div id="wrapper">
            @auth
            <div id="topbar-notification">
                <div class="alert alert-success position-fixed w-100 text-center z-index-999" style="display: none" id="notif-success"></div>
                <div class="alert alert-danger position-fixed w-100 text-center z-index-999" style="display: none" id="notif-error"></div>
            </div>
            @include('layouts.assets.navbar')
            @include('layouts.assets.main_menu')
            @endauth

            @auth
                <div id="main-content">
                    <div class="container">
                        <div class="block-header">
                            <h2>@yield('title', config('app.name', 'Laravel'))</h2>
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                        @endif

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul class="list-unstyled mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            @else
                @yield('content')
            @endauth

        </div>

        <!-- Javascript -->
        <script src="{{ asset('bundles/libscripts.bundle.js') }}"></script>
        @yield('footer')
        <script>
        $(document).ready(function() {
            $(document).on('click', '.btn-hidden', function(e) {
                $(this).hide();
            });
        });
        </script>
        <script src="{{ asset('bundles/vendorscripts.bundle.js') }}"></script>

        <script src="{{ asset('bundles/mainscripts.bundle.js') }}"></script>

    </body>
</html>
