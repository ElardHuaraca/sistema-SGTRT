const { Datepicker } = require("vanillajs-datepicker")


var _id = null
var row = null

$(function () {

    Object.assign(Datepicker.locales, language)

    /* Implement datepicker */
    var element = document.getElementById("date_selected")
    new Datepicker(element, {
        minDate: new Date(2019, 0, 1),
        maxDate: new Date(2030, 11, 31),
        pickLevel: 0,
        startView: 1,
        language: 'es',
        todayHighlight: true,
    })

    var date_start_fourwall = document.getElementById("date_start_fourwall")
    new Datepicker(date_start_fourwall, {
        minDate: new Date(2019, 0, 1),
        maxDate: new Date(2030, 11, 31),
        pickLevel: 0,
        startView: 1,
        language: 'es',
        todayHighlight: true,
    })

    var date_end_fourwall = document.getElementById("date_end_fourwall")
    new Datepicker(date_end_fourwall, {
        minDate: new Date(2019, 0, 1),
        maxDate: new Date(2030, 11, 31),
        pickLevel: 0,
        startView: 1,
        language: 'es',
        todayHighlight: true,
    })

    var date_start_hp = document.getElementById("date_start_hp")
    new Datepicker(date_start_hp, {
        minDate: new Date(2019, 0, 1),
        maxDate: new Date(2030, 11, 31),
        pickLevel: 0,
        startView: 1,
        language: 'es',
        todayHighlight: true,
    })

    var date_end_hp = document.getElementById("date_end_hp")
    new Datepicker(date_end_hp, {
        minDate: new Date(2019, 0, 1),
        maxDate: new Date(2030, 11, 31),
        pickLevel: 0,
        startView: 1,
        language: 'es',
        todayHighlight: true,
    })
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

/* on write input give custom autocomplete obtaining values of the var projects */
$(function () {
    $('#modalCreateFourwall').on('input', 'input[name="codigo_alp"]', function () {
        if ($(this).val().length === 0) return
        var codigo_alp = projects.filter(project => project.idproject.toString().indexOf($(this).val()) > -1)
        if (codigo_alp.length === 0) return
        else if (codigo_alp.length === 1) {
            if (codigo_alp[0].idproject.toString() === $(this).val()) {
                removeDivWithRecomendations()
                return
            }
        }
        addDivWithRecomendations(codigo_alp, 0)
    })

    $('#modalCreateNexus').on('input', 'input[name="codigo_alp"]', function () {
        if ($(this).val().length === 0) return
        var codigo_alp = projects.filter(project => project.idproject.toString().indexOf($(this).val()) > -1)
        if (codigo_alp.length === 0) return
        else if (codigo_alp.length === 1) {
            if (codigo_alp[0].idproject.toString() === $(this).val()) {
                removeDivWithRecomendations()
                return
            }
        }
        addDivWithRecomendations(codigo_alp, 1)
    })

    $('#modalCreateHp').on('input', 'input[name="codigo_alp"]', function () {
        if ($(this).val().length === 0) return
        var codigo_alp = projects.filter(project => project.idproject.toString().indexOf($(this).val()) > -1)
        if (codigo_alp.length === 0) return
        else if (codigo_alp.length === 1) {
            if (codigo_alp[0].idproject.toString() === $(this).val()) {
                removeDivWithRecomendations()
                return
            }
        }
        addDivWithRecomendations(codigo_alp, 2)
    })

    $('input[name="codigo_alp"').on('keydown', function (e) {
        removeDivWithRecomendations()
    })
})

function addDivWithRecomendations(codigo_alp, index) {
    removeDivWithRecomendations()
    var div = document.createElement("div")
    div.className = "col-12"
    div.id = "div_recomendations"
    div.style = "position: absolute; padding-top:2.5rem; padding-left:10rem;z-index:100"
    codigo_alp.forEach(element => {
        var div_recomendation = document.createElement("div")
        div_recomendation.className = "bg-light p-2 border border-secondary"
        div_recomendation.style = "cursor:pointer; display:block;"
        div_recomendation.innerHTML = "<span>" + element.idproject + " - " + element.name + "</span>"
        div_recomendation.innerHTML += "<input type='hidden' value=" + element.idproject + ">"
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
        div_recomendation.addEventListener("click", function (e) {
            var div_target = $(e.target)
            if (typeof div_target.find("input").val() === "undefined") {
                div_target = div_target.parent()
            }
            var input = $('input[name="codigo_alp"]')[index]
            $(input).val(div_target.find("input").val())
            _id = 1
            removeDivWithRecomendations()
        })
        div.appendChild(div_recomendation)
    })
    var recomendations = document.getElementsByClassName("recomendations")
    recomendations[index].appendChild(div)
}

/* function remove recomendation div */
function removeDivWithRecomendations() {
    var recomendations = document.getElementById("div_recomendations")
    if (recomendations == null) return
    recomendations.remove()
}

/* prepare form for the submit */
$(function () {

    $('#modalCreateFourwall').on('show.bs.modal', function (event) {
        setRequiredInputs($(event.currentTarget))
    })

    $('#btn-update-create-fourwall').on('click', function () {
        $('#btn-sumbit-fourwall').trigger('click')
    })

    $('#modalCreateNexus').on('show.bs.modal', function (event) {
        setRequiredInputs($(event.currentTarget))
    })

    $('#btn-update-create-nexus').on('click', function () {
        $('#btn-sumbit-nexus').trigger('click')
    })

    $('#modalCreateHp').on('show.bs.modal', function (event) {
        setRequiredInputs($(event.currentTarget))
    })

    $('#btn-update-create-hp').on('click', function () {
        $('#btn-sumbit-hp').trigger('click')
    })

    $('.form-create-fourwall').on('submit', function (e) {
        e.preventDefault()
        var form = $(this)
        var data = form.serializeArray()
        var token = $('input[name="_token"]').val()
        $.ajax({
            headers: { 'X-CSRF-TOKEN': token },
            url: '/maintenance/costs/fourwalls/create',
            type: 'POST',
            data: {
                'idproject': data[0].value,
                'equipment': data[1].value,
                'serie': data[2].value,
                'cost': data[3].value,
                'date_start': data[4].value,
                'date_end': data[5].value
            },
            beforeSend: function () {
                $('#btn-succes-loading').trigger('click')
            }
        }).then(function (data) {
            $('#btn-succes').trigger('click')
            updateCost(data, 0)
            form.trigger('reset')
        }).catch(function (data) {
            $('#btn-close-loading').trigger('click')
            $('#btn-succes-error').trigger('click')
            console.log(data.responseJSON)
        }).always(function () {
            $('#modal-succes-loading').modal('toggle')
            $('#btn-close-loading, .btn-close-loading').trigger('click')
        })
    })

    $('.form-create-nexus').on('submit', function (e) {
        e.preventDefault()
        var form = $(this)
        var data = form.serializeArray()
        var token = $('input[name="_token"]').val()
        $.ajax({
            headers: { 'X-CSRF-TOKEN': token },
            url: '/maintenance/costs/nexus/create',
            type: 'POST',
            data: {
                'idproject': data[0].value,
                'network_point': data[1].value,
                'cost': data[2].value,
            },
            beforeSend: function () {
                $('#btn-succes-loading').trigger('click')
            }
        }).then(function (data) {
            $('#btn-succes').trigger('click')
            updateCost(data, 1)
            form.trigger('reset')
        }).catch(function (data) {
            $('#btn-close-loading').trigger('click')
            $('#btn-succes-error').trigger('click')
            console.log(data.responseJSON)
        }).always(function () {
            $('#modal-succes-loading').modal('toggle')
            $('#btn-close-loading, .btn-close-loading').trigger('click')
        })
    })

    $('.form-create-hp').on('submit', function (e) {
        e.preventDefault()
        var form = $(this)
        var data = form.serializeArray()
        var token = $('input[name="_token"]').val()
        $.ajax({
            headers: { 'X-CSRF-TOKEN': token },
            url: '/maintenance/costs/hps/create',
            type: 'POST',
            data: {
                'idproject': data[0].value,
                'equipment': data[1].value,
                'serie': data[2].value,
                'cost': data[3].value,
                'date_start': data[4].value,
                'date_end': data[5].value
            },
            beforeSend: function () {
                $('#btn-succes-loading').trigger('click')
            }
        }).then(function (data) {
            $('#btn-succes').trigger('click')
            updateCost(data, 2)
            form.trigger('reset')
        }).catch(function (data) {
            $('#btn-close-loading').trigger('click')
            $('#btn-succes-error').trigger('click')
            console.log(data.responseJSON)
        }).always(function () {
            $('#modal-succes-loading').modal('toggle')
            $('#btn-close-loading, .btn-close-loading').trigger('click')
        })
    })
})

function setRequiredInputs(modal) {
    modal.find('input[name="codigo_alp"]').attr('required', true)
    modal.find('input[name="equipment_fourwall"]').attr('required', true)
    modal.find('input[name="serie_fourwall"]').attr('required', true)
    modal.find('input[name="cost_fourwall"]').attr('required', true)
    modal.find('input[name="date_start"]').attr('required', true)
    modal.find('input[name="point_red_nexus"]').attr('required', true)
    modal.find('input[name="cost_nexus"]').attr('required', true)
    modal.find('input[name="equip_hp"]').attr('required', true)
    modal.find('input[name="serie_hp"]').attr('required', true)
    modal.find('input[name="cost_hp"]').attr('required', true)
}

/* Update cost in datatable */
function updateCost(data, type) {
    var index = $.dataTableInit.data().toArray().findIndex(element => element[1] === data.idproject)
    var row = $.dataTableInit.row(index).data()
    let href = $(row[type + 3]).attr('href')
    row[type + 3] = (deleteDolarAndHreft(row[type + 3]) + parseFloat(data.cost))
    row[6] = deleteDolar(row[6]) + parseFloat(data.cost)
    row[7] = row[6] * tchange
    row[type + 3] = `<a href="${href}">$ ${row[type + 3].toFixed(2)}</a>`
    row[6] = '$' + row[6].toFixed(2)
    row[7] = 'S/.' + row[7].toFixed(2)
    $.dataTableInit.row(index).data(row).draw()
}

function deleteDolarAndHreft(row) {
    let start = row.indexOf('$')
    let end = row.indexOf('</')
    var value = row.substring(start + 1, end)
    return parseFloat(value.replaceAll(' ', ''))
}

function deleteDolar(row) {
    var replace = row.replace('$', '')
    return parseFloat(replace.replaceAll(' ', ''))
}
