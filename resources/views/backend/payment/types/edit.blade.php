@extends('backend.layouts.app', ['submenu' => 'update', 'bread' => 'update'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Edit payment type data</h3>
        </div>
    </div>
</header>
<!-- body header end -->


<!-- body content start -->
<div class="body-content">
    <div class="container">
        <!-- form content -->
        <form action="{{ route('paymentTypeUpdatePost', ['data' => $data->id]) }}" method="post">
            @csrf

            <div class="row">

                <!-- name -->
                <div class="col-sm-10 required">
                    <label for="name">Payment Type Name</label>
                    <input type="text" name="name" id="name" value="{{ $data->name }}" placeholder="" class="form-control mb-3" required>
                </div>
                <!-- name -->

                <!-- button -->
                <div class="form-group col-12 mt-4 my-5">
                    <button type="submit" class="btn btn-success">Save Data</button>
                    <button type="reset" class="btn btn-danger">Reset</button>

                    @if (Auth::user()->can('paymentType.view'))
                    <a href="{{ route('paymentTypeView', ['data' => $data->id]) }}" class="btn btn-secondary">View Details</a>
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