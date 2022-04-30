$(function () {
    /* Evit write more two decimals */
    $('.newtChange').on('keyup', function () {
        var value = $(this).val();
        var newValue = value.replace(/(\.\d{2})\d+/g, '$1');
        $(this).val(newValue);
    })
})

$(function () {
    $('#updatetChange').on('click', function () {
        var data = $('.form-update-tchange')
        var serializeArray = data.serializeArray()
        $.ajax({
            headers: { 'X-CSRF-TOKEN': serializeArray[0].value },
            type: 'PUT',
            url: '/update/tchange',
            data: { 'valor': serializeArray[1].value },
            dataType: 'json',
            beforeSend: function () {
                $('#btn-close').trigger('click')
                $('#btn-succes-loading').trigger('click')
            },
            success: function (data) {
                $('#btn-close-loading').trigger('click')
                $('#btn-succes').trigger('click')
                $('#valor-cambio').text(data.valor)
            },
            error: function (data) {
                $('#btn-close-loading').trigger('click')
                $('#btn-succes-error').trigger('click')
                console.log(data.responseJSON)
            }
        })
    })

})
