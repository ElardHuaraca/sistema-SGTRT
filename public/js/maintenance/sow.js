/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*****************************************!*\
  !*** ./resources/js/maintenance/sow.js ***!
  \*****************************************/
var _id = null;
var type = null;
var row = null;
var tab = 0;
/* Controll tab navigation on SOW */

$(function () {
  $('#sow-list a').on('click', function (e) {
    e.preventDefault();
    $(this).tab('show');
  });
  $('#sow-list a').on('shown.bs.tab', function () {
    if ($(this).attr('href') == '#bronce') {
      $('#btn-next').text('Siguiente');
      tab = 0;
    } else if ($(this).attr('href') == '#silver') {
      $('#btn-next').text('Siguiente');
      tab = 1;
    } else if ($(this).attr('href') == '#gold') {
      $('#btn-next').text('Guardar');
      tab = 2;
    }
  });
});
/* final all button and remove class disabled */

$(function () {
  /* Cahnge input to required */
  $('#modalCreateEditSow').on('show.bs.modal', function () {
    $('#form-bronce').find('input').each(function () {
      $(this).prop('required', true);
      if ($(this).attr('id') !== 'version_sow' && $(this).attr('id') !== 'name_sow') $(this).evitWriteTextCost();
    });
    $('#form-silver').find('input').each(function () {
      $(this).prop('required', true);
      $(this).evitWriteTextCost();
    });
    $('#form-gold').find('input').each(function () {
      $(this).prop('required', true);
      $(this).evitWriteTextCost();
    });
    $('#version_sow').evitWriteTextOnVersion();
  });
  /* Disabled navigation tab on hide modal*/

  $('#modalCreateEditSow').on('hide.bs.modal', function () {
    if ($('#content-mod #btn-next').text() === 'Actualizar') {
      resetForms();
      tab = 0;
      type = null;
      $('#content-mod #btn-next').text('Siguiente');
    }

    $('#sow-list a[href="#bronce"]').tab('show');
    $('#sow-list a[href="#bronce"]').removeClass('text-black');
    $('#sow-list a[href="#bronce"]').removeClass('disabled');
    $('#sow-list a[href="#silver"]').addClass('disabled');
    $('#sow-list a[href="#silver"]').removeClass('text-black');
    $('#sow-list a[href="#gold"]').addClass('disabled');
  });
});
$(function () {
  $('#btn-next').on('click', function (e) {
    if (!($(e.currentTarget).text() === 'Actualizar')) {
      if (!$('#sow-list a[href="#bronce"]').hasClass('text-black') || tab == 0) {
        $('#btn-submit-bronce').trigger('click');
      } else if (!$('#sow-list a[href="#silver"]').hasClass('text-black') || tab == 1) {
        $('#btn-submit-silver').trigger('click');
      } else {
        $('#btn-submit-gold').trigger('click');
      }
    } else {
      switch (type) {
        case 'BRONCE':
          $('#btn-submit-bronce').trigger('click');
          break;

        case 'SILVER':
          $('#btn-submit-silver').trigger('click');
          break;

        case 'GOLD':
          $('#btn-submit-gold').trigger('click');
          break;
      }
    }
  });
  $('#form-bronce').on('submit', function (e) {
    e.preventDefault();

    if (type === 'BRONCE') {
      var serialice = $(this).serializeArray();
      data = {
        version: $('#version_sow').val(),
        name: $('#name_sow').val(),
        type: 'BRONCE',
        cost_cpu: serialice[0].value,
        cost_ram: serialice[1].value,
        cost_hdd_mechanical: serialice[2].value,
        cost_hdd_solid: 0,
        cost_mo_clo_sw_ge: serialice[3].value,
        cost_mo_cot: serialice[4].value,
        cost_cot_monitoring: serialice[5].value,
        cost_license_vssp: 0,
        cost_license_vssp_srm: 0,
        cost_link: 0,
        add_cost_antivirus: serialice[6].value,
        add_cost_win_license_cpu: serialice[7].value,
        add_cost_win_license_ram: serialice[8].value,
        add_cost_linux_license: serialice[9].value,
        cost_backup_db: serialice[10].value
      };
      updateSow(data);
    } else {
      removeDisabledAndShow('#sow-list a[href="#silver"]');
      $('#sow-list a[href="#bronce"]').addClass('text-black');
    }
  });
  $('#form-silver').on('submit', function (e) {
    e.preventDefault();

    if (type === 'SILVER') {
      var serialice = $(this).serializeArray();
      var sow = sows.filter(function (sow) {
        return sow.idsow === _id;
      })[0];
      data = {
        version: sow.version,
        name: sow.name,
        type: 'SILVER',
        cost_cpu: serialice[0].value,
        cost_ram: serialice[1].value,
        cost_hdd_mechanical: serialice[2].value,
        cost_hdd_solid: serialice[3].value,
        cost_mo_clo_sw_ge: serialice[4].value,
        cost_mo_cot: serialice[5].value,
        cost_cot_monitoring: serialice[6].value,
        cost_license_vssp: serialice[7].value,
        cost_license_vssp_srm: 0,
        cost_link: 0,
        add_cost_antivirus: serialice[8].value,
        add_cost_win_license_cpu: serialice[9].value,
        add_cost_win_license_ram: serialice[10].value,
        add_cost_linux_license: serialice[11].value,
        cost_backup_db: serialice[12].value
      };
      updateSow(data);
    } else {
      removeDisabledAndShow('#sow-list a[href="#gold"]');
      $('#sow-list a[href="#silver"]').addClass('text-black');
    }
  });
  $('#form-gold').on('submit', function (e) {
    e.preventDefault();

    if (type === 'GOLD') {
      var serialice = $(this).serializeArray();
      var sow = sows.filter(function (sow) {
        return sow.idsow === _id;
      })[0];
      data = {
        version: sow.version,
        name: sow.name,
        type: 'GOLD',
        cost_cpu: serialice[0].value,
        cost_ram: serialice[1].value,
        cost_hdd_mechanical: serialice[2].value,
        cost_hdd_solid: serialice[3].value,
        cost_mo_clo_sw_ge: serialice[4].value,
        cost_mo_cot: serialice[5].value,
        cost_cot_monitoring: serialice[6].value,
        cost_license_vssp: serialice[7].value,
        cost_license_vssp_srm: serialice[8].value,
        cost_link: serialice[9].value,
        add_cost_antivirus: serialice[10].value,
        add_cost_win_license_cpu: serialice[11].value,
        add_cost_win_license_ram: serialice[12].value,
        add_cost_linux_license: serialice[13].value,
        cost_backup_db: serialice[14].value
      };
      updateSow(data);
    } else {
      saveNewSow();
    }
  });
});

