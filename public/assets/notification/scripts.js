// customer select
const customerDataUrl = $('.select-customer').attr('data-url');
const customerDataSelected = $('.select-customer').attr('data-active');

$('.select-customer').select2({
    minimumResultsForSearch: 2, /* search disabled */
    tags: false,
    placeholder: 'Select Customer',
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

// via select
$('.select-via').select2({
    // minimumResultsForSearch: 2,
    tags: false,
    closeOnSelect: false,
    placeholder: 'Select Type',
    // dropdownCssClass: "font_13",
    width: '100%',
});
// via select