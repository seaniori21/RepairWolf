@extends('backend.auth.layouts.app')

@section('content')

<!-- register section start -->
<section class="login-section">
    <div class="cover">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="company-description pe-md-4 py-4">
                        <div>
                            <h2>Lorem ipsum dolor sit amet, consectetur adipisicing elit</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>
                    </div>
                </div>

               <div class="col-lg-6">
                    <div class="form-content pl-lg-4">
                        <div>
                            <h2>{{ __('Reset Password') }}</h2>                                    

                            @if (session('error'))
                                <div class="alert alert-warning d-flex align-items-center alert-dismissible" role="alert">
                                    <span>{{ session('error') }}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if (session('status'))
                                <div class="alert alert-success d-flex align-items-center alert-dismissible" role="alert">
                                    <span>{{ session('status') }}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-warning d-flex align-items-center alert-dismissible" role="alert">
                                    <strong>Oops! </strong>
                                    @foreach ($errors->all() as $error)
                                        <span>{{ $error }}</span>
                                    @endforeach
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('admin.password.email.submit') }}">
                                @csrf

                                <div class="form-group mb-3">
                                    <input id="email" type="email" placeholder="Email Address" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                </div>

                                <div class="form-group action-area">
                                    <button type="submit" class="btn bg-secondary">{{ __('Send Password Reset Link') }}</button>

                                    <div class="other-links">
                                        @if (Route::has('admin.login'))
                                            <a class="text-decoration-none" href="{{ route('admin.login') }}">
                                                {{ __('Login') }}
                                            </a>
                                        @endif
                                        @if (Route::has('admin.register'))
                                            <span class="text-white">&nbsp;|&nbsp;</span>
                                            <a class="text-decoration-none" href="{{ route('admin.register') }}">
                                                {{ __('Register') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- register section end -->

@endsection

@push('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/auth/style.min.css') }}">
@endpush