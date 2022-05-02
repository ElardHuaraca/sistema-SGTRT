const { Chart, registerables } = require("chart.js");

$(function () {
    /* Registrar los controladores default */
    Chart.register(...registerables)
});

/*Agregar valores a la tabla*/
$(function () {
    if ((typeof grafic_default === 'undefined')) return
    var canva = $('#chart-grafic')
    var yValues = grafic_default.map(function (item) { return item.tiempo_ejecucion })
    var xValues = grafic_default.map(function (item) { return item.periodo })
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
            ;
        }
        chart.update()
    })
})
