/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!******************************************!*\
  !*** ./resources/js/maintenance/cost.js ***!
  \******************************************/
var _id = null;
var row = null;
/* on write input give custom autocomplete obtaining values of the var projects */

$(function () {
  $('#modalCreateFourwall').on('input', 'input[name="codigo_alp"]', function () {
    var _this = this;

    if ($(this).val().length === 0) return;
    var codigo_alp = projects.filter(function (project) {
      return project.idproject.toString().indexOf($(_this).val()) > -1;
    });
    if (codigo_alp.length === 0) return;else if (codigo_alp.length === 1) {
      if (codigo_alp[0].idproject.toString() === $(this).val()) {
        removeDivWithRecomendations();
        return;
      }
    }
    addDivWithRecomendations(codigo_alp, 0);
  });
  $('#modalCreateNexus').on('input', 'input[name="codigo_alp"]', function () {
    var _this2 = this;

    if ($(this).val().length === 0) return;
    var codigo_alp = projects.filter(function (project) {
      return project.idproject.toString().indexOf($(_this2).val()) > -1;
    });
    if (codigo_alp.length === 0) return;else if (codigo_alp.length === 1) {
      if (codigo_alp[0].idproject.toString() === $(this).val()) {
        removeDivWithRecomendations();
        return;
      }
    }
    addDivWithRecomendations(codigo_alp, 1);
  });
  $('#modalCreateHp').on('input', 'input[name="codigo_alp"]', function () {
    var _this3 = this;

    if ($(this).val().length === 0) return;
    var codigo_alp = projects.filter(function (project) {
      return project.idproject.toString().indexOf($(_this3).val()) > -1;
    });
    if (codigo_alp.length === 0) return;else if (codigo_alp.length === 1) {
      if (codigo_alp[0].idproject.toString() === $(this).val()) {
        removeDivWithRecomendations();
        return;
      }
    }
    addDivWithRecomendations(codigo_alp, 2);
  });
  $('input[name="codigo_alp"').on('keydown', function (e) {
    removeDivWithRecomendations();
  });
  $('#modalCreateCost').on('input', 'input[name="codigo_alp"]', function () {
    var _this4 = this;

    if ($(this).val().length === 0) return;
    var codigo_alp = projects.filter(function (project) {
      return project.idproject.toString().indexOf($(_this4).val()) > -1;
    });
    if (codigo_alp.length === 0) return;else if (codigo_alp.length === 1) {
      if (codigo_alp[0].idproject.toString() === $(this).val()) {
        removeDivWithRecomendations();
        return;
      }
    }
    addDivWithRecomendations(codigo_alp, 0);
  });
  $('select[name="cost_type"]').on('change', function () {
    value = $(this).val();
    enableInputs(['serie_fourwall', 'date_start', 'date_end']);

    switch (value) {
      case 'fourwall':
        changeTexts(['Equipo 4wall', 'Serie 4wall', 'Costo 4wall']);
        break;

      case 'nexus':
        changeTexts(['Punto de Red', 'Serie Nexus', 'Costo Nexus']);
        break;

      case 'hp':
        changeTexts(['Equipo HP', 'Serie HP', 'Costo HP']);
        break;
    }
  });
  $('#save_cost').on('click', function () {
    $('#btn-sumbit-cost').trigger('click');
  });
  $('input[name="cost_fourwall"]').evitWriteTextCost();
  $('#form_costs').on('submit', function (e) {
    e.preventDefault();
    /* serialize form */

    var form = $(this).serializeArray();
    var data = {};
    var url = '';
    var type = 0;

    switch ($('select[name="cost_type"]').val()) {
      case 'fourwall':
        data = {
          'idproject': form[1].value,
          'equipment': form[2].value,
          'serie': form[3].value,
          'cost': form[4].value,
          'date_start': form[5].value,
          'date_end': form[6].value == '' ? null : form[6].value
        };
        url = '/maintenance/costs/fourwalls/create';
        type = 0;
        break;

      case 'nexus':
        data = {
          'idproject': form[1].value,
          'network_point': form[2].value,
          'cost': form[3].value,
          'serie': form[4].value,
          'date_start': form[5].value,
          'date_end': form[6].value == '' ? null : form[6].value
        };
        url = '/maintenance/costs/nexus/create';
        type = 1;
        break;

      case 'hp':
        data = {
          'idproject': form[1].value,
          'equipment': form[2].value,
          'serie': form[3].value,
          'cost': form[4].value,
          'date_start': form[5].value,
          'date_end': form[6].value == '' ? null : form[6].value
        };
        url = '/maintenance/costs/hps/create';
        type = 2;
        break;
    }

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
      },
      url: url,
      type: 'POST',
      data: data,
      dataType: 'json',
      beforeSend: function beforeSend() {
        $('#btn-succes-loading').trigger('click');
      }
    }).then(function (response) {
      $('#btn-succes').trigger('click');
      updateCost(response, type);
      $('#form_costs').trigger('reset');
    })["catch"](function (error) {
      $('#btn-succes-error').trigger('click');
      console.log(error);
    }).always(function () {
      $('#modal-succes-loading').modal('toggle');
      $('#btn-close-loading, .btn-close-loading').trigger('click');
    });
  });
  /* Consult cost by month */

  $('#btn-consult-date').on('click', function () {
    var date = $('#date_selected').val();
    $(this).searchData('GET', function (text, removeOnTextIsEmptyOrLoadComplete) {
      $.ajax({
        type: 'GET',
        url: '/maintenance/costs/filter/' + (date === '' ? 'na' : date.replaceAll('/', '-'))
      }).then(function (response) {
        if (response.length === 0) return $('.odd td').html('No se encontraron registros');
        removeOnTextIsEmptyOrLoadComplete('');
        var data = formatResponse(response);
        $.dataTableInit.rows.add(data).draw();
      })["catch"](function (error) {
        $('.odd td').html('No se encontraron registros');
        console.log(error);
      });
    });
  });
  /* verify if input file csv contains file */

  $('#btn-import-csv').on('click', function () {
    var file = $('#fileUnique');
    if (file.val() == '') return alert('Es necesario seleccionar un archivo');
    var formData = new FormData();
    formData.append('file', file[0].files[0], file[0].files[0].name);
    formData.append('upload_file', true);
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
      },
      url: '/maintenance/costs/import',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      timeout: 60000,
      xhr: function xhr() {
        var myXhr = $.ajaxSetup().xhr();

        if (myXhr.upload) {
          myXhr.upload.addEventListener('progress', function (e) {
            var percent = 0;
            var position = e.loaded || e.position;
            var total = e.total;
            var progress_bar_id = '.progress-bar-striped';

            if (e.lengthComputable) {
              percent = Math.round(position * 100 / total);
            }

            $(progress_bar_id).css('width', percent + '%');
            $(progress_bar_id).text(percent + '%');
          }, false);
        }

        return myXhr;
      },
      beforeSend: function beforeSend() {
        $('#btn-succes-loading').trigger('click');
        var hasClass = $('#progress-bar').hasClass('d-none');
        if (hasClass) $('#progress-bar').removeClass('d-none');
      }
    }).then(function (response) {
      /* verify status from response */
      if (response != 200) return $('#btn-succes-error').trigger('click');
      $('#btn-succes').trigger('click');
      $('#modal-succes .modal-body h3').text('Se importaron los datos correctamente');
    })["catch"](function (error) {
      $('#btn-succes-error').trigger('click');
      console.log(error);
    }).always(function () {
      $('#modal-succes-loading').modal('toggle');
      $('#btn-close-loading, .btn-close-loading').trigger('click');
    });
  });
  /* After upload file change text h3 from modal succes */

  $('#modal-succes').on('hidden.bs.modal', function () {
    $('#modal-succes .modal-body h3').text('Cambio realizado con exito');
    $('#fileUnique').val('');
    $('#progress-bar').addClass('d-none');
    $('.progress-bar-striped').css('width', '0%');
    $('.progress-bar-striped').text('0%');
  });
});

