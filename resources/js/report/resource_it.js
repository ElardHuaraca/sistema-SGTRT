const { Chart, registerables } = require("chart.js");
const { result } = require("lodash");

var timeout = null;
var oldData = null;

$(function () {
    /* Register default drivers */
    Chart.register(...registerables)
});

/* Add values to table */
$(function () {
    if ((typeof server === 'undefined')) return
    var canva = $('#chart-grafic')
    var yValues = server.filter(x => x.resource_name === 'CPU').map(x => x.amount)
    var xValues = server.filter(x => x.resource_name === 'CPU').map(x => x.date)
    var chart = new Chart(canva, {
        type: 'line',
        data: {
            labels: xValues,
            datasets: [{
                data: yValues,
                borderColor: 'green',
                fill: false,
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'CPU',
                    font: {
                        size: 20
                    }
                },
                legend: {
                    display: false
                }
            },
        }
    });

    $('#picker-resource').on('change', function (e) {
        var selected = $('option:selected', this).val();
        chart.options.plugins.title.text = selected.toString()
        if (selected === 'CPU') {
        }
        chart.update()
    })
})


$(function () {
    /* On find reload datatable with new array */
    $('#input-buscar-cliente').on('keyup', function (e) {
        const text = $(this).val()
        $(this).searchData(text, (text, removeOnTextIsEmptyOrLoadComplete) => {
            $.ajax({
                url: '/reports/filter/project/name',
                type: 'GET',
                data: { 'name': text }
            }).then(function (response) {
                if (response.length === 0) return $('.odd td').html('No se encontraron registros')
                removeOnTextIsEmptyOrLoadComplete('')
                var data = formatResponse(response)
                $.dataTableInit.rows.add(data).draw()
            }).catch(function (error) {
                console.log(error)
            })
        })
    })

    $('#input-buscar-hostname').on('keyup', function (e) {
        const text = $(this).val()
        $(this).searchData(text, (text, removeOnTextIsEmptyOrLoadComplete) => {
            $.ajax({
                url: '/reports/filter/hostname/vmware',
                type: 'GET',
                data: { 'name': text }
            }).then(function (response) {
                if (response.length === 0) return $('.odd td').html('No se encontraron registros')
                removeOnTextIsEmptyOrLoadComplete('')
                var data = formatResponse(response)
                $.dataTableInit.rows.add(data).draw()
            }).catch(function (error) {
                console.log(error)
            })
        })
    })


    /* Filter by date */
    $('#btn-consult').on('click', function (e) {
        var start = $('input[name="date_start"]')
        var end = $('input[name="date_end"]')

        removeDangerClassOrAddDagerClass(start, true)
        removeDangerClassOrAddDagerClass(end, true)

        if (start.val() === '' && end.val() === '') {
            $(this).searchData('', () => { })
            return
        }

        if (start.val() === '') {
            alert('Seleccione una fecha de inicio')
            removeDangerClassOrAddDagerClass(start, false)
            return
        }

        if (end.val() === '') {
            alert('Seleccione una fecha de fin')
            removeDangerClassOrAddDagerClass(end, false)
            return
        }

        var date_start = reestructureDate(start.val())
        var date_end = reestructureDate(end.val())

        if (!(date_end >= date_start)) {
            alert('La fecha de fin debe ser mayor o igual a la fecha de inicio')
            return
        }

        $(this).searchData('GET', (text, removeOnTextIsEmptyOrLoadComplete) => {
            $.ajax({
                url: '/reports/filter/btween/dates',
                type: 'GET',
                data: { 'date_start': start.val(), 'date_end': end.val() }
            }).then(function (response) {
                if (response.length === 0) return $('.odd td').html('No se encontraron registros')
                removeOnTextIsEmptyOrLoadComplete('')
                var data = formatResponse(response)
                $.dataTableInit.rows.add(data).draw()
            }).catch(function (error) {
                console.log(error)
            })
        })
    })

    function formatResponse(response) {
        return response.map(function (item, index) {
            resources = JSON.parse(item.resources);
            return {
                0: index + 1,
                1: item.active,
                2: item.idproject,
                3: item.project_name,
                4: item.name,
                5: resources.CPU === undefined ? '0' : resources.CPU,
                6: resources.RAM === undefined ? '0' : resources.RAM,
                7: (resources.HDD === undefined ? 0 : resources.HDD) + (resources.SSD === undefined ? 0 : resources.SSD),
                8: item.service,
                9: `<a class="btn btn-info" role="button"
                        href="/reports/${item.idserver}">
                        <i class="fa-solid fa-chart-simple"></i>
                    </a>`
            };
        });
    }

    function removeDangerClassOrAddDagerClass(element, state) {
        if (state) element.removeClass('border-danger')
        else element.addClass('border-danger')
    }

})
