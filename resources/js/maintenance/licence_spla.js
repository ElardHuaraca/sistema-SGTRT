var action = null
var row = null
var id = null

$(function () {
    _token = $('input[name="_token"]').val()
    /* Init button action on change state */
    $('#table-resources-it').updateState('/maintenance/licence/spla/update/status/', _token, '.btn-status-licence-spla')

    /* Prepare input for only cost */
    $('input[name="cost"]').evitWriteTextCost()

    /* Create licence_spla and Update licence_spla */
    $('#btn-update-create-licence_spla').on('click', function () { $('#btn-sumbit-licence_spla').trigger('click') })

    $('#modalCreateEditLicenceSpla').on('show.bs.modal', function (event) {
        var relatedTarget = $(event.relatedTarget)
        row = relatedTarget.hasClass('btn-edit-licence-spla') ? $(relatedTarget).parents('tr')[0] : null
        id = relatedTarget.hasClass('btn-edit-licence-spla') ? relatedTarget.val() : null
        action = relatedTarget.hasClass('btn-edit-licence-spla') ? 'update' : 'create'

        if (relatedTarget.hasClass('btn-edit-licence-spla')) $('#staticBackdropLabel').text('Editar Licencia SPLA')
        else $('#staticBackdropLabel').text('Crear Licencia SPLA')

        if (row !== null) {
            var licence_spla = licences.filter(x => `${x.idspla}` === relatedTarget.val())[0]
            $('.form-update-create-licence_spla').find('input,select').each((_index, element) => {
                $(element).val(licence_spla[$(element).attr('name')])
            })
        }
    })

    $('#modalCreateEditLicenceSpla').on('hidden.bs.modal', function () {
        if (row !== null) {
            $('.form-update-create-licence_spla').trigger('reset')
        }
    })

    $('.form-update-create-licence_spla').on('submit', function (e) {
        e.preventDefault()
        var form = $(this)
        const data = form.serializeArray()
        $.ajax({
            headers: { 'X-CSRF-TOKEN': _token },
            url: action === 'create' ? '/maintenance/licence/spla/create' : '/maintenance/licence/spla/update/' + id,
            type: action === 'create' ? 'POST' : 'PUT',
            data: data,
            dataType: 'json',
            beforeSend: function () {
                $('#btn-succes-loading').trigger('click')
            }
        }).then(function (response) {
            if (action === 'create') {
                var row_data = $.dataTableInit.row(0).data() === undefined ? Array() : $.dataTableInit.row(0).data()
                row_data[0] = $.dataTableInit.data().length + 1
                row_data[1] = response.code
                row_data[2] = response.name
                row_data[3] = response.type
                row_data[4] = `$ ${response.cost}`
                row_data[5] = `<button class="btn btn-warning btn-edit-licence-spla" data-bs-toggle="modal"
                                data-bs-target="#modalCreateEditLicenceSpla" value="${response.idspla}">Editar</button>`
                row_data[6] = ` <button class="btn btn-success btn-status-licence-spla"
                                value="${response.idspla}">Activo</button>`
                licences.push(response)
                $.dataTableInit.row.add(row_data).draw()
            }
            else {
                var row_data = $.dataTableInit.row(row).data()
                row_data[1] = response.code
                row_data[2] = response.name
                row_data[3] = response.type
                row_data[4] = `$ ${response.cost}`
                row_data[5] = `<button class="btn btn-warning btn-edit-licence-spla" data-bs-toggle="modal"
                                data-bs-target="#modalCreateEditLicenceSpla" value="${response.idspla}">Editar</button>`
                row_data[6] = ` <button class="btn btn-success btn-status-licence-spla"
                                value="${response.idspla}">Activo</button>`
                $.dataTableInit.row(row).data(row_data).draw(false)
            }
            $('#btn-succes').trigger('click')
            action = null
            row = null
            id = null
            form.trigger('reset')
        }).catch(function (error) {
            $('#btn-close-loading').trigger('click')
            $('#btn-succes-error').trigger('click')
            console.log(error)
        }).always(function () {
            setTimeout(function () {
                $('#btn-close-loading, .btn-close-loading').trigger('click')
                $('#modal-succes-loading').hide()
            }, 50)
        })
    })
})
