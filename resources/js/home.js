$(function () {
    /* Evit write more two decimals */
    $('.newtExchangeRates').evitWriteTextCost();
})

$(function () {
    $('#updatetChange').on('click', function () {
        var data = $('.form-update-exchangeRates')
        var serializeArray = data.serializeArray()
        $.ajax({
            headers: { 'X-CSRF-TOKEN': serializeArray[0].value },
            type: 'PUT',
            url: '/update/tchange',
            data: { 'value': serializeArray[1].value },
            dataType: 'json',
            beforeSend: function () {
                $('#btn-close').trigger('click')
                $('#btn-succes-loading').trigger('click')
            }
        }).then(function (data) {
            $('#btn-succes').trigger('click')
            $('#valor-cambio').text(data.value)
            $('.fst-italic.fw-bolder').text('T.C: ' + data.value)
        }).catch(function (data) {
            $('#btn-succes-error').trigger('click')
            console.log(data.responseJSON)
        }).always(function () {
            $('#modal-succes-loading').modal('toggle')
            $('#btn-close-loading ,.btn-close-loading').trigger('click')
        })
    })
})
