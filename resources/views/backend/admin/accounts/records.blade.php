@extends('backend.layouts.app', ['submenu' => 'records', 'bread' => 'all_account'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>
                Accounts

                @if (Auth::user()->can('account.delete'))
                <a href="{{ route('adminTrashRecordsPage') }}" class="btn btn-danger btn-sm py-0">Trashed</a>
                @endif
            </h3>
        </div>
    </div>
</header>
<!-- body header end -->


<!-- body content start -->
<div class="body-content pb-5">
    <div class="container">
        <table id="datatable" class="display responsive nowrap mb-3" style="width:100%; position: relative;">
            <div class="loading"></div>
            <thead>
                <tr>
                    @if (Auth::user()->can('account.delete'))
                    <th class="check-box no-sort">
                        <div class="delete-check">
                            <input type="checkbox" id="checkAll">
                            <label></label>
                        </div>
                    </th>
                    @endif

                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Created At</th>

                    @if (Auth::user()->can('account.history') || Auth::user()->can('account.view') || Auth::user()->can('account.edit') || Auth::user()->can('account.delete'))
                    <th class="no-sort text-end">
                        <span class="header-action-title" title="Action">Action</span>
                        <span class="header-action">
                            @if (Auth::user()->can('account.delete'))
                            <a href="{{ route('ajaxAdminRemoveMulti') }}" title="Delete" class="delete-all-confirm bg-danger text-white">
                                <i class="icon ion-ios-trash io-18"></i>
                            </a>
                            @endif
                        </span>
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(count($admins) > 0)
                @foreach($admins as $key=>$value)
                <tr>
                    @if (Auth::user()->can('account.delete'))
                    <td title="Select" class="check-box">
                        <div class="delete-check">
                            <input type="checkbox" name="select_check[]" value="{{ $value->id }}">
                            <label></label>
                        </div>
                    </td>
                    @endif

                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->name }}</td>
                    <td title="{{ $value->email }}">{{ $value->email }}</td>
                    <td>
                        @if(count($value->getRoleNames()) > 0)
                        @foreach($value->getRoleNames() as $role)
                            <span class="badge bg-success">{{ ucfirst($role) }}</span>
                        @endforeach
                        @endif
                    </td>
                    <td>{{ date('h:i a, d M Y', strtotime($value->created_at)) }}</td>

                    @if (Auth::user()->can('account.history') || Auth::user()->can('account.view') || Auth::user()->can('account.edit') || Auth::user()->can('account.delete'))
                    <td style="text-align: right;">
                        <span class="table-action">
                            @if (Auth::user()->can('account.history') || Auth::user()->can('account.view'))
                            <a href="{{ route('adminViewAccount', ['admin_data' => $value->id]) }}" title="View">
                                <i class="icon ion-ios-eye io-19"></i>
                            </a>
                            @endif
                            @if (Auth::user()->can('account.edit'))
                            <a href="{{ route('adminUpdateAccount', ['admin_data' => $value->id]) }}" title="Edit">
                                <i class="icon ion-edit io-15"></i>
                            </a>
                            @endif
                            @if (Auth::user()->can('account.delete') && !$value->hasRole('superadmin'))
                            <a href="{{ route('adminRemoveAccount', ['admin_data' => $value->id]) }}" title="Delete" class='delete-confirm'>
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
<!-- body content end -->

@endsection


@push('scripts')
    @if (Auth::user()->can('account.delete'))
    <script type="text/javascript">
        let exportable_column = [1,2,3,4,5];
    </script>
    @else
    <script type="text/javascript">
        let exportable_column = [0,1,2,3,4];
    </script>
    @endif
@endpush

<x-data-table table="datatable" statusUrl="/accounts/status-change"/>