var tabla, tbla_sucursal;  

//Función que se ejecuta al inicio
function init() {

  $("#bloc_Accesos").addClass("menu-open bg-color-191f24");

  $("#mAccesos").addClass("active");

  $("#lUsuario").addClass("active");

  tbla_principal();

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════  
  lista_select2("../ajax/usuario.php?op=select2Trabajador", '#trabajador', null);
  lista_select2("../ajax/ajax_general.php?op=select2_cargo_trabajador", '#cargo_trabajador_per', null);
  lista_select2("../ajax/ajax_general.php?op=select2Sucursal", '#sucursal', null);
  
  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro_trabajador").on("click", function (e) {  $("#submit-form-trabajador").submit(); });
  $("#guardar_registro_sucursal").on("click", function (e) {  $("#submit-form-sucursal").submit(); });

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  $("#trabajador").select2({ templateResult: formatStateTrabajador, theme: "bootstrap4",  placeholder: "Selecione trabajador", allowClear: true, });  

  $("#tipo_documento_per").select2({theme:"bootstrap4", placeholder: "Selecione tipo Doc.", allowClear: true, });
  $("#cargo_trabajador_per").select2({theme:"bootstrap4", placeholder: "Selecione tipo Doc.", allowClear: true, });
  $("#sucursal").select2({theme:"bootstrap4", placeholder: "Selecione Sucursal.", allowClear: true, });

  // restringimos la fecha para no elegir mañana
  no_select_tomorrow('#nacimiento_trab');
  no_select_over_18('#nacimiento_per');
  
  // Formato para telefono
  $("[data-mask]").inputmask(); 
  var estado_cargo = 0 ;
}

function formatStateTrabajador (state) {
  //console.log(state);
  if (!state.id) { return state.text; }
  var baseUrl = state.title != '' ? `../dist/docs/persona/perfil/${state.title}`: '../dist/svg/user_default.svg'; 
  var onerror = `onerror="this.src='../dist/svg/user_default.svg';"`;
  var $state = $(`<span><img src="${baseUrl}" class="img-circle mr-2 w-25px" ${onerror} />${state.text}</span>`);
  return $state;
};

//Función limpiar
function limpiar_form_usuario() {
  $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');
  // Agregamos la validacion
  $("#trabajador").rules('add', { required: true, messages: {  required: "Campo requerido" } });  
  $("#password").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#confirm_password").rules('add', { required: true, messages: {  required: "Campo requerido" } });

  //Select2 trabajador
  lista_select2("../ajax/usuario.php?op=select2Trabajador", '#trabajador', null);

  $("#idusuario").val("");
  $("#trabajador_c").html(`Trabajador <sup class="text-danger">*</sup>`); 
  $("#cargo").val("");;
  $("#login").val("");
  $("#password").val("");
  $("#password-old").val(""); 
  $("#confirm_password").val(""); 
  
  $(".modal-title").html("Agregar Trabajador");    

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function permisos() {
  $("#permisos").html('<i class="fas fa-spinner fa-pulse fa-2x"></i>');
  //Permiso
  $.post(`../ajax/usuario.php?op=permisos&id=`, function (r) {
    r = JSON.parse(r); //console.log(r);
    if (r.status) { $("#permisos").html(r.data); } else { ver_errores(e); }    
  }).fail( function(e) { console.log(e); ver_errores(e); } );
}

function show_hide_form(flag) {
	if (flag == 1)	{		
		$("#mostrar-tabla").show();
    $("#mostrar-form").hide();
    $(".btn-regresar").hide();
    $(".btn-agregar").show();
	}	else	{
		$("#mostrar-tabla").hide();
    $("#mostrar-form").show();
    $(".btn-regresar").show();
    $(".btn-agregar").hide();
	}
}

function cargo_trabajador() { 
  $("#cargo").html("");  
  var id_persona=$("#trabajador").select2('val');
  if (id_persona == '' || id_persona == null ) {  }else{
    $(".charge-cargo").html(`<i class="fas fa-spinner fa-pulse fa-lg text-red"></i>`);
    $.post("../ajax/usuario.php?op=select2_cargo_trabajador", {id_persona : id_persona}, function(e, status) {
      e = JSON.parse(e);
      $("#cargo").html(e.data.cargo);
      $(".charge-cargo").html("");
    });
  }  
}

function validar_usuario(id) {
  $.post("../ajax/usuario.php?op=validar_usuario", {'idusuario':id}, function (e, textStatus, jqXHR) {
    e = JSON.parse(e); console.log(e);
  });
}

//Función Listar
function tbla_principal() {

  tabla = $('#tabla-usuarios').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>", //Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i>', className: "btn bg-gradient-info", action: function ( e, dt, node, config ) { tabla.ajax.reload(); toastr_success('Exito!!', 'Actualizando tabla', 400); } },
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5,6], }, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray", }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5,6], }, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success", }, 
      { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,2,3,4,5,6], }, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger", } ,
      { extend: "colvis", text: `Columnas`, className: "btn bg-gradient-gray", exportOptions: { columns: "th:not(:last-child)", }, },
    ],
    ajax:{
      url: '../ajax/usuario.php?op=tbla_principal',
      type : "get",
      dataType : "json",						
      error: function(e){        
        console.log(e.responseText); ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: 0
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: 1
      if (data[1] != '') { $("td", row).eq(1).addClass("text-center"); }
    },    
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [ 
      //{ targets: [6], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      //{ targets: [12], visible: false, searchable: false },
    ],
  }).DataTable();
}

