@extends('backend.layouts.app', ['submenu' => 'update', 'bread' => 'update'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Edit allow list ip address</h3>
        </div>
    </div>
</header>
<!-- body header end -->


<!-- body content start -->
<div class="body-content">
    <div class="container">
        <!-- form content -->
        <form action="{{ route('ipAllowListUpdatePost', ['data' => $data->id]) }}" method="post">
            @csrf

            <div class="row">

                <!-- ip_address -->
                <div class="col-sm-6 required">
                    <label for="ip_address">IP Address</label>
                    <input type="text" minlength="7" maxlength="15" size="15" name="ip_address" id="ip_address" value="{{ $data->ip_address }}" placeholder="" class="form-control mb-3" required>
                </div>
                <!-- ip_address -->

                <!-- note -->
                <div class="col-sm-6">
                    <label for="note">Note</label>
                    <input type="text" name="note" id="note" value="{{ $data->note }}" placeholder="" class="form-control mb-3">
                </div>
                <!-- note -->

                <!-- button -->
                <div class="form-group col-12 mt-4 my-5">
                    <button type="submit" class="btn btn-success">Save Data</button>
                    <button type="reset" class="btn btn-danger">Reset</button>

                    @if (Auth::user()->can('ipallowlist.view'))
                    <a href="{{ route('ipAllowListView', ['data' => $data->id]) }}" class="btn btn-secondary">View Details</a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
<!-- body content end -->

@endsection


@push('style')
    <style type="text/css">
        .width-fit {
            min-width: fit-content !important;
        }        
        textarea { resize: vertical !important; }
    </style>
@endpush