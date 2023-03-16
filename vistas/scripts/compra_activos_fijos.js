var tabla;
var tabla_comp_prov;
var tablaactivos1;
var tabla_list_comp_prov;
var tabla_pagos1;

var array_class_trabajador = [];

//Requejo99@
function init() {
  
  $("#mAllactivos_fijos").addClass("active");

  fecha_actual();

  $("#idproyecto").val(localStorage.getItem("nube_idproyecto"));
  $("#idproyecto_pago").val(localStorage.getItem("nube_idproyecto"));

  tbla_principal();  

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════ 
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#idproveedor', null);
  lista_select2("../ajax/ajax_general.php?op=select2Color", '#color_p', null);
  lista_select2("../ajax/ajax_general.php?op=select2marcas_activos", '#marca_p', null);
  lista_select2("../ajax/ajax_general.php?op=select2UnidaMedida", '#unidad_medida_p', null);
  lista_select2("../ajax/ajax_general.php?op=select2Categoria", '#categoria_insumos_af_p', null);
  lista_select2("../ajax/ajax_general.php?op=select2Banco", '#banco_pago', null);
  lista_select2("../ajax/ajax_general.php?op=select2Banco", '#banco_prov', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════ 
  $("#guardar_registro_compras").on("click", function (e) { $("#submit-form-compra-activos-f").submit(); });

  $("#guardar_registro_proveedor").on("click", function (e) { $("#submit-form-proveedor").submit(); });

  $("#guardar_registro_pago").on("click", function (e) { $("#submit-form-pago").submit(); });

  $("#guardar_registro_material").on("click", function (e) {  $("#submit-form-materiales").submit(); });  
  //subir factura modal
  $("#guardar_registro_comprobante").on("click", function (e) { $("#submit-form-comprobante").submit(); });

  // ══════════════════════════════════════ SELECT2 - COMPRAS ACTIVOS ══════════════════════════════════════

  $("#idproveedor").select2({ theme: "bootstrap4", placeholder: "Selecione trabajador", allowClear: true, });

  $("#glosa").select2({templateResult: templateGlosa, theme: "bootstrap4", placeholder: "Selecione Glosa", allowClear: true, });

  $("#tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione Comprobante", allowClear: true, });

  // ══════════════════════════════════════ SELECT2 - PAGO COMPRAS ACTIVOS ══════════════════════════════════════

  $("#forma_pago").select2({ theme: "bootstrap4", placeholder: "Selecione una forma de pago", allowClear: true, });

  $("#tipo_pago").select2({ theme: "bootstrap4", placeholder: "Selecione un tipo de pago", allowClear: true, });

  $("#banco_pago").select2({ templateResult: templateBanco, theme: "bootstrap4", placeholder: "Selecione un banco", allowClear: true, });

  // ══════════════════════════════════════ SELECT2 - PROVEEDOR ══════════════════════════════════════

  $("#banco_prov").select2({ templateResult: templateBanco, theme: "bootstrap4", placeholder: "Selecione un banco", allowClear: true, });

  // ══════════════════════════════════════ SELECT2 - MATERIAL ══════════════════════════════════════

  $("#categoria_insumos_af_p").select2({ theme: "bootstrap4", placeholder: "Seleccinar color", allowClear: true, });  
  $("#marca_p").select2({ theme: "bootstrap4", placeholder: "Seleccinar marca", allowClear: true, });

  $("#color_p").select2({templateResult: templateColor, theme: "bootstrap4", placeholder: "Seleccinar color", allowClear: true, });

  $("#unidad_medida_p").select2({ theme: "bootstrap4", placeholder: "Seleccinar una unidad", allowClear: true, });

  // ══════════════════════════════════════ I N I T I A L I Z E   N U M B E R   F O R M A T ══════════════════════════════════════
  $('#precio_unitario_p').number( true, 2 );
  $('#precio_sin_igv_p').number( true, 2 );
  $('#precio_igv_p').number( true, 2 );
  $('#precio_total_p').number( true, 2 );

  // Formato para telefono
  $("[data-mask]").inputmask();
}

function templateBanco (state) {
  //console.log(state);
  if (!state.id) { return state.text; }
  var baseUrl = state.title != '' ? `../dist/docs/banco/logo/${state.title}`: '../dist/docs/banco/logo/logo-sin-banco.svg'; 
  var onerror = `onerror="this.src='../dist/docs/banco/logo/logo-sin-banco.svg';"`;
  var $state = $(`<span><img src="${baseUrl}" class="img-circle mr-2 w-25px" ${onerror} />${state.text}</span>`);
  return $state;
};

function templateColor (state) {
  if (!state.id) { return state.text; }
  var color_bg = state.title != '' ? `${state.title}`: '#ffffff00';   
  var $state = $(`<span ><b style="background-color: ${color_bg}; color: ${color_bg};" class="mr-2"><i class="fas fa-square"></i><i class="fas fa-square"></i></b>${state.text}</span>`);
  return $state;
}

function templateGlosa (state) {
  if (!state.id) { return state.text; }  
  var $state = $(`<span ><b class="mr-2"><i class="${state.title}"></i></b>${state.text}</span>`);
  return $state;
}

//pago compra ativo fijo
$("#foto1_i").click(function () {$("#foto1").trigger("click"); });
$("#foto1").change(function (e) {addImage(e, $("#foto1").attr("id")); });

//subir factura compra ativo fijo
$("#doc1_i").click(function () { $("#doc1").trigger("click");  });
$("#doc1").change(function (e) { addImageApplication(e, $("#doc1").attr("id")); });

// Perfil material
$("#foto2_i").click(function () {  $("#foto2").trigger("click"); });
$("#foto2").change(function (e) { addImage(e, $("#foto2").attr("id"), '../dist/img/default/img_defecto_activo_fijo.png'); });

//ficha tecnica
$("#doc2_i").click(function() {  $('#doc2').trigger('click'); });
$("#doc2").change(function(e) {  addImageApplication(e,$("#doc2").attr("id")) }); 

function foto1_eliminar() {
  $("#foto1").val("");

  $("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");

  $("#foto1_nombre").html("");
}

function foto2_eliminar() {
  $("#foto2").val("");

  $("#foto2_i").attr("src", "../dist/img/default/img_defecto_activo_fijo.png");

  $("#foto2_nombre").html("");
}

// Eliminamos el doc comprobante
function doc1_eliminar() {
  $("#doc1").val("");

  $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

  $("#doc1_nombre").html("");
}

