@extends('backend.layouts.app', ['submenu' => 'update', 'bread' => 'update'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Create Invoice</h3>
        </div>
    </div>
</header>
<!-- body header end -->


<!-- body content start -->
<div class="body-content">
    <div class="container">
        <form action="{{ route('admin.invoice.edit.post', ['data' => $data->id]) }}" method="post">@csrf
            <div class="row">
                <!-- name -->
                <div class="col-sm-12">
                    <div class="input-group input-group-lg mb-3">
                      <span class="input-group-text" style="font-weight: bold;font-size: 18px;">Invoice #</span>
                      <input type="number" name="invoice_no" value="{{ $data->no }}" required class="form-control" style="color: #a1a5b7 !important;height: 54px;font-size: 18px !important;font-weight: bold;">
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="input-group mb-3">
                      <span class="input-group-text">Date</span>
                      <input type="date" required name="date" value="{{ $data->invoice_date }}" id="date" class="form-control" placeholder="Select Date">
                    </div>
                </div>


                <div class="col-sm-4">
                    <div class="input-group mb-3">
                      <span class="input-group-text">Due Date</span>
                      <input type="date" required name="due_date" value="{{ $data->invoice_due_date }}" class="form-control" placeholder="Select Date">
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="input-group mb-3">
                      <label class="input-group-text" for="currency_select" style="width: auto;">Currency</label>
                      <select class="form-select" name="currency" id="currency_select">
                        @if(\App\Helpers\Currency::options())
                        @foreach(\App\Helpers\Currency::options() as $currency => $sign)
                        <option value="{{ $sign }}" {{ $sign === $data->currency ? 'selected' : '' }}>{{ $currency }}</option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                </div>

                <div class="col-sm-12 my-3">
                    <hr class="mt-2" style="border-color: #aaa;">
                </div>

                <div class="col-sm-6">
                    <div class="form-group required mb-3">
                        <label for="name">Bill From</label>
                        
                        <div class="input-group mb-3">
                          <span class="input-group-text">Name</span>
                          <input name="from_name" type="text" value="{{ $data->from_name }}" class="form-control" placeholder="Bill from name">
                        </div>

                        <div class="input-group mb-3">
                          <span class="input-group-text">Email</span>
                          <input name="from_email" type="email" value="{{ $data->from_email }}" class="form-control" placeholder="Bill from email">
                        </div>

                        <div class="input-group mb-3">
                          <span class="input-group-text">Phone</span>
                          <input name="from_phone" type="text" value="{{ $data->from_phone }}" class="form-control" placeholder="Bill from email">
                        </div>

                        <div class="input-group">
                          <span class="input-group-text">Address</span>
                          <textarea name="from_address" class="form-control note-textarea" placeholder="Bill from address" rows="2">{{ $data->from_address }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group required mb-3">
                        <label for="name">Bill To</label>
                        
                        <div class="input-group mb-3">
                            <select class="select-clients-multiple" name="client" id="client" multiple="multiple" style="width: 100%; display: hidden;">
                                @if($clients)
                                @foreach($clients as $key => $item)

                                @php
                                    $names = [
                                        $item->first_name,
                                        $item->middle_name,
                                        $item->last_name
                                    ];
                                @endphp

                                <option value="{{ $item->id }}" {{ $data->to_client_id === $item->id ? 'selected' : '' }}>
                                    {{ implode(' ', $names) }} - 
                                    {{ $item->emails ? $item->emails[0]->address : 'N/A' }} - 
                                    {{ $item->phones ? $item->phones[0]->number : 'N/A' }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="input-group mb-3">
                          <span class="input-group-text">Name</span>
                          <input name="to_name" type="text" id="to_name_input" value="{{ $data->to_name }}" class="form-control" placeholder="Bill to name">
                        </div>                        

                        <div class="input-group mb-3">
                          <span class="input-group-text">Email</span>
                          <input name="to_email" type="email" id="to_email_input" value="{{ $data->to_email }}" class="form-control" placeholder="Bill to email">
                        </div>

                        <div class="input-group mb-3">
                          <span class="input-group-text">Phone</span>
                          <input name="to_phone" type="text" id="to_phone_input" value="{{ $data->to_phone }}" class="form-control" placeholder="Bill to phone">
                        </div>

                        <div class="input-group">
                          <span class="input-group-text">Address</span>
                          <textarea name="to_address" id="to_address_input" class="form-control note-textarea" placeholder="Bill to address" rows="2">{{ $data->to_address }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 mt-4 mb-0">
                   <div class="form-group required mb-3">
                        <label class="fs-5">Invoice Items</label>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless">
                          <thead>
                            <tr style="border-bottom: 1px solid #e4e4e4;text-transform: uppercase; font-weight: bold;">
                              <th scope="col" style="min-width: 150px;">Item</th>
                              <th scope="col" style="width: 12%;">Price</th>
                              <th scope="col" style="width: 12%;">Qty</th>
                              <th scope="col" style="width: 12%;">Total</th>
                              <th scope="col" class="text-end" style="width: 0%;">Action</th>
                            </tr>
                          </thead>
                          <tbody style="font-weight: 200;" class="invoice-item-container">
                            @if(count($data->items))
                            @foreach($data->items as $key => $value)
                            @php $key = $key+1; @endphp
                            <tr class="invoice-row row-no-{{ $key }}">
                                <td>
                                    <input name="item[{{ $key }}][name]" type="text" class="form-control special-input mt-2" value="{{ $value->item }}" placeholder="Item name" required>
                                    <textarea name="item[{{ $key }}][description]" class="form-control special-input my-2" value="" placeholder="Description" required>{{ $value->description }}</textarea>
                                </td>

                                <td>
                                    <input name="item[{{ $key }}][price]" type="number" value="{{ $value->price }}"  min="0" step="0.01" class="form-control special-input mt-2 item-{{ $key }}-price" placeholder="0.00" required style="min-width: 100px">
                                </td>

                                <td>
                                    <input name="item[{{ $key }}][quantity]" type="number" value="{{ $value->quantity }}"  min="0" class="form-control special-input mt-2 item-{{ $key }}-quantity" placeholder="--" style="min-width: 100px">
                                </td>

                                <td class="pt-4">
                                    <span class="item-currency-sign">$</span>
                                    <span class="item-{{ $key }}-total">{{ $value->total }}</span>
                                </td>

                                <td class="text-end">
                                    <button type="button" class='btn btn-danger btn-sm mt-2 px-2 py-0 remove-invoice-item-btn'>
                                        <i class="icon ion-ios-trash io-18"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                          </tbody>
                        </table>                        
                    </div>
                </div>

                <div class="col-xl-7 mb-4">
                    <button type="button" class="btn btn-dark btn-sm fs-6" id="add-invoice-item-btn" data-count="{{ count($data->items) > 0 ? count($data->items) : 0  }}">Add Invoice Item</button>
                </div>

                <div class="col-xl-5 mb-4">
                    <div class="d-flex flex-row justify-content-between">
                        <h6 class="fw-semibold mb-0 fs-16">Subtotal</h6>
                        <h6 class="pt-0 fw-semibold">
                            <span class="item-currency-sign fs-16">$</span>
                            <span class="item-1-total fs-16" id="subtotal-output">{{ $data->subtotal }}</span>
                        </h6>
                    </div>

                    <div class="d-flex flex-row justify-content-between align-items-center mt-2">
                        <h6 class="fw-semibold mb-0">Tax</h6>

                        <input name="tax" id="tax-input" type="number" value="{{ $data->tax }}"  min="0" step="0.01" class="form-control special-input mt-0 fs-6 p-0 px-1" placeholder="0.00" style="width: 100%;max-width: 100px;text-align: right;">
                    </div>

                    <div class="d-flex flex-row justify-content-between align-items-center mt-2">
                        <h6 class="fw-semibold mb-0">Discount</h6>

                        <input name="discount" id="discount-input" type="number" value="{{ $data->discount }}"  min="0" step="0.01" class="form-control special-input mt-0 fs-6 p-0 px-1" placeholder="0.00" style="width: 100%;max-width: 100px;text-align: right;">
                    </div>

                    <div class="d-flex flex-row justify-content-between align-items-center mt-3 pt-2" style="border-top: 1px solid #e4e4e4;">
                        <h6 class="fw-semibold mb-0 fs-18">Total</h6>
                        <h6 class="pt-0 fw-semibold">
                            <span class="item-currency-sign fs-18">$</span>
                            <span class="item-1-total fs-18" id="total-output">{{ $data->grand_total }}</span>
                        </h6>
                    </div>
                </div>

                <!-- notes -->
                <div class="form-group col-sm-12 required mb-3">
                    <label for="notes">Notes</label>
                    <textarea type="text" name="notes" id="notes" placeholder="Thanks for your business" class="form-control" required>{{ $data->note }}</textarea>
                </div>

                <!-- button -->
                <div class="form-group col-12 mb-5">
                    <button type="submit" class="btn btn-success">Save Invoice</button>
                    <button type="reset" class="btn btn-secondary me-3">Reset</button>

                    @if (Auth::user()->can('invoice.download'))
                    <a href="{{ route('admin.invoice.download', ['data' => $data->id]) }}" class="btn btn-secondary">Download Invoice</a>
                    @endif
                </div>
            </div>
        </form>        
    </div>
</div>
<!-- body content end -->

@endsection


@push('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('assets/invoice/style.css') }}" rel="stylesheet" />
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

    <script type="text/javascript">
        let sys_currency_sign = '£';

        $(".select-clients-multiple").select2({
            tags: false,
            placeholder: "Search and select client",
            // selectOnClose: true,
            closeOnSelect: true,
            // dropdownCssClass: "increasedzindexclass",
            maximumSelectionLength: 1,
            language: {
                maximumSelected: function (e) {
                    return 'Maxium one client can be selected';
                }
            }
        }).on('select2:select', function(e) {
            onSelectClient();
        }).on('select2:unselect', function(e) {
            $(this).select2('close');
        });

        handleCurrencySign();

        $('#currency_select').on('change', function(e) {
            handleCurrencySign();
        });

        function handleCurrencySign() {
            var currency_sign = $('#currency_select').val();

            if(currency_sign) {
                $('.item-currency-sign').html(currency_sign);
                sys_currency_sign = currency_sign;
            }
        }

        function onSelectClient() {
            var client_id = $('#client').val();
                client_id = client_id ? client_id[0] : null;

            $.ajax({
                type: 'GET',
                url: '{{ route('admin.invoice.ajax.cleintData') }}',
                data: { 
                    client_id: client_id
                },
                dataType: 'json',
                success: function (response) {
                    if(response) {
                        var name = '';
                        if(response.first_name) { name += response.first_name }
                        if(response.middle_name) { name += ' '+response.middle_name }
                        if(response.last_name) { name += ' '+response.last_name }
                        
                        var address = '';
                        if(response.address_l1) { address += response.address_l1 }
                        if(response.address_l2) { address += ', '+response.address_l2 }
                        
                        
                        var email = '';
                        if(response.emails) { email = response.emails[0].address; }

                        var phone = '';
                        if(response.phones) { phone = response.phones[0].number; }

                        $('#to_name_input').val(name);
                        $('#to_address_input').html(address);
                        $('#to_phone_input').val(phone);
                        $('#to_email_input').val(email);
                    }
                }
            });
        }

        // --------------------
        // document.querySelector('#date').value = currentDate();

        $('.invoice-item-container').on('input', 'input[type="number"]', function(e) {
            handleCalculation();
        });

        $('#discount-input, #tax-input').on('input', function(e) {
            handleCalculation();
        });

        function handleCalculation() {
            var subtotal = 0;

            $('.invoice-item-container > tr').each(function(index, tr) {
                var classes = $(tr).attr("class");
                var classArr = classes.split('row-no-');
                var row_no = parseInt(classArr[1]);

                var quantity = $(tr).find('.item-'+row_no+'-quantity').val();
                var price = $(tr).find('.item-'+row_no+'-price').val();

                if(isNaN(parseInt(quantity))) {
                    quantity = 1;
                }

                if(parseInt(quantity) === 0) {
                    quantity = 1;
                }

                var item_total = parseInt(quantity ? quantity : 0) * parseFloat(price ? price : 0);
                
                subtotal += item_total;
                // item_total = item_total ? item_total.toFixed(2) : 0;
                $(tr).find('.item-'+row_no+'-total').html(item_total ? item_total.toFixed(2) : '0.00');
            });

            var tax_value = $('#tax-input').val();
                tax_value = tax_value ? parseFloat(tax_value).toFixed(2) : 0;

            var discount = $('#discount-input').val();
                discount = discount ? parseFloat(discount).toFixed(2) : 0;

            // show subtotal
            $('#subtotal-output').html(subtotal ? parseFloat(subtotal).toFixed(2) : '0.00');
            // show subtotal

            var total = subtotal;

            if(discount) { total = total - parseFloat(discount); }
            if(tax_value) { total = total + parseFloat(tax_value); }

            // show subtotal
            $('#total-output').html(total ? parseFloat(total).toFixed(2) : '0.00');
            // show subtotal
        }

        // add item
        $('#add-invoice-item-btn').on('click', function(e) {
            var count = parseInt($(this).attr('data-count'));
            var key = count + 1;

            $('.invoice-item-container').append('<tr class="invoice-row row-no-'+key+'"> <td> <input name="item['+key+'][name]" type="text" class="form-control special-input mt-2" value="" placeholder="Item name" required> <textarea name="item['+key+'][description]" class="form-control special-input my-2" value="" placeholder="Description" required></textarea> </td><td> <input name="item['+key+'][price]" type="number" value="" min="0" step="0.01" class="form-control special-input mt-2 item-'+key+'-price" required placeholder="0.00" style="min-width: 100px"> </td><td> <input name="item['+key+'][quantity]" type="number" value="" min="1" step="1" class="form-control special-input mt-2 item-'+key+'-quantity" placeholder="--" style="min-width: 100px"> </td><td class="pt-4"> <span class="item-currency-sign">'+sys_currency_sign+'</span> <span class="item-'+key+'-total">0.00</span> </td><td class="text-end"> <button type="button" class="btn btn-danger btn-sm mt-2 px-2 py-0 remove-invoice-item-btn"> <i class="icon ion-ios-trash io-18"></i> </button> </td></tr>');            

            $(this).attr('data-count', count+1);
        });

        $('.invoice-item-container').on('click','.invoice-row .remove-invoice-item-btn',function(e) {
            e.preventDefault();
            $(this).closest('.invoice-row').remove();

            handleCalculation();
        });
    </script>
@endpush