/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!******************************************!*\
  !*** ./resources/js/user/user-manage.js ***!
  \******************************************/
$(function () {
  $('#table-resources-it').on('click', '.state-user-active, .state-user-inactive', function (e) {
    var btn = $(this);
    var id = btn.val();
    var status = $(this).hasClass('btn-success') ? 0 : 1;
    var data = $('.form-update-user');
    var serializeArray = data.serializeArray();
    var token = serializeArray[0].value;
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': token
      },
      type: 'PUT',
      url: '/users/update/status/' + id,
      data: {
        'status': status
      },
      dataType: 'json',
      beforeSend: function beforeSend() {
        $('#btn-succes-loading').trigger('click');
      }
    }).then(function (data) {
      if (data.estado == 1) {
        btn.removeClass('btn-danger');
        btn.addClass('btn-success');
        btn.text('Activo');
      } else {
        btn.removeClass('btn-success');
        btn.addClass('btn-danger');
        btn.text('Inactivo');
      }
    })["catch"](function (data) {
      $('#btn-close-loading').trigger('click');
      $('#btn-succes-error').trigger('click');
      console.log(data.responseJSON);
    }).always(function () {
      $('#modal-succes-loading').modal('toggle');
      $('#btn-close-loading ,.btn-close-loading').trigger('click');
    });
  });
});
var _id = null;
var row = null;
/* create user on click button btn-update-create-user verify input required*/

