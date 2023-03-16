//Requejo99@
var reload_detraccion = "";

var tabla_compra_insumo;
var tabla_comprobantes;

var tabla_compra_x_proveedor;
var tabla_detalle_compra_x_proveedor;

var tablamateriales;

var tabla_pagos1;
var tabla_pagos2;
var tabla_pagos3;

var array_doc = [];
var host = window.location.host == 'localhost'? `http://localhost/front_jdl/admin/dist/docs/compra_insumo/comprobante_compra/` : `${window.location.origin}/dist/docs/compra_insumo/comprobante_compra/` ;

var array_class_trabajador = [];

//Función que se ejecuta al inicio
function init() {

  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Compras").addClass("menu-open bg-color-191f24");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#mCompra").addClass("active bg-green");

  $("#lCompras").addClass("active");

  $("#idproyecto").val(localStorage.getItem("nube_idproyecto"));

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/ajax_general.php?op=select2Persona_por_tipo&tipo=3", '#idproveedor', null);
  lista_select2("../ajax/ajax_general.php?op=select2Persona_por_tipo&tipo=3", '#filtro_proveedor', null);
  lista_select2("../ajax/ajax_general.php?op=select2Banco", '#banco', null);
  lista_select2("../ajax/ajax_general.php?op=select2UnidaMedida", '#unidad_medida_pro', null);
  lista_select2("../ajax/ajax_general.php?op=select2Categoria", '#categoria_producto_pro', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro_compras").on("click", function (e) {  $("#submit-form-compras").submit(); });
  $("#guardar_registro_proveedor").on("click", function (e) { $("#submit-form-proveedor").submit(); });
  $("#guardar_registro_pago").on("click", function (e) {  $("#submit-form-pago").submit(); });
  $("#guardar_registro_comprobante_compra").on("click", function (e) {  $("#submit-form-comprobante-compra").submit();  });  
  $("#guardar_registro_material").on("click", function (e) {  $("#submit-form-producto").submit(); });  

  // ══════════════════════════════════════ INITIALIZE SELECT2 - FILTROS ══════════════════════════════════════
  $("#filtro_tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione comprobante", allowClear: true, });
  $("#filtro_proveedor").select2({ theme: "bootstrap4", placeholder: "Selecione proveedor", allowClear: true, });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - COMPRAS ══════════════════════════════════════
  $("#idproveedor").select2({templateResult: templatePersona, theme: "bootstrap4", placeholder: "Selecione proveedor", allowClear: true, });
  $("#tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione Comprobante", allowClear: true, });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - PAGO COMPRAS ══════════════════════════════════════
  $("#forma_pago").select2({ theme: "bootstrap4", placeholder: "Selecione una forma de pago", allowClear: true, });
  $("#tipo_pago").select2({ theme: "bootstrap4", placeholder: "Selecione un tipo de pago", allowClear: true, });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - PROVEEDOR ══════════════════════════════════════
  $("#banco").select2({templateResult: templateBanco, theme: "bootstrap4", placeholder: "Selecione un banco", allowClear: true, });
  $("#tipo_documento_per").select2({theme:"bootstrap4", placeholder: "Selec. tipo Doc.", allowClear: true, });
  
  // ══════════════════════════════════════ INITIALIZE SELECT2 - P R O D U C T O ══════════════════════════════════════
  $("#unidad_medida_pro").select2({ theme: "bootstrap4", placeholder: "Seleccinar una unidad", allowClear: true, });
  $("#categoria_producto_pro").select2({ theme: "bootstrap4", placeholder: "Seleccinar una categoria", allowClear: true, });

  no_select_tomorrow("#fecha_compra");
  no_select_over_18('#nacimiento_per');

  // ══════════════════════════════════════ INITIALIZE SELECT2 - MATERIAL ══════════════════════════════════════
  //$('#filtro_fecha_inicio').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
  
  // Inicializar - Date picker  
  $('#filtro_fecha_inicio').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });
  $('#filtro_fecha_fin').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });

  // ══════════════════════════════════════ I N I T I A L I Z E   N U M B E R   F O R M A T ══════════════════════════════════════
  $('#precio_unitario_p').number( true, 2 );
  $('#precio_sin_igv_p').number( true, 2 );
  $('#precio_igv_p').number( true, 2 );
  $('#precio_total_p').number( true, 2 );

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

  $("#idcompra_producto").val("");
  $("#idproveedor").val("null").trigger("change");
  $("#tipo_comprobante").val("Ninguno").trigger("change");

  $("#serie_comprobante").val("");
  $("#val_igv").val(0);
  $("#descripcion").val("");
  
  $("#total_venta").val("");  
  $(".total_venta").html("0");

  $(".subtotal_compra").html("S/ 0.00");
  $("#subtotal_compra").val("");

  $(".igv_compra").html("S/ 0.00");
  $("#igv_compra").val("");

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