function addDivWithRecomendations(codigo_alp, index) {
  removeDivWithRecomendations();
  var div = document.createElement("div");
  div.className = "col-12";
  div.id = "div_recomendations";
  div.style = "position: absolute; padding-top:2.5rem; padding-left:10rem;z-index:100";
  codigo_alp.forEach(function (element) {
    var div_recomendation = document.createElement("div");
    div_recomendation.className = "bg-light p-2 border border-secondary";
    div_recomendation.style = "cursor:pointer; display:block;";
    div_recomendation.innerHTML = "<span>" + element.idproject + " - " + element.name + "</span>";
    div_recomendation.innerHTML += "<input type='hidden' value=" + element.idproject + ">";
    div_recomendation.addEventListener("mouseenter", function (e) {
      var div_target = $(e.target);
      div_target.removeClass("bg-light");
      div_target.addClass("bg-secondary");
    });
    div_recomendation.addEventListener("mouseleave", function (e) {
      var div_target = $(e.target);
      div_target.removeClass("bg-secondary");
      div_target.addClass("bg-light");
    });
    div_recomendation.addEventListener("click", function (e) {
      var div_target = $(e.target);
      if (typeof div_target.find("input").val() === "undefined") div_target = div_target.parent();
      var input = index != 0 ? $('input[name="codigo_alp"]')[index] : $('input[name="codigo_alp"]');
      $(input).val(div_target.find("input").val());
      _id = 1;
      removeDivWithRecomendations();
    });
    div.appendChild(div_recomendation);
  });
  var recomendations = document.getElementsByClassName("recomendations");
  index != 0 ? recomendations[index].appendChild(div) : recomendations[index + 1].appendChild(div);
}
/* function remove recomendation div */


