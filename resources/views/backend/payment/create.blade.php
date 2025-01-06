@extends('backend.layouts.app', ['submenu' => 'create', 'bread' => 'create'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Add new payment data</h3>
        </div>
    </div>
</header>
<!-- body header end -->


<!-- body content start -->
<div class="body-content">
    <div class="container">
        <!-- form content -->
        <form action="{{ route('paymentCreatePost') }}" method="post">
            @csrf

            <div class="row">

                <!-- payment_type -->
                <div class="col-sm-6 required mb-3">
                    <label for="payment_type">Payment Type</label>
                    <select required class="select-payment_type" name="payment_type" id="payment_type" data-url="{{ route('ajaxPaymentTypeDataFetch') }}" style="width: 100%; display: hidden;">
                        <option value="" selected disabled>Select a payment type</option>
                    </select>
                </div>
                <!-- payment_type -->

                <!-- order -->
                <div class="col-sm-6 required mb-3">
                    <label for="order">Order No</label>
                    <select required class="select-order" data-url="{{ route('ajaxOrderDataFetch') }}" name="order" id="order" style="width: 100%; display: hidden;">
                        <option value="" selected disabled>Select order no</option>
                    </select>
                </div>
                <!-- order -->

                <!-- amount -->
                <div class="col-sm-6 required">
                    <label for="amount">Payment Amount</label>
                    <input type="number" min="0" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" placeholder="" class="form-control mb-3" required>
                </div>
                <!-- amount -->

                <!-- authorization_approval_code -->
                <div class="col-sm-6">
                    <label for="authorization_approval_code">Authorization Approval Code</label>
                    <input type="text" name="authorization_approval_code" id="authorization_approval_code" value="{{ old('authorization_approval_code') }}" placeholder="" class="form-control mb-3">
                </div>
                <!-- authorization_approval_code -->

                <!-- credit_card_number -->
                <div class="col-sm-4">
                    <label for="credit_card_number">Credit Card Number</label>
                    <input type="text" name="credit_card_number" id="credit_card_number" value="{{ old('credit_card_number') }}" placeholder="" class="form-control mb-3">
                </div>
                <!-- credit_card_number -->                

                <!-- expiration_date -->
                <div class="col-sm-4">
                    <label for="expiration_date">Expiration Date</label>
                    <input type="date" name="expiration_date" id="expiration_date" value="{{ old('expiration_date') }}" placeholder="" class="form-control mb-3" style="color: black !important;">
                </div>
                <!-- expiration_date -->              

                <!-- security_code -->
                <div class="col-sm-4">
                    <label for="security_code">Security Code</label>
                    <input type="text" name="security_code" id="security_code" value="{{ old('security_code') }}" placeholder="" class="form-control mb-3">
                </div>
                <!-- security_code -->

                <!-- button -->
                <div class="form-group col-12 mt-4 my-5">
                    <button type="submit" class="btn btn-success">Save Data</button>
                    <button type="reset" class="btn btn-danger">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- body content end -->

{{-- <x-ckeditor classes="content"/> --}}

@endsection


@push('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style type="text/css">
        .width-fit {
            min-width: fit-content !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/payment/scripts.js') }}"></script>
@endpush