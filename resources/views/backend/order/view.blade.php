@extends('backend.layouts.app', ['submenu' => 'view', 'bread' => 'none'])

@section('content')

<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Order details</h3>
        </div>
    </div>
</header>

<!-- body header start -->
<header class="body-content mt-0">
    <div class="container">
        <div class="profile-info mt-0 rounded-3">
            <div>
                {{-- <div class="profile p-0 rounded-3" href="javascript:void(0)">
                    <img style="object-fit: cover; height: 300px;" src="{{ asset($data->image? '/upload/crop-files/'.$data->image:'assets/images/media/logo_default.png') }}" alt="" id="profile_picture_preview">
                </div> --}}
                <div>
                    <div class="d-flex flex-wrap mb-2">
                        <span class="profile-name mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #a3a3a3;">Order No</small>
                            #{{ $data->no }}
                        </span>

                        <span class="profile-name mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #a3a3a3;">Order Date</small>
                            {{ date('d M Y',strtotime($data->order_date)) }}
                        </span>

                        <span class="profile-name mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #a3a3a3;">Order Status</small>
                            {{ ucwords($data->status) }}
                        </span>
                    </div>

                    <div class="d-flex flex-wrap">
                        <span class="profile-name mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #a3a3a3;">Customer</small>
                            @if($data->customer)
                            @if (Auth::user()->can('customer.view'))
                            <a href="{{ route('customerView', ['data' => $data->customer->id]) }}" class="ps-0 d-flex align-items-center relative" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                </svg>

                                <span class="ms-1">{{ $data->customer->first_name.' '.$data->customer->last_name }}</span>
                            </a>
                            @else
                            {{ $data->customer->first_name.' '.$data->customer->last_name }}
                            @endif
                            @else N/A @endif
                        </span>

                        <span class="profile-name mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #a3a3a3;">Cashier</small>
                            @if($data->cashier)
                            @if (Auth::user()->can('account.view'))
                            <a href="{{ route('adminViewAccount', ['admin_data' => $data->cashier->id]) }}" class="ps-0 d-flex align-items-center relative" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                </svg>

                                <span class="ms-1">{{ $data->cashier->name }}</span>
                            </a>
                            @else
                            {{ $data->cashier->name }}
                            @endif
                            @else N/A @endif
                        </span>

                        <span class="profile-name mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #a3a3a3;">Service Person</small>
                            @if($data->servicePerson)
                            @if (Auth::user()->can('account.view'))
                            <a href="{{ route('adminViewAccount', ['admin_data' => $data->servicePerson->id]) }}" class="ps-0 d-flex align-items-center relative" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                </svg>

                                <span class="ms-1">{{ $data->servicePerson->name }}</span>
                            </a>
                            @else
                            {{ $data->servicePerson->name }}
                            @endif
                            @else N/A @endif
                        </span>
                    </div>

                    <div class="d-flex flex-wrap mt-2">
                        <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" xstyle="color: #a3a3a3;">Customer Vehicle Data</small>
                        </h3>
                    </div>

                    <div class="d-flex flex-wrap mt-0">
                        <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #a3a3a3;">License Plate</small>
                            <span>{{ $data->vehicle ? $data->vehicle->license_plate: 'N/A' }}</span>
                        </h3>

                        <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #a3a3a3;">VIN</small>
                            <span>{{ $data->vehicle ? $data->vehicle->vin: 'N/A' }}</span>
                        </h3>

                        <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #a3a3a3;">Body Type</small>
                            <span>{{ $data->vehicle && $data->vehicle->body_type ? $data->vehicle->body_type: 'N/A' }}</span>
                        </h3>
                    </div>

                    <div class="d-flex flex-wrap mt-0">
                        <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #a3a3a3;">Model</small>
                            <span>{{ $data->vehicle && $data->vehicle->model ? $data->vehicle->model: 'N/A' }}</span>
                        </h3>

                        <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #a3a3a3;">Make</small>
                            <span>{{ $data->vehicle && $data->vehicle->make ? $data->vehicle->make: 'N/A' }}</span>
                        </h3>

                        <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #a3a3a3;">Year</small>
                            <span>{{ $data->vehicle && $data->vehicle->year ? $data->vehicle->year: 'N/A' }}</span>
                        </h3>
                    </div>

                    <div class="d-flex flex-wrap mt-0">
                        <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #a3a3a3;">Trim</small>
                            <span>{{ $data->vehicle && $data->vehicle->trim ? $data->vehicle->trim: 'N/A' }}</span>
                        </h3>

                        <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #a3a3a3;">Color</small>
                            <span>{{ $data->vehicle && $data->vehicle->color ? $data->vehicle->color: 'N/A' }}</span>
                        </h3>
                    </div>

                    <div class="d-flex flex-wrap mt-4">
                        <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" xstyle="color: #a3a3a3;">
                                Products
                                @if (Auth::user()->can('order.delete'))
                                <a href="{{ route('admin.order.product.trashed', ['data' => $data->id]) }}" xtarget="_blank" class="btn btn-danger btn-sm px-1 py-0">Trashed</a>
                                @endif
                            </small>
                        </h3>
                    </div>

                    <ol class="list-group list-group-flush rounded-2">
                        @if(count($data->productItems))
                        @foreach($data->productItems as $key => $value)
                        <div class="d-flex flex-wrap gap-2 p-0 mt-0 px-0 list-group-item bg-transparent">
                            <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                                <small class="fs-6" style="color: #a3a3a3;">#</small>
                                <span>{{ $key+1 }}</span>
                            </h3>

                            <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                                <small class="fs-6" style="color: #a3a3a3;">Type</small>
                                <span>{{ $value->product ? ucwords($value->product->type) : 'N/A' }}</span>
                            </h3>

                            <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                                <small class="fs-6" style="color: #a3a3a3;">Name</small>
                                <span>
                                    @if($value->product)
                                    @if (Auth::user()->can('product.view'))
                                    <a href="{{ route('productView', ['data' => $value->product->id]) }}" class="ps-0 d-flex align-items-center relative" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                        </svg>

                                        <span class="ms-1">{{ $value->product->name }}</span>
                                    </a>
                                    @else
                                        {{ $value->product->name }}
                                    @endif
                                    @else N/A @endif
                                </span>
                            </h3>

                            <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                                <small class="fs-6" style="color: #a3a3a3;">Quantity</small>
                                <span>{{ $value->quantity ? $value->quantity : 1 }}</span>
                            </h3>

                            <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                                <small class="fs-6" style="color: #a3a3a3;">Notification</small>
                                <span style="cursor: default;">
                                    @php
                                        $scheduledNotification = \Illuminate\Support\Facades\DB::table('notifications')->where('id', $value->scheduled_notify_id)->first();
                                        $notificationData = isset($scheduledNotification->data) && $scheduledNotification->data ? json_decode($scheduledNotification->data) : [];
                                    @endphp

                                    @if($value->scheduled_notify_id && $value->notification_sent)
                                        <small title="{{ isset($notificationData->line) && $notificationData->line ? $notificationData->line : '' }}">Sent</small>
                                    @else
                                        @if($notificationData)
                                        <small title="{{ $notificationData->line }}">{{ isset($notificationData->scheduled_date) && $notificationData->scheduled_date ? 'Scheduled at '.date('h:i a, d M Y', strtotime($notificationData->scheduled_date)) : '' }}</small>
                                        @else
                                        <small>N/A</small>
                                        @endif
                                    @endif
                                </span>
                            </h3>

                            <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                                <small class="fs-6" style="color: #a3a3a3;">Base Price</small>
                                <span>${{ $value->base_price ? number_format($value->base_price, 2) : 0.00 }}</span>
                            </h3>

                            <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                                <small class="fs-6" style="color: #a3a3a3;">List Price</small>
                                <span>{{ $value->list_price ? '$'.number_format($value->list_price, 2) : 'N/A' }}</span>
                            </h3>

                            <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                                <small class="fs-6" style="color: #a3a3a3;">Total</small>
                                <span>${{ number_format(($value->list_price ? $value->list_price : $value->base_price)*($value->quantity ? $value->quantity : 0.00), 2) }}</span>
                            </h3>
                        </div>
                        @endforeach
                        @else
                        <div class="alert alert-warning py-1 px-2 mt-1" role="alert">
                            There is no product added to this order.
                        </div>
                        @endif
                    </ol>

                    <div class="d-flex flex-wrap mt-4">
                        <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" xstyle="color: #a3a3a3;">
                                Customer Payment Data
                                @if (Auth::user()->can('order.delete'))
                                <a href="{{ route('admin.order.payment.trashed', ['data' => $data->id]) }}" xtarget="_blank" class="btn btn-danger btn-sm px-1 py-0">Trashed</a>
                                @endif
                            </small>
                        </h3>
                    </div>

                    <ol class="list-group list-group-flush rounded-2">
                        @if(count($data->payments))
                        @foreach($data->payments as $key => $value)
                        <div class="d-flex flex-wrap gap-2 p-0 mt-0 px-0 list-group-item bg-transparent">
                            <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                                <small class="fs-6" style="color: #a3a3a3;">#</small>
                                <span>{{ $key+1 }}</span>
                            </h3>

                            <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                                <small class="fs-6" style="color: #a3a3a3;">Type</small>
                                <span>{{ $value->paymentType ? ucwords($value->paymentType->name) : 'N/A' }}</span>
                            </h3>

                            <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                                <small class="fs-6" style="color: #a3a3a3;">Payment Amount</small>
                                <span>${{ $value->amount ? $value->amount : 0.00 }}</span>
                            </h3>

                            @if($value->authorization_approval_code)
                            <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                                <small class="fs-6" style="color: #a3a3a3;">Auth. Approval Code</small>
                                <span>{{ $value->authorization_approval_code ? $value->authorization_approval_code : 'N/A' }}</span>
                            </h3>
                            @endif

                            @if($value->credit_card_number)
                            <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                                <small class="fs-6" style="color: #a3a3a3;">Credit Card Number</small>
                                <span>{{ $value->credit_card_number ? $value->credit_card_number : 'N/A' }}</span>
                            </h3>
                            @endif

                            @if($value->expiration_date)
                            <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                                <small class="fs-6" style="color: #a3a3a3;">Expiration Date</small>
                                <span>{{ $value->expiration_date ? date('d M Y', strtotime($value->expiration_date)) : 'N/A' }}</span>
                            </h3>
                            @endif

                            @if($value->security_code)
                            <h3 class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                                <small class="fs-6" style="color: #a3a3a3;">Security Code</small>
                                <span>{{ $value->security_code ? $value->security_code : 'N/A' }}</span>
                            </h3>
                            @endif
                        </div>
                        @endforeach
                        @else
                        <div class="alert alert-warning py-1 px-2 mt-1" role="alert">
                            There is no payment records added to this order.
                        </div>
                        @endif
                    </ol>

                    <div class="d-flex flex-wrap mt-4">
                        <span class="profile-name mt-2 mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" xstyle="color: #a3a3a3;">
                                Order Commnets
                                @if (Auth::user()->can('order.delete'))
                                <a href="{{ route('admin.order.comment.trashed', ['data' => $data->id]) }}" xtarget="_blank" class="btn btn-danger btn-sm px-1 py-0">Trashed</a>
                                @endif
                            </small>
                        </span>
                    </div>

                    <ol class="list-group list-group-flush rounded-2">
                        @if(count($data->comments))
                        @foreach($data->comments as $key => $value)
                        <div class="d-flex flex-wrap gap-2 p-0 mt-0 px-0 list-group-item bg-transparent">
                            <span class="profile-name m-0 fs-5 fw-bolder text-dark d-flex align-items-center me-3" style="flex-direction: row;">
                                <small class="fs-6" style="color: #a3a3a3;">#</small>
                                <span>{{ $key+1 }}</span>
                            </span>

                            <span class="profile-name m-0 fs-5 fw-bolder text-dark d-flex align-items-center me-3" style="flex-direction: row;">
                                <small class="fs-6" style="color: #a3a3a3;">Text:&nbsp;</small>
                                <span>{{ $value->text ? $value->text : 'N/A' }}</span>
                            </span>

                            <span class="profile-name m-0 fs-5 fw-bolder text-dark d-flex align-items-center me-3" style="flex-direction: row;">
                                <small class="fs-6" style="color: #a3a3a3;">Attchment:&nbsp;</small>
                                @if($value->attachment)
                                <a href="{{ asset('upload/attachments/'.$value->attachment) }}" class="ps-0 d-flex align-items-center relative" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                    </svg>

                                    <span class="ms-1">{{ $value->attachment_name }}</span>
                                </a>
                                @else N/A @endif
                            </span>
                        </div>
                        @endforeach
                        @else
                        <div class="alert alert-warning py-1 px-2 mt-1" role="alert">
                            There is no comment added to this order.
                        </div>
                        @endif
                    </ol>



                    @php
                        $baseTaxAmount = $data->tax > 0 && $data->base_total > 0 ? ($data->tax / 100) * $data->base_total : 0;
                        $listTaxAmount = $data->tax > 0 && $data->list_total > 0 ? ($data->tax / 100) * $data->list_total : 0;
                    @endphp

                    <div class="d-flex flex-wrap mt-4">
                        <span class="profile-name mt-2 mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #a3a3a3;">TAX</small>
                            <span>{{ $data->tax }}%</span>
                        </span>

                        <span class="profile-name mt-2 mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #a3a3a3;">Convenience Fee</small>
                            <span>${{ number_format($data->convenience_fee, 2) }}</span>
                        </span>
                    </div>

                    <div class="d-flex flex-wrap mt-4">
                        <span class="profile-name mt-2 mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #a3a3a3;">Base Total</small>
                            <span>${{ number_format($data->base_total + $baseTaxAmount + $data->convenience_fee, 2) }}</span>
                        </span>

                        <span class="profile-name mt-2 mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #a3a3a3;">List Total</small>
                            <span>${{ number_format($data->list_total + $listTaxAmount + $data->convenience_fee, 2) }}</span>
                        </span>

                        <span class="profile-name mt-2 mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #a3a3a3;">Paid Amount</small>
                            <span>${{ number_format($data->paid_amount, 2) }}</span>
                        </span>

                        <span class="profile-name mt-2 mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #a3a3a3;">Due Amount</small>
                            <span>${{ number_format($data->due_amount, 2) }}</span>
                        </span>
                    </div>

                    <div class="d-flex flex-wrap mt-2">
                        <span class="profile-name mt-2 mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #a3a3a3;">Last Updated At</small>
                            <span>{{ date('h:i a, d M Y', strtotime($data->updated_at)) }}</span>
                        </span>

                        <span class="profile-name mt-2 mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #a3a3a3;">Created At</small>
                            <span>{{ date('h:i a, d M Y', strtotime($data->created_at)) }}</span>
                        </span>
                    </div>

                    <div class="d-flex flex-wrap mt-2" style="gap: 10px;">
                        @if (Auth::user()->can('order.receipt'))
                        <a href="{{ route('admin.order.receipt.base', ['data' => $data->id]) }}" class="btn d-block btn-outline-success">Base Price Receipt</a>
                        <a href="{{ route('admin.order.receipt.list', ['data' => $data->id]) }}" class="btn d-block btn-outline-success">List Price Receipt</a>
                        @endif

                        @if(!$data->trash)
                            @if (Auth::user()->can('order.edit'))
                            <a href="{{ route('admin.order.edit', ['data' => $data->id]) }}" class="btn d-block btn-outline-primary">Edit</a>
                            @endif

                            @if (Auth::user()->can('order.delete'))
                            <a href="{{ route('admin.order.remove', ['data' => $data->id]) }}" class="btn d-block btn-outline-danger delete-confirm">Delete</a>
                            @endif
                        @else
                            @if (Auth::user()->can('order.delete'))
                            <a href="{{ route('admin.order.records.trashed.restore', ['data' => $data->id]) }}" class="btn d-block btn-outline-success restore-confirm">Restore Order</a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- body header end -->

