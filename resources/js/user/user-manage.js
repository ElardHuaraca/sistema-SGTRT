$(function() {
    $('#table-resources-it').on('click', '.state-user-active, .state-user-inactive', function(e) {
        let btn = $(this)
        let id = btn.val()
        let state = $(this).hasClass('btn-success') ? false : true
        var data = $('.form-update-user')
        var serializeArray = data.serializeArray()
        var token = serializeArray[0].value
        $.ajax({
            headers: { 'X-CSRF-TOKEN': token },
            type: 'PUT',
            url: '/users/update/state/' + id,
            data: { 'state': state },
            dataType: 'json',
            beforeSend: function() {
                $('#btn-succes-loading').trigger('click')
            }
        }).then(function(data) {
            if (data.state === "true") {
                btn.removeClass('btn-danger')
                btn.addClass('btn-success')
                btn.text('Activo')
                console.log('Activo')
            } else {
                btn.removeClass('btn-success')
                btn.addClass('btn-danger')
                btn.text('Inactivo')
                console.log('Inactivo')
            }
        }).catch(function(data) {
            $('#btn-close-loading').trigger('click')
            $('#btn-succes-error').trigger('click')
            console.log(data.responseJSON)
        }).always(function() {
            $('#modal-succes-loading').hide()
            $('#btn-close-loading ,.btn-close-loading').trigger('click')
        })
    })
})

var _id = null
var row = null
    /* create user on click button btn-update-create-user verify input required*/
$(function() {
    /* Get row from dataTable and save in variable */
    $('#table-resources-it').on('click', '#btn-edit-user,.btn-delete-user', function() {
        row = $(this).parents('tr')[0]
    })

    $('#btn-update-create-user').on('click', () => $('#btn-sumbit-user').trigger('click'))

    $('#btn-create-user').on('click', () => { _id = null;
        row = null })

    $('#table-resources-it').on('click', '.btn-delete-user', function() {
        _id = $(this).val()
        $('#btn-succes-confirmation').trigger('click')
    })

    /* validate form-update-user */
    $('#btn-sumbit-user').on('click', function() {
        var data = $('.form-update-user')
        var form = data.find('input,select')
        let input_email = document.getElementById('input-email')
        input_email.validity.typeMismatch ? (input_email.setCustomValidity('El email no es válido')) : input_email.setCustomValidity('')
        var valid = form.filter(function() { return $(this).val() == '' })
        if (valid.length > 0) return
    })

    /* Prevent defult on sumbit form */
    $('.form-update-user').on('submit', function(e) {
        e.preventDefault()
        var data = $('.form-update-user').serializeArray()
        var token = data[0].value
        var id = typeof _id === 'null' ? '' : _id
            /* get first character of name and only first lastname */
        var name = data[1].value.trim()
        var last_name = data[2].value.trim()
        var usuario = (name.charAt(0) + last_name.split(' ')[0]).toLowerCase()
        var email = data[3].value
        var number_phone = data[4].value.replaceAll('-', '')
        var password = data[5].value
        var role = data[6].value
        $.ajax({
            headers: { 'X-CSRF-TOKEN': token },
            type: _id == null ? 'POST' : 'PUT',
            url: _id == null ? '/users/create' : '/users/update/',
            data: {
                'id': id,
                'name': name,
                'last_name': last_name,
                'username': usuario,
                'email': email,
                'number_phone': number_phone,
                'password': password,
                'role': role
            },
            dataType: 'json',
            beforeSend: function() {
                $('#btn-succes-loading').trigger('click')
            }
        }).then(function(data) {
            $('#btn-succes').trigger('click')
            users.map(function(user) {
                if (user.iduser == data.iduser) {
                    user.name = data.name
                    user.last_name = data.last_name
                    user.username = data.username
                    user.email = data.email
                    user.number_phone = data.number_phone
                    user.role = data.role
                }
                return user
            })
            if (data.iduser == id) {
                var rowData = $.dataTableInit.row(row).data()
                rowData[1] = data.username
                rowData[2] = data.role
                $.dataTableInit.row(row).data(rowData)
                $(':button').removeClass('disabled')
            } else {
                var rowData = $.dataTableInit.row(0).data()
                rowData[0] = $.dataTableInit.data().length + 1
                rowData[1] = data.username
                rowData[2] = data.role
                    /* change value into button betwee rowData[3] and rowData[5] */
                rowData[3] = `<button type="button" class="btn btn-success fs-6 state-user-active" value="${data.iduser}">
                                Activo
                            </button>`
                rowData[4] = `<button class="btn btn-warning" id="btn-edit-user" data-bs-toggle="modal" data-bs-target="#modalEditUser" value="${data.iduser}">
                                Editar
                            </button>`
                rowData[5] = `<button class="btn btn-danger" id="btn-delete-user" value="${data.iduser}" data-bs-toggle="modal" data-bs-target="#modal-succes-confirmation">
                                Eliminar
                            </button>`
                $.dataTableInit.row.add(rowData).draw()
                users.push({
                    'iduser': data.iduser,
                    'name': data.name,
                    'last_name': data.last_name,
                    'username': data.username,
                    'email': data.email,
                    'number_phone': data.number_phone,
                    'role': data.role
                })
            }
        }).catch(function(data) {
            $('#btn-close-loading').trigger('click')
            $('#btn-succes-error').trigger('click')
            console.log(data.responseJSON)
        }).always(function() {
            $('#modal-succes-loading').hide()
            $('#btn-close-loading, .btn-close-loading').trigger('click')
        })
    })

    $('#delete-data').on('click', function() {
        var data = $('.form-update-user').serializeArray()
        var token = data[0].value
        $.ajax({
            headers: { 'X-CSRF-TOKEN': token },
            type: 'DELETE',
            url: '/users/delete/' + _id,
            dataType: 'json',
            beforeSend: function() {
                $('#btn-succes-loading').trigger('click')
            }
        }).then(function(data) {
            $('#btn-succes').trigger('click')
            $.dataTableInit.row(row).remove().draw()
            users = users.filter(function(user) { return user.iduser != _id })
        }).catch(function(data) {
            $('#btn-close-loading').trigger('click')
            $('#btn-succes-error').trigger('click')
            console.log(data.responseJSON)
        }).always(function() {
            $('#modal-succes-loading').hide()
            $('#btn-close-loading, .btn-close-loading').trigger('click')
        })
    })
})

