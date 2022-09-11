
var _id = null
var row = null

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

    $('#modalCreateCost').on('input', 'input[name="codigo_alp"]', function () {
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

    $('select[name="cost_type"]').on('change', function () {
        value = $(this).val()
        enableInputs(['serie_fourwall', 'date_start', 'date_end'])
        switch (value) {
            case 'fourwall':
                changeTexts(['Equipo 4wall', 'Serie 4wall', 'Costo 4wall'])
                break;
            case 'nexus':
                changeTexts(['Punto de Red', 'Serie Nexus', 'Costo Nexus'])
                break;

            case 'hp':
                changeTexts(['Equipo HP', 'Serie HP', 'Costo HP'])
                break;
        }
    })

    $('#save_cost').on('click', function () { $('#btn-sumbit-cost').trigger('click') })

    $('#form_costs').on('submit', function (e) {
        e.preventDefault()
        /* serialize form */
        const form = $(this).serializeArray()

        let data = {}
        let url = ''
        let type = 0

        switch ($('select[name="cost_type"]').val()) {
            case 'fourwall':
                data = {
                    'idproject': form[1].value,
                    'equipment': form[2].value,
                    'serie': form[3].value,
                    'cost': form[4].value,
                    'date_start': form[5].value,
                    'date_end': form[6].value == '' ? null : form[6].value,
                }
                url = '/maintenance/costs/fourwalls/create'
                type = 0
                break;
            case 'nexus':
                data = {
                    'idproject': form[1].value,
                    'network_point': form[2].value,
                    'cost': form[3].value,
                    'serie': form[4].value,
                    'date_start': form[5].value,
                    'date_end': form[6].value == '' ? null : form[6].value
                }
                url = '/maintenance/costs/nexus/create'
                type = 1
                break;
            case 'hp':
                data = {
                    'idproject': form[1].value,
                    'equipment': form[2].value,
                    'serie': form[3].value,
                    'cost': form[4].value,
                    'date_start': form[5].value,
                    'date_end': form[6].value == '' ? null : form[6].value
                }
                url = '/maintenance/costs/hps/create'
                type = 2
                break;
        }

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() },
            url: url,
            type: 'POST',
            data: data,
            dataType: 'json',
            beforeSend: function () {
                $('#btn-succes-loading').trigger('click')
            }
        }).then(function (response) {
            console.log(response)
            $('#btn-succes').trigger('click')
            updateCost(response, type)
            $(this).trigger('reset')
        }).catch(function (error) {
            $('#btn-succes-error').trigger('click')
            console.log(error)
        }).always(function () {
            $('#modal-succes-loading').modal('toggle')
            $('#btn-close-loading, .btn-close-loading').trigger('click')
        })
    })

    /* Consult cost by month */
    $('#btn-consult-date').on('click', function () {
        const date = $('#date_selected').val()

        $(this).searchData('GET', (text, removeOnTextIsEmptyOrLoadComplete) => {
            $.ajax({
                type: 'GET',
                url: '/maintenance/costs/filter/' + (date === '' ? 'na' : date.replaceAll('/', '-')),
            }).then(function (response) {
                if (response.length === 0) return $('.odd td').html('No se encontraron registros')
                removeOnTextIsEmptyOrLoadComplete('')
                const data = formatResponse(response)
                $.dataTableInit.rows.add(data).draw()
            }).catch(function (error) {
                $('.odd td').html('No se encontraron registros')
                console.log(error)
            })
        })
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
            if (typeof div_target.find("input").val() === "undefined") div_target = div_target.parent()
            var input = index != 0 ? $('input[name="codigo_alp"]')[index] : $('input[name="codigo_alp"]')
            $(input).val(div_target.find("input").val())
            _id = 1
            removeDivWithRecomendations()
        })
        div.appendChild(div_recomendation)
    })
    var recomendations = document.getElementsByClassName("recomendations")
    index != 0 ? recomendations[index].appendChild(div) : recomendations[index + 1].appendChild(div)
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
    console.log($.dataTableInit.data().toArray())
    var index = $.dataTableInit.data().toArray().findIndex(element => element[1] == data.idproject)
    console.log(index)
    console.log(data)
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

function changeTexts(texts) {
    $('#first_text').text(texts[0])
    $('#second_text').text(texts[1] != '' ? texts[1] : $('#second_text').text())
    $('#third_text').text(texts[2])
}

function disableInputs(inputs) {
    inputs.forEach(function (input) {
        $('input[name="' + input + '"]').attr('disabled', true)
    })
}

function enableInputs(inputs) {
    inputs.forEach(function (input) {
        $('input[name="' + input + '"]').attr('disabled', false)
    })
}

function formatResponse(response) {
    return response.map((item, index) => {
        return {
            0: index + 1,
            1: item.idproject,
            2: item.name,
            3: item.costfourwall === null ?
                `<a href="/maintenance/costs/fourwall/${item.idproject}">0.00</a>` :
                `<a href="/maintenance/costs/fourwall/${item.idproject}">$ ${getNumber(item.costfourwalls)}</a>`,
            4: item.costnexus === null ?
                `<a href="/maintenance/costs/nexus/${item.idproject}">0.00</a>` :
                `<a href="/maintenance/costs/nexus/${item.idproject}">$ ${getNumber(item.costnexus)}</a>`,
            5: item.costhp === null ?
                `<a href="/maintenance/costs/hp/${item.idproject}">0.00</a>` :
                `<a href="/maintenance/costs/hp/${item.idproject}">$ ${getNumber(item.costhp)}</a>`,
            6: `$ ${getNumber(JSON.parse(item.costfourwalls === null ? 0.00 : item.costfourwalls) +
                JSON.parse(item.costnexus === null ? 0.00 : item.costnexus) +
                JSON.parse(item.costhp === null ? 0.00 : item.costhp))
                }`,
            7: `S/. ${getNumber((JSON.parse(item.costfourwalls === null ? 0.00 : item.costfourwalls) +
                JSON.parse(item.costnexus === null ? 0.00 : item.costnexus) +
                JSON.parse(item.costhp === null ? 0.00 : item.costhp)) * tchange)}`
        }
    })
}

/* get number two values */
function getNumber(number) {
    return Number(number).toFixed(2)
}
