@extends('backend.layouts.app', ['submenu' => 'records', 'bread' => 'records'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Notifications Records</h3>
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
                    @if (Auth::user()->can('notification.delete'))
                    <th class="check-box no-sort" style="background: transparent;">
                        <div class="delete-check">
                            <input type="checkbox" id="checkAll">
                            <label></label>
                        </div>
                    </th>
                    @endif

                    <th class="sort border-r border-t">#</th>
                    <th class="sort border-r border-t">Via</th>
                    <th class="sort border-r border-t">Customer</th>
                    <th class="no-sort border-r border-t">Subject</th>
                    <th class="sort border-r border-t">Status</th>
                    <th class="sort border-r border-t">Sent At</th>

                    @if (Auth::user()->can('notification.view') || Auth::user()->can('notification.delete'))
                    <th class="no-sort text-end">
                        <span class="header-action-title" title="Action">Action</span>
                        <span class="header-action">
                            @if (Auth::user()->can('notification.delete'))
                            <a href="{{ route('ajaxNotificationRemoveMulti') }}" title="Delete" class="delete-all-confirm bg-danger text-white">
                                <i class="icon ion-ios-trash io-18"></i>
                            </a>
                            @endif
                        </span>
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(count($notifications) > 0)
                @foreach($notifications as $key=>$value)
                
                @php
                    $ext_data = $value->data ? json_decode($value->data) : [];
                @endphp

                <tr>
                    @if (Auth::user()->can('notification.delete'))
                    <td title="Select" class="check-box">
                        <div class="delete-check">
                            <input type="checkbox" name="select_check[]" value="{{ $value->id }}">
                            <label></label>
                        </div>
                    </td>
                    @endif

                    <td>{{ $key+1 }}</td>
                    <td>
                        @if(isset($ext_data->via))
                            @foreach($ext_data->via as $q => $item)
                            
                            @if($item === 'mail')
                            <span class="badge text-bg-primary">Mail</span>
                            @endif

                            @if($item === "App\Notifications\Channel\MessageChannel")
                            <span class="badge text-bg-success">SMS</span>
                            @endif

                            @endforeach
                        @else N/A @endif
                    </td>
                    <td>
                        @php
                            if ($value->notifiable_type === 'App\Models\Customer') {
                                $customer = \App\Models\Customer::where('id', $value->notifiable_id)->first();
                                $link_route_name = 'customerView';
                            }
                        @endphp

                        @if(!empty($customer))
                        @if (Auth::user()->can('account.view'))
                        <a href="{{ route($link_route_name, ['data' => $customer->id]) }}" class="ps-0 d-flex align-items-center relative" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>

                            <span class="ms-1">{{ $customer->first_name.' '.$customer->last_name }}</span>
                        </a>
                        @else
                            {{ $customer->first_name.' '.$customer->last_name }}
                        @endif
                        @else N/A @endif
                    </td>
                    <td>{{ isset($ext_data->subject) ? $ext_data->subject : 'N/A' }}</td>
                    <td title="{{ isset($ext_data->line) && $ext_data->line ? $ext_data->line : '' }}">
                        @if(isset($ext_data->order_product_id) && $ext_data->order_product_id)
                            @php
                                $orderProductItem = \App\Models\OrderProduct::where('id', $ext_data->order_product_id)->first();
                            @endphp

                            @if($orderProductItem && $orderProductItem->notification_sent)
                                <span class="badge text-bg-success">Sent</span>
                            @else
                                <span class="badge text-bg-secondary">Scheduled {{ isset($ext_data->scheduled_date) && $ext_data->scheduled_date ? 'at '.date('h:i a, d M Y', strtotime($ext_data->scheduled_date)) : '' }}</span>
                            @endif
                        @else
                            <span class="badge text-bg-success">Sent</span>
                        @endif
                    </td>
                    <td>
                        @if(isset($ext_data->order_product_id) && $ext_data->order_product_id)
                            @php
                                $orderProductItem = \App\Models\OrderProduct::where('id', $ext_data->order_product_id)->first();
                            @endphp

                            @if($orderProductItem && $orderProductItem->notification_sent)
                                {{ isset($ext_data->scheduled_date) && $ext_data->scheduled_date ? date('h:i a, d M Y', strtotime($ext_data->scheduled_date)) : '' }}
                            @else
                                N/A
                            @endif
                        @else
                            {{ date('h:i a, d M Y', strtotime($value->created_at)) }}
                        @endif
                    </td>

                    @if (Auth::user()->can('notification.view') || Auth::user()->can('notification.delete'))
                    <td style="text-align: right;">
                        <span class="table-action">
                            @if (Auth::user()->can('notification.view'))
                            <a href="{{ route('notificationView', ['data' => $value->id]) }}" title="View">
                                <i class="icon ion-ios-eye io-19"></i>
                            </a>
                            @endif
                            @if (Auth::user()->can('notification.delete'))
                            <a href="{{ route('notificationRemove', ['data' => $value->id]) }}" title="Delete" class='delete-confirm'>
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
    @if (Auth::user()->can('notification.delete'))
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