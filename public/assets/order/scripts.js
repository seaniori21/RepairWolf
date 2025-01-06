// calculations
function fetchRowNo(rowClass) {
	var response = null;

    // Extract the row number from the class
    var classes = rowClass.split(' ');
    var rowNumberClass = classes.find(function(className) {
        return className.startsWith('row-no-');
    });

    if (rowNumberClass) {
        var rowNumber = rowNumberClass.replace('row-no-', '');
        response = rowNumber;
    }

    return response;
}

$('.product-item-container, .payment-item-container, .product-table-summery').on('change keyup','input[type="number"]',function(e) {
	handleProductItemCalculations();
});

function handleProductItemCalculations() {
	let amountOptionsObj = {
		minimumFractionDigits: 2,
		maximumFractionDigits: 2
	    // style: 'currency',
	    // currency: 'USD'
	};

	let taxValue = $('#tax-input').val();
	    taxValue = taxValue ? parseFloat(taxValue) : 0;

	let convenienceFee = $('#convenience_fee-input').val();
	    convenienceFee = convenienceFee ? parseFloat(convenienceFee) : 0;

	let discount = $('#discount-input').val();
	    discount = discount ? parseFloat(discount) : 0;

	let totalAmount = 0;
	let paymentAmount = 0;
	let dueAmount = 0;

	// console.log({'taxValue': taxValue, 'convenienceFee': convenienceFee, 'discount': discount});

	// payment item row
	$('tbody.payment-item-container tr.payment-row').each(function( index, item ) {
		var itemRowClass = $(this).attr('class');
		var itemRowNo = fetchRowNo(itemRowClass);

		if (itemRowNo) {
			var itemPaymentAmount = $(this).find(`input[name="payment[${itemRowNo}][amount]"]`).val();
			if (itemPaymentAmount) { paymentAmount += parseFloat(itemPaymentAmount); }
		}
	});
	// payment item row

	// product item row
	$('tbody.product-item-container tr.product-row').each(function( index, item ) {
		var itemRowClass = $(this).attr('class');
		var itemRowNo = fetchRowNo(itemRowClass);

		if (itemRowNo) {
			var itemQty = $(this).find(`input[name="product[${itemRowNo}][quantity]"]`).val();
			var itemBasePrice = $(this).find(`input[name="product[${itemRowNo}][base_price]"]`).val();
			var itemListPrice = $(this).find(`input[name="product[${itemRowNo}][list_price]"]`).val();
			var working_price = itemBasePrice;

			if (itemListPrice && itemListPrice > 0) { working_price = itemListPrice; }

			if (itemQty && working_price) {
				var itemTotal = parseInt(itemQty) * parseFloat(working_price);
					totalAmount += itemTotal;

				$(this).find(`.product-${itemRowNo}-total`).text(itemTotal.toLocaleString('en-US', amountOptionsObj));
			}
		}
	});
	// product item row

	// calcs
	totalAmount += (taxValue / 100) * totalAmount;
	totalAmount += convenienceFee;
	totalAmount -= discount;

	dueAmount = totalAmount - paymentAmount;
	// calcs


	// console.log(paymentAmount,totalAmount,dueAmount)

	// output
	$('.product-table-summery').find('#subtotal-output').text(totalAmount.toLocaleString('en-US', amountOptionsObj));
	$('.product-table-summery').find('#paid-amount-output').text(paymentAmount.toLocaleString('en-US', amountOptionsObj));
	$('.product-table-summery').find('#payment-due-output').text(dueAmount.toLocaleString('en-US', amountOptionsObj));
	// output
}
// calculations

// vehicle
const fetchSelectedVehicleDataUrl = $('.select-vehicle').attr('data-details-fetch-url');

$('.select-vehicle').on('change', function(e) {
	e.preventDefault();
	let dataId = $(this).val();

	$.ajax({
	    url: fetchSelectedVehicleDataUrl,
	    method:'GET',
	    data:{'dataId': dataId},
	    dataType:'json',
	    success:function(response)
	    {
	    	$('#vin').val(response.vin);
	    	$('#license_plate').val(response.license_plate);
	    	$('#color').val(response.color);
	    }
	});
});
// vehicle

// products
const productSectionLoader = $('#product-loader');
const fetchSelectedProductDataUrl = $('.select-products').attr('data-details-fetch-url');