function ver_form_add() {
  array_class_trabajador = [];
  $("#tabla-compra").hide();
  $("#tabla-compra-proveedor").hide();
  $("#agregar_compras").show();
  $("#regresar").show();
  $("#btn_agregar").hide();
  $("#guardar_registro_compras").hide();
  $("#div_tabla_compra").hide();
  $("#factura_compras").hide();

  // $(".leyecnda_pagos").hide();
  // $(".leyecnda_saldos").hide();
  listarmateriales();
}

function regresar() {
  $("#regresar").hide();
  $("#tabla-compra").show();
  $("#tabla-compra-proveedor").show();
  $("#agregar_compras").hide();
  $("#btn_agregar").show();
  $("#div_tabla_compra").show();
  $("#div_tabla_compra_proveedor").hide();
  //----
  $("#factura_compras").hide();
  $("#btn-factura").hide();
  //-----
  $("#pago_compras").hide();
  $("#btn-pagar").hide();

  // $(".leyecnda_pagos").show();
  // $(".leyecnda_saldos").show();

  $("#monto_total").html("");
  $("#ttl_monto_pgs_detracc").html("");
  $("#pagos_con_detraccion").hide();
  limpiar_form_compra();
  limpiar_form_proveedor();
}

//TABLA - COMPRAS
function tbla_principal(fecha_1, fecha_2, id_proveedor, comprobante) {
  //console.log(idproyecto);
  tabla_compra_insumo = $("#tabla-compra").dataTable({
    responsive: true, 
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,7,8,5,6], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,7,8,5,6], } }, 
      { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,2,3,7,8,5,6], } },              
    ],
    ajax: {
      url: `../ajax/compra_producto.php?op=tbla_principal&fecha_1=${fecha_1}&fecha_2=${fecha_2}&id_proveedor=${id_proveedor}&comprobante=${comprobante}`,
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
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    footerCallback: function( tfoot, data, start, end, display ) {
      var api = this.api(); var total = api.column( 5 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api.column( 5 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(total)}</span>` );
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
    columnDefs: [
      { targets: [7,8],  visible: false,  searchable: false,  },
      { targets: [5], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
      { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
    ],
  }).DataTable();

  $(tabla_compra_insumo).ready(function () {  $('.cargando').hide(); });

  //console.log(idproyecto);
  tabla_compra_x_proveedor = $("#tabla-compra-proveedor").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: ["copyHtml5", "excelHtml5",  "pdf"],
    ajax: {
      url: "../ajax/compra_producto.php?op=listar_compraxporvee",
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {
      //console.log(data);
      if (data[5] != '') {
        $("td", row).eq(5).addClass('text-right');
      }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    footerCallback: function( tfoot, data, start, end, display ) {
      var api = this.api(); var total = api.column( 5 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api.column( 5 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(total)}</span>` );
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
    columnDefs: [
      { targets: [5], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
    ],
  }).DataTable();
}

