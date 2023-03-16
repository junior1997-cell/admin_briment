//Requejo99@

var tabla_compra_grano;
var tabla_pago_compra_grano;

var tabla_compra_x_cliente;
var tabla_detalle_compra_x_cliente;

var pago_compra_total = 0;

var host = window.location.host == 'localhost'? `http://localhost/front_jdl/admin/dist/docs/compra_insumo/comprobante_compra/` : `${window.location.origin}/dist/docs/compra_insumo/comprobante_compra/` ;

var array_class_trabajador = [];

//Función que se ejecuta al inicio
function init() {

  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_ComprasGrano").addClass("menu-open");

  $("#mCompraGrano").addClass("active bg-green");

  $("#lComprasGranoV2").addClass("active");
  $('.lComprasGrano-img').attr('src', '../dist/svg/blanco-grano-cafe-ico.svg');

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/ajax_general.php?op=select2Persona_por_tipo&tipo=2", '#idcliente', null);
  lista_select2("../ajax/ajax_general.php?op=select2Persona_por_tipo&tipo=2", '#filtro_cliente', null);
  lista_select2("../ajax/ajax_general.php?op=select2Banco", '#banco', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════

  $("#guardar_registro_compras").on("click", function (e) {  $("#submit-form-compras").submit(); });
  $("#guardar_registro_proveedor").on("click", function (e) { $("#submit-form-proveedor").submit(); });
  $("#guardar_registro_pago_compra").on("click", function (e) { $("#submit-form-pago-compra").submit(); });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - COMPRAS ══════════════════════════════════════

  $("#idcliente").select2({templateResult: templateCliente, theme: "bootstrap4", placeholder: "Selecione cliente", allowClear: true, });

  $("#tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione Comprobante", allowClear: true, });

  $("#metodo_pago").select2({ theme: "bootstrap4", placeholder: "Selecione método", allowClear: true, });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - PAGO COMPRAS ══════════════════════════════════════
  $("#forma_pago_p").select2({ theme: "bootstrap4", placeholder: "Selecione forma de pago", allowClear: true, });
  // ══════════════════════════════════════ INITIALIZE SELECT2 - cliente ══════════════════════════════════════

  $("#banco").select2({templateResult: templateBanco, theme: "bootstrap4", placeholder: "Selecione un banco", allowClear: true, });
  $("#tipo_documento_per").select2({theme: "bootstrap4", placeholder: "Selecione un banco", allowClear: true, });
  
  // ══════════════════════════════════════ INITIALIZE SELECT2 - MATERIAL ══════════════════════════════════════

  // ══════════════════════════════════════ INITIALIZE SELECT2 - FILTROS ══════════════════════════════════════
  $("#filtro_tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione comprobante", allowClear: true, });
  $("#filtro_cliente").select2({ theme: "bootstrap4", placeholder: "Selecione Cliente", allowClear: true, });

  // Inicializar - Date picker  
  $('#filtro_fecha_inicio').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });
  $('#filtro_fecha_fin').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });

  // ══════════════════════════════════════ I N I T I A L I Z E   N U M B E R   F O R M A T ══════════════════════════════════════
  $('#precio_unitario_p').number( true, 2 );
  $('#precio_sin_igv_p').number( true, 2 );
  $('#precio_igv_p').number( true, 2 );
  $('#precio_total_p').number( true, 2 );

  no_select_tomorrow('#fecha_compra');
  no_select_over_18('#nacimiento_per');

  $('#monto_pago_compra').number( true, 2 );

  // Formato para telefono
  $("[data-mask]").inputmask();
  $('.jq_image_zoom').zoom({ on:'grab' });
  //filtros();
}

$('.click-btn-fecha-inicio').on('click', function (e) {$('#filtro_fecha_inicio').focus().select(); });
$('.click-btn-fecha-fin').on('click', function (e) {$('#filtro_fecha_fin').focus().select(); });

function templateBanco (state) {
  //console.log(state);
  if (!state.id) { return state.text; }
  var baseUrl = state.title != '' ? `../dist/docs/banco/logo/${state.title}`: '../dist/docs/banco/logo/logo-sin-banco.svg'; 
  var onerror = `onerror="this.src='../dist/docs/banco/logo/logo-sin-banco.svg';"`;
  var $state = $(`<span><img src="${baseUrl}" class="img-circle mr-2 w-25px" ${onerror} />${state.text}</span>`);
  return $state;
};

function templateCliente (state) {
  //console.log(state);
  if (!state.id) { return state.text; }
  var baseUrl = state.title != '' ? `../dist/docs/persona/perfil/${state.title}`: '../dist/svg/user_default.svg'; 
  var onerror = `onerror="this.src='../dist/svg/user_default.svg';"`;
  var $state = $(`<span><img src="${baseUrl}" class="img-circle mr-2 w-25px" ${onerror} />${state.text}</span>`);
  return $state;
};


//vaucher - pago
$("#doc3_i").click(function () { $("#doc3").trigger("click"); });
$("#doc3").change(function (e) { addImageApplication(e, $("#doc3").attr("id")); });

