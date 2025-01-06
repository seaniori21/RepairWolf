@extends('backend.layouts.app', ['bread' => 'permissions', 'submenu' => 'permissions'])

@section('content')

@if (Auth::user()->can('permissions.create'))
<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Add Perimission Item</h3>
        </div>
    </div>
</header>
<!-- body header end -->


<!-- body content start -->
<div class="body-content">
    <div class="container">
        <!-- form content -->
        <form action="{{ route('adminPermissionSave') }}" method="post">@csrf
            <div class="row">

                <div class="form-group col-sm-5 required mb-4">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Permission Name" class="form-control">
                </div>

                <div class="form-group col-sm-5 required mb-4">
                    <label for="group">Group</label>
                    <input type="text" name="group" id="group" value="{{ old('group') }}" placeholder="Permission Group" class="form-control">
                </div>

                <!-- button -->
                <div class="form-group col-2">
                    <label for="location">&nbsp;</label>
                    <button type="submit" class="btn btn-success mt-0">Add</button>
                    <button type="reset" class="btn btn-secondary mt-0">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- body content end -->
<div class="body-content"><hr></div>
@endif

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Permissions</h3>
        </div>
    </div>
</header>
<!-- body header end -->


<!-- body content start -->
@if (Auth::user()->can('permissions.view') || Auth::user()->can('permissions.edit') || Auth::user()->can('permissions.delete'))
<div class="body-content pb-5">
    <div class="container">
        <table id="datatable" class="display responsive nowrap mb-3" style="width:100%; position: relative;">
            <div class="loading"></div>
            <thead>
                <tr>
                    @if (Auth::user()->can('permissions.delete'))
                    <th class="check-box no-sort">
                        <div class="delete-check">
                            <input type="checkbox" id="checkAll">
                            <label></label>
                        </div>
                    </th>
                    @endif

                    <th>#</th>
                    <th>Name</th>
                    <th>Group</th>

                    @if (Auth::user()->can('permissions.edit') || Auth::user()->can('permissions.delete'))
                    <th class="no-sort text-end">
                        <span class="header-action-title" title="Action">Action</span>
                        <span class="header-action">
                            @if (Auth::user()->can('permissions.delete'))
                            <a href="{{ route('ajaxPermissionRemoveMulti') }}" title="Delete" class="delete-all-confirm bg-danger text-white">
                                <i class="icon ion-ios-trash io-18"></i>
                            </a>
                            @endif
                        </span>
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(count($permissions) > 0)
                @foreach($permissions as $key=>$value)
                <tr>
                    @if (Auth::user()->can('permissions.delete'))
                    <td title="Select" class="check-box">
                        <div class="delete-check">
                            <input type="checkbox" name="select_check[]" value="{{ $value->id }}">
                            <label></label>
                        </div>
                    </td>
                    @endif

                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->group_name }}</td>
                    @if (Auth::user()->can('permissions.edit') || Auth::user()->can('permissions.delete'))
                    <td style="text-align: right;">
                        <span class="table-action">
                            @if (Auth::user()->can('permissions.edit'))
                            <a href="{{ route('adminPermissionEdit', ['data' => $value->id]) }}" title="Edit">
                                <i class="icon ion-edit io-15"></i>
                            </a>
                            @endif

                            @if (Auth::user()->can('permissions.delete'))
                            <a href="{{ route('adminRemovePermission', ['data' => $value->id]) }}" title="Delete" class='delete-confirm'>
                                <i class="icon ion-ios-trash io-18"></i>
                            </a>
                            @endif
                        </span>
                    </td>
                    @endif
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>        
    </div>
</div>
@endif
<!-- body content end -->

@endsection

@push('scripts')
    @if (Auth::user()->can('permissions.delete'))
    <script type="text/javascript">
        let exportable_column = [1,2,3];
    </script>
    @else
    <script type="text/javascript">
        let exportable_column = [0,1,2,3];
    </script>
    @endif
@endpush

<x-data-table table="datatable" statusUrl="/authorization/permissions/status-change"/>