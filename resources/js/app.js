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
