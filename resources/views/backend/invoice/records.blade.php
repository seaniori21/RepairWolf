@extends('backend.layouts.app', ['submenu' => 'records', 'bread' => 'records'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Invoice Rrecords</h3>
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
                    @if (Auth::user()->can('invoice.delete'))
                    <th class="check-box no-sort">
                        <div class="delete-check">
                            <input type="checkbox" id="checkAll">
                            <label></label>
                        </div>
                    </th>
                    @endif

                    <th>#</th>
                    <th>Client</th>
                    <th>To Name</th>
                    <th>To Email</th>
                    <th>Invoice Date</th>
                    <th>Due Date</th>
                    <th>Total</th>

                    @if (Auth::user()->can('invoice.download') || Auth::user()->can('invoice.edit') || Auth::user()->can('invoice.delete'))
                    <th class="no-sort text-end">
                        <span class="header-action-title" title="Action">Action</span>
                        <span class="header-action">
                            @if (Auth::user()->can('invoice.delete'))
                            <a href="{{ route('admin.invoice.remove.all') }}" title="Delete" class="delete-all-confirm bg-danger text-white">
                                <i class="icon ion-ios-trash io-18"></i>
                            </a>
                            @endif
                        </span>
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(count($invoices) > 0)
                @foreach($invoices as $key=>$value)
                <tr>
                    @if (Auth::user()->can('invoice.delete'))
                    <td title="Select" class="check-box">
                        <div class="delete-check">
                            <input type="checkbox" name="select_check[]" value="{{ $value->id }}">
                            <label></label>
                        </div>
                    </td>
                    @endif

                    <td>{{ $key+1 }}</td>
                    
                    @if($value->client)
                    @php
                        $name = [
                            $value->client->first_name,
                            $value->client->middle_name,
                            $value->client->last_name
                        ];
                    @endphp
                    <td title="{{ implode(' ', $name) }}">
                        <a href="{{ route('clientViewAccount', ['data' => $value->client->id]) }}" target="__blank" class="btn btn-outline-success btn-sm">{{ Illuminate\Support\Str::limit(implode(' ', $name), 10, ' (...)') }}</a>
                    </td>
                    @else
                    <td>N/A</td>
                    @endif

                    <td>{{ $value->to_name }}</td>
                    <td>{{ $value->to_email }}</td>
                    <td>{{ date('d M Y', strtotime($value->invoice_date)) }}</td>
                    <td>{{ date('d M Y', strtotime($value->invoice_due_date)) }}</td>
                    <td>{{ $value->currency.$value->grand_total }}</td>

                    @if (Auth::user()->can('invoice.edit') || Auth::user()->can('invoice.delete') || Auth::user()->can('invoice.download'))
                    <td style="text-align: right;">
                        <span class="table-action">
                            @if (Auth::user()->can('invoice.download'))
                            <a href="{{ route('admin.invoice.download', ['data' => $value->id]) }}" title="Download">
                                <i class="icon ion-android-download io-18"></i>
                            </a>
                            @endif
                            @if (Auth::user()->can('invoice.edit'))
                            <a href="{{ route('admin.invoice.edit', ['data' => $value->id]) }}" title="Edit">
                                <i class="icon ion-edit io-15"></i>
                            </a>
                            @endif
                            @if (Auth::user()->can('invoice.delete'))
                            <a href="{{ route('admin.invoice.remove', ['data' => $value->id]) }}" title="Delete" class='delete-confirm'>
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
    @if (Auth::user()->can('invoice.delete'))
    <script type="text/javascript">
        let exportable_column = [1,3,4,5,6,7];
    </script>
    @else
    <script type="text/javascript">
        let exportable_column = [0,1,2,3,4,5,6];
    </script>
    @endif
@endpush

<x-data-table table="datatable" statusUrl="/clients/status-change"/>

@push('style')
    {{-- <link rel="stylesheet" href="{{ asset('backend/css/print.css') }}"> --}}
@endpush