$('.select-customer').on('select2:select', function(e) {
	$('.select-vehicle').val(null);

	var currentCustomerId = $(this).val();
	fetchSelect2DataByAjax({ class: 'select-vehicle', placeholder: 'Select a vehicle', url: vehicleDataUrl, additional: currentCustomerId });
});
$('.select-products').on('select2:select', function(e) {
	e.preventDefault();
	productSectionLoader.show();
	
	let dataId = $(this).val();

	$.ajax({
	    url: fetchSelectedProductDataUrl,
	    method:'GET',
	    data:{'dataId': dataId},
	    dataType:'json',
	    success:function(response)
	    {
	    	$('.select-products').val(null).trigger('change');
	    	// console.log(response)

	    	if (response) {
	    		appendPrductInTable(response);
	    	}
	    }
	});
});

function appendPrductInTable(data) {
	var rCount = $('tbody.product-item-container tr.product-row').length;
	var modelCount = $('.scheduled-notification-container div.scheduled-notification-modal').length;
	
	if ($(`tbody.product-item-container tr.product-row.row-no-${rCount}`).length) { rCount = rCount+1; }
	if ($(`tbody.product-item-container tr.product-row.row-no-${rCount}`).length) { rCount = rCount+1; }
	if ($(`tbody.product-item-container tr.product-row.row-no-${rCount}`).length) { rCount = rCount+1; }

	if ($(`.scheduled-notification-container div#notification-modal-id-${modelCount}`).length) { modelCount = modelCount+1; }
	if ($(`.scheduled-notification-container div#notification-modal-id-${modelCount}`).length) { modelCount = modelCount+1; }
	if ($(`.scheduled-notification-container div#notification-modal-id-${modelCount}`).length) { modelCount = modelCount+1; }

    var row_el = `<tr class="product-row row-no-${rCount}">
        <td>
        	<input type="hidden" name="product[${rCount}][id]" value="${data.id}">
            <input required name="product[${rCount}][quantity]" type="number" value="1" min="1" class="form-control special-input my-1 px-1 product-${rCount}-quantity" placeholder="" style="width: 40px">
        </td>

        <td class="pt-3"><span class="product-${rCount}-key" title="${data.identification_code}">${(data.identification_code).length > 14 ? (data.identification_code).substring(0,14)+'...' : (data.identification_code) }</span></td>
        <td class="pt-3"><span class="product-${rCount}-upc" title="${data.upc}">${ data.upc ? (data.upc).length > 14 ? (data.upc).substring(0,14)+'...' : (data.upc) : '--' }</span></td>
        <td class="pt-3"><span class="product-${rCount}-name" title="${data.name}">${(data.name).length > 14 ? (data.name).substring(0,14)+'...' : (data.name) }</span></td>
        <td class="pt-3"><span class="product-${rCount}-manufacturer" title="${data.manufacturer}">${(data.manufacturer).length > 14 ? (data.manufacturer).substring(0,14)+'...' : (data.manufacturer) }</span></td>
        <td class="pt-3"><span class="product-${rCount}-type text-capitalize">${data.type}</span></td>

        <td>
        	<button type="button" class="btn btn-sm btn-secondary my-1 px-2" data-bs-toggle="modal" data-bs-target="#notification-modal-id-${rCount}" data-modal-id="${rCount}">Setup</button>
        </td>

        <td>
            <input name="product[${rCount}][base_price]" type="number" value="${data.base_price}"  min="0" step="0.01" class="form-control special-input my-1 px-1 product-${rCount}-basePrice" placeholder="0.00" required style="width: 90px; padding: 4px;">
        </td>

        <td>
            <input name="product[${rCount}][list_price]" type="number" value="${data.list_price}"  min="0" step="0.01" class="form-control special-input my-1 px-1 product-${rCount}-listPrice" placeholder="0.00" style="width: 90px; padding: 4px;">
        </td>

        <td class="pt-3 text-end">
            <span class="product-currency-sign">$</span>
            <span class="product-${rCount}-total">0.00</span>
        </td>

        <td class="text-end">
            <button type="button" class='btn btn-danger btn-sm my-1 px-2 py-0 remove-product-item-btn'>
                <i class="icon ion-ios-trash io-18"></i>
            </button>
        </td>
    </tr>`;

    var modal_el = `<div class="modal fade scheduled-notification-modal" id="notification-modal-id-${modelCount}" data-modal-id="${modelCount}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5">Setup Scheduled Notification</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="scheduled_notification[${modelCount}][notification_uuid]" value="" />
            <input type="hidden" name="scheduled_notification[${modelCount}][product_id]" value="${data.id}" />
            <input type="hidden" name="scheduled_notification[${modelCount}][status]" value="inactive" />

            <div class="input-group mb-3">
              <span class="input-group-text">Date</span>
              <input type="datetime-local" name="scheduled_notification[${modelCount}][date]" value="" class="form-control notify-date-input" placeholder="Select Date">
            </div>

            <div class="input-group">
              <span class="input-group-text">Text&nbsp;</span>
              <textarea name="scheduled_notification[${modelCount}][text]" class="form-control notify-text-input resize-vertical special-input xmy-1 comment--text" aria-label="With textarea"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary save-scheduled-notification-btn">Save</button>
          </div>
        </div>
      </div>
    </div>`;

    $('tbody.product-item-container').append(row_el);
    $('.scheduled-notification-container').append(modal_el);

    $('tbody.product-item-container .empty-product-row').remove();

    productSectionLoader.hide();

    handleProductItemCalculations();
    initScheduledNotifyDates();
}

