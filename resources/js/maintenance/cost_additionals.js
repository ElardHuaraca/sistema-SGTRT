var _id = null
var _row = null
var _idproject = null
var _type = null

$(function () {

    /* prepare inputs for insert costs */
    $('input[name="cost_fourwall"]').evitWriteTextCost()
    $('input[name="cost_nexus"]').evitWriteTextCost()
    $('input[name="cost_hp"]').evitWriteTextCost()

    /* on show fourwall,nexus,hp modal */
    $('#modalUpdateFourwall').on('show.bs.modal', function (event) {
        var relatedTarget = event.relatedTarget
        var fourwall = fourwalls.filter(x => x.idfourwall === JSON.parse($(relatedTarget).attr('value')))[0]
        /* set data on inputs */
        $('input[name="codigo_alp"').val(fourwall.idproject)
        $('input[name="equipment_fourwall"').val(fourwall.equipment)
        $('input[name="serie_fourwall"').val(fourwall.serie)
        $('input[name="cost_fourwall"').val(fourwall.cost)
        setDate('date_start', fourwall.date_start)
        if (fourwall.date_end !== null) setDate('date_end', fourwall.date_end)
        _id = fourwall.idfourwall
        _row = $(relatedTarget).parents('tr')[0]
        _idproject = fourwall.idproject
    });

    $('#modalUpdateNexus').on('show.bs.modal', function (event) {
        var relatedTarget = event.relatedTarget
        var nexu = nexus.filter(x => x.idnexus === JSON.parse($(relatedTarget).attr('value')))[0]
        /* set data on inputs */
        $('input[name="codigo_alp"').val(nexu.idproject)
        $('input[name="point_red_nexus"').val(nexu.network_point)
        $('input[name="cost_nexus"').val(nexu.cost)
        _id = nexu.idnexus
        _row = $(relatedTarget).parents('tr')[0]
        _idproject = nexu.idproject
    });

    $('#modalUpdateHp').on('show.bs.modal', function (event) {
        var relatedTarget = event.relatedTarget
        var hp = hps.filter(x => x.idhp === JSON.parse($(relatedTarget).attr('value')))[0]
        /* set data on inputs */
        $('input[name="codigo_alp"').val(hp.idproject)
        $('input[name="equip_hp"').val(hp.equipment)
        $('input[name="serie_hp"').val(hp.serie)
        $('input[name="cost_hp"').val(hp.cost)
        setDate('date_start', hp.date_start)
        if (hp.date_end !== null) setDate('date_end', hp.date_end)
        _id = hp.idhp
        _row = $(relatedTarget).parents('tr')[0]
        _idproject = hp.idproject
    });

    /* on hide fourwall modal */
    $('#modalUpdateFourwall').on('hide.bs.modal', function () { $('input[name="codigo_alp"').attr('disabled', true) })
    $('#modalUpdateNexus').on('hide.bs.modal', function () { $('input[name="codigo_alp"').attr('disabled', true) })
    $('#modalUpdateHp').on('hide.bs.modal', function () { $('input[name="codigo_alp"').attr('disabled', true) })

    /* Set date on input when show modal */
    function setDate(input, date) {
        var da = new Date(date)
        $('input[name="' + input + '"').val(da.toLocaleDateString('en-GB'))
    }

    /* click submit data on click save */
    $('#btn-update-fourwall').on('click', function () { $('#btn-sumbit-fourwall').trigger('click') })
    $('#btn-update-nexus').on('click', function () { $('#btn-sumbit-nexus').trigger('click') })
    $('#btn-update-hp').on('click', function () { $('#btn-sumbit-hp').trigger('click') })

    /* on send form  Fourwall*/
    $('.form-update-fourwall').on('submit', function (e) {
        e.preventDefault()

        var id = $('input[name="codigo_alp"]').val()
        if (id === '' || JSON.parse(id) !== _idproject) return $('#btn-succes-error').trigger('click')

        var form = $(this).serializeArray()
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() },
            url: '/maintenance/costs/fourwall/update/' + _id,
            type: 'PUT',
            data: {
                'equipment': form.filter(x => x.name == 'equipment_fourwall')[0].value,
                'serie': form.filter(x => x.name == 'serie_fourwall')[0].value,
                'cost': form.filter(x => x.name == 'cost_fourwall')[0].value,
                'date_start': form.filter(x => x.name == 'date_start')[0].value,
                'date_end': form.filter(x => x.name == 'date_end')[0].value === '' ? null : form.filter(x => x.name == 'date_end_fourwall')[0].value
            },
            beforeSend: function () {
                $('#btn-succes-loading').trigger('click')
            }
        }).then(function (response) {
            $('#btn-succes').trigger('click')
            response_date_start = reestructureDate(response.date_start)
            response_date_end = response.date_end === null ? null : reestructureDate(response.date_end)
            var rowData = $.dataTableInit.row(_row).data()
            rowData[3] = response.equipment
            rowData[4] = response.serie
            rowData[5] = `$ ${response.cost}`
            rowData[6] = `${response_date_start.toLocaleDateString('es-CL')}`.replaceAll('-', '/')
            rowData[7] = response.date_end === null ? 'N.E.' : `${response_date_end.toLocaleDateString('es-CL')}`.replaceAll('-', '/')
            $.dataTableInit.row(_row).data(rowData)
            fourwalls = fourwalls.map(x => {
                if (x.idfourwall === _id) {
                    x.equipment = response.equipment
                    x.serie = response.serie
                    x.cost = response.cost
                    x.date_start = response_date_start.toLocaleDateString('en-US')
                    x.date_end = response_date_end === null ? null : response_date_end
                }
                return x
            })
        }).catch(function (error) {
            $('#btn-succes-error').trigger('click')
            console.log(error)
        }).always(function () {
            $('#modal-succes-loading').modal('toggle')
            $('#btn-close-loading, .btn-close-loading').trigger('click')
        })
    });

    /* on send form  Nexus*/
    $('.form-update-nexus').on('submit', function (e) {
        e.preventDefault()

        var id = $('input[name="codigo_alp"]').val()
        if (id === '' || JSON.parse(id) !== _idproject) return $('#btn-succes-error').trigger('click')

        var form = $(this).serializeArray()
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() },
            url: '/maintenance/costs/nexus/update/' + _id,
            type: 'PUT',
            data: {
                'network_point': form.filter(x => x.name == 'point_red_nexus')[0].value,
                'cost': form.filter(x => x.name == 'cost_nexus')[0].value
            },
            beforeSend: function () {
                $('#btn-succes-loading').trigger('click')
            }
        }).then(function (response) {
            $('#btn-succes').trigger('click')
            var rowData = $.dataTableInit.row(_row).data()
            rowData[3] = response.network_point
            rowData[4] = `$ ${response.cost}`
            $.dataTableInit.row(_row).data(rowData)
            nexus = nexus.map(x => x.idnexus === _id ? response : x)
        }).catch(function (error) {
            $('#btn-succes-error').trigger('click')
            console.log(error)
        }).always(function () {
            $('#modal-succes-loading').modal('toggle')
            $('#btn-close-loading, .btn-close-loading').trigger('click')
        })

    });

    /* on send form  Hp*/
    $('.form-update-hp').on('submit', function (e) {
        e.preventDefault()

        var id = $('input[name="codigo_alp"]').val()
        if (id === '' || JSON.parse(id) !== _idproject) return $('#btn-succes-error').trigger('click')

        var form = $(this).serializeArray()
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() },
            url: '/maintenance/costs/hp/update/' + _id,
            type: 'PUT',
            data: {
                'equipment': form.filter(x => x.name == 'equip_hp')[0].value,
                'serie': form.filter(x => x.name == 'serie_hp')[0].value,
                'cost': form.filter(x => x.name == 'cost_hp')[0].value,
                'date_start': form.filter(x => x.name == 'date_start')[0].value,
                'date_end': form.filter(x => x.name == 'date_end')[0].value === '' ? null : form.filter(x => x.name == 'date_end')[0].value
            },
            beforeSend: function () {
                $('#btn-succes-loading').trigger('click')
            }
        }).then(function (response) {
            $('#btn-succes').trigger('click')
            response_date_start = reestructureDate(response.date_start)
            response_date_end = response.date_end === null ? null : reestructureDate(response.date_end)
            var rowData = $.dataTableInit.row(_row).data()
            rowData[3] = response.equipment
            rowData[4] = response.serie
            rowData[5] = `$ ${response.cost}`
            rowData[6] = `${response_date_start.toLocaleDateString('es-CL')}`.replaceAll('-', '/')
            rowData[7] = response.date_end === null ? 'N.E.' : `${response_date_end.toLocaleDateString('es-CL')}`.replaceAll('-', '/')
            $.dataTableInit.row(_row).data(rowData)
            hp = hps.map(x => {
                if (x.idhp === _id) {
                    x.equipment = response.equipment
                    x.serie = response.serie
                    x.cost = response.cost
                    x.date_start = response_date_start.toLocaleDateString('en-US')
                    x.date_end = response_date_end === null ? null : response_date_end
                }
                return x
            })
        }).catch(function (error) {
            $('#btn-succes-error').trigger('click')
            console.log(error)
        }).always(function () {
            $('#modal-succes-loading').modal('toggle')
            $('#btn-close-loading, .btn-close-loading').trigger('click')
        })
    });

    /* ------------------------ Delete --------------------------------- */
    /* on click delete fourwall or nexus or hp */
    $('#table-resources-it').on('click', '.btn-delete-fourwall', function () { $('#btn-succes-confirmation').trigger('click'); _id = $(this).attr('value'); _row = $(this).parents('tr')[0]; _type = 'fourwall' })
    $('#table-resources-it').on('click', '.btn-delete-nexus', function () { $('#btn-succes-confirmation').trigger('click'); _id = $(this).attr('value'); _row = $(this).parents('tr')[0]; _type = 'nexus' })
    $('#table-resources-it').on('click', '.btn-delete-hp', function () { $('#btn-succes-confirmation').trigger('click'); _id = $(this).attr('value'); _row = $(this).parents('tr')[0]; _type = 'hp' })

    /* change text modal on click btn-succes-confirmation */
    $('#btn-succes-confirmation').on('click', function () { $('#title_action_perform').text('¿Está seguro de eliminar este registro?') })

    /* on acept delete registration */
    $('#delete-data').on('click', function () {
        var url = null
        switch (_type) {
            case 'fourwall':
                url = '/maintenance/costs/fourwall/update/status/' + _id
                break
            case 'nexus':
                url = '/maintenance/costs/nexus/update/status/' + _id
                break
            case 'hp':
                url = '/maintenance/costs/hp/update/status/' + _id
                break
        }

        if (url === null) return $('#btn-succes-error').trigger('click')

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() },
            url: url,
            type: 'PUT',
            beforeSend: function () {
                $('#btn-succes-loading').trigger('click')
            }
        }).then(function (response) {
            $('#btn-succes').trigger('click')
            switchDelete(response)
        }).catch(function (error) {
            $('#btn-succes-error').trigger('click')
            console.log(error)
        }).always(function () {
            $('#modal-succes-loading').modal('toggle')
            $('#btn-close-loading, .btn-close-loading').trigger('click')
        })
    })

    /* ------------------------- switch between fourwall,nexus,hp on delete  --------------------------------- */
    function switchDelete(response) {
        switch (_type) {
            case 'fourwall':
                var rowData = $.dataTableInit.row(_row).data()
                columns = $.dataTableInit.columns().header().length
                rowData[columns - 2] = `<a class="btn btn-warning btn-sm disabled">
                                    <i class="fas fa-edit"></i>
                                    <span>Editar</span>
                                </a>`
                rowData[columns - 1] = `<a class="btn btn-danger btn-sm disabled">
                                    <i class="fas fa-trash"></i>
                                    <span>Eliminado</span>
                                </a>`
                $.dataTableInit.row(_row).data(rowData)
                $.dataTableInit.order([columns - 1, 'desc']).draw()
                fourwalls = fourwalls.map(x => x.idfourwall === _id ? response : x)
                break;
            case 'nexus':
                var rowData = $.dataTableInit.row(_row).data()
                columns = $.dataTableInit.columns().header().length
                rowData[columns - 2] = `<a class="btn btn-warning btn-sm disabled">
                                    <i class="fas fa-edit"></i>
                                    <span>Editar</span>
                                </a>`
                rowData[columns - 1] = `<a class="btn btn-danger btn-sm disabled">
                                    <i class="fas fa-trash"></i>
                                    <span>Eliminado</span>
                                </a>`
                $.dataTableInit.row(_row).data(rowData)
                $.dataTableInit.order([columns - 1, 'desc']).draw()
                nexus = nexus.map(x => x.idnexus === _id ? response : x)
                break;
            case 'hp':
                var rowData = $.dataTableInit.row(_row).data()
                columns = $.dataTableInit.columns().header().length
                rowData[columns - 2] = `<a class="btn btn-warning btn-sm disabled">
                                    <i class="fas fa-edit"></i>
                                    <span>Editar</span>
                                </a>`
                rowData[columns - 1] = `<a class="btn btn-danger btn-sm disabled">
                                    <i class="fas fa-trash"></i>
                                    <span>Eliminado</span>
                                </a>`
                $.dataTableInit.row(_row).data(rowData)
                $.dataTableInit.order([columns - 1, 'desc']).draw()
                hp = hps.map(x => x.idhp === _id ? response : x)
                break;
        }
    }

    /* ------------------------- reestructure date --------------------------------- */
    function reestructureDate(date) {
        var date_reestructure = date.split('/')
        return new Date(date_reestructure[2], date_reestructure[1] - 1, date_reestructure[0])
    }
});
