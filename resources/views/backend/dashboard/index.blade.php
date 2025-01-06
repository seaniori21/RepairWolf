@extends('backend.layouts.app', ['bread' => 'none', 'submenu' => 'none'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>
                <small class="block" style="color: #aaa; font-size: 14px;">
                    @php
                        $currentTime = \Carbon\Carbon::now();

                        if ($currentTime->hour >= 6 && $currentTime->hour < 12) {
                            echo "Good morning!";
                        } elseif ($currentTime->hour >= 12 && $currentTime->hour < 18) {
                            echo "Good afternoon!";
                        } elseif ($currentTime->hour >= 18 && $currentTime->hour < 22) {
                            echo "Good evening!";
                        } else {
                            echo "Good night!";
                        }
                    @endphp
                </small>

                <span class="d-block">{{ Auth::user()->name }}</span>
            </h3>
        </div>
    </div>
</header>
<!-- body header end -->

<!-- body header start -->
<header class="body-content mt-0">
    <div class="container">
        <div class="profile-info mt-0 rounded-3">
            <div>
                {{-- <div class="profile p-0 rounded-3" href="javascript:void(0)">
                    <img style="object-fit: cover;" src="{{ asset('assets/images/avatars/user-new.webp') }}" alt="" id="profile_picture_preview">
                </div> --}}
                <div>
                    <div class="d-flex flex-wrap">
                        <h3 class="profile-name mt-2 mb-1 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #aaa;">
                                Welcome
                            </small>
                            <span>TW Auto Body Shop Service</span>
                        </h3>
                    </div>

                    {{-- <div class="d-flex flex-wrap mt-2">
                        <span class="profile-name mt-2 mb-1 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #aaa;">Description</small>
                            <span class="d-block">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Veritatis, animi, vero quis obcaecati reiciendis ipsa laudantium at voluptate ipsam incidunt nesciunt iure. Odit eos, nisi repellendus. Placeat recusandae debitis cum perspiciatis ea tempora ducimus earum voluptatum beatae illo impedit ipsum minus dolorum aspernatur, accusamus. Ipsa, et. Animi qui reiciendis doloribus aperiam voluptas recusandae, nihil culpa, sunt soluta natus mollitia dolorem ab officiis perspiciatis magni vero quisquam esse minus repellat. Quas.</span>
                        </span>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</header>
<!-- body header end -->

<!-- body content start -->
<div class="body-content">
    <div class="container">
        <div class="row">

        </div>
    </div>
</div>
<!-- body content end -->

@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/profile/profile.min.css') }}">
@endpush