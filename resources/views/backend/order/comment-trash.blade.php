@extends('backend.layouts.app', ['submenu' => 'trashed-records', 'bread' => 'trashed-records'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Trashed Order Comments</h3>
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
                    <th>Comment</th>
                    <th>Attachment</th>
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
                @if(count($orderComments) > 0)
                @foreach($orderComments as $key => $value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->text }}</td>
                    <td>
                        @if($value->attachment)
                        <a href="{{ asset('upload/attachments/'.$value->attachment) }}" class="ps-0 d-flex align-items-center relative" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>

                            <span class="ms-1">{{ $value->attachment_name }}</span>
                        </a>
                        @else N/A @endif
                    </td>

                    <td>{{ date('h:i a, d M Y', strtotime($value->created_at)) }}</td>
                    
                    @if (Auth::user()->can('order.delete'))
                    <td style="text-align: right;">
                        <span class="table-action">
                            <a href="{{ route('admin.order.comment.trashed.restore', ['data' => $data->id, 'item' => $value->id]) }}" title="Restore">
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
        let exportable_column = [0,1,2,3];
    </script>
@endpush

<x-data-table table="datatable" statusUrl=""/>