//Función para guardar o editar
function guardar_y_editar_usuario(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-usuario")[0]);

  $.ajax({
    url: "../ajax/usuario.php?op=guardar_y_editar_usuario",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) { 
      try {
        e = JSON.parse(e); console.log(e);
        if (e.status == true) {
          tabla.ajax.reload(null, false);
          show_hide_form(1); limpiar_form_usuario(); sw_success('Correcto!', "Usuario guardado correctamente." );          
        } else {
          ver_errores(d);
        }
      } catch (err) { console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>'); }             
      $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener( "progress", function (evt) {

        if (evt.lengthComputable) {
          var prct = (evt.loaded / evt.total) * 100;
          prct = Math.round(prct);

          $("#barra_progress_usuario").css({ width: prct + "%", });

          $("#barra_progress_usuario").text(prct + "%");

        }
      }, false );

      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#div_barra_progress_usuario").show();
      $("#barra_progress_usuario").css({ width: "0%",  });
      $("#barra_progress_usuario").text("0%");
    },
    complete: function () {
      $("#div_barra_progress_usuario").hide();
      $("#barra_progress_usuario").css({ width: "0%", });
      $("#barra_progress_usuario").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar(idusuario) {
  $(".tooltip").removeClass("show").addClass("hidde");
  $(".trabajador-name").html(`<i class="fas fa-spinner fa-pulse fa-2x"></i>`);  

  limpiar_form_usuario();  

  $(".modal-title").html("Editar Trabajador");
  $("#trabajador").val("").trigger("change"); 
  $("#trabajador_c").html(`Trabajador <b class="text-danger">(Selecione nuevo) </b>`);
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  // Removemos la validacion
  $("#trabajador").rules('remove', 'required');
  $("#password").rules('remove', 'required');
  $("#confirm_password").rules('remove', 'required');

  show_hide_form(2);

  $("#permisos").html('<i class="fas fa-spinner fa-pulse fa-2x"></i>');

  $.post("../ajax/usuario.php?op=mostrar", { idusuario: idusuario }, function (data, status) {

    data = JSON.parse(data);  console.log(data); 

    $(".trabajador-name").html(` <i class="fas fa-users-cog text-primary"></i> <b class="texto-parpadeante font-size-20px">${data.data.nombres}</b> `);    

    $("#trabajador_old").val(data.data.idpersona);
    // $("#cargo").val(data.data.cargo).trigger("change");
    $("#login").val(data.data.login);
    $("#password-old").val(data.data.password);
    $("#idusuario").val(data.data.idusuario);

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();   
    
    // Cargo
    $.post("../ajax/usuario.php?op=select2_cargo_trabajador", {id_persona : data.data.idpersona}, function(e, status) { e = JSON.parse(e); $("#cargo").val("");$("#cargo").val(e.data.cargo); });

  }).fail( function(e) { console.log(e); ver_errores(e); } );

  //Permiso
  $.post(`../ajax/usuario.php?op=permisos&id=${idusuario}`, function (r) {

    r = JSON.parse(r); console.log(r);

    if (r.status) { $("#permisos").html(r.data); } else { ver_errores(e); }
    //$("#permiso_4").rules('add', { required: true, messages: {  required: "Campo requerido" } });
    
  }).fail( function(e) { console.log(e); ver_errores(e); } );
}

//Función para desactivar registros
function eliminar(idusuario, nombre) {
  
  crud_eliminar_papelera(
    "../ajax/usuario.php?op=desactivar",
    "../ajax/usuario.php?op=eliminar", 
    idusuario, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla.ajax.reload(null, false) },
    false, 
    false, 
    false,
    false
  );
}

// :::::::::::::::::::::::::::::::::::::::::::::::::::: S E C C I O N   T R A B A J A D O R  ::::::::::::::::::::::::::::::::::::::::::::::::::::

// abrimos el navegador de archivos
$("#foto1_i").click(function() { $('#foto1').trigger('click'); });
$("#foto1").change(function(e) { addImage(e,$("#foto1").attr("id"), ) });

function foto1_eliminar() {
	$("#foto1").val("");
	$("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");
	$("#foto1_nombre").html("");
}

function limpiar_form_trabajador() {

  $("#guardar_registro_trabajador").html('Guardar Cambios').removeClass('disabled');

  $("#idpersona_per").val(""); 
  $("#tipo_documento_per").val("null").trigger("change");
  $("#cargo_trabajador_per").val("null").trigger("change");
  $("#id_tipo_persona_per").val("4");

  $("#num_documento_per").val(""); 
  $("#nombre_per").val("");   
  $("#email_per").val(""); 
  $("#telefono_per").val(""); 
  $("#direccion_per").val("");

  $("#nacimiento_per").val("");
  $("#edad_per").val("");
  $(".edad_per").html("0 años.");  

  $("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");
	$("#foto1").val("");
	$("#foto1_actual").val("");  
  $("#foto1_nombre").html(""); 

  // ocultamos el password
  var x = document.getElementById("password"); var y = document.getElementById("confirm_password");
  x.type = "password"; y.type = "password";
  
  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función para guardar o editar
function guardar_y_editar_trabajador(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-trabajador")[0]);
  $("#div_barra_progress_trabajador").show();

  $.ajax({
    url: "../ajax/usuario.php?op=guardar_y_editar_trabajador",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) { 
      try {
        e = JSON.parse(e); console.log(e);
        if (e.status == true) {
          lista_select2("../ajax/usuario.php?op=select2Trabajador", '#trabajador', e.id_tabla);          
          sw_success('Correcto!', "Trabajador guardado correctamente." ); 
          limpiar_form_trabajador();
          $("#modal-agregar-trabajador").modal("hide");           
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>'); }             
      $("#guardar_registro_trabajador").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_trabajador").css({"width": percentComplete+'%'});
          $("#barra_progress_trabajador").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_trabajador").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_trabajador_div").show();
      $("#barra_progress_trabajador").css({ width: "0%",  });
      $("#barra_progress_trabajador").text("0%");
    },
    complete: function () {
      $("#barra_progress_trabajador_div").hide();
      $("#barra_progress_trabajador").css({ width: "0%", });
      $("#barra_progress_trabajador").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });

}

// :::::::::::::::::::::::::::::::::::::::::::::::::::: S E C C I O N   S U C U R S A L  ::::::::::::::::::::::::::::::::::::::::::::::::::::

function show_hide_form_sucursal(flag) {
  if (flag == 1) {
    $('.div_tabla_sucursal').show();
    $('#form-sucursal').hide();

    $('.btn-agregar-sucursal').show();
    $('.btn-regresar-sucursal').hide();
    $('#guardar_registro_sucursal').hide();

    $('#modal-agregar-sucursal .modal-dialog').addClass('modal-xl').removeClass('modal-md');
  } else if( flag == 2) {
    $('.div_tabla_sucursal').hide();
    $('#form-sucursal').show();

    $('.btn-agregar-sucursal').hide();
    $('.btn-regresar-sucursal').show();
    $('#guardar_registro_sucursal').show();

    $('#modal-agregar-sucursal .modal-dialog').addClass('modal-md').removeClass('modal-xl');
  }
}

function limpiar_form_sucursal() {
  $("#sucursal").val("").trigger('change');
  
  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function tabla_sucursal(id, nombre) {

  $('#modal-agregar-sucursal').modal('show');
  $('.titulo-modal-sucursal').html(`Sucursal: ${nombre}`);
  $('#idpersona_suc').val(id);
  $('#guardar_registro_sucursal').hide();

  tbla_sucursal = $('#tabla-sucursal').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>", //Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i>', className: "btn bg-gradient-info", action: function ( e, dt, node, config ) { tbla_sucursal.ajax.reload(); toastr_success('Exito!!', 'Actualizando tabla', 400); } },
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,4], }, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray", }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,4], }, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success", }, 
      { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,2,3,4], }, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger", } ,
      // { extend: "colvis", text: `Columnas`, className: "btn bg-gradient-gray", exportOptions: { columns: "th:not(:last-child)", }, },
    ],
    ajax:{
      url: `../ajax/usuario.php?op=tbla_principal_sucursal&id_persona=${id}`,
      type : "get",
      dataType : "json",						
      error: function(e){        
        console.log(e.responseText); ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: 0
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: 1
      if (data[1] != '') { $("td", row).eq(1).addClass("text-center"); }
    },    
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [ 
      //{ targets: [6], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      //{ targets: [12], visible: false, searchable: false },
    ],
  }).DataTable();
}

