@extends('backend.layouts.app', ['submenu' => 'records', 'bread' => 'records'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>
                Vehicle Records
                @if (Auth::user()->can('vehicle.delete'))
                <a href="{{ route('vehicleTrashRecordsPage') }}" class="btn btn-danger btn-sm py-0">Trashed</a>
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
                    @if (Auth::user()->can('vehicle.delete'))
                    <th class="check-box no-sort">
                        <div class="delete-check">
                            <input type="checkbox" id="checkAll">
                            <label></label>
                        </div>
                    </th>
                    @endif

                    <th>#</th>

                    <th>Customer</th>
                    <th>License Plate</th>
                    <th>VIN</th>
                    <th>Year, Make, Model</th>

                    <th>Created At</th>
                    @if (Auth::user()->can('vehicle.history') || Auth::user()->can('vehicle.view') || Auth::user()->can('vehicle.edit') || Auth::user()->can('vehicle.delete'))
                    <th class="no-sort text-end">
                        <span class="header-action-title" title="Action">Action</span>
                        <span class="header-action">
                            @if (Auth::user()->can('vehicle.delete'))
                            <a href="{{ route('ajaxVehicleRemoveMulti') }}" title="Delete" class="delete-all-confirm bg-danger text-white">
                                <i class="icon ion-ios-trash io-18"></i>
                            </a>
                            @endif
                        </span>
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(count($vehicles) > 0)
                @foreach($vehicles as $key=>$value)
                <tr>
                    @if (Auth::user()->can('vehicle.delete'))
                    <td title="Select" class="check-box">
                        <div class="delete-check">
                            <input type="checkbox" name="select_check[]" value="{{ $value->id }}">
                            <label></label>
                        </div>
                    </td>
                    @endif

                    <td>{{ $key+1 }}</td>

                    <td>
                        @if($value->customer)
                        @if (Auth::user()->can('customer.view'))
                        <a href="{{ route('customerView', ['data' => $value->customer->id]) }}" class="ps-0 d-flex align-items-center relative" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>

                            <span class="ms-1">{{ $value->customer->first_name.' '.$value->customer->last_name }}</span>
                        </a>
                        @else
                            {{ $value->customer->first_name.' '.$value->customer->last_name }}
                        @endif
                        @else N/A @endif
                    </td>
                    <td>{{ $value->license_plate }}</td>
                    <td>{{ $value->vin }}</td>
                    <td>{{ $value->year }}{{ ', '.$value->make }}{{ ', '.$value->model }}</td>

                    <td>{{ date('h:i a, d M Y', strtotime($value->created_at)) }}</td>
                    @if (Auth::user()->can('vehicle.history') || Auth::user()->can('vehicle.view') || Auth::user()->can('vehicle.edit') || Auth::user()->can('vehicle.delete'))
                    <td style="text-align: right;">
                        <span class="table-action">
                            @if (Auth::user()->can('vehicle.view'))
                            <a href="{{ route('vehicleView', ['data' => $value->id]) }}" title="View">
                                <i class="icon ion-ios-eye io-19"></i>
                            </a>
                            @endif
                            @if (Auth::user()->can('vehicle.edit'))
                            <a href="{{ route('vehicleUpdate', ['data' => $value->id]) }}" title="Edit">
                                <i class="icon ion-edit io-15"></i>
                            </a>
                            @endif
                            @if (Auth::user()->can('vehicle.delete'))
                            <a href="{{ route('vehicleRemove', ['data' => $value->id]) }}" title="Delete" class='delete-confirm'>
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
    @if (Auth::user()->can('vehicle.delete'))
    <script type="text/javascript">
        let exportable_column = [1,2,3,4,5,6];
    </script>
    @else
    <script type="text/javascript">
        let exportable_column = [0,1,2,3,4,5];
    </script>
    @endif
@endpush

<x-data-table table="datatable" statusUrl=""/>