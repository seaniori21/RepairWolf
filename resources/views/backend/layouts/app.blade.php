<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- CSRF Token -->

        <title>TW Auto Body Shop Service</title>

        <!-- favicon -->
        <link rel='icon' href='{{ asset(!empty($common_data['favicon'])? $common_data['favicon'] : 'assets/images/logo/logo.png') }}' type='image/x-icon'>
        <!-- favicon -->

        <!-- icons -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet">
        <!-- icons -->

        <!-- Styles -->
        @vite(['resources/sass/app.scss','resources/js/app.js'])
        
        {{-- {{ \App\Helpers\ViteHelper::vite_assets() }} --}}
        
        <link rel="stylesheet" href="{{ asset('assets/layouts/master.min.css') }}">
        <style type="text/css">:root {--primary: #288bcb;--secondary: #1f6d9e;--brand: #e19e20;--brand-70: #e19e2082;--brand-30: #e19e2047;}</style>
        <script src="{{ asset('assets/layouts/settings.min.js') }}"></script>
        <style type="text/css" class="primary-settings">body .main-nav { background: var(--primary);}</style>
        <style type="text/css" class="secondary-settings">body .brand, body .right-bar .title { background: var(--secondary) !important}</style>
        <style type="text/css" class="brand-settings">body .body-content .tabs-form .nav-tabs .nav-link.active{background:var(--brand)}body .aside-nav li.active>a,body .body-header .header-title h3{color:var(--brand)}body .dataTables_wrapper .dt-buttons button{background-color:var(--brand)}body .dataTables_wrapper .dt-buttons button{border-color:var(--brand)} body .dataTables_wrapper .dt-buttons button:hover {background-color: var(--brand-70)} body .body-content .table-action a{color:var(--brand);background:var(--brand-30)} body .body-content .table-action a:hover{color:var(--brand);background:var(--brand-70)}body .aside-nav li a:hover{color:var(--brand)}body .body-content .delete-check input[type=checkbox]+label:before{border-color:var(--brand)}.body-content .delete-check input[type=checkbox]:checked+label:after{background:var(--brand)}body .body-content .status input[type=checkbox]:checked+label{background:var(--brand-70)}body .body-content .status input[type=checkbox]:checked+label{border:1px solid var(--brand-30)}body .body-content .status input[type=checkbox]+label:before{background:var(--brand-30)}body .body-content .status input[type=checkbox]+label{border:1px solid var(--brand-30)}</style>
        
        @stack('style')
        <!-- Styles -->
    </head>
    <body>
        <section class="wrapper {{Auth::user()->aside == 1?'':'aside-close'}}" data-menu="{{ $menu }}" data-submenu="{{ $submenu }}" data-bread="{{ $bread }}">
            <!-- aside content start -->
            @include('backend.layouts.left-aside')
            <!-- aside content end -->

            <!-- right bar -->
            @include('backend.layouts.right-aside')
            <!-- right bar -->

            <!-- body section start -->
            <div class="column body">
                <!-- body nav start -->
                @include('backend.layouts.top-nav')
                <!-- body nav end -->

                <div id="toast-container" aria-live="polite" aria-atomic="true" data-app-name="{{ env('APP_NAME') }}" style="position: absolute; top: 65px; right: 25px; z-index: 99;">
                    {{-- <div class="toast mb-2" data-bs-animation="true" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                          <svg class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#007aff"></rect></svg>
                          <strong class="me-auto">Bootstrap</strong>
                          <strong class="badge text-bg-success text-light">Success</strong>
                          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        
                        <div class="toast-body bg-very-light">                            
                            <span>See? Just like this.</span>
                        </div>
                    </div> --}}
                </div>

                <!-- body container start -->
                <div class="body-container">
                    <div class="container-fluid">
                        <div class="row">                            

                            <!-- breadcrumbs -->
                            @if(!empty($nav))
                                @include($nav)
                            @endif
                            <!-- breadcrumbs -->

                            <!-- body content end -->
                            @yield('content')
                            <!-- body content end -->

                        </div>
                    </div>
                </div>
                <!-- body container end -->
            </div>
            <!-- body section end -->
        </section>

        <div class="wrapper-background"></div>

        <!-- sweetalert 2 js -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- sweetalert 2 js -->

        <!-- jQuery js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!-- jQuery js -->

        <!-- momentjs -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.40/moment-timezone-with-data-10-year-range.js"></script>
        <!-- momentjs -->

        <!-- include js -->
        <script type="text/javascript">let left_aside_status_admin = "{{ Auth::user()->aside }}";</script>
        <script type="text/javascript">let myTimezone = '{{Auth::user()->tz}}';</script>
        <script src="{{ asset('assets/layouts/alert_init.min.js') }}"></script>
        <script src="{{ asset('assets/layouts/app.min.js') }}"></script>


        <x-alert-component/>

        @stack('scripts')
    </body>
</html>