//Función para guardar o editar
function guardar_y_editar_sucursal(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-sucursal")[0]);

  $.ajax({
    url: "../ajax/usuario.php?op=guardar_y_editar_sucursal",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) { 
      try {
        e = JSON.parse(e); console.log(e);
        if (e.status == true) {        
          sw_success('Correcto!', "Sucursal guardado correctamente." ); 
          tbla_sucursal.ajax.reload(null, false);
          limpiar_form_sucursal();
          show_hide_form_sucursal(1)
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>'); }             
      $("#guardar_registro_sucursal").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_sucursal").css({"width": percentComplete+'%'}).text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_sucursal").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_sucursal_div").show();
      $("#barra_progress_sucursal").css({ width: "0%",  }).text("0%");
    },
    complete: function () {
      $("#barra_progress_sucursal_div").hide();
      $("#barra_progress_sucursal").css({ width: "0%", }).text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });

}

//Función para desactivar registros
function eliminar_sucursal(idpersona_sucursal, idusuario, nombre, codigo, direccion) { 

  crud_eliminar_papelera(
    "../ajax/usuario.php?op=desactivar_sucursal",
    "../ajax/usuario.php?op=eliminar_sucursal", 
    idpersona_sucursal, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tbla_sucursal.ajax.reload(null, false) },
    function(){ 
      if (localStorage.getItem('nube_id_usuario') == id_usuario ) {
        localStorage.setItem('nube_id_sucursal', idpersona_sucursal);
        localStorage.setItem('nube_nombre_sucursal', nombre);
        localStorage.setItem('nube_codigo_sucusal', codigo);
        localStorage.setItem('nube_direcion_sucursal', direccion);        
    
        // mostramos el nombre en el NAV
        $("#ver-proyecto").html(`<i class="fas fa-store-alt"></i> ${nombre}`);
      } 
    }, 
    false, 
    false,
    false
  );
}

