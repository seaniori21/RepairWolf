@extends('backend.layouts.app', ['submenu' => 'trashed-comment-records', 'bread' => 'trashed-comment-records'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Trashed Customer Comments</h3>
        </div>

        @if (Auth::user()->can('customer.view'))
        <div class="header-title mt-2">
            <h5>Customer Name: 
                <a href="{{ route('customerView', ['data' => $data->id]) }}" class="py-0">{{ $data->first_name.' '.$data->last_name }}</a>
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
                    <th>Created At</th>

                    @if (Auth::user()->can('customer.delete'))
                    <th class="no-sort text-end">
                        <span class="header-action-title" title="Action">Action</span>
                        <span class="header-action"></span>
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(count($customerComments) > 0)
                @foreach($customerComments as $key=>$value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->text }}</td>
                    <td>{{ date('h:i a, d M Y', strtotime($value->created_at)) }}</td>

                    @if (Auth::user()->can('customer.delete'))
                    <td style="text-align: right;">
                        <span class="table-action">
                            @if (Auth::user()->can('customer.view'))
                            <a href="{{ route('customerTrashCommentRestore', ['data' => $data->id, 'item' => $value->id]) }}" class="restore-confirm" title="Restore">
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
        let exportable_column = [0,1,2];
    </script>
@endpush

<x-data-table table="datatable" statusUrl=""/>