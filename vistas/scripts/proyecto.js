var tabla; var tabla2; 

//Función que se ejecuta al inicio
function init(){  

  $('#mEscritorio').addClass("active");  

  tbla_principal(1, 'shadow-0px-05rem-1rem-rgb-255-193-7', '.box-proceso');

  tablero(); 
  box_proyecto();
  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/ajax_general.php?op=select2EmpresaACargo", '#empresa_acargo', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro").on("click", function (e) { $("#submit-form-proyecto").submit(); });   

  // mostramos las fechas feriadas
  $.post("../ajax/proyecto.php?op=listar_feriados",  function (data, status) {

    data = JSON.parse(data);  console.log(data);
    var fecha_feriada = [];

    if (data.status == true) {
      $.each(data.data, function (index, value) { fecha_feriada.push(format_d_m_a(value.fecha_feriado)); });

      $('#fecha_inicio').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
  
      // Inicializar - Date picker  
      $('#fecha_inicio').datepicker({ 
        format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto",
        daysOfWeekDisabled: [6], 
        datesDisabled: fecha_feriada,
      });
    } else {
      ver_errores(data);
    }     

  }).fail( function(e) { ver_errores(e); } );

   // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════

  $('#fecha_pago_obrero').select2({ theme: "bootstrap4", placeholder: "Selecione", allowClear: true });
  $('#fecha_valorizacion').select2({ theme: "bootstrap4", placeholder: "Selecione", allowClear: true});
  $('#empresa_acargo').select2({ templateResult: template_sleect2_empresa, placeholder: "Empresa a cargo", allowClear: false});

  $('#fecha_fin').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });

  // Formato para telefono
  $("[data-mask]").inputmask();
}
$('.click-btn-fecha-inicio').on('click', function (e) {$('#fecha_inicio').focus().select(); });

init();

function template_sleect2_empresa (state) {
  //console.log(state);
  if (!state.id) { return state.text; }
  var baseUrl = state.title != '' ? `../dist/svg/${state.title}`: '../dist/svg/user_default.svg'; 
  var onerror = `onerror="this.src='../dist/svg/user_default.svg';"`;
  var $state = $(`<span><img src="${baseUrl}" class="img-circle mr-2 w-25px" ${onerror} />${state.text}</span>`);
  return $state;
};

function validar_permanent() { if ($("#fecha_pago_obrero").select2('val') == null) {  $("#definiendo").prop('checked', false); } }

function permanente_pago_obrero() {

  if ($("#fecha_pago_obrero").select2('val') == null) {
    toastr_error('Selecione un pago obrero:','<ul> <li>Quincenal</li> <li>Semanal</li> </ul>', 700);
    //toastr.error(`Selecione un pago obrero: <ul> <li>Quincenal</li> <li>Semanal</li> </ul>`);

    if($('#definiendo').is(':checked')){ 
      $("#definiendo").prop('checked', false); 
    }else{ 
      $("#definiendo").prop('checked', true); 
    }
  
  } else {
    if($('#definiendo').is(':checked')){ 
      if ($('#fecha_pago_obrero').is(':disabled')) {
        $("#permanente_pago_obrero").val(1);
      }else{
        $("#permanente_pago_obrero").val(0);
      }
       
    }else{ 
      if ($('#fecha_pago_obrero').is(':disabled')) {
        $("#permanente_pago_obrero").val(1);
      }else{
        $("#permanente_pago_obrero").val(1);
      } 
    }
  }
}