$(function () {
  /* Get row from dataTable and save in variable */
  $('#table-resources-it').on('click', '#btn-edit-user,.btn-delete-user', function () {
    row = $(this).parents('tr')[0];
  });
  $('#btn-update-create-user').on('click', function () {
    return $('#btn-sumbit-user').trigger('click');
  });
  $('#btn-create-user').on('click', function () {
    _id = null;
    row = null;
  });
  $('.btn-delete-user').on('click', function () {
    _id = $(this).val();
    $('#btn-succes-confirmation').trigger('click');
  });
  /* validate form-update-user */

  $('#btn-sumbit-user').on('click', function () {
    var data = $('.form-update-user');
    var form = data.find('input,select');
    var input_email = document.getElementById('input-email');
    input_email.validity.typeMismatch ? input_email.setCustomValidity('El email no es válido') : input_email.setCustomValidity('');
    var valid = form.filter(function () {
      return $(this).val() == '';
    });
    if (valid.length > 0) return;
  });
  /* Prevent defult on sumbit form */

  $('.form-update-user').on('submit', function (e) {
    e.preventDefault();
    var data = $('.form-update-user').serializeArray();
    var token = data[0].value;
    var id = typeof _id === 'null' ? '' : _id;
    /* get first character of name and only first lastname */

    var name = data[1].value.trim();
    var lastname = data[2].value.trim();
    var usuario = (name.charAt(0) + lastname.split(' ')[0]).toLowerCase();
    var email = data[3].value;
    var phone = data[4].value.replaceAll('-', '');
    var password = data[5].value;
    var rol = data[6].value;
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': token
      },
      type: _id == null ? 'POST' : 'PUT',
      url: _id == null ? '/users/create' : '/users/update/',
      data: {
        'id': id,
        'name': name,
        'lastname': lastname,
        'username': usuario,
        'email': email,
        'phone': phone,
        'password': password,
        'rol': rol
      },
      dataType: 'json',
      beforeSend: function beforeSend() {
        $('#btn-succes-loading').trigger('click');
      }
    }).then(function (data) {
      $('#btn-succes').trigger('click');
      users.map(function (user) {
        if (user.idusuario == data.idusuario) {
          user.nombres = data.nombres;
          user.apellidos = data.apellidos;
          user.usuario = data.usuario;
          user.correo = data.correo;
          user.telefono = data.telefono;
          user.rol = data.rol;
        }

        return user;
      });

      if (data.idusuario == id) {
        var rowData = $.dataTableInit.row(row).data();
        rowData[1] = data.usuario;
        rowData[2] = data.rol;
        $.dataTableInit.row(row).data(rowData);
      } else {
        var rowData = $.dataTableInit.row(0).data();
        rowData[0] = $.dataTableInit.data().length + 1;
        rowData[1] = data.usuario;
        rowData[2] = data.rol;
        /* change value into button betwee rowData[3] and rowData[5] */

        rowData[3] = "<button type=\"button\" class=\"btn btn-success fs-6 state-user-active\" value=\"".concat(data.idusuario, "\">\n                                Activo\n                            </button>");
        rowData[4] = "<button class=\"btn btn-warning\" id=\"btn-edit-user\" ata-bs-toggle=\"modal\" data-bs-target=\" #modalEditUser\" value=\"".concat(data.idusuario, "\">\n                                Editar\n                            </button>");
        rowData[5] = "<button class=\"btn btn-danger\" id=\"btn-delete-user\" value=\"".concat(data.idusuario, "\">\n                                Eliminar\n                            </button>");
        $.dataTableInit.row.add(rowData).draw();
        users.push({
          'idusuario': data.idusuario,
          'nombres': data.nombres,
          'apellidos': data.apellidos,
          'usuario': data.usuario,
          'correo': data.correo,
          'telefono': data.telefono,
          'rol': data.rol
        });
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
    var data = $('.form-update-user').serializeArray();
    var token = data[0].value;
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': token
      },
      type: 'DELETE',
      url: '/users/delete/' + _id,
      dataType: 'json',
      beforeSend: function beforeSend() {
        $('#btn-succes-loading').trigger('click');
      }
    }).then(function (data) {
      $('#btn-succes').trigger('click');
      $.dataTableInit.row(row).remove().draw();
      users = users.filter(function (user) {
        return user.idusuario != _id;
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
/* Permite only numbers in input[type=tel] and add '-' automatic after every three numbers input  */

$(function () {
  $('input[type="tel"]').on('keypress', function (e) {
    /* verify input text is only number*/
    var key = e.key;

    if (key.match(/[0-9]/) === null) {
      e.preventDefault();
    } else {
      var val = $(this).val();

      if (val.length == 3 || val.length == 7) {
        $(this).val(val + '-');
      }
    }
  });
});
/* Change title on modalEditUser */

$(function () {
  $('#modalEditUser').on('show.bs.modal', function (e) {
    var btn = $(e.relatedTarget);
    var modal = $(this);
    $('.modal-title').css('padding-left', '10.5rem');
    setRequiredInput();

    if (btn.attr('id') == 'btn-edit-user') {
      $(this).find('.modal-title').text('Actualizar usuario');
      _id = btn.val();
      var user = users.find(function (user) {
        return user.idusuario == _id;
      });
      modal.find('#btn-update-create-user').text('Actualizar');
      setDataForm(user);
      $('input[name="password"]').attr('required', false);
    } else {
      $(this).find('.modal-title').text('Crear usuario');
      modal.find('#btn-update-create-user').text('Crear');
      $('.form-update-user').trigger('reset');
    }
  });
});

function setDataForm(data) {
  $('input[name="name"]').val(data.nombres);
  $('input[name="lastname"]').val(data.apellidos);
  $('input[name="email"]').val(data.correo);
  /* add '-' after every three numbers*/

  if (data.telefono === null) {
    $('input[name="phone"]').val('');
  } else {
    $('input[name="phone"]').val(data.telefono.toString().replace(/(\d{3})(\d{3})(\d{3})/, '$1-$2-$3'));
  }
  /* $('input[name="phone"]').val(data.telefono) */


  $('select[name="rol"]').val(data.rol).trigger('change');
  $('#input-password').clone().val('').insertAfter('#input-password').prev().remove();
}
/* add reqiored attribute to Input */


function setRequiredInput() {
  $('input[name="name"]').attr('required', true);
  $('input[name="lastname"]').attr('required', true);
  $('input[name="email"]').attr('required', true);
  $('input[name="phone"]').attr('required', true);
  $('select[name="rol"]').attr('required', true);
  $('input[name="password"]').attr('required', true);
}
/******/ })()
;