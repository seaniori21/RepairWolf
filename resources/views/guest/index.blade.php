@extends('backend.auth.layouts.app')

@section('content')

<!-- login section start -->
<section class="login-section">
    <div class="cover">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="company-description border-none pe-md-4 py-4">
                        <div class="xtext-center">
                            <img src="{{ asset('assets/images/logo/logo.png') }}" class="mb-3 shadow-lg" height="100" alt="">
                            <h2>TW Auto Body Shop Service</h2>
                            <!--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Veritatis, animi, vero quis obcaecati reiciendis ipsa laudantium at voluptate ipsam incidunt nesciunt iure. Odit eos, nisi repellendus. Placeat recusandae debitis cum perspiciatis ea tempora ducimus earum voluptatum beatae illo impedit ipsum minus dolorum aspernatur, accusamus. Ipsa, et. Animi qui reiciendis doloribus aperiam voluptas recusandae, nihil culpa, sunt soluta natus mollitia dolorem ab officiis perspiciatis magni vero quisquam esse minus repellat. Quas.</p>-->

                            <a class="btn btn-success btn-sm" href="{{ route('admin.login') }}">
                                {{ __('Login Now') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- login section end -->

@endsection

@push('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/auth/style.min.css') }}">
@endpush