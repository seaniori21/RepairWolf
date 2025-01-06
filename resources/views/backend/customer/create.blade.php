@extends('backend.layouts.app', ['submenu' => 'create', 'bread' => 'create'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Add new customer data</h3>
        </div>
    </div>
</header>
<!-- body header end -->


<!-- body content start -->
<div class="body-content">
    <div class="container">
        <!-- form content -->
        <form action="{{ route('customerCreatePost') }}" method="post">
            @csrf

            <div class="row">

                <!-- name -->
                <div class="col-sm-6 required">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" placeholder="" class="form-control mb-3" required>
                </div>

                <div class="col-sm-6 required">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" placeholder="" class="form-control mb-3" required>
                </div>
                <!-- name -->

                <!-- email -->
                <div class="col-sm-4">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="" class="form-control mb-3">
                </div>
                <!-- email -->

                <!-- phone -->
                <div class="col-sm-4">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="" class="form-control mb-3">
                </div>
                <!-- phone -->

                <!-- mobile -->
                <div class="col-sm-4 required">
                    <label for="mobile">Mobile</label>
                    <input type="text" required name="mobile" id="mobile" value="{{ old('mobile') }}" placeholder="" class="form-control mb-3">
                </div>
                <!-- mobile -->

                <!-- address -->
                <div class="col-sm-12">
                    <label for="address">Address</label>
                    <input type="text" name="address_line_1" id="address" value="{{ old('address') }}" placeholder="Address line 1" class="form-control mb-2">
                    <input type="text" name="address_line_2" id="address_line_2" value="{{ old('address_line_2') }}" placeholder="Address line 2" class="form-control mb-3">
                </div>
                <!-- address -->

                <!-- city -->
                <div class="col-sm-4">
                    <label for="city">City</label>
                    <input type="text" name="city" id="city" value="{{ old('city') }}" placeholder="" class="form-control mb-3">
                </div>
                <!-- city -->

                <!-- state -->
                <div class="col-sm-4">
                    <label for="state">States</label>
                    {{-- <input type="text" name="state" id="state" value="{{ old('state') }}" placeholder="" class="form-control mb-3"> --}}

                    <select class="select-states" name="state" id="state" style="width: 100%; display: hidden;">
                        <option value="" selected disabled>Select state</option>

                        @if(App\Helpers\StaticData::states())
                        @foreach(App\Helpers\StaticData::states() as $state)
                        <option value="{{ $state }}">{{ $state }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <!-- state -->

                <!-- zip -->
                <div class="col-sm-4" style="position: relative;">
                    <label for="zip_code">Postal / Zip Code</label>
                    <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code') }}" placeholder="Postal / Zip Code" class="form-control mb-2" list="browsers">
                </div>
                <!-- zip -->

                <!-- comment -->
                <div class="col-sm-12 xmb-4">
                    <label>Comment</label>
                    
                    <div class="comment-inputs-container mb-2">
                        @if(old('comment'))
                            @foreach(old('comment') as $key => $old_comment)
                            <div class="comment-item d-flex mb-2">
                                <textarea required name="comment[]" class="form-control resize-vertical me-0" id="comment-{{ $key }}" rows="1">{{ $old_comment }}</textarea>
                                
                                @if($key > 0)
                                <button type="button" class="btn btn-danger btn-sm mt-0 remove-comment-item-btn">Remove</button>
                                @endif
                            </div>
                            @endforeach
                        @else
                            {{-- <div class="comment-item d-flex align-items-start gap-2 mb-2">
                                <textarea required name="comment[]" class="form-control resize-vertical me-0" id="comment-1" rows="1"></textarea>
                                <button type="button" class="btn btn-danger btn-sm mt-0 remove-comment-item-btn">Remove</button>
                            </div> --}}
                        @endif
                    </div>

                    <button type="button" class="btn btn-dark btn-sm" id="add-more-comment-btn" data-count='{{ old('comment') ? count(old('comment')) : 0}}'>Add comment</button>
                </div>
                <!-- comment -->

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
        // comment
        $('#add-more-comment-btn').on('click', function(e) {
            var count = parseInt($(this).attr('data-count'));

            $('.comment-inputs-container').append(`<div class="comment-item d-flex align-items-start gap-2 mb-2">
                <textarea required name="comment[]" class="form-control resize-vertical" id="comment-${count+1}" rows="1"></textarea>
                <button type="button" class="btn btn-danger btn-sm mt-0 remove-comment-item-btn">Remove</button>
            </div>`);
            
            $(this).attr('data-count', count+1);
        });

        $('.comment-inputs-container').on('click','.comment-item .remove-comment-item-btn',function(e) {
            e.preventDefault();
            $(this).closest('.comment-item').remove();
        });
        // comment

        // states select
        const statesDataUrl = $('.select-states').attr('data-url');
        const statesDataSelected = $('.select-states').attr('data-active');

        $('.select-states').select2({
            minimumResultsForSearch: 2, /* search disabled */
            tags: false,
            placeholder: 'Select states',
            // dropdownCssClass: "font_13",
            width: '100%',
        });
        // states select
    </script>
@endpush