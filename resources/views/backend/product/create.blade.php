@extends('backend.layouts.app', ['submenu' => 'create', 'bread' => 'create'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Add new product data</h3>
        </div>
    </div>
</header>
<!-- body header end -->


<!-- body content start -->
<div class="body-content">
    <div class="container">
        <!-- form content -->
        <form action="{{ route('productCreatePost') }}" method="post">
            @csrf

            <div class="row">

                <!-- type -->
                <div class="col-sm-4 required">
                    <label for="type">Product Type</label>
                    <select required name="type" id="type" class="form-control mb-3" style="appearance: auto;">
                        <option value="labor">Labor</option>
                        <option value="service">Service</option>
                        <option value="part">Part</option>
                    </select>
                </div>
                <!-- type -->

                <!-- identification_code -->
                <div class="col-sm-4 required">
                    <label for="identification_code">Identification Code</label>
                    <input type="text" name="identification_code" id="identification_code" value="{{ old('identification_code') }}" placeholder="" class="form-control mb-3" required>
                </div>
                <!-- identification_code -->

                <!-- upc -->
                <div class="col-sm-4">
                    <label for="upc">UPC</label>
                    <input type="text" name="upc" id="upc" value="{{ old('upc') }}" placeholder="" class="form-control mb-3">
                </div>
                <!-- upc -->

                <!-- base_price -->
                <div class="col-sm-6 required">
                    <label for="base_price">Base Price</label>
                    <input type="number" min="0" step="0.01" name="base_price" id="base_price" value="{{ old('base_price') }}" placeholder="" class="form-control mb-3" required>
                </div>
                <!-- base_price -->

                <!-- list_price -->
                <div class="col-sm-6">
                    <label for="list_price">List Price</label>
                    <input type="number" min="0" step="0.01" name="list_price" id="list_price" value="{{ old('list_price') }}" placeholder="" class="form-control mb-3">
                </div>
                <!-- list_price -->              

                <!-- name -->
                <div class="col-sm-8 required">
                    <label for="name">Product Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="" class="form-control mb-3" required>
                </div>
                <!-- name -->

                <!-- manufacturer -->
                <div class="col-sm-4 required">
                    <label for="manufacturer">Manufacturer</label>
                    <input type="text" name="manufacturer" id="manufacturer" value="{{ old('manufacturer') }}" placeholder="" class="form-control mb-3" required>
                </div>
                <!-- manufacturer -->

                <!-- description -->
                <div class="col-sm-12">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control resize-vertical mb-3" rows="2">{{ old('description') }}</textarea>
                </div>
                <!-- description -->

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
    <style type="text/css">
        .width-fit {
            min-width: fit-content !important;
        }
    </style>
@endpush