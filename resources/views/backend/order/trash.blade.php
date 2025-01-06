@extends('backend.layouts.app', ['submenu' => 'trashed-records', 'bread' => 'trashed-records'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Trashed Order</h3>
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
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Service Person</th>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Due</th>
                    <th>Status</th>
                    <th>Last Notify</th>
                    <th>Created At</th>
                    
                    @if (Auth::user()->can('order.view') || Auth::user()->can('order.delete'))
                    <th class="no-sort text-end">
                        <span class="header-action-title" title="Action">Action</span>
                        <span class="header-action">
                        </span>
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(count($orders) > 0)
                @foreach($orders as $key=>$value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ date('d M Y', strtotime($value->order_date)) }}</td>
                    <td>
                        @if($value->customer)
                        <a href="{{ route('customerView', ['data' => $value->customer->id]) }}" class="ps-0 d-flex align-items-center relative" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>

                            <span class="ms-1">{{ $value->customer->first_name.' '.$value->customer->last_name }}</span>
                        </a>
                        @else
                        N/A
                        @endif
                    </td>
                    <td>
                        @if($value->servicePerson)
                        <a href="{{ route('adminViewAccount', ['admin_data' => $value->servicePerson->id]) }}" class="ps-0 d-flex align-items-center relative" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>

                            <span class="ms-1">{{ $value->servicePerson->name }}</span>
                        </a>
                        @else
                        N/A
                        @endif
                    </td>
                    <td>{{ '$'.$value->subtotal }}</td>
                    <td>{{ '$'.$value->paid_amount }}</td>
                    <td>{{ '$'.$value->due_amount }}</td>
                    <td>
                        @if($value->status)
                        @if($value->status === 'closed')
                        <span class="badge text-bg-success">Closed</span>
                        @else
                        <span class="badge text-bg-primary">{{ ucwords($value->status) }}</span>
                        @endif
                        @else
                        <span class="badge text-bg-secondary">N/A</span>
                        @endif
                    </td>

                    @php
                        $last_sent_notification = $value->productItems->where('notification_sent', 1)->sortByDesc('scheduled_notify_at')->first();
                    @endphp

                    @if($last_sent_notification)
                        @php
                            $scheduledNotification = \Illuminate\Support\Facades\DB::table('notifications')->where('id', $last_sent_notification->scheduled_notify_id)->first();
                            $notificationData = isset($scheduledNotification->data) && $scheduledNotification->data ? json_decode($scheduledNotification->data) : [];
                        @endphp

                    <td title="{{ isset($notificationData->line) && $notificationData->line ? $notificationData->line : '' }}">
                        {{ isset($notificationData->scheduled_date) && $notificationData->scheduled_date ? 'Sent at '.date('h:i a, d M Y', strtotime($notificationData->scheduled_date)) : '' }}
                    </td>
                    @else
                    <td>N/A</td>
                    @endif
                    <td>{{ date('h:i a, d M Y', strtotime($value->created_at)) }}</td>

                    @if (Auth::user()->can('order.delete') || Auth::user()->can('order.view'))
                    <td style="text-align: right;">
                        <span class="table-action">                            
                            @if (Auth::user()->can('order.view'))
                            <a href="{{ route('admin.order.view', ['data' => $value->id]) }}" title="Download">
                                <i class="icon ion-eye io-18"></i>
                            </a>
                            @endif
                            @if (Auth::user()->can('order.delete'))
                            <a href="{{ route('admin.order.records.trashed.restore', ['data' => $value->id]) }}" class="restore-confirm" title="Restore">
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