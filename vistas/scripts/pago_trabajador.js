var tabla; 
var tabla_mes;
var tabla_pagos;
var mes_pago_trabajador = 0;
//Función que se ejecuta al inicio
function init() {

  $("#bloc_Recurso").addClass("menu-open bg-color-191f24");

  $("#mRecurso").addClass("active");

  $("#lAllTrabajador").addClass("active");

  tbla_trabajador();

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  // lista_select2("../ajax/ajax_general.php?op=select2_cargo_trabajador", '#cargo_trabajador', null);
  // lista_select2("../ajax/ajax_general.php?op=select2Trabajador", '#nombre_trabajador', null);
  
  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro_mes").on("click", function (e) {  $("#submit-form-mes").submit(); });  
  $("#guardar_registro_pagos").on("click", function (e) {  $("#submit-form-pagos").submit(); });  

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════  
  $("#tipo_documento").select2({theme:"bootstrap4", placeholder: "Selec. tipo Doc.", allowClear: true, });
  $("#cargo_trabajador").select2({theme:"bootstrap4", placeholder: "Selecione cargo", allowClear: true, });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

init();
// abrimos el navegador de archivos
$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addImageApplication(e,$("#doc1").attr("id")) });

$("#doc2_i").click(function() {  $('#doc2').trigger('click'); });
$("#doc2").change(function(e) {  addImageApplication(e,$("#doc2").attr("id")) });

// Eliminamos el doc 1
function doc1_eliminar() {
	$("#doc1").val("");
	$("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');
	$("#doc1_nombre").html("");
}

function show_hide_table(flag) {
  if (flag == 1) {
    $("#div-tabla-trabajador").show();
    $("#div-tabla-mes-pago").hide();
    $("#div-tabla-pagos").hide();
    $("#btn-agregar-mes").hide();
    $("#btn-regresar").hide();
    $("#btn-regresar-meses").hide();
    $("#btn-regresar-todo").hide();
    $("#btn-agregar-pago").hide();
    $(".nombre_trabajador_view").html("");
    $(".sueldo_trab_view").hide("");
    $(".val_sueldo").html("");
  } else if (flag == 2) {
    $("#div-tabla-trabajador").hide();
    $("#div-tabla-mes-pago").show();
    $("#div-tabla-pagos").hide();
    $("#btn-agregar-mes").show();
    $("#btn-regresar").show();
    $("#btn-regresar-meses").hide();
    $("#btn-regresar-todo").hide();
    $("#btn-agregar-pago").hide();
    $(".sueldo_trab_view").show("");

  } else if (flag == 3) {
    $("#div-tabla-trabajador").hide();
    $("#div-tabla-mes-pago").hide();
    $("#btn-agregar-mes").hide();
    $("#btn-regresar").hide();
    $("#div-tabla-pagos").show();
    $("#btn-regresar-meses").show();
    $("#btn-regresar-todo").show();
    $("#btn-agregar-pago").show();
    $(".sueldo_trab_view").show("");
  }
}

//Función Listar
function tbla_trabajador() {

  tabla=$('#tabla-trabajador').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,9,10,11,12,13,5,3,17,18,14,15,16,], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,9,10,11,12,13,5,3,17,18,14,15,16,], } }, 
      { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,9,10,11,12,13,5,3,17,18,14,15,16,], } },
    ],
    ajax:{
      url: '../ajax/pago_trabajador.php?op=tbla_trabajador',
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);  ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass('text-center'); } 
      // columna: 1
      if (data[1] != '') { $("td", row).eq(1).addClass('text-nowrap'); }
      // columna: pagar
      if (data[6] != '') { $("td", row).eq(6).addClass('text-center'); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [
      { targets: [8, 9, 10, 11, 12, 13, 14, 15, 16,17,18], visible: false, searchable: false, }, 
    ],
  }).DataTable();

}

