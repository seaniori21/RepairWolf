@extends('backend.layouts.app', ['submenu' => 'view', 'bread' => 'none'])

@section('content')

<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Notification details</h3>
        </div>
    </div>
</header>

<!-- body header start -->
<header class="body-content mt-0">
    <div class="container">
        <div class="profile-info mt-0 rounded-3">
            <div>
                @php
                    if ($data->notifiable_type === 'App\Models\Customer') {
                        $customer = \App\Models\Customer::where('id', $data->notifiable_id)->first();
                        $link_route_name = 'customerView';
                    }

                    $ext_data = $data->data ? json_decode($data->data) : [];
                @endphp

                <div>
                    <div class="d-flex flex-wrap">
                        <span class="profile-name mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #aaa;">Via</small>
                            <span>
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
                            </span>
                        </span>

                        <span class="profile-name mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #aaa;">Customer</small>
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
                        </span>
                    </div>

                    <div class="d-flex flex-wrap">
                        <span class="profile-name mt-2 mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #aaa;">Subject</small>
                            {{ isset($ext_data->subject) && $ext_data->subject ? $ext_data->subject : 'N/A' }}
                        </span>

                        <span class="profile-name mt-2 mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #aaa;">Greeting</small>
                            {{ isset($ext_data->greeting) && $ext_data->greeting ? $ext_data->greeting : 'N/A' }}
                        </span>
                    </div>

                    @if(isset($ext_data->scheduled_date) && $ext_data->scheduled_date)
                    <div class="d-flex flex-wrap">
                        <span class="profile-name mt-2 mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #aaa;">Scheduled Date</small>
                            {{ isset($ext_data->scheduled_date) && $ext_data->scheduled_date ? date('h:i a, d M Y', strtotime($ext_data->scheduled_date)) : 'N/A' }}
                        </span>

                        @if(isset($ext_data->order_product_id) && $ext_data->order_product_id)
                        @php $orderProductItem = \App\Models\OrderProduct::where('id', $ext_data->order_product_id)->first(); @endphp

                        <span class="profile-name mt-2 mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #aaa;">Status</small>
                            @if($orderProductItem->notification_sent)
                                <span class="badge text-bg-success">Sent</span>
                            @else
                                <span class="badge text-bg-secondary">Scheduled</span>
                            @endif
                        </span>
                        @endif
                    </div>
                    @endif

                    <div class="d-flex flex-wrap">
                        <span class="profile-name mt-2 mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #aaa;">Notification Body</small>
                            {{ isset($ext_data->line) && $ext_data->line ? $ext_data->line : 'N/A' }}
                        </span>
                    </div>

                    <div class="d-flex flex-wrap mt-4">
                        <span class="profile-name mt-2 mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #aaa;">Last Updated At</small>
                            <span>{{ date('h:i a, d M Y', strtotime($data->updated_at)) }}</span>
                        </span>

                        <span class="profile-name mt-2 mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #aaa;">Created At</small>
                            <span>{{ date('h:i a, d M Y', strtotime($data->created_at)) }}</span>
                        </span>
                    </div>

                    <div class="d-flex flex-wrap mt-2" style="gap: 10px;">
                        @if (Auth::user()->can('notification.delete'))
                        <a href="{{ route('notificationRemove', ['data' => $data->id]) }}" class="btn d-block btn-outline-danger delete-confirm-perm">Delete</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- body header end -->

@endsection

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