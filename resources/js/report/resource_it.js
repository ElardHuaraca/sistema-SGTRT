const { Chart, registerables } = require("chart.js");
const { result } = require("lodash");

var timeout = null;
let oldData = null;
let newData = null

$(function () {
    /* Register default drivers */
    Chart.register(...registerables)

    oldData = $.dataTableInit.rows().data()
    newData = oldData.toArray()
});

/* Add values to table */
$(function () {
    if ((typeof server === 'undefined')) return
    server.sort((a, b) => new Date(a.date) - new Date(b.date))
    var canva = $('#chart-grafic')
    var yValues = server.filter(x => x.resource_name === 'CPU').map(x => x.amount)
    var xValues = server.filter(x => x.resource_name === 'CPU').map(x => $.refactorDateNotMinutes(x.date))
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
            responsive: true
        }
    });

    $('#picker-resource').on('change', function (e) {
        var selected = $('option:selected', this).val();
        chart.options.plugins.title.text = selected.toString()
        switch (selected) {
            case 'RAM':
                chart.data.labels = server.filter(x => x.resource_name === 'RAM').map(x => $.refactorDateNotMinutes(x.date))
                chart.data.datasets[0].data = server.filter(x => x.resource_name === 'RAM').map(x => x.amount)
                break;
            case 'DISCO':
                server_disk = server.filter(x => x.resource_name === 'HDD' || x.resource_name === 'SSD').reduce((acc, cur) => {
                    if (acc[cur.date] != null || acc[cur.date] != undefined) { acc[cur.date] += cur.amount }
                    else { acc[cur.date] = cur.amount }
                    return acc
                }, [])
                /* refactor server_disk to [{resource_name:'DISK',date:date,mount}] */
                server_disk = Object.keys(server_disk).map(x => {
                    return {
                        resource_name: 'DISK',
                        date: x,
                        amount: server_disk[x]
                    }
                })
                chart.data.labels = server_disk.map(x => $.refactorDateNotMinutes(x.date))
                chart.data.datasets[0].data = server_disk.map(x => x.amount)
                break;
            default:
                chart.data.labels = server.filter(x => x.resource_name === 'CPU').map(x => $.refactorDateNotMinutes(x.date))
                chart.data.datasets[0].data = server.filter(x => x.resource_name === 'CPU').map(x => x.amount)
                break;
        }
        chart.update()
    })
})


$(function () {

    /* On find reload datatable with new array */
    $('#input-buscar-cliente').on('keyup', function (e) {
        const text = $(this).val()
        $(this).searchData(text, (text, removeOnTextIsEmptyOrLoadComplete) => {
            const filter = newData.filter(x => x[3].match(text.toUpperCase()))
            removeOnTextIsEmptyOrLoadComplete('')
            if (filter.length > 0) return oldData.rows.add(filter).draw()
            return $('.odd td').html('No se encontraron registros')
        })
    })

    $('#input-buscar-hostname').on('keyup', function (e) {
        const text = $(this).val()
        $(this).searchData(text, (text, removeOnTextIsEmptyOrLoadComplete) => {
            $.ajax({
                url: '/reports/filter/hostname/vmware',
                type: 'GET',
                data: {
                    'name': text,
                    'date_start': $('input[name="date_start"]').val(),
                    'date_end': $('input[name="date_end"]').val()
                }
            }).then(function (response) {
                if (response.length === 0) return $('.odd td').html('No se encontraron registros')
                removeOnTextIsEmptyOrLoadComplete('')
                var data = formatResponse(response)
                $.dataTableInit.rows.add(data).draw()
            }).catch(function (error) {
                $('.odd td').html('No se encontraron registros')
                console.log(error)
            })
        })
    })


    /* Filter by date */
    $('#btn-consult').on('click', function (e) {
        start = $('input[name="date_start"]').val()
        end = $('input[name="date_end"]').val()

        removeDangerClassOrAddDagerClass($('input[name="date_start"]'), true)
        removeDangerClassOrAddDagerClass($('input[name="date_end"]'), true)

        if (start === '' && end === '') {
            newData = oldData.toArray()
            $(this).searchData('', () => { })
            return
        }

        if (start === '') {
            alert('Seleccione una fecha de inicio')
            removeDangerClassOrAddDagerClass($('input[name="date_start"]'), false)
            return
        }

        if (end === '') {
            alert('Seleccione una fecha de fin')
            removeDangerClassOrAddDagerClass($('input[name="date_end"]'), false)
            return
        }

        var date_start = reestructureDate(start)
        var date_end = reestructureDate(end)

        if (!(date_end >= date_start)) {
            alert('La fecha de fin debe ser mayor o igual a la fecha de inicio')
            return
        }

        $(this).searchData('GET', (text, removeOnTextIsEmptyOrLoadComplete) => {
            $.ajax({
                url: '/reports/filter/btween/dates',
                type: 'GET',
                data: { 'date_start': start, 'date_end': end }
            }).then(function (response) {
                if (response.length === 0) return $('.odd td').html('No se encontraron registros')
                removeOnTextIsEmptyOrLoadComplete('')
                var data = formatResponse(response)
                $.dataTableInit.rows.add(data).draw()
                newData = $.dataTableInit.rows().data().toArray()
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
                        href="/reports/${item.idserver}/${start.replaceAll('/', '-')}/${end.replaceAll('/', '-')}">
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
