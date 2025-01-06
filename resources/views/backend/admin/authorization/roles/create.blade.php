@extends('backend.layouts.app', ['bread' => 'create-roles', 'submenu' => 'roles'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Add Role Item</h3>
        </div>
    </div>
</header>
<!-- body header end -->


<!-- body content start -->
<div class="body-content">
    <div class="container">
        <!-- form content -->
        <form action="{{ route('adminRolesCreateSave') }}" method="post">@csrf
            <div class="row">

                <div class="form-group col-sm-12 required mb-3">
                    <label for="name">Role Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Role Name" class="form-control">
                </div>

                <div class="form-group col-sm-12 required mb-4">
                    <label for="group" class="fw-bolder">Role Permissions</label>

                    <div class="permission-container table-responsive">
                        <table class="table">
                          <tbody>
                            <tr>
                                <th>Administrator Access </th>
                                <td>
                                    <div class="form-check mb-0">
                                      <input class="form-check-input" type="checkbox" value="" id="admin-permission-check-all">
                                      <label class="form-check-label" for="admin-permission-check-all">
                                        Select All
                                      </label>
                                    </div>
                                </td>
                            </tr>
                            @if(count($permissions) > 0)
                            @foreach($permissions as $group_name => $permissions)
                            <tr>
                                <th scope="row">
                                    <strong>{{ ucwords(str_replace('.', ' ', $group_name)) }}</strong>
                                </th>

                                @php
                                    $group_simplify = str_replace('.', '', $group_name);
                                @endphp

                                @if(count($permissions) > 0)
                                @if(count($permissions) > 1)
                                    <td>
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox" value="" id="permission-check-all-{{ $group_simplify }}">
                                            <label class="form-check-label" for="permission-check-all-{{ $group_simplify }}">
                                                Select All
                                            </label>
                                        </div>
                                    </td>
                                @endif
                                @foreach($permissions as $perm_id => $permission)
                                    @php
                                        $permission_name = explode('.', $permission);
                                        $permission_label = $permission_name[count($permission_name)-1];
                                    @endphp
                                    
                                    <td>
                                        <div class="form-check mb-0">
                                          <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission }}" id="permission-check-{{ $group_simplify }}-{{ $perm_id }}">
                                          <label class="form-check-label" for="permission-check-{{ $group_simplify }}-{{ $perm_id }}">
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

                <!-- button -->
                <div class="form-group col-12 mb-5">
                    <button type="submit" class="btn btn-success mt-0">Add Role</button>
                    <button type="reset" class="btn btn-secondary mt-0">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- body content end -->

@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('assets/authorization/roles-settings.min.js') }}"></script>
@endpush
