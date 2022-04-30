require('./bootstrap');
require('datatables.net-bs5');
require('datatables.net-responsive-bs5');

$(function () {
    $('#btn-close-succes').on('click', function () {
        $('#btn-close-loading').trigger('click')
    })
    $('#btn-close-error').on('click', function () {
        $('#btn-close-loading').trigger('click')
    })
})