//Función Listar meses de pago
function tbla_pago_trabajador(idpersona, nombres, sueldo_mensual, cargo) {
  get_year_month();
  $(".val_sueldo").html('S/ '+formato_miles(sueldo_mensual));
  $(".nombre_trabajador_view").html(': '+nombres);
  $("#nombre_trabajador").val(nombres);
  // console.log(idpersona, sueldo_mensual, cargo);
  limpiar_form_pago();
  $("#idpersona").val(idpersona);
  $("#sueldo_mensual").val(sueldo_mensual);
  $("#extraer_cargo").val(cargo);

  show_hide_table(2);

  tabla_mes=$('#tabla-mes-pago').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,1,2,4], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,1,2,4], } }, 
      { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,1,2,4], } },
    ],
    ajax:{
      url: `../ajax/pago_trabajador.php?op=tbla_mes_pago&idpersona=${idpersona}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);  ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass('text-center'); } 
      // columna: 1
      if (data[1] != '') { $("td", row).eq(1).addClass('text-nowrap'); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    footerCallback: function( tfoot, data, start, end, display ) {
      var api1 = this.api(); var total1 = api1.column( 4 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api1.column( 4 ).footer() ).html( `<span class="float-left">S/</span> <span class="float-right">${formato_miles(total1)}</span>` );      
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [
      //{ targets: [], visible: false, searchable: false, }, 
      { targets: [4], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },      

    ],
  }).DataTable();
}

//Función para guardar o editar
function guardar_y_editar_mes_pago(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-mes")[0]);

  $.ajax({
    url: "../ajax/pago_trabajador.php?op=guardaryeditar_mes_pago",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  //console.log(e); 
        if (e.status == true) {	
          Swal.fire("Correcto!", "Guardado correctamente", "success");
          tabla_mes.ajax.reload(null, false); 
          $("#modal-agregar-trabajador").modal("hide"); 
          
        }else{
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_mes").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress").css({"width": percentComplete+'%'});
          $("#barra_progress").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_mes").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress").css({ width: "0%",  });
      $("#barra_progress").text("0%");
    },
    complete: function () {
      $("#barra_progress").css({ width: "0%", });
      $("#barra_progress").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function datos_trabajador(idtrabajador){

  $(".tooltip").removeClass("show").addClass("hidde");

  $('#datostrabajador').html(''+
  '<div class="row" >'+
    '<div class="col-lg-12 text-center">'+
      '<i class="fas fa-spinner fa-pulse fa-6x"></i><br />'+
      '<br />'+
      '<h4>Cargando...</h4>'+
    '</div>'+
  '</div>');

  var verdatos=''; 

  var imagen_perfil =''; btn_imagen_perfil=''; 

  $("#modal-ver-pago_trabajador").modal("show")

  $.post("../ajax/pago_trabajador.php?op=datos_trabajador", { idtrabajador: idtrabajador }, function (e, status) {

    e = JSON.parse(e);  console.log(e);
    
    if (e.status == true) {
      
    
      if (e.data.foto_perfil != '') {

        imagen_perfil=`<img src="../dist/docs/persona/perfil/${e.data.foto_perfil}" alt="" class="img-thumbnail w-130px">`
        
        btn_imagen_perfil=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/persona/perfil/${e.data.foto_perfil}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/persona/perfil/${e.data.foto_perfil}" download="PERFIL ${e.data.nombres}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      
      } else {
        imagen_perfil='No hay imagen';
        btn_imagen_perfil='';
      }

      verdatos=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th rowspan="3" class="text-center">${imagen_perfil}<br>${btn_imagen_perfil} </th>
                  <td> <b>Nombre: </b>${e.data.nombres}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                 <td> <b>Cargo: </b>${e.data.cargo}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <td> <b>${e.data.tipo_documento}: </b>${e.data.numero_documento}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Sueldo mensual </th>
                  <td>${e.data.sueldo_mensual}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Sueldo diario </th>
                  <td>${e.data.sueldo_diario}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Banco</th>
                  <td>${e.data.banco}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>cuenta bancaria</th>
                  <td>${e.data.cuenta_bancaria}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>cci</th>
                  <td>${e.data.cci}</td>
                </tr>
                
              </tbody>
            </table>
          </div>
        </div>
      </div>`;
    
      $("#datostrabajador").html(verdatos);

    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

/* =========================== S E C C I O N   DE T A L L E   D E   P A G O S =========================== */
//Función limpiar
function limpiar_form_pago() {
       
  $("#idpago_trabajador").val(""); 
  $("#monto").val("");
  $("#fecha_pago").val("");
  $("#descripcion").val("");

  $("#doc_old_1").val("");
  $("#doc1").val("");  
  $('#doc1_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc1_nombre').html("");

  
  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function ver_desglose_de_pago(idmes_pago_trabajador,nombre_mes) {
  mes_pago_trabajador = idmes_pago_trabajador;
  $("#idmes_pago_trabajador_p").val(idmes_pago_trabajador);
  $("#nombre_mes").val(nombre_mes);
  show_hide_table(3);

  tabla_pagos=$('#tabla-ingreso-pagos').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,4], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,4], } }, 
      { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,2,3,4], } },
    ],
    ajax:{
      url: `../ajax/pago_trabajador.php?op=listar_pago&idmes_pago_trabajador=${idmes_pago_trabajador}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);  ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass('text-center'); } 
      // columna: 1
      if (data[1] != '') { $("td", row).eq(1).addClass('text-nowrap'); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    footerCallback: function( tfoot, data, start, end, display ) {
      var api1 = this.api(); var total1 = api1.column( 3 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api1.column( 3 ).footer() ).html( `<span class="float-left">S/</span> <span class="float-right">${formato_miles(total1)}</span>` );       
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [
      //{ targets: [], visible: false, searchable: false, }, 
      { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      { targets: [3], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
    ],
  }).DataTable();

}

//Función para guardar o editar
function guardar_y_editar_pago(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-pagos-trabajdor")[0]);

  $.ajax({
    url: "../ajax/pago_trabajador.php?op=guardar_editar_pago",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  //console.log(e); 
        if (e.status == true) {	
          Swal.fire("Correcto!", "Guardado correctamente", "success");
          tabla_pagos.ajax.reload(null, false);  
          tabla_mes.ajax.reload(null, false);               
          limpiar_form_pago();
          $("#modal-agregar-pago-trabajdor").modal("hide"); 
          
        }else{
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_mes").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress").css({"width": percentComplete+'%'});
          $("#barra_progress").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_mes").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress").css({ width: "0%",  });
      $("#barra_progress").text("0%");
    },
    complete: function () {
      $("#barra_progress").css({ width: "0%", });
      $("#barra_progress").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

// mostramos los datos para editar
function mostrar_pago(idpago_trabajador) {

  limpiar_form_pago();  

  $("#cargando-3-fomulario").hide();
  $("#cargando-4-fomulario").show();

  $("#modal-agregar-pago-trabajdor").modal("show")

  $.post("../ajax/pago_trabajador.php?op=mostrar_pago", { idpago_trabajador: idpago_trabajador }, function (e, status) {

    e = JSON.parse(e);  console.log(e);   

    if (e.status == true) {         
      
      $("#idpago_trabajador").val(e.data.idpago_trabajador);      
      $("#idmes_pago_trabajador_p").val(e.data.idmes_pago_trabajador);      
      $("#nombre_mes").val(e.data.nombre_mes);
      $("#monto").val(e.data.monto);
      $("#fecha_pago").val(e.data.fecha_pago);
      $("#descripcion").val(e.data.descripcion);
       
    
      if (e.data.comprobante == "" || e.data.comprobante == null  ) {

        $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
    
        $("#doc1_nombre").html('');
    
        $("#doc_old_1").val(""); $("#doc1").val("");
    
      } else {
    
        $("#doc_old_1").val(e.data.comprobante); 
    
        $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(e.data.comprobante)}</i></div></div>`);
        
        // cargamos la imagen adecuada par el archivo
        $("#doc1_ver").html(doc_view_extencion(e.data.comprobante,'pago_trabajador', 'comprobante', '100%', '210' ));
              
      }


      $("#cargando-3-fomulario").show();
      $("#cargando-4-fomulario").hide();

    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