//Función limpiar
function limpiar() {
  $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');  
  $(".show_hide_select_1").show(); 
  $(".show_hide_select_2").hide();
  $(".show_hide_select_2").html('');

  $('.show_hide_switch_1').show();
  $('.show_hide_switch_2').hide();

  $("#idproyecto").val("");  
  $("#tipo_documento option[value='RUC']").attr("selected", true);
  $("#num_documento").val(""); 
  $("#empresa").val(""); 
  $("#nombre_proyecto").val(""); $("#nombre_codigo").val("");
  $("#ubicacion").val(""); 
  $("#actividad_trabajo").val("");  

  $("#fecha_inicio_actividad").val("");  $("#fecha_fin_actividad").val("");
  $('#plazo_actividad').val("0");
  $('.plazo_actividad').html("0");

  $("#fecha_inicio").val("");  $("#fecha_fin").val("");   
  $("#dias_habiles").val(""); $("#plazo").val(""); 

  $("#costo").val(""); 
  $("#garantia").val("0"); 
  $("#empresa_acargo").val("Seven's Ingenieros SAC").trigger("change"); 

  $("#fecha_pago_obrero").prop("disabled", false);
  $("#definiendo").removeAttr("disabled");
  $("#permanente_pago_obrero").val("0");

  $("#fecha_pago_obrero").val("").trigger("change");
  $("#fecha_valorizacion").val("").trigger("change");  

  $("#doc1").val(""); 
  $("#doc_old_1").val(""); 
  $("#doc1_nombre").html('');
  $("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

  $("#doc2").val(""); 
  $("#doc_old_2").val("");
  $("#doc2_nombre").html('');
  $("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

  $("#doc3").val(""); 
  $("#doc_old_3").val("");
  $("#doc3_nombre").html('');
  $("#doc3_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >'); 

  $("#doc4").val(""); 
  $("#doc_old_4").val("");
  $("#doc4_nombre").html('');
  $("#doc4_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

  $("#doc5").val(""); 
  $("#doc_old_5").val("");
  $("#doc5_nombre").html('');
  $("#doc5_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

  $("#doc6").val(""); 
  $("#doc_old_6").val("");
  $("#doc6_nombre").html('');
  $("#doc6_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar en curso o no empezados
function tbla_principal(estado, class_color, box_select) {

  $('.info-box').removeClass('shadow-0px-05rem-1rem-rgb-255-193-7 shadow-0px-05rem-1rem-rgb-220-53-69 shadow-0px-05rem-1rem-rgb-40-167-69 shadow-0px-05rem-1rem-rgb-23-162-184');
  $(box_select).addClass(class_color);

  tabla=$('#tabla-proyectos').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,6,7,3,8,5,9,10,11,12,13,14,15,], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,6,7,3,8,5,9,10,11,12,13,14,15,], } }, { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,6,7,3,8,5,9,10,11,12,13,14,15,], } }, {extend: "colvis"} ,        
    ],
    ajax:{
      url: `../ajax/proyecto.php?op=tbla_principal&estado=${estado}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText); ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: costo
      if (data[5] != '') { $("td", row).eq(5).addClass("text-right"); }
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
      { targets: [5], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
      { targets: [6,7,8,9,10,11,12,13,14,15], visible: false, searchable: false, },
    ],
  }).DataTable();   
  
}

//Función Listar todos lo proyectos terminados
function tbla_secundaria() {

  tabla2=$('#tabla-proyectos-terminados').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,6,7,3,8,5,9,10,11,12,13,14,15,], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,6,7,3,8,5,9,10,11,12,13,14,15,], } }, { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,6,7,3,8,5,9,10,11,12,13,14,15,], } }, {extend: "colvis"} ,        
    ],
    ajax:{
      url: '../ajax/proyecto.php?op=listar-proyectos-terminados',
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText); ver_errores(e);	
      }
    },
    createdRow: function (row, data, ixdex) {    
      // columna: #
      if (data[0] != '') {
        $("td", row).eq(0).addClass("text-center");
      }
      // columna: costo
      if (data[5] != '') {
        $("td", row).eq(5).addClass("text-right");
      }
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
      { targets: [6], visible: false, searchable: false, },
      { targets: [7], visible: false, searchable: false, },
      { targets: [8], visible: false, searchable: false, },
      { targets: [9], visible: false, searchable: false, },
      { targets: [10], visible: false, searchable: false, },
      { targets: [11], visible: false, searchable: false, },
      { targets: [12], visible: false, searchable: false, },
      { targets: [13], visible: false, searchable: false, },
      { targets: [14], visible: false, searchable: false, },
      { targets: [15], visible: false, searchable: false, },      
    ],
  }).DataTable();
  
}

//Función para guardar o editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-proyecto")[0]);

  $.ajax({
    url: "../ajax/proyecto.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  //console.log(e);  
        if (e) {
          
          tabla.ajax.reload(null, false);	

          Swal.fire("Correcto!", "Proyecto guardado correctamente", "success");	      
          
          limpiar(); tablero(); box_proyecto();

          $("#modal-agregar-proyecto").modal("hide");        
          
        }else{
          ver_errores(e);				 
        }
      } catch (err) { console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>'); } 
      $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');
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
      $("#guardar_registro").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
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

//Función para guardar o editar
function guardar_editar_valorizacion(e) {
  e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-valorizaciones")[0]);

  $.ajax({
    url: "../ajax/proyecto.php?op=editar_doc_valorizaciones",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (datos) {
             
      if (datos == 'ok') {

        tabla.ajax.reload(null, false);	

        Swal.fire("Correcto!", "Documento guardado correctamente", "success");	      
         
				limpiar();

        $("#modal-agregar-valorizaciones").modal("hide");        

			}else{

        Swal.fire("Error!", datos, "error");
				 
			}
    },
    xhr: function () {

      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener("progress", function (evt) {

        if (evt.lengthComputable) {

          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress2").css({"width": percentComplete+'%'});

          $("#barra_progress2").text(percentComplete.toFixed(2)+" %");

          if (percentComplete === 100) {

            setTimeout(l_m, 600);
          }
        }
      }, false);
      return xhr;
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function l_m(){
  
  // limpiar();
  $("#barra_progress").css({"width":'0%'});
  $("#barra_progress").text("0%");

  $("#barra_progress2").css({"width":'0%'});
  $("#barra_progress2").text("0%");
  
}

function abrir_proyecto(idproyecto, ec_razon_social, nombre_proyecto, fecha_inicial, fecha_final) {

  Swal.fire("Abierto!", `<b class="text-success">${nombre_proyecto}</b> <br> Proyecto abierto corrrectamente`, "success");

  $(".tooltip").removeClass("show").addClass("hidde");
}

//Función para desactivar registros
function empezar_proyecto(idproyecto, nombre_proyecto ) {
  crud_simple_alerta(
    '../ajax/proyecto.php?op=empezar_proyecto', 
    idproyecto, 
    '¿Está Seguro de  Empezar  el proyecto ?', 
    `<b class="text-success">${nombre_proyecto}</b> <br> Tendras acceso a agregar o editar: provedores, trabajadores!`, 
    'Si, Empezar!',
    function(){ Swal.fire("En curso!", "Tu proyecto esta en curso.", "success"); },
    function(){ tabla.ajax.reload(null, false);  box_proyecto();}
  );  
}

//Función para activar registros
function terminar_proyecto(idproyecto, nombre_proyecto) {

  crud_simple_alerta(
    '../ajax/proyecto.php?op=terminar_proyecto', 
    idproyecto, 
    '¿Está Seguro de  Terminar  el Proyecto?', 
    `<b class="text-danger"><del>${nombre_proyecto}</del></b> <br> No tendras acceso a editar o agregar: proveedores o trabajadores!`, 
    'Si, Terminar!',
    function(){ Swal.fire("Terminado!", "Tu Proyecto ha sido terminado.", "success"); },
    function(){ tabla.ajax.reload(null, false);  box_proyecto();}
  );        
}

//Función para activar registros
function reiniciar_proyecto(idproyecto, nombre_proyecto) {

  crud_simple_alerta(
    '../ajax/proyecto.php?op=reiniciar_proyecto', 
    idproyecto, 
    '¿Está Seguro de Reactivar el Proyecto?', 
    `<b class="text-success">${nombre_proyecto}</b> <br> Despues de esto tendrás que empezar el proyecto!`, 
    'Si, Reactivar!',
    function(){ Swal.fire("Reactivado!", "Tu Proyecto ha sido Reactivado.", "success"); },
    function(){ tabla.ajax.reload(null, false);  box_proyecto();}
  );       
}

// caculamos el plazo CON DATE RANGER
function calcular_palzo() {

  $("#fecha_inicio_fin").on("apply.daterangepicker", function (ev, picker) { 

    var plazo_dia = picker.endDate.diff(picker.startDate, "days");

    $("#plazo").val( plazo_dia +' dias');    
  });
}

// abrimos el navegador de archivos
$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addImageApplication(e, $("#doc1").attr("id")) });

$("#doc2_i").click(function() {  $('#doc2').trigger('click'); });
$("#doc2").change(function(e) {  addImageApplication(e, $("#doc2").attr("id")) });

$("#doc3_i").click(function() {  $('#doc3').trigger('click'); });
$("#doc3").change(function(e) {  addImageApplication(e, $("#doc3").attr("id")) });

$("#doc4_i").click(function() {  $('#doc4').trigger('click'); });
$("#doc4").change(function(e) {  addImageApplication(e, $("#doc4").attr("id")) });

$("#doc5_i").click(function() {  $('#doc5').trigger('click'); });
$("#doc5").change(function(e) {  addImageApplication(e, $("#doc5").attr("id")) });

$("#doc6_i").click(function() {  $('#doc6').trigger('click'); });
$("#doc6").change(function(e) {  addImageApplication(e, $("#doc6").attr("id")) });

// Eliminamos el doc 1
function doc1_eliminar() {

	$("#doc1").val("");

	$("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc1_nombre").html("");
}

// Eliminamos el doc 2
function doc2_eliminar() {

	$("#doc2").val("");

	$("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc2_nombre").html("");
}

// Eliminamos el doc 3
function doc3_eliminar() {

	$("#doc3").val("");

	$("#doc3_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc3_nombre").html("");
}

// Eliminamos el doc 4
function doc4_eliminar() {

	$("#doc4").val("");

	$("#doc4_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc4_nombre").html("");
}

// Eliminamos el doc 5
function doc5_eliminar() {

	$("#doc5").val("");

	$("#doc5_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc5_nombre").html("");
}

// Eliminamos el doc 6
function doc6_eliminar() {

	$("#doc6").val("");

	$("#doc6_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc6_nombre").html("");
} 

function ver_modal_docs(verdoc1, verdoc2, verdoc3, verdoc4, verdoc5, verdoc6) {
  //console.log(verdoc1, verdoc2, verdoc3,verdoc4, verdoc5, verdoc6);
  $('#modal-ver-docs').modal("show");

  if (verdoc1 == "") {

    $('#verdoc1').html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" height="206" >');

    $("#verdoc1_nombre").html(`Contratro de obra <div class="col-md-12 row mt-2"> <div class="col-6 col-sm-6 col-md-6 col-lg-6"> <a class="btn btn-warning  btn-block btn-xs" href="#"  onclick="no_pdf();" type="button" > <i class="fas fa-download"></i> </a> </div> <div class="col-6 col-sm-6 col-md-6 col-lg-6"> <a class="btn btn-info  btn-block btn-xs" href="#"  onclick="no_pdf();" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>`);
  
  } else {
    // cargamos la imagen adecuada par el archivo
    var ver_doc = doc_view_extencion(verdoc1, 'valorizacion', 'documento', '100%', '206');

    $("#verdoc1").html(ver_doc);

    var href = '#'; var disabled = 'disabled';

    if ( pdf_o_img(verdoc1) ) { href = `../dist/docs/valorizacion/documento/${verdoc1}`;  disabled = '';  } 

    $("#verdoc1_nombre").html(`Contratro de obra.${extrae_extencion(verdoc1)}<div class="col-md-12 row mt-2"> 
      <div class="col-6 col-sm-6 col-md-6 col-lg-6"> 
        <a  class="btn btn-warning btn-block btn-xs" href="${href}" download="Contratro de obra" onclick="dowload_pdf();"  type="button" > 
          <i class="fas fa-download"></i> 
        </a> 
      </div>  
      <div class="col-6 col-sm-6 col-md-6 col-lg-6"> 
        <a  class="btn btn-info  btn-block btn-xs ${disabled}" href="${href}" type="button" target="_blank" > 
          Ver completo <i class="fas fa-expand"></i> 
        </a> 
      </div> 
    </div>`);
  }
  
  if (verdoc2 == "") {

    $('#verdoc2').html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" height="206" >');

    $("#verdoc2_nombre").html(`Entrega de terreno <div class="col-md-12 row mt-2"> <div class="col-6 col-sm-6 col-md-6 col-lg-6"> <a class="btn btn-warning  btn-block btn-xs" href="#"  onclick="no_pdf();"  type="button" > <i class="fas fa-download"></i> </a> </div> <div class="col-6 col-sm-6 col-md-6 col-lg-6"> <a class="btn btn-info btn-block btn-xs" href="#"  onclick="no_pdf();" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>`);

  } else {
     
    // cargamos la imagen adecuada par el archivo

    var ver_doc = doc_view_extencion(verdoc2, 'valorizacion', 'documento', '100%', '206');

    $("#verdoc2").html(ver_doc);

    var href = '#'; var disabled = 'disabled';

    if ( pdf_o_img(verdoc2) ) { href = `../dist/docs/valorizacion/documento/${verdoc2}`;  disabled = '';  } 

    $("#verdoc2_nombre").html(`Entrega de terreno.${extrae_extencion(verdoc2)}<div class="col-md-12 row mt-2"> 
      <div class="col-6 col-sm-6 col-md-6 col-lg-6"> 
        <a  class="btn btn-warning btn-block btn-xs" href="${href}" download="Entrega de terreno" onclick="dowload_pdf();"  type="button" > 
          <i class="fas fa-download"></i> 
        </a> 
      </div>  
      <div class="col-6 col-sm-6 col-md-6 col-lg-6"> 
        <a  class="btn btn-info  btn-block btn-xs ${disabled}" href="${href}" type="button" target="_blank" > 
          Ver completo <i class="fas fa-expand"></i> 
        </a> 
      </div> 
    </div>`);
     
  }

  if (verdoc3 == "") {

    $('#verdoc3').html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" height="206" >');

    $("#verdoc3_nombre").html(`Inicio de obra <div class="col-md-12 row mt-2"> <div class="col-6 col-sm-6 col-md-6 col-lg-6"> <a class="btn btn-warning  btn-block btn-xs" href="#"  onclick="no_pdf();" type="button" > <i class="fas fa-download"></i> </a> </div> <div class="col-6 col-sm-6 col-md-6 col-lg-6"> <a class="btn btn-info  btn-block btn-xs" href="#"  onclick="no_pdf();" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>`);

  } else {
    
    // cargamos la imagen adecuada par el archivo
    var ver_doc = doc_view_extencion(verdoc3, 'valorizacion', 'documento', '100%', '206');

    $("#verdoc3").html(ver_doc);

    var href = '#'; var disabled = 'disabled';

    if ( pdf_o_img(verdoc3) ) { href = `../dist/docs/valorizacion/documento/${verdoc3}`;  disabled = '';  } 

    $("#verdoc3_nombre").html(`Inicio de obra.${extrae_extencion(verdoc3)}<div class="col-md-12 row mt-2"> 
      <div class="col-6 col-sm-6 col-md-6 col-lg-6"> 
        <a  class="btn btn-warning btn-block btn-xs" href="${href}" download="Inicio de obra" onclick="dowload_pdf();"  type="button" > 
          <i class="fas fa-download"></i> 
        </a> 
      </div>  
      <div class="col-6 col-sm-6 col-md-6 col-lg-6"> 
        <a  class="btn btn-info  btn-block btn-xs ${disabled}" href="${href}" type="button" target="_blank" > 
          Ver completo <i class="fas fa-expand"></i> 
        </a> 
      </div> 
    </div>`);
  }  

  if (verdoc4 == "") {

    $('#verdoc4').html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" height="206" >');

    $("#verdoc4_nombre").html(`Presupuesto <div class="col-md-12 row mt-2"> <div class="col-6 col-sm-6 col-md-6 col-lg-6"> <a class="btn btn-warning  btn-block btn-xs" href="#"  onclick="no_pdf();" type="button" > <i class="fas fa-download"></i> </a> </div> <div class="col-6 col-sm-6 col-md-6 col-lg-6"> <a class="btn btn-info  btn-block btn-xs" href="#"  onclick="no_pdf();" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>`);

  } else {    

    // cargamos la imagen adecuada par el archivo
    var ver_doc = doc_view_extencion(verdoc4, 'valorizacion', 'documento', '100%', '206');

    $("#verdoc4").html(ver_doc);

    var href = '#'; var disabled = 'disabled';

    if ( pdf_o_img(verdoc4) ) { href = `../dist/docs/valorizacion/documento/${verdoc4}`;  disabled = '';  } 

    $("#verdoc4_nombre").html(`Presupuesto.${extrae_extencion(verdoc4)}<div class="col-md-12 row mt-2"> 
      <div class="col-6 col-sm-6 col-md-6 col-lg-6"> 
        <a  class="btn btn-warning btn-block btn-xs" href="${href}" download="Presupuesto" onclick="dowload_pdf();"  type="button" > 
          <i class="fas fa-download"></i> 
        </a> 
      </div>  
      <div class="col-6 col-sm-6 col-md-6 col-lg-6"> 
        <a  class="btn btn-info  btn-block btn-xs ${disabled}" href="${href}" type="button" target="_blank" > 
          Ver completo <i class="fas fa-expand"></i> 
        </a> 
      </div> 
    </div>`);
     
  }

  if (verdoc5 == "") {

    $('#verdoc5').html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" height="206" >');

    $("#verdoc5_nombre").html(`Analisis de costos unitarios <div class="col-md-12 row mt-2"> <div class="col-6 col-sm-6 col-md-6 col-lg-6"> <a class="btn btn-warning  btn-block btn-xs" href="#"  onclick="no_pdf();" type="button" > <i class="fas fa-download"></i> </a> </div> <div class="col-6 col-sm-6 col-md-6 col-lg-6"> <a class="btn btn-info  btn-block btn-xs" href="#"  onclick="no_pdf();" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>`);

  } else {

    // cargamos la imagen adecuada par el archivo
    var ver_doc = doc_view_extencion(verdoc5, 'valorizacion', 'documento', '100%', '206');

    $("#verdoc5").html(ver_doc);

    var href = '#'; var disabled = 'disabled';

    if ( pdf_o_img(verdoc5) ) { href = `../dist/docs/valorizacion/documento/${verdoc5}`;  disabled = '';  } 

    $("#verdoc5_nombre").html(`Analisis de costos unitarios.${extrae_extencion(verdoc5)}<div class="col-md-12 row mt-2"> 
      <div class="col-6 col-sm-6 col-md-6 col-lg-6"> 
        <a  class="btn btn-warning btn-block btn-xs" href="${href}" download="Analisis de costos unitarios" onclick="dowload_pdf();"  type="button" > 
          <i class="fas fa-download"></i> 
        </a> 
      </div>  
      <div class="col-6 col-sm-6 col-md-6 col-lg-6"> 
        <a  class="btn btn-info  btn-block btn-xs ${disabled}" href="${href}" type="button" target="_blank" > 
          Ver completo <i class="fas fa-expand"></i> 
        </a> 
      </div> 
    </div>`);    
  }

  if (verdoc6 == "") {

    $('#verdoc6').html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" height="206" >');

    $("#verdoc6_nombre").html(`Insumos <div class="col-md-12 row mt-2"> <div class="col-6 col-sm-6 col-md-6 col-lg-6"> <a class="btn btn-warning  btn-block btn-xs" href="#"  onclick="no_pdf();" type="button" > <i class="fas fa-download"></i> </a> </div> <div class="col-6 col-sm-6 col-md-6 col-lg-6"> <a class="btn btn-info  btn-block btn-xs" href="#"  onclick="no_pdf();" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>`);

  } else {  

    // cargamos la imagen adecuada par el archivo
    var ver_doc = doc_view_extencion(verdoc6, 'valorizacion', 'documento', '100%', '206');

    $("#verdoc6").html(ver_doc);

    var href = '#'; var disabled = 'disabled';

    if ( pdf_o_img(verdoc6) ) { href = `../dist/docs/valorizacion/documento/${verdoc6}`;  disabled = '';  } 

    $("#verdoc6_nombre").html(`Insumos.${extrae_extencion(verdoc6)}<div class="col-md-12 row mt-2"> 
      <div class="col-6 col-sm-6 col-md-6 col-lg-6"> 
        <a  class="btn btn-warning btn-block btn-xs" href="${href}" download="Analisis de costos unitarios" onclick="dowload_pdf();"  type="button" > 
          <i class="fas fa-download"></i> 
        </a> 
      </div>  
      <div class="col-6 col-sm-6 col-md-6 col-lg-6"> 
        <a  class="btn btn-info  btn-block btn-xs ${disabled}" href="${href}" type="button" target="_blank" > 
          Ver completo <i class="fas fa-expand"></i> 
        </a> 
      </div> 
    </div>`);  

  }

  $(".tooltip").removeClass("show").addClass("hidde");
}

function no_pdf() {
  toastr.error("No hay DOC disponible, suba un DOC en el apartado de editar!!")
}

function dowload_pdf() {
  toastr.success("El documento se descargara en breve!!")
}

function mostrar(idproyecto) {
  
  limpiar();

  $("#cargando-1-fomulario").hide();

  $("#cargando-2-fomulario").show();

  $("#modal-agregar-proyecto").modal("show")

  $.post("../ajax/proyecto.php?op=mostrar", { idproyecto: idproyecto }, function (data, status) {

    data = JSON.parse(data);  console.log(data);   

    if (data.status) {
      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
      
      $("#idproyecto").val(data.data.idproyecto);  
      $("#tipo_documento option[value='"+data.data.tipo_documento+"']").attr("selected", true);
      $("#num_documento").val(data.data.numero_documento); 
      $("#empresa").val(data.data.empresa); 
      $("#nombre_proyecto").val(data.data.nombre_proyecto); $("#nombre_codigo").val(data.data.nombre_codigo);
      $("#ubicacion").val(data.data.ubicacion); 
      $("#actividad_trabajo").val(data.data.actividad_trabajo);  
        
      $("#dias_habiles").val(parseInt( data.data.dias_habiles));     
      $("#plazo").val(data.data.plazo); 
      $("#costo").val(formato_miles(data.data.costo)); 
      $("#garantia").val(data.data.garantia); 
      $("#empresa_acargo").val(data.data.idempresa_a_cargo).trigger("change");
      $("#fecha_pago_obrero").val(data.data.fecha_pago_obrero).trigger("change");
      $("#fecha_valorizacion").val(data.data.fecha_valorizacion).trigger("change");
      
      // console.log(format_d_m_a(data.fecha_inicio));
      $("#fecha_inicio").datepicker("setDate" ,format_d_m_a(data.data.fecha_inicio));
      $("#fecha_fin").val(format_d_m_a(data.data.fecha_fin));

      $("#fecha_inicio_actividad").val(format_d_m_a(data.data.fecha_inicio_actividad));  
      $("#fecha_fin_actividad").val(format_d_m_a(data.data.fecha_fin_actividad));
      $('#plazo_actividad').val(data.data.plazo_actividad); 
      $('.plazo_actividad').html(data.data.plazo_actividad);

      if (data.data.permanente_pago_obrero == '1') {      
        $(".show_hide_select_1").hide(); 
        $(".show_hide_select_2").show();
        $(".show_hide_select_2").html(`<label for="">Pago de obreros <sup class="text-danger">*</sup></label> <span class="form-control" > ${data.data.fecha_pago_obrero} </span>`);
  
        $('.show_hide_switch_1').hide();
        $('.show_hide_switch_2').show();
  
        $("#definiendo").prop('checked', true);        
      } else {      
        $(".show_hide_select_1").show(); 
        $(".show_hide_select_2").hide();
        $(".show_hide_select_2").html("");
  
        $('.show_hide_switch_1').show();
        $('.show_hide_switch_2').hide();
        $("#definiendo").prop('checked', false);       
      }
  
      $("#permanente_pago_obrero").val(data.data.permanente_pago_obrero);
      
      //validamoos DOC-1
      if (data.data.doc1_contrato_obra != ""  ) {
  
        $("#doc_old_1").val(data.data.doc1_contrato_obra); 
  
        $("#doc1_nombre").html('Contrato de obra.' + extrae_extencion(data.data.doc1_contrato_obra));     
  
        // cargamos la imagen adecuada par el archivo
        var doc_html = doc_view_extencion(data.data.doc1_contrato_obra, 'valorizacion', 'documento', '100%', '210');
  
        $("#doc1_ver").html(doc_html);
        
      } else {
  
        $("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" width="50%" >');
  
        $("#doc1_nombre").html('');
  
        $("#doc_old_1").val(""); 
      }
  
      //validamoos DOC-2
      if (data.doc2_entrega_terreno != "" ) {
  
        $("#doc_old_2").val(data.data.doc2_entrega_terreno);
  
        $("#doc2_nombre").html('Entrega de terreno.' + extrae_extencion(data.data.doc2_entrega_terreno) );
  
        // cargamos la imagen adecuada par el archivo
        var doc_html = doc_view_extencion(data.data.doc2_entrega_terreno, 'valorizacion', 'documento', '100%', '210');
  
        $("#doc2_ver").html(doc_html);
         
      } else {
  
        $("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" width="50%" >');
  
        $("#doc2_nombre").html('');
  
        $("#doc_old_2").val("");
      }
  
      //validamoos DOC-3
      if (data.doc3_inicio_obra != "" ) {
  
        $("#doc_old_3").val(data.data.doc3_inicio_obra);
  
        $("#doc3_nombre").html('Inicio de obra.' + extrae_extencion(data.data.doc3_inicio_obra));
  
        // cargamos la imagen adecuada par el archivo
        var doc_html = doc_view_extencion(data.data.doc3_inicio_obra, 'valorizacion', 'documento', '100%', '210');
  
        $("#doc3_ver").html(doc_html);
         
      } else {
  
        $("#doc3_ver").html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" width="50%" >');
  
        $("#do3_nombre").html('');
  
        $("#doc_old_3").val("");
      }
  
      //validamoos DOC-4
      if (data.data.doc4_presupuesto != "" ) {
  
        $("#doc_old_4").val(data.data.doc4_presupuesto);
  
        $("#doc4_nombre").html('Presupuesto.' + extrae_extencion(data.data.doc4_presupuesto));
  
        // cargamos la imagen adecuada par el archivo
        var doc_html = doc_view_extencion(data.data.doc4_presupuesto, 'valorizacion', 'documento', '100%', '210');
  
        $("#doc4_ver").html(doc_html);
        
      } else {
  
        $("#doc4_ver").html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" width="50%" >');
  
        $("#doc4_nombre").html('');
  
        $("#doc_old_4").val("");
      }
  
      //validamoos DOC-5
      if (data.data.doc5_analisis_costos_unitarios != "" ) {
  
        $("#doc_old_5").val(data.data.doc5_analisis_costos_unitarios);
  
        $("#doc5_nombre").html('Analisis de costos unitarios.' + extrae_extencion(data.data.doc5_analisis_costos_unitarios));
  
        // cargamos la imagen adecuada par el archivo
        var doc_html = doc_view_extencion(data.data.doc5_analisis_costos_unitarios, 'valorizacion', 'documento', '100%', '210');
  
        $("#doc5_ver").html(doc_html);
  
      } else {
  
        $("#doc5_ver").html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" width="50%" >');
  
        $("#doc5_nombre").html('');
  
        $("#doc_old_5").val("");
      }
  
      //validamoos DOC-6
      if (data.data.doc6_insumos != "" ) {
  
        $("#doc_old_6").val(data.data.doc6_insumos);
  
        $("#doc6_nombre").html('Insumos.' + extrae_extencion(data.data.doc6_insumos));
  
        // cargamos la imagen adecuada par el archivo
        var doc_html = doc_view_extencion(data.data.doc6_insumos, 'valorizacion', 'documento', '100%', '210');
  
        $("#doc6_ver").html(doc_html);
        
      } else {
  
        $("#doc6_ver").html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" width="50%" >');
  
        $("#doc6_nombre").html('');
  
        $("#doc_old_6").val("");
      }
    } else {
      ver_errores(data);
    }

  }).fail( function(e) { ver_errores(e); } );
  $(".tooltip").removeClass("show").addClass("hidde");  
}

function mostrar_detalle(idproyecto) {

  $('#cargando-3-fomulario').hide();
  $('#cargando-4-fomulario').show();

  $("#modal-ver-detalle").modal("show");

  $.post("../ajax/proyecto.php?op=mostrar", { idproyecto: idproyecto }, function (data, status) {

    data = JSON.parse(data);  //console.log(data);   

    var ruta_carpeta = window.location.host;

    if (ruta_carpeta == 'localhost') {
      ruta_carpeta = 'http://localhost/front_jdl/admin/dist/docs/valorizacion/documento/'
    } else {
      ruta_carpeta = `${window.location.origin}/dist/docs/valorizacion/documento/`;
    }

    if (data.status) {
      $('#cargando-detalle-proyecto').html(`
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <table class="table table-hover table-bordered">         
                <tbody>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Empresa</th>
                    <td>${data.data.empresa}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Documento</th>
                    <td>${data.data.tipo_documento}: ${data.data.numero_documento}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Nombre de  proyecto</th>
                    <td>${data.data.nombre_proyecto}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Ubicación</th>
                    <td>${data.data.ubicacion}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Actividad del trabajo</th>
                    <td>${data.data.actividad_trabajo}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Fecha inicio/fin actividad</th>
                    <td>${format_d_m_a(data.data.fecha_inicio_actividad)} / ${format_d_m_a(data.data.fecha_fin_actividad)}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Fecha inicio/fin</th>
                    <td>${format_d_m_a(data.data.fecha_inicio)} / ${format_d_m_a(data.data.fecha_fin)}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Plazo</th>                            
                    <td>${data.data.plazo}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Costo total</th>
                    <td>${formato_miles(data.data.costo)}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Garantía</th>
                    <td>${formato_miles(parseFloat(data.data.garantia) * 100)}%</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Empresa a cargo</th>
                    <td><img src="../dist/svg/${data.data.ec_logo}" class="mr-2 w-25px" /> ${data.data.ec_razon_social} - ${data.data.ec_tipo_documento} ${data.data.ec_numero_documento}</td>
                  </tr>
                </tbody>
              </table>       
            </div>
          </div>
      </div>`);
      
      if (data.data.doc1_contrato_obra == '' || data.data.doc1_contrato_obra == null) {
        $('.name_doc_1').html('<i class="fas fa-paperclip"></i> Acta-de-contrato-de-obra');
        $('.name_icon_1').html( doc_view_icon(data.data.doc1_contrato_obra) );
        $('.download_doc_1').removeClass('btn-outline-success').addClass('btn-default disabled');
        $('.ver_doc_1').removeClass('btn-outline-info').addClass('btn-default disabled');
        $('.imprimir_doc_1').removeClass('btn-outline-primary').addClass('btn-default disabled');

        $('.download_doc_1').attr('onclick', ``);
      } else {
        $('.name_doc_1').html(`<i class="fas fa-paperclip"></i> Acta-de-contrato-de-obra.${extrae_extencion(data.data.doc1_contrato_obra)}`); 
        $('.name_icon_1').html( doc_view_icon(data.data.doc1_contrato_obra) );
        $('.download_doc_1').removeClass('btn-default disabled').addClass('btn-outline-success');
        $('.download_doc_1').attr('download', 'Contrato de obra');
        $('.download_doc_1').attr('href', `../dist/docs/valorizacion/documento/${data.data.doc1_contrato_obra}`);
        $('.download_doc_1').attr('onclick', `ok_dowload_doc();`);

        if ( pdf_o_img(data.data.doc1_contrato_obra) ) {
          $('.ver_doc_1').removeClass('btn-default disabled').addClass('btn-outline-info');
          $('.ver_doc_1').attr('href', `../dist/docs/valorizacion/documento/${data.data.doc1_contrato_obra}`);        

          $('.imprimir_doc_1').removeClass('btn-default disabled').addClass('btn-outline-primary');
          $('.imprimir_doc_1').attr('onclick', `printJS({printable:'${ruta_carpeta}${data.data.doc1_contrato_obra}', type:'pdf', showModal:true})`);
        } else {
          $('.ver_doc_1').removeClass('btn-outline-info').addClass('btn-default disabled');
          $('.imprimir_doc_1').removeClass('btn-outline-primary').addClass('btn-default disabled');
        }        
      }

      if (data.data.doc2_entrega_terreno == '' || data.data.doc2_entrega_terreno == null) {
        $('.name_doc_2').html('<i class="fas fa-paperclip"></i> Acta-de-entrega-de-terreno');
        $('.name_icon_2').html( doc_view_icon(data.data.doc2_entrega_terreno) );
        $('.download_doc_2').removeClass('btn-outline-success').addClass('btn-default disabled');
        $('.ver_doc_2').removeClass('btn-outline-info').addClass('btn-default disabled');
        $('.imprimir_doc_2').removeClass('btn-outline-primary').addClass('btn-default disabled');

        $('.download_doc_2').attr('onclick', ``);
      } else {
        $('.name_doc_2').html(`<i class="fas fa-paperclip"></i> Acta-de-entrega-de-terreno.${extrae_extencion(data.data.doc2_entrega_terreno)}`);
        $('.name_icon_2').html( doc_view_icon(data.data.doc2_entrega_terreno) );
        $('.download_doc_2').removeClass('btn-default disabled').addClass('btn-outline-success');
        $('.download_doc_2').attr('download', 'Acta-de-entrega-de-terreno');
        $('.download_doc_2').attr('href', `../dist/docs/valorizacion/documento/${data.data.doc2_entrega_terreno}`);
        $('.download_doc_2').attr('onclick', `ok_dowload_doc();`);

        if ( pdf_o_img(data.data.doc2_entrega_terreno) ) {
          $('.ver_doc_2').removeClass('btn-default disabled').addClass('btn-outline-info');
          $('.ver_doc_2').attr('href', `../dist/docs/valorizacion/documento/${data.data.doc2_entrega_terreno}`);        

          $('.imprimir_doc_2').removeClass('btn-default disabled').addClass('btn-outline-primary');
          $('.imprimir_doc_2').attr('onclick', `printJS({printable:'${ruta_carpeta}${data.data.doc2_entrega_terreno}', type:'pdf', showModal:true})`);
        } else {
          $('.ver_doc_2').removeClass('btn-outline-info').addClass('btn-default disabled');
          $('.imprimir_doc_2').removeClass('btn-outline-primary').addClass('btn-default disabled');
        }        
      }
      
      if (data.data.doc3_inicio_obra == '' || data.data.doc3_inicio_obra == null) {
        $('.name_doc_3').html('<i class="fas fa-paperclip"></i> Acta-de-inicio-de-obra');
        $('.name_icon_3').html( doc_view_icon(data.data.doc3_inicio_obra) );
        $('.download_doc_3').removeClass('btn-outline-success').addClass('btn-default disabled');
        $('.ver_doc_3').removeClass('btn-outline-info').addClass('btn-default disabled');
        $('.imprimir_doc_3').removeClass('btn-outline-primary').addClass('btn-default disabled');

        $('.download_doc_3').attr('onclick', ``);
      } else {
        $('.name_doc_3').html(`<i class="fas fa-paperclip"></i> Acta-de-inicio-de-obra.${extrae_extencion(data.data.doc3_inicio_obra)}`);
        $('.name_icon_3').html( doc_view_icon(data.data.doc3_inicio_obra) );
        $('.download_doc_3').removeClass('btn-default disabled').addClass('btn-outline-success');
        $('.download_doc_3').attr('download', 'Acta-de-inicio-de-obra');
        $('.download_doc_3').attr('href', `../dist/docs/valorizacion/documento/${data.data.doc3_inicio_obra}`);
        $('.download_doc_3').attr('onclick', `ok_dowload_doc();`);

        if ( pdf_o_img(data.data.doc3_inicio_obra) ) {
          $('.ver_doc_3').removeClass('btn-default disabled').addClass('btn-outline-info');
          $('.ver_doc_3').attr('href', `../dist/docs/valorizacion/documento/${data.data.doc3_inicio_obra}`);        

          $('.imprimir_doc_3').removeClass('btn-default disabled').addClass('btn-outline-primary');
          $('.imprimir_doc_3').attr('onclick', `printJS({printable:'${ruta_carpeta}${data.data.doc3_inicio_obra}', type:'pdf', showModal:true})`);
        } else {
          $('.ver_doc_3').removeClass('btn-outline-info').addClass('btn-default disabled');
          $('.imprimir_doc_3').removeClass('btn-outline-primary').addClass('btn-default disabled');
        }        
      }

      if (data.data.doc4_presupuesto == '' || data.data.doc4_presupuesto == null) {
        $('.name_doc_4').html('<i class="fas fa-paperclip"></i> Presupuesto');
        $('.name_icon_4').html( doc_view_icon(data.data.doc4_presupuesto) );
        $('.download_doc_4').removeClass('btn-outline-success').addClass('btn-default disabled');
        $('.ver_doc_4').removeClass('btn-outline-info').addClass('btn-default disabled');
        $('.imprimir_doc_4').removeClass('btn-outline-primary').addClass('btn-default disabled');

        $('.download_doc_4').attr('onclick', ``);
      } else {
        $('.name_doc_4').html(`<i class="fas fa-paperclip"></i> Presupuesto.${extrae_extencion(data.data.doc4_presupuesto)}`);
        $('.name_icon_4').html( doc_view_icon(data.data.doc4_presupuesto) );
        $('.download_doc_4').removeClass('btn-default disabled').addClass('btn-outline-success');
        $('.download_doc_4').attr('download', 'Presupuesto');
        $('.download_doc_4').attr('href', `../dist/docs/valorizacion/documento/${data.data.doc4_presupuesto}`);
        $('.download_doc_4').attr('onclick', `ok_dowload_doc();`);

        if ( pdf_o_img(data.data.doc4_presupuesto) ) {
          $('.ver_doc_4').removeClass('btn-default disabled').addClass('btn-outline-info');
          $('.ver_doc_4').attr('href', `../dist/docs/valorizacion/documento/${data.data.doc4_presupuesto}`);        

          $('.imprimir_doc_4').removeClass('btn-default disabled').addClass('btn-outline-primary');
          $('.imprimir_doc_4').attr('onclick', `printJS({printable:'${ruta_carpeta}${data.data.doc4_presupuesto}', type:'pdf', showModal:true})`);
        } else {
          $('.ver_doc_4').removeClass('btn-outline-info').addClass('btn-default disabled');
          $('.imprimir_doc_4').removeClass('btn-outline-primary').addClass('btn-default disabled');
        }        
      }

      if (data.data.doc5_analisis_costos_unitarios == '' || data.data.doc5_analisis_costos_unitarios == null) {
        $('.name_doc_5').html('<i class="fas fa-paperclip"></i> Analisis-de-costos-unitarios');
        $('.name_icon_5').html( doc_view_icon(data.data.doc5_analisis_costos_unitarios) );
        $('.download_doc_5').removeClass('btn-outline-success').addClass('btn-default disabled');
        $('.ver_doc_5').removeClass('btn-outline-info').addClass('btn-default disabled');
        $('.imprimir_doc_5').removeClass('btn-outline-primary').addClass('btn-default disabled');

        $('.download_doc_5').attr('onclick', ``);
      } else {
        $('.name_doc_5').html(`<i class="fas fa-paperclip"></i> Analisis-de-costos-unitarios.${extrae_extencion(data.data.doc5_analisis_costos_unitarios)}`);
        $('.name_icon_5').html( doc_view_icon(data.data.doc5_analisis_costos_unitarios) );
        $('.download_doc_5').removeClass('btn-default disabled').addClass('btn-outline-success');
        $('.download_doc_5').attr('download', 'Analisis-de-costos-unitarios');
        $('.download_doc_5').attr('href', `../dist/docs/valorizacion/documento/${data.data.doc5_analisis_costos_unitarios}`);
        $('.download_doc_5').attr('onclick', `ok_dowload_doc();`);

        if ( pdf_o_img(data.data.doc5_analisis_costos_unitarios) ) {
          $('.ver_doc_5').removeClass('btn-default disabled').addClass('btn-outline-info');
          $('.ver_doc_5').attr('href', `../dist/docs/valorizacion/documento/${data.data.doc5_analisis_costos_unitarios}`);        

          $('.imprimir_doc_5').removeClass('btn-default disabled').addClass('btn-outline-primary');
          $('.imprimir_doc_5').attr('onclick', `printJS({printable:'${ruta_carpeta}${data.data.doc5_analisis_costos_unitarios}', type:'pdf', showModal:true})`);
        } else {
          $('.ver_doc_5').removeClass('btn-outline-info').addClass('btn-default disabled');
          $('.imprimir_doc_5').removeClass('btn-outline-primary').addClass('btn-default disabled');
        }        
      }

      if (data.data.doc6_insumos == '' || data.data.doc6_insumos == null) {
        $('.name_doc_6').html('<i class="fas fa-paperclip"></i> Insumos');
        $('.name_icon_6').html( doc_view_icon(data.data.doc6_insumos) );
        $('.download_doc_6').removeClass('btn-outline-success').addClass('btn-default disabled');
        $('.ver_doc_6').removeClass('btn-outline-info').addClass('btn-default disabled');
        $('.imprimir_doc_6').removeClass('btn-outline-primary').addClass('btn-default disabled');

        $('.download_doc_6').attr('onclick', ``);
      } else {
        $('.name_doc_6').html(`<i class="fas fa-paperclip"></i> Insumos.${extrae_extencion(data.data.doc6_insumos)}`);
        $('.name_icon_6').html( doc_view_icon(data.data.doc6_insumos) );
        $('.download_doc_6').removeClass('btn-default disabled').addClass('btn-outline-success');
        $('.download_doc_6').attr('download', 'Insumos');
        $('.download_doc_6').attr('href', `../dist/docs/valorizacion/documento/${data.data.doc6_insumos}`);

        $('.download_doc_6').attr('onclick', `ok_dowload_doc();`);

        if ( pdf_o_img(data.data.doc6_insumos) ) {
          $('.ver_doc_6').removeClass('btn-default disabled').addClass('btn-outline-info');
          $('.ver_doc_6').attr('href', `../dist/docs/valorizacion/documento/${data.data.doc6_insumos}`);        

          $('.imprimir_doc_6').removeClass('btn-default disabled').addClass('btn-outline-primary');
          $('.imprimir_doc_6').attr('onclick', `printJS({printable:'${ruta_carpeta}${data.data.doc6_insumos}', type:'pdf', showModal:true})`);
        } else {
          $('.ver_doc_6').removeClass('btn-outline-info').addClass('btn-default disabled');
          $('.imprimir_doc_6').removeClass('btn-outline-primary').addClass('btn-default disabled');
        }        
      }  
      
      $('#cargando-3-fomulario').show();
      $('#cargando-4-fomulario').hide();

    } else {
      ver_errores(data);
    }
     
  }).fail( function(e) { ver_errores(e); } );

  $(".tooltip").removeClass("show").addClass("hidde");
}

function tablero() {   

  $.post("../ajax/proyecto.php?op=tablero",  function (data, status) {

    data = JSON.parse(data);  //console.log(data);

    if (data.status) {
      $("#cantidad_proyectos").html(data.data.proyecto);
      $("#cantidad_proveedores").html(data.data.proveedor);
      $("#cantidad_trabajadores").html(data.data.trabajador);
      $("#cantidad_compra").html(data.data.servicio);
    } else {
      ver_errores(data);
    } 

  }).fail( function(e) { ver_errores(e); } );
}

function box_proyecto() {   

  $.post("../ajax/proyecto.php?op=box_proyecto",  function (e, status) {

    e = JSON.parse(e);  console.log(e);

    if (e.status) {
      $(".cant_proceso").html(e.data.cant_proceso);
      $(".cant_no_emmpezado").html(e.data.cant_no_emmpezado);
      $(".cant_teminado").html(e.data.cant_teminado);
      $(".cant_todos").html(e.data.cant_todos);
    } else {
      ver_errores(e);
    } 

  }).fail( function(e) { ver_errores(e); } );
}

function ver_modal_docs_valorizaciones(idproyecto, documento) {

  //console.log(idproyecto, extrae_extencion( documento));

  $('#verdoc7').html('<img src="../dist/svg/doc_uploads_no.svg" alt="" height="206" >');

  $('#idproyect').val(idproyecto);

  $('#doc_old_7').val(documento);

  $('#modal-agregar-valorizaciones').modal("show");

  if (documento == "") {

    $('#verdoc7').html('<img src="../dist/svg/doc_uploads_no.svg" alt="" height="206" >');

    $("#verdoc7_nombre").html("valorizaciones"+
      '<div class="col-md-12 row mt-2">'+
        '<div class="col-md-6">'+
          '<a class="btn btn-warning  btn-block" href="#"  onclick="no_pdf();"style="padding:0px 12px 0px 12px !important;" type="button" >'+
            '<i class="fas fa-download"></i>'+
          '</a>'+
          '</div>'+

          '<div class="col-md-6">'+
          '<a class="btn btn-info  btn-block" href="#"  onclick="no_pdf();"style="padding:0px 12px 0px 12px !important;" type="button" >'+
            'Ver completo <i class="fas fa-expand"></i>'+
          '</a>'+
        '</div>'+
      '</div>'+
    '');
  } else {
    var nombredocs = "";

    
    if (extrae_extencion( documento) == "xls") {

      nombredocs = "valorizaciones.xls";     $('#verdoc7').html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');

    } else {

      if (extrae_extencion( documento) == "xlsx") {

        nombredocs = "valorizaciones.xlsx";  $('#verdoc7').html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');

      } else {

        if (extrae_extencion( documento) == "xlsx") {

          nombredocs = "valorizaciones.xlsx";  $('#verdoc7').html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
  
        } else {

          if (extrae_extencion( documento) == "xlsm") {

            nombredocs = "valorizaciones.xlsm";  $('#verdoc7').html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
    
          } else {
            nombredocs = "valorizaciones";  $('#verdoc7').html('<img src="../dist/svg/logo-excel.svg" alt="" width="50%" >');
          }
        }
      }      
    }       

    $("#verdoc7_nombre").html(nombredocs +
      '<div class="col-md-12 row mt-2">'+
          '<div class="col-md-6 ">'+
            '<a  class="btn btn-warning  btn-block" href="../dist/docs/valorizacion/documento/'+documento+'"  download="Valorizaciones" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" >'+
              '<i class="fas fa-download"></i>'+
            '</a>'+
          '</div>'+
          '<div class="col-md-6 ">'+
            '<button  class="btn btn-info  btn-block " href="../dist/docs/valorizacion/documento/'+documento+'" disabled  target="_blank" style="padding:0px 12px 0px 12px !important;" type="button" >'+
              'Ver completo <i class="fas fa-expand"></i>'+
            '</button>'+
          '</div>'+
      '</div>'+
    '');
  }
}

function calcular_plazo_fechafin() {
  
  if ($("#dias_habiles").val() == "" && $("#fecha_inicio").val() == "20-02-1112") {  //console.log($("#dias_habiles").val(),$("#fecha_inicio").val());   
  } else { //console.log($("#dias_habiles").val(),$("#fecha_inicio").val());
    if ($("#dias_habiles").val() != "") {

      if ($("#fecha_inicio").val() != "") {      
      
        if (parseInt( $("#dias_habiles").val() ) > 0) {
          
          // sumamos las fechas
          var fecha_fin = sumaFecha( $("#dias_habiles").val(), $("#fecha_inicio").val()); //console.log(format_a_m_d(fecha_fin));

          $.post("../ajax/proyecto.php?op=mostrar-rango-fechas-feriadas", { fecha_i: format_a_m_d($("#fecha_inicio").val()), fecha_f: format_a_m_d(fecha_fin) }, function (data, status) {
            
            data = JSON.parse(data);  //console.log(data);
            if (data.status) {
              var fecha_fin_es_feriado = true;
              // sumamos el new plazo            
              var new_plazo = parseInt($("#dias_habiles").val()) + parseInt( data.data.count_feriado) ;

              // sumamos las fechas con el nuevo plazo
              fecha_fin = sumaFecha( new_plazo, $("#fecha_inicio").val());

              var cant_sabados = cuentaSabado( format_a_m_d($("#fecha_inicio").val()), format_a_m_d( fecha_fin ) );

              new_plazo = parseInt($("#dias_habiles").val()) + parseInt( data.data.count_feriado) + parseInt(cant_sabados);

              // sumamos las fechas con el nuevo plazo
              fecha_fin = sumaFecha( new_plazo, $("#fecha_inicio").val());

              //console.log(cant_sabados);
              // while (fecha_fin_es_feriado == false) {            
              //   fecha_fin_es_feriado = false;
              //   // $.post("../ajax/proyecto.php?op=fecha_fin-es-feriado", { fecha_fin: format_a_m_d(fecha_fin) }, function (data, status) {
                
              //   // });
              // }

              var fecha_ayer = sumaFecha( -1, fecha_fin);

              $("#fecha_fin").val(fecha_ayer); $("#plazo").val(new_plazo);

              toastr.success(`Suma de fecha correctamente. </br> <h6 class="pt-1 mt-1 pb-1 mb-1">→ ${data.data.count_feriado} feriados encontrados. </h6> <h6 class="pt-1 mt-1">→ ${cant_sabados} sábados</h6>`)
            
            } else {
              ver_errores(data);
            }
            
          }).fail( function(e) { ver_errores(e); } );
          
        } else {

          toastr.error('Seleccione una plazo positivo')
        }
      } else {
        toastr.error('Seleccione una fecha INICIO')
      }
    } else {
      // toastr.error('Agregar un PLAZO válido')
    } 
  } 
}

function calcular_plazo_actividad() {

  var plazo = 0;  

  if ($('#fecha_inicio_actividad').val() != "" && $('#fecha_fin_actividad').val() != "") {

    var fecha1 = moment( format_a_m_d($('#fecha_inicio_actividad').val()) );

    var fecha2 = moment( format_a_m_d($('#fecha_fin_actividad').val()) );

    plazo = fecha2.diff(fecha1, 'days') + 1;
  } 

  $('.plazo_actividad').html(plazo);
  $('#plazo_actividad').val(plazo);
}

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M S  :::::::::::::::::::::::::::::::::::::::..

$(function () {    

  // validamos el formulario  

  $('#fecha_pago_obrero').on('change', function() { $(this).trigger('blur'); });
  $('#fecha_valorizacion').on('change', function() { $(this).trigger('blur'); });
  $('#empresa_acargo').on('change', function() { $(this).trigger('blur'); });

  $("#form-proyecto").validate({
    //ignore: '.select2-input, .select2-focusser',
    rules: {
      tipo_documento:   { maxlength: 45 },
      num_documento:    { required: true, minlength: 6, maxlength: 20 },
      empresa:          { required: true, minlength: 6, maxlength: 200 },
      nombre_proyecto:  { required: true, minlength: 6 },
      nombre_codigo:    {required: true, minlength: 4 },
      ubicacion:        {minlength: 6, maxlength: 300},
      actividad_trabajo:{minlength: 6},
      fecha_inicio:     {required: true,minlength: 1, maxlength: 25},
      fecha_fin:        {required: true,minlength: 1, maxlength: 25},
      dias_habiles:     {required: true,minlength: 1, maxlength: 11, digits: true, number: true},
      plazo:            {required: true,minlength: 1, maxlength: 11, number: true},
      costo:            { minlength: 1, maxlength: 20,  },
      garantia:         {required: true,  number: true, min: 0, max: 1, },
      fecha_pago_obrero:{required: true},
      fecha_valorizacion:{required: true}
    },
    messages: {
      num_documento:    { required: "Campo requerido.", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 20 caracteres.", },
      empresa:          { required: "Campo requerido.", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 200 caracteres.", },
      nombre_proyecto:  { required: "Campo requerido.", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 200 caracteres.", },
      nombre_codigo:    { required: "Campo requerido.", minlength: "MÍNIMO 4 caracteres.",  },
      ubicacion:        { minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 300 caracteres.", },
      actividad_trabajo:{ minlength: "MÍNIMO 6 caracteres.", },
      fecha_inicio:     { required: "Campo requerido.", minlength: "1 caracterer como minimo.", },
      fecha_fin:        { required: "Campo requerido.", minlength: "1 caracterer como minimo.", },
      dias_habiles:     { required: "Campo requerido.", minlength: "MÍNIMO 1 dígito.", maxlength: "MÁXIMO 11 dígitos.", digits: "Solo números positivos" },
      plazo:            { required: "Campo requerido.", minlength: "1 dígitos como minimo.", maxlength: "11 dígitos como máximo.", },
      costo:            { minlength: "1 dígitos como minimo.", maxlength: "20 dígitos como máximo.", },
      garantia:         {  required: "Requerido.", number: 'No número.', min: 'MÍNIMO 0', max: "MÁXIMO 1", },
      fecha_pago_obrero:{ required: "Campo requerido" },
      fecha_valorizacion:{ required: "Campo requerido" }
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
      guardaryeditar(e);       
    },
  });

  $('#fecha_pago_obrero').rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $('#fecha_valorizacion').rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $('#empresa_acargo').rules('add', { required: true, messages: {  required: "Campo requerido" } });

});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

function cuentaSabado(fi, ff){
  //console.log(fi + " / "+ ff);
  var inicio = new Date(fi); //Fecha inicial
  var fin = new Date(ff); //Fecha final
  var timeDiff = Math.abs(fin.getTime() - inicio.getTime());
  var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); //Días entre las dos fechas
  var cuentaFinde = 0; //Número de Sábados y Domingos
  var array = new Array(diffDays);

  for (var i=0; i < diffDays; i++) {
    //0 => Domingo - 6 => Sábado
    //console.log(inicio.getDay());
    if (inicio.getDay() == 5) {
      cuentaFinde++;
    }

    inicio.setDate(inicio.getDate() + 1);
  }

 return cuentaFinde;
}

// input decimal letra
$(function() {
  // $("#costo").bind("change keyup input", function() {
  //   var position = this.selectionStart - 1;
  //   //remove all but number and .
  //   var fixed = this.value.replace(/[^0-9\.]/g, "");
  //   if (fixed.charAt(0) === ".")
  //     //can't start with .
  //     fixed = fixed.slice(1);

  //   var pos = fixed.indexOf(".") + 1;
  //   if (pos >= 0)
  //     //avoid more than one .
  //     fixed = fixed.substr(0, pos) + fixed.slice(pos).replace(".", "");

  //   if (this.value !== fixed) {
  //     this.value = fixed;
  //     this.selectionStart = position;
  //     this.selectionEnd = position;
  //   }
  // });

  $("#dias_habilees").bind("change keyup input", function() {
    var position = this.selectionStart - 1;
    //remove all but number and .
    var fixed = this.value.replace(/[^0-9]/g, "");

    if (this.value !== fixed) {
      this.value = fixed;
      this.selectionStart = position;
      this.selectionEnd = position;
    }
  });
});


// $("#guardar_registro_valorizaciones").on("click", function (e) { $("#form-valorizaciones").submit(); });
// $("#form-valorizaciones").on("submit", function (e) { guardar_editar_valorizacion(e); }); 
//Date range picker
// $('#fecha_inicio_fin').daterangepicker({
//   dateFormat: 'YYYY/MM/DD',
//   autoUpdateInput: false,
//   inline: true,
//   locale: {
//     cancelLabel: 'Clear'
//   },
//   isInvalidDate: function(date) {
//     if (date.day() == 0 || date.day() == 1 || date.day() == 2 || date.day() == 3 || date.day() == 4 || date.day() == 5)
//       return false;
//     return true;
//   },    
// });
// $('input[name="fecha_inicio_fin"]').on('apply.daterangepicker', function(ev, picker) {
//   $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
// });

// $('input[name="fecha_inicio_fin"]').on('cancel.daterangepicker', function(ev, picker) {
//   $(this).val('');
// });

