@extends('backend.layouts.app', ['submenu' => 'create', 'bread' => 'create'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Send Notificaton</h3>
        </div>
    </div>
</header>
<!-- body header end -->


<!-- body content start -->
<div class="body-content">
    <div class="container">
        <!-- form content -->
        <form action="{{ route('notificationCreatePost') }}" method="post">
            @csrf

            <div class="row">

                <!-- customer -->
                <div class="col-sm-8 required mb-3">
                    <label for="customer">Customer</label>
                    <select class="select-customer" required name="customer" id="customer" data-url="{{ route('ajaxCustomerDataFetch') }}" style="width: 100%; display: hidden;">
                        @if($predefinedData['customer'])
                            @php $customerData = \App\Models\Customer::findOrFail($predefinedData['customer']); @endphp
                            @if($customerData)
                            <option selected value="{{ $customerData['id'] }}">{{ $customerData['first_name'].' '.$customerData['last_name'] }}</option>
                            @endif
                        @else
                            <option selected disabled value="">Select Customer</option>
                        @endif
                    </select>
                </div>
                <!-- customer -->

                <!-- via -->
                @php
                    $typeData = $predefinedData['notificationType'] ? json_decode($predefinedData['notificationType']) : [];
                @endphp

                <div class="col-sm-4 required mb-3">
                    <label for="via">Notification Type</label>
                    <select class="select-via" required name="via[]" multiple id="via" style="width: 100%; display: hidden;">
                        <option value="sms" {{ in_array('sms', $typeData) ? 'selected' : '' }}>SMS</option>
                        <option value="mail" {{ in_array('mail', $typeData) ? 'selected' : '' }}>Mail</option>
                    </select>
                </div>
                <!-- via -->

                <!-- greeting -->
                <div class="col-sm-6 required">
                    <label for="greeting">Greeting Text</label>
                    <input type="text" name="greeting" id="greeting" value="{{ $predefinedData['greeting'] ? $predefinedData['greeting'] : old('greeting') }}" placeholder="" class="form-control mb-3" required>
                </div>
                <!-- greeting -->

                <!-- subject -->
                <div class="col-sm-6 required">
                    <label for="subject">Subject</label>
                    <input type="text" name="subject" id="subject" value="{{ $predefinedData['subject'] ? $predefinedData['subject'] : old('subject') }}" placeholder="" class="form-control mb-3" required>
                </div>
                <!-- subject -->

                <!-- text -->
                @if($predefinedData['customer'])
                    @php
                        $customerData = \App\Models\Customer::findOrFail($predefinedData['customer']);
                        if($customerData) {
                            $predefinedBody = ($customerData['first_name'].' '.$customerData['last_name']).', '.($predefinedData['text'] ? $predefinedData['text'] : '');
                        }
                    @endphp
                @endif

                <div class="col-sm-12 required">
                    <label for="text">Notification Body</label>
                    <textarea name="text" id="text" class="form-control mb-3 resize-verticle" rows="3" required>{{ $predefinedBody ? $predefinedBody : old('text') }}</textarea>
                </div>
                <!-- text -->

                <!-- button -->
                <div class="form-group col-12 mt-4 my-5">
                    <button type="submit" class="btn btn-success">Send Notification</button>
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
    <script type="text/javascript" src="{{ asset('assets/notification/scripts.js') }}"></script>
@endpush