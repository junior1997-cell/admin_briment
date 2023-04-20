//Requejo99@
var reload_detraccion = "";

var tabla_venta_producto;

var tabla_venta_x_proveedor;
var tabla_detalle_compra_x_proveedor;

var tablamateriales;

var tabla_pago_venta;

var array_doc = [];
var host = window.location.host == 'localhost'? `http://localhost/admin_briment/dist/docs/compra_insumo/comprobante_compra/` : `${window.location.origin}/dist/docs/compra_insumo/comprobante_compra/` ;

var array_class_trabajador = [];

//Función que se ejecuta al inicio
function init() {

  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Ventas").addClass("menu-open bg-color-191f24");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#mVentas").addClass("active bg-color-0037a4 text-white");

  $("#lVentasProductos").addClass("active");

  $("#idproyecto").val(localStorage.getItem("nube_idproyecto"));

  listarmateriales();

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/ajax_general.php?op=select2Persona_por_tipo&tipo=2", '#idcliente', null);
  lista_select2("../ajax/ajax_general.php?op=select2Persona_por_tipo&tipo=2", '#filtro_proveedor', null);
  lista_select2("../ajax/ajax_general.php?op=select2Banco", '#banco', null);
  lista_select2("../ajax/ajax_general.php?op=select2UnidaMedida", '#unidad_medida_pro', null);
  lista_select2("../ajax/ajax_general.php?op=select2Categoria", '#categoria_producto_pro', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════

  $("#guardar_registro_ventas").on("click", function (e) {  $("#submit-form-ventas").submit(); });

  $("#guardar_registro_proveedor").on("click", function (e) { $("#submit-form-proveedor").submit(); });

  $("#guardar_registro_pago_venta").on("click", function (e) {  $("#submit-form-pago-venta").submit(); });

  $("#guardar_registro_comprobante_compra").on("click", function (e) {  $("#submit-form-comprobante-compra").submit();  });  

  $("#guardar_registro_material").on("click", function (e) {  $("#submit-form-producto").submit(); });  

  // ══════════════════════════════════════ INITIALIZE SELECT2 - FILTROS ══════════════════════════════════════
  $("#filtro_tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione comprobante", allowClear: true, });
  $("#filtro_proveedor").select2({ theme: "bootstrap4", placeholder: "Selecione cliente", allowClear: true, });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - ventas ══════════════════════════════════════
  $("#idcliente").select2({templateResult: templatePersona, theme: "bootstrap4", placeholder: "Selecione cliente", allowClear: true, });
  $("#tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione Comprobante", allowClear: true, });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - PAGO VENTAS ══════════════════════════════════════
  $("#forma_pago_pv").select2({ theme: "bootstrap4", placeholder: "Selecione", allowClear: true, });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - AGRICULTOR ══════════════════════════════════════
  $("#banco").select2({templateResult: templateBanco, theme: "bootstrap4", placeholder: "Selecione un banco", allowClear: true, });
  $("#tipo_documento_per").select2({theme:"bootstrap4", placeholder: "Selec. tipo Doc.", allowClear: true, });
  
  // ══════════════════════════════════════ INITIALIZE SELECT2 - P R O D U C T O ══════════════════════════════════════
  $("#unidad_medida_pro").select2({ theme: "bootstrap4", placeholder: "Seleccinar una unidad", allowClear: true, });
  $("#categoria_producto_pro").select2({ theme: "bootstrap4", placeholder: "Seleccinar una categoria", allowClear: true, });

  $("#metodo_pago").select2({ theme: "bootstrap4", placeholder: "Selecione método", allowClear: true, });

  no_select_tomorrow("#fecha_venta");
  no_select_tomorrow("#fecha_pago_pv");
  no_select_over_18('#nacimiento_per');

  // ══════════════════════════════════════ INITIALIZE SELECT2 - P R O D U C T O ══════════════════════════════════════
  //$('#filtro_fecha_inicio').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
  
  // Inicializar - Date picker  
  $('#filtro_fecha_inicio').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });
  $('#filtro_fecha_fin').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });

  // ══════════════════════════════════════ I N I T I A L I Z E   N U M B E R   F O R M A T ══════════════════════════════════════
  $('#precio_unitario_p').number( true, 2 );
  $('#precio_sin_igv_p').number( true, 2 );
  $('#precio_igv_p').number( true, 2 );
  $('#precio_total_p').number( true, 2 );

  $('#monto_pv').number( true, 2 );

  // Formato para telefono
  $("[data-mask]").inputmask();

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

function templatePersona (state) {
  //console.log(state);
  if (!state.id) { return state.text; }
  var baseUrl = state.title != '' ? `../dist/docs/persona/perfil/${state.title}`: '../dist/svg/user_default.svg'; 
  var onerror = `onerror="this.src='../dist/svg/user_default.svg';"`;
  var $state = $(`<span><img src="${baseUrl}" class="img-circle mr-2 w-25px" ${onerror} />${state.text}</span>`);
  return $state;
};

// ::::::::::::::::::::::::::::::::::::::::::::: S E C C I O N   C O M P R A S :::::::::::::::::::::::::::::::::::::::::::::

//Función limpiar
function limpiar_form_compra() {
  $(".tooltip").removeClass("show").addClass("hidde");

  $("#idventa_producto").val("");
  $("#idcliente").val("null").trigger("change");
  $("#tipo_comprobante").val("Ninguno").trigger("change");

  $("#metodo_pago").val("").trigger("change");

  $("#serie_comprobante").val("");
  $("#val_igv").val(0);
  $("#descripcion").val("");
  
  $("#total_venta").val("");  
  $(".total_venta").html("0");

  $(".subtotal_compra").html("S/ 0.00");
  $("#subtotal_compra").val("");

  $(".igv_venta").html("S/ 0.00");
  $("#igv_venta").val("");

  $(".total_venta").html("S/ 0.00");
  $("#total_venta").val("");

  $("#estado_detraccion").val("0");
  $('#my-switch_detracc').prop('checked', false); 

  $(".filas").remove();

  cont = 0;

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function table_show_hide(flag) {
  $('.h1-nombre-cliente').html('- aumenta tus ingresos');
  // tabla principal
  if ( flag == 1 ) {
    $("#btn-regresar").hide();
    $("#btn-agregar").show();
    $("#btn-pagar").hide();
    $("#guardar_registro_ventas").hide();  
    
    $("#div-tabla-venta").show();
    $("#div-tabla-factura-por-proveedor").hide();
    $("#div-form-agregar-ventas").hide();  
    $("#div-tabla-pago-ventas").hide();

    limpiar_form_compra();
    limpiar_form_proveedor();
    listarmateriales();
  //formulario venta 
  } else if ( flag == 2 ) {
    $("#btn-regresar").show();
    $("#btn-agregar").hide();
    $("#btn-pagar").hide();
    $("#guardar_registro_ventas").hide();  
    
    $("#div-tabla-venta").hide();
    $("#div-tabla-factura-por-proveedor").hide();
    $("#div-form-agregar-ventas").show();  
    $("#div-tabla-pago-ventas").hide();  
    array_class_trabajador = [];   
  
  // tabla facturas por proveedor
  } else if ( flag == 3 ) {

    $("#btn-regresar").show();
    $("#btn-agregar").hide();
    $("#btn-pagar").hide();
    $("#guardar_registro_ventas").hide();  
    
    $("#div-tabla-venta").hide();
    $("#div-tabla-factura-por-proveedor").show();
    $("#div-form-agregar-ventas").hide();  
    $("#div-tabla-pago-ventas").hide();
  // tabla pagos
  } else if ( flag == 4 ) {
    $("#btn-regresar").show();
    $("#btn-agregar").hide();
    $("#btn-pagar").show();
    $("#guardar_registro_ventas").hide();  
    
    $("#div-tabla-venta").hide();
    $("#div-tabla-factura-por-proveedor").hide();
    $("#div-form-agregar-ventas").hide();  
    $("#div-tabla-pago-ventas").show();
    
  }
}

function capturar_pago_compra() {

  $(".span-pago-compra").html('(Seleccione metodo pago)');
  $("#monto_pago_compra").prop("readonly", true).val(0);
  $("#fecha_proximo_pago").prop("readonly", true);   
  $('#fecha_proximo_pago').rules('remove', 'required');

  // si se edita, no se puede modificar estos datos
  if ($("#idventa_producto").val() == "" || $("#idventa_producto").val() == null) {
    if ($("#metodo_pago").select2("val") == "CONTADO") {      
      var total_venta = $("#total_venta").val();
      $("#monto_pago_compra").val(total_venta);   
      $(".span-pago-compra").html('(Contado)');  
      $("#fecha_proximo_pago").val(moment().format('YYYY-MM-DD')); 
    } else if ($("#metodo_pago").select2("val") == "CREDITO") {
      $("#monto_pago_compra").prop("readonly", false).val(0);
      $("#fecha_proximo_pago").prop("readonly", false);
      $(".span-pago-compra").html('(Crédito)'); 
      $('#fecha_proximo_pago').rules('add', { required: true, messages: { required: 'Campo requerido' } }); 
    }
  } else {
    $('#fecha_proximo_pago').rules('remove', 'required');
    if ($("#metodo_pago").select2("val") == "CONTADO") {      
      $("#fecha_proximo_pago").val(moment().format('YYYY-MM-DD')); 
    } else if ($("#metodo_pago").select2("val") == "CREDITO") {
      $("#fecha_proximo_pago").prop("readonly", false);
    }
  }  
}

//TABLA - ventas
function tbla_principal(fecha_1, fecha_2, id_proveedor, comprobante) {
  //console.log(idproyecto);
  tabla_venta_producto = $("#tabla-venta").dataTable({
    responsive: true, 
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,10,11,4,12,13,6,7,14,9,15], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,10,11,4,12,13,6,7,14,9,15], } }, 
      { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,2,3,10,11,4,12,13,6,7,14,9,15], } } ,         
    ],
    ajax: {
      url: `../ajax/venta_producto.php?op=tbla_principal&fecha_1=${fecha_1}&fecha_2=${fecha_2}&id_proveedor=${id_proveedor}&comprobante=${comprobante}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },     
    createdRow: function (row, data, ixdex) {
      //console.log(data);
      if (data[1] != '') { $("td", row).eq(1).addClass('text-nowrap'); }
      if (data[6] != '') { $("td", row).eq(6).addClass('text-nowrap'); }
      if (data[9] != '' || data[9] == 0 ) { 
        $("td", row).eq(9).addClass('text-right');  
        parseFloat(data[9]) < 0 ? $("td", row).eq(9).addClass('bg-red') : "";
        parseFloat(data[9]) == 0 ? $("td", row).eq(9).addClass('bg-success') : ""; 
      }       
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    footerCallback: function( tfoot, data, start, end, display ) {
      var api1 = this.api(); var total1 = api1.column( 7 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api1.column( 7 ).footer() ).html( `<span class="float-left">S/</span> <span class="float-right">${formato_miles(total1)}</span>` ); 
      
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
      { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      { targets: [7], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },      
      { targets: [9], render: $.fn.dataTable.render.number( ',', '.', 2) },
      { targets: [10,11,12,13,14,15],  visible: false,  searchable: false,  },
    ],
  }).DataTable();

  $(tabla_venta_producto).ready(function () {  $('.cargando').hide(); });

  //console.log(idproyecto);
  tabla_venta_x_proveedor = $("#tabla-venta-proveedor").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: ["copyHtml5", "excelHtml5", "pdf"],
    ajax: {
      url: "../ajax/venta_producto.php?op=listar_compra_x_porveedor",
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {
      //console.log(data);
      if (data[4] != '') {  $("td", row).eq(4).addClass('text-center'); }
      if (data[6] != '') {  $("td", row).eq(6).addClass('text-right'); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    footerCallback: function( tfoot, data, start, end, display ) {
      var api1 = this.api(); var total1 = api1.column( 4 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api1.column( 4 ).footer() ).html( `${formato_miles(total1)}` );

      var api2 = this.api(); var total2 = api2.column( 6 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api2.column( 6 ).footer() ).html( `<span class="float-left">S/</span> <span class="float-right">${formato_miles(total2)}</span>` );
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
    columnDefs:[       
      // { "targets": [ 3 ], "visible": false, "searchable": false }, 
      // { targets: [7], render: $.fn.dataTable.render.number(',', '.', 2) },
      { targets: [6], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
    ]
  }).DataTable();
}

//facturas agrupadas por proveedor.
function listar_facuras_proveedor(idcliente) {
  table_show_hide(3);

  tabla_detalle_compra_x_proveedor = $("#detalles-tabla-compra-prov").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: ["copyHtml5", "excelHtml5",  "pdf"],
    ajax: {
      url: "../ajax/venta_producto.php?op=tabla_detalle_compra_x_porveedor&idcliente=" + idcliente,
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
  }).DataTable();
}

//Función para guardar o editar - ventas
function guardar_y_editar_ventas(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-ventas")[0]);  

  Swal.fire({
    title: "¿Está seguro que deseas guardar esta compra?",
    html: "Verifica que todos lo <b>campos</b>  esten <b>conformes</b>!!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Guardar!",
    preConfirm: (input) => {
      return fetch("../ajax/venta_producto.php?op=guardaryeditarcompra", {
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

        tabla_venta_producto.ajax.reload(null, false);
        tabla_venta_x_proveedor.ajax.reload(null, false);

        limpiar_form_compra(); table_show_hide(1);
        
        $("#modal-agregar-usuario").modal("hide");        
      } else {
        ver_errores(result.value);
      }      
    }
  });  
}

//Función para eliminar registros
function eliminar_venta(idventa_producto, nombre) {

  $(".tooltip").removeClass("show").addClass("hidde");

  crud_eliminar_papelera(
    "../ajax/venta_producto.php?op=papelera_venta",
    "../ajax/venta_producto.php?op=eliminar_venta", 
    idventa_producto, 
    "!Elija una opción¡", 
    `<b class="text-danger">${nombre}</b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu compra ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu compra ha sido Eliminado.' ) }, 
    function(){ tabla_venta_producto.ajax.reload(null, false); tabla_venta_x_proveedor.ajax.reload(null, false); },
    function(){ tablamateriales.ajax.reload(null, false); },
    false, 
    false,
    false
  );

}