// Eliminamos el doc comprobante proyecto
function doc2_eliminar() {
  $("#doc2").val("");

  $("#doc2_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

  $("#doc2_nombre").html("");
}

//Función limpiar
function limpiar_form_compra() {
  $(".tooltip").remove();

  $("#idcompra_af_general").val("");
  $("#idcompra_proyecto").val("");
  $("#idproyecto").val("");
  $("#idproveedor").val("null").trigger("change");
  $("#tipo_comprobante").val("Ninguno").trigger("change");
  $("#glosa").val("null").trigger("change");

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

function limpiar_form_comprobante_compra() {

  $("#idcompra").val("");

  $("#doc_old_2").val("");
  $("#doc2").val("");  
  $('#doc2_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc2_nombre').html("");
}

// OCULTAR MOSTRAR - TABLAS
function table_show_hide(flag) {
  if (flag == 1) {
         
    $("#btn-regresar").hide(); 
    $("#btn-agregar").show();
    $("#btn-pagar").hide();

    $("#guardar_registro_compras").hide();

    $("#div-tabla-principal").show();
    $("#div-tabla-compra-por-proveedor").show();
    $("#div-tabla-detalle-compra-proveedor").hide();
    $("#div-pago-compras").hide();

    $("#formulario-agregar-compra").hide();
    $(".nombre-title-page").html(`<i class="fas fa-hand-holding-usd"></i> Compras de Activos Fijos`);

  } else if (flag == 2) {    

    array_class_trabajador = [];

    $("#btn-regresar").show(); 
    $("#btn-agregar").hide();
    $("#btn-pagar").hide();

    $("#guardar_registro_compras").hide();

    $("#div-tabla-principal").hide();
    $("#div-tabla-compra-por-proveedor").hide();
    $("#div-tabla-detalle-compra-proveedor").hide();
    $("#div-pago-compras").hide();

    $("#formulario-agregar-compra").show();

    tblaActivosFijos();

  }else if (flag == 3) {
      
    $("#btn-regresar").show(); 
    $("#btn-agregar").hide();
    $("#btn-pagar").show();

    $("#guardar_registro_compras").hide();

    $("#div-tabla-principal").hide();
    $("#div-tabla-compra-por-proveedor").hide();
    $("#div-tabla-detalle-compra-proveedor").hide();
    $("#div-pago-compras").show();

    $("#formulario-agregar-compra").hide();
  }else if (flag == 4) {
    $("#btn-regresar").hide(); 
    $("#btn-agregar").show();
    $("#btn-pagar").hide();

    $("#guardar_registro_compras").hide();

    $("#div-tabla-principal").show();
    $("#div-tabla-compra-por-proveedor").hide();
    $("#div-tabla-detalle-compra-proveedor").show();
    $("#div-pago-compras").hide();
  }
}


//::::::::::::::LISTAMOS LAS TABLAS PRINCIPALES:::::::::
//tabla
function tbla_principal() {
  //console.log(idproyecto);
  tabla = $("#tabla-compra").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,10,11,12,13,14,15,6,14,15,16,17,18], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,10,11,12,13,14,15,6,14,15,16,17,18], } }, { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,2,3,10,11,12,13,14,15,6,14,15,16,17,18], } }, {extend: "colvis"} ,      
    ],
    ajax: {
      url: "../ajax/compra_activos_fijos.php?op=tbla_compra_activos_fijos",
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: #
      if (data[1] != '') { $("td", row).eq(1).addClass("text-nowrap"); }
      // columna: #
      if (data[3] != '') { $("td", row).eq(3).addClass("text-nowrap"); }
      // columna: #
      if (data[6] != '') { $("td", row).eq(6).addClass("text-nowrap text-right"); }
      //console.log(data);
      if (quitar_formato_miles(data[8]) > 0) {
        // $("td", row).eq(8).css({ "background-color": "#ffc107", color: "black", });
        $("td", row).eq(8).addClass("text-nowrap bg-warning text-right");
      } else if (quitar_formato_miles(data[8]) == 0) {
        //$("td", row).eq(8).css({ "background-color": "#28a745", color: "white", });
        $("td", row).eq(8).addClass("text-nowrap bg-success text-right");
      } else {
        //$("td", row).eq(8).css({ "background-color": "#ff5252", color: "white", });
        $("td", row).eq(8).addClass("text-nowrap bg-danger text-right");
      }
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
      { targets: [3], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD-MM-YYYY'), },
      { targets: [10,11,12,13,14,15,16,17,18], visible: false, searchable: false, },
    ],
  }).DataTable();

  //console.log(idproyecto);
  tabla_comp_prov = $("#tabla-compra-proveedor").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,4], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,4], } }, { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,2,3,4], } }, {extend: "colvis"} ,
    ],
    ajax: {
      url: "../ajax/compra_activos_fijos.php?op=tbla_compra_x_porveedor",
      type: "get",
      dataType: "json",
      error: function (e) { console.log(e.responseText); ver_errores(e); },
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
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

//tabla
function listar_facuras_x_proveedor(idproveedor, nombre_proveedor) {

  table_show_hide(4); 
  $(".proveedor-lista-facturas").html(nombre_proveedor);

  tabla_list_comp_prov = $("#tabla-detalles-compra-proveedor").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5,6], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5,6], } }, { extend: 'pdfHtml5', exportOptions: { columns: [0,2,3,4,5,6], } }, {extend: "colvis"} ,
    ],
    ajax: {
      url: `../ajax/compra_activos_fijos.php?op=tbla_detalle_compra_x_porveedor&idproveedor=${idproveedor}`,
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
    iDisplayLength: 10, //Paginación
    order: [[1, "desc"]], //Ordenar (columna,orden)
    columnDefs: [
      { /* targets: [8], visible: true, searchable: true,*/ },
      { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD-MM-YYYY'), }
    ],
  }).DataTable();
}

