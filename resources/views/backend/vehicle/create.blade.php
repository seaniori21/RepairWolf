@extends('backend.layouts.app', ['submenu' => 'create', 'bread' => 'create'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Add new vehicle data</h3>
        </div>
    </div>
</header>
<!-- body header end -->


<!-- body content start -->
<div class="body-content">
    <div class="container">
        <!-- form content -->
        <form action="{{ route('vehicleCreatePost') }}" method="post">
            @csrf

            <div class="row">

                <!-- customer -->
                <div class="col-sm-4 required">
                    <label for="customer">Customer</label>
                    <select class="select-customer" required data-url="{{ route('ajaxCustomerDataFetch') }}" name="customer" id="customer" style="width: 100%; display: hidden;">
                        <option value="" selected disabled>Select customer</option>
                    </select>
                </div>
                <!-- customer -->

                <!-- license_plate -->
                <div class="col-sm-4 required">
                    <label for="license_plate">License Plate</label>
                    <input type="text" name="license_plate" id="license_plate" value="{{ old('license_plate') }}" placeholder="" class="form-control mb-3" required>
                </div>
                <!-- license_plate -->

                <!-- vin -->
                <div class="col-sm-4 required">
                    <label for="vin">VIN</label>
                    <input type="text" name="vin" id="vin" value="{{ old('vin') }}" placeholder="" class="form-control mb-3" required>
                </div>
                <!-- vin -->

                <!-- year -->
                <div class="col-sm-4 required">
                    <label for="year">Year</label>
                    <input type="text" name="year" id="year" value="{{ old('year') }}" placeholder="" class="form-control mb-3" required>
                </div>
                <!-- year -->

                <!-- make -->
                <div class="col-sm-4 required">
                    <label for="make">Make</label>

                    <select required class="select-make" name="make" id="make" style="width: 100%; display: hidden;">
                        <option value="" selected disabled>Select manufacturer</option>

                        @if(App\Helpers\StaticData::vehicleMakers())
                        @foreach(App\Helpers\StaticData::vehicleMakers() as $make)
                        <option value="{{ $make }}">{{ $make }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <!-- make -->

                <!-- model -->
                <div class="col-sm-4 required">
                    <label for="model">Model</label>
                    <input type="text" name="model" id="model" value="{{ old('model') }}" placeholder="" class="form-control mb-3" required>
                </div>
                <!-- model -->

                <!-- body_type -->
                <div class="col-sm-4">
                    <label for="body_type">Body Type</label>
                    <input type="text" name="body_type" id="body_type" value="{{ old('body_type') }}" placeholder="" class="form-control mb-3">
                </div>
                <!-- body_type -->

                <!-- trim -->
                <div class="col-sm-4">
                    <label for="trim">Trim</label>
                    <input type="text" name="trim" id="trim" value="{{ old('trim') }}" placeholder="" class="form-control mb-3">
                </div>
                <!-- trim -->

                <!-- color -->
                <div class="col-sm-4 required">
                    <label for="color">Color</label>
                    <input type="text" name="color" id="color" value="{{ old('color') }}" placeholder="" class="form-control mb-3" required>
                </div>
                <!-- color -->

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
    <script type="text/javascript">
        // customer select
        const customerDataUrl = $('.select-customer').attr('data-url');
        const customerDataSelected = $('.select-customer').attr('data-active');

        $('.select-customer').select2({
            minimumResultsForSearch: 2, /* search disabled */
            tags: false,
            placeholder: 'Select customer',
            // dropdownCssClass: "font_13",
            width: '100%',
            ajax: {
                url: customerDataUrl,
                method:'GET',
                data: function (params) {
                  return {
                    // active: customerDataSelected,
                    search: params.term,
                    type: 'public'
                  };
                },
                dataType: 'json',
                processResults: function (response) {
                    return {
                        results: response ? response : [],
                        pagination: {
                            "more": false
                        }
                    };
                },
            }
        });
        // customer select

        // make select
        const makeDataUrl = $('.select-make').attr('data-url');
        const makeDataSelected = $('.select-make').attr('data-active');

        $('.select-make').select2({
            minimumResultsForSearch: 2, /* search disabled */
            tags: false,
            placeholder: 'Select manufacturer',
            // dropdownCssClass: "font_13",
            width: '100%',
        });
        // make select
    </script>
@endpush