// .......::::::::::::::::::::::::::::::::::::::::: AGREGAR FACURAS, BOLETAS, NOTA DE VENTA, ETC :::::::::::::::::::::::::::::::::::.......
//Declaración de variables necesarias para trabajar con las ventas y sus detalles
var impuesto = 18;
var cont = 0;
var detalles = 0;

function agregarDetalleComprobante(idproducto, nombre, unidad_medida, categoria, precio_venta, precio_compra, img, stock) {
  // console.log(idproducto,nombre,unidad_medida, categoria,precio_venta,img,stock);
  var stock = $(`#table_stock_${idproducto}`).text() == 0  ||  $(`#table_stock_${idproducto}`).text() == ''? 0 : parseFloat($(`#table_stock_${idproducto}`).text()) ;
  // var precio_venta = 0;
  var precio_sin_igv =0;
  var cantidad = 1;
  var descuento = 0;
  var precio_igv = 0;

  if (idproducto != "") {

    if ($(".producto_" + idproducto).hasClass("producto_selecionado")) {
      if (stock == 0) {
        toastr_error('No hay Stock!!', 'El producto se quedo sin stock, recarga en el modulo de <a href="ingreso_producto.php">compras</a>', 700);
      } else {

        $(`#table_stock_${idproducto}`).html(formato_miles(stock - 1));

        toastr_success("Agregado!!",`Producto: ${nombre} agregado !!`, 700);

        var cant_producto = $(".producto_" + idproducto).val();

        var sub_total = parseInt(cant_producto, 10) + 1;

        $(".producto_" + idproducto).val(sub_total).trigger('change');

        modificarSubtotales();
      }
      
    } else {
      if (stock <= 0) {        
        toastr_error('No hay Stock!!', 'El producto se quedo sin stock, recarga en el modulo de <a href="ingreso_producto.php">compras</a>', 700);
      } else {

        $(`#table_stock_${idproducto}`).html(formato_miles(stock - 1));

        if ($("#tipo_comprobante").select2("val") == "Factura") {
          var subtotal = cantidad * precio_venta;
        } else {
          var subtotal = cantidad * precio_sin_igv;
        }

        var img_p = "";

        if (img == "" || img == null) {
          img_p = `../dist/docs/producto/img_perfil/producto-sin-foto.svg`;
        } else {
          img_p = `../dist/docs/producto/img_perfil/${img}`;
        }

        var fila = `
        <tr class="filas" id="fila${cont}">         
          <td class="py-1">
            <button type="button" class="btn btn-warning btn-sm" onclick="mostrar_productos(${idproducto}, ${cont})"><i class="fas fa-pencil-alt"></i></button>
            <button type="button" class="btn btn-danger btn-sm btn-file-delete-${cont}" onclick="recover_stock(${idproducto}, ${cont}, 0, 'recover_remove_new');"><i class="fas fa-times"></i></button>
          </td>
          <td class="py-1">         
            <input type="hidden" name="idproducto[]" value="${idproducto}">
            <div class="user-block text-nowrap">
              <img class="profile-user-img img-responsive img-circle cursor-pointer img_perfil_${cont}" src="${img_p}" alt="user image" onerror="this.src='../dist/svg/404-v2.svg';" onclick="ver_img_producto('${img_p}', '${encodeHtml(nombre)}')">
              <span class="username"><p class="mb-0 nombre_producto_${cont}">${nombre}</p></span>
              <span class="description categoria_${cont}"><b>Categoría: </b>${categoria}</span>
            </div>
          </td>
          <td class="py-1"><span class="unidad_medida_${cont}">${unidad_medida}</span> <input type="hidden" class="unidad_medida_${cont}" name="unidad_medida[]" id="unidad_medida[]" value="${unidad_medida}"><input class="categoria_${cont}" type="hidden" name="categoria[]" id="categoria[]" value="${categoria}"></td>
          <td class="py-1 form-group">
            <input type="number" class="w-100px valid_cantidad form-control producto_${idproducto} producto_selecionado" name="valid_cantidad[${cont}]" id="valid_cantidad_${cont}" value="${cantidad}" min="0.01" required onkeyup="replicar_precio_venta(${cont}, '#cantidad_${cont}', this);" onchange="replicar_precio_venta(${cont}, '#cantidad_${cont}', this);">
            <input type="hidden" class="cantidad_${cont}" name="cantidad[]" id="cantidad_${cont}" value="${cantidad}" min="0.01" required onkeyup="update_stock(${idproducto},${cont});" onchange="update_stock(${idproducto},${cont});">            
          </td>
          <td class="py-1 hidden"><input type="number" class="w-135px input-no-border precio_sin_igv_${cont}" name="precio_sin_igv[]" id="precio_sin_igv[]" value="${parseFloat(precio_sin_igv).toFixed(2)}" readonly min="0" ></td>
          <td class="py-1 hidden"><input type="number" class="w-135px input-no-border precio_igv_${cont}" name="precio_igv[]" id="precio_igv[]" value="${parseFloat(precio_igv).toFixed(2)}" readonly  ></td>
          <td class="py-1 form-group">
            <input type="number" class="w-135px form-control valid_precio_con_igv" name="valid_precio_con_igv[${cont}]" id="valid_precio_con_igv_${cont}" value="${parseFloat(precio_venta).toFixed(2)}" min="0.01" required onkeyup="replicar_precio_venta(${cont}, '#precio_con_igv_${cont}', this);" onchange="replicar_precio_venta(${cont}, '#precio_con_igv_${cont}', this);">
            <input type="hidden" class="precio_con_igv_${cont}" name="precio_con_igv[]" id="precio_con_igv_${cont}" value="${parseFloat(precio_venta).toFixed(2)}" onkeyup="modificarSubtotales();" onchange="modificarSubtotales();">
            <input type="hidden" name="precio_compra[]" id="precio_compra_${cont}" value="${precio_compra}" >
          </td>        
          <td class="py-1 form-group">
            <input type="number" class="w-135px form-control descuento_${cont}" name="descuento[]" value="${descuento}" min="0.00" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()">
          </td>
          <td class="py-1 text-right"><span class="text-right subtotal_producto_${cont}" name="subtotal_producto" id="subtotal_producto">${subtotal}</span></td>
          <td class="py-1"><button type="button" onclick="modificarSubtotales()" class="btn btn-info btn-sm"><i class="fas fa-sync"></i></button></td>
        </tr>`;

        detalles = detalles + 1;

        $("#detalles").append(fila);

        array_class_trabajador.push({ id_cont: cont });

        modificarSubtotales();
        
        toastr_success("Agregado!!",`Producto: ${nombre} agregado !!`, 700);

        // reglas de validación     
        $('.valid_precio_con_igv').each(function(e) { 
          $(this).rules('add', { required: true, messages: { required: 'Campo requerido' } }); 
          $(this).rules('add', { min:0.01, messages: { min:"Mínimo 0.01" } }); 
        });
        $('.valid_cantidad').each(function(e) { 
          $(this).rules('add', { required: true, messages: { required: 'Campo requerido' } }); 
          $(this).rules('add', { min:0.01, messages: { min:"Mínimo 0.01" } }); 
        });

        cont++;
        evaluar(); 
      } 
           
    }
  } else {
    // alert("Error al ingresar el detalle, revisar los datos del artículo");
    toastr_error("Error!!",`Error al ingresar el detalle, revisar los datos del producto.`, 700);
  }
}

