@extends('backend.layouts.app', ['submenu' => 'records', 'bread' => 'records'])

@section('content')

@if (Auth::user()->can('ipallowlist.create'))
<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Add new ip address</h3>
        </div>
    </div>
</header>
<!-- body header end -->

<!-- body content start -->
<div class="body-content">
    <div class="container">
        <!-- form content -->
        <form action="{{ route('ipAllowListRecordsPagePost') }}" method="post">
            @csrf

            <div class="row">
                <!-- ip_address -->
                <div class="col-sm-6 required">
                    <label for="ip_address">IP Address</label>
                    <input type="text" minlength="7" maxlength="15" size="15" name="ip_address" id="ip_address" value="{{ old('ip_address') }}" placeholder="" class="form-control mb-3" required>
                </div>
                <!-- ip_address -->

                <!-- note -->
                <div class="col-sm-4">
                    <label for="note">Note</label>
                    <input type="text" name="note" id="note" value="{{ old('note') }}" placeholder="" class="form-control mb-3">
                </div>
                <!-- note -->

                <!-- button -->
                <div class="col-sm-2">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-success">Add Data</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- body content end -->
@endif

@if (Auth::user()->can('ipallowlist.history') || Auth::user()->can('ipallowlist.view') || Auth::user()->can('ipallowlist.edit') || Auth::user()->can('ipallowlist.delete'))
<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>
                Allowed IP Address Records

                @if (Auth::user()->can('ipallowlist.delete'))
                <a href="{{ route('ipAllowListTrashRecordsPage') }}" class="btn btn-danger btn-sm py-0">Trashed</a>
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
                    @if (Auth::user()->can('ipallowlist.delete'))
                    <th class="check-box no-sort">
                        <div class="delete-check">
                            <input type="checkbox" id="checkAll">
                            <label></label>
                        </div>
                    </th>
                    @endif

                    <th>#</th>
                    <th>IP Address</th>
                    <th>Note</th>
                    <th>Created At</th>

                    @if (Auth::user()->can('ipallowlist.history') || Auth::user()->can('ipallowlist.view') || Auth::user()->can('ipallowlist.edit') || Auth::user()->can('ipallowlist.delete'))
                    <th class="no-sort text-end">
                        <span class="header-action-title" title="Action">Action</span>
                        <span class="header-action">
                            @if (Auth::user()->can('ipallowlist.delete'))
                            <a href="{{ route('ajaxIpAllowListRemoveMulti') }}" title="Delete" class="delete-all-confirm bg-danger text-white">
                                <i class="icon ion-ios-trash io-18"></i>
                            </a>
                            @endif
                        </span>
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(count($ipAllowLists) > 0)
                @foreach($ipAllowLists as $key=>$value)
                <tr>
                    @if (Auth::user()->can('ipallowlist.delete'))
                    <td title="Select" class="check-box">
                        <div class="delete-check">
                            <input type="checkbox" name="select_check[]" value="{{ $value->id }}">
                            <label></label>
                        </div>
                    </td>
                    @endif

                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->ip_address }}</td>
                    <td>{{ $value->note ? $value->note : '--' }}</td>
                    <td>{{ date('h:i a, d M Y', strtotime($value->created_at)) }}</td>
                    @if (Auth::user()->can('ipallowlist.history') || Auth::user()->can('ipallowlist.view') || Auth::user()->can('ipallowlist.edit') || Auth::user()->can('ipallowlist.delete'))
                    <td style="text-align: right;">
                        <span class="table-action">
                            @if (Auth::user()->can('ipallowlist.history') || Auth::user()->can('ipallowlist.view'))
                            <a href="{{ route('ipAllowListView', ['data' => $value->id]) }}" title="View">
                                <i class="icon ion-ios-eye io-19"></i>
                            </a>
                            @endif
                            @if (Auth::user()->can('ipallowlist.edit'))
                            <a href="{{ route('ipAllowListUpdate', ['data' => $value->id]) }}" title="Edit">
                                <i class="icon ion-edit io-15"></i>
                            </a>
                            @endif
                            @if (Auth::user()->can('ipallowlist.delete'))
                            <a href="{{ route('ipAllowListRemove', ['data' => $value->id]) }}" title="Delete" class='delete-confirm'>
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

@push('scripts')
    @if (Auth::user()->can('ipallowlist.delete'))
    <script type="text/javascript">
        let exportable_column = [1,2,3,4];
    </script>
    @else
    <script type="text/javascript">
        let exportable_column = [0,1,2,3];
    </script>
    @endif
@endpush

<x-data-table table="datatable" statusUrl=""/>
@endif

@endsection