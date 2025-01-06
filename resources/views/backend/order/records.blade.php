@extends('backend.layouts.app', ['submenu' => 'records', 'bread' => 'records'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title mb-3">
            <h3>
                Order Records

                @if (Auth::user()->can('order.delete'))
                    <a href="{{ route('admin.order.records.trashed') }}" class="btn btn-danger btn-sm py-0">Trashed</a>
                @endif
            </h3>
        </div>

        <form action="{{ route('admin.order.records') }}" method="get">
            {{-- @csrf --}}

            <div class="row">
                <div class="d-flex gap-2">
                    <!-- order_date -->
                    <div class="">
                        <div class="input-group mb-3">
                          <span class="input-group-text" style="background: #ddd;">Daterange</span>
                          <input type="text" readonly id="datefilter" value="{{ request('from') ? date('d M Y - ', strtotime(request('from'))) : '' }}{{ request('to') ? date('d M Y', strtotime(request('to'))) : '' }}" class="form-control" placeholder="Select Date" style="box-shadow: none;min-width: 215px;border-top-right-radius: 5px;border-bottom-right-radius: 5px;cursor: pointer;">

                          <input type="hidden" name="from" id="date_from" value="">
                          <input type="hidden" name="to" id="date_to" value="">
                        </div>
                    </div>
                    <!-- order_date -->

                    <!-- status -->
                    <div class="">
                        <div class="input-group mb-3">
                          <span class="input-group-text" style="background: #ddd;">Status</span>
                          <select class="form-select" name="status" style="box-shadow: none;">
                            <option value="">All</option>
                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>

                            {{-- <option value="open">Open</option>
                            <option value="closed">Closed</option> --}}
                          </select>
                        </div>
                    </div>
                    <!-- status -->

                    <!-- paid -->
                    <div class="">
                        <div class="input-group mb-3">
                          <span class="input-group-text" style="background: #ddd;">Paid</span>
                          <select class="form-select" name="paid" style="box-shadow: none;">
                            <option value="">All</option>
                            <option value="yes" {{ request('paid') == 'yes' ? 'selected' : '' }}>Paid</option>
                            <option value="no" {{ request('paid') == 'no' ? 'selected' : '' }}>Unpaid</option>

                            {{-- <option value="yes">Yes</option>
                            <option value="no">No</option> --}}
                          </select>
                        </div>
                    </div>
                    <!-- paid -->

                    <!-- due -->
                    <div class="">
                        <div class="input-group mb-3">
                          <span class="input-group-text" style="background: #ddd;">Due</span>
                          <select class="form-select" name="due" style="box-shadow: none;">
                            <option value="">All</option>
                            <option value="yes" {{ request('due') == 'yes' ? 'selected' : '' }}>Yes</option>
                            <option value="no" {{ request('due') == 'no' ? 'selected' : '' }}>No</option>

                            {{-- <option value="yes">Yes</option>
                            <option value="no">No</option> --}}
                          </select>
                        </div>
                    </div>
                    <!-- due -->

                    <!-- btn -->
                    <div class="ms-4">
                        <button type="submit" class="btn btn-success">Filter Orders</button>
                        <a href="{{ route('admin.order.records') }}" class="btn btn-danger">Reset</a>
                    </div>
                    <!-- btn -->
                </div>
            </div>
        </form>
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
                    @if (Auth::user()->can('order.delete'))
                    <th class="check-box no-sort" style="background: transparent;">
                        <div class="delete-check">
                            <input type="checkbox" id="checkAll">
                            <label></label>
                        </div>
                    </th>
                    @endif

                    <th>#</th>
                    <th>No</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Service Person</th>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Due</th>
                    <th>Status</th>
                    <th>Last Notify</th>

                    @if (Auth::user()->can('order.history') || Auth::user()->can('order.receipt') || Auth::user()->can('order.view') || Auth::user()->can('order.edit') || Auth::user()->can('order.delete'))
                    <th class="no-sort text-end">
                        <span class="header-action-title" title="Action">Action</span>
                        <span class="header-action">
                            @if (Auth::user()->can('order.delete'))
                            <a href="{{ route('admin.order.remove.all') }}" title="Delete" class="delete-all-confirm bg-danger text-white">
                                <i class="icon ion-ios-trash io-18"></i>
                            </a>
                            @endif
                        </span>
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(count($orders) > 0)
                @foreach($orders as $key=>$value)
                <tr>
                    @if (Auth::user()->can('order.delete'))
                    <td title="Select" class="check-box">
                        <div class="delete-check">
                            <input type="checkbox" name="select_check[]" value="{{ $value->id }}">
                            <label></label>
                        </div>
                    </td>
                    @endif

                    <td>{{ $key+1 }}</td>
                    <td>
                        @if(Auth::user()->can('order.edit'))
                        <a href="{{ route('admin.order.edit', ['data' => $value->id]) }}" class="ps-0 d-flex align-items-center relative" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>

                            <span class="ms-1">{{ $value->no }}</span>
                        </a>
                        @else
                            {{ $value->no }}
                        @endif
                    </td>
                    <td>{{ date('h:i a, d M Y', strtotime($value->order_date)) }}</td>
                    <td>
                        @if($value->customer)
                            @if(Auth::user()->can('customer.view'))
                            <a href="{{ route('customerView', ['data' => $value->customer->id]) }}" class="ps-0 d-flex align-items-center relative" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                </svg>

                                <span class="ms-1">{{ $value->customer->first_name.' '.$value->customer->last_name }}</span>
                            </a>
                            @else
                                {{ $value->customer->first_name.' '.$value->customer->last_name }}
                            @endif
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($value->servicePerson)
                            @if(Auth::user()->can('account.view'))
                                <a href="{{ route('adminViewAccount', ['admin_data' => $value->servicePerson->id]) }}" class="ps-0 d-flex align-items-center relative" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                    </svg>

                                    <span class="ms-1">{{ $value->servicePerson->name }}</span>
                                </a>
                            @else
                                {{ $value->servicePerson->name }}
                            @endif
                        @else
                        N/A
                        @endif
                    </td>
                    <td>{{ '$'.$value->grand_total }}</td>
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
    
                    @if (Auth::user()->can('order.receipt') || Auth::user()->can('order.view') || Auth::user()->can('order.edit') || Auth::user()->can('order.delete') || Auth::user()->can('order.history'))
                    <td style="text-align: right;">
                        <span class="table-action">
                            @if (Auth::user()->can('order.receipt'))
                            <a href="{{ route('admin.order.receipt.base', ['data' => $value->id]) }}" title="View Base Price Receipt">
                                <i class="icon ion-ios-paper io-18"></i>
                            </a>

                            <a href="{{ route('admin.order.receipt.list', ['data' => $value->id]) }}" title="View List Price  Receipt">
                                <i class="icon ion-ios-paper-outline io-18"></i>
                            </a>
                            @endif

                            @if (Auth::user()->can('notification.create'))
                            <a href="{{ route('notificationCreate', ['customer_id' => $value->customer_id, 'type' => json_encode(["sms"]), 'greeting' => 'TowWolf Payment Reminder', 'subject' => 'Payment Reminder', 'text' => 'we currently have an open balance for your auto services and would appreciate a prompt payment. If you have any questions please call us at 718-339-8500']) }}" title="Send Notification">
                                <i class="icon ion-ios-bell io-18"></i>
                            </a>
                            @endif

                            @if (Auth::user()->can('order.view'))
                            <a href="{{ route('admin.order.view', ['data' => $value->id]) }}" title="View Details">
                                <i class="icon ion-eye io-18"></i>
                            </a>
                            @endif

                            @if (Auth::user()->can('order.edit'))
                            <a href="{{ route('admin.order.edit', ['data' => $value->id]) }}" title="Edit">
                                <i class="icon ion-edit io-15"></i>
                            </a>
                            @endif

                            @if (Auth::user()->can('order.delete'))
                            <a href="{{ route('admin.order.remove', ['data' => $value->id]) }}" title="Delete" class='delete-confirm'>
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
    @if (Auth::user()->can('order.delete'))
    <script type="text/javascript">
        let exportable_column = [1,2,3,4,5,6,7,8,9,10,11];
    </script>
    @else
    <script type="text/javascript">
        let exportable_column = [0,1,2,3,4,5,6,7,8,9,10];
    </script>
    @endif
@endpush

<x-data-table table="datatable" statusUrl=""/>

@push('style')
    <!-- daterangepicker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- daterangepicker -->
@endpush

@push('scripts')
    <!-- daterangepicker -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <!-- daterangepicker -->

    <script type="text/javascript">
        $("#datefilter").daterangepicker(
            {
                ranges: {
                    "Today": [moment(), moment()],
                    "Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")],
                    "Last 7 Days": [moment().subtract(6, "days"), moment()],
                    "Last 30 Days": [moment().subtract(30, "days"), moment()],
                    "This Month": [moment().startOf("month"), moment().endOf("month")],
                    "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")],
                },
                alwaysShowCalendars: true,
                // autoApply: true,
                // startDate: "31/03/2023",
                // endDate: "28/09/2023",
                // opens: 'center',
                autoUpdateInput: false,
                locale: {
                  format: 'DD MMM YYYY',
                  cancelLabel: 'Clear'
                }
            },
            function (start, end, label) {
                $('#date_from').val(start.format('YYYY-MM-DD')).trigger('change');
                $('#date_to').val(end.format('YYYY-MM-DD')).trigger('change');
            }
        );

        $('input[id="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD MMM YYYY') + ' - ' + picker.endDate.format('DD MMM YYYY'));
        });

        $('input[id="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    </script>
@endpush