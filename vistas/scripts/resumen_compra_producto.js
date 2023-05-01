var tabla_principal;
var tabla_factura;
var tabla_materiales;
var tabla_comprobantes;

var array_class_trabajador = [];
var cont = 0;
var detalles = 0;

var idproyecto_r = "", idproducto_r = "", nombre_producto_r = "", precio_promedio_r = "", subtotal_x_producto_r = "";

//Función que se ejecuta al inicio
function init(){

  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Compras").addClass("menu-open bg-color-191f24");

  $("#mLogisticaAdquisiciones").addClass("active");

	$("#mCompra").addClass("active bg-color-0037a4 text-white");

	$("#lResumenProducto").addClass("active");
	
	tbla_principal(localStorage.getItem('nube_id_sucursal'));	
  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  // lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#idproveedor', null);
  // lista_select2("../ajax/ajax_general.php?op=select2Banco", '#banco_prov', null);
  // lista_select2("../ajax/ajax_general.php?op=select2Color", '#color_p', null);
  // lista_select2("../ajax/ajax_general.php?op=select2UnidaMedida", '#unidad_medida_p', null);
  // lista_select2("../ajax/ajax_general.php?op=select2Categoria_all", '#categoria_insumos_af_p', null);
  // lista_select2("../ajax/ajax_general.php?op=select2TierraConcreto", '#idtipo_tierra_concreto', null);


  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  // $("#guardar_registro_compras").on("click", function (e) {  $("#submit-form-compras").submit(); });

  // ═══════════════════ SELECT2 - COMPRAS ═══════════════════
  // $("#idproveedor").select2({ theme: "bootstrap4", placeholder: "Selecione trabajador", allowClear: true, });


  // Formato para telefono
  $("[data-mask]").inputmask();
}

// OCULTAR MOSTRAR - TABLAS
function table_show_hide(flag) {
  if (flag == 1) {
    $(".mensaje-tbla-principal").show();
    $("#btn-regresar").hide();
    $("#btn-regresar-todo").hide();
    $("#btn-regresar-bloque").hide();
    $("#guardar_registro_compras").hide();    

    $(".nombre-insumo").html(`<img src="../dist/svg/negro-palana-ico.svg" class="nav-icon" alt="" style="width: 21px !important;"> Almacén - Resumen de Producto`);

    $("#tabla-principal").show();
    $("#tabla-factura").hide();
    $("#tabla-editar-factura").hide();
  } else if (flag == 2) {
    
    $(".mensaje-tbla-principal").hide();
    $("#btn-regresar").show();
    $("#btn-regresar-todo").hide();
    $("#btn-regresar-bloque").hide();
    $("#guardar_registro_compras").hide();

    $("#tabla-principal").hide();
    $("#tabla-factura").show();
    $("#tabla-editar-factura").hide();
  }else if (flag == 3) {
      
    $(".mensaje-tbla-principal").hide();
    $("#btn-regresar").hide();
    $("#btn-regresar-todo").show();
    $("#btn-regresar-bloque").show();   
    $("#guardar_registro_compras").hide();      

    $("#tabla-principal").hide();
    $("#tabla-factura").hide();
    $("#tabla-editar-factura").show();      
    
  }
}