//Función para desactivar registros
function eliminar_pago(idpago_trabajador, descripcion) {

  crud_eliminar_papelera(
    "../ajax/pago_trabajador.php?op=desactivar_pago",
    "../ajax/pago_trabajador.php?op=eliminar_pago", 
    idpago_trabajador, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${descripcion}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla_pagos.ajax.reload(null, false); tabla_mes.ajax.reload(null, false);},
    false, 
    false, 
    false,
    false
  );
 
}


// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {   

  $("#form-mes").validate({
    rules: {
      idpersona:  { required: true },
      mes:        { required: true },
      anio:       { required: true },
    },
    messages: {
      idpersona:  { required: "Campo requerido.", },
      mes:        { required: "Campo requerido.", },
      anio:       { required: "Campo requerido.", },
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
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      guardar_y_editar_mes_pago(e);
    },
  });

  $("#form-pagos-trabajdor").validate({
    rules: {
      fecha_pago:{ required: true},
      monto:     { required: true},
    },
    messages: {
      fecha_pago:{ required: "Campo requerido seleccione una fecha dentro del rango.", },
      monto:     { required: "Campo requerido.", },
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
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      guardar_y_editar_pago(e);
    },
  });

});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

function sueld_mensual(){

  var sueldo_mensual = $('#sueldo_mensual').val()

  var sueldo_diario=(sueldo_mensual/30).toFixed(1);

  var sueldo_horas=(sueldo_diario/8).toFixed(1);

  $("#sueldo_diario").val(sueldo_diario);

}

