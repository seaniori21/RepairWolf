@extends('backend.layouts.app', ['submenu' => 'trashed-records', 'bread' => 'trashed-records'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Trashed Customers</h3>
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
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Created At</th>

                    @if (Auth::user()->can('customer.delete'))
                    <th class="no-sort text-end">
                        <span class="header-action-title" title="Action">Action</span>
                        <span class="header-action">
                        </span>
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(count($customers) > 0)
                @foreach($customers as $key=>$value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    
                    @php
                        $names = [
                            $value->first_name,
                            $value->last_name
                        ];
                    @endphp

                    <td title="{{ implode(' ', $names) }}">{{ Illuminate\Support\Str::limit(implode(' ', $names), 15, ' (...)') }}</td>

                    <td>{{ $value->email }}</td>
                    <td>{{ $value->mobile }}</td>
                    <td>{{ date('h:i a, d M Y', strtotime($value->created_at)) }}</td>

                    @if (Auth::user()->can('customer.history') || Auth::user()->can('customer.view') || Auth::user()->can('customer.delete'))
                    <td style="text-align: right;">
                        <span class="table-action">
                            @if (Auth::user()->can('customer.history') || Auth::user()->can('customer.view'))
                            <a href="{{ route('customerView', ['data' => $value->id]) }}" title="View">
                                <i class="icon ion-ios-eye io-19"></i>
                            </a>
                            @endif
                            @if (Auth::user()->can('customer.delete'))
                            <a href="{{ route('customerTrashRecordsRestore', ['data' => $value->id]) }}" class="restore-confirm" title="Restore">
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
        let exportable_column = [0,1,2,3,4];
    </script>
@endpush

<x-data-table table="datatable" statusUrl=""/>