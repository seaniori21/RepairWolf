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

                            @if($errors->any())
                                <div class="alert alert-warning d-flex align-items-center alert-dismissible" role="alert">
                                    <strong>Oops!&nbsp;</strong>
                                    @foreach ($errors->all() as $error)
                                        <span> {{ $error }}</span>
                                    @endforeach
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('admin.password.reset.submit') }}">
                                @csrf

                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="form-group mb-3">
                                    <input id="email" type="email" placeholder="Email Address" class="form-control bg-secondary" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" readonly>
                                </div>

                                <div class="form-group mb-3">
                                    <input id="password" type="password" placeholder="New Password" class="form-control" name="password" autocomplete="new-password" required autofocus>
                                </div>

                                <div class="form-group mb-3">
                                    <input id="password-confirm" type="password" placeholder="Confirm Password" class="form-control" autocomplete="new-password" name="password_confirmation" required>
                                </div>

                                <div class="form-group action-area">
                                    <button type="submit" class="btn bg-success">{{ __('Reset Password') }}</button>

                                    <div class="other-links">
                                        @if (Route::has('login'))
                                            <a class="" href="{{ route('login') }}">
                                                {{ __('Login') }}
                                            </a>
                                        @endif
                                        <span>&nbsp;</span>
                                        @if (Route::has('register'))
                                            <a class="" href="{{ route('password.request') }}">
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