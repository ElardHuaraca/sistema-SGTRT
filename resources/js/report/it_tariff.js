$(function () {

    /* Detect on click in button and verify dates for search vms*/
    $('#btn-consult').on('click', function (e) {
        date_start = $('input[name="date_start"]').val()
        date_end = $('input[name="date_end"]').val()

        if ((date_start != '' && date_end == '') || (date_start == '' && date_end != '')) {
            return alert('Por favor seleccione un rango de fechas')
        } else if (reestructureDate(date_start) > reestructureDate(date_end) || reestructureDate(date_start).getTime() == reestructureDate(date_end).getTime()) {
            return alert('La fecha de fin debe ser mayor a la fecha de inicio')
        } else if (date_start == '' && date_end == '') {
            $(this).searchData('', () => { })
        } else {
            $(this).searchData('/reports/it/tariff/bwteen/dates', function (text, removeOnTextIsEmptyOrLoadComplete) {
                $.ajax({
                    url: text,
                    type: 'GET',
                    data: {
                        'date_start': date_start,
                        'date_end': date_end
                    }
                }).then(function (response) {
                    if (response.length === 0) return $('.odd td').html('No se encontraron registros');
                    removeOnTextIsEmptyOrLoadComplete('')
                    const data = formatResponseByProject(response)
                    $.dataTableInit.rows.add(data).draw();
                }).catch(function (error) {
                    $.dataTableInit.rows.add([]).draw();
                    console.log(error)
                })
            })
        }

    });

    /* Function to reestructure data for dataTable*/
    function formatResponseByProject(response) {
        return response.map(item => {
            return {
                0: item.idproject,
                1: item.project_name,
                2: item.CPU,
                3: item.DISK,
                4: item.RAM,
                5: item.cost_splas,
                6: item.lic_cloud,
                7: item.backup,
                8: item.mo,
                9: item.cost_maintenance,
                10: item.cost_total,
                11: `<a class="btn btn-success"
                        href="/reports/it/tariff/project/${item.idproject}/${date_start.replaceAll('/', '-')}/${date_end.replaceAll('/', '-')}"
                        role="button">
                        Ver Detalle
                    </a>`
            };
        });
    }
});