// TABLA - PRINCIPAL
function tbla_principal(nube_idsucursal) {
	tabla_principal=$('#tbla-resumen-insumos').dataTable({
		responsive: true,
		lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
		aProcessing: true,//Activamos el procesamiento del datatables
	  aServerSide: true,//Paginación y filtrado realizados por el servidor
	  dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",//Definimos los elementos del control de tabla
	  buttons: [ 
      { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i>', className: "btn bg-gradient-info", action: function ( e, dt, node, config ) { tabla_principal.ajax.reload(null, false); toastr_success('Exito!!', 'Actualizando tabla', 400); } },
      { extend: 'copyHtml5', exportOptions: { columns: [0,2,11,12,4,5,6,8,9,10], }, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray", footer: true,  }, 
      { extend: 'excelHtml5', exportOptions: { columns: [0,2,11,12,4,5,6,8,9,10], }, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success", footer: true,  }, 
      { extend: 'pdfHtml5', exportOptions: { columns: [0,2,11,12,4,5,6,8,9,10], }, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger", footer: false, orientation: 'landscape', pageSize: 'LEGAL',  },
      { extend: "colvis", text: `Columnas`, className: "btn bg-gradient-gray", exportOptions: { columns: "th:not(:last-child)", }, },
    ],
		ajax:	{
      url: `../ajax/resumen_compra_producto.php?op=tbla_principal&nube_idsucursal=${nube_idsucursal}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	 ver_errores(e);
      }
		},
    createdRow: function (row, data, ixdex) {  
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: op
      if (data[1] != '') { $("td", row).eq(1).addClass("text-nowrap"); }
      // columna: UM
      if (data[4] != '') { $("td", row).eq(4).addClass("text-center"); }
      // columna: stock
      if (data[5] != '') { $("td", row).eq(5).addClass("text-center"); }
      //columna:cantidad
      if (data[6] != '') { $("td", row).eq(6).addClass("text-center"); }
      // columna: Compra
      if (data[7] != '') { $("td", row).eq(7).addClass("text-center");  }
      // columna: Precio promedio
      if (data[8] != '') { $("td", row).eq(8).addClass("text-right"); }
      // columna: Descuento
      if (data[9] != '') { $("td", row).eq(9).addClass("text-right");  }
      // columna: Suma Total
      if (data[10] != '') { $("td", row).eq(10).addClass("text-right");  }

    },
		language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    footerCallback: function( tfoot, data, start, end, display ) {
      var api1 = this.api(); var total1 = api1.column( 9 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api1.column( 9 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(total1)}</span>` );

      var api2 = this.api(); var total2 = api2.column( 10 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api2.column( 10 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(total2)}</span>` );
    },
		bDestroy: true,
		iDisplayLength: 10,//Paginación
	  //order: [[ 0, "desc" ]]//Ordenar (columna,orden)
    columnDefs:[ 
      { "targets": [ 11,12 ], "visible": false, "searchable": false }, 
      { targets: [6], render: $.fn.dataTable.render.number(',', '.', 2) },
      { targets: [8,9,10], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
    ]
	}).DataTable();
  
}

// :::::::::::::::::::::::::::::::::::::::::::::::::::: SECCION COMPRAS ::::::::::::::::::::::::::::::::::::::::::::::::::::

// TABLA - FACTURAS
function tbla_facuras( idproducto, nombre_producto ) {

  idproducto_r = idproducto; nombre_producto_r = nombre_producto;

  $(".cantidad_x_producto").html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  $('.precio_promedio').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  $(".descuento_x_producto").html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  $('.subtotal_x_producto').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');

  $(".nombre-insumo").html(`Producto: <b>${nombre_producto}</b>`);

  table_show_hide(2);	

	tabla_factura = $('#tbla-facura').dataTable({
		responsive: true,
		lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
		aProcessing: true,//Activamos el procesamiento del datatables
		aServerSide: true,//Paginación y filtrado realizados por el servidor
		dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",//Definimos los elementos del control de tabla
		buttons: [ 
      { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i>', className: "btn bg-gradient-info", action: function ( e, dt, node, config ) { tabla_factura.ajax.reload(null, false); toastr_success('Exito!!', 'Actualizando tabla', 400); } },
      { extend: 'copyHtml5', exportOptions: { columns: [0,2,10,11,4,5,6,8], }, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray", footer: true,  }, 
      { extend: 'excelHtml5', exportOptions: { columns: [0,2,10,11,4,5,6,8], }, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success", footer: true,  }, 
      { extend: 'pdfHtml5', exportOptions: { columns: [0,2,10,11,4,5,6,8], }, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger", footer: false, orientation: 'landscape', pageSize: 'LEGAL',  },
      { extend: "colvis", text: `Columnas`, className: "btn bg-gradient-gray", exportOptions: { columns: "th:not(:last-child)", }, },

    ],
		ajax:	{
      url: `../ajax/resumen_compra_producto.php?op=tbla_facturas&idproducto=${idproducto}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: Cantidad
      if (data[5] != '') { $("td", row).eq(5).addClass("text-center"); }
      // columna: Precio promedio
      if (data[6] != '') { $("td", row).eq(6).addClass("text-right h5"); }
      // columna: Precio actual
      if (data[7] != '') { $("td", row).eq(7).addClass("text-right"); }    
      if (data[8] != '') { $("td", row).eq(8).addClass("text-right"); }
    },
		language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    footerCallback: function( tfoot, data, start, end, display ) {
      
      var api0 = this.api(); var total0 = api0.column( 5 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api0.column( 5 ).footer() ).html( `${formato_miles(total0)}` );
      var api1 = this.api(); var total1 = api1.column( 6 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api1.column( 6 ).footer() ).html( `<span class="float-left">S/</span> <span class="float-right">${formato_miles(total1/end)}</span>` );
      var api2 = this.api(); var total2 = api2.column( 7 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api2.column( 7 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(total2)}</span>` );
      var api3 = this.api(); var total3 = api3.column( 8 ).data().reduce( function ( a, b ) { return (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api3.column( 8 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(total3)}</span>` );
      
    },
		bDestroy: true,
		iDisplayLength: 10,//Paginación
		order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [      
      { targets: [4], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      { targets: [6,7,8], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
      { targets: [9,10], visible: false, searchable: false, },
    ],
	}).DataTable();  

}


//mostramos el detalle del comprobante de la compras
function ver_detalle_compras(idcompra_producto, id_producto) {

  $("#cargando-5-fomulario").hide();
  $("#cargando-6-fomulario").show();

  $("#print_pdf_compra").addClass('disabled');
  $("#excel_compra").addClass('disabled');

  $("#modal-ver-compras").modal("show");

  $.post(`../ajax/ajax_general.php?op=ver_detalle_compras&id_compra=${idcompra_producto}&id_producto=${id_producto}`, function (e, status) {
    e = JSON.parse(e); console.log(e);
    $(".detalle_de_compra").html(e.data); 
    $("#cargando-5-fomulario").show();
    $("#cargando-6-fomulario").hide();

    $("#print_pdf_compra").removeClass('disabled');    
    $("#excel_compra").removeClass('disabled').attr('href', `../reportes/export_xlsx_compra_producto.php?id=${idcompra_producto}`);
    $("#print_pdf_compra").attr('href', `../reportes/pdf_compra_productos.php?id=${idcompra_producto}` );
  }).fail( function(e) { ver_errores(e); } ); 
}

// :::::::::::::::::::::::::::::::::::::::::::::::::::: SECCION AGREGAR PRODUCTO ::::::::::::::::::::::::::::::::::::::::::::::::::::

// DETALLE DEL MATERIAL
function mostrar_detalle_material(idproducto) {  

  $(".tooltip").remove("show");

  $('#datosproductos').html(`<div class="row"><div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br /><br /><h4>Cargando...</h4></div></div>`);

  var verdatos=''; var imagenver='';

  $("#modal-ver-detalle-material-activo-fijo").modal("show")

  $.post("../ajax/resumen_compra_producto.php?op=mostrar_materiales", { 'idproducto': idproducto }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 
    if (e.status) {     
    
      if (e.data.imagen != '') {

        imagen_perfil=`<img src="../dist/docs/producto/img_perfil/${e.data.imagen}" onerror="this.src='../dist/svg/404-v2.svg';" alt="" class="img-thumbnail w-150px">`
        
        btn_imagen_perfil=`
        <div class="row mt-1">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/producto/img_perfil/${e.data.imagen}" data-toggle="tooltip" data-original-title="Ver imagen"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/producto/img_perfil/${e.data.imagen}" download="PERFIL - ${removeCaracterEspecial(e.data.nombre)}" data-toggle="tooltip" data-original-title="Descargar imagen"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      
      } else {

        imagen_perfil=`<img src="../dist/docs/producto/img_perfil/producto-sin-foto.svg" onerror="this.src='../dist/svg/404-v2.svg';" alt="" class="img-thumbnail w-150px">`;
        btn_imagen_perfil='';

      }      

      var retorno_html=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th rowspan="2">${imagen_perfil}<br>${btn_imagen_perfil}</th>
                  <td> <b>Nombre: </b> ${e.data.nombre}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <td> <b>Categoria: </b> ${e.data.categoria}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>U.M.</th>
                  <td>${e.data.nombre_medida}</td>
                </tr>                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>U.M.</th>
                  <td>${e.data.nombre_medida}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Precio  </th>
                  <td>${e.data.precio_unitario}</td>
                </tr> 
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Stock</th>
                    <td>${e.data.stock}</td>
                </tr> 
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Contenido Neto: </th>
                  <td>${e.data.contenido_neto}</td>
                </tr>   
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Descripción</th>
                  <td>${e.data.descripcion}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;
    
      $("#datosproductos").html(retorno_html);
      $('[data-toggle="tooltip"]').tooltip();
      $(`.jq_image_zoom`).zoom({ on:'grab' });
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } ); 

}

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {

  // Aplicando la validacion del select cada vez que cambie
  $("#idproveedor").on('change', function() { $(this).trigger('blur'); });


  $("#form-compras").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      idproveedor:    { required: true },
      tipo_comprobante:{ required: true },
      serie_comprobante:{ minlength: 2 },
      descripcion:    { minlength: 4 },
      fecha_compra:   { required: true },
      glosa:          { required: true },
      val_igv:        { required: true, number: true, min:0, max:1 },
    },
    messages: {
      idproveedor:    { required: "Campo requerido", },
      tipo_comprobante:{ required: "Campo requerido", },
      serie_comprobante:{ minlength: "mayor a 2 caracteres", },
      descripcion:    { minlength: "mayor a 4 caracteres", },
      fecha_compra:   { required: "Campo requerido", },
      glosa:          { required: "Campo requerido", },
      val_igv:        { required: "Campo requerido", number: 'Ingrese un número', min:'Mínimo 0', max:'Maximo 1' },
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
      guardar_y_editar_compras(form);
    },
  });

  // Aplicando la validacion del select cada vez que cambie
  $("#idproveedor").rules('add', { required: true, messages: {  required: "Campo requerido" } });

});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

// ver imagen grande del producto agregado a la compra
function ver_img_producto(file, nombre) {
  $('.foto-insumo').html(nombre);
  $(".tooltip").removeClass("show").addClass("hidde");
  $("#modal-ver-perfil-insumo").modal("show");
  $('#perfil-insumo').html(`<span class="jq_image_zoom"><img class="img-thumbnail" src="${file}" onerror="this.src='../dist/svg/404-v2.svg';" alt="Perfil" width="100%"></span>`);
  $('.jq_image_zoom').zoom({ on:'grab' });
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

init();