$('.product-item-container').on('click','.product-row .remove-product-item-btn',function(e) {
    e.preventDefault();
    
    $(this).closest('.product-row').remove();
    handleProductItemCalculations();

    var tableRowCount = $('tbody.product-item-container tr.product-row').length;
    var modalId = $(this).closest('.product-row').find('button[data-bs-toggle="modal"]').attr('data-modal-id');

    if (tableRowCount <= 0) {
    	var empty_el = `<tr class="empty-product-row">
            <td class="text-center" colspan="11">No product added yet</td>
        </tr>`;

        $('tbody.product-item-container').append(empty_el);
    }

    if (modalId) { $(`.scheduled-notification-container .scheduled-notification-modal[data-modal-id="${modalId}"]`).remove(); }
});
// products

// payment data
$('#add-payment-item-btn').on('click', function(e) {
	var data_payment_type_url = $(this).attr('data-payment-type-url');
	var key = $('tbody.payment-item-container tr.payment-row').length;

	if ($(`tbody.payment-item-container tr.payment-row.row-no-${key}`).length) { key = key+1; }
	if ($(`tbody.payment-item-container tr.payment-row.row-no-${key}`).length) { key = key+1; }
	if ($(`tbody.payment-item-container tr.payment-row.row-no-${key}`).length) { key = key+1; }

	var append_el = `<tr class="payment-row row-no-${key}">
        <td>
            <div class="special-input my-1">
                <select required class="select-payment_type payment-${key}-payment_type" name="payment[${key}][payment_type]" data-url="${data_payment_type_url}" style="width: 100%;">
                    <option selected disabled>Select</option>
                </select>
            </div>
        </td>

        <td>
            <input required name="payment[${key}][amount]" type="number" value=""  min="0" class="form-control special-input my-1 payment-${key}-amount" placeholder="0.00" style="min-width: 10px">
        </td>

        <td>
            <input name="payment[${key}][auth_approval_code]" type="text" value="" class="form-control special-input my-1 payment-${key}-auth_approval_code" placeholder="" style="min-width: 10px">
        </td>

        <td>
            <input name="payment[${key}][credit_card_number]" type="text" value="" class="form-control special-input my-1 payment-${key}-credit_card_number" placeholder="" style="min-width: 10px">
        </td>

        <td>
            <input name="payment[${key}][expiration_date]" type="date" class="form-control special-input my-1 payment-${key}-expiration_date" value="" placeholder="">
        </td>

        <td>
            <input name="payment[${key}][security_code]" type="text" class="form-control special-input my-1 payment-${key}-security_code" value="" placeholder="">
        </td>

        <td class="text-end">
            <button type="button" class='btn btn-danger btn-sm my-1 px-2 py-0 remove-payment-item-btn'>
                <i class="icon ion-ios-trash io-18"></i>
            </button>
        </td>
    </tr>`;

    $('.payment-item-container').append(append_el);
    $('tbody.payment-item-container .empty-payment-row').remove();

    fetchSelect2DataByAjax({ class: 'select-payment_type', placeholder: 'Select', url: data_payment_type_url });
});

$('.payment-item-container').on('click','.payment-row .remove-payment-item-btn',function(e) {
    e.preventDefault();

    $(this).closest('.payment-row').remove();
	handleProductItemCalculations();

    var tableRowCount = $('tbody.payment-item-container tr').length;

    if (tableRowCount <= 0) {
    	var empty_el = `<tr class="empty-payment-row">
            <td class="text-center" colspan="7">No payment added yet</td>
        </tr>`;

        $('tbody.payment-item-container').append(empty_el);
    }
});
// payment data

