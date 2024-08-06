$(document).ready(() => {
    $('.multi-select').select2({
        width: 'resolve',
        selectionCssClass: ':all:', // The helper :all: can be used to add all CSS classes present on the original <select> element.
    });
});