/* Permite only numbers in input[type=tel] and add '-' automatic after every three numbers input  */
$(function() {
    $('input[type="tel"]').on('keypress', function(e) {
        /* verify input text is only number*/
        var key = e.key
        if (key.match(/[0-9]/) === null) {
            e.preventDefault()
        } else {
            var val = $(this).val()
            if (val.length == 3 || val.length == 7) {
                $(this).val(val + '-')
            }
        }
    })
})

/* Change title on modalEditUser */
$(function() {
    $('#modalEditUser').on('show.bs.modal', function(e) {
        let btn = $(e.relatedTarget)
        let modal = $(this)
        $('.modal-title').css('padding-left', '10.5rem')
        setRequiredInput()
        if (btn.attr('id') == 'btn-edit-user') {
            $(this).find('.modal-title').text('Actualizar usuario')
            _id = btn.val()
            let user = users.find(user => user.iduser == _id)
            modal.find('#btn-update-create-user').text('Actualizar')
            setDataForm(user)
            $('input[name="password"]').attr('required', false)
        } else {
            $(this).find('.modal-title').text('Crear usuario')
            modal.find('#btn-update-create-user').text('Crear')
            $('.form-update-user').trigger('reset')
        }
    })
})

function setDataForm(data) {
    $('input[name="name"]').val(data.name)
    $('input[name="lastname"]').val(data.last_name)
    $('input[name="email"]').val(data.email)
        /* add '-' after every three numbers*/
    if (data.number_phone === null) {
        $('input[name="phone"]').val('')
    } else {
        $('input[name="phone"]').val(data.number_phone.toString().replace(/(\d{3})(\d{3})(\d{3})/, '$1-$2-$3'))
    }
    /* $('input[name="phone"]').val(data.telefono) */
    $('select[name="rol"]').val(data.role).trigger('change')
    $('#input-password').clone().val('').insertAfter('#input-password').prev().remove()
}

/* add reqiored attribute to Input */
function setRequiredInput() {
    $('input[name="name"]').attr('required', true)
    $('input[name="lastname"]').attr('required', true)
    $('input[name="email"]').attr('required', true)
    $('input[name="phone"]').attr('required', true)
    $('select[name="rol"]').attr('required', true)
    $('input[name="password"]').attr('required', true)
}