// comment data
$('#add-comment-item-btn').on('click', function(e) {
	var key = $('tbody.comment-item-container tr.comment-row').length;
	
	if ($(`tbody.comment-item-container tr.comment-row.row-no-${key}`).length) { key = key+1; }
	if ($(`tbody.comment-item-container tr.comment-row.row-no-${key}`).length) { key = key+1; }
	if ($(`tbody.comment-item-container tr.comment-row.row-no-${key}`).length) { key = key+1; }

	var append_el = `<tr class="comment-row row-no-${key}">
        <td>
            <textarea name="comment[${key}][text]" class="form-control resize-vertical special-input my-1 comment-${key}-text" value="" placeholder="Type Order Comment" required rows="1"></textarea>
        </td>

        <td>
            <input name="comment[${key}][file]" type="file" value="" class="form-control special-input my-1 comment-${key}-file" placeholder="" style="min-width: 10px">
        </td>

        <td class="text-end">
            <button type="button" class='btn btn-danger btn-sm my-1 px-2 py-0 remove-comment-item-btn'>
                <i class="icon ion-ios-trash io-18"></i>
            </button>
        </td>
    </tr>`;

    $('.comment-item-container').append(append_el);
    $('tbody.comment-item-container .empty-comment-row').remove();
});

$('.comment-item-container').on('click','.comment-row .remove-comment-item-btn',function(e) {
    e.preventDefault();
    $(this).closest('.comment-row').remove();

    var tableRowCount = $('tbody.comment-item-container tr.comment-row').length;

    if (tableRowCount <= 0) {
    	var empty_el = `<tr class="empty-comment-row">
            <td class="text-center" colspan="3">No comment added yet</td>
        </tr>`;

        $('tbody.comment-item-container').append(empty_el);
    }
});
// comment data


// scheduled notification
$(document).on('click', 'input[type="datetime-local"]', function() { this.showPicker(); });

initScheduledNotifyDates();
function initScheduledNotifyDates() {
	var currentDate = new Date();
		// currentDate.setDate(currentDate.getDate() + 1);
		currentDate.setDate(currentDate.getDate());
	var minDate = currentDate.toISOString().slice(0, 16);
	// $('.scheduled-notification-container input[type="datetime-local"]').attr('min', minDate).trigger('change');	
}


$('.scheduled-notification-container').on('change keyup input','input, textarea',function(e) {
	$(this).removeClass('invalid-input');
});

$('.scheduled-notification-container').on('click','.scheduled-notification-modal .save-scheduled-notification-btn',function(e) {
	e.preventDefault();

	var modalId = $(this).closest('.scheduled-notification-modal').attr('data-modal-id');
	var notifyDate = $(this).closest('.scheduled-notification-modal').find('.notify-date-input').val();
	var notifyText = $(this).closest('.scheduled-notification-modal').find('.notify-text-input').val();

	if (!notifyDate) { $(this).closest('.scheduled-notification-modal').find('.notify-date-input').addClass('invalid-input'); }
	else { 
		$(this).closest('.scheduled-notification-modal').find('.notify-date-input').removeClass('invalid-input');
		$('.product-item-container').find(`button[data-modal-id="${modalId}"]`).removeClass('btn-success').addClass('btn-secondary');
	}

	if (!notifyText) { $(this).closest('.scheduled-notification-modal').find('.notify-text-input').addClass('invalid-input'); }
	else { 
		$(this).closest('.scheduled-notification-modal').find('.notify-text-input').removeClass('invalid-input');
		$('.product-item-container').find(`button[data-modal-id="${modalId}"]`).removeClass('btn-success').addClass('btn-secondary');
	}

	if (notifyDate && notifyText) { 
		$(this).closest('.scheduled-notification-modal').modal('hide');
		$(this).closest('.scheduled-notification-modal').find(`input[name="scheduled_notification[${modalId}][status]"]`).attr('value','active').trigger('change');
		$('.product-item-container').find(`button[data-modal-id="${modalId}"]`).removeClass('btn-secondary').addClass('btn-success');
	} else {
		$(this).closest(`.scheduled-notification-modal input[name="scheduled_notification[${modalId}][status]"]`).attr('value','inactive').trigger('change');
	}
});
// scheduled notification

// status
// status-check-confirm
$('select.status-check-confirm').on('change', function(e) {
	e.preventDefault();
	var currentSelectInput = this;

	if ($(currentSelectInput).val() === 'closed') {
	    Swal.fire({
	        title: 'Are you sure?',
	        text: "Once its closed only superadmin can reopen it",
	        icon: 'question',
	        showCancelButton: !0,
	        confirmButtonColor: '#3085d6',
	        cancelButtonColor: '#d33',
	        confirmButtonText: 'Yes'
	    }).then((result) => {
	        if (!result.isConfirmed) {
	        	$(currentSelectInput).val('open');
	        }
	    })
	}
})
// status