function saveNewSow() {
  var bronce = $('#form-bronce').serializeArray();
  var silver = $('#form-silver').serializeArray();
  var gold = $('#form-gold').serializeArray();
  var data = {
    bronce: {
      version: $('#version_sow').val(),
      name: $('#name_sow').val(),
      type: 'BRONCE',
      cost_cpu: bronce[0].value,
      cost_ram: bronce[1].value,
      cost_hdd_mechanical: bronce[2].value,
      cost_hdd_solid: 0,
      cost_mo_clo_sw_ge: bronce[3].value,
      cost_mo_cot: bronce[4].value,
      cost_cot_monitoring: bronce[5].value,
      cost_license_vssp: 0,
      cost_license_vssp_srm: 0,
      cost_link: 0,
      add_cost_antivirus: bronce[6].value,
      add_cost_win_license_cpu: bronce[7].value,
      add_cost_win_license_ram: bronce[8].value,
      add_cost_linux_license: bronce[9].value,
      cost_backup_db: bronce[10].value
    },
    silver: {
      version: $('#version_sow').val(),
      name: $('#name_sow').val(),
      type: 'SILVER',
      cost_cpu: silver[0].value,
      cost_ram: silver[1].value,
      cost_hdd_mechanical: silver[2].value,
      cost_hdd_solid: silver[3].value,
      cost_mo_clo_sw_ge: silver[4].value,
      cost_mo_cot: silver[5].value,
      cost_cot_monitoring: silver[6].value,
      cost_license_vssp: silver[7].value,
      cost_license_vssp_srm: 0,
      cost_link: 0,
      add_cost_antivirus: silver[8].value,
      add_cost_win_license_cpu: silver[9].value,
      add_cost_win_license_ram: silver[10].value,
      add_cost_linux_license: silver[11].value,
      cost_backup_db: silver[12].value
    },
    gold: {
      version: $('#version_sow').val(),
      name: $('#name_sow').val(),
      type: 'GOLD',
      cost_cpu: gold[0].value,
      cost_ram: gold[1].value,
      cost_hdd_mechanical: gold[2].value,
      cost_hdd_solid: silver[3].value,
      cost_mo_clo_sw_ge: gold[4].value,
      cost_mo_cot: gold[5].value,
      cost_cot_monitoring: gold[6].value,
      cost_license_vssp: gold[7].value,
      cost_license_vssp_srm: gold[8].value,
      cost_link: gold[9].value,
      add_cost_antivirus: gold[10].value,
      add_cost_win_license_cpu: gold[11].value,
      add_cost_win_license_ram: gold[12].value,
      add_cost_linux_license: gold[13].value,
      cost_backup_db: gold[14].value
    }
  };
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('input[name="_token"]').val()
    },
    url: '/maintenance/sows/create',
    type: 'POST',
    data: data,
    dataType: 'json',
    beforeSend: function beforeSend() {
      $('#btn-succes-loading').trigger('click');
    }
  }).then(function (response) {
    addSowsToDataTable(response);
    $('#btn-succes').trigger('click');
    resetForms();
  })["catch"](function (error) {
    $('#btn-close-loading').trigger('click');
    $('#btn-succes-error').trigger('click');
    console.log(error);
  }).always(function () {
    $('#modal-succes-loading').hide();
    $('#btn-close-loading, .btn-close-loading').trigger('click');
  });
}