function extraer_sueldo_trabajador() {
  $('#sueldo_mensual').val(""); 
  $('#extraer_cargo').val("");
  if ($('#nombre_trabajador').select2("val") == null || $('#nombre_trabajador').select2("val") == '') { 
    $('.btn-editar-cliente').addClass('disabled').attr('data-original-title','Seleciona un cliente');
  } else { 
   
    var sueldo_trabajador =  $('#nombre_trabajador').select2('data')[0].element.attributes.sueldo_mensual.value;
    var cargo_trabajador =  $('#nombre_trabajador').select2('data')[0].element.attributes.cargo_trabajador.value;

    $("#sueldo_mensual").val(sueldo_trabajador);    

    $("#extraer_cargo").val(cargo_trabajador);    
  }  
}

function extraer_nombre_mes() {
  var fecha = $('#fecha_pago').val(); 
  if (fecha == '' || fecha == null) { } else {
    $('#nombre_mes').val();
  }    
}

//funcion para obtener mes y el año actual
function get_year_month() {
  var fecha = new Date();
  var year = fecha.getFullYear();
  var mesActual = new Intl.DateTimeFormat('es-ES', { month: 'long'}).format(new Date());
  var correcion_mes =mesActual.charAt(0).toUpperCase() + mesActual.slice(1);
  $("#anio").val(year);
  $("#mes").val(correcion_mes);
}

// ver imagen grande de la persona
function ver_img_persona(file, nombre) {
  $('.foto-persona').html(nombre);
  $(".tooltip").removeClass("show").addClass("hidde");
  $("#modal-ver-perfil-persona").modal("show");
  $('#perfil-persona').html(`<span class="jq_image_zoom"><img class="img-thumbnail" src="${file}" onerror="this.src='../dist/svg/404-v2.svg';" alt="Perfil" width="100%"></span>`);
  $('.jq_image_zoom').zoom({ on:'grab' });
}

