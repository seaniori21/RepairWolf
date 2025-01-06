<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>TW Body Shop Manager</title>

        <!-- favicon -->
        <link rel='icon' href='{{ asset(!empty($common_data['favicon'])? $common_data['favicon'] : 'assets/images/icon/fav.png') }}' type='image/x-icon'>
        <!-- favicon -->
        
        {{-- <link rel="stylesheet" href="{{ asset('build/assets/css/style-base.min.css') }}"> --}}
        {{-- <link rel="stylesheet" href="{{ asset('build/assets/css/style.min.css') }}"> --}}

        @vite(['resources/sass/app.scss'])
        {{-- {{ \App\Helpers\ViteHelper::vite_assets() }} --}}

        @stack('style')

        {{-- @vite(['resources/css/app.css', 'resources/js/app.js','resources/assets/auth/css/style.css']) --}}
        {{-- @vite(['resources/assets/auth/style.css']) --}}
    </head>
    <body>
        <!-- credential nav start -->
        <nav class="fixed-top">
            <div class="navber credential-nav">
                <div class="container">
                    <div class="nav-content">
                        <div class="brand">
                            {{-- <a href="Javascript:void(0)">Admin Panel of {{ config('app.name', 'Laravel') }}</a> --}}
                        </div>
                        <div class="log">
                            {{-- <span>Don't have an account?</span> --}}
                            {{-- <a href="{{ route('home') }}" target="__blank">Visit Website</a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <!-- credential nav end -->

        <!-- body -->
        @yield('content')
        <!-- body -->

        @stack('scripts')
    </body>
</html>