function evaluar() {
  if (detalles > 0) {
    $("#guardar_registro_ventas").show();
  } else {
    $("#guardar_registro_ventas").hide();
    cont = 0;
    $(".subtotal_compra").html("S/ 0.00");
    $("#subtotal_compra").val(0);

    $(".igv_venta").html("S/ 0.00");
    $("#igv_venta").val(0);

    $(".total_venta").html("S/ 0.00");
    $("#total_compra").val(0);
  }
}

function default_val_igv() { if ($("#tipo_comprobante").select2("val") == "Factura") { $("#val_igv").val(0.18); } }

function modificarSubtotales() {  

  var val_igv = $('#val_igv').val(); //console.log(array_class_trabajador);

  if ($("#tipo_comprobante").select2("val") == null) {

    $(".hidden").hide(); //Ocultamos: IGV, PRECIO CON IGV

    $("#colspan_subtotal").attr("colspan", 5); //cambiamos el: colspan

    $("#val_igv").val(0);
    $("#val_igv").prop("readonly",true);
    $(".val_igv").html('IGV (0%)');

    $("#tipo_gravada").val('NO GRAVADA');
    $(".tipo_gravada").html('NO GRAVADA');

    if (array_class_trabajador.length === 0) {
    } else {
      array_class_trabajador.forEach((element, index) => {
        var cantidad = parseFloat($(`.cantidad_${element.id_cont}`).val());
        var precio_con_igv = parseFloat($(`.precio_con_igv_${element.id_cont}`).val());
        var deacuento = parseFloat($(`.descuento_${element.id_cont}`).val());
        var subtotal_producto = 0;

        // Calculamos: IGV
        var precio_sin_igv = precio_con_igv;
        $(`.precio_sin_igv_${element.id_cont}`).val(precio_sin_igv);

        // Calculamos: precio + IGV
        var igv = 0;
        $(`.precio_igv_${element.id_cont}`).val(igv);

        // Calculamos: Subtotal de cada producto
        subtotal_producto = cantidad * parseFloat(precio_con_igv) - deacuento;
        $(`.subtotal_producto_${element.id_cont}`).html(formato_miles(subtotal_producto.toFixed(4)));
      });
      calcularTotalesSinIgv();
    }
  } else {
    if ($("#tipo_comprobante").select2("val") == "Factura") {

      $(".hidden").show(); //Mostramos: IGV, PRECIO SIN IGV

      $("#colspan_subtotal").attr("colspan", 7); //cambiamos el: colspan
      
      $("#val_igv").prop("readonly",false);

      if (array_class_trabajador.length === 0) {
        if (val_igv == '' || val_igv <= 0) {
          $("#tipo_gravada").val('NO GRAVADA');
          $(".tipo_gravada").html('NO GRAVADA');
          $(".val_igv").html(`IGV (0%)`);
        } else {
          $("#tipo_gravada").val('GRAVADA');
          $(".tipo_gravada").html('GRAVADA');
          $(".val_igv").html(`IGV (${(parseFloat(val_igv) * 100).toFixed(2)}%)`);
        }
        
      } else {
        // validamos el valor del igv ingresado        

        array_class_trabajador.forEach((element, index) => {
          var cantidad = parseFloat($(`.cantidad_${element.id_cont}`).val());
          var precio_con_igv = parseFloat($(`.precio_con_igv_${element.id_cont}`).val());
          var deacuento = parseFloat($(`.descuento_${element.id_cont}`).val());
          var subtotal_producto = 0;

          // Calculamos: Precio sin IGV
          var precio_sin_igv = ( quitar_igv_del_precio(precio_con_igv, val_igv, 'decimal')).toFixed(2);
          $(`.precio_sin_igv_${element.id_cont}`).val(precio_sin_igv);

          // Calculamos: IGV
          var igv = (parseFloat(precio_con_igv) - parseFloat(precio_sin_igv)).toFixed(2);
          $(`.precio_igv_${element.id_cont}`).val(igv);

          // Calculamos: Subtotal de cada producto
          subtotal_producto = cantidad * parseFloat(precio_con_igv) - deacuento;
          $(`.subtotal_producto_${element.id_cont}`).html(formato_miles(subtotal_producto.toFixed(2)));
        });

        calcularTotalesConIgv();
      }
    } else {

      $(".hidden").hide(); //Ocultamos: IGV, PRECIO CON IGV

      $("#colspan_subtotal").attr("colspan", 5); //cambiamos el: colspan

      $("#val_igv").val(0);
      $("#val_igv").prop("readonly",true);
      $(".val_igv").html('IGV (0%)');

      $("#tipo_gravada").val('NO GRAVADA');
      $(".tipo_gravada").html('NO GRAVADA');

      if (array_class_trabajador.length === 0) {
      } else {
        array_class_trabajador.forEach((element, index) => {
          var cantidad = parseFloat($(`.cantidad_${element.id_cont}`).val());
          var precio_con_igv = parseFloat($(`.precio_con_igv_${element.id_cont}`).val());
          var deacuento = parseFloat($(`.descuento_${element.id_cont}`).val());
          var subtotal_producto = 0;

          // Calculamos: IGV
          var precio_sin_igv = precio_con_igv;
          $(`.precio_sin_igv_${element.id_cont}`).val(precio_sin_igv);

          // Calculamos: precio + IGV
          var igv = 0;
          $(`.precio_igv_${element.id_cont}`).val(igv);

          // Calculamos: Subtotal de cada producto
          subtotal_producto = cantidad * parseFloat(precio_con_igv) - deacuento;
          $(`.subtotal_producto_${element.id_cont}`).html(formato_miles(subtotal_producto.toFixed(4)));
        });

        calcularTotalesSinIgv();
      }
    }
  }
  toastr_success("Actualizado!!",`Precio Actualizado.`, 700);
  capturar_pago_compra();
}

function calcularTotalesSinIgv() {
  var total = 0.0;
  var igv = 0;
  var mtotal = 0;

  if (array_class_trabajador.length === 0) {
  } else {
    array_class_trabajador.forEach((element, index) => {
      total += parseFloat(quitar_formato_miles($(`.subtotal_producto_${element.id_cont}`).text()));
    });

    $(".subtotal_compra").html("S/ " + formato_miles(total));
    $("#subtotal_compra").val(redondearExp(total, 4));

    $(".igv_venta").html("S/ 0.00");
    $("#igv_venta").val(0.0);
    $(".val_igv").html('IGV (0%)');

    $(".total_venta").html("S/ " + formato_miles(total.toFixed(2)));
    $("#total_venta").val(redondearExp(total, 4));
  }
}

function calcularTotalesConIgv() {
  var val_igv = $('#val_igv').val();
  var igv = 0;
  var total = 0.0;

  var subotal_sin_igv = 0;

  array_class_trabajador.forEach((element, index) => {
    total += parseFloat(quitar_formato_miles($(`.subtotal_producto_${element.id_cont}`).text()));
  });

  //console.log(total); 

  subotal_sin_igv = quitar_igv_del_precio(total, val_igv, 'decimal').toFixed(2);
  igv = (parseFloat(total) - parseFloat(subotal_sin_igv)).toFixed(2);

  $(".subtotal_compra").html(`S/ ${formato_miles(subotal_sin_igv)}`);
  $("#subtotal_compra").val(redondearExp(subotal_sin_igv, 4));

  $(".igv_venta").html("S/ " + formato_miles(igv));
  $("#igv_venta").val(igv);

  $(".total_venta").html("S/ " + formato_miles(total.toFixed(2)));
  $("#total_venta").val(redondearExp(total, 4));

  total = 0.0;
}

function ocultar_comprob() {
  if ($("#tipo_comprobante").select2("val") == "Ninguno") {
    $("#content-serie-comprobante").hide();

    $("#content-serie-comprobante").val("");

    $("#content-descripcion").removeClass("col-lg-5").addClass("col-lg-7");
  } else {
    $("#content-serie-comprobante").show();

    $("#content-descripcion").removeClass("col-lg-7").addClass("col-lg-5");
  }
}