function updateSow(data) {
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('input[name="_token"]').val()
    },
    url: '/maintenance/sows/update/' + _id,
    type: 'PUT',
    data: data,
    dataType: 'json',
    beforeSend: function beforeSend() {
      $('#btn-succes-loading').trigger('click');
    }
  }).then(function (response) {
    addSowsToDataTable(response);
    $('#btn-succes').trigger('click');
    _id = null;
    row = null;
    type = null;
    resetForms();
  })["catch"](function (error) {
    $('#btn-close-loading').trigger('click');
    $('#btn-succes-error').trigger('click');
    console.log(error);
  }).always(function () {
    $('#modal-succes-loading').hide();
    $('#btn-close-loading, .btn-close-loading').trigger('click');
  });
}

function addSowsToDataTable(response) {
  sows = response;
  var reformat = response.map(function (item, index) {
    return {
      0: index + 1,
      1: item.version,
      2: item.name + ' ' + item.type + ' ' + item.version,
      3: $.refactorDateMinutes(item.created_at),
      4: $.refactorDateMinutes(item.updated_at),
      5: "<button class=\"btn btn-warning\" id=\"btn-edit-sow\" data-bs-toggle=\"modal\" data-bs-target=\"#modalCreateEditSow\" value=\"".concat(item.idsow, "\">\n                    Editar\n                </button>"),
      6: "<button class=\"btn ".concat(item.is_deleted ? 'btn-danger' : 'btn-success', " fs-6 btn-status-sow\" id=\"btn-delete-sow\" value=\"").concat(item.idsow, "\">\n                    ").concat(item.is_deleted ? 'Inactivo' : 'Activo', "\n                </button>")
    };
  });
  $.dataTableInit.clear().draw();
  $.dataTableInit.rows.add(reformat).draw();
}
/* capitalize only first character and end lower */


function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
}

$(function () {
  $('#table-resources-it').on('click', '#btn-edit-sow, .btn-status-sow', function () {
    row = $(this).parents('tr')[0];
  });
  /* set data to form on click edit button */

  $('#modalCreateEditSow').on('show.bs.modal', function (e) {
    var button = $(e.relatedTarget);

    if (button.hasClass('btn-warning') || button.hasClass('btn-status-sow')) {
      var sow = sows.filter(function (sow) {
        return sow.idsow.toString() === button.val();
      })[0];
      _id = sow.idsow;
      type = sow.type;

      if (sow.type === 'BRONCE') {
        $('#form-bronce').find('input').each(function () {
          $(this).val(sow[$(this).attr('name')]);
        });
      } else if (sow.type === 'SILVER') {
        removeDisabledAndShow('#sow-list a[href="#silver"]');
        $('#sow-list a[href="#bronce"]').addClass('disabled');
        $('#form-silver').find('input').each(function () {
          $(this).val(sow[$(this).attr('name')]);
        });
      } else {
        removeDisabledAndShow('#sow-list a[href="#gold"]');
        $('#sow-list a[href="#bronce"]').addClass('disabled');
        $('#form-gold').find('input').each(function () {
          $(this).val(sow[$(this).attr('name')]);
        });
      }

      $('#content-mod #btn-next').text('Actualizar');
    }
  });
});
/* Disable sow */

$(function () {
  $('#table-resources-it').on('click', '.btn-status-sow', function () {
    var btn = $(this);
    var id = btn.val();
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
      },
      url: '/maintenance/sows/update/status/' + id,
      type: 'PUT',
      data: {
        'is_deleted': $(this).hasClass('btn-danger') ? false : true
      },
      beforeSend: function beforeSend() {
        $('#btn-succes-loading').trigger('click');
      }
    }).then(function (response) {
      addSowsToDataTable(response);
    })["catch"](function (error) {
      $('#btn-close-loading').trigger('click');
      $('#btn-succes-error').trigger('click');
      console.log(error);
    }).always(function () {
      $('#btn-close-loading, .btn-close-loading').trigger('click');
      $('#modal-succes-loading').hide();
    });
  });
});

function removeDisabledAndShow(string) {
  var item = $(string);
  item.removeClass('disabled');
  item.tab('show');
}

function resetForms() {
  $('#form-bronce').trigger('reset');
  $('#form-silver').trigger('reset');
  $('#form-gold').trigger('reset');
}
/******/ })()
;