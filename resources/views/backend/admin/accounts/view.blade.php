@extends('backend.layouts.app', ['submenu' => 'view', 'bread' => 'none'])

@section('content')

<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Account details</h3>
        </div>
    </div>
</header>

<!-- body header start -->
<header class="body-content mt-0">
    <div class="container">
        <div class="profile-info mt-0 rounded-3">
            <div>
                <div>
                    <div class="d-flex flex-wrap">
                        <span class="profile-name mb-1 fs-5 fw-bolder text-dark d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #aaa;">Name</small>
                            <span>{{ $admin_data->name }}</span>
                        </span>
                    </div>

                    <div class="d-flex flex-wrap">
                        <span class="profile-name mt-2 mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #aaa;">Email</small>
                            {{ $admin_data->email }}
                        </span>
                    </div>
                    
                    <div class="d-flex flex-wrap">
                        <span class="profile-name mt-2 mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #aaa;">Account Roles</small>
                            <div>                                
                                @if(count($admin_data->roles) > 0)
                                @foreach($admin_data->roles as $role)
                                    <span class="badge bg-success">{{ ucfirst($role->name) }}</span>
                                @endforeach
                                @endif
                            </div>
                        </span>
                    </div>

                    <div class="d-flex flex-wrap mt-2">
                        <span class="profile-name mt-2 mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #aaa;">Last Updated At</small>
                            <span>{{ date('h:i a, d M Y', strtotime($admin_data->updated_at)) }}</span>
                        </span>

                        <span class="profile-name mt-2 mb-1 fs-5 fw-bold text-muted d-flex align-items-stretch me-3" style="flex-direction: column;">
                            <small class="fs-6" style="color: #aaa;">Created At</small>
                            <span>{{ date('h:i a, d M Y', strtotime($admin_data->created_at)) }}</span>
                        </span>
                    </div>

                    <div class="d-flex flex-wrap mt-2" style="gap: 10px;">
                        @if (!$admin_data->trash)
                            @if (Auth::user()->can('account.edit'))
                            <a href="{{ route('adminUpdateAccount', ['admin_data' => $admin_data->id]) }}" class="btn d-block btn-outline-primary">Edit</a>
                            @endif

                            @if (Auth::user()->can('account.delete'))
                            <a href="{{ route('adminRemoveAccount', ['admin_data' => $admin_data->id]) }}" class="btn d-block btn-outline-danger delete-confirm">Delete</a>
                            @endif
                        @else
                            @if (Auth::user()->can('account.delete') && !$admin_data->hasRole('superadmin'))
                                <a href="{{ route('adminTrashRecordsRestore', ['data' => $admin_data->id]) }}" class="btn d-block btn-outline-success restore-confirm">Restore Account</a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- body header end -->


@if (Auth::user()->can('account.history'))
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Account's Data Activity Log</h3>
        </div>
    </div>
</header>

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
                    <th class="no-sort text-end">
                        <span class="header-action-title" title="Action">Action</span>
                    </th>
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
                    
                    @if (Auth::user()->can('account.delete'))
                    <td style="text-align: right;">
                        <span class="table-action">
                            <a href="{{ route('adminHistoryItemRemove', ['data' => $admin_data->id, 'item' => $value->id]) }}" title="Delete" class='delete-confirm'>
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

<x-data-table table="datatable" statusUrl=""/>

@push('scripts')
    <script type="text/javascript">
        let exportable_column = [0,1,2,3,4,5,6,7];
    </script>
@endpush

@endif

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