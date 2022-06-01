/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*********************************************!*\
  !*** ./resources/js/maintenance/project.js ***!
  \*********************************************/
var _id = null;
var row = null;
$(function () {
  setRequiredInput();
  $('#table-resources-it').on('click', '#btn-edit-project, .btn-update-project', function () {
    row = $(this).parents('tr')[0];
  });
  $('#btn-update-create-project').on('click', function () {
    return $('#btn-sumbit-project').trigger('click');
  });
  $('#btn-create-project').on('click', function () {
    _id = null;
    row = null;
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
        return project.idproject == _id;
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
    var codigo_alp = data[1].value;
    var name = data[2].value;
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': token
      },
      type: _id == null ? 'POST' : 'PUT',
      url: _id == null ? '/maintenance/projects/create' : '/maintenance/projects/update/' + _id,
      data: {
        'idproject': codigo_alp,
        'name': name
      },
      beforeSend: function beforeSend() {
        $('#btn-succes-loading').trigger('click');
      }
    }).then(function (data) {
      $('#btn-succes').trigger('click');

      if (_id != null) {
        var rowData = $.dataTableInit.row(row).data();
        rowData[1] = data.idproject;
        rowData[2] = data.name;
        rowData[3] = "<button class=\"btn btn-warning\" id=\"btn-edit-project\" data-bs-toggle=\"modal\" data-bs-target=\"#modalEditProject\" value=\"".concat(data.idproject, "\">\n                                Editar\n                            </button>");
        rowData[4] = "<button type=\"button\" class=\"btn ".concat(data.is_deleted === false ? 'btn-success' : 'btn-danger', " fs-6 btn-status-sow\"  value=\"").concat(data.idsow, "\">\n                            ").concat(data.is_deleted === false ? 'Activo' : 'Inactivo', "\n                        </button>");
        $.dataTableInit.row(row).data(rowData);
        projects.map(function (project) {
          if (project.idproject == _id) {
            project.name = data.name, project.idproject = data.idproject;
          }

          return project;
        });
      } else {
        var rowData = $.dataTableInit.row(0).data();
        rowData[0] = $.dataTableInit.data().length + 1;
        rowData[1] = data.idproject;
        rowData[2] = data.name;
        rowData[3] = "<button class=\"btn btn-warning\" id=\"btn-edit-project\" data-bs-toggle=\"modal\" data-bs-target=\"#modalEditProject\" value=\"".concat(data.idproject, "\">\n                                Editar\n                            </button>");
        rowData[4] = "<button class=\"btn btn-success btn-update-project state-project-active\" value=\"".concat(data.idproject, "\">\n                                Activo\n                            </button>");
        $.dataTableInit.row.add(rowData).draw();
        projects.push(data);
      }

      _id = null;
    })["catch"](function (data) {
      $('#btn-close-loading').trigger('click');
      $('#btn-succes-error').trigger('click');
      console.log(data.responseJSON);
    }).always(function () {
      $('#modal-succes-loading').modal('toggle');
      $('#btn-close-loading, .btn-close-loading').trigger('click');
    });
  });
  $('#table-resources-it').on('click', '.btn-update-project', function () {
    var btn = $(this);
    _id = btn.val();
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
      },
      type: 'PUT',
      url: '/maintenance/projects/update/status/' + _id,
      data: {
        'is_deleted': $(this).hasClass('btn-danger') ? false : true
      },
      dataType: 'json',
      beforeSend: function beforeSend() {
        $('#btn-succes-loading').trigger('click');
      }
    }).then(function (response) {
      console.log(response.is_deleted);

      if (response.is_deleted === 'false') {
        btn.removeClass('btn-danger');
        btn.addClass('btn-success');
        btn.text('Activo');
      } else {
        btn.removeClass('btn-success');
        btn.addClass('btn-danger');
        btn.text('Inactivo');
      }
    })["catch"](function (error) {
      $('#btn-close-loading').trigger('click');
      $('#btn-succes-error').trigger('click');
      console.log(error);
    }).always(function () {
      $('#btn-close-loading, .btn-close-loading').trigger('click');
      $('#modal-succes-loading').hide();
    });
  });
  /*     $('#delete-data').on('click', function() {
          var data = $('.form-update-project').serializeArray()
          var token = data[0].value
          $.ajax({
              headers: { 'X-CSRF-TOKEN': token },
              type: 'DELETE',
              url: '/projects/delete/' + _id,
              data: {
                  'id': _id
              },
              beforeSend: function() {
                  $('#btn-succes-loading').trigger('click')
              }
          }).then(function(data) {
              $('#btn-succes').trigger('click')
              $.dataTableInit.row(row).remove().draw()
              projects = projects.filter(project => project.idproyecto != _id)
          }).catch(function(data) {
              $('#btn-close-loading').trigger('click')
              $('#btn-succes-error').trigger('click')
              console.log(data.responseJSON)
          }).always(function() {
              $('#modal-succes-loading').modal('toggle')
              $('#btn-close-loading, .btn-close-loading').trigger('click')
          })
      }) */
});
$(function () {
  $('input[name="codigo_alp"]').evitWriteText();
});

function setRequiredInput() {
  $('input[name="codigo_alp"]').attr('required', true);
  $('input[name="codigo_alp"]').attr('readonly', false);
  $('input[name="proyecto"]').attr('required', true);
}

function setDataForm(data) {
  $('input[name="codigo_alp"]').val(data.idproject);
  $('input[name="proyecto"]').val(data.name);
}
/******/ })()
;