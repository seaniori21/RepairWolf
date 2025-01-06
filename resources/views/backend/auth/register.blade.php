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
                            <h2>TW Auto Body Shop Service</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Veritatis, animi, vero quis obcaecati reiciendis ipsa laudantium at voluptate ipsam incidunt nesciunt iure. Odit eos, nisi repellendus. Placeat recusandae debitis cum perspiciatis ea tempora ducimus earum voluptatum beatae illo impedit ipsum minus dolorum aspernatur, accusamus. Ipsa, et. Animi qui reiciendis doloribus aperiam voluptas recusandae, nihil culpa, sunt soluta natus mollitia dolorem ab officiis perspiciatis magni vero quisquam esse minus repellat. Quas.</p>
                        </div>
                    </div>
                </div>

               <div class="col-lg-6">
                    <div class="form-content pl-lg-4">
                        <div>
                            <h2>Admin Registration</h2>                                    

                            @if($errors->any())
                                <div class="alert alert-warning d-flex align-items-center alert-dismissible" role="alert">
                                    <strong>Oops!</strong>
                                    @foreach ($errors->all() as $error)
                                        <span>{{ $error }}</span>
                                    @endforeach
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('admin.register.submit') }}">
                                @csrf

                                <div class="form-group mb-3">
                                    <input id="name" type="text" placeholder="Your Name" class="form-control" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                </div>

                                <div class="form-group mb-3">
                                    <input id="email" type="email" placeholder="Email Address" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                </div>

                                <div class="form-group mb-3">
                                    <input id="password" type="password" placeholder="Password" class="form-control" name="password" required>
                                </div>

                                <div class="form-group mb-3">
                                    <input id="password-confirm" type="password" placeholder="Confirm Password" class="form-control" name="password_confirmation" required>
                                </div>

                                <div class="form-group action-area d-block">
                                    <button type="submit" class="btn bg-secondary me-2">Register</button>

                                    @if (Route::has('admin.login'))
                                        <a class="ml-2 text-decoration-none" href="{{ route('admin.login') }}">
                                            {{ __('Already registered? Login') }}
                                        </a>
                                    @endif
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