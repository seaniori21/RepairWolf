@extends('backend.layouts.app', ['submenu' => 'types', 'bread' => 'types'])

@section('content')

@if (Auth::user()->can('paymentType.create'))
<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Add new payment type</h3>
        </div>
    </div>
</header>
<!-- body header end -->

<!-- body content start -->
<div class="body-content">
    <div class="container">
        <!-- form content -->
        <form action="{{ route('paymentTypeRecordsPagePost') }}" method="post">
            @csrf

            <div class="row">
                <!-- name -->
                <div class="col-sm-10 required">
                    <label for="name">Payment Type Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="" class="form-control mb-3" required>
                </div>
                <!-- name -->

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

@if (Auth::user()->can('paymentType.history') || Auth::user()->can('paymentType.view') || Auth::user()->can('paymentType.create') || Auth::user()->can('paymentType.edit') || Auth::user()->can('paymentType.delete'))
<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>
                Payment Types Records
                <a href="{{ route('paymentTypeTrashRecordsPage') }}" class="btn btn-danger btn-sm py-0">Trashed</a>
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
                    @if (Auth::user()->can('paymentType.delete'))
                    <th class="check-box no-sort">
                        <div class="delete-check">
                            <input type="checkbox" id="checkAll">
                            <label></label>
                        </div>
                    </th>
                    @endif

                    <th>#</th>
                    <th>Name</th>
                    <th>Created At</th>
                    @if (Auth::user()->can('paymentType.history') || Auth::user()->can('paymentType.view') || Auth::user()->can('paymentType.edit') || Auth::user()->can('paymentType.delete'))
                    <th class="no-sort text-end">
                        <span class="header-action-title" title="Action">Action</span>
                        <span class="header-action">
                            @if (Auth::user()->can('paymentType.delete'))
                            <a href="{{ route('ajaxPaymentTypeRemoveMulti') }}" title="Delete" class="delete-all-confirm bg-danger text-white">
                                <i class="icon ion-ios-trash io-18"></i>
                            </a>
                            @endif
                        </span>
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(count($paymentTypes) > 0)
                @foreach($paymentTypes as $key=>$value)
                <tr>
                    @if (Auth::user()->can('paymentType.delete'))
                    <td title="Select" class="check-box">
                        <div class="delete-check">
                            <input type="checkbox" name="select_check[]" value="{{ $value->id }}">
                            <label></label>
                        </div>
                    </td>
                    @endif

                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->name }}</td>
                    <td>{{ date('h:i a, d M Y', strtotime($value->created_at)) }}</td>
                    
                    @if (Auth::user()->can('paymentType.history') || Auth::user()->can('paymentType.view') || Auth::user()->can('paymentType.edit') || Auth::user()->can('paymentType.delete'))
                    <td style="text-align: right;">
                        <span class="table-action">
                            @if (Auth::user()->can('paymentType.history') || Auth::user()->can('paymentType.view'))
                            <a href="{{ route('paymentTypeView', ['data' => $value->id]) }}" title="View">
                                <i class="icon ion-ios-eye io-19"></i>
                            </a>
                            @endif
                            @if (Auth::user()->can('paymentType.edit'))
                            <a href="{{ route('paymentTypeUpdate', ['data' => $value->id]) }}" title="Edit">
                                <i class="icon ion-edit io-15"></i>
                            </a>
                            @endif
                            @if (Auth::user()->can('paymentType.delete'))
                            <a href="{{ route('paymentTypeRemove', ['data' => $value->id]) }}" title="Delete" class='delete-confirm'>
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
@endif

@endsection



@if (Auth::user()->can('paymentType.history') || Auth::user()->can('paymentType.view') || Auth::user()->can('paymentType.create') || Auth::user()->can('paymentType.edit') || Auth::user()->can('paymentType.delete'))

@push('scripts')
    @if (Auth::user()->can('paymentType.delete'))
    <script type="text/javascript">
        let exportable_column = [1,3,4,5,6,7,8];
    </script>
    @else
    <script type="text/javascript">
        let exportable_column = [0,2,3,4,5,6,7];
    </script>
    @endif
@endpush
<x-data-table table="datatable" statusUrl="/paymentType/status-change"/>

@endif