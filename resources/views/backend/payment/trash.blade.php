@extends('backend.layouts.app', ['submenu' => 'trashed-records', 'bread' => 'trashed-records'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Trashed Payment Records</h3>
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
                    <th>#</th>
                    <th>Order No</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th title="Authorization Approval Code">AAC</th>
                    <th title="Credit Card Number">CCN</th>
                    <th>Expiration Date</th>
                    <th>Security Code</th>
                    <th>Created At</th>
                    
                    @if (Auth::user()->can('payment.delete'))
                    <th class="no-sort text-end">
                        <span class="header-action-title" title="Action">Action</span>
                        <span class="header-action">
                            {{-- @if (Auth::user()->can('payment.delete'))
                            <a href="{{ route('ajaxCustomerRemoveMulti') }}" title="Delete" class="delete-all-confirm bg-danger text-white">
                                <i class="icon ion-ios-trash io-18"></i>
                            </a>
                            @endif --}}
                        </span>
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(count($payments) > 0)
                @foreach($payments as $key=>$value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>
                        @if($value->order)
                        @if (Auth::user()->can('order.view'))
                        <a href="{{ route('admin.order.view', ['data' => $value->order->id]) }}" class="ps-0 d-flex align-items-center relative" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>

                            <span class="ms-1">{{ $value->order->no }}</span>
                        </a>
                        @else
                            {{ $value->order->no }}
                        @endif
                        @else N/A @endif
                    </td>
                    <td>{{ $value->paymentType ? $value->paymentType->name : '--' }}</td>
                    <td>${{ $value->amount }}</td>
                    <td>{{ $value->authorization_approval_code ? $value->authorization_approval_code : '--' }}</td>
                    <td>{{ $value->credit_card_number ? $value->credit_card_number : '--' }}</td>
                    <td>{{ $value->expiration_date ? date('d M Y', strtotime($value->expiration_date)) : '--' }}</td>
                    <td>{{ $value->security_code ? $value->security_code : '--' }}</td>
                    <td>{{ date('h:i a, d M Y', strtotime($value->created_at)) }}</td>

                    @if (Auth::user()->can('payment.history') || Auth::user()->can('payment.view') || Auth::user()->can('payment.delete'))
                    <td style="text-align: right;">
                        <span class="table-action">
                            @if (Auth::user()->can('payment.history') || Auth::user()->can('payment.view'))
                            <a href="{{ route('paymentView', ['data' => $value->id]) }}" title="View">
                                <i class="icon ion-ios-eye io-19"></i>
                            </a>
                            @endif
                            @if (Auth::user()->can('payment.delete'))
                            <a href="{{ route('paymentTrashRecordsRestore', ['data' => $value->id]) }}" class="restore-confirm" title="Restore">
                                <i class="icon ion-android-sync io-19"></i>
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
    <script type="text/javascript">
        let exportable_column = [0,1,2,3,4,5,6,7,8];
    </script>
@endpush

<x-data-table table="datatable" statusUrl=""/>