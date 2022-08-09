/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***********************************************!*\
  !*** ./resources/js/report/server_summary.js ***!
  \***********************************************/
_id = null;
row = null;
var KEYS = ['SO', 'SQL Server', 'Remote Desktop', 'Office'];
var OLD_SERVERS = servers;
var SPLA_REMOVED = [];
$(function () {
  /* prepare input to double */
  $('input[name="reduction_so"]').evitWriteTextCost();
  $('input[name="reduction_sql"]').evitWriteTextCost();
  $('input[name="reduction_remote"]').evitWriteTextCost();
  $('input[name="reduction_office"]').evitWriteTextCost();
  /* load data on show modal */

  $('#modalEditServer').on('show.bs.modal', function (e) {
    var server_id = $(e.relatedTarget).attr('value');
    _id = server_id;
    row = $(e.relatedTarget).parents('tr')[0];
    var server = servers.filter(function (x) {
      return x.idserver == server_id;
    })[0];

    if (server === undefined || server.idserver === null) {
      $('#btn-succes-error').trigger('click');
      $('#error_text').text('Error al cargar la información');
      return;
    }

    var resource = resources.filter(function (x) {
      return x.idserver == server.idserver;
    })[0];

    if (resource === undefined) {
      resource = {
        resources: '{ "HDD": 0, "SSD": 0, "RAM": 0, "CPU": 0 }'
      };
    }

    resource = JSON.parse(resource.resources);
    $('#server_title').text(server.server_name);
    $('#machine_name').text(server.machine_name);
    $('#hostname').text(server.hostname);
    $('#cpu').text(resource.CPU === undefined ? 0 : resource.CPU);
    $('#ram').text(resource.RAM === undefined ? 0 : resource.RAM);
    var disk = resource.HDD + resource.SSD;
    $('#disk').text(isNaN(disk) ? 0 : disk);
    $('#service').text(server.service);
    /* detect if have sow */

    if (server.sow_name !== undefined && server.sow_name !== null) {
      $('input[name="sow"]').val(server.version + ' ' + server.sow_name + ' ' + server.type);
      $('input[name="sow_id"]').val(server.idsow);
    }
    /* detect if have assign service */


    assign_service = assign_services.filter(function (x) {
      return x.idserver == server.idserver;
    })[0];

    if (assign_service !== undefined) {
      $('input[name="backup"]').prop('checked', assign_service.is_backup);
      /* detec if have additional */

      if (assign_service.is_additional) {
        $('input[name="additional_service"]').prop('checked', assign_service.is_additional);
        remove_disabled_on_assigned_service();
      }
      /* detect if have additional spla */


      if (assign_service.is_additional_spla) {
        $('input[name="additional_spla_service"]').prop('checked', assign_service.is_additional_spla);
        remove_disabled_on_assigned_spla_service();
        assigned_additionals_services_spla(server.idserver);
      }
    }
  });
  /* reset status on hide modal */

  $('#modalEditServer').on('hide.bs.modal', function () {
    $('input[name="sow"]').val('');
    $('input[name="sow_id"]').val('');
    add_disabled_on_assigned_service();
    add_disabled_on_assigned_spla_service();
  });
  /* on switch change */

  $('#switchAditional').on('change', function () {
    checked = $(this).is(':checked');
    if (checked) remove_disabled_on_assigned_service();else add_disabled_on_assigned_service_additional();
  });
  $('#switchLicenseServer').on('change', function () {
    checked = $(this).is(':checked');
    if (checked) remove_disabled_on_assigned_spla_service();else add_disabled_on_assigned_spla_service();
  });
  $('#switchLicenseWindows').on('change', function () {
    checked = $(this).is(':checked');
    switch_license_linux = $('#switchLicenseLinux');
    if (checked) switch_license_linux.prop('checked', false).attr('disabled', true);else switch_license_linux.removeAttr('disabled');
  });
  $('#switchLicenseLinux').on('change', function () {
    checked = $(this).is(':checked');
    switch_license_linux = $('#switchLicenseWindows');
    if (checked) switch_license_linux.prop('checked', false).attr('disabled', true);else switch_license_linux.removeAttr('disabled');
  });
  /* prepare input sow to find sows */

  $('input[name="sow"]').on('keyup', function () {
    var _this = this;

    if ($(this).val().length === 0) return $(this).removeRecomendations();
    var sow_search = sows.filter(function (sow) {
      return sow.name.toLowerCase().includes($(_this).val().toLowerCase()) || sow.version.toLowerCase().includes($(_this).val().toLowerCase()) || sow.type.toLowerCase().includes($(_this).val().toLowerCase());
    });
    if (sow_search.length === 0) return;

    values = function values(element) {
      return element.version + ' ' + element.name + ' ' + element.type;
    };

    span = function span(element) {
      return "<span>" + values(element) + "</span>";
    };

    sow_id = function sow_id(element) {
      return element.idsow;
    };

    $(this).addRecomendations(sow_search, null, this, span, values, null, 5, 'input[name="sow_id"]', sow_id);
  });
  $('input[name="sow"]').on('change paste keyup', function () {
    if ($(this).val() === '') $('input[name="sow_id"]').val('');
  });
  /* on click guardar send form */

  $('#btn-update-create-server').on('click', function () {
    $('button[name="submit_form"]').trigger('click');
  });
  /* Send form and update data from server */

  $('#update_server').on('submit', function (e) {
    e.preventDefault();
    form = $(this).serializeArray();
    json = {
      'server': {
        idsow: form[1].value === '' ? null : form[1].value
      },
      'assign_service': {
        'is_backup': form.filter(function (x) {
          return x.name === 'backup';
        }).length > 0,
        'is_additional': form.filter(function (x) {
          return x.name === 'additional_service';
        }).length > 0,
        'is_windows_license': form.filter(function (x) {
          return x.name === 'windows_license';
        }).length > 0,
        'is_antivirus': form.filter(function (x) {
          return x.name === 'antivirus';
        }).length > 0,
        'is_vcpu': form.filter(function (x) {
          return x.name === 'vcpu';
        }).length > 0,
        'is_linux_license': form.filter(function (x) {
          return x.name === 'linux_license';
        }).length > 0,
        'is_additional_spla': form.filter(function (x) {
          return x.name === 'additional_spla_service';
        }).length > 0
      },
      'assign_spla_licences': {
        'SO': {
          percentage: form.filter(function (x) {
            return x.name === 'reduction_so';
          }).length > 0 ? form.filter(function (x) {
            return x.name === 'reduction_so';
          })[0].value : 0,
          idspla: form.filter(function (x) {
            return x.name === 'SO' && x.value !== '';
          }).length > 0 ? form.filter(function (x) {
            return x.name === 'SO';
          })[0].value : null
        },
        'SQL Server': {
          percentage: form.filter(function (x) {
            return x.name === 'reduction_sql';
          }).length > 0 ? form.filter(function (x) {
            return x.name === 'reduction_sql';
          })[0].value : 0,
          idspla: form.filter(function (x) {
            return x.name === 'SQL Server' && x.value !== '';
          }).length > 0 ? form.filter(function (x) {
            return x.name === 'SQL Server';
          })[0].value : null
        },
        'Remote Desktop': {
          percentage: form.filter(function (x) {
            return x.name === 'reduction_remote';
          }).length > 0 ? form.filter(function (x) {
            return x.name === 'reduction_remote';
          })[0].value : 0,
          idspla: form.filter(function (x) {
            return x.name === 'Remote Desktop' && x.value !== '';
          }).length > 0 ? form.filter(function (x) {
            return x.name === 'Remote Desktop';
          })[0].value : null
        },
        'Office': {
          percentage: form.filter(function (x) {
            return x.name === 'reduction_office';
          }).length > 0 ? form.filter(function (x) {
            return x.name === 'reduction_office';
          })[0].value : 0,
          idspla: form.filter(function (x) {
            return x.name === 'Office' && x.value !== '';
          }).length > 0 ? form.filter(function (x) {
            return x.name === 'Office';
          })[0].value : null
        }
      }
    };
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
      },
      url: '/reports/server/summary/' + _id,
      type: 'POST',
      data: json,
      dataType: 'json',
      beforeSend: function beforeSend() {
        $('#btn-succes-loading').trigger('click');
      }
    }).then(function (response) {
      change_sow_from_data_table_and_update_server(response.server);
      additional = assign_services.filter(function (server) {
        return server.idserver === response.server.idserver;
      })[0];
      if (additional === undefined) assign_services.push(create_additionals(response.assign_services));else assign_services.map(function (x) {
        if (x.idserver === additional.idserver) update_additionals(x, response.assign_services);
      });
      KEYS.forEach(function (key) {
        if (json.assign_spla_licences[key].idspla === null) {
          spla = assign_splas.filter(function (x) {
            return x.type === key && x.idserver === JSON.parse(_id);
          })[0];
          if (spla !== undefined) SPLA_REMOVED.push(spla);
        }
      });
      assign_splas = assign_splas.filter(function (x) {
        return !SPLA_REMOVED.includes(x);
      });
      response.assign_splas.forEach(function (x) {
        spla = assign_splas.filter(function (y) {
          return y.iddiscount === x.iddiscount;
        })[0];
        if (spla === undefined) assign_splas.push(create_additional_spla(x));else assign_splas.map(function (z) {
          if (z.iddiscount === spla.iddiscount) update_additional_spla(z, x);
        });
      });
      _id = null;
      row = null;
      $('#btn-succes').trigger('click');
    })["catch"](function (error) {
      $('#btn-close-loading').trigger('click');
      $('#btn-succes-error').trigger('click');
      console.log(error);
    }).always(function () {
      $('#btn-close-loading, .btn-close-loading').trigger('click');
      $('#modal-succes-loading').hide();
    });
  });
  /* remove disabled if active check input[name="additional_service"] */

  function remove_disabled_on_assigned_service() {
    $('input[name="windows_license"]').removeAttr('disabled');
    $('input[name="antivirus"]').removeAttr('disabled');
    $('input[name="vcpu"]').removeAttr('disabled');
    $('input[name="linux_license"]').removeAttr('disabled');
    if (assign_service === undefined) return;
    $('input[name="windows_license"]').prop('checked', assign_service.is_windows_license !== undefined ? assign_service.is_windows_license : false);
    $('input[name="antivirus"]').prop('checked', assign_service.is_antivirus !== undefined ? assign_service.is_antivirus : false);
    $('input[name="vcpu"]').prop('checked', assign_service.is_vcpu !== undefined ? assign_service.is_vcpu : false);
    $('input[name="linux_license"]').prop('checked', assign_service.is_linux_license !== undefined ? assign_service.is_linux_license : false);
  }
  /* add disabled if not active check input[name="additional_service"] */


  function add_disabled_on_assigned_service() {
    $('input[name="backup"]').prop('checked', false);
    $('input[name="additional_service"]').prop('checked', false);
    $('input[name="additional_spla_service"]').prop('checked', false);
    add_disabled_on_assigned_service_additional();
  }

  function add_disabled_on_assigned_service_additional() {
    $('input[name="windows_license"]').prop('checked', false);
    $('input[name="windows_license"]').attr('disabled', true);
    $('input[name="antivirus"]').prop('checked', false);
    $('input[name="antivirus"]').attr('disabled', true);
    $('input[name="vcpu"]').prop('checked', false);
    $('input[name="vcpu"]').attr('disabled', true);
    $('input[name="linux_license"]').prop('checked', false);
    $('input[name="linux_license"]').attr('disabled', true);
  }
  /* remove disabled if active check input[name="additional_spla_service"] */


  function remove_disabled_on_assigned_spla_service() {
    $('select[name="SO"]').removeAttr('disabled');
    $('input[name="reduction_so"]').removeAttr('disabled');
    $('select[name="SQL Server"').removeAttr('disabled');
    $('input[name="reduction_sql"').removeAttr('disabled');
    $('select[name="Remote Desktop"').removeAttr('disabled');
    $('input[name="reduction_remote"').removeAttr('disabled');
    $('select[name="Office"').removeAttr('disabled');
    $('input[name="reduction_office"').removeAttr('disabled');
  }
  /* add disabled if not active check input[name="additional_service"] */


  function add_disabled_on_assigned_spla_service() {
    $('select[name="SO"]').attr('disabled', true);
    $('select[name="SO"]').prop('selectedIndex', 0);
    $('input[name="reduction_so"').attr('disabled', true);
    $('input[name="reduction_so"').val('0');
    $('select[name="SQL Server"').attr('disabled', true);
    $('select[name="SQL Server"').prop('selectedIndex', 0);
    $('input[name="reduction_sql"').attr('disabled', true);
    $('input[name="reduction_sql"').val('0');
    $('select[name="Remote Desktop"').attr('disabled', true);
    $('select[name="Remote Desktop"').prop('selectedIndex', 0);
    $('input[name="reduction_remote"').attr('disabled', true);
    $('input[name="reduction_remote"').val('0');
    $('select[name="Office"').attr('disabled', true);
    $('select[name="Office"').prop('selectedIndex', 0);
    $('input[name="reduction_office"').attr('disabled', true);
    $('input[name="reduction_office"').val('0');
  }

  function assigned_additionals_services_spla(idserver) {
    assign_spla_licences = assign_splas.filter(function (x) {
      return x.idserver == idserver;
    });
    if (assign_spla_licences === undefined || assign_spla_licences.length === 0) return;
    assign_spla_licences.forEach(function (assign_spla) {
      switch (assign_spla.type) {
        case 'SO':
          /* find option and change to selected*/
          option = $("select[name=\"SO\"] option[value=\"".concat(assign_spla.idspla, "\"]"))[0];
          $(option).prop('selected', true);
          $('input[name="reduction_so"]').val(assign_spla.percentage).text(assign_spla.percentage);
          break;

        case 'SQL Server':
          option = $("select[name=\"SQL Server\"] option[value=\"".concat(assign_spla.idspla, "\"]"))[0];
          $(option).prop('selected', true);
          $('input[name="reduction_sql"]').val(assign_spla.percentage).text(assign_spla.percentage);
          break;

        case 'Remote Desktop':
          option = $("select[name=\"Remote Desktop\"] option[value=\"".concat(assign_spla.idspla, "\"]"))[0];
          $(option).prop('selected', true);
          $('input[name="reduction_remote"]').val(assign_spla.percentage).text(assign_spla.percentage);
          break;

        case 'Office':
          option = $("select[name=\"Office\"] option[value=\"".concat(assign_spla.idspla, "\"]"))[0];
          $(option).prop('selected', true);
          $('input[name="reduction_office"]').val(assign_spla.percentage).text(assign_spla.percentage);
          break;
      }
    });
  }

  function change_sow_from_data_table_and_update_server(server) {
    sow = server.idsow !== null ? sows.filter(function (x) {
      return x.idsow == server.idsow;
    })[0] : '';
    data = $.dataTableInit.row(row).data();
    data[5] = sow === '' ? 'N.A.' : "<span>".concat(sow.version, " ").concat(sow.name, "</span>");
    $.dataTableInit.row(row).data(data);
    servers.map(function (x) {
      if (x.idserver == server.idserver) {
        x.idsow = sow === '' ? null : sow.idsow;
        x.sow_name = sow === '' ? null : sow.name;
        x.type = sow === '' ? null : sow.type;
        x.version = sow === '' ? null : sow.version;
      }
    });
  }

  function update_additionals(old_additional, new_additional) {
    old_additional.is_backup = JSON.parse(new_additional.is_backup);
    old_additional.is_additional = JSON.parse(new_additional.is_additional);
    old_additional.is_additional_spla = JSON.parse(new_additional.is_additional_spla);
    old_additional.is_windows_license = JSON.parse(new_additional.is_windows_license);
    old_additional.is_antivirus = JSON.parse(new_additional.is_antivirus);
    old_additional.is_vcpu = JSON.parse(new_additional.is_vcpu);
    old_additional.is_linux_license = JSON.parse(new_additional.is_linux_license);
    return old_additional;
  }

  function create_additionals(new_additional) {
    additional_service = {
      idserver: JSON.parse(new_additional.idserver),
      is_backup: JSON.parse(new_additional.is_backup),
      is_additional: JSON.parse(new_additional.is_additional),
      is_additional_spla: JSON.parse(new_additional.is_additional_spla),
      is_windows_license: JSON.parse(new_additional.is_windows_license),
      is_antivirus: JSON.parse(new_additional.is_antivirus),
      is_vcpu: JSON.parse(new_additional.is_vcpu),
      is_linux_license: JSON.parse(new_additional.is_linux_license)
    };
    return additional_service;
  }

  function update_additional_spla(old_spla, new_spla) {
    old_spla.percentage = JSON.parse(new_spla.percentage);
    old_spla.idspla = JSON.parse(new_spla.idspla);
    return old_spla;
  }

  function create_additional_spla(new_spla) {
    additional_spla = {
      iddiscount: JSON.parse(new_spla.iddiscount),
      idserver: JSON.parse(new_spla.idserver),
      idspla: JSON.parse(new_spla.idspla),
      percentage: JSON.parse(new_spla.percentage),
      type: new_spla.type
    };
    return additional_spla;
  }
  /* Find client / hostname */


  $('#input-buscar-cliente').on('keyup', function (e) {
    var text = $(this).val();
    if (text === '' && e.key === 'Backslash') servers = OLD_SERVERS;
    $(this).searchData(text, function (text, removeOnTextIsEmptyOrLoadComplete) {
      $.ajax({
        url: '/reports/filter/server/project/name',
        type: 'GET',
        data: {
          'name': text
        }
      }).then(function (response) {
        console.log(response);
        if (response.length === 0) return $('.odd td').html('No se encontraron registros');
        var data = formatResponse(response);
        removeOnTextIsEmptyOrLoadComplete('');
        $.dataTableInit.rows.add(data).draw();
        servers = response;
      })["catch"](function (error) {
        $('.odd td').html('No se encontraron registros');
        console.log(error);
      });
    });
  });
  $('#input-buscar-hostname').on('keyup', function (e) {
    var text = $(this).val();
    $(this).searchData(text, function (text, removeOnTextIsEmptyOrLoadComplete) {
      $.ajax({
        url: '/reports/filter/server/hostname/vmware',
        type: 'GET',
        data: {
          'name': text
        }
      }).then(function (response) {
        if (response.length === 0) return $('.odd td').html('No se encontraron registros');
        var data = formatResponse(response);
        removeOnTextIsEmptyOrLoadComplete('');
        $.dataTableInit.rows.add(data).draw();
      })["catch"](function (error) {
        $('.odd td').html('No se encontraron registros');
        console.log(error);
      });
    });
  });

  function formatResponse(response) {
    return response.map(function (item, index) {
      return {
        0: index + 1,
        1: item.active,
        2: item.alp,
        3: item.project_name,
        4: item.server_name,
        5: "".concat(item.version, " ").concat(item.sow_name, " ").concat(item.type),
        6: "<a data-bs-toggle=\"modal\" class=\"btn btn-warning\" href=\"#modalEditServer\" role=\"button\" value=\"".concat(item.idserver, "\">\n                        Ver Detalle\n                    </a>")
      };
    });
  }
});
/******/ })()
;