// Eliminamos el COMPROBANTE - PAGO
function doc3_eliminar() {
  $("#doc3").val("");
  $("#doc_old_3").val("");  
  $("#doc3_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
  $("#doc3_nombre").html("");
}

// ::::::::::::::::::::::::::::::::::::::::::::: S E C C I O N   C O M P R A S :::::::::::::::::::::::::::::::::::::::::::::

//Función limpiar
function limpiar_form_compra() {
  $(".tooltip").removeClass("show").addClass("hidde");

  $("#idcompra_grano").val("");
  $("#idcliente").val("null").trigger("change");
  $("#tipo_comprobante").val("Ninguno").trigger("change");
  $("#metodo_pago").val("").trigger("change");

  $("#numero_comprobante").val("");
  $("#val_igv").val(0);
  $("#descripcion").val("");
  $("#fecha_compra").val(moment().format('YYYY-MM-DD'));

  $(".tipo_grano").val(''); 
  $(".unidad_medida").val('KILO'); 
  $(".peso_bruto").val('0.00'); 
  $(".sacos").val('0.00'); 
  $(".dcto_humedad").val('0.00'); 
  $(".dcto_rendimiento").val('0.00'); 
  $(".dcto_segunda").val('0.00'); 
  $(".dcto_cascara").val('0.00'); 
  $(".dcto_taza").val('0.00'); 
  $(".dcto_tara").val('0.00'); 
  $(".peso_neto").val('0.00'); 
  $(".quintal_neto").val('0.00'); 
  $(".precio_con_igv").val('0.00'); 
  $(".descuento").val('0.00').trigger("change");
  
  $("#total_compra").val("");  
  $(".total_compra").html("0");

  $(".subtotal_compra").html("S/ 0.00");
  $("#subtotal_compra").val("");

  $(".igv_compra").html("S/ 0.00");
  $("#igv_compra").val("");

  $(".total_compra").html("S/ 0.00");
  $("#total_compra").val("");

  $(".filas").remove();

  cont = 0;

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function show_hide_form(flag) {
  $('.h1-nombre-cliente').html('');
  if (flag == 1) {
    // show tabla principal
    $("#div_tabla_compra").show();
    $("#div_tabla_compra_cliente").hide();
    $("#div_form_agregar_compras_grano").hide();
    $("#div_tabla_pago_compras_grano").hide();
    $("#div_tabla_detalle_compra_x_cliente").hide();

    $("#btn_agregar").show();
    $("#btn_regresar").hide();    
    $("#btn_pagar").hide();
    
  } else if(flag == 2) {
    // show tabla detalle compra por cliente
    $("#div_tabla_compra").hide();
    $("#div_form_agregar_compras_grano").hide();
    $("#div_tabla_pago_compras_grano").hide();
    $("#div_tabla_detalle_compra_x_cliente").show();

    $("#btn_agregar").hide();
    $("#btn_regresar").show();    
    $("#btn_pagar").hide();
  } else if(flag == 3) {
    // show form compra
    $("#div_tabla_compra").hide();
    $("#div_tabla_compra_cliente").hide();
    $("#div_form_agregar_compras_grano").show();
    $("#div_tabla_pago_compras_grano").hide();
    $("#div_tabla_detalle_compra_x_cliente").hide();

    $("#btn_agregar").hide();
    $("#btn_regresar").show();    
    $("#btn_pagar").hide();
  } else if(flag == 4) {
    // show form pago
    $("#div_tabla_compra").hide();
    $("#div_tabla_compra_cliente").hide();
    $("#div_form_agregar_compras_grano").hide();
    $("#div_tabla_pago_compras_grano").show();
    $("#div_tabla_detalle_compra_x_cliente").hide();

    $("#btn_agregar").hide();
    $("#btn_regresar").show();    
    $("#btn_pagar").show();
  }
  array_class_trabajador = [];  

  // $(".leyecnda_pagos").hide();
  // $(".leyecnda_saldos").hide();

  limpiar_form_compra();
  limpiar_form_cliente();
}

//TABLA - COMPRAS
function tbla_principal( fecha_1, fecha_2, id_cliente, comprobante) {
  //console.log(idproyecto);
  tabla_compra_grano = $("#tabla-compra-grano").dataTable({
    responsive: true, 
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,10,11,4,12,13,6,7,14,9], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,10,11,4,12,13,6,7,14,9], } }, 
      { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,2,3,10,11,4,12,13,6,7,14,9], } } ,        
    ],
    ajax: {
      url: `../ajax/compra_cafe_v2.php?op=tbla_principal&fecha_1=${fecha_1}&fecha_2=${fecha_2}&id_cliente=${id_cliente}&comprobante=${comprobante}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },     
    createdRow: function (row, data, ixdex) {
      //console.log(data);
      if (data[1] != '') { $("td", row).eq(1).addClass('text-nowrap'); }
      if (data[5] != '') { $("td", row).eq(5).addClass('text-left'); }
      if (data[6] != '') { $("td", row).eq(6).addClass('text-nowrap'); }    
      if (data[9] != '' || data[9] == 0 ) { 
        $("td", row).eq(9).addClass('text-right'); 
        parseFloat(data[9])<0? $("td", row).eq(9).addClass('bg-red') : ""; 
        parseFloat(data[9]) == 0 ? $("td", row).eq(9).addClass('bg-success') : ""; 
      } 
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    footerCallback: function( tfoot, data, start, end, display ) {
      var api = this.api(); var total = api.column( 7 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api.column( 7 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(total)}</span>` );
      
      var api2 = this.api(); var total2 = api2.column( 14 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api2.column( 8 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(total2)}</span>` );
      $( api2.column( 14 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(total2)}</span>` );

      var api3 = this.api(); var total3 = api3.column( 9 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api3.column( 9 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(total3)}</span>` );
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
    columnDefs: [
      { targets: [7], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
      { targets: [9], render: $.fn.dataTable.render.number( ',', '.', 2) },
      { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      { targets: [10,11,12,13,14],  visible: false,  searchable: false,  },
    ],
  }).DataTable();

  $(tabla_compra_grano).ready(function () {  $('.cargando').hide(); });

  //console.log(idproyecto);
  tabla_compra_x_cliente = $("#tabla-compra-cliente").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: ["copyHtml5", "excelHtml5", "pdf"],
    ajax: {
      url: `../ajax/compra_cafe_v2.php?op=tabla_compra_x_cliente`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {
      //console.log(data);
      if (data[5] != '') { $("td", row).eq(5).addClass('text-right'); }
    },
    footerCallback: function( tfoot, data, start, end, display ) {
      var api = this.api(); 
      var total = api.column( 5 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api.column( 5 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(total)}</span>` );      
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
    columnDefs: [
      { targets: [5], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
      //{ targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      // { targets: [8,11],  visible: false,  searchable: false,  },
    ],
  }).DataTable();
}