//Función para activar registros
function activar_sucursal(idpersona_sucursal, id_usuario, nombre, codigo, direccion) {
  Swal.fire({
    title: "¿Está Seguro de  Activar esta sucursal?",
    html: `<b class="text-success">${nombre}</b> <br> Los datos que realice el usuario estara sujeto a esta sucursal.`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.get("../ajax/usuario.php?op=activar_sucursal", { idpersona_sucursal: idpersona_sucursal, id_usuario: id_usuario  }, function (e) {
        e = JSON.parse(e);  //console.log(e); 
    
        if (e.status == true) {
          Swal.fire("Activado!", "Tu sucursal ha sido activado.", "success");
          tbla_sucursal.ajax.reload(null, false);

          if (localStorage.getItem('nube_id_usuario') == id_usuario ) {
            localStorage.setItem('nube_id_sucursal', idpersona_sucursal);
            localStorage.setItem('nube_nombre_sucursal', nombre);
            localStorage.setItem('nube_codigo_sucusal', codigo);
            localStorage.setItem('nube_direcion_sucursal', direccion);        

            // mostramos el nombre en el NAV
            $("#ver-proyecto").html(`<i class="fas fa-store-alt"></i> ${nombre}`);
          }
        } else {
          ver_errores(e);
        }
        
      }).fail( function(e) { ver_errores(e); } );      
    }
  });      
}