@if (Auth::user()->can('order.history'))
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Orders's Data Activity Log</h3>
        </div>
    </div>
</header>
@endif

@if (Auth::user()->can('order.history'))
<!-- body content start -->
<div class="body-content pb-5">
    <div class="container">
        <table id="datatable" class="display responsive nowrap mb-3" style="width:100%; position: relative;">
            <div class="loading"></div>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Modified By</th>
                    <th>Ip Address</th>
                    <th>Device</th>
                    <th>Browser</th>
                    <th>Table</th>
                    <th>Summery</th>
                    <th>Created At</th>
                    @if (Auth::user()->can('order.delete'))
                    <th class="no-sort text-end">
                        <span class="header-action-title" title="Action">Action</span>
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if($activityLog)
                @foreach($activityLog as $key => $value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>
                        @if($value->user)
                        @if (Auth::user()->can('account.view'))
                        <a href="{{ route('adminViewAccount', ['admin_data' => $value->user->id]) }}" class="ps-0 d-flex align-items-center relative" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>

                            <span class="ms-1">{{ $value->user->name }}</span>
                        </a>
                        @else
                            {{ $value->user->name }}
                        @endif
                        @else N/A @endif
                    </td>
                    <td>{{ $value->user ? $value->user->ip_address : "N/A" }}</td>
                    <td>{{ $value->user ? $value->user->device : "N/A" }}</td>
                    <td>{{ $value->user ? $value->user->browser : "N/A" }}</td>

                    <td>{{ $value->table }}</td>
                    <td>{{ $value->summery }}</td>
                    <td>{{ date('h:i a, d M Y', strtotime($value->created_at)) }}</td>
                    
                    @if (Auth::user()->can('order.delete'))
                    <td style="text-align: right;">
                        <span class="table-action">
                            <a href="{{ route('admin.order.remove.history', ['data' => $data->id, 'item' => $value->id]) }}" title="Delete" class='delete-confirm'>
                                <i class="icon ion-ios-trash io-18"></i>
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
@endif

@endsection

@if (Auth::user()->can('order.history'))
<x-data-table table="datatable" statusUrl=""/>
@push('scripts')
    <script type="text/javascript">
        let exportable_column = [0,1,2,3,4,5,6,7];
    </script>
@endpush
@endif


@push('style')
    <link rel="stylesheet" href="{{ asset('assets/profile/profile.min.css') }}">
    <style type="text/css">
        .fs-16 {
            font-size: 16px;
        }
        .page-content {border: 1px solid #eee;padding: 5px;margin-top: 5px;min-height: 310px;}.page-url-link {word-break: break-all;}
        .table-button {
            color: var(--brand);
            background: var(--brand-30);
            display: inline-block;
            width: 32px;
            height: 32px;
            line-height: 32px;
            text-align: center;
            border-radius: 50%;
            transition: all 0.2s;
        }

        .table-button:hover {
            color: var(--brand);
            background: var(--brand-70);
        }
    </style>
@endpush