//Función para guardar o editar - COMPRAS
function guardaryeditar_compras(e) {

  var formData = new FormData($("#form-compra-activos-f")[0]);

  Swal.fire({
    title: "¿Está seguro que deseas guardar esta compra?",
    html: "Verifica que todos lo <b>campos</b>  esten <b>conformes</b>!!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Guardar!",
    preConfirm: (input) => {
      return fetch("../ajax/compra_activos_fijos.php?op=guardaryeditarcompraactivo", {
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
    console.log(result);
    if (result.isConfirmed) {
      if (result.value.status == true){        
        Swal.fire("Correcto!", "Compra guardada correctamente", "success");
        if (tabla) { tabla.ajax.reload(null, false); } 
        if (tabla_comp_prov) { tabla_comp_prov.ajax.reload(null, false); }
        limpiar_form_compra(); table_show_hide(1);
        cont = 0;        
      } else {
        ver_errores(result.value);
      }      
    }
  });
}

function eliminar_compra(idcompra_af_general, nombre) {

  $(".tooltip").remove();

  crud_eliminar_papelera(
    "../ajax/compra_activos_fijos.php?op=anular_compra",
    "../ajax/compra_activos_fijos.php?op=eliminar_compra", 
    idcompra_af_general, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla.ajax.reload(null, false); tabla_comp_prov.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );

}

// .......:::::::::::::::::: AGREGAR FACURAS, BOLETAS, NOTA DE VENTA, ETC ::::::::::::.......

//Declaración de variables necesarias para trabajar con las compras y sus detalles
var impuesto = 18;
var cont = 0;
var detalles = 0;
var cont_p = 0;
var detalles_p = 0;

function agregarDetalleComprobante(idproducto, nombre, unidad_medida, nombre_color, precio_sin_igv, precio_igv, precio_total, img, ficha_tecnica_producto) {
  var stock = 5;
  var cantidad = 1;
  var descuento = 0;

  if (idproducto != "") {
    // $('.producto_'+idproducto).addClass('producto_selecionado');
    if ($(".producto_" + idproducto).hasClass("producto_selecionado")) {
      toastr.success("Material: " + nombre + " agregado !!");

      var cant_producto = $(".producto_" + idproducto).val();

      var sub_total = parseInt(cant_producto, 10) + 1;

      $(".producto_" + idproducto).val(sub_total);

      modificarSubtotales();
    } else {

      if ($("#tipo_comprobante").select2("val") == "Factura") {
        var subtotal = cantidad * precio_total;
      } else {
        var subtotal = cantidad * precio_sin_igv;
      }

      var img_p = "";

      if (img == "" || img == null) {
        img_p = "../dist/docs/material/img_perfil/producto-sin-foto.svg";
      } else {
        img_p = `../dist/docs/material/img_perfil/${img}`;
      }

      var fila = `
      <tr class="filas" id="fila${cont}">         
        <td class="">
          <button type="button" class="btn btn-warning btn-sm" onclick="mostrar_material(${idproducto}, ${cont})" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>
          <button type="button" class="btn btn-danger btn-sm" onclick="eliminarDetalle(${cont})" data-toggle="tooltip" data-original-title="Eliminar"><i class="fas fa-times"></i></button>
        </td>
        <td class="">         
          <input type="hidden" name="idproducto[]" value="${idproducto}">
          <input type="hidden" name="ficha_tecnica_producto[]" value="${ficha_tecnica_producto}">
          <div class="user-block text-nowrap">
            <img class="profile-user-img img-responsive img-circle cursor-pointer img_perfil_${cont}" src="${img_p}" alt="user image" onerror="this.src='../dist/svg/404-v2.svg';" onclick="ver_img_producto('${img_p}', '${encodeHtml(nombre)}', ${cont} )" data-toggle="tooltip" data-original-title="Ver imagen">
            <span class="username"><p class="mb-0 nombre_producto_${cont}">${nombre}</p></span>
            <span class="description color_${cont}"><b>Color: </b>${nombre_color}</span>
          </div>
        </td>
        <td class=""><span class="unidad_medida_${cont}">${unidad_medida}</span> <input class="unidad_medida_${cont}" type="hidden" name="unidad_medida[]" id="unidad_medida[]" value="${unidad_medida}"><input class="color_${cont}" type="hidden" name="nombre_color[]" id="nombre_color[]" value="${nombre_color}"></td>
        <td class=" form-group"><input class="producto_${idproducto} producto_selecionado w-100px cantidad_${cont} form-control" type="number" name="cantidad[]" id="cantidad[]" value="${cantidad}" min="0.01" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
        <td class=" hidden"><input type="number" class="w-135px input-no-border precio_sin_igv_${cont}" name="precio_sin_igv[]" id="precio_sin_igv[]" value="${parseFloat(precio_sin_igv).toFixed(2)}" readonly min="0" ></td>
        <td class=" hidden"><input class="w-135px input-no-border precio_igv_${cont}" type="number" name="precio_igv[]" id="precio_igv[]" value="${parseFloat(precio_igv).toFixed(2)}" readonly  ></td>
        <td class="form-group"><input class="w-135px precio_con_igv_${cont} form-control" type="number" name="precio_con_igv[]" id="precio_con_igv[]" value="${parseFloat(precio_total).toFixed(2)}" min="0.01" required onkeyup="modificarSubtotales();" onchange="modificarSubtotales();"></td>
        <td class=""><input type="number" class="w-135px descuento_${cont}" name="descuento[]" value="${descuento}" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
        <td class=" text-right"><span class="text-right subtotal_producto_${cont}" name="subtotal_producto" id="subtotal_producto">${subtotal}</span></td>
        <td class=""><button type="button" onclick="modificarSubtotales()" class="btn btn-info btn-sm" data-toggle="tooltip" data-original-title="Actualizar precios"><i class="fas fa-sync"></i></button></td>
      </tr>`;

      detalles = detalles + 1;

      $("#detalles").append(fila);

      array_class_trabajador.push({ id_cont: cont });

      modificarSubtotales();

      toastr.success("Material: " + nombre + " agregado !!");

      cont++;
      evaluar();
      $('[data-toggle="tooltip"]').tooltip();
    }
  } else {
    // alert("Error al ingresar el detalle, revisar los datos del artículo");
    toastr.error("Error al ingresar el detalle, revisar los datos del material.");
  }

  $(".tooltip").remove();
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
  toastr.success("Precio Actualizado !!!");
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
      toastr.success('No has difinido un tipo de calculo de IGV.')
    break;
  } 
  
  return precio_sin_igv; 
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

  toastr.warning("Producto removido.");
}

//MOSTRAR - EDITAR LA COMPRA DE ACTIVOS GENERAL
function mostrar_compra_general(idcompra_af_general) {

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  limpiar_form_compra();
  array_class_trabajador = [];

  cont = 0; detalles = 0;

  table_show_hide(2);

  $.post("../ajax/compra_activos_fijos.php?op=ver_compra_editar", { idcompra_af_general: idcompra_af_general }, function (e, status) {
    
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
        //$(".content-descripcion").removeClass("col-lg-7").addClass("col-lg-4");
      }

      $("#idcompra_af_general").val(e.data.idcompra_af_general);
      $("#idproveedor").val(e.data.idproveedor).trigger("change");
      $("#fecha_compra").val(e.data.fecha_compra);
      $("#tipo_comprobante").val(e.data.tipo_comprobante).trigger("change");
      $("#serie_comprobante").val(e.data.serie_comprobante).trigger("change");
      $("#val_igv").val(e.data.val_igv);
      $("#descripcion").val(e.data.descripcion);
      $("#glosa").val(e.data.glosa).trigger("change");

      if (e.data.producto.length === 0) {         
        toastr.error(`<p class="h5">Sin productos.</p> Este registro no tiene productos para mostrar`);  
        $(".subtotal").html("S/ 0.00");
        $(".igv_comp").html("S/ 0.00");
        $(".total").html("S/ 0.00");
      } else { 

        e.data.producto.forEach((element, index) => {

          var img = "";

          if (element.imagen == "" || element.imagen == null) {
            img = "../dist/docs/material/img_perfil/producto-sin-foto.svg";
          } else {
            img = `../dist/docs/material/img_perfil/${element.imagen}`;
          }

          var fila = `
          <tr class="filas" id="fila${cont}">
            <td>
              <button type="button" class="btn btn-warning btn-sm" onclick="mostrar_material(${element.idproducto}, ${cont})" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>
              <button type="button" class="btn btn-danger btn-sm" onclick="eliminarDetalle(${cont})" data-toggle="tooltip" data-original-title="Eliminar"><i class="fas fa-times"></i></button></td>
            </td>
            <td>
              <input type="hidden" name="idproducto[]" value="${element.idproducto}">
              <input type="hidden" name="ficha_tecnica_producto[]" value="${element.ficha_tecnica_producto}">
              <div class="user-block text-nowrap">
                <img class="profile-user-img img-responsive img-circle cursor-pointer img_perfil_${cont}" src="${img}" alt="user image" onerror="this.src='../dist/svg/404-v2.svg';" onclick="ver_img_producto('${img}', '${encodeHtml(element.nombre_producto)}', ${cont})" data-toggle="tooltip" data-original-title="Ver imagen">
                <span class="username"><p class="mb-0 nombre_producto_${cont}" >${element.nombre_producto}</p></span>
                <span class="description color_${cont}"><b>Color: </b>${element.color}</span>
              </div>
            </td>
            <td> <span class="unidad_medida_${cont}">${element.unidad_medida}</span> <input class="unidad_medida_${cont}" type="hidden" name="unidad_medida[]" id="unidad_medida[]" value="${element.unidad_medida}"> <input class="color_${cont}" type="hidden" name="nombre_color[]" id="nombre_color[]" value="${element.color}"></td>
            <td class="form-group"><input class="producto_${element.idproducto} producto_selecionado w-100px cantidad_${cont} form-control" type="number" name="cantidad[]" id="cantidad[]" value="${element.cantidad}" min="0.01" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
            <td class="hidden"><input class="w-135px input-no-border precio_sin_igv_${cont}" type="number" name="precio_sin_igv[]" id="precio_sin_igv[]" value="${element.precio_sin_igv}" readonly ></td>
            <td class="hidden"><input class="w-135px input-no-border precio_igv_${cont}" type="number"  name="precio_igv[]" id="precio_igv[]" value="${element.igv}" readonly ></td>
            <td class="form-group"><input type="number" class="w-135px precio_con_igv_${cont} form-control" type="number"  name="precio_con_igv[]" id="precio_con_igv[]" value="${parseFloat(element.precio_con_igv).toFixed(2)}" min="0.01" required onkeyup="modificarSubtotales();" onchange="modificarSubtotales();"></td>
            <td><input type="number" class="w-135px descuento_${cont}" name="descuento[]" value="${element.descuento}" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
            <td class="text-right"><span class="text-right subtotal_producto_${cont}" name="subtotal_producto" id="subtotal_producto">0.00</span></td>
            <td><button type="button" onclick="modificarSubtotales()" class="btn btn-info btn-sm" data-toggle="tooltip" data-original-title="Actualizar precios"><i class="fas fa-sync"></i></button></td>
          </tr>`;

          detalles = detalles + 1;

          $("#detalles").append(fila);

          array_class_trabajador.push({ id_cont: cont });

          cont++;
          evaluar();
        });

        modificarSubtotales();  
        $('[data-toggle="tooltip"]').tooltip();            
      }
       
      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);            
    }    
  }).fail( function(e) { ver_errores(e); } );
}

