// payment type
const paymentTypeDataUrl = $('.select-payment_type').attr('data-url');
// const paymentTypeDataSelected = $('.select-payment_type').attr('data-active');

$('.select-payment_type').select2({
    minimumResultsForSearch: 2, /* search disabled */
    tags: false,
    placeholder: 'Select Payment Type',
    // dropdownCssClass: "font_13",
    width: '100%',
    ajax: {
        url: paymentTypeDataUrl,
        method:'GET',
        data: function (params) {
          return {
            // active: paymentTypeDataSelected,
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
// payment type

// order select
const orderDataUrl = $('.select-order').attr('data-url');
const orderDataSelected = $('.select-order').attr('data-active');

$('.select-order').select2({
    minimumResultsForSearch: 2, /* search disabled */
    tags: false,
    placeholder: 'Select Order No',
    // dropdownCssClass: "font_13",
    width: '100%',
    ajax: {
        url: orderDataUrl,
        method:'GET',
        data: function (params) {
          return {
            // active: orderDataSelected,
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
// order select









// $(".select-clients").select2({
//     tags: false,
//     placeholder: "Search and select client",
//     // selectOnClose: true,
//     closeOnSelect: true,
//     // dropdownCssClass: "increasedzindexclass",
//     // maximumSelectionLength: 1,
//     language: {
//         maximumSelected: function (e) {
//             return 'Maxium one client can be selected';
//         }
//     }
// }).on('select2:select', function(e) {
//     // onSelectClient();
// }).on('select2:unselect', function(e) {
//     $(this).select2('close');
// });