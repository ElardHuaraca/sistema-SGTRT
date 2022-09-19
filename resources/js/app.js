const { delay } = require('lodash');
const { Datepicker } = require("vanillajs-datepicker")

require('./bootstrap');
require('datatables.net-bs5');
require('datatables.net-responsive-bs5');


var timeout = null;
var oldData = null;

$(function () {

    $.dataTableInit = $('#table-resources-it').DataTable({
        responsive: true,
        searching: false,
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
            if (e.key.match(/[0-9\./]/) === null) e.preventDefault()
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
                        refor = refor[0] + '.' + refor[1].substring(0, 3)
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

    /* add option to find proyect or sow , etc */
    $.fn.addRecomendations = function addDivWithRecomendations(array, index, relatedTarget, span_item, values, style, col, seccond_input, second_input_value) {
        $('').removeRecomendations()
        var div = document.createElement("div")
        div.className = `col-${col}`
        div.id = "div_recomendations"
        div.style = "position: absolute;" + style
        array.forEach(element => {
            var div_recomendation = document.createElement("div")
            div_recomendation.className = "bg-light p-2 border border-secondary"
            div_recomendation.style = "cursor:pointer; display:block;"
            div_recomendation.innerHTML = span_item(element)

            div_recomendation.addEventListener("mouseenter", function (e) {
                var div_target = $(e.target)
                div_target.removeClass("bg-light")
                div_target.addClass("bg-secondary")
            })

            div_recomendation.addEventListener("mouseleave", function (e) {
                var div_target = $(e.target)
                div_target.removeClass("bg-secondary")
                div_target.addClass("bg-light")
            })

            div_recomendation.addEventListener("click", function () {
                var input = index !== null ? $(relatedTarget)[index] : $(relatedTarget)
                input = input == null ? $(relatedTarget) : input
                $(input).val(values(element))
                seccond_input !== null ? $(seccond_input).val(second_input_value(element)) : ''
                $(input).removeRecomendations()
            })

            div.appendChild(div_recomendation)
        })
        var recomendations = document.getElementsByClassName("recomendations")
        index !== null ? recomendations[index].appendChild(div) : recomendations[0].appendChild(div)
    }

    /* function remove recomendation div */
    $.fn.removeRecomendations = function removeDivWithRecomendations() {
        var recomendations = document.getElementById("div_recomendations")
        if (recomendations == null) return
        recomendations.remove()
    }

    /* Only firs on load data */
    $.oldData = $.dataTableInit.rows().data()

    /* function on search */
    $.fn.searchData = function loadDataOnDataTable(text, _function) {
        clearTimeout(timeout)
        timeout = setTimeout(function () {

            removeOnTextIsEmptyOrLoadComplete(text)
            if (text !== '') {
                $.dataTableInit.clear().draw()
                $('.odd td').html('<div class="spinner"></div>')
                _function(text, removeOnTextIsEmptyOrLoadComplete)

            } else {
                $.dataTableInit.clear().draw()
                $.dataTableInit.rows.add($.oldData).draw()
            }

            clearTimeout(timeout)
        }, 800)
    }

    function removeOnTextIsEmptyOrLoadComplete(text) {
        text === '' ? $('#table-resources-it .spinner').last().addClass('d-none') : ''
    }

    /* Tranform ('yyyy-mm-dd HH:mm:ss') to ('dd/mm/yyyy') */
    $.refactorDateNotMinutes = function (date_refactor) {
        const date = date_refactor.split('-')
        const date_ = new Date(date[0], date[1] - 1, date[2])
        return ("00" + date_.getDate()).slice(-2) + "/" + ("00" + (date_.getMonth() + 1)).slice(-2) + "/" + date_.getFullYear()
    }

    /* Transform ('yyyy-mm-dd HH:mm:ss to dd/mm/yyyy HH:mm:ss') */
    $.refactorDateMinutes = function (date_refactor) {
        const date = new Date(date_refactor)
        return ("00" + date.getDate()).slice(-2) + "/" + ("00" + (date.getMonth() + 1)).slice(-2) + "/" + date.getFullYear() + " " + ("00" + date.getHours()).slice(-2) + ":" + ("00" + date.getMinutes()).slice(-2) + ":" + ("00" + date.getSeconds()).slice(-2)
    }

})

$(function () {

    Object.assign(Datepicker.locales, language)

    /* Implement datepicker */
    var element = document.getElementById("date_selected")
    $('#date_selected').on('keydown', function (e) { if (e.key === 'Backspace') $(this).val('') })
    if (element !== undefined && element !== null) {
        new Datepicker(element, {
            minDate: new Date(2019, 0, 1),
            maxDate: new Date(2030, 11, 31),
            pickLevel: 1,
            startView: 1,
            language: 'es',
            todayHighlight: true,
        })
    }

    var date_start_fourwall = document.getElementById("date_start_fourwall")
    $('#date_start_fourwall').on('keydown', function (e) { if (e.key === 'Backspace') $(this).val('') })
    if (date_start_fourwall !== undefined && date_start_fourwall !== null) {
        new Datepicker(date_start_fourwall, {
            minDate: new Date(2019, 0, 1),
            maxDate: new Date(2030, 11, 31),
            pickLevel: 0,
            startView: 1,
            language: 'es',
            todayHighlight: true,
        })
    }

    var date_end_fourwall = document.getElementById("date_end_fourwall")
    $('#date_end_fourwall').on('keydown', function (e) { if (e.key === 'Backspace') $(this).val('') })
    if (date_end_fourwall !== undefined && date_end_fourwall !== null) {
        new Datepicker(date_end_fourwall, {
            minDate: new Date(2019, 0, 1),
            maxDate: new Date(2030, 11, 31),
            pickLevel: 0,
            startView: 1,
            language: 'es',
            todayHighlight: true,
        })
    }

    var date_start_hp = document.getElementById("date_start_hp")
    $('#date_start_hp').on('keydown', function (e) { if (e.key === 'Backspace') $(this).val('') })
    if (date_start_hp !== undefined && date_start_hp !== null) {
        new Datepicker(date_start_hp, {
            minDate: new Date(2019, 0, 1),
            maxDate: new Date(2030, 11, 31),
            pickLevel: 0,
            startView: 1,
            language: 'es',
            todayHighlight: true,
        })
    }

    var date_end_hp = document.getElementById("date_end_hp")
    $('#date_end_hp').on('keydown', function (e) { if (e.key === 'Backspace') $(this).val('') })
    if (date_end_hp !== undefined && date_end_hp !== null) {
        new Datepicker(date_end_hp, {
            minDate: new Date(2019, 0, 1),
            maxDate: new Date(2030, 11, 31),
            pickLevel: 0,
            startView: 1,
            language: 'es',
            todayHighlight: true,
        })
    }

    var date_start_resources = document.getElementById("date_start_resources")
    $('#date_start_resources').on('keydown', function (e) { if (e.key === 'Backspace') $(this).val('') })
    if (date_start_resources !== undefined && date_start_resources !== null) {
        new Datepicker(date_start_resources, {
            minDate: new Date(2019, 0, 1),
            pickLevel: 0,
            startView: 1,
            language: 'es',
            todayHighlight: true,
        })
    }

    var date_end_resources = document.getElementById("date_end_resources")
    $('#date_end_resources').on('keydown', function (e) { if (e.key === 'Backspace') $(this).val('') })
    if (date_end_resources !== undefined && date_end_resources !== null) {
        new Datepicker(date_end_resources, {
            minDate: new Date(2019, 0, 1),
            pickLevel: 0,
            startView: 1,
            language: 'es',
            todayHighlight: true,
        })
    }
})


const language = {
    es: {
        days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
        daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
        daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
        today: "Hoy",
        monthsTitle: "Meses",
        clear: "Borrar",
        weekStart: 1,
        format: "dd/mm/yyyy"
    }
}

window.reestructureDate = function (date) {
    var date_reestructure = date.split('/')
    return new Date(date_reestructure[2], date_reestructure[1] - 1, date_reestructure[0])
}
