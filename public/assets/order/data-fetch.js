function fetchSelect2DataByAjax(data) {
    /* data = { class: className, placeholder: string, url: url } */

    const select2Template = (state) => {
        if (state.loading) { return state.text; }

        if (state.badge) {
            switch(state.badge) {
                case 'part':
                    var badge_class = 'badge text-bg-secondary';
                    break;
                case 'service':
                    var badge_class = 'badge text-bg-success';
                    break;
                case 'labor':
                    var badge_class = 'badge text-bg-primary';
                    break;
            }

            var el = $(`<div class="d-flex align-items-center justify-content-start gap-2">
                <small class="${badge_class} rounded-pill text-capitalize" style="width: 55px;">${state.badge}</small>
                ${state.text}
            </div>`);
        } else {
            var el = $(`<span>${state.text}</span>`);
        }

        return el;
    };

    $('.'+data.class).select2({
        minimumResultsForSearch: 2, /* search disabled */
        tags: false,
        placeholder: data.placeholder,
        width: '100%',
        ajax: {
            url: data.url,
            delay: 250,
            method:'GET',
            data: function (params) {
              return {
                additional: data.additional,
                search: params.term,
                type: 'public'
              };
            },
            dataType: 'json',
            processResults: function (response) {
                // console.log(response)
                return {
                    results: response ? response : [],
                    pagination: {
                        "more": false
                    }
                };
            },
        },
        templateResult: select2Template
    });
}

// customer select
const customerDataUrl = $('.select-customer').attr('data-url');
fetchSelect2DataByAjax({ class: 'select-customer', placeholder: 'Select a customer', url: customerDataUrl });
// customer select

// cashier select
const cashierDataUrl = $('.select-cashier').attr('data-url');
fetchSelect2DataByAjax({ class: 'select-cashier', placeholder: 'Select a cashier', url: cashierDataUrl });
// cashier select

// vehicle select
var currentCustomerId = $('.select-customer').val();
const vehicleDataUrl = $('.select-vehicle').attr('data-url');
fetchSelect2DataByAjax({ class: 'select-vehicle', placeholder: 'Select a vehicle', url: vehicleDataUrl });
// vehicle select

// service-person select
const servicePersonDataUrl = $('.select-service_person').attr('data-url');
fetchSelect2DataByAjax({ class: 'select-service_person', placeholder: 'Select a service person', url: servicePersonDataUrl });
// service-person select

// product select
const productDataUrl = $('.select-products').attr('data-url');
fetchSelect2DataByAjax({ class: 'select-products', placeholder: 'Select a product to add', url: productDataUrl });
// product select

// payment type select
const paymentTypeDataUrl = $('#add-payment-item-btn').attr('data-payment-type-url');
fetchSelect2DataByAjax({ class: 'select-payment_type', placeholder: 'Select', url: paymentTypeDataUrl });
// payment type select