//facturas agrupadas por cliente.
function listar_facuras_cliente(idcliente, nombre) {
  
  show_hide_form(2);

  $('.h1-nombre-cliente').html(`- <b>${nombre}</b>`);

  tabla_detalle_compra_x_cliente = $("#tabla-detalle-compra-grano-x-cliente").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: ["copyHtml5", "excelHtml5",  "pdf",],
    ajax: {
      url: `../ajax/compra_cafe_v2.php?op=listar_detalle_compra_x_cliente&idcliente=${idcliente}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 5, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
    columnDefs: [
      { targets: [5], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
      { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
    ],
  }).DataTable();
}

//Función para guardar o editar - COMPRAS
function guardar_y_editar_compras(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-compras")[0]);  

  Swal.fire({
    title: "¿Está seguro que deseas guardar esta compra?",
    html: "Verifica que todos lo <b>campos</b>  esten <b>conformes</b>!!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Guardar!",
    preConfirm: (input) => {
      return fetch("../ajax/compra_cafe_v2.php?op=guardar_y_editar_compra_grano", {
        method: 'POST', // or 'PUT'
        body: formData, // data can be `string` or {object}!        
      }).then(response => {
        //console.log(response);
        if (!response.ok) { throw new Error(response.statusText) }
        return response.json();
      }).catch(error => { Swal.showValidationMessage(`<b>Solicitud fallida:</b> ${error}`); });
    },
    showLoaderOnConfirm: true,
  }).then((result) => {
    if (result.isConfirmed) {
      if (result.value.status == true){        
        Swal.fire("Correcto!", "Compra guardada correctamente", "success");

        tabla_compra_grano.ajax.reload(null, false);
        tabla_compra_x_cliente.ajax.reload(null, false);

        limpiar_form_compra(); show_hide_form(1);
        
        $("#modal-agregar-usuario").modal("hide");        
      } else {
        ver_errores(result.value);
      }      
    }
  });  
}

//Función para eliminar registros
function eliminar_compra(idcompra_proyecto, nombre) {

  $(".tooltip").removeClass("show").addClass("hidde");

  crud_eliminar_papelera(
    "../ajax/compra_cafe_v2.php?op=anular",
    "../ajax/compra_cafe_v2.php?op=eliminar_compra", 
    idcompra_proyecto, 
    "!Elija una opción¡", 
    `<b class="text-danger">${nombre}</b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu compra ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu compra ha sido Eliminado.' ) }, 
    function(){ tabla_compra_grano.ajax.reload(null, false); tabla_compra_x_cliente.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );
}

// .......::::::::::::::::::::::::::::::::::::::::: AGREGAR FACURAS, BOLETAS, NOTA DE VENTA, ETC :::::::::::::::::::::::::::::::::::.......
//Declaración de variables necesarias para trabajar con las compras y sus detalles

var impuesto = 18;
var cont = 0;
var detalles = 0;

function default_val_igv() { if ($("#tipo_comprobante").select2("val") == "Factura") { $("#val_igv").val(0.18); } }

function modificarSubtotales() {  

  var val_igv = $('#val_igv').val(); //console.log(array_class_trabajador);

  $('.convert_a_q').html( `(${$('.tipo_grano').val() == 'PERGAMINO' ? '55.2' : '56.0'})` );

  if ($("#tipo_comprobante").select2("val") == "Factura") {    

    $(".hidden").show(); //Mostramos: IGV, PRECIO SIN IGV
    $("#colspan_subtotal").attr("colspan", 16); //cambiamos el: colspan    
    $("#val_igv").prop("readonly",false);

    if (val_igv == '' || val_igv <= 0) {
      $("#tipo_gravada").val('NO GRAVADA');
      $(".tipo_gravada").html('NO GRAVADA');
      $(".val_igv").html(`IGV (0%)`);
    } else {
      $("#tipo_gravada").val('GRAVADA');
      $(".tipo_gravada").html('GRAVADA');
      $(".val_igv").html(`IGV (${(parseFloat(val_igv) * 100).toFixed(2)}%)`);
    }      

    var peso_bruto      = $(`.peso_bruto`).val() == null || $(`.peso_bruto`).val() == '' ? 0 : parseFloat($(`.peso_bruto`).val());
    // var dcto_humedad = parseFloat($(`.dcto_humedad`).val());
    // var dcto_cascara = parseFloat($(`.dcto_cascara`).val());
    var dcto_tara       = $(`.dcto_tara`).val() == null || $(`.dcto_tara`).val() == '' ? 0 : parseFloat($(`.dcto_tara`).val());
    var dcto_humedad    = 0, dcto_cascara = 0;

    var peso_neto       = peso_bruto - (dcto_humedad + dcto_cascara + dcto_tara);
    var quintal_neto    = peso_neto / ( $(`.tipo_grano`).val() == 'PERGAMINO' ? 55.2 : 56 )  ;
    $(`.peso_neto`).val(redondearExp(peso_neto, 2));
    $(`.quintal_neto`).val(redondearExp(quintal_neto, 2));

    var precio_con_igv  = $(`.precio_con_igv`).val() == null || $(`.precio_con_igv`).val() == '' ? 0 : parseFloat($(`.precio_con_igv`).val());
    var descuento       = $(`.descuento`).val() == null || $(`.descuento`).val() == '' ? 0 : parseFloat($(`.descuento`).val());
    var subtotal_producto = 0;

    // Calculamos: Precio sin IGV
    var precio_sin_igv  = ( quitar_igv_del_precio(precio_con_igv, val_igv, 'decimal')).toFixed(2);
    $(`.precio_sin_igv`).val(precio_sin_igv);

    // Calculamos: IGV
    var igv             = (parseFloat(precio_con_igv) - parseFloat(precio_sin_igv)).toFixed(2);
    $(`.precio_igv`).val(igv);

    // Calculamos: Subtotal de cada producto
    subtotal_producto   = quintal_neto * parseFloat(precio_con_igv) - descuento;
    $(`.subtotal_producto`).html(formato_miles(subtotal_producto));
    $(`.input_subtotal_producto`).val( redondearExp(subtotal_producto, 2) );

    calcularTotalesConIgv();
    
  } else if ($("#tipo_comprobante").select2("val") != "Factura" || $("#tipo_comprobante").select2("val") == null) {

    $(".hidden").hide(); //Ocultamos: IGV, PRECIO CON IGV
    $("#colspan_subtotal").attr("colspan", 14); //cambiamos el: colspan

    $("#val_igv").val(0);
    $("#val_igv").prop("readonly",true);
    $(".val_igv").html('IGV (0%)');

    $("#tipo_gravada").val('NO GRAVADA');
    $(".tipo_gravada").html('NO GRAVADA');

    var peso_bruto      = $(`.peso_bruto`).val() == null || $(`.peso_bruto`).val() == '' ? 0 : parseFloat($(`.peso_bruto`).val());
    // var dcto_humedad = parseFloat($(`.dcto_humedad`).val());
    // var dcto_cascara = parseFloat($(`.dcto_cascara`).val());
    var dcto_tara       = $(`.dcto_tara`).val() == null || $(`.dcto_tara`).val() == '' ? 0 : parseFloat($(`.dcto_tara`).val());
    var dcto_humedad    = 0, dcto_cascara = 0;

    var peso_neto       = peso_bruto - (dcto_humedad + dcto_cascara + dcto_tara);
    var quintal_neto    = peso_neto / ( $(`.tipo_grano`).val() == 'PERGAMINO' ? 55.2 : 56 )  ;
    $(`.peso_neto`).val(redondearExp(peso_neto, 2));
    $(`.quintal_neto`).val(redondearExp(quintal_neto, 2));
    
    var precio_con_igv  = $(`.precio_con_igv`).val() == null || $(`.precio_con_igv`).val() == '' ? 0 : parseFloat($(`.precio_con_igv`).val());
    var descuento       = $(`.descuento`).val() == null || $(`.descuento`).val() == '' ? 0 : parseFloat($(`.descuento`).val());
    var subtotal_producto = 0;

    // Calculamos: IGV
    var precio_sin_igv  = precio_con_igv;
    $(`.precio_sin_igv`).val(precio_sin_igv);

    // Calculamos: precio + IGV
    $(`.precio_igv`).val(0);

    // Calculamos: Subtotal de cada producto
    subtotal_producto   = quintal_neto * parseFloat(precio_con_igv) - descuento;
    $(`.subtotal_producto`).html(formato_miles(subtotal_producto));
    $(`.input_subtotal_producto`).val( redondearExp(subtotal_producto, 2) );
    calcularTotalesSinIgv();   
    
  }
  capturar_pago_compra();
  toastr_success("Actualizado!!",`Precio Actualizado.`, 700);
}

function calcularTotalesSinIgv() {
  var total = 0.0;
  var igv = 0;
  var mtotal = 0;

  total = parseFloat(quitar_formato_miles($(`.subtotal_producto`).text()));

  $(".subtotal_compra").html("S/ " + formato_miles(total));
  $("#subtotal_compra").val(redondearExp(total, 2));

  $(".igv_compra").html("S/ 0.00");
  $("#igv_compra").val(0.0);
  $(".val_igv").html('IGV (0%)');

  $(".total_compra").html("S/ " + formato_miles(total.toFixed(2)));
  $("#total_compra").val(redondearExp(total, 2));
  
}

function calcularTotalesConIgv() {
  var val_igv = $('#val_igv').val();
  var igv = 0;
  var total = 0.0;

  var subotal_sin_igv = 0;
  total = parseFloat(quitar_formato_miles($(`.subtotal_producto`).text()));

  //console.log(total); 
  subotal_sin_igv = quitar_igv_del_precio(total, val_igv, 'decimal').toFixed(2);
  igv = (parseFloat(total) - parseFloat(subotal_sin_igv)).toFixed(2);

  $(".subtotal_compra").html(`S/ ${formato_miles(subotal_sin_igv)}`);
  $("#subtotal_compra").val(redondearExp(subotal_sin_igv, 2));

  $(".igv_compra").html("S/ " + formato_miles(igv));
  $("#igv_compra").val(igv);

  $(".total_compra").html("S/ " + formato_miles(total.toFixed(2)));
  $("#total_compra").val(redondearExp(total, 2));

  total = 0.0;
}

function quitar_igv_del_precio(precio , igv, tipo ) {
  
  var precio_sin_igv = 0;

  switch (tipo) {
    case 'decimal':

      // validamos el valor del igv ingresado
      if (igv > 0 && igv <= 1) { 
        $("#tipo_gravada").val('GRAVADA');
        $(".tipo_gravada").html('GRAVADA');
        $(".val_igv").html(`IGV (${(parseFloat(igv) * 100).toFixed(2)}%)`); 
      } else { 
        igv = 0; 
        $(".val_igv").html('IGV (0%)'); 
        $("#tipo_gravada").val('NO GRAVADA');
        $(".tipo_gravada").html('NO GRAVADA');
      }

      if (parseFloat(precio) != NaN && igv > 0 ) {
        precio_sin_igv = ( parseFloat(precio) * 100 ) / ( ( parseFloat(igv) * 100 ) + 100 )
      }else{
        precio_sin_igv = precio;
      }
    break;

    case 'entero':
      
      // validamos el valor del igv ingresado
      if (igv > 0 && igv <= 100) { 
        $("#tipo_gravada").val('GRAVADA');
        $(".tipo_gravada").html('GRAVADA');
        $(".val_igv").html(`IGV (${parseFloat(igv)}%)`); 
      } else { 
        igv = 0; 
        $(".val_igv").html('IGV (0%)'); 
        $("#tipo_gravada").val('NO GRAVADA');
        $(".tipo_gravada").html('NO GRAVADA');
      }

      if (parseFloat(precio) != NaN && igv > 0 ) {
        precio_sin_igv = ( parseFloat(precio) * 100 ) / ( parseFloat(igv)  + 100 )
      }else{
        precio_sin_igv = precio;
      }
    break;
  
    default:
      $(".val_igv").html('IGV (0%)');
      toastr_error("Vacio!!","No has difinido un tipo de calculo de IGV", 700);
    break;
  } 
  
  return precio_sin_igv; 
}

function ocultar_comprob() {
  if ($("#tipo_comprobante").select2("val") == "Ninguno" || $("#tipo_comprobante").select2("val") == null) {
    $("#content-serie-comprobante").hide();
    $("#content-establecimiento").removeClass("col-lg-6").addClass("col-lg-12");
  } else {
    $("#content-serie-comprobante").show();
    $("#content-establecimiento").removeClass("col-lg-12").addClass("col-lg-6");
  }
}

function capturar_pago_compra() {

  $(".span-pago-compra").html('(Selec metodo pago)');
  $("#monto_pago_compra").prop("readonly", true).val(0);
  $("#fecha_proximo_pago").prop("readonly", true);

  var date_compra = $('#fecha_compra').val() == '' || $('#fecha_compra').val() == null ? "" : $('#fecha_compra').val();   

  if ($("#idcompra_grano").val() == "" || $("#idcompra_grano").val() == null) {
    if ($("#metodo_pago").select2("val") == "CONTADO") {      
      var total_compra = $("#total_compra").val();
      $("#monto_pago_compra").val(total_compra);   
      $(".span-pago-compra").html('(Contado)');  
      $("#fecha_proximo_pago").val(date_compra); 
    } else if ($("#metodo_pago").select2("val") == "CREDITO") {
      date_compra == '' || date_compra == null ? "" : restrigir_fecha_ant('#fecha_proximo_pago', date_compra) ;
      $("#monto_pago_compra").prop("readonly", false).val(0);
      $("#fecha_proximo_pago").prop("readonly", false);
      $(".span-pago-compra").html('(Crédito)'); 
    }
  } else {
    if ($("#metodo_pago").select2("val") == "CONTADO") {      
      $("#fecha_proximo_pago").val(date_compra); 
    } else if ($("#metodo_pago").select2("val") == "CREDITO") {
      $("#fecha_proximo_pago").prop("readonly", false);
    }
  }  
}


//mostramos para editar el datalle del comprobante de la compras
function ver_compra_editar(idcompra_grano) {

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  limpiar_form_compra();
  array_class_trabajador = [];

  cont = 0;
  detalles = 0;
  show_hide_form(3);

  $.post("../ajax/compra_cafe_v2.php?op=ver_compra_editar", { 'idcompra_grano': idcompra_grano }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);

    if (e.status == true) {

      if (e.data.tipo_comprobante == "Factura") {
        $(".content-igv").show();
        $(".content-tipo-comprobante").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
        $(".content-descripcion").removeClass("col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
        $(".content-serie-comprobante").show();
      } else if (e.data.tipo_comprobante == "Boleta" || e.data.tipo_comprobante == "Nota de venta") {
        $(".content-serie-comprobante").show();
        $(".content-igv").hide();
        $(".content-tipo-comprobante").removeClass("col-lg-4 col-lg-5").addClass("col-lg-5");
        $(".content-descripcion").removeClass(" col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
      } else if (e.data.tipo_comprobante == "Ninguno") {
        $(".content-serie-comprobante").hide();
        $(".content-serie-comprobante").val("");
        $(".content-igv").hide();
        $(".content-tipo-comprobante").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
        $(".content-descripcion").removeClass(" col-lg-4 col-lg-5 col-lg-7").addClass("col-lg-8");
      } else {
        $(".content-serie-comprobante").show();
      }

      $("#idcompra_grano").val(e.data.idcompra_grano);
      $("#fecha_compra").val(e.data.fecha_compra).trigger("change");
      $("#numero_comprobante").val(e.data.numero_comprobante);
      $("#val_igv").val(e.data.val_igv);
      $("#descripcion").val(e.data.descripcion);
      
      $("#idcliente").val(e.data.idpersona).trigger("change");
      $("#metodo_pago").val(e.data.metodo_pago).trigger("change");
      $("#fecha_proximo_pago").val(e.data.fecha_proximo_pago);
      $("#tipo_comprobante").val(e.data.tipo_comprobante).trigger("change");   
      
      
      $(".tipo_grano").val(e.data.detalle_compra.tipo_grano); 
      $(".unidad_medida").val(e.data.detalle_compra.unidad_medida); 
      $(".peso_bruto").val(e.data.detalle_compra.peso_bruto); 
      $(".sacos").val(e.data.detalle_compra.sacos); 
      $(".dcto_humedad").val(e.data.detalle_compra.dcto_humedad); 
      $(".dcto_rendimiento").val(e.data.detalle_compra.dcto_rendimiento); 
      $(".dcto_segunda").val(e.data.detalle_compra.dcto_segunda); 
      $(".dcto_cascara").val(e.data.detalle_compra.dcto_cascara); 
      $(".dcto_taza").val(e.data.detalle_compra.dcto_taza); 
      $(".dcto_tara").val(e.data.detalle_compra.dcto_tara); 
      $(".peso_neto").val(e.data.detalle_compra.peso_neto); 
      $(".quintal_neto").val(e.data.detalle_compra.quintal_neto); 
      $(".precio_con_igv").val(e.data.detalle_compra.precio_con_igv); 
      $(".descuento").val(e.data.detalle_compra.descuento).trigger("change");

      modificarSubtotales();

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
      
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );
}


//mostramos el detalle del comprobante de la compras
function ver_detalle_compras(idcompra_grano) {

  $("#cargando-5-fomulario").hide();
  $("#cargando-6-fomulario").show();

  $("#print_pdf_compra").addClass('disabled');
  $("#excel_compra").addClass('disabled');

  $("#modal-ver-detalle-compras-grano").modal("show");

  $.post("../ajax/ajax_general.php?op=ver_detalle_compras_grano_v2&idcompra_grano=" + idcompra_grano, function (e) {
    e = JSON.parse(e);
    if (e.status == true) {
      $(".detalle_de_compra_grano").html(e.data);       

      $("#print_pdf_compra").removeClass('disabled');
      $("#print_pdf_compra").attr('href', `../reportes/pdf_compra_cafe_v2.php?id=${idcompra_grano}` );
      $("#excel_compra").removeClass('disabled');

      $("#cargando-5-fomulario").show();
      $("#cargando-6-fomulario").hide();

      $('[data-toggle="tooltip"]').tooltip();
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

// :::::::::::::::::::::::::: S E C C I O N   P A G O   C O M P R A  ::::::::::::::::::::::::::

// abrimos el navegador de archivos
$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addImageApplication(e,$("#doc1").attr("id")) });

// Eliminamos el doc 1
function doc1_eliminar() {

	$("#doc1").val("");

	$("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc1_nombre").html("");
}

function limpiar_form_pago_compra() {  

  $("#idpago_compra_grano_p").val(""); 
  $("#forma_pago_p").val("null").trigger("change"); 
  $("#fecha_pago_p").val(""); 
  $("#monto_p").val(""); 
  $("#descripcion_p").val(""); 
  $(".deuda-actual").val("0.00"); 

  $("#doc_old_1").val("");
  $("#doc1").val("");  
  $('#doc1_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc1_nombre').html(""); 
  
  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function tbla_pago_compra( idcompra_grano, total_compra, total_pago, cliente) {

  show_hide_form(4);
  $("#idcompra_grano_p").val(idcompra_grano);
  $("#total_de_compra").html(formato_miles(total_compra));
  $(".h1-nombre-cliente").html(` - <b>${cliente}</b>` );

  tabla_pago_compra_grano = $("#tabla-pagos-compras").dataTable({
    responsive: true, 
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5], } }, 
      { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,2,3,4,5], } } ,        
    ],
    ajax: {
      url: `../ajax/compra_cafe_v2.php?op=tabla_pago_compras&idcompra_grano=${idcompra_grano}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },     
    createdRow: function (row, data, ixdex) {
      //console.log(data);
      if (data[1] != '') { $("td", row).eq(1).addClass('text-nowrap'); }
    },
    footerCallback: function( tfoot, data, start, end, display ) {
      var api = this.api(); 
      var total = api.column( 4 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api.column( 4 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right" id="total_depositos">${formato_miles(total)}</span>` );      
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
    columnDefs: [
      { targets: [4], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
      //{ targets: [9], render: $.fn.dataTable.render.number( ',', '.', 2) },
      { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      //{ targets: [10,11,12,13],  visible: false,  searchable: false,  },
    ],
  }).DataTable();

}

//Función para eliminar registros
function eliminar_pago_compra(idpago_compra_grano, nombre) {

  $(".tooltip").removeClass("show").addClass("hidde");

  crud_eliminar_papelera(
    "../ajax/compra_cafe_v2.php?op=papelera_pago_compra",
    "../ajax/compra_cafe_v2.php?op=eliminar_pago_compra", 
    idpago_compra_grano, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu compra ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu compra ha sido Eliminado.' ) }, 
    function(){ $("#total_depositos").html('0.00'); pago_compra_total = 0; tabla_pago_compra_grano.ajax.reload(null, false); },
    function(){ tabla_compra_x_cliente.ajax.reload(null, false); },
    false, 
    false,
    false
  );
}

function calcular_deuda() {
  var monto_actual = $('#monto_p').val() == '' || $('#monto_p').val() == null ? 0 : quitar_formato_miles($('#monto_p').val());
  var monto_pagados = $('#total_depositos').text() == '' || $('#total_depositos').text() == null ? 0 : quitar_formato_miles($('#total_depositos').text());
  var monto_de_compra = $('#total_de_compra').text() == '' || $('#total_de_compra').text() == null ? 0 : quitar_formato_miles($('#total_de_compra').text());
  var idpago_compra_grano_p = $('#idpago_compra_grano_p').val();

  var monto_deuda = 0;
  if (idpago_compra_grano_p == ''  || idpago_compra_grano_p == null) {
    monto_deuda = parseFloat(monto_de_compra) - parseFloat(monto_pagados) - parseFloat(monto_actual);
  } else {
    var btn_monto_pagados = $(`#btn_monto_pagado_${idpago_compra_grano_p}`).attr("monto_pagado");
    monto_deuda = parseFloat(monto_de_compra) - (parseFloat(monto_pagados) - parseFloat(btn_monto_pagados)) - parseFloat(monto_actual) ;
  }  

  if (monto_deuda < 0) {
    $('.deuda-actual').html(formato_miles(monto_deuda)).addClass('input-no-valido').removeClass('input-valido input-no-valido-warning');
  } else if (monto_deuda == 0 ) {
    $('.deuda-actual').html(formato_miles(monto_deuda)).addClass('input-valido').removeClass('input-no-valido input-no-valido-warning');
  }else if (monto_deuda > 0) {
    $('.deuda-actual').html(formato_miles(monto_deuda)).addClass('input-no-valido-warning').removeClass('input-no-valido input-valid');
  }
}

//guardar cliente
function guardar_y_editar_pago_compra(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-pago-compras")[0]);

  $.ajax({
    url: "../ajax/compra_cafe_v2.php?op=guardar_y_editar_pago_compra",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      e = JSON.parse(e);
      try {
        if (e.status == true) {
          pago_compra_total = 0;
          tabla_pago_compra_grano.ajax.reload(null, false);
          Swal.fire("Correcto!", "cliente guardado correctamente.", "success");          
          limpiar_form_pago_compra();
          $("#modal-agregar-pago-compra").modal("hide");
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }       
      
      $("#guardar_registro_pago_compra").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_pago_compra").css({"width": percentComplete+'%'});
          $("#barra_progress_pago_compra").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_pago_compra").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_pago_compra").css({ width: "0%",  });
      $("#barra_progress_pago_compra").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_pago_compra").css({ width: "0%", });
      $("#barra_progress_pago_compra").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar_editar_pago(idpago_compra_grano) {
  $(".tooltip").removeClass("show").addClass("hidde");
  $("#cargando-7-fomulario").hide();
  $("#cargando-8-fomulario").show();
  
  limpiar_form_pago_compra();

  $("#modal-agregar-pago-compra").modal("show")

  $.post("../ajax/compra_cafe_v2.php?op=mostrar_editar_pago", { 'idpago_compra_grano': idpago_compra_grano }, function (e, status) {

    e = JSON.parse(e);  console.log(e);  

    if (e.status == true) {
      $("#idpago_compra_grano_p").val(e.data.idpago_compra_grano);
      $("#idcompra_grano_p").val(e.data.idcompra_grano); 
      $("#forma_pago_p").val(e.data.forma_pago).trigger("change");
      $("#fecha_pago_p").val(e.data.fecha_pago); 
      $("#monto_p").val(e.data.monto).trigger("change");
      $("#descripcion_p").val(e.data.descripcion); 

      if (e.data.comprobante == "" || e.data.comprobante == null  ) {
        $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
        $("#doc1_nombre").html('');
        $("#doc_old_1").val(""); $("#doc1").val("");
      } else {
        $("#doc_old_1").val(e.data.comprobante);
        $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(e.data.comprobante)}</i></div></div>`);
        // cargamos la imagen adecuada par el archivo
        $("#doc1_ver").html(doc_view_extencion(e.data.comprobante,'compra_grano', 'comprobante_pago', '100%', '210' ));            
      }

      $("#cargando-7-fomulario").show();
      $("#cargando-8-fomulario").hide();
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );
}

function ver_documento_pago(doc, name_download) {
  $('.tile-modal-comprobante').html(name_download);
  $('#modal-ver-comprobante-pago').modal('show');
  $('.div-view-comprobante-pago').html(doc_view_download_expand(doc, 'dist/docs/compra_grano/comprobante_pago',name_download,'100%', '410'));
  $('.jq_image_zoom').zoom({ on:'grab' });
}

// :::::::::::::::::::::::::: - S E C C I O N   D E S C A R G A S -  ::::::::::::::::::::::::::



// :::::::::::::::::::::::::: S E C C I O N   C L I E N T E  ::::::::::::::::::::::::::
// abrimos el navegador de archivos
$("#foto1_i").click(function() { $('#foto1').trigger('click'); });
$("#foto1").change(function(e) { addImage(e,$("#foto1").attr("id")) });

function foto1_eliminar() {
	$("#foto1").val("");
	$("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");
	$("#foto1_nombre").html("");
}

//Función limpiar
function limpiar_form_cliente() {
  $("#guardar_registro_proveedor").html('Guardar Cambios').removeClass('disabled');

  $("#idpersona_per").val(""); 
  $("#tipo_documento_per").val("null").trigger("change");
  $("#cargo_trabajador_per").val("1");
  $("#id_tipo_persona_per").val("2");

  $("#num_documento_per").val(""); 
  $("#nombre_per").val("");   
  $("#email_per").val(""); 
  $("#telefono_per").val(""); 
  $("#direccion_per").val("");

  $("#banco").val("").trigger("change");
  $("#cta_bancaria").val(""); 
  $("#cci").val(""); 
  $("#titular_cuenta").val(""); 

  $("#nacimiento_per").val("");
  $("#edad_per").val("");
  $(".edad_per").html("0 años.");  

  $("#input_socio_per").val("0"); 
  $(".sino_per").html('(NO)');
  $("#socio_per").prop('checked', false);  

  $("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");
	$("#foto1").val("");
	$("#foto1_actual").val("");  
  $("#foto1_nombre").html(""); 
  
  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

// damos formato a: Cta, CCI
function formato_banco() {

  if ($("#banco").select2("val") == null || $("#banco").select2("val") == "" || $("#banco").select2("val") == '1') {

    $("#cta_bancaria").prop("readonly",true);   $("#cci").prop("readonly",true);
  } else {
    
    $(".chargue-format-1").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>'); $(".chargue-format-2").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');

    $("#cta_bancaria").prop("readonly",false);   $("#cci").prop("readonly",false);

    $.post("../ajax/ajax_general.php?op=formato_banco", { idbanco: $("#banco").select2("val") }, function (e, status) {

      e = JSON.parse(e);  console.log(e); 

      if (e.status) {
        $(".chargue-format-1").html('Cuenta Bancaria'); $(".chargue-format-2").html('CCI');

        var format_cta = decifrar_format_banco(e.data.formato_cta); var format_cci = decifrar_format_banco(e.data.formato_cci);

        $("#cta_bancaria").inputmask(`${format_cta}`);

        $("#cci").inputmask(`${format_cci}`);
      } else {
        ver_errores(e);
      }      

    }).fail( function(e) { ver_errores(e); } );   
  }  
}

//guardar cliente
function guardar_proveedor(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-proveedor")[0]);

  $.ajax({
    url: "../ajax/compra_cafe_v2.php?op=guardar_y_editar_cliente",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      e = JSON.parse(e);
      try {
        if (e.status == true) {
          // toastr.success("cliente registrado correctamente");
          Swal.fire("Correcto!", "Cliente guardado correctamente.", "success");          
          limpiar_form_cliente();
          $("#modal-agregar-cliente").modal("hide");
          //Cargamos los items al select cliente
          lista_select2("../ajax/ajax_general.php?op=select2Persona_por_tipo&tipo=2", '#idcliente', e.data);
          lista_select2("../ajax/ajax_general.php?op=select2Persona_por_tipo&tipo=2", '#filtro_cliente', null);
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }       
      
      $("#guardar_registro_proveedor").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_proveedor").css({"width": percentComplete+'%'});
          $("#barra_progress_proveedor").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_proveedor").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_proveedor").css({ width: "0%",  });
      $("#barra_progress_proveedor").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_proveedor").css({ width: "0%", });
      $("#barra_progress_proveedor").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar_para_editar_cliente() {
  limpiar_form_cliente();

  $("#cargando-3-fomulario").hide();
  $("#cargando-4-fomulario").show();

  $('#modal-agregar-cliente').modal('show');
  $(".tooltip").remove();

  $.post("../ajax/compra_cafe_v2.php?op=mostrar_editar_cliente", { 'idcliente': $('#idcliente').select2("val") }, function (e, status) {

    e = JSON.parse(e);  console.log(e);

    if (e.status == true) {     
      $("#idpersona_per").val(e.data.idpersona);
      $("#tipo_documento_per").val(e.data.tipo_documento).trigger("change");     

      $("#nombre_per").val(e.data.nombres);
      $("#num_documento_per").val(e.data.numero_documento);
      $("#direccion_per").val(e.data.direccion);
      $("#telefono_per").val(e.data.celular);
      $("#email_per").val(e.data.correo);

      $("#nacimiento_per").val(e.data.fecha_nacimiento).trigger("change");
      $("#edad_per").val(e.data.edad);        
    
      $("#cta_bancaria").val(e.data.cuenta_bancaria).trigger("change"); 
      $("#cci").val(e.data.cci).trigger("change"); 
      $("#banco").val(e.data.idbancos).trigger("change"); 
      $("#titular_cuenta_per").val(e.data.titular_cuenta);

      $("#sueldo_mensual_per").val(e.data.sueldo_mensual);
      $("#sueldo_diario_per").val(e.data.sueldo_diario);  
      $("#cargo_trabajador_per").val(e.data.idcargo_trabajador);
      
      $("#id_tipo_persona_per").val(e.data.idtipo_persona); 

      $("#sueldo_mensual_per").val(e.data.sueldo_mensual);
      $("#sueldo_diario_per").val(e.data.sueldo_diario);  
      
      $("#input_socio_per").val(e.data.es_socio);       

      if (e.data.foto_perfil!="") {
        $("#foto1_i").attr("src", "../dist/docs/persona/perfil/" + e.data.foto_perfil);
        $("#foto1_actual").val(e.data.foto_perfil);
      }
      calcular_edad('#nacimiento_per','.edad_per','#edad_per'); 

      $("#cargando-3-fomulario").show();
      $("#cargando-4-fomulario").hide();
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); });
}

function extrae_ruc() {
  $('#ruc_dni_cliente').val(""); 
  if ($('#idcliente').select2("val") == null || $('#idcliente').select2("val") == '') { 
    $('.btn-editar-cliente').addClass('disabled').attr('data-original-title','Seleciona un cliente');
  } else { 
    if ($('#idcliente').select2("val") == 1) {
      $('.btn-editar-cliente').addClass('disabled').attr('data-original-title','No editable');      
    } else{
      var name_cliente = $('#idcliente').select2('data')[0].text;
      var ruc_dni = $('#idcliente').select2('data')[0].element.attributes.ruc_dni.value;
      $('.btn-editar-cliente').removeClass('disabled').attr('data-original-title',`Editar: ${recorte_text(name_cliente, 15)}`);   
      $('#ruc_dni_cliente').val(ruc_dni);  
    }
  }
  $('[data-toggle="tooltip"]').tooltip();
}

//Función para guardar o editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-comidas_ex")[0]);
 
  $.ajax({
    url: "../ajax/comidas_extras.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e); 
        if (e.status == true) {
          toastr.success('Registrado correctamente');
          tabla.ajax.reload(null, false); 
          limpiar();
          $("#modal-agregar-comidas_ex").modal("hide");
          total(fecha_1_r,fecha_2_r,id_proveedor_r,comprobante_r);
        }else{  
          ver_errores(e);
        } 
        $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');
      } catch (err) {
        console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>');
      } 

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
      $("#barra_progress").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress").css({ width: "0%", });
      $("#barra_progress").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..
$(function () {

  // Aplicando la validacion del select cada vez que cambie
  $("#idcliente").on('change', function() { $(this).trigger('blur'); });
  $("#tipo_comprobante").on('change', function() { $(this).trigger('blur'); });
  $("#metodo_pagos").on('change', function() { $(this).trigger('blur'); });

  $("#tipo_documento_per").on('change', function() { $(this).trigger('blur'); });
  $("#banco").on('change', function() { $(this).trigger('blur'); });

  $("#form-compras").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      idcliente:          { required: true },
      tipo_comprobante:   { required: true },
      numero_comprobante: { minlength: 2 },
      descripcion:        { minlength: 4 },
      fecha_compra:       { required: true },
      metodo_pago:        { required: true },
      val_igv:            { required: true, number: true, min:0, max:1 },
      tipo_grano:         { required: true, },
    },
    messages: {
      idcliente:          { required: "Campo requerido", },
      tipo_comprobante:   { required: "Campo requerido", },
      numero_comprobante: { minlength: "Minimo 2 caracteres", },
      descripcion:        { minlength: "Minimo 4 caracteres", },
      fecha_compra:       { required: "Campo requerido", },
      metodo_pago:        { required: "Campo requerido", },
      tipo_grano:         { required: "Campo requerido", },
      val_igv:            { required: "Campo requerido", number: 'Ingrese un número', min:'Mínimo 0', max:'Maximo 1' },
      peso_bruto:         { min: "Mínimo 0.01", step:'Mínimo 2 decimales', required: "Campo requerido"},
      sacos:              { min: "Mínimo 0.00", step:'Mínimo 2 decimales', required: "Campo requerido"},
      dcto_humedad:       { min: "Mínimo 0.00", step:'Mínimo 2 decimales', required: "Campo requerido"},
      dcto_rendimiento:   { min: "Mínimo 0.00", step:'Mínimo 2 decimales', required: "Campo requerido"},
      dcto_segunda:       { min: "Mínimo 0.00", step:'Mínimo 2 decimales', required: "Campo requerido"},
      dcto_cascara:       { min: "Mínimo 0.00", step:'Mínimo 2 decimales', required: "Campo requerido"},
      dcto_taza:          { min: "Mínimo 0.00", step:'Mínimo 2 decimales', required: "Campo requerido"},
      dcto_tara:          { min: "Mínimo 0.00", step:'Mínimo 2 decimales', required: "Campo requerido"},
      peso_neto:          { min: "Mínimo 0.01", step:'Mínimo 2 decimales', required: "Campo requerido"},
      quintal_neto:       { min: "Mínimo 0.01", step:'Mínimo 2 decimales', required: "Campo requerido"},
      precio_con_igv:     { min: "Mínimo 0.01", step:'Mínimo 2 decimales', required: "Campo requerido"},
      descuento:          { min: "Mínimo 0.00", step:'Mínimo 2 decimales', required: "Campo requerido"}
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

    submitHandler: function (form) {
      guardar_y_editar_compras(form);
    },
  });  

  $("#form-proveedor").validate({
    rules: {
      tipo_documento_per: { required: true },
      num_documento_per:  { required: true, minlength: 6, maxlength: 20 },
      nombre_per:         { required: true, minlength: 6, maxlength: 100 },
      email_per:          { email: true, minlength: 10, maxlength: 50 },
      direccion_per:      { minlength: 5, maxlength: 200 },
      telefono_per:       { minlength: 8 },
      cta_bancaria_per:   { minlength: 10,},
      banco_per:          { required: true},
    },
    messages: {
      tipo_documento_per: { required: "Campo requerido.", },
      num_documento_per:  { required: "Campo requerido.", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 20 caracteres.", },
      nombre_per:         { required: "Campo requerido.", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 100 caracteres.", },
      email_per:          { required: "Campo requerido.", email: "Ingrese un coreo electronico válido.", minlength: "MÍNIMO 10 caracteres.", maxlength: "MÁXIMO 50 caracteres.", },
      direccion_per:      { minlength: "MÍNIMO 5 caracteres.", maxlength: "MÁXIMO 200 caracteres.", },
      telefono_per:       { minlength: "MÍNIMO 8 caracteres.", },
      cta_bancaria_per:   { minlength: "MÍNIMO 10 caracteres.", },
      banco_per:          { required: "Campo requerido.", },
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
      guardar_proveedor(e);
    },
  });

  $("#form-pago-compras").validate({
    rules: {
      forma_pago_p: { required: true },
      fecha_pago_p: { required: true, },
      monto_p:      { required: true, },
      descripcion_p:{ minlength: 4, maxlength: 200 },
    },
    messages: {
      forma_pago_p: { required: "Campo requerido.", },
      fecha_pago_p: { required: "Campo requerido.", },
      monto_p:      { required: "Campo requerido.", },
      descripcion_p:{ minlength: "MÍNIMO 4 caracteres.", maxlength: "MÁXIMO 200 caracteres.", },
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
      guardar_y_editar_pago_compra(e);
    },
  });

  $("#idcliente").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#metodo_pago").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#tipo_comprobante").rules('add', { required: true, messages: {  required: "Campo requerido" } });

  $("#tipo_documento_per").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#banco").rules('add', { required: true, messages: {  required: "Campo requerido" } });
});


// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

function cargando_search() {
  $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ...`);
}

function filtros() {  

  var fecha_1       = $("#filtro_fecha_inicio").val();
  var fecha_2       = $("#filtro_fecha_fin").val();  
  var id_cliente  = $("#filtro_cliente").select2('val');
  var comprobante   = $("#filtro_tipo_comprobante").select2('val');   
  
  var nombre_cliente = $('#filtro_cliente').find(':selected').text();
  var nombre_comprobante = ' ─ ' + $('#filtro_tipo_comprobante').find(':selected').text();

  // filtro de fechas
  if (fecha_1 == "" || fecha_1 == null) { fecha_1 = ""; } else{ fecha_1 = format_a_m_d(fecha_1) == '-'? '': format_a_m_d(fecha_1);}
  if (fecha_2 == "" || fecha_2 == null) { fecha_2 = ""; } else{ fecha_2 = format_a_m_d(fecha_2) == '-'? '': format_a_m_d(fecha_2);} 

  // filtro de cliente
  if (id_cliente == '' || id_cliente == 0 || id_cliente == null) { id_cliente = ""; nombre_cliente = ""; }

  // filtro de trabajdor
  if (comprobante == '' || comprobante == null || comprobante == 0 ) { comprobante = ""; nombre_comprobante = "" }

  $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ${nombre_cliente} ${nombre_comprobante}...`);
  //console.log(fecha_1, fecha_2, id_cliente, comprobante);

  tbla_principal(fecha_1, fecha_2, id_cliente, comprobante);
}


//validando excedentes
function validando_excedentes() {
  var totattotal = quitar_formato_miles(localStorage.getItem("monto_total_p"));
  var monto_total_dep = quitar_formato_miles(localStorage.getItem("monto_total_dep"));
  var monto_entrada = $("#monto_pago").val();
  var total_suma = parseFloat(monto_total_dep) + parseFloat(monto_entrada);
  var debe = parseFloat(totattotal) - monto_total_dep;

  //console.log(typeof total_suma);

  if (total_suma > totattotal) {
    toastr_error("Exedente!!",`Monto excedido al total del monto a pagar!`, 700);
  } else {
    toastr_success("Aceptado!!",`Monto Aceptado.`, 700);
  }
}

// ver imagen grande del producto agregado a la compra
function ver_img_producto(img, nombre) {
  $("#ver_img_material").attr("src", `${img}`);
  $(".nombre-img-material").html(nombre);
  $("#modal-ver-img-material").modal("show");
}

function export_excel_detalle_factura() {
  $tabla = document.querySelector("#tabla_detalle_factura");
  let tableExport = new TableExport($tabla, {
    exportButtons: false, // No queremos botones
    filename: "Detalle comprobante", //Nombre del archivo de Excel
    sheetname: "detalle factura", //Título de la hoja
  });
  let datos = tableExport.getExportData(); console.log(datos);
  let preferenciasDocumento = datos.tabla_detalle_factura.xlsx;
  tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);

}

function autoincrement_comprobante(data) {
  var comprobante = $(data).select2('val');  

  if (comprobante == null || comprobante == '' ) {
    $('#numero_comprobante').val("");
  } else {
    
    $.post(`../ajax/ajax_general.php?op=autoincrement_comprobante`, function (e, textStatus, jqXHR) {
      e = JSON.parse(e); //console.log(e);

      if (comprobante == 'Boleta') {
        $('#numero_comprobante').val(`B-${e.data.compra_cafe_b}`);
      } else if (comprobante == 'Factura'){
        $('#numero_comprobante').val(`F-${e.data.compra_cafe_f}`);
      } else if (comprobante == 'Nota de venta'){
        $('#numero_comprobante').val(`NV-${e.data.compra_cafe_nv}`);
      }
      
    },);
  }
}

//Función para guardar o editar - COMPRAS
function guardar_y_editar_compras____________plantilla_cargando_POST(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-compras")[0]);

  var swal2_header = `<img class="swal2-image bg-color-252e38 b-radio-7px p-15px m-10px" src="../dist/gif/cargando.gif">`;

  var swal2_content = `<div class="row sweet_loader" >    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
      <div class="progress" id="barra_progress_compra_div">
        <div id="barra_progress_compra" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
          0%
        </div>
      </div>
    </div>
  </div>`;

  Swal.fire({
    title: "¿Está seguro que deseas guardar esta compra?",
    html: "Verifica que todos lo <b>campos</b>  esten <b>conformes</b>!!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Guardar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "../ajax/compra_cafe_v2.php?op=guardaryeditarcompra",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function() {
          Swal.fire({
            title: "Guardando...",
            html: 'Tu <b>información</b> se esta guradando en la <b>base de datos</b>.',
            showConfirmButton: false,
            didRender: function() { 
              /* solo habrá un swal2 abierta.*/               
              $('.swal2-header').prepend(swal2_header); 
              $('.swal2-content').prepend(swal2_content);
            }
          });
          $("#barra_progress_compra").addClass('progress-bar-striped progress-bar-animated');
        },
        success: function (e) {
          try {
            e = JSON.parse(e);
            if (e.status == true ) {
              // toastr.success("Usuario registrado correctamente");
              Swal.fire("Correcto!", "Compra guardada correctamente", "success");



              limpiar_form_compra(); show_hide_form();
              
              $("#modal-agregar-usuario").modal("hide");
              l_m();
              
            } else {
              // toastr.error(datos);
              Swal.fire("Error!", datos, "error");
              l_m();
            }
          } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); } 

        },
        xhr: function () {
          var xhr = new window.XMLHttpRequest();    
          xhr.upload.addEventListener("progress", function (evt) {    
            if (evt.lengthComputable) {    
              var percentComplete = (evt.loaded / evt.total)*100;
              /*console.log(percentComplete + '%');*/
              $("#barra_progress_compra").css({"width": percentComplete+'%'});    
              $("#barra_progress_compra").text(percentComplete.toFixed(2)+" %");
            }
          }, false);
          return xhr;
        }
      });
    }
  });  
}

