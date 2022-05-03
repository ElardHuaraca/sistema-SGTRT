/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*********************************************!*\
  !*** ./resources/js/maintenance/project.js ***!
  \*********************************************/
var _id = null;
var row = null;
$(function () {
  setRequiredInput();
  $('#table-resources-it').on('click', '#btn-edit-project, .btn-delete-project', function () {
    row = $(this).parents('tr')[0];
  });
  $('#btn-update-create-project').on('click', function () {
    return $('#btn-sumbit-project').trigger('click');
  });
  $('#btn-create-project').on('click', function () {
    _id = null;
    row = null;
  });
  $('#table-resources-it').on('click', '.btn-delete-project', function () {
    _id = $(this).val();
    $('#btn-succes-confirmation').trigger('click');
  });
  $('#btn-sumbit-project').on('click', function () {
    var data = $('.form-update-project');
    var form = data.find('input');
    var valid = form.filter(function () {
      return $(this).val() == '';
    });
    if (valid.length > 0) return;
  });
  $('#modalEditProject').on('show.bs.modal', function (e) {
    var btn = $(e.relatedTarget);
    var modal = $(e.delegateTarget);

    if (btn.attr('id') == 'btn-edit-project') {
      _id = btn.val();
      modal.find('.modal-title').text('Editar Proyecto');
      modal.find('#btn-update-create-project').text('Actualizar');
      var project = projects.find(function (project) {
        return project.idproyecto == _id;
      });
      setDataForm(project);
    } else {
      modal.find('.modal-title').text('Crear Proyecto');
      modal.find('#btn-update-create-project').text('Crear');
      setRequiredInput();
      $('.form-update-project').trigger('reset');
    }
  });
});
$(function () {
  $('.form-update-project').on('submit', function (e) {
    e.preventDefault();
    var data = $(e.delegateTarget).serializeArray();
    var token = data[0].value;
    var name = data[2].value;
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': token
      },
      type: _id == null ? 'POST' : 'PUT',
      url: _id == null ? '/projects/create' : '/projects/update/' + _id,
      data: {
        'name': name
      },
      beforeSend: function beforeSend() {
        $('#btn-succes-loading').trigger('click');
      }
    }).then(function (data) {
      $('#btn-succes').trigger('click');
      projects.map(function (project) {
        if (project.idproyecto == data.idproyecto) {
          project.nombre = data.nombre;
        }

        return project;
      });

      if (data.idproyecto == _id) {
        var rowData = $.dataTableInit.row(row).data();
        rowData[1] = data.idproyecto;
        rowData[2] = data.nombre;
        $.dataTableInit.row(row).data(rowData);
      } else {
        var rowData = $.dataTableInit.row(0).data();
        rowData[0] = $.dataTableInit.data().length + 1;
        rowData[1] = data.idproyecto;
        rowData[2] = data.nombre;
        rowData[3] = "<button class=\"btn btn-warning\" id=\"btn-edit-project\" data-bs-toggle=\"modal\" data-bs-target=\"#modalEditProject\" value=\"".concat(data.idproyecto, "\">\n                                Editar\n                            </button>");
        rowData[4] = "<button class=\"btn btn-danger btn-delete-project\" data-bs-toggle=\"modal\" data-bs-target=\"#modal-succes-confirmation\" value=\"".concat(data.idproyecto, "\">\n                                Eliminar\n                            </button>");
        $.dataTableInit.row.add(rowData).draw();
        projects.push(data);
      }
    })["catch"](function (data) {
      $('#btn-close-loading').trigger('click');
      $('#btn-succes-error').trigger('click');
      console.log(data.responseJSON);
    }).always(function () {
      $('#modal-succes-loading').modal('toggle');
      $('#btn-close-loading, .btn-close-loading').trigger('click');
    });
  });
  $('#delete-data').on('click', function () {
    var data = $('.form-update-project').serializeArray();
    var token = data[0].value;
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': token
      },
      type: 'DELETE',
      url: '/projects/delete/' + _id,
      data: {
        'id': _id
      },
      beforeSend: function beforeSend() {
        $('#btn-succes-loading').trigger('click');
      }
    }).then(function (data) {
      $('#btn-succes').trigger('click');
      $.dataTableInit.row(row).remove().draw();
      projects = projects.filter(function (project) {
        return project.idproyecto != _id;
      });
    })["catch"](function (data) {
      $('#btn-close-loading').trigger('click');
      $('#btn-succes-error').trigger('click');
      console.log(data.responseJSON);
    }).always(function () {
      $('#modal-succes-loading').modal('toggle');
      $('#btn-close-loading, .btn-close-loading').trigger('click');
    });
  });
});

function setRequiredInput() {
  $('input[name="codigo_alp"]').attr('required', true);
  $('input[name="codigo_alp"]').attr('readonly', false);
  $('input[name="proyecto"]').attr('required', true);
}

function setDataForm(data) {
  $('input[name="codigo_alp"]').val(data.idproyecto);
  $('input[name="codigo_alp"]').attr('readonly', true);
  $('input[name="proyecto"]').val(data.nombre);
}
/******/ })()
;