function eliminarDetalle(idproducto, indice) {
  $("#fila" + indice).remove();

  array_class_trabajador.forEach(function (car, index, object) {
    if (car.id_cont === indice) {
      object.splice(index, 1);
    }
  });  

  modificarSubtotales();

  detalles = detalles - 1;

  evaluar();

  toastr_warning("Removido!!","Producto removido", 700);
}

//mostramos para editar el datalle del comprobante de la ventas
function mostrar_venta(idventa_producto) {

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  limpiar_form_compra();
  array_class_trabajador = [];

  cont = 0;
  detalles = 0;
  table_show_hide(2);

  $.post("../ajax/venta_producto.php?op=ver_venta_editar", { idventa_producto: idventa_producto }, function (e, status) {
    
    e = JSON.parse(e); //console.log(e);

    if (e.status == true) {
      
      if (e.data.venta.tipo_comprobante == "Factura") {
        $(".content-igv").show();
        $(".content-tipo-comprobante").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
        $(".content-descripcion").removeClass("col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
        $(".content-serie-comprobante").show();
      } else if (e.data.venta.tipo_comprobante == "Boleta" || e.data.venta.tipo_comprobante == "Nota de venta") {
        $(".content-serie-comprobante").show();
        $(".content-igv").hide();
        $(".content-tipo-comprobante").removeClass("col-lg-4 col-lg-5").addClass("col-lg-5");
        $(".content-descripcion").removeClass(" col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
      } else if (e.data.venta.tipo_comprobante == "Ninguno") {
        $(".content-serie-comprobante").hide();
        $(".content-serie-comprobante").val("");
        $(".content-igv").hide();
        $(".content-tipo-comprobante").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
        $(".content-descripcion").removeClass(" col-lg-4 col-lg-5 col-lg-7").addClass("col-lg-8");
      } else {
        $(".content-serie-comprobante").show();
        //$(".content-descripcion").removeClass("col-lg-7").addClass("col-lg-4");
      }

      $("#idventa_producto").val(e.data.venta.idventa_producto);
      $("#idcliente").val(e.data.venta.idpersona).trigger("change");
      $("#fecha_venta").val(e.data.venta.fecha_venta);
      $("#tipo_comprobante").val(e.data.venta.tipo_comprobante).trigger("change");
      $("#serie_comprobante").val(e.data.venta.serie_comprobante).trigger("change");
      $("#val_igv").val(e.data.venta.val_igv);
      $("#descripcion").val(e.data.venta.descripcion);

      $("#metodo_pago").val(e.data.venta.metodo_pago).trigger("change");
      $("#fecha_proximo_pago").val(e.data.venta.fecha_proximo_pago);

      if (e.data.detalle) {

        e.data.detalle.forEach((val, index) => {

          var img = "";

          if (val.imagen == "" || val.imagen == null) {
            img = `../dist/docs/producto/img_perfil/producto-sin-foto.svg`;
          } else {
            img = `../dist/docs/producto/img_perfil/${val.imagen}`;
          }

          recover_stock(val.idproducto, cont, val.cantidad );

          var fila = `
          <tr class="filas" id="fila${cont}">
            <td>
              <button type="button" class="btn btn-warning btn-sm" onclick="mostrar_productos(${val.idproducto}, ${cont})"><i class="fas fa-pencil-alt"></i></button>
              <button type="button" class="btn btn-danger btn-sm btn-file-delete-${cont}" onclick="recover_stock(${val.idproducto}, ${cont}, 0, 'recover_remove_edit');"><i class="fas fa-times"></i></button></td>
            </td>
            <td>
              <input type="hidden" name="idproducto[]" value="${val.idproducto}">
              <div class="user-block text-nowrap">
                <img class="profile-user-img img-responsive img-circle cursor-pointer img_perfil_${cont}" src="${img}" alt="user image" onerror="this.src='../dist/svg/404-v2.svg';" onclick="ver_img_producto('${img}', '${encodeHtml(val.nombre)}')">
                <span class="username"><p class="mb-0 nombre_producto_${cont}" >${val.nombre}</p></span>
                <span class="description categoria_${cont}"><b>Categoría: </b>${val.categoria}</span>
              </div>
            </td>
            <td> <span class="unidad_medida_${cont}">${val.unidad_medida}</span> <input class="unidad_medida_${cont}" type="hidden" name="unidad_medida[]" id="unidad_medida[]" value="${val.unidad_medida}"> <input class="categoria_${cont}" type="hidden" name="categoria[]" id="categoria[]" value="${val.categoria}"></td>            
            <td class="py-1 form-group">
              <input class="w-100px valid_cantidad form-control producto_${val.idproducto} producto_selecionado" type="number" name="valid_cantidad[${cont}]" id="valid_cantidad_${cont}" value="${val.cantidad}" min="0.01" required onkeyup="replicar_precio_venta(${cont}, '#cantidad_${cont}', this);" onchange="replicar_precio_venta(${cont}, '#cantidad_${cont}', this);">
              <input type="hidden" class="cantidad_${cont}" name="cantidad[]" id="cantidad_${cont}" value="${val.cantidad}" onkeyup="update_stock(${val.idproducto},${cont});" onchange="update_stock(${val.idproducto},${cont});">              
              <input type="hidden" class="" name="cantidad_old[]" id="cantidad_old_${cont}" value="${val.cantidad}">
            </td>
            <td class="hidden"><input type="number" class="w-135px input-no-border precio_sin_igv_${cont}" name="precio_sin_igv[]" id="precio_sin_igv[]" value="${val.precio_sin_igv}" readonly ></td>
            <td class="hidden"><input type="number" class="w-135px input-no-border precio_igv_${cont}" name="precio_igv[]" id="precio_igv[]" value="${val.igv}" readonly ></td>             
            <td class="py-1 form-group">
              <input type="number" class="w-135px form-control valid_precio_con_igv" name="valid_precio_con_igv[${cont}]" id="valid_precio_con_igv_${cont}" value="${parseFloat(val.precio_con_igv).toFixed(2)}" min="0.01" required onkeyup="replicar_precio_venta(${cont}, '#precio_con_igv_${cont}', this);" onchange="replicar_precio_venta(${cont}, '#precio_con_igv_${cont}', this);">
              <input type="hidden" class="precio_con_igv_${cont}" name="precio_con_igv[]" id="precio_con_igv_${cont}" value="${parseFloat(val.precio_con_igv).toFixed(2)}" onkeyup="modificarSubtotales();" onchange="modificarSubtotales();">
              <input type="hidden" name="precio_compra[]" id="precio_compra_${cont}" value="${val.precio_compra}" >
            </td>
            <td class="py-1 form-group">
              <input type="number" class="w-135px form-control descuento_${cont}" name="descuento[]" value="${val.descuento}" min="0.00" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()">
            </td>
            <td class="text-right"><span class="text-right subtotal_producto_${cont}" name="subtotal_producto" id="subtotal_producto">0.00</span></td>
            <td><button type="button" onclick="modificarSubtotales()" class="btn btn-info btn-sm"><i class="fas fa-sync"></i></button></td>
          </tr>`;

          detalles = detalles + 1;

          $("#detalles").append(fila);

          array_class_trabajador.push({ id_cont: cont });

          // reglas de validación     
          $('.valid_precio_con_igv').each(function(e) { 
            $(this).rules('add', { required: true, messages: { required: 'Campo requerido' } }); 
            $(this).rules('add', { min:0.01, messages: { min:"Mínimo 0.01" } }); 
          });
          $('.valid_cantidad').each(function(e) { 
            $(this).rules('add', { required: true, messages: { required: 'Campo requerido' } }); 
            $(this).rules('add', { min:0.01, messages: { min:"Mínimo 0.01" } }); 
          });

          cont++;
          evaluar();
        });

        modificarSubtotales();
      } else {  
        toastr_error("Sin productos!!","Este registro no tiene productos para mostrar", 700);     
      }

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
      
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );
}

//mostramos para editar el datalle del comprobante de la ventas
function copiar_venta(idventa_producto) {

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  limpiar_form_compra();
  array_class_trabajador = [];

  cont = 0;
  detalles = 0;
  table_show_hide(2);

  $.post("../ajax/venta_producto.php?op=ver_venta_editar", { idventa_producto: idventa_producto }, function (e, status) {
    
    e = JSON.parse(e); //console.log(e);

    if (e.status == true) {
      
      if (e.data.venta.tipo_comprobante == "Factura") {
        $(".content-igv").show();
        $(".content-tipo-comprobante").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
        $(".content-descripcion").removeClass("col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
        $(".content-serie-comprobante").show();
      } else if (e.data.venta.tipo_comprobante == "Boleta" || e.data.venta.tipo_comprobante == "Nota de venta") {
        $(".content-serie-comprobante").show();
        $(".content-igv").hide();
        $(".content-tipo-comprobante").removeClass("col-lg-4 col-lg-5").addClass("col-lg-5");
        $(".content-descripcion").removeClass(" col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
      } else if (e.data.venta.tipo_comprobante == "Ninguno") {
        $(".content-serie-comprobante").hide();
        $(".content-serie-comprobante").val("");
        $(".content-igv").hide();
        $(".content-tipo-comprobante").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
        $(".content-descripcion").removeClass(" col-lg-4 col-lg-5 col-lg-7").addClass("col-lg-8");
      } else {
        $(".content-serie-comprobante").show();
        //$(".content-descripcion").removeClass("col-lg-7").addClass("col-lg-4");
      }

      // $("#idventa_producto").val(e.data.venta.idventa_producto); // esto no se usa cuando duplicams la factura
      $("#idcliente").val(e.data.venta.idpersona).trigger("change");
      $("#fecha_venta").val(e.data.venta.fecha_venta);
      $("#tipo_comprobante").val(e.data.venta.tipo_comprobante).trigger("change");
      $("#serie_comprobante").val(e.data.venta.serie_comprobante).trigger("change");
      $("#val_igv").val(e.data.venta.val_igv);
      $("#descripcion").val(e.data.venta.descripcion);

      $("#metodo_pago").val(e.data.venta.metodo_pago).trigger("change");
      $("#fecha_proximo_pago").val(e.data.venta.fecha_proximo_pago);

      if (e.data.detalle) {

        e.data.detalle.forEach((val, index) => {

          var img = "";

          if (val.imagen == "" || val.imagen == null) {
            img = `../dist/docs/producto/img_perfil/producto-sin-foto.svg`;
          } else {
            img = `../dist/docs/producto/img_perfil/${val.imagen}`;
          }    

          var fila = `
          <tr class="filas" id="fila${cont}">
            <td>
              <button type="button" class="btn btn-warning btn-sm" onclick="mostrar_productos(${val.idproducto}, ${cont})"><i class="fas fa-pencil-alt"></i></button>
              <button type="button" class="btn btn-danger btn-sm btn-file-delete-${cont}" onclick="recover_stock(${val.idproducto}, ${cont}, 0, 'recover_remove_new');"><i class="fas fa-times"></i></button></td>
            </td>
            <td>
              <input type="hidden" name="idproducto[]" value="${val.idproducto}">
              <div class="user-block text-nowrap">
                <img class="profile-user-img img-responsive img-circle cursor-pointer img_perfil_${cont}" src="${img}" alt="user image" onerror="this.src='../dist/svg/404-v2.svg';" onclick="ver_img_producto('${img}', '${encodeHtml(val.nombre)}')">
                <span class="username"><p class="mb-0 nombre_producto_${cont}" >${val.nombre}</p></span>
                <span class="description categoria_${cont}"><b>Categoría: </b>${val.categoria}</span>
              </div>
            </td>
            <td> <span class="unidad_medida_${cont}">${val.unidad_medida}</span> <input class="unidad_medida_${cont}" type="hidden" name="unidad_medida[]" id="unidad_medida[]" value="${val.unidad_medida}"> <input class="categoria_${cont}" type="hidden" name="categoria[]" id="categoria[]" value="${val.categoria}"></td>            
            <td class="py-1 form-group">
              <input class="w-100px valid_cantidad form-control producto_${val.idproducto} producto_selecionado" type="number" name="valid_cantidad[${cont}]" id="valid_cantidad_${cont}" value="${val.cantidad}" min="0.01" required onkeyup="replicar_precio_venta(${cont}, '#cantidad_${cont}', this);" onchange="replicar_precio_venta(${cont}, '#cantidad_${cont}', this);">
              <input type="hidden" class="cantidad_${cont}" name="cantidad[]" id="cantidad_${cont}" value="${val.cantidad}" onkeyup="update_stock(${val.idproducto},${cont});" onchange="update_stock(${val.idproducto},${cont});">              
              <input type="hidden" class="" name="cantidad_old[]" id="cantidad_old_${cont}" value="${val.cantidad}">
            </td>
            <td class="hidden"><input type="number" class="w-135px input-no-border precio_sin_igv_${cont}" name="precio_sin_igv[]" id="precio_sin_igv[]" value="${val.precio_sin_igv}" readonly ></td>
            <td class="hidden"><input type="number" class="w-135px input-no-border precio_igv_${cont}" name="precio_igv[]" id="precio_igv[]" value="${val.igv}" readonly ></td>             
            <td class="py-1 form-group">
              <input type="number" class="w-135px form-control valid_precio_con_igv" name="valid_precio_con_igv[${cont}]" id="valid_precio_con_igv_${cont}" value="${parseFloat(val.precio_con_igv).toFixed(2)}" min="0.01" required onkeyup="replicar_precio_venta(${cont}, '#precio_con_igv_${cont}', this);" onchange="replicar_precio_venta(${cont}, '#precio_con_igv_${cont}', this);">
              <input type="hidden" class="precio_con_igv_${cont}" name="precio_con_igv[]" id="precio_con_igv_${cont}" value="${parseFloat(val.precio_con_igv).toFixed(2)}" onkeyup="modificarSubtotales();" onchange="modificarSubtotales();">
              <input type="hidden" name="precio_compra[]" id="precio_compra_${cont}" value="${val.precio_compra}" >
            </td>
            <td class="py-1 form-group">
              <input type="number" class="w-135px form-control descuento_${cont}" name="descuento[]" value="${val.descuento}" min="0.00" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()">
            </td>
            <td class="text-right"><span class="text-right subtotal_producto_${cont}" name="subtotal_producto" id="subtotal_producto">0.00</span></td>
            <td><button type="button" onclick="modificarSubtotales()" class="btn btn-info btn-sm"><i class="fas fa-sync"></i></button></td>
          </tr>`;

          detalles = detalles + 1;

          $("#detalles").append(fila);

          array_class_trabajador.push({ id_cont: cont });

          // reglas de validación     
          $('.valid_precio_con_igv').each(function(e) { 
            $(this).rules('add', { required: true, messages: { required: 'Campo requerido' } }); 
            $(this).rules('add', { min:0.01, messages: { min:"Mínimo 0.01" } }); 
          });
          $('.valid_cantidad').each(function(e) { 
            $(this).rules('add', { required: true, messages: { required: 'Campo requerido' } }); 
            $(this).rules('add', { min:0.01, messages: { min:"Mínimo 0.01" } }); 
          });

          update_stock(val.idproducto, cont);

          cont++;
          evaluar();
        });

        modificarSubtotales();
      } else {  
        toastr_error("Sin productos!!","Este registro no tiene productos para mostrar", 700);     
      }

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
      
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );
}

//mostramos el detalle del comprobante de la ventas
function ver_detalle_ventas(idventa_producto) {

  $("#cargando-5-fomulario").hide();
  $("#cargando-6-fomulario").show();

  $("#print_pdf_compra").addClass('disabled');
  $("#excel_compra").addClass('disabled');

  $("#modal-ver-ventas").modal("show");

  $.post(`../ajax/ajax_general.php?op=ver_detalle_ventas&idventa_producto=${idventa_producto}`, function (e) {
    e = JSON.parse(e); console.log(e);
    if (e.status == true) {
      $(".detalle_de_compra").html(e.data); 
      $("#cargando-5-fomulario").show();
      $("#cargando-6-fomulario").hide();

      $("#excel_compra").removeClass('disabled').attr('href', `../reportes/export_xlsx_venta_producto.php?id=${idventa_producto}`);
      $("#print_pdf_compra").removeClass('disabled').attr('href', `../reportes/pdf_venta_productos.php?id=${idventa_producto}` );
      
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

// :::::::::::::::::::::::::: S E C C I O N   P A G O   V E N T A  ::::::::::::::::::::::::::

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

  $("#idpago_venta_producto_pv").val(""); 
  $("#forma_pago_pv").val("null").trigger("change"); 
  $("#fecha_pago_pv").val(""); 
  $("#monto_pv").val(""); 
  $("#descripcion_pv").val(""); 
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

function tbla_pago_venta( idventa_producto, total_compra, total_pago, cliente) {

  table_show_hide(4);
  $("#idventa_producto_pv").val(idventa_producto);
  $("#total_de_venta").html(formato_miles(total_compra));
  $(".h1-nombre-cliente").html(` - <b>${cliente}</b>` );

  tabla_pago_venta = $("#tabla-pagos-ventas").dataTable({
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
      url: `../ajax/venta_producto.php?op=tabla_pago_venta&idventa_producto=${idventa_producto}`,
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
function eliminar_pago_venta(idpago_venta_producto, nombre) {

  $(".tooltip").removeClass("show").addClass("hidde");

  crud_eliminar_papelera(
    "../ajax/venta_producto.php?op=papelera_pago_venta",
    "../ajax/venta_producto.php?op=eliminar_pago_venta", 
    idpago_venta_producto, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu compra ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu compra ha sido Eliminado.' ) }, 
    function(){ $("#total_depositos").html('0.00'); tabla_pago_venta.ajax.reload(null, false); },
    function(){ tabla_venta_x_proveedor.ajax.reload(null, false); tabla_venta_producto.ajax.reload(null, false); },
    false, 
    false,
    false
  );
}

function calcular_deuda() {
  var monto_actual = $('#monto_pv').val() == '' || $('#monto_pv').val() == null ? 0 : quitar_formato_miles($('#monto_pv').val());
  var monto_pagados = $('#total_depositos').text() == '' || $('#total_depositos').text() == null ? 0 : quitar_formato_miles($('#total_depositos').text());
  var monto_de_compra = $('#total_de_venta').text() == '' || $('#total_de_venta').text() == null ? 0 : quitar_formato_miles($('#total_de_venta').text());
  var idpago_venta_producto_pv = $('#idpago_venta_producto_pv').val();

  var monto_deuda = 0;
  if (idpago_venta_producto_pv == ''  || idpago_venta_producto_pv == null) {
    monto_deuda = parseFloat(monto_de_compra) - parseFloat(monto_pagados) - parseFloat(monto_actual);
  } else {
    var btn_monto_pagados = $(`#btn_monto_pagado_${idpago_venta_producto_pv}`).attr("monto_pagado");
    monto_deuda = parseFloat(monto_de_compra) - (parseFloat(monto_pagados) - parseFloat(btn_monto_pagados)) - parseFloat(monto_actual) ;
  }  

  console.log(monto_deuda);

  if (monto_deuda < 0) {
    $('.deuda-actual').html(formato_miles(monto_deuda)).addClass('input-no-valido').removeClass('input-valido input-no-valido-warning');
  } else if (monto_deuda == 0 ) {
    $('.deuda-actual').html(formato_miles(monto_deuda)).addClass('input-valido').removeClass('input-no-valido input-no-valido-warning');
  }else if (monto_deuda > 0) {
    $('.deuda-actual').html(formato_miles(monto_deuda)).addClass('input-no-valido-warning').removeClass('input-no-valido input-valid');
  }
}

//guardar cliente
function guardar_y_editar_pago_venta(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-pago-venta")[0]);

  $.ajax({
    url: "../ajax/venta_producto.php?op=guardar_y_editar_pago_venta",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      e = JSON.parse(e);
      try {
        if (e.status == true) {          
          if (tabla_pago_venta) { tabla_pago_venta.ajax.reload(null, false); } 
          if (tabla_venta_x_proveedor) { tabla_venta_x_proveedor.ajax.reload(null, false); }     
          if (tabla_venta_producto) { tabla_venta_producto.ajax.reload(null, false); } 
                
          
          Swal.fire("Correcto!", "Pago guardado correctamente.", "success");          
          limpiar_form_pago_compra();
          $("#modal-agregar-pago-venta").modal("hide");
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }       
      
      $("#guardar_registro_pago_venta").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_pago_venta").css({"width": percentComplete+'%'});
          $("#barra_progress_pago_venta").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_pago_venta").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_pago_venta").css({ width: "0%",  });
      $("#barra_progress_pago_venta").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_pago_venta").css({ width: "0%", });
      $("#barra_progress_pago_venta").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar_editar_pago(idpago_venta) {
  $(".tooltip").remove();
  $("#cargando-7-fomulario").hide();
  $("#cargando-8-fomulario").show();
  
  limpiar_form_pago_compra();

  $("#modal-agregar-pago-venta").modal("show")

  $.post("../ajax/venta_producto.php?op=mostrar_editar_pago", { 'idpago_venta': idpago_venta }, function (e, status) {

    e = JSON.parse(e);  console.log(e);  

    if (e.status == true) {

      $("#idpago_venta_producto_pv").val(e.data.idpago_venta_producto);
      $("#idventa_producto_pv").val(e.data.idventa_producto); 
      $("#forma_pago_pv").val(e.data.forma_pago).trigger("change");
      $("#fecha_pago_pv").val(e.data.fecha_pago); 
      $("#monto_pv").val(e.data.monto).trigger("change");
      $("#descripcion_pv").val(e.data.descripcion); 

      if (e.data.comprobante == "" || e.data.comprobante == null  ) {
        $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
        $("#doc1_nombre").html('');
        $("#doc_old_1").val(""); $("#doc1").val("");
      } else {
        $("#doc_old_1").val(e.data.comprobante);
        $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(e.data.comprobante)}</i></div></div>`);
        // cargamos la imagen adecuada par el archivo
        $("#doc1_ver").html(doc_view_extencion(e.data.comprobante,'venta_producto', 'comprobante_pago', '100%', '210' ));            
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
  $('.div-view-comprobante-pago').html(doc_view_download_expand(doc, 'dist/docs/venta_producto/comprobante_pago',name_download,'100%', '410'));
  $('.jq_image_zoom').zoom({ on:'grab' });
}

// :::::::::::::::::::::::::: - S E C C I O N   D E S C A R G A S -  ::::::::::::::::::::::::::

function download_no_multimple(id_compra, cont, nombre_doc) {
  $(`.descarga_compra_${id_compra}`).html('<i class="fas fa-spinner fa-pulse"></i>');
  //console.log(id_compra, nombre_doc);
  var cant_download_ok = 0; var cant_download_error = 0;
  $.post("../ajax/venta_producto.php?op=ver_comprobante_compra", { 'id_compra': id_compra }, function (e, textStatus, jqXHR) {
    e = JSON.parse(e); console.log(e);
    if (e.status == true) {
      e.data.forEach((val, index) => {
        if ( UrlExists(`${host}${val.comprobante}`) == 200 ) {
          download_file(host, val.comprobante, `${cont}·${index+1} ${nombre_doc}`);
          cant_download_ok++;
        } else {
          cant_download_error++;
        }      
      });

      if (cant_download_ok == 0 && cant_download_error == 0) { toastr_error('Vacio!!', 'No hay documentos para descargar.', 700); }
      if (cant_download_ok > 0 ) { toastr_success('Exito!!', `${cant_download_ok} Descargas con exito`, 700); }
      if (cant_download_error > 0 ) { toastr_error('No existe!!', `Hay ${cant_download_error} docs que problabe que este eliminado o se haya movido el documento.`, 700); }

      $(`.descarga_compra_${id_compra}`).html('<i class="fas fa-cloud-download-alt"></i>');
    } else {
      ver_errores(e);
    } 
  }).fail( function(e) { ver_errores(e); } );
  
}

function add_remove_comprobante(id_compra, doc, factura_name) {
  
  $('.check_add_doc').addClass('hidden');
  $('.custom-control').addClass('pl-0');
  $('.cargando_check').removeClass('hidden');

  if ($(`#check_descarga_${id_compra}`).is(':checked')) {
    $.post("../ajax/venta_producto.php?op=ver_comprobante_compra", { 'id_compra': id_compra }, function (e, textStatus, jqXHR) {
      e = JSON.parse(e); console.log(e);
      if (e.status == true) {
        var cont_docs_ok = 0; var cont_docs_error = 0;
        e.data.forEach((val, index) => {
          if (UrlExists(`${host}${val.comprobante}`) == 200) {
            array_doc.push({ 
              'id_compra': id_compra,
              'id_factura_compra': val.idfactura_compra_insumo,
              'doc_ruta': `${host}${val.comprobante}`,
            });
            cont_docs_ok++;
          } else {          
            cont_docs_error++;
          }         
        });

        if (cont_docs_ok == 0 && cont_docs_error == 0) {
          toastr_success("Vacio!!",`No hay Documentos para agregar `, 700);
        } else if (cont_docs_ok > 0) {
          toastr_success("Agregado!!",`${cont_docs_ok} Documentos agregado <p class="h5">${factura_name}</p>`, 700);
        } else if (cont_docs_error > 0) {
          toastr_error("Error!!",`${cont_docs_error} Documentos no encontrados <p class="h5">${factura_name}</p>`, 700);
          $(`#check_descarga_${id_compra}`).prop('checked', false);
        }   
        if (cont_docs_error > 0) { toastr_error("Error!!",`${cont_docs_error} Documentos no encontrados <p class="h5">${factura_name}</p>`, 700); }   
        
        $('.check_add_doc').removeClass('hidden');
        $('.custom-control').removeClass('pl-0');   
        $('.cargando_check').addClass('hidden');
      } else {
        ver_errores(e);
      }      
      console.log(array_doc);
    }).fail( function(e) { ver_errores(e); } );
    
  } else {
    $.post("../ajax/venta_producto.php?op=ver_comprobante_compra", { 'id_compra': id_compra }, function (e, textStatus, jqXHR) {
      e = JSON.parse(e); console.log(e);
      if (e.status == true) {
        var cont_doc = 0;
        e.data.forEach((val, index) => {
          // eliminamos el indice elegido
          array_doc.forEach(function (car, index, object) {
            if (car.id_factura_compra === val.idfactura_compra_insumo) {
              object.splice(index, 1); cont_doc++;
            }
          });     
        });  
        toastr_info("Quitado!!",`${cont_doc} Documento quitado <p class="h5">${factura_name}</p>`, 700);  
        
        $('.check_add_doc').removeClass('hidden');
        $('.custom-control').removeClass('pl-0');   
        $('.cargando_check').addClass('hidden');
      } else {
        ver_errores(e);
      }      
      console.log(array_doc);   
    }).fail( function(e) { ver_errores(e); } );     
  }  
}

function download_multimple() {
  //toastr.info(`Aun estamos en desarrollo`);
  $('.btn-descarga-multiple').html('<i class="fas fa-spinner fa-pulse "></i>').addClass('disabled btn-danger').removeClass('btn-success');
  $('.btn-descarga-multiple').attr('onclick', `toastr_error('Espera!!', 'Espera la descarga que esta en curso.', 700);`);
  if (array_doc.length === 0) {
    toastr_error("Vacío!!","Selecciona algún documento", 700);
    $('.btn-descarga-multiple').html('<i class="fas fa-cloud-download-alt"></i>').removeClass('disabled btn-danger').addClass('btn-info');
    $('.btn-descarga-multiple').attr('onclick', 'download_multimple();');
  } else {
    const zip = new JSZip();  let count = 0; const zipFilename = "Comprobantes-de-insumos.zip";
    array_doc.forEach(async function (value){

      const urlArr = value.doc_ruta.split('/');
      const filename = urlArr[urlArr.length - 1];

      try {
        const file = await JSZipUtils.getBinaryContent(value.doc_ruta)
        zip.file(filename, file, { binary: true});
        count++;
        if(count === array_doc.length) {
          zip.generateAsync({type:'blob'}).then(function(content) {
            var download_zip = saveAs(content, zipFilename);
            $( download_zip ).ready(function() { toastr_success("Exito!!","Descarga exitosa", 700); });
            $('.btn-descarga-multiple').html('<i class="fas fa-cloud-download-alt"></i>').removeClass('disabled btn-danger').addClass('btn-info');
            $('.btn-descarga-multiple').attr('onclick', 'download_multimple();');
          });
        }
      } catch (err) {
        console.log(err); toastr_success("Error!!","Error al descargar", 700);
        $('.btn-descarga-multiple').html('<i class="fas fa-cloud-download-alt"></i>').removeClass('disabled btn-danger').addClass('btn-info');
        $('.btn-descarga-multiple').attr('onclick', 'download_multimple();');
      }
    });
  }  
}

// :::::::::::::::::::::::::: S E C C I O N   A G R I C U L T O R  ::::::::::::::::::::::::::
// abrimos el navegador de archivos
$("#foto1_i").click(function() { $('#foto1').trigger('click'); });
$("#foto1").change(function(e) { addImage(e,$("#foto1").attr("id")) });

function foto1_eliminar() {
	$("#foto1").val("");
	$("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");
	$("#foto1_nombre").html("");
}

//Función limpiar
function limpiar_form_proveedor() {
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

  $(".tooltip").removeClass("show").addClass("hidde");
}

// damos formato a: Cta, CCI
function formato_banco() {

  if ($("#banco_prov").select2("val") == null || $("#banco_prov").select2("val") == "" || $("#banco_prov").select2("val") == "1" ) {

    $("#c_bancaria_prov").prop("readonly", true);
    $("#cci_prov").prop("readonly", true);
    $("#c_detracciones_prov").prop("readonly", true);

  } else {
    
    $(".chargue-format-1").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');
    $(".chargue-format-2").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');
    $(".chargue-format-3").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');    

    $.post("../ajax/ajax_general.php?op=formato_banco", { 'idbanco': $("#banco_prov").select2("val") }, function (e, status) {
      
      e = JSON.parse(e);  // console.log(e);

      if (e.status == true) {
        $(".chargue-format-1").html("Cuenta Bancaria");
        $(".chargue-format-2").html("CCI");
        $(".chargue-format-3").html("Cuenta Detracciones");

        $("#c_bancaria_prov").prop("readonly", false);
        $("#cci_prov").prop("readonly", false);
        $("#c_detracciones_prov").prop("readonly", false);

        var format_cta = decifrar_format_banco(e.data.formato_cta);
        var format_cci = decifrar_format_banco(e.data.formato_cci);
        var formato_detracciones = decifrar_format_banco(e.data.formato_detracciones);
        // console.log(format_cta, formato_detracciones);

        $("#c_bancaria_prov").inputmask(`${format_cta}`);
        $("#cci_prov").inputmask(`${format_cci}`);
        $("#c_detracciones_prov").inputmask(`${formato_detracciones}`);
      } else {
        ver_errores(e);
      }      
    }).fail( function(e) { ver_errores(e); } );
  }
}

//guardar proveedor
function guardar_proveedor(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-proveedor")[0]);

  $.ajax({
    url: "../ajax/venta_producto.php?op=guardar_proveedor",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      e = JSON.parse(e);
      try {
        if (e.status == true) {
          // toastr.success("proveedor registrado correctamente");
          Swal.fire("Correcto!", "Proveedor guardado correctamente.", "success");          
          limpiar_form_proveedor();
          $("#modal-agregar-proveedor").modal("hide");
          
          //Cargamos los items al select cliente
          lista_select2("../ajax/ajax_general.php?op=select2Persona_por_tipo&tipo=2", '#idcliente', e.data);
          lista_select2("../ajax/ajax_general.php?op=select2Persona_por_tipo&tipo=2", '#filtro_proveedor', null);
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

function mostrar_para_editar_proveedor() {
  $("#cargando-11-fomulario").hide();
  $("#cargando-12-fomulario").show();
  
  limpiar_form_proveedor();

  $('#modal-agregar-proveedor').modal('show');
  $(".tooltip").remove();

  $.post("../ajax/venta_producto.php?op=mostrar_editar_proveedor", { 'idpersona': $('#idcliente').select2("val") }, function (e, status) {

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
      if (e.data.es_socio==1) {        
        $("#input_socio_per").val('1');
        $(".sino_per").html('(SI)');        
        if($('#socio_per').is(':checked') ){$("#definiendo").prop('checked', false);  }else{ $("#socio_per").prop('checked', true); }
      }

      if (e.data.foto_perfil!="") {
        $("#foto1_i").attr("src", "../dist/docs/persona/perfil/" + e.data.foto_perfil);
        $("#foto1_actual").val(e.data.foto_perfil);
      }
      calcular_edad('#nacimiento_per','.edad_per','#edad_per'); 

      $("#cargando-11-fomulario").show();
      $("#cargando-12-fomulario").hide();
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); });
}

function habilitando_socio() {  
  if ($("#socio_per").val()==null || $("#socio_per").val()=="" || $('#socio_per').is(':checked') ) {
    $("#input_socio_per").val('0'); $(".sino_per").html('(NO)');
  }else{
    $("#input_socio_per").val('1'); $(".sino_per").html('(SI)');
  }
}

// :::::::::::::::::::::::::: S E C C I O N   P R O D U C T O  ::::::::::::::::::::::::::

// abrimos el navegador de archivos
$("#foto2_i").click(function () { $("#foto2").trigger("click"); });
$("#foto2").change(function (e) { addImage(e, $("#foto2").attr("id"), "../dist/img/default/img_defecto_producto.jpg"); });

function foto2_eliminar() {
  $("#foto2").val("");
  $("#foto2_i").attr("src", "../dist/img/default/img_defecto_producto.jpg");
  $("#foto2_nombre").html("");
}

//Función limpiar
function limpiar_producto() {
  
  $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');
  $('.name-modal-title-agregar').html('Agregar Producto');

  //Mostramos los Materiales
  $("#idproducto_pro").val("");  
  $("#nombre_producto_pro").val("");  
  $("#categoria_producto_pro").val("").trigger("change");
  $("#unidad_medida_pro").val("null").trigger("change");
  $("#marca_pro").val(""); 
  $("#contenido_neto_pro").val(1).trigger("change");  
  $("#precio_unitario_pro").val('0.00');  
  $("#stock_pro").val('0.00').trigger("change");
  $("#descripcion_pro").val(""); 

  $("#foto1_i").attr("src", "../dist/img/default/img_defecto_producto.jpg");
  $("#foto1").val("");
  $("#foto1_actual").val("");
  $("#foto1_nombre").html("");  

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función ListarArticulos
function listarmateriales() {
  tablamateriales = $("#tblamateriales").dataTable({
    // responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate"></i>', action: function ( e, dt, node, config ) { tablamateriales.ajax.reload(null, false); toastr_success('Exito!!', 'Actualizando tabla', 400); } }
    ],
    ajax: {
      url: "../ajax/ajax_general.php?op=tblaProductosVenta",
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: sueldo mensual
      if (data[3] != '') { $("td", row).eq(3).addClass('text-right'); }  
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 100, //Paginación
    // order: [[0, "desc"]], //Ordenar (columna,orden)
  }).DataTable();
}

function mostrar_productos(idproducto, cont) { 

  limpiar_producto();  

  $("#cargando-9-fomulario").hide();
  $("#cargando-10-fomulario").show();
  $('.name-modal-title-agregar').html('Editar Producto');
  $("#cont").val(cont); 

  $("#modal-agregar-productos").modal("show");

  $.post("../ajax/venta_producto.php?op=mostrar_productos", { 'idproducto_pro': idproducto }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);    

    if (e.status == true) {
      $("#idproducto_pro").val(e.data.idproducto);
      $("#nombre_producto_pro").val(e.data.nombre);
      $("#marca_pro").val(e.data.marca).trigger("change");  
      $("#descripcion_pro").val(e.data.descripcion);
      $("#stock_pro").val(e.data.stock); 
      $("#contenido_neto_pro").val(e.data.contenido_neto);  
      $('#precio_unitario_pro').val(parseFloat(e.data.precio_unitario));
      
      $("#unidad_medida_pro").val(e.data.idunidad_medida).trigger("change");
      
      $("#categoria_producto_pro").val(e.data.idcategoria_producto).trigger("change");

      if (e.data.imagen != "") {        
        $("#foto2_i").attr("src", "../dist/docs/producto/img_perfil/" + e.data.imagen);  
        $("#foto2_actual").val(e.data.imagen);
      }

      $('.jq_image_zoom').zoom({ on:'grab' });
      $("#cargando-9-fomulario").show();
      $("#cargando-10-fomulario").hide();
    } else {
      ver_errores(e);
    }      
  }).fail( function(e) { ver_errores(e); } );
}

//Función para guardar o editar
function guardar_y_editar_productos(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-producto")[0]);

  $.ajax({
    url: "../ajax/venta_producto.php?op=guardar_y_editar_productos",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);
        if (e.status == true) {
          Swal.fire("Correcto!", "Producto creado correctamente", "success");
          tablamateriales.ajax.reload(null, false);
          actualizar_producto();
          $("#modal-agregar-productos").modal("hide");

        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); } 
      $("#guardar_registro_material").html('Guardar Cambios').removeClass('disabled');
    },
    beforeSend: function () {
      $("#guardar_registro_material").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
    }
  });
}

function actualizar_producto() {

  var idproducto      = $("#idproducto_pro").val(); 
  var cont            = $("#cont").val(); 

  var nombre_p        = $("#nombre_producto_pro").val();  
  var unidad_medida_p = $("#unidad_medida_pro").find(':selected').text();
  var categoria_p     = $("#categoria_producto_pro").find(':selected').text();

  if (idproducto == "" || idproducto == null) {     
  } else {
    $(`.nombre_producto_${cont}`).html(nombre_p); 
    $(`.categoria_${cont}`).html(`<b>Cateogría: </b>${categoria_p}`);
    $(`.categoria_${cont}`).val(categoria_p); 
    $(`.unidad_medida_${cont}`).html(unidad_medida_p); 
    $(`.unidad_medida_${cont}`).val(unidad_medida_p);

    if ($('#foto2').val()) {
      var src_img = $(`#foto2_i`).attr("src");
      $(`.img_perfil_${cont}`).attr("src", src_img);
    }  
  } 
  
  modificarSubtotales();
}

init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..
$(function () {
  // Aplicando la validacion del select cada vez que cambie
  $("#metodo_pago").on('change', function() { $(this).trigger('blur'); });

  $("#idcliente").on('change', function() { $(this).trigger('blur'); });
  $("#tipo_comprobante").on('change', function() { $(this).trigger('blur'); });
  $("#tipo_documento").on('change', function() { $(this).trigger('blur'); });
  $("#banco").on('change', function() { $(this).trigger('blur'); });
  
  $('#unidad_medida_pro').on('change', function() { $(this).trigger('blur'); });
  $('#categoria_producto_pro').on('change', function() { $(this).trigger('blur'); });
  
  $('#forma_pago_pago').on('change', function() { $(this).trigger('blur'); });;

  $("#form-ventas").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      idcliente:        { required: true },
      tipo_comprobante:   { required: true },
      serie_comprobante:  { minlength: 2 },
      descripcion:        { minlength: 4 },
      fecha_venta:       { required: true },
      val_igv:            { required: true, number: true, min:0, max:1 },
    },
    messages: {
      idcliente:        { required: "Campo requerido", },
      tipo_comprobante:   { required: "Campo requerido", },
      serie_comprobante:  { minlength: "Minimo 2 caracteres", },
      descripcion:        { minlength: "Minimo 4 caracteres", },
      fecha_venta:       { required: "Campo requerido", },
      val_igv:            { required: "Campo requerido", number: 'Ingrese un número', min:'Mínimo 0', max:'Maximo 1' },
      // 'cantidad[]':       { min: "Mínimo 0.01", required: "Campo requerido"},
      'precio_con_igv[]': { min: "Mínimo 0.01", required: "Campo requerido"},
      'descuento[]':      { min: "Mínimo 0.00", required: "Campo requerido"},
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
      guardar_y_editar_ventas(form);
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
      cta_bancaria:       { minlength: 10,},
      banco:              { required: true},
    },
    messages: {
      tipo_documento_per: { required: "Campo requerido.", },
      num_documento_per:  { required: "Campo requerido.", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 20 caracteres.", },
      nombre_per:         { required: "Campo requerido.", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 100 caracteres.", },
      email_per:          { required: "Campo requerido.", email: "Ingrese un coreo electronico válido.", minlength: "MÍNIMO 10 caracteres.", maxlength: "MÁXIMO 50 caracteres.", },
      direccion_per:      { minlength: "MÍNIMO 5 caracteres.", maxlength: "MÁXIMO 200 caracteres.", },
      telefono_per:       { minlength: "MÍNIMO 8 caracteres.", },
      cta_bancaria:       { minlength: "MÍNIMO 10 caracteres.", },
      banco:              { required: "Campo requerido.", },
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

  $("#form-producto").validate({
    rules: {
      nombre_producto_pro:    { required: true, minlength:3, maxlength:200},
      categoria_producto_pro: { required: true },
      marca_pro:              { required: true },
      unidad_medida_pro:      { required: true },
      contenido_neto_pro:     {  min: 1, number: true },
      descripcion_pro:        { minlength: 4 },      
    },
    messages: {
      nombre_producto_pro:    { required: "Por favor ingrese nombre", minlength:"Minimo 3 caracteres", maxlength:"Maximo 200 caracteres" },
      categoria_producto_pro: { required: "Campo requerido", },
      marca_pro:              { required: "Campo requerido" },
      unidad_medida_pro:      { required: "Campo requerido" },
      contenido_neto_pro:     { minlength: "Minimo 3 caracteres", number:"Tipo nùmerico" },    
      descripcion_pro:        { minlength: "Minimo 4 caracteres" },
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
      guardar_y_editar_productos(e);
    },

  });

  $("#form-pago-venta").validate({
    rules: {
      forma_pago_pv: { required: true },
      fecha_pago_pv: { required: true, },
      monto_pv:      { required: true, },
      descripcion_pv:{ minlength: 4, maxlength: 200 },
    },
    messages: {
      forma_pago_pv: { required: "Campo requerido.", },
      fecha_pago_pv: { required: "Campo requerido.", },
      monto_pv:      { required: "Campo requerido.", },
      descripcion_pv:{ minlength: "MÍNIMO 4 caracteres.", maxlength: "MÁXIMO 200 caracteres.", },
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
      guardar_y_editar_pago_venta(e);
    },
  });

  $("#metodo_pago").rules('add', { required: true, messages: {  required: "Campo requerido" } });

  $("#idcliente").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#tipo_comprobante").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#tipo_documento").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#banco").rules('add', { required: true, messages: {  required: "Campo requerido" } });

  $('#unidad_medida_pro').rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $('#categoria_producto_pro').rules('add', { required: true, messages: {  required: "Campo requerido" } });

  $('#forma_pago_pago').rules('add', { required: true, messages: {  required: "Campo requerido" } });

});


// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

function cargando_search() {
  $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ...`);
}

function filtros() {  

  var fecha_1       = $("#filtro_fecha_inicio").val();
  var fecha_2       = $("#filtro_fecha_fin").val();  
  var id_proveedor  = $("#filtro_proveedor").select2('val');
  var comprobante   = $("#filtro_tipo_comprobante").select2('val');   
  
  var nombre_proveedor = $('#filtro_proveedor').find(':selected').text();
  var nombre_comprobante = ' ─ ' + $('#filtro_tipo_comprobante').find(':selected').text();

  // filtro de fechas
  if (fecha_1 == "" || fecha_1 == null) { fecha_1 = ""; } else{ fecha_1 = format_a_m_d(fecha_1) == '-'? '': format_a_m_d(fecha_1);}
  if (fecha_2 == "" || fecha_2 == null) { fecha_2 = ""; } else{ fecha_2 = format_a_m_d(fecha_2) == '-'? '': format_a_m_d(fecha_2);} 

  // filtro de proveedor
  if (id_proveedor == '' || id_proveedor == 0 || id_proveedor == null) { id_proveedor = ""; nombre_proveedor = ""; }

  // filtro de trabajdor
  if (comprobante == '' || comprobante == null || comprobante == 0 ) { comprobante = ""; nombre_comprobante = "" }

  $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ${nombre_proveedor} ${nombre_comprobante}...`);
  //console.log(fecha_1, fecha_2, id_proveedor, comprobante);

  tbla_principal(fecha_1, fecha_2, id_proveedor, comprobante);
}

// ver imagen grande del producto agregado a la compra
function ver_img_producto(file, nombre) {
  $('.foto-insumo').html(nombre);
  $(".tooltip").removeClass("show").addClass("hidde");
  $("#modal-ver-perfil-insumo").modal("show");
  $('#perfil-insumo').html(`<span class="jq_image_zoom"><img class="img-thumbnail" src="${file}" onerror="this.src='../dist/svg/404-v2.svg';" alt="Perfil" width="100%"></span>`);
  $('.jq_image_zoom').zoom({ on:'grab' });
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

function replicar_precio_venta(cont, name_input, valor) {
  var value = $(valor).val(); console.log(value);
  $(`${name_input}`).val(value).trigger("change");
}

function update_stock(id, count) {console.log(id, count);  
  var stock = $(`#table_stock_${id}`).attr('stock') == 0  ||  $(`#table_stock_${id}`).attr('stock') == ''? 0 : parseFloat($(`#table_stock_${id}`).attr('stock')) ;
  var cant  = $(`.cantidad_${count}`).val() == 0  ||  $(`.cantidad_${count}`).val() == ''? 0 : parseFloat($(`.cantidad_${count}`).val()) ;
  var dif   = stock - cant;
  console.log('stock =' + stock, 'cant =' + cant, 'dif =' + dif);
  if (stock >= cant) {    
    $(`#table_stock_${id}`).html(formato_miles(dif));    
    modificarSubtotales();    
  } else {
    $(`#valid_cantidad_${count}`).val(0);
    toastr_error('No hay Stock!!', 'El producto se quedo sin stock, recarga en el modulo de <a href="ingreso_producto.php">compras</a>', 700);
  }  
}

function recover_stock(id, count, cantidad_entrante, type_recover = false) {
  var stock_actual = $(`#table_stock_${id}`).attr('stock') == 0  ||  $(`#table_stock_${id}`).attr('stock') == ''? 0 : parseFloat($(`#table_stock_${id}`).attr('stock')) ;
  var cant  = $(`.cantidad_${count}`).val() == 0  ||  $(`.cantidad_${count}`).val() == ''? 0 : parseFloat($(`.cantidad_${count}`).val()) ;
  var cant_old  = $(`#cantidad_old_${count}`).val() == 0  ||  $(`#cantidad_old_${count}`).val() == ''? 0 : parseFloat($(`#cantidad_old_${count}`).val()) ;
  
  if (type_recover == null || type_recover == '' || type_recover == false) {

    var diferencia = stock_actual + parseFloat(cantidad_entrante);
    $(`#table_stock_${id}`).attr('stock', diferencia);

  } else if(type_recover == 'recover_remove_new') { /* -- recuperamos cuando CREAMOS una nueva venta -- */ 
    
    $(`.btn-file-delete-${count}`).html(`<i class="fas fa-spinner fa-pulse"></i>`).addClass('disabled');    
    $(`#table_stock_${id}`).html( formato_miles(stock_actual) );
    eliminarDetalle(id, count);

  } else if(type_recover == 'recover_remove_edit') { /* -- recuperamos cuando EDITAMOS una nueva venta -- */ 
    //  esta funcioalidad queda pendiente ---------
    
    $(`.btn-file-delete-${count}`).html(`<i class="fas fa-spinner fa-pulse"></i>`).addClass('disabled');     
    $(`#table_stock_${id}`).html( formato_miles(stock_actual) );    
    $(`#add-productos-eliminados`).append(`<input name="idproducto_recover[] value="" /><input name="stock_recover[] value="" />`);    
    eliminarDetalle(id, count);
     
  }
}

function autoincrement_comprobante(data) {
  var comprobante = $(data).select2('val');  

  if (comprobante == null || comprobante == '' ) {
    $('#serie_comprobante').val("");
  } else {
    
    $.post(`../ajax/ajax_general.php?op=autoincrement_comprobante`, function (e, textStatus, jqXHR) {
      e = JSON.parse(e); //console.log(e);

      if (comprobante == 'Boleta') {
        $('#serie_comprobante').val(`B-${e.data.venta_producto_b}`);
      } else if (comprobante == 'Factura'){
        $('#serie_comprobante').val(`F-${e.data.venta_producto_f}`);
      } else if (comprobante == 'Nota de venta'){
        $('#serie_comprobante').val(`NV-${e.data.venta_producto_nv}`);
      }
      
    },);
  }
}