//MOSTRAR - EDITAR LA COMPRA DE ACTIVOS PROYECTO
function mostrar_compra_proyecto(params) {
  editar_detalle
}

//DETALLE - COMRAS ACTIVOS FIJOS GENERAL
function ver_detalle_compras_activo_fijo(idcompra_af_general) {
  $(".tooltip").remove();
  $("#cargando-9-fomulario").hide();
  $("#cargando-10-fomulario").show();

  $("#print_pdf_compra").addClass('disabled');
  $("#excel_compra").addClass('disabled');

  $("#modal-ver-compras-general").modal("show"); 

  $.post("../ajax/compra_activos_fijos.php?op=ver_detalle_compras_activo_fijo&idcompra_af_general=" + idcompra_af_general, function (r) {

    $(".detalle_de_compra_general").html(r); //console.log(r);

    $('[data-toggle="tooltip"]').tooltip();
    $("#cargando-9-fomulario").show();
    $("#cargando-10-fomulario").hide();

    $("#print_pdf_compra").removeClass('disabled');
    $("#print_pdf_compra").attr('href', `../reportes/pdf_compra_activos_fijos.php?id=${idcompra_af_general}&op=activo_fijo` );
    $("#excel_compra").removeClass('disabled');
  }).fail( function(e) { ver_errores(e); } );
}

//DETALLE - COMRAS ACTIVOS FIJOS PROYECTO
function ver_detalle_compras_insumo(id_compra) {
  $(".tooltip").remove();
  $("#cargando-9-fomulario").hide();
  $("#cargando-10-fomulario").show();

  $("#print_pdf_compra").addClass('disabled');
  $("#excel_compra").addClass('disabled');

  $("#modal-ver-compras-general").modal("show");
  
  $.post("../ajax/compra_activos_fijos.php?op=ver_detalle_compras_insumo&id_compra=" + id_compra, function (r) {

    $(".detalle_de_compra_general").html(r); //console.log(r);

    $('[data-toggle="tooltip"]').tooltip();
    $("#cargando-9-fomulario").show();
    $("#cargando-10-fomulario").hide();

    $("#print_pdf_compra").removeClass('disabled');
    $("#print_pdf_compra").attr('href', `../reportes/pdf_compra_activos_fijos.php?id=${id_compra}&op=insumo`);
    $("#excel_compra").removeClass('disabled');
  }).fail( function(e) { ver_errores(e); } );
}


// :::::::::::::::::::::::::::::::::::::::::::::::::::: V E R   F A C T U R A S   Y   C O M P R O B A N T E S ::::::::::::::::::::::::::::::::::::::::::::::::::::

//MOSTRAMOS LOS COMPROBANTES DE LA COMPRA GENERAL
function comprobante_compra_activo_fijo(idcompra, doc) {
  $(".tooltip").remove();
  $("#modal-comprobante-compra").modal("show");
  $("#idcompra").val(idcompra);
  $("#ruta_guardar").val('guardar_y_editar_comprobante_activo_fijo');
  
  $("#doc1_nombre").html("");
  $("#doc_old_1").val("");  

  if (doc != "") {
    $("#doc_old_1").val(doc);
    // cargamos la imagen adecuada par el archivo
    $("#doc1_ver").html(doc_view_extencion(doc, 'compra_activo_fijo', 'comprobante_compra','100%' ));
    
    //ver_completo descargar comprobante subir
    $(".subir").removeClass("col-md-6").addClass("col-md-4");
    $(".recargar_activo_fijo").removeClass("col-md-6").addClass("col-md-4");

    $(".ver_completo").show();
    $(".ver_completo").removeClass("col-md-4").addClass("col-md-2");
    $("#ver_completo").attr("href", "../dist/docs/compra_activo_fijo/comprobante_compra/" + doc);

    $(".descargar").show();
    $(".descargar").removeClass("col-md-4").addClass("col-md-2");    
    $("#descargar_comprob").attr("href", "../dist/docs/compra_activo_fijo/comprobante_compra/" + doc);
  } else {
    $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
    $("#doc_old_1").val("");

    $(".ver_completo").hide();
    $(".descargar").hide();

    $(".subir").removeClass("col-md-4").addClass("col-md-6");
    $(".recargar_activo_fijo").removeClass("col-md-4").addClass("col-md-6");
  }
  $(".recargar_activo_fijo").show();
  $(".recargar_insumno").hide();
}

//MOSTRAMOS LOS COMPROBANTES DE LA COMPRA POR PROYECTO.
function comprobante_compra_insumo(idcompra, doc) {
  $(".tooltip").remove();
  $("#modal-comprobante-compra").modal("show");
  $("#idcompra").val(idcompra);
  $("#ruta_guardar").val('guardar_y_editar_comprobante_insumo');

  $("#doc1_nombre").html("");
  $("#doc_old_1").val("doc");
  
  if (doc != "") {
    $("#doc_old_1").val(doc);
    $("#doc1_ver").html(doc_view_extencion(doc, 'compra_insumo', 'comprobante_compra','100%' ));

    $(".subir").removeClass("col-md-6").addClass("col-md-4");
    $(".recargar_insumno").removeClass("col-md-6").addClass("col-md-4"); 

    $(".ver_completo").show();
    $(".ver_completo").removeClass("col-md-4").addClass("col-md-2");
    $("#ver_completo").attr("href", "../dist/docs/compra_insumo/comprobante_compra/" + doc);

    $(".descargar").show();
    $(".descargar").removeClass("col-md-4").addClass("col-md-2");
    $("#descargar").attr("href", "../dist/docs/compra_insumo/comprobante_compra/" + doc); 
    
  } else {
    $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
    $("#doc_old_1").val("");

    $(".ver_completo").hide();
    $(".descargar").hide(); 

    $(".subir").removeClass("col-md-4").addClass("col-md-6");
    $(".recargar_insumno").removeClass("col-md-4").addClass("col-md-6");
  }
  $(".recargar_activo_fijo").hide();
  $(".recargar_insumno").show();
}

