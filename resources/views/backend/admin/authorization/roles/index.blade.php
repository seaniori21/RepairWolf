@extends('backend.layouts.app', ['bread' => 'roles', 'submenu' => 'roles'])

@section('content')

<!-- body header start -->
@if (Auth::user()->can('roles.create'))
<header class="body-header mb-2">
    <div class="container">
        <div class="header-title justify-content-start">
            <h3>Roles</h3>
            <a href="{{ route('adminRolesCreate') }}" class="btn btn-success btn-sm ms-3">Add new role</a>
        </div>
        <p class="text-muted mt-2">A role provided access to predefined menus and features <br>so that depending on assigned role an administrator can have access to what he need</p>
    </div>
</header>
@endif
<!-- body header end -->


<!-- body content start -->
<div class="body-content pb-5">
    <div class="container">

        <div class="row roles-container">
            @if(count($roles) > 0)
            @foreach($roles as $key=>$value)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-success">
                  <div class="card-body d-flex justify-content-between">
                    <div class="card-left">
                        <p class="card-text text-muted">Total {{ $value->accounts? count($value->accounts) : 0 }} Users</p>
                        <h4 class="card-title mb-1">
                            <b>{{ ucwords($value->name) }}</b>
                        </h4>

                        @if (Auth::user()->can('roles.view'))
                        <a href="{{ route('adminRolesView', ['data' => $value->id]) }}" class="btn btn-success btn-sm py-0" style="font-size: 12px;"><b>View</b></a>
                        @endif
                        
                        @if (Auth::user()->can('roles.edit'))
                        <a href="{{ route('adminRolesEdit', ['data' => $value->id]) }}" class="btn btn-primary btn-sm py-0" style="font-size: 12px;"><b>Edit</b></a>
                        @endif

                        @if (Auth::user()->can('roles.delete') && strtolower($value->name) != 'superadmin')
                        <a href="{{ route('adminRemoveRoles', ['data' => $value->id]) }}" class="btn btn-danger btn-sm py-0 delete-confirm" style="font-size: 12px;"><b>Delete</b></a>
                        @endif
                    </div>
                    <div class="card-right">
                        <div class="d-flex h-100 justify-content-between align-content-around flex-column align-items-end">
                            <div class="card-imgs">
                                @if($value->accounts)
                                @foreach($value->accounts as $acc_no => $account)
                                <img src="{{ asset($account->image ? 'upload/crop-files/'.$account->image : 'assets/images/avatars/user-new.webp') }}" class="{{ $acc_no }} rounded-circle" style="width: 30px; height: 30px;object-fit: cover; border: 2px solid var(--brand-30); {{ $acc_no > 0 ? 'margin-left: -15px;' : '' }}">
                                @endforeach
                                @endif
                            </div>

                            <small class="card-text text-muted">{{ $value->permissions ? count($value->permissions) : 0 }} Permissions</small>                            
                        </div>
                    </div>
                  </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>

    </div>
</div>
<!-- body content end -->

@endsection

@push('scripts')
@endpush