<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <!-- favicon -->
    <link rel="icon" href="{{ asset('common/images/fav.png') }}" sizes="16x16 32x32" type="image/png">
    <!-- fonts -->
    <!-- icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.0/css/ionicons.min.css">
    <!-- bootstrap 4 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- master css -->

    <script type="text/javascript">
        function reload() {
          location.reload();
        }
    </script>
</head>
<body>
    <!-- error_page section start -->
    <style>
        .error_page{padding: 3rem 0 1rem; text-align: center;}
        .logo {max-width: 340px; margin: 0 auto; border-bottom: 2px solid #ddd; padding-bottom: 20px;}
        .content {max-width: 400px;width: 100%; margin: 0 auto;}
        h4 {color: #555; font-weight: bold;}
    </style>
    <section class="error_page">
        <div class="container" style=" min-height: calc(100vh - 166px); height: 100%; display: flex; justify-content: center;align-items: center;">
            <div class="content">
                <div class="logo">
                    <a href="{{ route('admin.home') }}">
                        <img src="{{ asset('assets/images/icon/company.png') }}" style="width: 100px;" alt="">                
                    </a>
                </div>
                {{-- <img src="{{ asset('common/images/503.png') }}" alt="@yield('message')" height="150" class="mb-4"> --}}
                <h1 class="text-danger" style="font-size: 5rem; font-weight: bold;">@yield('code') </h1>
                <h4>@yield('message')</h4>
                <p>@yield('text')</p>


                @if(Auth::guard('admin'))
                {{-- <a href="{{ route('adminProfile') }}" class="btn btn-outline-success btn-sm">Go to Profile</a> --}}
                @endif
                <a href="{{ route('admin.home') }}" class="btn btn-outline-secondary btn-sm">Homepage</a>
                <button type="button" class="btn btn-outline-info btn-sm" onclick="reload()">Reload Page</button>
            </div>
        </div>
    </section>
    <!-- error_page section end -->
</body>
</html>