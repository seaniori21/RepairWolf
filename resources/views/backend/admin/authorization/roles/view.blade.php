@extends('backend.layouts.app', ['bread' => 'edit-roles', 'submenu' => 'roles'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>View Role Item</h3>
        </div>
    </div>
</header>
<!-- body header end -->


<!-- body content start -->
<div class="body-content">
    <div class="container">
        <div class="row">
            <div class="form-group col-sm-12 required mb-3">
                <label for="name">Role Name</label>
                <h4 class="d-block">{{ $data->name }}</h4>
            </div>

            <div class="form-group col-sm-12 required mb-4">
                <label for="group" class="fw-bolder mb-2">Role Permissions</label>

                <div class="permission-container table-responsive">
                    <table class="table">
                      <tbody>
                        @if(count($permissions) > 0)
                        @foreach($permissions as $group_name => $permissions)
                        <tr>
                            <th scope="row">
                                <strong>{{ ucwords(str_replace('.', ' ', $group_name)) }}</strong>
                            </th>

                            @if(count($permissions) > 0)
                            @foreach($permissions as $perm_id => $permission)
                                @php
                                    $permission_name = explode('.', $permission);
                                    $permission_label = $permission_name[count($permission_name)-1];
                                @endphp
                                
                                <td>
                                    <div class="form-check mb-0">
                                      <input class="form-check-input" type="checkbox" name="permissions[]" onclick="return false;" {{ $data->hasPermissionTo($permission) ? 'checked' : '' }} value="{{ $permission }}" id="permission-check-{{ $group_name }}-{{ $perm_id }}">
                                      <label class="form-check-label" for="permission-check-{{ $group_name }}-{{ $perm_id }}">
                                        {{ ucwords($permission_label) }}
                                      </label>
                                    </div>
                                </td>
                            @endforeach
                            @endif
                        </tr>
                        @endforeach
                        @endif

                      </tbody>
                    </table>
                </div>
            </div>

            @if (Auth::user()->can('roles.edit') || Auth::user()->can('roles.delete'))
            <div class="d-flex flex-wrap mt-0 mb-5" style="gap: 10px;">
                @if (Auth::user()->can('roles.edit'))
                <a href="{{ route('adminRolesEdit', ['data' => $data->id]) }}" class="btn d-block btn-outline-primary">Edit</a>
                @endif

                @if (Auth::user()->can('roles.delete') && strtolower($data->name) != 'superadmin')
                <a href="{{ route('adminRolesEdit', ['data' => $data->id]) }}" class="btn d-block btn-outline-danger delete-confirm">Delete</a>
                @endif
            </span>
            @endif
        </div>
    </div>
</div>
<!-- body content end -->

@endsection