init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {

  $("#trabajador").on('change', function() { $(this).trigger('blur'); });
  $("#sucursal").on('change', function() { $(this).trigger('blur'); });

  $("#tipo_documento_per").on('change', function() { $(this).trigger('blur'); });
  $("#cargo_trabajador_per").on('change', function() { $(this).trigger('blur'); });  

  $("#form-usuario").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      login:            { required: true, minlength: 4, maxlength: 20,
        remote: {
          url: "../ajax/usuario.php?op=validar_usuario",
          type: "get",
          data: {
            action: function () { return "checkusername";  },
            username: function() { var username = $("#login").val(); return username; },
            idusuario: function() { var idusuario = $("#idusuario").val(); return idusuario; }
          }
        }
      },
      password:         { required: true, minlength: 4, maxlength: 20 },      
      confirm_password: { required:true, equalTo:"#password" },      
      
    },
    messages: {
      login:            { required: "Este campo es requerido.", minlength: "MÍNIMO 4 caracteres.", maxlength: "MÁXIMO 20 caracteres.", remote:"Usuario en uso." },
      password:         { equired: "Campo requerido.", minlength: "MÍNIMO 4 caracteres.", maxlength: "MÁXIMO 20 caracteres.", },
      confirm_password: { required: "Campo requerido.", equalTo: "Repita la misma contraseña porfavor.", success:'darara'},
      
    },
    
    errorElement: "span",

    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error); 
    },

    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid").removeClass("is-valid"); 
    },

    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid").addClass("is-valid");              
    },

    submitHandler: function (e) {
      guardar_y_editar_usuario(e);
    },
  });

  $("#form-trabajador").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      tipo_documento_per: { required: true },
      num_documento_per:  { required: true, minlength: 6, maxlength: 20 },
      nombre_per:         { required: true, minlength: 6, maxlength: 100 },
      email_per:          { email: true, minlength: 10, maxlength: 50 },
      direccion_per:      { minlength: 5, maxlength: 200 },
      telefono_per:       { minlength: 8 },
      cargo_trabajador_per:{ required: true,},
    },
    messages: {
      tipo_documento_per: { required: "Campo requerido.", },
      num_documento_per:  { required: "Campo requerido.", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 20 caracteres.", },
      nombre_per:         { required: "Campo requerido.", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 100 caracteres.", },
      email_per:          { required: "Campo requerido.", email: "Ingrese un coreo electronico válido.", minlength: "MÍNIMO 10 caracteres.", maxlength: "MÁXIMO 50 caracteres.", },
      direccion_per:      { minlength: "MÍNIMO 5 caracteres.", maxlength: "MÁXIMO 200 caracteres.", },
      telefono_per:       { minlength: "MÍNIMO 8 caracteres.", },
      cargo_trabajador_per:{ required: "Campo requerido.", },
    },
        
    errorElement: "span",

    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },

    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid").removeClass("is-valid");
    },

    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid").addClass("is-valid");
    },

    submitHandler: function (e) {
      guardar_y_editar_trabajador(e);
    },
  });

  $("#form-sucursal").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      sucursal : {required: true,},
      
    },
    messages: {
      sucursal: {required: "Este campo es requerido.",},
    },
        
    errorElement: "span",

    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },

    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid").removeClass("is-valid");
    },

    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid").addClass("is-valid");
    },

    submitHandler: function (e) {
      guardar_y_editar_sucursal(e);
    },
  });

  $("#trabajador").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#sucursal").rules('add', { required: true, messages: {  required: "Campo requerido" } });

  $("#tipo_documento_per").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#cargo_trabajador_per").rules('add', { required: true, messages: {  required: "Campo requerido" } });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

function marcar_todos_permiso() {
   
  if ($(`#marcar_todo`).is(':checked')) {
    $('.permiso').each(function(){ this.checked = true; });
    $('.marcar_todo').html('Desmarcar Todo');
  } else {
    $('.permiso').each(function(){ this.checked = false; });
    $('.marcar_todo').html('Marcar Todo');
  }  
}

function sueld_mensual(val_input){
  var sueldo_mensual = $(val_input).val() == null || $(val_input).val() == ''  ? 0 : parseFloat($(val_input).val()) ;
  var sueldo_diario=redondearExp((sueldo_mensual/30), 2);
  var sueldo_horas=redondearExp((sueldo_diario/8), 2);
  $("#sueldo_diario_per").val(sueldo_diario);
}

function ver_password(click) {
  var x = document.getElementById("password"); var y = document.getElementById("confirm_password");
  if (x.type === "password") {
    x.type = "text"; y.type = "text"; $('#icon-view-password').html(`<i class="fa-solid fa-eye-slash text-primary"></i>`); 
    $(click).attr('data-original-title', 'Ocultar contraseña');
  } else {
    x.type = "password"; y.type = "password";  $('#icon-view-password').html(`<i class="fa-solid fa-eye text-primary"></i>`);
    $(click).attr('data-original-title', 'Ver contraseña');
  }

  $('[data-toggle="tooltip"]').tooltip();
}