function guardar_y_editar_comprobante(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-comprobante")[0]);
  var ruta_guardar = $("#ruta_guardar").val();

  $.ajax({
    url: `../ajax/compra_activos_fijos.php?op=${ruta_guardar}`,
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      e = JSON.parse(e); console.log(e);
      if (e.status) {
        Swal.fire("Correcto!", "Documento guardado correctamente", "success");
        tabla.ajax.reload(null, false);
        limpiar_form_compra();
        $("#modal-comprobante-compra").modal("hide");
      } else {
        ver_errores(e);
      }
    },
    xhr: function () {

      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener("progress", function (evt) {

        if (evt.lengthComputable) {

          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_comprobante").css({"width": percentComplete+'%'});

          $("#barra_progress_comprobante").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#barra_progress_comprobante").css({ width: "0%",  });
      $("#barra_progress_comprobante").text("0%");
    },
    complete: function () {
      $("#barra_progress_comprobante").css({ width: "0%", });
      $("#barra_progress_comprobante").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

// :::::::::::::::::::::::::::::::::::::::::::::::::::: 💰  S E C C I O N   P A G O  💵   C O M P R A S   D E   A C T I V O S ::::::::::::::::::::::::::::::::::::::::::::::::::::

function tbla_pagos_activo_fijo(idcompra_af_general, monto_total, total_deposito, nombre_proveedor) {

  most_datos_prov_pago(idcompra_af_general);
  localStorage.setItem("idcompra_pago_comp_nube", idcompra_af_general);

  localStorage.setItem("monto_total_p", monto_total);
  localStorage.setItem("monto_total_dep", total_deposito);

  $("#monto_total_general").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>');
  $("#total_compra").html(formato_miles(monto_total));
  $(".nombre-title-page").html('<i class="nav-icon fas fa-truck"></i> ' + nombre_proveedor);  

  table_show_hide(3)

  tabla_pagos1 = $("#tabla-pagos-proveedor").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,5,7,9,10,11,12,13,6,], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,5,7,9,10,11,12,13,6,], } }, { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,2,5,7,9,10,11,12,13,6,], } }, {extend: "colvis"} ,
    ],
    ajax: {
      url: `../ajax/compra_activos_fijos.php?op=tbla_pagos_activo_fijo&idcompra_af_general=${idcompra_af_general}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    createdRow: function (row, data, ixdex) {  
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: #
      if (data[1] != '') { $("td", row).eq(1).addClass("text-nowrap"); }
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
      { targets: [5], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD-MM-YYYY'), },
      { targets: [9,10,11,12,13], visible: false, searchable: false, },
    ],
  }).DataTable();

  total_pagos(idcompra_af_general);
}

//-total Pagos
function total_pagos(idcompra_af_general) {

  $.post("../ajax/compra_activos_fijos.php?op=suma_total_pagos", { idcompra_af_general: idcompra_af_general }, function (e, status) {

    e = JSON.parse(e);  //console.log(data);
    if (e.status) {
      $("#monto_total_general").html('S/ '+formato_miles(e.data.total_monto));
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );
}

//Función limpiar
function limpiar_form_pago_compra() {
  $("#forma_pago").val("").trigger("change");
  $("#tipo_pago").val("").trigger("change");
  $("#monto_pago").val("");
  $("#numero_op_pago").val("");
  $("#idpago_af_general").val("");
  $("#descripcion_pago").val("");
  $("#idpago_compra").val("");

  $("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");
  $("#foto1").val("");
  $("#foto1_actual").val("");
  $("#foto1_nombre").html("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function show_hide_forma_pago() {

  if ($('#forma_pago').select2('val') == 'Efectivo') {
    $("#tipo_pago").val("Proveedor").trigger("change");
    $("#banco_pago").val("1").trigger("change");
    $('.show_hide_tipo_pago').hide();
    $('.show_hide_cta_destino').hide();
    $('.show_hide_banco').hide();
    $('.show_hide_titular_cuenta').hide();
  } else {
    $('.show_hide_tipo_pago').show();
    $('.show_hide_cta_destino').show();
    $('.show_hide_banco').show();
    $('.show_hide_titular_cuenta').show();
  }
}

//mostrar datos proveedor pago
function most_datos_prov_pago(idcompra_af_general) {

  $("#h4_mostrar_beneficiario").html("");

  $("#banco_pago").val("").trigger("change");
  $.post("../ajax/compra_activos_fijos.php?op=most_datos_prov_pago", { idcompra_af_general: idcompra_af_general }, function (e, status) {
    e = JSON.parse(e); //console.log(data);

    if (e.status == true) {
      $("#idcompra_af_general_p").val(e.data.idcompra_af_general);
      $("#idproveedor_pago").val(e.data.idproveedor);
      $("#beneficiario_pago").val(e.data.razon_social);
      $("#h4_mostrar_beneficiario").html(e.data.razon_social);
      $("#banco_pago").val(e.data.idbancos).trigger("change");
      $("#tipo_pago").val('Proveedor').trigger("change");
      $("#titular_cuenta_pago").val(e.data.titular_cuenta);
      localStorage.setItem("nubecompra_c_b", e.data.cuenta_bancaria);
      localStorage.setItem("nubecompra_c_d", e.data.cuenta_detracciones);
      localStorage.setItem("nube_titular_cta", e.data.titular_cuenta);
      localStorage.setItem("nube_banco_pago", e.data.idbancos);

      if ($("#tipo_pago").select2("val") == "Proveedor") {$("#cuenta_destino_pago").val(e.data.cuenta_bancaria);}
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );
}

// datos_pago_proveedor
function datos_pago_proveedor() {
  var cuenta_bancaria = localStorage.getItem("nubecompra_c_b");
  var cuenta_detracciones = localStorage.getItem("nubecompra_c_d");
  var titular_cta = localStorage.getItem("nube_titular_cta");
  var banco_pago = localStorage.getItem("nube_banco_pago");

  $("#cuenta_destino_pago").val("");
  $("#titular_cuenta_pago").val(titular_cta);
  $("#banco_pago").val(banco_pago).trigger("change");

  if ($("#tipo_pago").select2("val") == "Proveedor") {    
    $("#cuenta_destino_pago").val(cuenta_bancaria);
  }else if ($("#tipo_pago").select2("val") == "Detraccion") {    
    $("#cuenta_destino_pago").val(cuenta_detracciones);
  }
}

//Guardar y editar PAGOS
function guardaryeditar_pago(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-servicios-pago")[0]);

  $.ajax({
    url: "../ajax/compra_activos_fijos.php?op=guardaryeditar_pago",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      e = JSON.parse(e);  console.log(e); 
      if (e.status == true) {
        toastr.success("servicio registrado correctamente");

        tabla.ajax.reload(null, false);        

        tabla_pagos1.ajax.reload(null, false);
        
        total_pagos(localStorage.getItem("idcompra_pago_comp_nube"));

        limpiar_form_pago_compra();

        $("#modal-agregar-pago").modal("hide");

      } else {
        ver_errores(e);
      }
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
      $("#barra_progress_pago_compra").css({ width: "0%",  });
      $("#barra_progress_pago_compra").text("0%");
    },
    complete: function () {
      $("#barra_progress_pago_compra").css({ width: "0%", });
      $("#barra_progress_pago_compra").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

//mostrar
function mostrar_pagos(idpago_af_general) {
  limpiar_form_pago_compra();
  $("#h4_mostrar_beneficiario").html("");
  $("#modal-agregar-pago").modal("show");
  $("#banco_pago").val("").trigger("change");
  $("#forma_pago").val("").trigger("change");
  $("#tipo_pago").val("").trigger("change");

  $.post("../ajax/compra_activos_fijos.php?op=mostrar_pagos", { idpago_af_general: idpago_af_general }, function (e, status) {
    e = JSON.parse(e);  console.log(e);

    if (e.status) {
      $("#idcompra_af_general_p").val(e.data.idcompra_af_general);
      $("#beneficiario_pago").val(e.data.beneficiario);
      $("#h4_mostrar_beneficiario").html(e.data.beneficiario);
      $("#cuenta_destino_pago").val(e.data.cuenta_destino);
      $("#banco_pago").val(e.data.idbancos).trigger("change");
      $("#titular_cuenta_pago").val(e.data.titular_cuenta);
      $("#forma_pago").val(e.data.forma_pago).trigger("change");
      $("#tipo_pago").val(e.data.tipo_pago).trigger("change");
      $("#fecha_pago").val(e.data.fecha_pago);
      $("#monto_pago").val(e.data.monto);
      $("#numero_op_pago").val(e.data.numero_operacion);
      $("#descripcion_pago").val(e.data.descripcion);
      $("#idpago_af_general").val(e.data.idpago_af_general);

      if (e.data.imagen != "") {
        $("#foto1_i").attr("src", "../dist/docs/compra_activo_fijo/comprobante_pago/" + e.data.imagen);
        $("#foto1_actual").val(e.data.imagen);
      }
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );
}

//Función para eliminar registros
function eliminar_pagos(idpago_af_general, nombre) {
  $(".tooltip").remove();

  crud_eliminar_papelera(
    "../ajax/compra_activos_fijos.php?op=desactivar_pagos",
    "../ajax/compra_activos_fijos.php?op=eliminar_pagos", 
    idpago_af_general, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function () { sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function () { sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function () { total_pagos(localStorage.getItem("idcompra_pago_comp_nube")); tabla_pagos1.ajax.reload(null, false); },
    function () { tabla.ajax.reload(null, false); }, 
    false, 
    false,
    false
  );

}

function ver_modal_vaucher(imagen, proveedor) {
  $("#img-vaucher").attr("src", "");
  $("#modal-ver-vaucher").modal("show");
  $("#img-vaucher").attr("src", "../dist/docs/compra_activo_fijo/comprobante_pago/" + imagen);
  $("#descargar_voucher_pago").attr("href", "../dist/docs/compra_activo_fijo/comprobante_pago/" + imagen);
  $("#descargar_voucher_pago").attr("download", `Voucher pago - ${quitar_punto(proveedor)}`);

  $("#ver_completo_voucher_pago").attr("href", "../dist/docs/compra_activo_fijo/comprobante_pago/" + imagen);

  $(".tooltip").remove();
}

// :::::::::::::::::::::::::::::::::::::::::::::::::::: S E C C I O N   P R O V E E D O R  ::::::::::::::::::::::::::::::::::::::::::::::::::::
//Función limpiar
function limpiar_form_proveedor() {
  $("#idproveedor_prov").val("");
  $("#tipo_documento_prov option[value='RUC']").attr("selected", true);
  $("#nombre_prov").val("");
  $("#num_documento_prov").val("");
  $("#direccion_prov").val("");
  $("#telefono_prov").val("");
  $("#c_bancaria_prov").val("");
  $("#cci_prov").val("");
  $("#c_detracciones_prov").val("");
  $("#banco_prov").val("").trigger("change");
  $("#titular_cuenta_prov").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();

  $(".tooltip").remove();
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

      if (e.status) {
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
        $(".chargue-format-1").html("Cuenta Bancaria");
        $(".chargue-format-2").html("CCI");
        $(".chargue-format-3").html("Cuenta Detracciones");
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

  crud_guardar_editar_modal_select2_xhr( 
    "../ajax/compra_activos_fijos.php?op=guardar_proveedor", 
    formData,
    '#barra_progress_proveeedor', 
    "../ajax/ajax_general.php?op=select2Proveedor", 
    '#idproveedor',
    function(){ limpiar_form_proveedor(); $("#modal-agregar-proveedor").modal("hide"); }, 
    function(){ sw_success('Correcto!', "Proveedor guardado correctamente." ); }, 
  );
  
}

function mostrar_para_editar_proveedor() {
  $("#cargando-7-fomulario").hide();
  $("#cargando-8-fomulario").show();

  $('#modal-agregar-proveedor').modal('show');
  $(".tooltip").remove();

  $.post("../ajax/compra_activos_fijos.php?op=mostrar_editar_proveedor", { 'idproveedor': $('#idproveedor').select2("val") }, function (e, status) {

    e = JSON.parse(e);  console.log(e);

    if (e.status == true) {     
      $("#idproveedor_prov").val(e.data.idproveedor);
      $("#tipo_documento_prov option[value='" + e.data.tipo_documento + "']").attr("selected", true);
      $("#nombre_prov").val(e.data.razon_social);
      $("#num_documento_prov").val(e.data.ruc);
      $("#direccion_prov").val(e.data.direccion);
      $("#telefono_prov").val(e.data.telefono);
      $("#banco_prov").val(e.data.idbancos).trigger("change");
      $("#c_bancaria_prov").val(e.data.cuenta_bancaria);
      $("#cci_prov").val(e.data.cci);
      $("#c_detracciones_prov").val(e.data.cuenta_detracciones);
      $("#titular_cuenta_prov").val(e.data.titular_cuenta);      

      $("#cargando-7-fomulario").show();
      $("#cargando-8-fomulario").hide();
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); });
}

// :::::::::::::::::::::::::::::::::::::::::::::::::::: S E C C I O N    I N S U M O S   Y   A C T I V O S :::::::::::::::::::::::::::::::::::::::::::::::::::: 
//Función limpiar
function limpiar_materiales() {
  $("#idproducto_p").val("");  
  $("#nombre_p").val("");
  $("#modelo_p").val("");
  $("#serie_p").val("");
  // $("#marca_p").val("");
  $("#marca_p").val("").trigger("change");
  $("#descripcion_p").val("");

  $("#precio_unitario_p").val("");
  $("#precio_sin_igv_p").val("");
  $("#precio_igv_p").val("");
  $("#precio_total_p").val("");

  $("#foto2_i").attr("src", "../dist/img/default/img_defecto_activo_fijo.png");
  $("#foto2").val("");
  $("#foto2_actual").val("");
  $("#foto2_nombre").html("");   

  $("#doc_old_2").val("");
  $("#doc2").val("");  
  $('#doc2_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc2_nombre').html("");

  $("#unidad_medida_p").val(4).trigger("change");
  $("#color_p").val(1).trigger("change");
  $("#categoria_insumos_af_p").val("").trigger("change");

  $("#my-switch_igv").prop("checked", true);
  $("#estado_igv_p").val("1");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función para guardar o editar
function guardar_y_editar_materiales(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-materiales")[0]);

  $.ajax({
    url: "../ajax/compra_activos_fijos.php?op=guardar_y_editar_materiales",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {        
        e = JSON.parse(e);  console.log(e);
        if (e.status == true ) {

          Swal.fire("Correcto!", "Producto creado correctamente", "success");

          tabla.ajax.reload(null, false);
          tablaactivos1.ajax.reload(null, false);
          //limpiar_materiales();
          
          actualizar_producto();

          $("#modal-agregar-material-activos-fijos").modal("hide");
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>'); }      
    },
    xhr: function () {

      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener("progress", function (evt) {

        if (evt.lengthComputable) {

          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_activo_fijo").css({"width": percentComplete+'%'});

          $("#barra_progress_activo_fijo").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#barra_progress_activo_fijo").css({ width: "0%",  });
      $("#barra_progress_activo_fijo").text("0%");
    },
    complete: function () {
      $("#barra_progress_activo_fijo").css({ width: "0%", });
      $("#barra_progress_activo_fijo").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

//Función ListarArticulos
function tblaActivosFijos() {
  tablaactivos1 = $("#tblaactivos").dataTable({
    //responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [],
    ajax: {
      url: "../ajax/ajax_general.php?op=tblaActivosFijos",
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "desc"]], //Ordenar (columna,orden)
  }).DataTable();
}

function mostrar_material(idproducto, cont) { 

  $("#cargando-9-fomulario").hide();
  $("#cargando-10-fomulario").show();
  
  limpiar_materiales();  

  $("#modal-agregar-material-activos-fijos").modal("show");

  $.post("../ajax/compra_activos_fijos.php?op=mostrar_activo_fijo", { 'idproducto': idproducto }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);

    if (e.status) {
      $("#idproducto_p").val(e.data.idproducto);
      $("#cont").val(cont);

      $("#nombre_p").val(e.data.nombre);
      $("#modelo_p").val(e.data.modelo);
      $("#serie_p").val(e.data.serie);
      $("#marca_p").val(e.data.marca).trigger("change");  
      $("#descripcion_p").val(e.data.descripcion);

      $('#precio_unitario_p').val(parseFloat(e.data.precio_unitario).toFixed(2));
      $("#estado_igv_p").val(parseFloat(e.data.estado_igv).toFixed(2));
      $("#precio_sin_igv_p").val(parseFloat(e.data.precio_sin_igv).toFixed(2));
      $("#precio_igv_p").val(parseFloat(e.data.precio_igv).toFixed(2));
      $("#precio_total_p").val(parseFloat(e.data.precio_total).toFixed(2));
      
      $("#unidad_medida_p").val(e.data.idunidad_medida).trigger("change");
      $("#color_p").val(e.data.idcolor).trigger("change");  
      $("#categoria_insumos_af_p").val(e.data.idcategoria_insumos_af).trigger("change");    

      if (e.data.estado_igv == "1") {
        $("#my-switch_igv").prop("checked", true);
      } else {
        $("#my-switch_igv").prop("checked", false);
      }
      
      if (e.data.imagen != "") {
        
        $("#foto2_i").attr("src", "../dist/docs/material/img_perfil/" + e.data.imagen);

        $("#foto2_actual").val(e.data.imagen);
      }

      // FICHA TECNICA
      if (e.data.ficha_tecnica == "" || e.data.ficha_tecnica == null  ) {

        $("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

        $("#doc2_nombre").html('');

        $("#doc_old_2").val(""); $("#doc2").val("");

      } else {

        $("#doc_old_2").val(e.data.ficha_tecnica); 

        $("#doc2_nombre").html(`<div class="row"> <div class="col-md-12"><i>Ficha-tecnica.${extrae_extencion(e.data.ficha_tecnica)}</i></div></div>`);
        
        $("#doc2_ver").html(doc_view_extencion(e.data.ficha_tecnica, 'material', 'ficha_tecnica', '100%'));
              
      }

      $("#cargando-9-fomulario").show();
      $("#cargando-10-fomulario").hide();
    } else {
      ver_errores(e);
    }     
  }).fail( function(e) { ver_errores(e); } );
}

function precio_con_igv() {
  var precio_ingresado = $("#precio_unitario_p").val()=='' ? 0 : parseFloat($("#precio_unitario_p").val());

  var input_precio_con_igv = 0;
  var igv = 0;
  var input_precio_sin_igv = 0;

  if ($("#my-switch_igv").is(":checked")) {
    input_precio_sin_igv = precio_ingresado / 1.18;
    igv = precio_ingresado - input_precio_sin_igv;
    input_precio_con_igv = precio_ingresado;
    
    $("#precio_sin_igv_p").val(input_precio_sin_igv.toFixed(2));
    $("#precio_igv_p").val(igv.toFixed(2));    
    $("#precio_total_p").val(input_precio_con_igv.toFixed(2));

    $("#estado_igv_p").val("1");
  } else {
    input_precio_con_igv = precio_ingresado * 1.18;
    igv = input_precio_con_igv - precio_ingresado;
    input_precio_sin_igv = parseFloat(precio_ingresado);

    $("#precio_sin_igv_p").val( input_precio_sin_igv.toFixed(2));
    $("#precio_igv_p").val(igv.toFixed(2));    
    $("#precio_total_p").val(input_precio_con_igv.toFixed(2));

    $("#estado_igv_p").val("0");
  }
}

$("#my-switch_igv").on("click ", function (e) {

  var precio_ingresado = $("#precio_unitario_p").val()=='' ? 0 : parseFloat($("#precio_unitario_p").val());

  var input_precio_con_igv = 0;
  var igv = 0;
  var input_precio_sin_igv = 0;

  if ($("#my-switch_igv").is(":checked")) {
    input_precio_sin_igv = precio_ingresado / 1.18;
    igv = precio_ingresado - input_precio_sin_igv;
    input_precio_con_igv = precio_ingresado;  

    $("#precio_sin_igv_p").val(redondearExp(input_precio_sin_igv, 2));
    $("#precio_igv_p").val(redondearExp(igv, 2));   
    $("#precio_total_p").val(redondearExp(input_precio_con_igv, 2)) ;

    $("#estado_igv_p").val("1");
  } else {
    input_precio_con_igv = precio_ingresado * 1.18;     
    igv = input_precio_con_igv - precio_ingresado;
    input_precio_sin_igv = parseFloat(precio_ingresado);  

    $("#precio_sin_igv_p").val(redondearExp(input_precio_sin_igv, 2));
    $("#precio_igv_p").val(redondearExp(igv, 2));
    $("#precio_total_p").val(redondearExp(input_precio_con_igv, 2) );

    $("#estado_igv_p").val("0");
  }
});

function actualizar_producto() {

  var idproducto = $("#idproducto_p").val(); 
  var cont = $("#cont").val(); 

  var nombre_p = $("#nombre_p").val();  
  var precio_total_p = $("#precio_total_p").val();
  var unidad_medida_p = $("#unidad_medida_p").find(':selected').text();
  var color_p = $("#color_p").find(':selected').text();  

  if (idproducto == "" || idproducto == null) {   
  } else {
    $(`.nombre_producto_${cont}`).html(nombre_p); 
    $(`.color_${cont}`).html(`<b>Color: </b>${color_p}`);
    $(`.color_${cont}`).val(color_p); 
    $(`.unidad_medida_${cont}`).html(unidad_medida_p); 
    $(`.unidad_medida_${cont}`).val(unidad_medida_p);
    $(`.precio_con_igv_${cont}`).val(precio_total_p);    
     
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
  $("#idproveedor").on('change', function() { $(this).trigger('blur'); });
  $("#glosa").on('change', function() { $(this).trigger('blur'); });
  $("#banco_pago").on('change', function() { $(this).trigger('blur'); });
  $("#tipo_comprobante").on('change', function() { $(this).trigger('blur'); });
  $("#forma_pago").on('change', function() { $(this).trigger('blur'); });
  $("#tipo_pago").on('change', function() { $(this).trigger('blur'); });
  $("#banco_prov").on('change', function() { $(this).trigger('blur'); });
  $("#categoria_insumos_af_p").on('change', function() { $(this).trigger('blur'); });
  $("#color_p").on('change', function() { $(this).trigger('blur'); });
  $("#unidad_medida_p").on('change', function() { $(this).trigger('blur'); });
  $('#marca_p').on('change', function() { $(this).trigger('blur'); });

  $("#form-compra-activos-f").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      idproveedor:      { required: true },
      tipo_comprobante: { required: true },
      serie_comprobante:{ minlength: 2 },
      descripcion:      { minlength: 4 },
      fecha_compra:     { required: true },
      glosa:            { required: true },
      val_igv:          { required: true, number: true, min:0, max:1 },
    },
    messages: {
      idproveedor:      { required: "Campo requerido", },
      tipo_comprobante: { required: "Campo requerido", },
      serie_comprobante:{ minlength: "mayor a 2 caracteres", },
      descripcion:      { minlength: "mayor a 4 caracteres", },
      fecha_compra:     { required: "Campo requerido", },
      glosa:            { required: "Campo requerido", },
      val_igv:          { required: "Campo requerido", number: 'Ingrese un número', min:'Mínimo 0', max:'Maximo 1' },
      'cantidad[]':     { min: "Mínimo 0.01", required: "Campo requerido"},
      'precio_con_igv[]':{ min: "Mínimo 0.01", required: "Campo requerido"}
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
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      guardaryeditar_compras(form);
    },
  });

  $("#form-proveedor").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      tipo_documento_prov:{ required: true },
      num_documento_prov: { required: true, minlength: 6, maxlength: 20 },
      nombre_prov:        { required: true, minlength: 3, maxlength: 100 },
      direccion_prov:     { minlength: 5, maxlength: 150 },
      telefono_prov:      { minlength: 8 },
      c_bancaria_prov:    { minlength: 6, },
      cci_prov:           { minlength: 6, },
      c_detracciones_prov:{ minlength: 6, },      
      banco_prov:         { required: true },
      titular_cuenta_prov:{ minlength: 4 },
    },
    messages: {
      tipo_documento_prov:{ required: "Campo requerido.", },
      num_documento_prov: { required: "Campo requerido.", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 20 caracteres.", },
      nombre_prov:        { required: "Campo requerido.", minlength: "MÍNIMO 3 caracteres.", maxlength: "MÁXIMO 100 caracteres.", },
      direccion_prov:     { minlength: "MÍNIMO 5 caracteres.", maxlength: "MÁXIMO 150 caracteres.", },
      telefono_prov:      { minlength: "MÍNIMO 9 caracteres.", },
      c_bancaria_prov:    { minlength: "MÍNIMO 6 caracteres.", },
      cci_prov:           { minlength: "MÍNIMO 6 caracteres.",  },
      c_detracciones_prov:{ minlength: "MÍNIMO 6 caracteres.", },      
      banco_prov:         { required: "Campo requerido.",  },
      titular_cuenta_prov:{ minlength: 'MÍNIMO 4 caracteres.' },
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
      guardar_proveedor(e);
    },
  });

  $("#form-servicios-pago").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      forma_pago:       { required: true },
      tipo_pago:        { required: true },
      banco_pago:       { required: true },
      fecha_pago:       { required: true },
      monto_pago:       { required: true },
      numero_op_pago:   { minlength: 3 },
      descripcion_pago: { minlength: 3 },
      titular_cuenta_pago: { minlength: 3 },
    },
    messages: {
      forma_pago:       { required: "Campo requerido.", },
      tipo_pago:        {  required: "Campo requerido.", },
      banco_pago:       { required: "Campo requerido.", },
      fecha_pago:       { required: "Campo requerido.", },
      monto_pago:       { required: "Campo requerido.", },
      numero_op_pago:   { minlength: 'MÍNIMO 3 caracteres' },
      descripcion_pago: { minlength: 'MÍNIMO 3 caracteres' },
      titular_cuenta_pago: { minlength: 'MÍNIMO 3 caracteres' },
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
      guardaryeditar_pago(e);
    },
  });

  $("#form-comprobante").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      nombre: { required: true },
    },

    messages: {
      nombre: {   required: "Campo requerido", },
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
      guardar_y_editar_comprobante(e);
    },
  });

  $("#form-materiales").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      categoria_insumos_af_p: { required: true },
      nombre_p:         { required: true, minlength:3, maxlength:200},      
      color_p:          { required: true },
      marca_p:            { required: true },
      unidad_medida_p:  { required: true },
      precio_unitario_p:{ required: true },
      descripcion_p:    { minlength: 3 },
    },
    messages: {
      categoria_insumos_af_p: { required: "Campo requerido.", },
      nombre_p:         { required: "Campo requerido.", minlength:"Minimo 3 caracteres", maxlength:"Maximo 200 caracteres" },      
      color_p:          { required: "Campo requerido." },
      marca_p:          { required: "Campo requerido" },
      unidad_medida_p:    { required: "Campo requerido." },
      precio_unitario_p:{ required: "Campo requerido.", },      
      descripcion_p:    { minlength: "Minimo 3 caracteres" },
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
      guardar_y_editar_materiales(e);
    },
  });

  $("#idproveedor").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#glosa").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#banco_pago").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#tipo_comprobante").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#forma_pago").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#tipo_pago").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#banco_prov").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#categoria_insumos_af_p").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#color_p").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#unidad_medida_p").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $('#marca_p').rules('add', { required: true, messages: {  required: "Campo requerido" } });

});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..


//validando excedentes
function validando_excedentes() {

  if ($("#monto_pago").val()!="") {

    var totattotal = localStorage.getItem("monto_total_p");
    var monto_total_dep = localStorage.getItem("monto_total_dep");
    var monto_entrada = $("#monto_pago").val();

    var total_suma = parseFloat(monto_total_dep) + parseFloat(monto_entrada);
    var debe = totattotal - monto_total_dep;
  
    if (total_suma > totattotal) {
      toastr.error("ERROR monto excedido al total del monto a pagar!");
    } else {
      toastr.success("Monto Aceptado.");
    }

  }else{

    var totattotal = localStorage.getItem("monto_total_p_af_p");
    var monto_total_dep = localStorage.getItem("monto_total_dep_p_af_p");
    var monto_entrada = $("#monto_pago_af_p").val();

    var total_suma = parseFloat(monto_total_dep) + parseFloat(monto_entrada);
    var debe = totattotal - monto_total_dep;
  
    if (total_suma > totattotal) {
      toastr.error("ERROR monto excedido al total del monto a pagar!");
    } else {
      toastr.success("Monto Aceptado.");
    }


  }

}

// ver imagen grande del producto agregado a la compra
function ver_img_producto(img_url, nombre, cont=null) {
  $(".tooltip").remove();
  if (cont == null || cont == "") {
    $("#ver_img_material").attr("src", img_url);
    $(".nombre-img-material").html(nombre);
    $("#modal-ver-img-material").modal("show");    
  } else {
    var img_peril = $(`.img_perfil_${cont}`).attr("src");

    $("#ver_img_material").attr("src", `${img_peril}`);
    $(".nombre-img-material").html(nombre);
    $("#modal-ver-img-material").modal("show");
  }  
}

function fecha_actual() {
  //Obtenemos la fecha actual
  var now = new Date();
  var day = ("0" + now.getDate()).slice(-2);
  var month = ("0" + (now.getMonth() + 1)).slice(-2);
  var today = now.getFullYear() + "-" + month + "-" + day;
  console.log(today);
  $("#fecha_compra").val(today);
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

function extrae_ruc() {
  if ($('#idproveedor').select2("val") == null || $('#idproveedor').select2("val") == '') { 
    $('.btn-editar-proveedor').addClass('disabled').attr('data-original-title','Seleciona un proveedor');
  } else { 
    if ($('#idproveedor').select2("val") == 1) {
      $('.btn-editar-proveedor').addClass('disabled').attr('data-original-title','No editable');
      var ruc = $('#idproveedor').select2('data')[0].element.attributes.ruc.value; //console.log(ruc);
      $('#ruc_proveedor').val(ruc);
    } else{
      var name_proveedor = $('#idproveedor').select2('data')[0].text;
      $('.btn-editar-proveedor').removeClass('disabled').attr('data-original-title',`Editar: ${recorte_text(name_proveedor, 15)}`);   
      var ruc = $('#idproveedor').select2('data')[0].element.attributes.ruc.value; //console.log(ruc);
      $('#ruc_proveedor').val(ruc);
    }
  }
  $('[data-toggle="tooltip"]').tooltip();
}