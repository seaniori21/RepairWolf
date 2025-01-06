@extends('backend.layouts.app', ['submenu' => 'records', 'bread' => 'records'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>
                Product Records

                @if (Auth::user()->can('product.delete'))
                <a href="{{ route('productTrashRecordsPage') }}" class="btn btn-danger btn-sm py-0">Trashed</a>
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
                    @if (Auth::user()->can('product.delete'))
                    <th class="check-box no-sort">
                        <div class="delete-check">
                            <input type="checkbox" id="checkAll">
                            <label></label>
                        </div>
                    </th>
                    @endif

                    <th>#</th>

                    <th>Type</th>
                    <th>Identification Code</th>
                    <th>Name</th>
                    <th>Base Price</th>
                    <th>List Price</th>

                    <th>Created At</th>
                    @if (Auth::user()->can('product.history') || Auth::user()->can('product.view') || Auth::user()->can('product.edit') || Auth::user()->can('product.delete'))
                    <th class="no-sort text-end">
                        <span class="header-action-title" title="Action">Action</span>
                        <span class="header-action">
                            @if (Auth::user()->can('product.delete'))
                            <a href="{{ route('ajaxProductRemoveMulti') }}" title="Delete" class="delete-all-confirm bg-danger text-white">
                                <i class="icon ion-ios-trash io-18"></i>
                            </a>
                            @endif
                        </span>
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(count($products) > 0)
                @foreach($products as $key=>$value)
                <tr>
                    @if (Auth::user()->can('product.delete'))
                    <td title="Select" class="check-box">
                        <div class="delete-check">
                            <input type="checkbox" name="select_check[]" value="{{ $value->id }}">
                            <label></label>
                        </div>
                    </td>
                    @endif

                    <td>{{ $key+1 }}</td>

                    <td>{{ ucwords($value->type) }}</td>
                    <td>{{ $value->identification_code }}</td>
                    <td>{{ $value->name }}</td>
                    <td>${{ $value->base_price }}</td>
                    <td>{{ $value->list_price ? '$'.$value->list_price : 'N/A' }}</td>

                    <td>{{ date('h:i a, d M Y', strtotime($value->created_at)) }}</td>
                    @if (Auth::user()->can('product.history') || Auth::user()->can('product.view') || Auth::user()->can('product.edit') || Auth::user()->can('product.delete'))
                    <td style="text-align: right;">
                        <span class="table-action">
                            @if (Auth::user()->can('product.view'))
                            <a href="{{ route('productView', ['data' => $value->id]) }}" title="View">
                                <i class="icon ion-ios-eye io-19"></i>
                            </a>
                            @endif
                            @if (Auth::user()->can('product.edit'))
                            <a href="{{ route('productUpdate', ['data' => $value->id]) }}" title="Edit">
                                <i class="icon ion-edit io-15"></i>
                            </a>
                            @endif
                            @if (Auth::user()->can('product.delete'))
                            <a href="{{ route('productRemove', ['data' => $value->id]) }}" title="Delete" class='delete-confirm'>
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
    @if (Auth::user()->can('product.delete'))
    <script type="text/javascript">
        let exportable_column = [1,2,3,4,5,6,7];
    </script>
    @else
    <script type="text/javascript">
        let exportable_column = [0,1,2,3,4,5,6];
    </script>
    @endif
@endpush

<x-data-table table="datatable" statusUrl=""/>