//facturas agrupadas por proveedor.
function listar_facuras_proveedor(idproveedor) {
  //console.log('idproyecto '+idproyecto, 'idproveedor '+idproveedor);
  $("#div_tabla_compra").hide();
  $("#agregar_compras").hide();
  $("#btn_agregar").hide();
  $("#regresar").show();
  $("#div_tabla_compra_proveedor").show();

  tabla_detalle_compra_x_proveedor = $("#detalles-tabla-compra-prov").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
    ajax: {
      url: "../ajax/compra_producto.php?op=listar_detalle_compraxporvee&idproveedor=" + idproveedor,
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
      return fetch("../ajax/compra_producto.php?op=guardaryeditarcompra", {
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

        tabla_compra_insumo.ajax.reload(null, false);
        tabla_compra_x_proveedor.ajax.reload(null, false);

        limpiar_form_compra(); regresar();
        
        $("#modal-agregar-usuario").modal("hide");        
      } else {
        ver_errores(result.value);
      }      
    }
  });  
}

//Función para eliminar registros
function eliminar_compra(idcompra_producto, nombre) {

  $(".tooltip").removeClass("show").addClass("hidde");

  crud_eliminar_papelera(
    "../ajax/compra_producto.php?op=anular",
    "../ajax/compra_producto.php?op=eliminar_compra", 
    idcompra_producto, 
    "!Elija una opción¡", 
    `<b class="text-danger">${nombre}</b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu compra ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu compra ha sido Eliminado.' ) }, 
    function(){ tabla_compra_insumo.ajax.reload(null, false); tabla_compra_x_proveedor.ajax.reload(null, false); },
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

function agregarDetalleComprobante(idproducto, nombre, unidad_medida, categoria, precio_venta, precio_compra, img, stock) {
  // console.log(idproducto,nombre,unidad_medida, categoria,precio_venta,img,stock);
  var stock = $(`#table_stock_${idproducto}`).text() == 0  ||  $(`#table_stock_${idproducto}`).text() == ''? 0 : parseFloat($(`#table_stock_${idproducto}`).text()) ;
  // var precio_venta = 0;
  var precio_sin_igv =0;
  var cantidad = 1;
  var descuento = 0;
  var precio_igv = 0;
  // console.log( stock );

  if (idproducto != "") {

    if ($(".producto_" + idproducto).hasClass("producto_selecionado")) {
      
      toastr_success("Agregado!!",`Producto: ${nombre} agregado !!`, 700);

      var cant_producto = $(".producto_" + idproducto).val();

      var sub_total = parseInt(cant_producto, 10) + 1;

      $(".producto_" + idproducto).val(sub_total);

      modificarSubtotales();
    } else {

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
          <button type="button" class="btn btn-danger btn-sm" onclick="eliminarDetalle(${cont})"><i class="fas fa-times"></i></button>
        </td>
        <td class="py-1">         
          <input type="hidden" name="idproducto[]" value="${idproducto}">
          <div class="user-block text-nowrap">
            <img class="profile-user-img img-responsive img-circle cursor-pointer img_perfil_${cont}" src="${img_p}" alt="user image" onerror="this.src='../dist/svg/404-v2.svg';" onclick="ver_img_producto('${img_p}', '${encodeHtml(nombre)}')">
            <span class="username"><p class="mb-0 nombre_producto_${cont}">${nombre}</p></span>
            <span class="description categoria_${cont}"><b>Categoría: </b>${categoria}</span>
          </div>
        </td>
        <td class="py-1"><span class="unidad_medida_${cont}">${unidad_medida}</span> <input class="unidad_medida_${cont}" type="hidden" name="unidad_medida[]" id="unidad_medida[]" value="${unidad_medida}"><input class="categoria_${cont}" type="hidden" name="categoria[]" id="categoria[]" value="${categoria}"></td>
        <td class="py-1 form-group"><input class="producto_${idproducto} producto_selecionado w-100px cantidad_${cont} form-control" type="number" name="cantidad[]" id="cantidad[]" value="${cantidad}" min="0.01" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
        <td class="py-1 hidden"><input type="number" class="w-135px input-no-border precio_sin_igv_${cont}" name="precio_sin_igv[]" id="precio_sin_igv[]" value="${parseFloat(precio_sin_igv).toFixed(2)}" readonly min="0" ></td>
        <td class="py-1 hidden"><input class="w-135px input-no-border precio_igv_${cont}" type="number" name="precio_igv[]" id="precio_igv[]" value="${parseFloat(precio_igv).toFixed(2)}" readonly  ></td>
        <td class="py-1 form-group">
          <input type="number" class="w-135px form-control valid_precio_con_igv "  name="valid_precio_con_igv[${cont}]" id="valid_precio_con_igv_${cont}" value="${parseFloat(precio_venta).toFixed(2)}" min="0.01" required onkeyup="replicar_precio_venta(${cont}, '#precio_con_igv_${cont}', this);" onchange="replicar_precio_venta(${cont}, '#precio_con_igv_${cont}', this);">
          <input type="hidden" class="precio_con_igv_${cont}" name="precio_con_igv[]" id="precio_con_igv_${cont}" value="${parseFloat(precio_venta).toFixed(2)}" onkeyup="modificarSubtotales();" onchange="modificarSubtotales();">
        </td>
        <td class="py-1 form-group">
          <input type="number" class="w-135px form-control valid_precio_venta" name="valid_precio_venta[${cont}]" id="valid_precio_venta_${cont}" value="${parseFloat(precio_venta).toFixed(2)}" min="0.01" required onkeyup="replicar_precio_venta(${cont}, '#precio_venta_${cont}', this);" onchange="replicar_precio_venta(${cont}, '#precio_venta_${cont}', this);" >
          <input type="hidden" class="precio_venta_${cont}" name="precio_venta[]" id="precio_venta_${cont}" value="${parseFloat(precio_venta).toFixed(2)}"  >
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
      $('.valid_precio_venta').each(function(e) { 
        $(this).rules('add', { required: true, messages: { required: 'Campo requerido' } }); 
        $(this).rules('add', { min:0.01, messages: { min:"Mínimo 0.01" } }); 
      });

      $('.valid_precio_con_igv').each(function(e) { 
        $(this).rules('add', { required: true, messages: { required: 'Campo requerido' } }); 
        $(this).rules('add', { min:0.01, messages: { min:"Mínimo 0.01" } }); 
      });

      cont++;
      evaluar();      
    }
  } else {
    // alert("Error al ingresar el detalle, revisar los datos del artículo");
    toastr_error("Error!!",`Error al ingresar el detalle, revisar los datos del producto.`, 700);
  }
}

function evaluar() {
  if (detalles > 0) {
    $("#guardar_registro_compras").show();
  } else {
    $("#guardar_registro_compras").hide();
    cont = 0;
    $(".subtotal_compra").html("S/ 0.00");
    $("#subtotal_compra").val(0);

    $(".igv_compra").html("S/ 0.00");
    $("#igv_compra").val(0);

    $(".total_venta").html("S/ 0.00");
    $("#total_compra").val(0);

  }
}

function default_val_igv() { if ($("#tipo_comprobante").select2("val") == "Factura") { $("#val_igv").val(0.18); } }

function modificarSubtotales() {  

  var val_igv = $('#val_igv').val(); //console.log(array_class_trabajador);

  if ($("#tipo_comprobante").select2("val") == null) {

    $(".hidden").hide(); //Ocultamos: IGV, PRECIO CON IGV

    $("#colspan_subtotal").attr("colspan", 6); //cambiamos el: colspan

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

      $("#colspan_subtotal").attr("colspan", 8); //cambiamos el: colspan
      
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

      $("#colspan_subtotal").attr("colspan", 6); //cambiamos el: colspan

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

    $(".igv_compra").html("S/ 0.00");
    $("#igv_compra").val(0.0);
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

  $(".igv_compra").html("S/ " + formato_miles(igv));
  $("#igv_compra").val(igv);

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

function eliminarDetalle(indice) {
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

//mostramos para editar el datalle del comprobante de la compras
function mostrar_compra(idcompra_producto) {

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  limpiar_form_compra();
  array_class_trabajador = [];

  cont = 0;
  detalles = 0;
  ver_form_add();

  $.post("../ajax/compra_producto.php?op=ver_compra_editar", { idcompra_producto: idcompra_producto }, function (e, status) {
    
    e = JSON.parse(e); //console.log(e);

    if (e.status == true) {

      console.log(e.data.compra.tipo_comprobante);
      if (e.data.compra.tipo_comprobante == "Factura") {
        $(".content-igv").show();
        $(".content-tipo-comprobante").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
        $(".content-descripcion").removeClass("col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
        $(".content-serie-comprobante").show();
      } else if (e.data.compra.tipo_comprobante == "Boleta" || e.data.compra.tipo_comprobante == "Nota de venta") {
        $(".content-serie-comprobante").show();
        $(".content-igv").hide();
        $(".content-tipo-comprobante").removeClass("col-lg-4 col-lg-5").addClass("col-lg-5");
        $(".content-descripcion").removeClass(" col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
      } else if (e.data.compra.tipo_comprobante == "Ninguno") {
        $(".content-serie-comprobante").hide();
        $(".content-serie-comprobante").val("");
        $(".content-igv").hide();
        $(".content-tipo-comprobante").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
        $(".content-descripcion").removeClass(" col-lg-4 col-lg-5 col-lg-7").addClass("col-lg-8");
      } else {
        $(".content-serie-comprobante").show();
        //$(".content-descripcion").removeClass("col-lg-7").addClass("col-lg-4");
      }

      $("#idcompra_producto").val(e.data.compra.idcompra_producto);
      $("#idproveedor").val(e.data.compra.idpersona).trigger("change");
      $("#fecha_compra").val(e.data.compra.fecha_compra);
      $("#tipo_comprobante").val(e.data.compra.tipo_comprobante).trigger("change");
      $("#serie_comprobante").val(e.data.compra.serie_comprobante).trigger("change");
      $("#val_igv").val(e.data.compra.val_igv);
      $("#descripcion").val(e.data.compra.descripcion);

      if (e.data.detalle) {

        e.data.detalle.forEach((element, index) => {

          var img = "";

          if (element.imagen == "" || element.imagen == null) {
            img = `../dist/docs/producto/img_perfil/producto-sin-foto.svg`;
          } else {
            img = `../dist/docs/producto/img_perfil/${element.imagen}`;
          }

          var fila = `
          <tr class="filas" id="fila${cont}">
            <td>
              <button type="button" class="btn btn-warning btn-sm" onclick="mostrar_productos(${element.idproducto}, ${cont})"><i class="fas fa-pencil-alt"></i></button>
              <button type="button" class="btn btn-danger btn-sm" onclick="eliminarDetalle(${cont})"><i class="fas fa-times"></i></button></td>
            </td>
            <td>
              <input type="hidden" name="idproducto[]" value="${element.idproducto}">
              <div class="user-block text-nowrap">
                <img class="profile-user-img img-responsive img-circle cursor-pointer img_perfil_${cont}" src="${img}" alt="user image" onerror="this.src='../dist/svg/404-v2.svg';" onclick="ver_img_producto('${img}', '${encodeHtml(element.nombre)}')">
                <span class="username"><p class="mb-0 nombre_producto_${cont}" >${element.nombre}</p></span>
                <span class="description categoria_${cont}"><b>Categoría: </b>${element.categoria}</span>
              </div>
            </td>
            <td> <span class="unidad_medida_${cont}">${element.unidad_medida}</span> <input class="unidad_medida_${cont}" type="hidden" name="unidad_medida[]" id="unidad_medida[]" value="${element.unidad_medida}"> <input class="categoria_${cont}" type="hidden" name="categoria[]" id="categoria[]" value="${element.categoria}"></td>
            <td class="form-group"><input class="producto_${element.idproducto} producto_selecionado w-100px cantidad_${cont} form-control" type="number" name="cantidad[]" id="cantidad[]" value="${element.cantidad}" min="0.01" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
            <td class="hidden"><input class="w-135px input-no-border precio_sin_igv_${cont}" type="number" name="precio_sin_igv[]" id="precio_sin_igv[]" value="${element.precio_sin_igv}" readonly ></td>
            <td class="hidden"><input class="w-135px input-no-border precio_igv_${cont}" type="number"  name="precio_igv[]" id="precio_igv[]" value="${element.igv}" readonly ></td>
            <td class="form-group"><input type="number" class="w-135px precio_con_igv_${cont} form-control" type="number"  name="precio_con_igv[]" id="precio_con_igv[]" value="${parseFloat(element.precio_con_igv).toFixed(2)}" min="0.01" required onkeyup="modificarSubtotales();" onchange="modificarSubtotales();"></td>
            <td class="form-group"><input type="number" class="w-135px form-control precio_venta_${cont}" name="precio_venta[]" id="precio_venta[]" value="${parseFloat(element.precio_venta).toFixed(2)}" min="0" ></td>
            <td><input type="number" class="w-135px descuento_${cont}" name="descuento[]" value="${element.descuento}" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
            <td class="text-right"><span class="text-right subtotal_producto_${cont}" name="subtotal_producto" id="subtotal_producto">0.00</span></td>
            <td><button type="button" onclick="modificarSubtotales()" class="btn btn-info btn-sm"><i class="fas fa-sync"></i></button></td>
          </tr>`;

          detalles = detalles + 1;

          $("#detalles").append(fila);

          array_class_trabajador.push({ id_cont: cont });

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

//mostramos para editar el datalle del comprobante de la compras
function copiar_venta(idcompra_producto) {

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  limpiar_form_compra();
  array_class_trabajador = [];

  cont = 0;
  detalles = 0;
  ver_form_add();

  $.post("../ajax/compra_producto.php?op=ver_compra_editar", { idcompra_producto: idcompra_producto }, function (e, status) {
    
    e = JSON.parse(e); //console.log(e);

    if (e.status == true) {

      console.log(e.data.compra.tipo_comprobante);
      if (e.data.compra.tipo_comprobante == "Factura") {
        $(".content-igv").show();
        $(".content-tipo-comprobante").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
        $(".content-descripcion").removeClass("col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
        $(".content-serie-comprobante").show();
      } else if (e.data.compra.tipo_comprobante == "Boleta" || e.data.compra.tipo_comprobante == "Nota de venta") {
        $(".content-serie-comprobante").show();
        $(".content-igv").hide();
        $(".content-tipo-comprobante").removeClass("col-lg-4 col-lg-5").addClass("col-lg-5");
        $(".content-descripcion").removeClass(" col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
      } else if (e.data.compra.tipo_comprobante == "Ninguno") {
        $(".content-serie-comprobante").hide();
        $(".content-serie-comprobante").val("");
        $(".content-igv").hide();
        $(".content-tipo-comprobante").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
        $(".content-descripcion").removeClass(" col-lg-4 col-lg-5 col-lg-7").addClass("col-lg-8");
      } else {
        $(".content-serie-comprobante").show();
        //$(".content-descripcion").removeClass("col-lg-7").addClass("col-lg-4");
      }

      // $("#idcompra_producto").val(e.data.compra.idcompra_producto); #no se usa cuando se copia
      $("#idproveedor").val(e.data.compra.idpersona).trigger("change");
      $("#fecha_compra").val(e.data.compra.fecha_compra);
      $("#tipo_comprobante").val(e.data.compra.tipo_comprobante).trigger("change");
      $("#serie_comprobante").val(e.data.compra.serie_comprobante).trigger("change");
      $("#val_igv").val(e.data.compra.val_igv);
      $("#descripcion").val(e.data.compra.descripcion);

      if (e.data.detalle) {

        e.data.detalle.forEach((element, index) => {

          var img = "";

          if (element.imagen == "" || element.imagen == null) {
            img = `../dist/docs/producto/img_perfil/producto-sin-foto.svg`;
          } else {
            img = `../dist/docs/producto/img_perfil/${element.imagen}`;
          }

          var fila = `
          <tr class="filas" id="fila${cont}">
            <td>
              <button type="button" class="btn btn-warning btn-sm" onclick="mostrar_productos(${element.idproducto}, ${cont})"><i class="fas fa-pencil-alt"></i></button>
              <button type="button" class="btn btn-danger btn-sm" onclick="eliminarDetalle(${cont})"><i class="fas fa-times"></i></button></td>
            </td>
            <td>
              <input type="hidden" name="idproducto[]" value="${element.idproducto}">
              <div class="user-block text-nowrap">
                <img class="profile-user-img img-responsive img-circle cursor-pointer img_perfil_${cont}" src="${img}" alt="user image" onerror="this.src='../dist/svg/404-v2.svg';" onclick="ver_img_producto('${img}', '${encodeHtml(element.nombre)}')">
                <span class="username"><p class="mb-0 nombre_producto_${cont}" >${element.nombre}</p></span>
                <span class="description categoria_${cont}"><b>Categoría: </b>${element.categoria}</span>
              </div>
            </td>
            <td> <span class="unidad_medida_${cont}">${element.unidad_medida}</span> <input class="unidad_medida_${cont}" type="hidden" name="unidad_medida[]" id="unidad_medida[]" value="${element.unidad_medida}"> <input class="categoria_${cont}" type="hidden" name="categoria[]" id="categoria[]" value="${element.categoria}"></td>
            <td class="form-group"><input class="producto_${element.idproducto} producto_selecionado w-100px cantidad_${cont} form-control" type="number" name="cantidad[]" id="cantidad[]" value="${element.cantidad}" min="0.01" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
            <td class="hidden"><input class="w-135px input-no-border precio_sin_igv_${cont}" type="number" name="precio_sin_igv[]" id="precio_sin_igv[]" value="${element.precio_sin_igv}" readonly ></td>
            <td class="hidden"><input class="w-135px input-no-border precio_igv_${cont}" type="number"  name="precio_igv[]" id="precio_igv[]" value="${element.igv}" readonly ></td>
            <td class="form-group"><input type="number" class="w-135px precio_con_igv_${cont} form-control" type="number"  name="precio_con_igv[]" id="precio_con_igv[]" value="${parseFloat(element.precio_con_igv).toFixed(2)}" min="0.01" required onkeyup="modificarSubtotales();" onchange="modificarSubtotales();"></td>
            <td class="form-group"><input type="number" class="w-135px form-control precio_venta_${cont}" name="precio_venta[]" id="precio_venta[]" value="${parseFloat(element.precio_venta).toFixed(2)}" min="0" ></td>
            <td class="form-group"><input type="number" class="w-135px form-control descuento_${cont}" name="descuento[]" value="${element.descuento}" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
            <td class="text-right"><span class="text-right subtotal_producto_${cont}" name="subtotal_producto" id="subtotal_producto">0.00</span></td>
            <td><button type="button" onclick="modificarSubtotales()" class="btn btn-info btn-sm"><i class="fas fa-sync"></i></button></td>
          </tr>`;

          detalles = detalles + 1;

          $("#detalles").append(fila);

          array_class_trabajador.push({ id_cont: cont });

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

//mostramos el detalle del comprobante de la compras
function ver_detalle_compras(idcompra_producto) {

  $("#cargando-5-fomulario").hide();
  $("#cargando-6-fomulario").show();

  $("#print_pdf_compra").addClass('disabled');
  $("#excel_compra").addClass('disabled');

  $("#modal-ver-compras").modal("show");

  $.post("../ajax/ajax_general.php?op=ver_detalle_compras&id_compra=" + idcompra_producto, function (r) {
    r = JSON.parse(r);
    if (r.status == true) {
      $(".detalle_de_compra").html(r.data); 
      $("#cargando-5-fomulario").show();
      $("#cargando-6-fomulario").hide();

      $("#excel_compra").removeClass('disabled').attr('href', `../reportes/export_xlsx_compra_producto.php?id=${idcompra_producto}`);
      $("#print_pdf_compra").removeClass('disabled').attr('href', `../reportes/pdf_compra_productos.php?id=${idcompra_producto}` );
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}


// :::::::::::::::::::::::::: - S E C C I O N   D E S C A R G A S -  ::::::::::::::::::::::::::

function download_no_multimple(id_compra, cont, nombre_doc) {
  $(`.descarga_compra_${id_compra}`).html('<i class="fas fa-spinner fa-pulse"></i>');
  //console.log(id_compra, nombre_doc);
  var cant_download_ok = 0; var cant_download_error = 0;
  $.post("../ajax/compra_producto.php?op=ver_comprobante_compra", { 'id_compra': id_compra }, function (e, textStatus, jqXHR) {
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
    $.post("../ajax/compra_producto.php?op=ver_comprobante_compra", { 'id_compra': id_compra }, function (e, textStatus, jqXHR) {
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
    $.post("../ajax/compra_producto.php?op=ver_comprobante_compra", { 'id_compra': id_compra }, function (e, textStatus, jqXHR) {
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

// :::::::::::::::::::::::::: S E C C I O N   P R O V E E D O R  ::::::::::::::::::::::::::

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
  $("#id_tipo_persona_per").val("3");

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
    url: "../ajax/compra_producto.php?op=guardar_proveedor",
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
          lista_select2("../ajax/ajax_general.php?op=select2Persona_por_tipo&tipo=3", '#idproveedor', e.data);
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
      $("#barra_progress_proveedor_div").show();
      $("#guardar_registro_proveedor").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_proveedor").css({ width: "0%",  });
      $("#barra_progress_proveedor").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_proveedor_div").hide();
      $("#barra_progress_proveedor").css({ width: "0%", });
      $("#barra_progress_proveedor").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar_para_editar_proveedor() {
  $("#cargando-11-fomulario").hide();
  $("#cargando-12-fomulario").show();

  $('#modal-agregar-proveedor').modal('show');
  $(".tooltip").remove();

  $.post("../ajax/compra_producto.php?op=mostrar_editar_proveedor", { 'idproveedor': $('#idproveedor').select2("val") }, function (e, status) {

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
      { text: '<i class="fa-solid fa-arrows-rotate"></i>', action: function ( e, dt, node, config ) { tablamateriales.ajax.reload(); toastr_success('Exito!!', 'Actualizando tabla', 400); } }
    ],
    ajax: {
      url: "../ajax/ajax_general.php?op=tblaProductos",
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
    iDisplayLength: 10, //Paginación
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

  $.post("../ajax/compra_producto.php?op=mostrar_productos", { 'idproducto_pro': idproducto }, function (e, status) {
    
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
    url: "../ajax/compra_producto.php?op=guardar_y_editar_productos",
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

  $("#idproveedor").on('change', function() { $(this).trigger('blur'); });
  $("#tipo_comprobante").on('change', function() { $(this).trigger('blur'); });
  $("#tipo_documento_per").on('change', function() { $(this).trigger('blur'); });
  $("#banco").on('change', function() { $(this).trigger('blur'); });
  
  $('#unidad_medida_pro').on('change', function() { $(this).trigger('blur'); });
  $('#categoria_producto_pro').on('change', function() { $(this).trigger('blur'); });

  $("#form-compras").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      idproveedor:        { required: true },
      tipo_comprobante:   { required: true },
      serie_comprobante:  { minlength: 2 },
      descripcion:        { minlength: 4 },
      fecha_compra:       { required: true },
      val_igv:            { required: true, number: true, min:0, max:1 },
    },
    messages: {
      idproveedor:        { required: "Campo requerido", },
      tipo_comprobante:   { required: "Campo requerido", },
      serie_comprobante:  { minlength: "Minimo 2 caracteres", },
      descripcion:        { minlength: "Minimo 4 caracteres", },
      fecha_compra:       { required: "Campo requerido", },
      val_igv:            { required: "Campo requerido", number: 'Ingrese un número', min:'Mínimo 0', max:'Maximo 1' },
      'cantidad[]':       { min: "Mínimo 0.01", required: "Campo requerido"},
      'precio_con_igv[]': { min: "Mínimo 0.01", required: "Campo requerido"},
      'descuento[]':      { min: "Mínimo 0.00", required: "Campo requerido"},
      // 'precio_con_igv[]': { min: "Mínimo 0.01", required: "Campo requerido"},
      // 'precio_con_igv[]': { min: "Mínimo 0.01", required: "Campo requerido"},
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

  $("#idproveedor").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#tipo_comprobante").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#tipo_documento_per").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#banco").rules('add', { required: true, messages: {  required: "Campo requerido" } });

  $('#unidad_medida_pro').rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $('#categoria_producto_pro').rules('add', { required: true, messages: {  required: "Campo requerido" } });

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

function replicar_precio_venta(id, name_input, valor) {
  var value = $(valor).val(); console.log(value);
  $(`${name_input}`).val(value).trigger("change");
}



