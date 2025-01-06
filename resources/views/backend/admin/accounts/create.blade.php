@extends('backend.layouts.app', ['submenu' => 'create', 'bread' => 'create_account'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Create account</h3>
        </div>
    </div>
</header>
<!-- body header end -->


<!-- body content start -->
<div class="body-content">
    <div class="container">
        <!-- form content -->
        <form action="{{ route('adminCreateAccountUpdate') }}" method="post">@csrf
            <div class="row">
                <div class="media-content col-sm-12">
                    <div class="row">

                        <div class="col-md-3">
                            <h5>Profile Picture <strong class="text-danger">*</strong></h5>

                            <input type="hidden" name="image" id="profile_picture">
                            <figure class="media-records media-img rounded-4" style="height: 200px;">
                                <img src="{{ asset('assets/images/media/logo_default.png') }}" class="" style="object-fit: cover;" id="profile_picture_preview" alt="">
                                <figcaption>
                                    <div>
                                        <a href="javascript:void(0)" id="profile_picture_link"><i class="icon ion-ios-paperplane io-28"></i></a>
                                        <input type="file" class="d-none" id="profile_picture_input" accept="image/*">
                                    </div>
                                </figcaption>
                            </figure>
                        </div>

                    </div>
                </div>
                
                <!-- name -->
                <div class="form-group col-sm-12 required mb-3">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Name" class="form-control" required>
                </div>

                <!-- timezone -->
                {{-- <div class="form-group col-md-4 mb-3">
                    <label for="email">Timezone</label>
                    <select name="timezone" class="form-select form-select-sm">
                        @if(\App\Helpers\Timezone::options())
                        @foreach(\App\Helpers\Timezone::options() as $key => $tz)
                        <option value="{{ $tz }}">{{ $tz }}</option>
                        @endforeach
                        @endif
                    </select>
                </div> --}}

                <!-- email -->
                <div class="form-group col-sm-6 required mb-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Email" class="form-control" required pattern="[A-Za-z0-9._+-]+@[A-Za-z0-9]+\.[a-z]{2,}"  title="must be a valid email address">
                </div>

                <!-- password -->
                <div class="form-group col-sm-6 required mb-3">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Password" class="form-control" required>
                </div>                

            	<div class="form-group col-sm-12 required mb-3">
                    <label>Assign Roles</label>

                    <div class="select-role-container w-100" style="display:none;">
                        <select class="select-roles-multiple" name="roles[]" multiple="multiple" style="width: 100%;" required>
                        @if(count($roles) > 0)
                        @foreach($roles as $key => $value)
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                        @endforeach
                        @endif
                        </select>
                    </div>
                </div>

                <!-- button -->
                <div class="form-group col-12 mb-5">
                    <button type="submit" class="btn btn-success">Create Account</button>
                    <button type="reset" class="btn btn-danger">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- body content end -->

<x-cropper>
    <script type="text/javascript">
        var profile_img = [
            {
                'id': 'profile_picture',
                'modal_title': 'Profile Picture',
                'ratio': 1,
                'width': 300,
                'height': 300,
            },
        ];
    </script>
</x-cropper>

@endsection


@push('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/accounts/style.min.css') }}">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="{{ asset('assets/accounts/scripts.min.js') }}"></script>
@endpush