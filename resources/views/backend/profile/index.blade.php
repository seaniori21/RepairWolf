@extends('backend.layouts.app', ['bread' => 'profile', 'submenu' => 'none'])

@section('content')

<!-- body header start -->
{{-- <header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Profile</h3>
        </div>
    </div> --}}
</header>
<!-- body header end -->

<!-- body content start -->
<div class="body-content my-4">
    <div class="container">

        <x-error-alerts/>        

        <div class="row">
            <div class="col-lg-12">
                <div class="profile-info mt-0 rounded-3">
                    {{-- <h4>Profile</h4> --}}
                    <div>
                        <a class="profile p-0 rounded-3" href="javascript:void(0)">
                            <input type="hidden" name="picture" id="profile_picture">
                            <input type="file" class="d-none" name="picture" id="profile_picture_input" accept="image/*">
                            <img style="object-fit: cover;height: 200px;background: #aaa;" src="{{ asset(Auth::user()->image? '/upload/crop-files/'.Auth::user()->image:'assets/images/avatars/user-new.webp') }}" alt="" id="profile_picture_preview">

                            @if (Auth::user()->can('profile.edit-image'))
                            <span class="cover" id="theme_cover_link">
                                <i class="icon ion-edit rounded-3"></i>
                            </span>
                            @endif
                        </a>
                        <div >
                            <h3 class="profile-name mt-2 mb-1 fw-bolder text-dark d-flex align-items-stretch">
                                <span>{{ strtoupper(Auth::user()->name) }}</span>
                                <span style="line-height: 25px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
                                        <path d="M10.0813 3.7242C10.8849 2.16438 13.1151 2.16438 13.9187 3.7242V3.7242C14.4016 4.66147 15.4909 5.1127 16.4951 4.79139V4.79139C18.1663 4.25668 19.7433 5.83365 19.2086 7.50485V7.50485C18.8873 8.50905 19.3385 9.59842 20.2758 10.0813V10.0813C21.8356 10.8849 21.8356 13.1151 20.2758 13.9187V13.9187C19.3385 14.4016 18.8873 15.491 19.2086 16.4951V16.4951C19.7433 18.1663 18.1663 19.7433 16.4951 19.2086V19.2086C15.491 18.8873 14.4016 19.3385 13.9187 20.2758V20.2758C13.1151 21.8356 10.8849 21.8356 10.0813 20.2758V20.2758C9.59842 19.3385 8.50905 18.8873 7.50485 19.2086V19.2086C5.83365 19.7433 4.25668 18.1663 4.79139 16.4951V16.4951C5.1127 15.491 4.66147 14.4016 3.7242 13.9187V13.9187C2.16438 13.1151 2.16438 10.8849 3.7242 10.0813V10.0813C4.66147 9.59842 5.1127 8.50905 4.79139 7.50485V7.50485C4.25668 5.83365 5.83365 4.25668 7.50485 4.79139V4.79139C8.50905 5.1127 9.59842 4.66147 10.0813 3.7242V3.7242Z" fill="currentColor"></path>
                                        <path d="M14.8563 9.1903C15.0606 8.94984 15.3771 8.9385 15.6175 9.14289C15.858 9.34728 15.8229 9.66433 15.6185 9.9048L11.863 14.6558C11.6554 14.9001 11.2876 14.9258 11.048 14.7128L8.47656 12.4271C8.24068 12.2174 8.21944 11.8563 8.42911 11.6204C8.63877 11.3845 8.99996 11.3633 9.23583 11.5729L11.3706 13.4705L14.8563 9.1903Z" fill="white"></path>
                                    </svg>
                                </span>
                            </h3>
                            <div class="roles-container">
                                @if(count(Auth::user()->getRoleNames()) > 0)
                                @foreach(Auth::user()->getRoleNames() as $role)
                                    <span class="me-1">
                                        <i class="icon ion-ios-contact"></i>
                                        {{ ucfirst($role) }}
                                    </span>
                                @endforeach
                                @endif
                            </div>

                            <h4 class="mb-0 mt-2 text-muted">{{ Auth::user()->email }}</h4>
                            <span class="text-muted">Joined {{ date('d M Y', strtotime(Auth::user()->created_at)) }}</span>
                        </div>
                    </div>
                </div>

                @if (Auth::user()->can('profile.edit-info') || Auth::user()->can('profile.edit-password'))
                <div class="profile-info mt-0 rounded-3">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                      @if (Auth::user()->can('profile.edit-info'))
                      <li class="nav-item pt-0" role="presentation">
                        <button class="me-2 btn btn-outline-dark {{ Session::get('accordin') ? Session::get('accordin') == 'info'? 'active':'' : 'active' }}" id="pills-info-tab" data-bs-toggle="pill" data-bs-target="#pills-info" type="button" role="tab" aria-controls="pills-info" aria-selected="{{ Session::get('accordin') == 'info'? 'true':'false' }}">Personal Informations</button>
                      </li>
                      @endif

                      <li class="nav-item pt-0" role="presentation">
                        <button class="me-2 btn btn-outline-dark {{ Session::get('accordin') == 'password'? 'active':'' }}" id="pills-password-tab" data-bs-toggle="pill" data-bs-target="#pills-password" type="button" role="tab" aria-controls="pills-password" aria-selected="{{ Session::get('accordin') == 'password'? 'true':'false' }}">Password</button>
                      </li>

                      {{-- <li class="nav-item pt-0" role="presentation">
                        <button class="me-2 btn btn-outline-dark {{ Session::get('accordin') == 'api'? 'active':'' }}" id="pills-token-tab" data-bs-toggle="pill" data-bs-target="#pills-token" type="button" role="tab" aria-controls="pills-token" aria-selected="{{ Session::get('accordin') == 'api'? 'true':'false' }}">API Token</button>
                      </li> --}}

                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        @if (Auth::user()->can('profile.edit-info'))
                        <div class="w-50 tab-pane fade {{ Session::get('accordin') ? Session::get('accordin') == 'info'? 'show active':'' : 'show active' }}" id="pills-info" role="tabpanel" aria-labelledby="pills-info-tab" tabindex="0">
                            <form action="{{ route('adminProfileUpdate') }}" method="post">
                                @csrf
                                <div class="row mt-2">
                                    <!-- name -->
                                    <div class="form-group col-md-12">
                                        <label for="name">Full name</label>
                                        <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" required placeholder="Name" class="form-control" />
                                    </div>
                                    <!-- email -->
                                    <div class="form-group col-md-12 mt-3">
                                        <label for="email">Email</label>
                                        <input type="email" value="{{ Auth::user()->email }}" name="email" required id="email" placeholder="Email" class="form-control" />
                                    </div>

                                    <!-- timezone -->
                                    {{-- <div class="form-group col-md-12 mt-3">
                                        <label for="email">Timezone</label>
                                        <select name="timezone" class="form-select form-select-sm">
                                            @if(\App\Helpers\Timezone::options())
                                            @foreach(\App\Helpers\Timezone::options() as $key => $tz)
                                            <option value="{{ $tz }}" {{ Auth::user()->tz === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div> --}}

                                    <!-- button -->
                                    <div class="form-group col-md-12 mt-4">
                                        <button type="submit" class="btn btn-success">Update</button>
                                        <button type="reset" class="btn btn-secondary">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @endif

                        @if (Auth::user()->can('profile.edit-password'))
                        <div class="w-50 tab-pane fade {{ Session::get('accordin') == 'password'? 'show active':''}}" id="pills-password" role="tabpanel" aria-labelledby="pills-password-tab" tabindex="0">
                            <form action="{{ route('adminProfilePasswordUpdate') }}" method="post">
                                @csrf
                                <div class="row mt-2">
                                    <!-- old password -->
                                    <div class="form-group col-12">
                                        <label for="old-password">Current password</label>
                                        <input type="password" name="current_password" id="old-password" required placeholder="Current password" class="form-control" autocomplete="" />
                                    </div>
                                    <!--new password -->
                                    <div class="form-group col-12 mt-3">
                                        <label for="new-password">New password</label>
                                        <input type="password" name="password" id="new-assword" required placeholder="New password" class="form-control" autocomplete="" />
                                    </div>
                                    <!-- confirm password -->
                                    <div class="form-group col-12 mt-3">
                                        <label for="confirm-password">Confirm password</label>
                                        <input type="password" name="password_confirmation" required id="confirm-password" placeholder="Confirm password" class="form-control" autocomplete="" />
                                    </div>

                                    <!-- button -->
                                    <div class="form-group col-12 mt-3 mt-2">
                                        <button type="submit" class="btn btn-success">Change password</button>
                                        <button type="reset" class="btn btn-secondary">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @endif

                        {{-- <div class="tab-pane fade {{ Session::get('accordin') == 'api'? 'show active':''}}" id="pills-token" role="tabpanel" aria-labelledby="pills-token-tab" tabindex="0">
                            <form action="{{ route('adminProfileGenerateAccessToken') }}" method="post">
                                @csrf
                                <div class="row">
                                    <!-- name -->

                                    <div class="col-md-12 mb-0">
                                        <label for="name" class="mb-0">Access Tokens</label>
                                    </div>

                                    @if(count(Auth::user()->tokens) > 0)
                                    @foreach (Auth::user()->tokens as $token)
                                    <div class="form-group col-md-10">
                                        <input type="text" value="{{ Auth::user()->api_token }}" placeholder="Name" class="form-control api_access_token" />
                                    </div>
                                    <div class="col-md-2">
                                        <a href="javascript:void(0)" title="Copy to clipboard" class="badge badge-success copy_to_clipboard_btn" style="padding: 9px 12px;">
                                            <i class="icon ion-ios-copy io-18"></i>
                                        </a>
                                        <a href="{{ route('adminProfileRemoveAccessToken', ['data' => $token->id]) }}" class="badge badge-danger delete-confirm" style="padding: 9px 12px;">
                                            <i class="icon ion-ios-trash io-18"></i>
                                        </a>
                                    </div>
                                    @endforeach
                                    @endif

                                    <!-- button -->
                                    <div class="form-group col-md-6">
                                        <button type="submit" class="btn submit-btn">Generate New Access Token</button>
                                    </div>
                                </div>
                            </form>
                        </div> --}}

                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- body content end -->

@if (Auth::user()->can('profile.edit-image'))
<x-cropper />
@else
<style type="text/css">#profile_picture_preview { cursor: default; }</style>
@endif

@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/profile/profile.min.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('assets/profile/settings.min.js') }}"></script>
@endpush