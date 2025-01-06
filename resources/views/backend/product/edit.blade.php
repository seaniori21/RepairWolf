@extends('backend.layouts.app', ['submenu' => 'update', 'bread' => 'update'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Edit product data</h3>
        </div>
    </div>
</header>
<!-- body header end -->


<!-- body content start -->
<div class="body-content">
    <div class="container">
        <!-- form content -->
        <form action="{{ route('productUpdatePost', ['data' => $data->id]) }}" method="post">
            @csrf

            <div class="row">

                <!-- type -->
                <div class="col-sm-4 required">
                    <label for="type">Product Type</label>
                    <select required name="type" id="type" class="form-control mb-3" style="appearance: auto;">
                        <option value="labor" {{ $data->type === 'labor' ? 'selected' : '' }}>Labor</option>
                        <option value="service" {{ $data->type === 'service' ? 'selected' : '' }}>Service</option>
                        <option value="part" {{ $data->type === 'part' ? 'selected' : '' }}>Part</option>
                    </select>
                </div>
                <!-- type -->

                <!-- identification_code -->
                <div class="col-sm-4 required">
                    <label for="identification_code">Identification Code</label>
                    <input type="text" name="identification_code" id="identification_code" value="{{ $data->identification_code }}" placeholder="" class="form-control mb-3" required>
                </div>
                <!-- identification_code -->

                <!-- upc -->
                <div class="col-sm-4">
                    <label for="upc">UPC</label>
                    <input type="text" name="upc" id="upc" value="{{ $data->upc }}" placeholder="" class="form-control mb-3">
                </div>
                <!-- upc -->

                <!-- base_price -->
                <div class="col-sm-6 required">
                    <label for="base_price">Base Price</label>
                    <input type="number" min="0" step="0.01" name="base_price" id="base_price" value="{{ $data->base_price }}" placeholder="" class="form-control mb-3" required>
                </div>
                <!-- base_price -->

                <!-- list_price -->
                <div class="col-sm-6">
                    <label for="list_price">List Price</label>
                    <input type="number" min="0" step="0.01" name="list_price" id="list_price" value="{{ $data->list_price }}" placeholder="" class="form-control mb-3">
                </div>
                <!-- list_price -->              

                <!-- name -->
                <div class="col-sm-8 required">
                    <label for="name">Product Name</label>
                    <input type="text" name="name" id="name" value="{{ $data->name }}" placeholder="" class="form-control mb-3" required>
                </div>
                <!-- name -->

                <!-- manufacturer -->
                <div class="col-sm-4 required">
                    <label for="manufacturer">Manufacturer</label>
                    <input type="text" name="manufacturer" id="manufacturer" value="{{ $data->manufacturer }}" placeholder="" class="form-control mb-3" required>
                </div>
                <!-- manufacturer -->

                <!-- description -->
                <div class="col-sm-12">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control resize-vertical mb-3" rows="2">{{ $data->description }}</textarea>
                </div>
                <!-- description -->

                <!-- button -->
                <div class="form-group col-12 mt-4 my-5">
                    <button type="submit" class="btn btn-success">Save Data</button>
                    <button type="reset" class="btn btn-danger">Reset</button>

                    @if (Auth::user()->can('product.view'))
                    <a href="{{ route('productView', ['data' => $data->id]) }}" class="btn btn-secondary">View Details</a>
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