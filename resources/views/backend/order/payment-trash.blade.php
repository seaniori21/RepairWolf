@extends('backend.layouts.app', ['submenu' => 'trashed-records', 'bread' => 'trashed-records'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Trashed Order Payments</h3>
        </div>

        @if (Auth::user()->can('order.view'))
        <div class="header-title mt-2">
            <h5>Order No: 
                <a href="{{ route('admin.order.view', ['data' => $data->id]) }}" class="py-0">{{ '#'.$data->no }}</a>
            </h5>
        </div>
        @endif
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
                    <th>Type</th>
                    <th>Amount</th>
                    <th title="Authorization Approval Code">AAC</th>
                    <th title="Credit Card Number">CCN</th>
                    <th>Expiration Date</th>
                    <th>Security Code</th>
                    <th>Created At</th>

                    @if (Auth::user()->can('order.delete'))
                    <th class="no-sort text-end">
                        <span class="header-action-title" title="Action">Action</span>
                        <span class="header-action"></span>
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(count($orderPayments) > 0)
                @foreach($orderPayments as $key => $value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->paymentType ? $value->paymentType->name : '--' }}</td>
                    <td>${{ $value->amount }}</td>
                    <td>{{ $value->authorization_approval_code ? $value->authorization_approval_code : '--' }}</td>
                    <td>{{ $value->credit_card_number ? $value->credit_card_number : '--' }}</td>
                    <td>{{ $value->expiration_date ? date('d M Y', strtotime($value->expiration_date)) : '--' }}</td>
                    <td>{{ $value->security_code ? $value->security_code : '--' }}</td>
                    <td>{{ date('h:i a, d M Y', strtotime($value->created_at)) }}</td>
                    
                    @if (Auth::user()->can('order.delete'))
                    <td style="text-align: right;">
                        <span class="table-action">
                            <a href="{{ route('admin.order.payment.trashed.restore', ['data' => $data->id, 'item' => $value->id]) }}" class="restore-confirm" title="Restore">
                                <i class="icon ion-android-sync io-19"></i>
                            </a>
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
        let exportable_column = [0,1,2,3,4,5,6,7];
    </script>
@endpush

<x-data-table table="datatable" statusUrl=""/>