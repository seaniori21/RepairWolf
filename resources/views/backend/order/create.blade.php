@extends('backend.layouts.app', ['submenu' => 'create', 'bread' => 'create'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Add Order</h3>
        </div>
    </div>
</header>
<!-- body header end -->


<!-- body content start -->
<div class="body-content">
    <div class="container">
        <form action="{{ route('admin.order.create.post') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- order_no -->
                <div class="col-sm-4">
                    <div class="input-group input-group mb-3">
                      <span class="input-group-text">Order No: #</span>
                      <input type="number" name="order_no" required class="form-control" value="{{ $lastOrderNo ? $lastOrderNo+1 : 1000 }}" style="color: #a1a5b7 !important;">
                    </div>
                </div>
                <!-- order_no -->

                <!-- status -->
                <div class="col-sm-4">
                    <div class="input-group mb-3">
                      <span class="input-group-text">Order Status</span>
                      <select class="form-select @if(!Auth::user()->hasRole('superadmin'))status-check-confirm @endif" name="status">
                        <option value="open">Open</option>
                        <option value="closed">Closed</option>
                      </select>
                    </div>
                </div>
                <!-- status -->

                <!-- order_date -->
                <div class="col-sm-4">
                    <div class="input-group mb-3">
                      <span class="input-group-text">Date</span>
                      <input type="datetime-local" required name="date" id="date" value="{{ date('Y-m-d') }}" class="form-control" placeholder="Select Date">
                    </div>
                </div>
                <!-- order_date -->                


                {{-- <div class="col-sm-4">
                    <div class="input-group mb-3">
                      <span class="input-group-text">Due Date</span>
                      <input type="date" required name="due_date" class="form-control" placeholder="Select Date">
                    </div>
                </div> --}}

                {{-- <div class="col-sm-4">
                    <div class="input-group mb-3">
                      <label class="input-group-text" for="currency_select" style="width: auto;">Currency</label>
                      <select class="form-select" name="currency" id="currency_select">
                        @if(\App\Helpers\Currency::options())
                        @foreach(\App\Helpers\Currency::options() as $currency => $sign)
                        <option value="{{ $sign }}">{{ $currency }}</option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                </div> --}}

                <div class="col-sm-6">
                    <div class="form-group mb-3">
                        <!-- customer -->
                        <span for="customer" class="fs-5 fw-semibold d-block mb-2">Customer</span>
                        <div class="input-group mb-3">
                            <select required class="select-customer" name="customer" data-url="{{ route('ajaxCustomerDataFetch') }}" id="customer" style="width: 100%; display: hidden;">
                                <option value="" selected disabled>Select a customer</option>
                            </select>
                        </div>
                        <!-- customer -->

                        <!-- vehicle -->
                        <span for="vechicle" class="fs-5 fw-semibold d-block mb-2">Customer Vehicle</span>
                        <div class="input-group mb-3">
                            <select required class="select-vehicle" name="vehicle" data-details-fetch-url="{{ route('ajaxVehicleDataFetchById') }}" data-url="{{ route('ajaxVehicleDataFetch') }}" id="vechicle" style="width: 100%; display: hidden;">
                                <option value="" selected disabled>Select Vehicle</option>
                                {{-- <option>2024, BMW, AMG, SUV</option> --}}
                            </select>
                        </div>
                        <!-- vehicle -->
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group mb-3">
                        <!-- employee: cashier -->
                        <span for="cashier" class="fs-5 fw-semibold d-block mb-2">Employee</span>
                        <div class="input-group mb-3">
                            <select required class="select-cashier" name="cashier" data-url="{{ route('ajaxCashierDataFetch') }}" id="cashier" style="width: 100%; display: hidden;">
                                <option value="" selected disabled>Select a cashier</option>
                            </select>
                        </div>
                        <!-- employee: cashier -->

                        <!-- employee: service_person -->
                        <span for="service_person" class="fs-5 fw-semibold d-block mb-2">Service Person</span>
                        <div class="input-group mb-3">
                            <select required class="select-service_person" name="service_person" data-url="{{ route('ajaxServicePersonDataFetch') }}" id="service_person" style="width: 100%; display: hidden;">
                                <option value="" selected disabled>Select service person</option>
                            </select>
                        </div>
                        <!-- employee: service_person -->
                    </div>                    
                </div>

                <div class="col-sm-12 mt-4 mb-0">
                    <div class="form-group mb-3">
                        <span class="fs-5 fw-semibold d-block mb-2">Vehicle Contents</span>

                        <div class="row">
                            <!-- vin -->
                            <div class="col-sm-4">
                                <div class="input-group mb-3">
                                  <span class="input-group-text">VIN</span>
                                  <input disabled type="text" id="vin" value="" class="form-control" style="cursor: default;">
                                </div>
                            </div>
                            <!-- vin -->

                            <!-- license plate -->
                            <div class="col-sm-4">
                                <div class="input-group mb-3">
                                  <span class="input-group-text">Licence Plate</span>
                                  <input disabled type="text" id="license_plate" value="" class="form-control" style="cursor: default;">
                                </div>
                            </div>
                            <!-- license plate -->

                            <!-- color -->
                            <div class="col-sm-4">
                                <div class="input-group mb-3">
                                  <span class="input-group-text">Color</span>
                                  <input disabled type="text" id="color" value="" class="form-control" style="cursor: default;">
                                </div>
                            </div>
                            <!-- color -->
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 mt-4 mb-1">
                   <div class="form-group required mb-0">
                        <span class="fs-5 d-block mb-2">
                            Products
                            <img id="product-loader" class="ms-1" src="{{ asset('assets/images/media/page-loader.gif') }}" style="width:15px; height:15px; display: none;">
                        </span>
                    </div>

                    <!-- products -->
                    <div class="input-group mb-3">
                        <select class="select-products" name="product" data-details-fetch-url="{{ route('ajaxProductDataFetchById') }}" data-url="{{ route('ajaxProductDataFetch') }}" id="product" style="width: 100%; display: hidden;">
                            <option selected disabled>Select a product to add</option>
                        </select>
                    </div>
                    <!-- products -->                    
                </div>

                <div class="col-sm-12 mb-0">
                    <!-- products table -->
                    <div class="table-responsive">
                        <table class="table table-borderless mb-1">
                          <thead>
                            <tr style="border-bottom: 1px solid #e4e4e4;font-weight: bold;">
                              <th scope="col">Qty <span class="text-danger">*</span></th>
                              <th scope="col">Key</th>
                              <th scope="col">UPC</th>
                              <th scope="col">Name</th>
                              <th scope="col">Manufacturer</th>
                              <th scope="col">Type</th>
                              <th scope="col">Notificaton</th>
                              <th scope="col">Base Price</th>
                              <th scope="col">List Price</th>
                              <th scope="col" class="text-end">Total</th>
                              <th scope="col" class="text-end">Action</th>
                            </tr>
                          </thead>
                          <tbody style="font-weight: 200;" class="product-item-container">
                            @foreach([1] as $key => $item)
                            {{-- <tr class="product-row row-no-{{ $key }}">
                                <td>
                                    <input type="hidden" name="product[{{ $key }}][id]">
                                    <input required name="product[{{ $key }}][quantity]" type="number" value="1"  min="1" class="form-control special-input my-1 px-1 product-{{ $key }}-quantity" placeholder="" style="width: 40px">
                                </td>

                                <td class="pt-3"><span class="product-{{ $key }}-key" title="Vero possimus ac">{{ Str::limit('Vero possimus ac', 11,'...') }}</span></td>
                                <td class="pt-3"><span class="product-{{ $key }}-upc" title="Est ipsum volupt">{{ Str::limit('Est ipsum volupt', 11,'...') }}</span></td>
                                <td class="pt-3"><span class="product-{{ $key }}-name" title="Mobil">{{ Str::limit('Mobil', 11,'...') }}</span></td>
                                <td class="pt-3"><span class="product-{{ $key }}-manufacturer" title="Omnis et numquam">{{ Str::limit('Omnis et numquam', 11,'...') }}</span></td>
                                <td class="pt-3"><span class="product-{{ $key }}-type text-capitalize">part</span></td>

                                <td>
                                    <button type="button" class="btn btn-sm btn-secondary my-1 px-2" data-bs-toggle="modal" data-bs-target="#notification-modal-id-{{ $key }}" data-modal-id="{{ $key }}">Setup</button>
                                </td>

                                <td>
                                    <input required name="product[{{ $key }}][base_price]" type="number" value=""  min="0" step="0.01" class="form-control special-input my-1 px-1 product-{{ $key }}-basePrice" placeholder="0.00" required style="width: 90px; padding: 4px;">
                                </td>

                                <td>
                                    <input required name="product[{{ $key }}][list_price]" type="number" value=""  min="0" step="0.01" class="form-control special-input my-1 px-1 product-{{ $key }}-listPrice" placeholder="0.00" style="width: 90px; padding: 4px;">
                                </td>                                

                                <td class="pt-3 text-end">
                                    <span class="currency-sign">$</span>
                                    <span class="product-{{ $key }}-total">0.00</span>
                                </td>

                                <td class="text-end">
                                    <button type="button" class='btn btn-danger btn-sm my-1 px-2 py-0 remove-product-item-btn'>
                                        <i class="icon ion-ios-trash io-18"></i>
                                    </button>
                                </td>
                            </tr> --}}
                            @endforeach

                            <tr class="empty-product-row">
                                <td class="text-center" colspan="11">No product added yet</td>
                            </tr>
                          </tbody>                          
                        </table>

                        <!-- summery -->
                        <table class="float-end mb-4">
                            <tbody class="product-table-summery">
                                <!-- tax -->
                                <tr>
                                    <td class="pt-2 pb-0 pe-5 text-end">
                                        <h6 class="fw-semibold mb-0 fs-16">TAX</h6>
                                    </td>
                                    <td class="pt-2 pb-0 text-end">
                                        <h6 class="pt-0 mb-0 fw-semibold">
                                            <span class="currency-sign fs-16">%</span>
                                            <input name="tax" id="tax-input" type="number" value="8.875"  min="0" step="0.001" class="form-control special-input mt-0 fs-6 p-0 px-1 d-inline" placeholder="0.00" style="width: 100%;max-width: 70px;text-align: right;">
                                        </h6>
                                    </td>
                                    <td style="width: 65px;"></td>
                                </tr>
                                <!-- tax -->

                                <!-- convenience -->
                                <tr>
                                    <td class="pt-2 pb-0 pe-5 text-end">
                                        <h6 class="fw-semibold mb-0 fs-16">Convenience Fee</h6>
                                    </td>
                                    <td class="pt-2 pb-0 text-end">
                                        <h6 class="pt-0 mb-0 fw-semibold">
                                            <span class="currency-sign fs-16">$</span>
                                            <input name="convenience_fee" id="convenience_fee-input" type="number" value=""  min="0" step="0.01" class="form-control special-input mt-0 fs-6 p-0 px-1 d-inline" placeholder="0.00" style="width: 100%;max-width: 70px;text-align: right;">
                                        </h6>
                                    </td>
                                    <td style="width: 65px;"></td>
                                </tr>
                                <!-- convenience -->

                                <!-- discount -->
                                <tr class="d-none">
                                    <td class="pt-2 pb-0 pe-5 text-end">
                                        <h6 class="fw-semibold mb-0 fs-16">Discount</h6>
                                    </td>
                                    <td class="pt-2 pb-0 text-end">
                                        <h6 class="pt-0 mb-0 fw-semibold">
                                            <span class="currency-sign fs-16">$</span>
                                            <input name="discount" id="discount-input" type="number" value=""  min="0" step="0.01" class="form-control special-input mt-0 fs-6 p-0 px-1 d-inline" placeholder="0.00" style="width: 100%;max-width: 70px;text-align: right;">
                                        </h6>
                                    </td>
                                    <td style="width: 65px;"></td>
                                </tr>
                                <!-- discount -->

                                <!-- total -->
                                <tr>
                                    <td class="pt-3 pb-0 pe-5 text-end">
                                        <h6 class="fw-semibold mb-0 fs-16">Total</h6>
                                    </td>
                                    <td class="pt-3 pb-0 text-end">
                                        <h6 class="pt-0 mb-0 fw-semibold">
                                            <span class="currency-sign fs-16">$</span>
                                            <span class="product-subtotal fs-16 pe-1" id="subtotal-output">0.00</span>
                                        </h6>
                                    </td>
                                    <td style="width: 65px;"></td>
                                </tr>
                                <!-- total -->

                                <!-- paid -->
                                <tr>
                                    <td class="pt-2 pb-0 pe-5 text-end">
                                        <h6 class="fw-semibold mb-0 fs-16">Paid Amount</h6>
                                    </td>
                                    <td class="pt-2 pb-0 text-end">
                                        <h6 class="pt-0 mb-0 fw-semibold">
                                            <span class="currency-sign fs-16">$</span>
                                            <span class="paid-amount fs-16 pe-1" id="paid-amount-output">0.00</span>
                                        </h6>
                                    </td>
                                    <td style="width: 65px;"></td>
                                </tr>
                                <!-- paid -->

                                <!-- due -->
                                <tr>
                                    <td class="pt-2 pb-0 pe-5 text-end">
                                        <h6 class="fw-semibold mb-0 fs-16">Due Amount</h6>
                                    </td>
                                    <td class="pt-2 pb-0 text-end">
                                        <h6 class="pt-0 mb-0 fw-semibold">
                                            <span class="currency-sign fs-16">$</span>
                                            <span class="payment-due fs-16 pe-1" id="payment-due-output">0.00</span>
                                        </h6>
                                    </td>
                                    <td style="width: 65px;"></td>
                                </tr>
                                <!-- due -->
                            </tbody>
                        </table>
                        <!-- summery -->
                    </div>
                    <!-- products table -->
                </div>

                <div class="col-sm-12 mt-4 mb-0">
                   <div class="form-group required mb-0">
                        <span class="fs-5 d-block mb-2">Customer Payment Data</span>
                    </div>

                    <!-- customer payment table -->
                    <div class="table-responsive">
                        <table class="table table-borderless">
                          <thead>
                            <tr style="border-bottom: 1px solid #e4e4e4;font-weight: bold;">
                              <th scope="col" style="width: 12%;">Type <span class="text-danger">*</span></th>
                              <th scope="col" style="width: 8%;">Amount (<span class="item-currency-sign">$</span>) <span class="text-danger">*</span></th>
                              <th scope="col" style="width: 8%;">Authorization Approval Code</th>
                              <th scope="col" style="width: 20%;">Credit Card Number</th>
                              <th scope="col" style="width: 15%;">Expiration Date</th>
                              <th scope="col" style="width: 12%;">Security Code</th>
                              <th scope="col" class="text-end" style="width: 0%;">Action</th>
                            </tr>
                          </thead>
                          <tbody style="font-weight: 200;" class="payment-item-container">
                            @foreach([1] as $key => $item)
                            {{-- <tr class="payment-row row-no-{{ $key }}">
                                <td>
                                    <div class="special-input my-1">
                                        <select required class="select-payment_type payment-{{ $key }}-payment_type" name="payment[{{ $key }}][payment_type]" style="width: 100%;">
                                            <option selected disabled>Select</option>
                                        </select>
                                    </div>
                                </td>

                                <td>
                                    <input required name="payment[{{ $key }}][amount]" type="number" value=""  min="0" class="form-control special-input my-1 payment-{{ $key }}-amount" placeholder="0.00" style="min-width: 10px">
                                </td>

                                <td>
                                    <input name="payment[{{ $key }}][auth_approval_code]" type="text" value="" class="form-control special-input my-1 payment-{{ $key }}-auth_approval_code" placeholder="" style="min-width: 10px">
                                </td>

                                <td>
                                    <input name="payment[{{ $key }}][credit_card_number]" type="text" value="" class="form-control special-input my-1 payment-{{ $key }}-credit_card_number" placeholder="" style="min-width: 10px">
                                </td>

                                <td>
                                    <input name="payment[{{ $key }}][expiration_date]" type="date" class="form-control special-input my-1 payment-{{ $key }}-expiration_date" value="" placeholder="">
                                </td>

                                <td>
                                    <input name="payment[{{ $key }}][security_code]" type="text" class="form-control special-input my-1 payment-{{ $key }}-security_code" value="" placeholder="">
                                </td>

                                <td class="text-end">
                                    <button type="button" class='btn btn-danger btn-sm my-1 px-2 py-0 remove-payment-item-btn'>
                                        <i class="icon ion-ios-trash io-18"></i>
                                    </button>
                                </td>
                            </tr> --}}
                            @endforeach
                            <tr class="empty-payment-row">
                                <td class="text-center" colspan="7">No payment added yet</td>
                            </tr>
                          </tbody>
                        </table>                        
                    </div>
                    <!-- customer payment table -->
                </div>

                <div class="col-xl-12 mb-4">
                    <button type="button" class="btn btn-dark btn-sm fs-6" id="add-payment-item-btn" data-payment-type-url="{{ route('ajaxPaymentTypeDataFetch') }}">Add Customer Payment Item</button>
                </div>

                <!-- comment -->
                <div class="col-sm-12 mt-4 mb-0">
                   <div class="form-group required mb-0">
                        <span class="fs-5 d-block mb-2">Order Comment</span>
                    </div>

                    <!-- comment table -->
                    <div class="table-responsive">
                        <table class="table table-borderless">
                          <thead>
                            <tr style="border-bottom: 1px solid #e4e4e4;font-weight: bold;">
                              <th scope="col" style="width: 8%;">Comment <span class="text-danger">*</span></th>
                              <th scope="col" style="width: 8%;">Attachment</th>
                              <th scope="col" class="text-end" style="width: 0%;">Action</th>
                            </tr>
                          </thead>
                          <tbody style="font-weight: 200;" class="comment-item-container">
                            @foreach([1] as $key => $item)
                            {{-- <tr class="comment-row row-no-{{ $key }}">
                                <td>
                                    <textarea name="comment[{{ $key }}][text]" class="form-control resize-vertical special-input my-1 comment-{{ $key }}-text" value="" placeholder="Type Order Comment" required rows="1"></textarea>
                                </td>

                                <td>
                                    <input name="comment[{{ $key }}][file]" type="file" value="" class="form-control special-input my-1 comment-{{ $key }}-file" placeholder="" style="min-width: 10px">
                                </td>

                                <td class="text-end">
                                    <button type="button" class='btn btn-danger btn-sm my-1 px-2 py-0 remove-comment-item-btn'>
                                        <i class="icon ion-ios-trash io-18"></i>
                                    </button>
                                </td>
                            </tr> --}}
                            @endforeach
                            <tr class="empty-comment-row">
                                <td class="text-center" colspan="3">No comment added yet</td>
                            </tr>

                          </tbody>
                        </table>                        
                    </div>
                    <!-- comment table -->
                </div>

                <div class="col-xl-12 mb-4">
                    <button type="button" class="btn btn-dark btn-sm fs-6" id="add-comment-item-btn">Add Comment Item</button>
                </div>
                <!-- comment -->

                <!-- Modals -->
                <div class="scheduled-notification-container">
                    {{-- <div class="modal fade scheduled-notification-modal" id="notification-modal-id-0" data-modal-id="0" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5">Setup Scheduled Notification</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <input type="hidden" name="scheduled_notification[0][notification_uuid]" value="">
                            <input type="hidden" name="scheduled_notification[0][product_id]" value="">
                            <input type="hidden" name="scheduled_notification[0][status]" value="inactive">

                            <div class="input-group mb-3">
                              <span class="input-group-text">Date</span>
                              <input type="datetime-local" name="scheduled_notification[0][date]" value="" class="form-control notify-date-input" placeholder="Select Date">
                            </div>

                            <div class="input-group">
                              <span class="input-group-text">Text&nbsp;</span>
                              <textarea name="scheduled_notification[0][text]" class="form-control notify-text-input resize-vertical special-input xmy-1 comment--text" aria-label="With textarea"></textarea>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary save-scheduled-notification-btn">Save</button>
                          </div>
                        </div>
                      </div>
                    </div> --}}
                </div>
                <!-- Modals -->

                <!-- button -->
                <div class="form-group col-12 mb-5">
                    <button type="submit" class="btn btn-success">Save Order</button>
                    <button type="reset" class="btn btn-danger">Reset</button>
                </div>
            </div>
        </form>        
    </div>
</div>
<!-- body content end -->

@endsection


@push('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style type="text/css">
        .width-fit {
            min-width: fit-content !important;
        }        
        .note-textarea { resize: vertical !important; }

        #add-log-item-btn,
        #add-more-email-btn,
        #add-more-number-btn {
            height: 30px;
            line-height: 30px;
        }
        .form-group .select2-container--default .select2-selection--multiple .select2-selection__choice{
            background-color: var(--primary)!important;
            color: white;
        }

        .form-group .select2-container--default .select2-selection--multiple .select2-selection__choice__remove{
            background-color: var(--secondary)!important;
            color: white;
        }
        .select2-container--default .select2-search--inline .select2-search__field {
            margin-bottom: 6px;
        }
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #00000040;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/order/data-fetch.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/order/scripts.js') }}"></script>

    <script type="text/javascript">let sys_currency_sign = '$';</script>
@endpush