function removeDivWithRecomendations() {
  var recomendations = document.getElementById("div_recomendations");
  if (recomendations == null) return;
  recomendations.remove();
}

function setRequiredInputs(modal) {
  modal.find('input[name="codigo_alp"]').attr('required', true);
  modal.find('input[name="equipment_fourwall"]').attr('required', true);
  modal.find('input[name="serie_fourwall"]').attr('required', true);
  modal.find('input[name="cost_fourwall"]').attr('required', true);
  modal.find('input[name="date_start"]').attr('required', true);
  modal.find('input[name="point_red_nexus"]').attr('required', true);
  modal.find('input[name="cost_nexus"]').attr('required', true);
  modal.find('input[name="equip_hp"]').attr('required', true);
  modal.find('input[name="serie_hp"]').attr('required', true);
  modal.find('input[name="cost_hp"]').attr('required', true);
}
/* Update cost in datatable */


function updateCost(data, type) {
  var index = $.dataTableInit.data().toArray().findIndex(function (element) {
    return element[1] == data.idproject;
  });
  var row = $.dataTableInit.row(index).data();
  var href = $(row[type + 3]).attr('href');
  row[type + 3] = deleteDolarAndHreft(row[type + 3]) + parseFloat(data.cost);
  row[6] = deleteDolar(row[6]) + parseFloat(data.cost);
  row[7] = row[6] * tchange;
  row[type + 3] = "<a href=\"".concat(href, "\">$ ").concat(row[type + 3].toFixed(2), "</a>");
  row[6] = '$' + row[6].toFixed(2);
  row[7] = 'S/.' + row[7].toFixed(2);
  $.dataTableInit.row(index).data(row).draw();
}

function deleteDolarAndHreft(row) {
  var start = row.indexOf('$');
  var end = row.indexOf('</');
  var value = row.substring(start + 1, end);
  return parseFloat(value.replaceAll(' ', ''));
}

function deleteDolar(row) {
  var replace = row.replace('$', '');
  return parseFloat(replace.replaceAll(' ', ''));
}

function changeTexts(texts) {
  $('#first_text').text(texts[0]);
  $('#second_text').text(texts[1] != '' ? texts[1] : $('#second_text').text());
  $('#third_text').text(texts[2]);
}

function disableInputs(inputs) {
  inputs.forEach(function (input) {
    $('input[name="' + input + '"]').attr('disabled', true);
  });
}

function enableInputs(inputs) {
  inputs.forEach(function (input) {
    $('input[name="' + input + '"]').attr('disabled', false);
  });
}

function formatResponse(response) {
  return response.map(function (item, index) {
    return {
      0: index + 1,
      1: item.idproject,
      2: item.name,
      3: item.costfourwall === null ? "<a href=\"/maintenance/costs/fourwall/".concat(item.idproject, "\">0.00</a>") : "<a href=\"/maintenance/costs/fourwall/".concat(item.idproject, "\">$ ").concat(getNumber(item.costfourwalls), "</a>"),
      4: item.costnexus === null ? "<a href=\"/maintenance/costs/nexus/".concat(item.idproject, "\">0.00</a>") : "<a href=\"/maintenance/costs/nexus/".concat(item.idproject, "\">$ ").concat(getNumber(item.costnexus), "</a>"),
      5: item.costhp === null ? "<a href=\"/maintenance/costs/hp/".concat(item.idproject, "\">0.00</a>") : "<a href=\"/maintenance/costs/hp/".concat(item.idproject, "\">$ ").concat(getNumber(item.costhp), "</a>"),
      6: "$ ".concat(getNumber(JSON.parse(item.costfourwalls === null ? 0.00 : item.costfourwalls) + JSON.parse(item.costnexus === null ? 0.00 : item.costnexus) + JSON.parse(item.costhp === null ? 0.00 : item.costhp))),
      7: "S/. ".concat(getNumber((JSON.parse(item.costfourwalls === null ? 0.00 : item.costfourwalls) + JSON.parse(item.costnexus === null ? 0.00 : item.costnexus) + JSON.parse(item.costhp === null ? 0.00 : item.costhp)) * tchange))
    };
  });
}
/* get number two values */


function getNumber(number) {
  return Number(number).toFixed(2);
}
/******/ })()
;