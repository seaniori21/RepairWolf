@extends('backend.layouts.app', ['submenu' => 'records', 'bread' => 'records'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>
                Customer Records
                @if (Auth::user()->can('customer.delete'))
                <a href="{{ route('customerTrashRecordsPage') }}" class="btn btn-danger btn-sm py-0">Trashed</a>
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
                    @if (Auth::user()->can('customer.delete'))
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
                    <th>Mobile</th>
                    {{-- <th>City</th> --}}
                    {{-- <th>State</th> --}}
                    <th>Created At</th>
                    {{-- <th>Status</th> --}}

                    @if (Auth::user()->can('customer.history') || Auth::user()->can('customer.view') || Auth::user()->can('customer.edit') || Auth::user()->can('customer.delete'))
                    <th class="no-sort text-end">
                        <span class="header-action-title" title="Action">Action</span>
                        <span class="header-action">
                            @if (Auth::user()->can('customer.delete'))
                            <a href="{{ route('ajaxCustomerRemoveMulti') }}" title="Delete" class="delete-all-confirm bg-danger text-white">
                                <i class="icon ion-ios-trash io-18"></i>
                            </a>
                            @endif
                        </span>
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(count($customers) > 0)
                @foreach($customers as $key=>$value)
                <tr>
                    @if (Auth::user()->can('customer.delete'))
                    <td title="Select" class="check-box">
                        <div class="delete-check">
                            <input type="checkbox" name="select_check[]" value="{{ $value->id }}">
                            <label></label>
                        </div>
                    </td>
                    @endif

                    <td>{{ $key+1 }}</td>
                    
                    @php
                        $names = [
                            $value->first_name,
                            $value->last_name
                        ];
                    @endphp

                    <td title="{{ implode(' ', $names) }}">{{ Illuminate\Support\Str::limit(implode(' ', $names), 15, ' (...)') }}</td>

                    <td>{{ $value->email }}</td>
                    <td>{{ $value->mobile }}</td>

                    {{-- <td title="{{ $value->city }}">{{ $value->city }}</td> --}}
                    {{-- <td title="{{ $value->state }}">{{ $value->state }}</td> --}}
                    <td>
                        {{ date('h:i a, d M Y', strtotime($value->created_at)) }}
                    </td>

                    @if (Auth::user()->can('customer.history') || Auth::user()->can('customer.view') || Auth::user()->can('customer.edit') || Auth::user()->can('customer.delete'))
                    <td style="text-align: right;">
                        <span class="table-action">
                            @if (Auth::user()->can('customer.history') || Auth::user()->can('customer.view'))
                            <a href="{{ route('customerView', ['data' => $value->id]) }}" title="View">
                                <i class="icon ion-ios-eye io-19"></i>
                            </a>
                            @endif
                            @if (Auth::user()->can('customer.edit'))
                            <a href="{{ route('customerUpdate', ['data' => $value->id]) }}" title="Edit">
                                <i class="icon ion-edit io-15"></i>
                            </a>
                            @endif
                            @if (Auth::user()->can('customer.delete'))
                            <a href="{{ route('customerRemove', ['data' => $value->id]) }}" title="Delete" class='delete-confirm'>
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
    @if (Auth::user()->can('customer.delete'))
    <script type="text/javascript">
        let exportable_column = [1,2,3,4,5];
    </script>
    @else
    <script type="text/javascript">
        let exportable_column = [0,1,2,3,4];
    </script>
    @endif
@endpush

<x-data-table table="datatable" statusUrl=""/>