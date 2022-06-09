const { delay } = require('lodash');

require('./bootstrap');
require('datatables.net-bs5');
require('datatables.net-responsive-bs5');

$(function () {

    $.dataTableInit = $('#table-resources-it').DataTable({
        responsive: true,
        language: {
            search: 'Buscar:',
            lengthMenu: 'Mostrar _MENU_ registros por página',
            zeroRecords: 'No se encontraron registros',
            info: 'Registros del _START_ al _END_ de _TOTAL_ registros',
            infoEmpty: 'No hay registros disponibles',
            infoFiltered: '(filtrado de _MAX_ registros totales)',
            paginate: {
                first: 'Primero',
                last: 'Último',
                next: 'Siguiente',
                previous: 'Anterior'
            }
        }
    })
})

$(function () {
    $('#btn-close-succes').on('click', function () {
        $('#btn-close-loading').trigger('click')
    })
    $('#btn-close-error').on('click', function () {
        $('#btn-close-succes').trigger('click')
        $('#btn-close-loading').trigger('click')
    })
})

/* change icon edge after click not-show-password */
$(function () {
    $('.not-show-password').on('click', function () {
        if ($(this).hasClass('fa-eye')) {
            $(this).removeClass('fa-eye').addClass('fa-eye-slash')
        } else {
            $(this).removeClass('fa-eye-slash').addClass('fa-eye')
        }
        let type = $('#input-password').attr('type') === 'password' ? 'text' : 'password'
        $('#input-password').trigger('blur').clone().prop('type', type).insertAfter($('#input-password')).prev().remove()
    })
})

/* Remove button class disabled on load document complete */
$(function () {
    $.fn.removeDisabled = function () {
        $(this).removeClass('disabled')
    }

    $('.remove-disable').removeDisabled()
})

$(function () {
    /* Evit insert text on press key into input double and detect when focus retired on input*/
    $.fn.evitWriteTextCost = function () {

        $(this).on('keypress', function (e) {
            if (e.key.match(/[0-9\./]/) === null) {
                e.preventDefault()
            }
        })

        $(this).on('focusout', function () {
            var value = $(this).val()
            var refor = value.split('.')
            if (refor.length == 1 && refor[0] != '') {
                refor = refor[0] + '.00'
            } else if (refor.length > 1) {
                if (refor[1] !== '') {
                    if (refor[1].length == 1) {
                        refor = refor[0] + '.' + refor[1] + '0'
                    } else if (refor[1].length > 2) {
                        refor = refor[0] + '.' + refor[1].substring(1, 3)
                    } else {
                        refor = refor[0] + '.' + refor[1]
                    }
                } else if (refor[1] === '') {
                    refor = refor[0] + '.00'
                }
            }
            $(this).val(refor)
        })
    }

    $.fn.evitWriteTextOnVersion = function () {
        $(this).on('keypress', function (e) {
            if (e.key.match(/[0-9\./]/) === null) {
                e.preventDefault()
            }
        })

        $(this).on('focusout', function () {
            var value = $(this).val()
            var refor = value.split('.')
            if (refor.length >= 2) {
                if (refor[1] === '') refor = refor[0]
                else refor = refor[0] + '.' + refor[1]
            }
            $(this).val(refor)
        })
    }

    $.fn.evitWriteText = function () {
        $(this).on('keypress', function (e) {
            if (e.key.match(/[0-9]/) === null) {
                e.preventDefault()
            }
        })
    }

    /* Update State and change state to 'Activo' or 'Inactivo' */

    $.fn.updateState = function (url, token, relatedTarget) {
        $('#table-resources-it').on('click', relatedTarget, function () {
            const state = $(this).hasClass('btn-success')
            const btn = $(this)
            console.log($(this))
            $.ajax({
                headers: { 'X-CSRF-TOKEN': token },
                url: url + btn.val(),
                type: 'PUT',
                data: {
                    'is_deleted': state
                },
                dataType: 'json',
                beforeSend: function () {
                    $('#btn-succes-loading').trigger('click')
                }
            }).then(function (response) {
                if (response.is_deleted === 'false') {
                    btn.removeClass('btn-danger').addClass('btn-success').text('Activo')
                } else {
                    btn.removeClass('btn-success').addClass('btn-danger').text('Inactivo')
                }
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
    }
})
