var tabla_principal;
var tabla_visto_bueno;

var nube_idproyecto_r = '', empresa_a_cargo_r ='', fecha_1_r="", fecha_2_r="", id_proveedor_r="", comprobante_r="", modulo_r = 'todos';

var zip = new JSZip();

//Función que se ejecuta al inicio
function init() {

  $("#lResumenFacura").addClass("active bg-green");
  
  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════ 
  $.get("../ajax/resumen_facturas.php?op=select2Proveedor", function (r) { $("#proveedor_filtro").html(r); $(".cargando_proveedor").html('Proveedor'); });
  lista_select2("../ajax/ajax_general.php?op=select2EmpresaACargo", '#filtro_empresa_a_cargo', null);

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════ 
  $("#filtro_empresa_a_cargo").select2({ templateResult: template_sleect2_empresa, theme: "bootstrap4", placeholder: "Selecione empresa", allowClear: true, });
  $("#proveedor_filtro").select2({ theme: "bootstrap4", placeholder: "Selecionar proveedor", allowClear: true, });
  $("#tipo_comprobante_filtro").select2({ theme: "bootstrap4", placeholder: "Selecionar comprobante", allowClear: true, });
  
  // Formato para telefono
  $("[data-mask]").inputmask();  
  
  //filtros();
} 

function template_sleect2_empresa (state) {
  //console.log(state);
  if (!state.id) { return state.text; }
  var baseUrl = state.title != '' ? `../dist/svg/${state.title}`: '../dist/svg/user_default.svg'; 
  var onerror = `onerror="this.src='../dist/svg/user_default.svg';"`;
  var $state = $(`<span><img src="${baseUrl}" class="img-circle mr-2 w-25px" ${onerror} />${state.text}</span>`);
  return $state;
};

//Función Listar - tabla compras
function tbla_principal(nube_idproyecto, empresa_a_cargo, fecha_1, fecha_2, id_proveedor, comprobante, modulo = 'todos') {

  $('.btn-zip').addClass('disabled');

  nube_idproyecto_r = nube_idproyecto; empresa_a_cargo_r =empresa_a_cargo; fecha_1_r=fecha_1; fecha_2_r=fecha_2; id_proveedor_r=id_proveedor; comprobante_r=comprobante; modulo_r = modulo;

  $('.total-subtotal').html('0.00');
  $('.total-igv').html('0.00');
  $('.total-total').html('0.00');

  var total_subtotal = 0, total_igv = 0, total = 0;

  var name_export = `Resumen de facturas Sevens ${moment().format('DD-MM-YYYY')}`;

  tabla_principal = $('#tabla-principal').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,3,4,5,6,7,8,9,10,11,12], } }, 
      { extend: 'excelHtml5', title: name_export, footer: true, exportOptions: { columns: [0,3,4,5,6,7,8,9,10,11,12], } ,
        action: function(e, dt, node, config) { var that = this; Swal.fire('Descargando EXCEL<span class="texto-parpadeante"> ...</span> '); setTimeout(function() { var descargando = $.fn.DataTable.ext.buttons.excelHtml5.action.call(that, e, dt, node, config); $(descargando).ready(function () { Swal.close(); });  }, 1000); }
      }, 
      { extend: 'pdfHtml5', title: name_export, footer: true, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,3,4,5,6,7,8,9,10,11,12], } ,
        action: function(e, dt, node, config) { var that = this; Swal.fire('Descargando PDF<span class="texto-parpadeante"> ...</span> '); setTimeout(function() { var descargando = $.fn.DataTable.ext.buttons.pdfHtml5.action.call(that, e, dt, node, config); $(descargando).ready(function () { Swal.close(); }); }, 1000); }
      }, 
      "colvis"
    ],
    ajax:	{
      url: `../ajax/resumen_facturas.php?op=listar_facturas_compras&id_proyecto=${nube_idproyecto}&empresa_a_cargo=${empresa_a_cargo}&fecha_1=${fecha_1}&fecha_2=${fecha_2}&id_proveedor=${id_proveedor}&comprobante=${comprobante}&visto_bueno='0'&modulo=${modulo}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);	
      }
		},
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[3] != '') { $("td", row).eq(3).addClass('text-center text-nowrap'); }   
      // columna: sub total
      if (data[8] != '') { $("td", row).eq(8).addClass('text-right'); $(".total-subtotal").html(formato_miles( total_subtotal += parseFloat(data[8]) )); }
      // columna: igv
      if (data[9] != '') { $("td", row).eq(9).addClass('text-right'); $(".total-igv").html(formato_miles( total_igv += parseFloat(data[9]) )); }  
      // columna: total
      if (data[10] != '') { $("td", row).eq(10).addClass('text-right'); $(".total-total").html(formato_miles( total += parseFloat(data[10]) )); }      
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
      { targets: [3], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD-MM-YYYY'), },
      { targets: [8,9,10], render: $.fn.dataTable.render.number(',', '.', 2) },
      //{ targets: [11], visible: false, searchable: false, }, 
    ]
  }).DataTable();
  
  $( tabla_principal ).ready(function() {
    //sumas_totales(nube_idproyecto, fecha_1, fecha_2, id_proveedor, comprobante, modulo);
    $('.cargando').hide();
    $('.btn-zip').removeClass('disabled');
    var elementsArray = document.getElementById("reload-all");
    elementsArray.style.display = 'none';
  });
  
}

function sumas_totales(nube_idproyecto, fecha_1, fecha_2, id_proveedor, comprobante, modulo = 'todos') {
  $('.btn-zip').addClass('disabled');
  $.post("../ajax/resumen_facturas.php?op=suma_totales", { 'id_proyecto': nube_idproyecto, 'fecha_1': fecha_1, 'fecha_2': fecha_2, 'id_proveedor': id_proveedor, 'comprobante': comprobante, 'visto_bueno':0, 'modulo':modulo }, function (e, status) {
    
    e = JSON.parse(e);  console.log(e); 

    if (e.status == true) {
      $('.total-total').html(`S/ ${formato_miles(parseFloat(e.data.total).toFixed(2))}`);
      $('.total-subtotal').html(`S/ ${formato_miles(parseFloat(e.data.subtotal).toFixed(2))}`);
      $('.total-igv').html(`S/ ${formato_miles(parseFloat(e.data.igv).toFixed(2))}`);

      $('.cargando').hide();
      $('.btn-zip').removeClass('disabled');
      var elementsArray = document.getElementById("reload-all");
      elementsArray.style.display = 'none';
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } ); 
}

// ══════════════════════════════════════  SECCION - VISTO BUENO  ══════════════════════════════════════ 

//Función Listar - tabla compras
function tbla_principal_visto_bueno(nube_idproyecto, empresa_a_cargo, fecha_1, fecha_2, id_proveedor, comprobante, modulo = 'todos') {
  $('.btn-zip').addClass('disabled');
  $('.total-subtotal-visto-bueno').html('0.00');
  $('.total-igv-visto-bueno').html('0.00');
  $('.total-total-visto-bueno').html('0.00');

  var total_subtotal = 0, total_igv = 0, total = 0;

  var name_export = `Resumen de facturas Sevens ${moment().format('DD-MM-YYYY')}`;

  tabla_visto_bueno = $('#tabla-principal-visto-bueno').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,3,4,5,6,7,8,9,10,11,12], } }, 
      { extend: 'excelHtml5', title: name_export, footer: true, exportOptions: { columns: [0,3,4,5,6,7,8,9,10,11,12], } ,
        action: function(e, dt, node, config) { var that = this; Swal.fire('Descargando EXCEL<span class="texto-parpadeante"> ...</span> '); setTimeout(function() { var descargando = $.fn.DataTable.ext.buttons.excelHtml5.action.call(that, e, dt, node, config); $(descargando).ready(function () { Swal.close(); });  }, 1000); }
      }, 
      { extend: 'pdfHtml5', title: name_export, footer: true, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,3,4,5,6,7,8,9,10,11,12], } ,
        action: function(e, dt, node, config) { var that = this; Swal.fire('Descargando PDF<span class="texto-parpadeante"> ...</span> '); setTimeout(function() { var descargando = $.fn.DataTable.ext.buttons.pdfHtml5.action.call(that, e, dt, node, config); $(descargando).ready(function () { Swal.close(); }); }, 1000); }
      }, 
      "colvis"
    ],
    ajax:	{
      url: `../ajax/resumen_facturas.php?op=listar_facturas_compras&id_proyecto=${nube_idproyecto}&empresa_a_cargo=${empresa_a_cargo}&fecha_1=${fecha_1}&fecha_2=${fecha_2}&id_proveedor=${id_proveedor}&comprobante=${comprobante}&visto_bueno='1'&modulo=${modulo}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);	
      }
		},
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[3] != '') { $("td", row).eq(3).addClass('text-center text-nowrap'); }   
      // columna: sub total
      if (data[8] != '') { $("td", row).eq(8).addClass('text-right'); $(".total-subtotal-visto-bueno").html(formato_miles( total_subtotal += parseFloat(data[8]) )); }
      // columna: igv
      if (data[9] != '') { $("td", row).eq(9).addClass('text-right'); $(".total-igv-visto-bueno").html(formato_miles( total_igv += parseFloat(data[9]) )); }  
      // columna: total
      if (data[10] != '') { $("td", row).eq(10).addClass('text-right'); $(".total-total-visto-bueno").html(formato_miles( total += parseFloat(data[10]) )); }     
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
      { targets: [3], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD-MM-YYYY'), },
      //{ targets: [11], visible: false, searchable: false, }, 
    ]
  }).DataTable();
  
  $( tabla_principal ).ready(function() {
    //sumas_totales_visto_bueno(nube_idproyecto, fecha_1, fecha_2, id_proveedor, comprobante, modulo);
    $('.cargando_visto_bueno').hide();
    $('.btn-zip').removeClass('disabled');
  });
  
}

function sumas_totales_visto_bueno(nube_idproyecto, fecha_1, fecha_2, id_proveedor, comprobante, modulo = 'todos') {
  $('.btn-zip').addClass('disabled');
  $.post("../ajax/resumen_facturas.php?op=suma_totales", { 'id_proyecto': nube_idproyecto, 'fecha_1': fecha_1, 'fecha_2': fecha_2, 'id_proveedor': id_proveedor, 'comprobante': comprobante, 'visto_bueno':1, 'modulo':modulo }, function (e, status) {
    
    e = JSON.parse(e);  console.log(e); 

    if (e.status == true) {
      
      $('.total-subtotal-visto-bueno').html(`S/ ${formato_miles(parseFloat(e.data.subtotal))}`);
      $('.total-igv-visto-bueno').html(`S/ ${formato_miles(parseFloat(e.data.igv))}`);
      $('.total-total-visto-bueno').html(`S/ ${formato_miles(parseFloat(e.data.total))}`);

      $('.cargando_visto_bueno').hide();
      $('.btn-zip').removeClass('disabled');
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } ); 
}

function visto_bueno(name_tabla, name_id_tabla, id_tabla, accion, nombre_agregar_quitar) {
  $(".tooltip").removeClass("show").addClass("hidde");
  console.log(name_tabla, name_id_tabla, id_tabla, accion);
  Swal.fire({
    title: `¿Está seguro de ${accion} V°B°?`,
    html: `<i class="text-success" >${nombre_agregar_quitar}</i> <br> Tu <b>nombre</b> se ${accion}a como evidencia de esta <b>afirmacion</b> !!`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: `Si, ${accion}!`,
    preConfirm: (input) => {
      return fetch(`../ajax/resumen_facturas.php?op=visto_bueno&name_tabla=${name_tabla}&name_id_tabla=${name_id_tabla}&id_tabla=${id_tabla}&accion=${accion}`).then(response => {
        //console.log(response);
        if (!response.ok) { throw new Error(response.statusText) }
        return response.json();
      }).catch(error => { Swal.showValidationMessage(`<b>Solicitud fallida:</b> ${error}`); });
    },
    showLoaderOnConfirm: true,
  }).then((result) => {
    console.log(result);
    if (result.isConfirmed) {
      if (result.value.status){        
        Swal.fire("Correcto!", "Visto bueno asignado", "success");
        if (tabla_principal) { tabla_principal.ajax.reload(null, false); } 
        if (tabla_visto_bueno) { tabla_visto_bueno.ajax.reload(null, false); } 
        $('#modal-ver-compras').modal('hide');
        sumas_totales(nube_idproyecto_r, fecha_1_r, fecha_2_r, id_proveedor_r, comprobante_r,  modulo_r);
        sumas_totales_visto_bueno(nube_idproyecto_r, fecha_1_r, fecha_2_r, id_proveedor_r, comprobante_r, modulo_r);
      } else {
        ver_errores(result);
      }      
    }
  });
}

// ══════════════════════════════════════  SECCION - COMPROBANTE  ══════════════════════════════════════ 


//ver ficha tecnica
function modal_comprobante(comprobante, fecha, tipo_comprobante, serie_comprobante, ruta, carpeta, subcarpeta) {

  var data_comprobante = ""; var url = ""; var nombre_download = "Comprobante"; 
  
  $("#modal-ver-comprobante").modal("show");

  if (comprobante == '' || comprobante == null) {       
    $(".ver-comprobante").html(`<div class="alert alert-warning alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fas fa-times text-white"></i></button>
      <h3><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h3>
      No hay un documento para ver. Edite este registro en su modulo correspondiente.
    </div>`);
  }else{
    var host = window.location.host == 'localhost'? `http://localhost/front_jdl/admin/dist/docs/${carpeta}/${subcarpeta}/${comprobante}` : `${window.location.origin}/dist/docs/${carpeta}/${subcarpeta}/${comprobante}` ;
    
    if ( UrlExists(host) == 200 ) {
      nombre_download = `${format_d_m_a(fecha)} ─ ${tipo_comprobante} - ${serie_comprobante}`;
      data_comprobante =  `<div class="col-md-12 mt-2 text-center"><i>${nombre_download}.${extrae_extencion(comprobante)}</i></div> <div class="col-md-12 mt-2"> ${doc_view_extencion(comprobante, carpeta, subcarpeta, width='100%', '450' )} </div>`;
      url = `../${ruta}${comprobante}`;    

      $(".ver-comprobante").html(`<div class="row" >
        <div class="col-md-6 text-center">
          <a type="button" class="btn btn-warning btn-block btn-xs" href="${url}" download="${nombre_download}"> <i class="fas fa-download"></i> Descargar. </a>
        </div>
        <div class="col-md-6 text-center">
          <a type="button" class="btn btn-info btn-block btn-xs" href="${url}" target="_blank" <i class="fas fa-expand"></i> Ver completo. </a>
        </div>
        <div class="col-md-12 mt-3">     
          ${data_comprobante}
        </div>
      </div>`);
      $('.jq_image_zoom').zoom({ on:'grab' });
    } else {     
      $(".ver-comprobante").html(`<div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fas fa-times text-white"></i></button>
        <h3><i class="icon fas fa-exclamation-triangle"></i> Documento no encontrado!</h3>
        Hubo un error al encontrar este archivo, los mas probable es que se haya eliminado, o se haya movido a otro lugar, se recomiendar editar en su modulo correspodiente.
      </div>`);      
    }    
  }
  
  $(".tooltip").removeClass("show").addClass("hidde");
}

function comprobante_multiple(id_tabla, fecha, tipo_comprobante, serie_comprobante, ruta, carpeta, subcarpeta) {
  $('.titulo-comprobante-multiple').html(`Comprobante: <b>${tipo_comprobante} - ${serie_comprobante} - ${fecha}</b>`);
  $("#modal-tabla-comprobantes-multiple").modal("show"); 

  tabla_comprobantes = $("#tabla-comprobantes-multiple").dataTable({
    responsive: true, 
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [ ],
    ajax: {
      url: `../ajax/resumen_facturas.php?op=tbla_comprobantes_multiple_${carpeta}&id_tabla=${id_tabla}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    }, 
    createdRow: function (row, data, ixdex) {
      // columna: 1
      if (data[3] != '') { $("td", row).eq(3).addClass("text-nowrap"); }
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
      { targets: [3], render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY hh:mm:ss a'), },
      //{ targets: [8,11],  visible: false,  searchable: false,  },
    ],
  }).DataTable();
}

// ══════════════════════════════════════  SECCION -  FILTRO ══════════════════════════════════════ 

function cargando_search() {
  var elementsArray = document.getElementById("reload-all");
  elementsArray.style.display = '';
  $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ...`);
  $('.cargando_visto_bueno').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ...`);
}

function filtros(prova) {  
  console.log(prova);
  var empresa_a_cargo = $("#filtro_empresa_a_cargo").select2('val');
  var fecha_1       = $("#fecha_filtro_1").val();
  var fecha_2       = $("#fecha_filtro_2").val();  
  var id_proveedor  = $("#proveedor_filtro").select2('val');
  var comprobante   = $("#tipo_comprobante_filtro").select2('val');   
  
  var nombre_empresa_a_cargo= $('#filtro_empresa_a_cargo').find(':selected').text();
  var nombre_proveedor = $('#proveedor_filtro').find(':selected').text();
  var nombre_comprobante = ' ─ ' + $('#tipo_comprobante_filtro').find(':selected').text();

  // filtro de empresa a cargo
  if (empresa_a_cargo == '' || empresa_a_cargo == null || empresa_a_cargo == 0 ) { empresa_a_cargo = ""; nombre_empresa_a_cargo = "" }

  // filtro de fechas
  if (fecha_1 == "" || fecha_1 == null) { fecha_1 = ""; }
  if (fecha_2 == "" || fecha_2 == null) { fecha_2 = ""; }  

  // filtro de proveedor
  if (id_proveedor == '' || id_proveedor == '0' || id_proveedor == null) { id_proveedor = ""; nombre_proveedor = ""; }

  // filtro de trabajdor
  if (comprobante == '' || comprobante == null || comprobante == 0 ) { comprobante = ""; nombre_comprobante = "" }

  $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ${nombre_empresa_a_cargo} ${nombre_proveedor} ${nombre_comprobante}...`);
  $('.cargando_visto_bueno').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ${nombre_empresa_a_cargo} ${nombre_proveedor} ${nombre_comprobante}...`);
  // console.log(fecha_1, fecha_2, id_proveedor, comprobante);

  tbla_principal(localStorage.getItem("nube_idproyecto"), empresa_a_cargo, fecha_1, fecha_2, id_proveedor, comprobante, modulo_r);
  tbla_principal_visto_bueno(localStorage.getItem("nube_idproyecto"), empresa_a_cargo, fecha_1, fecha_2, id_proveedor, comprobante, modulo_r);
}

function select_modulo(modulo) {  
  console.log(modulo);
  var empresa_a_cargo = $("#filtro_empresa_a_cargo").select2('val');
  var fecha_1       = $("#fecha_filtro_1").val();
  var fecha_2       = $("#fecha_filtro_2").val();  
  var id_proveedor  = $("#proveedor_filtro").select2('val');
  var comprobante   = $("#tipo_comprobante_filtro").select2('val');   
  
  var nombre_empresa_a_cargo= $('#filtro_empresa_a_cargo').find(':selected').text();
  var nombre_proveedor = $('#proveedor_filtro').find(':selected').text();
  var nombre_comprobante = ' ─ ' + $('#tipo_comprobante_filtro').find(':selected').text();

  // filtro de empresa a cargo
  if (empresa_a_cargo == '' || empresa_a_cargo == null || empresa_a_cargo == 0 ) { empresa_a_cargo = ""; nombre_empresa_a_cargo = "" }

  // filtro de fechas
  if (fecha_1 == "" || fecha_1 == null) { fecha_1 = ""; }
  if (fecha_2 == "" || fecha_2 == null) { fecha_2 = ""; }  

  // filtro de proveedor
  if (id_proveedor == '' || id_proveedor == '0' || id_proveedor == null) { id_proveedor = ""; nombre_proveedor = ""; }

  // filtro de trabajdor
  if (comprobante == '' || comprobante == null || comprobante == 0 ) { comprobante = ""; nombre_comprobante = "" }

  $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ${nombre_empresa_a_cargo} ${nombre_proveedor} ${nombre_comprobante}...`);
  $('.cargando_visto_bueno').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ${nombre_empresa_a_cargo} ${nombre_proveedor} ${nombre_comprobante}...`);
  // console.log(fecha_1, fecha_2, id_proveedor, comprobante);

  tbla_principal(localStorage.getItem("nube_idproyecto"), empresa_a_cargo, fecha_1, fecha_2, id_proveedor, comprobante, modulo);
  tbla_principal_visto_bueno(localStorage.getItem("nube_idproyecto"), empresa_a_cargo, fecha_1, fecha_2, id_proveedor, comprobante, modulo);
}

// ══════════════════════════════════════  SECCION - DOWNLOAD  ══════════════════════════════════════ 

function descargar_zip_comprobantes() {   
 
  $('.btn-zip').addClass('disabled btn-danger').removeClass('btn-success');
  $('.btn-zip').html('<i class="fas fa-spinner fa-pulse fa-sm"></i> Comprimiendo datos');

  $.post("../ajax/resumen_facturas.php?op=data_comprobantes", { 'id_proyecto': nube_idproyecto_r, 'fecha_1': fecha_1_r, 'fecha_2': fecha_2_r, 'id_proveedor': id_proveedor_r, 'comprobante': comprobante_r,'visto_bueno':'', 'modulo':modulo_r }, function (e, status) {
    
    e = JSON.parse(e);  console.log(e);    
    
    const zip = new JSZip();  let count = 0; const zipFilename = "comprobantes.zip";
    
    if (e.status == true) {
      if (e.data.data_comprobante.length === 0) {
        $('.btn-zip').removeClass('disabled btn-danger').addClass('btn-success');
        $('.btn-zip').html('<i class="far fa-file-archive fa-lg"></i> Comprobantes .zip');
        toastr.error("No hay docs para descargar!!!");
      }else{
        e.data.data_comprobante.forEach(async function (value){
           
          const urlArr = value.ruta_file.split('/');
          const filename = urlArr[urlArr.length - 1];
    
          try {   
             
            const file = await JSZipUtils.getBinaryContent(value.ruta_file)
            zip.file(filename, file, { binary: true});
            count++;
            if(count === e.data.data_comprobante.length) {
              zip.generateAsync({type:'blob'}).then(function(content) {
                var download_zip = saveAs(content, zipFilename);
                $( download_zip ).ready(function() { toastr.success('Descarga exitosa'); });
                $('.btn-zip').removeClass('disabled btn-danger').addClass('btn-success');
                $('.btn-zip').html('<i class="far fa-file-archive fa-lg"></i> Comprobantes .zip');
              });
            }
          } catch (err) {
            console.log(err); toastr.error('Error al descargar');
            $('.btn-zip').removeClass('disabled btn-danger').addClass('btn-success');
            $('.btn-zip').html('<i class="far fa-file-archive fa-lg"></i> Comprobantes .zip');
          }
        });
      }
    } else {
      ver_errores(e);
    }       

  }).fail( function(e) { ver_errores(e); } ); 
}

// ══════════════════════════════════════  SECCION - DOWNLOAD  ══════════════════════════════════════
function detalle_compra_insumo(id_tabla, name_tabla, name_id_tabla, id_tabla, accion, nombre_agregar_quitar) {
  $('.modal-eliminar-permanente').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  $('.modal-add-remove-visto-bueno').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  (accion == 'quitar'? $('.modal-add-remove-visto-bueno').removeClass('btn-outline-success').addClass('btn-outline-danger').html('<i class="fas fa-times"></i>').attr('data-original-title','Quitar visto bueno') :$('.modal-add-remove-visto-bueno').removeClass('btn-outline-danger').addClass('btn-outline-success').html('<i class="fas fa-check"></i>').attr('data-original-title','Dar visto bueno'));

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  
  $("#print_pdf_compra").addClass('disabled').show();
  $("#excel_compra").addClass('disabled').show();
  $(".nombre-title-detalle-modal").html('Detalle - Compra de Insumo');
  $('#modal-ver-compras .modal-dialog').addClass('modal-xl').removeClass('modal-md');
  $("#modal-ver-compras").modal("show");

  $.post(`../ajax/resumen_facturas.php?op=detalle_compra_insumo`,{'id_tabla':id_tabla}, function (r) {
    $(".detalle_de_modulo").html(r); 
    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#print_pdf_compra").removeClass('disabled');
    $("#print_pdf_compra").attr('href', `../reportes/pdf_compra_activos_fijos.php?id=${id_tabla}&op=insumo` );
    $("#excel_compra").removeClass('disabled');

    $('.modal-eliminar-permanente').attr('onclick', `eliminar_permanente('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${nombre_agregar_quitar}')`);
    $('.modal-add-remove-visto-bueno').attr('onclick', `visto_bueno('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${accion}', '${nombre_agregar_quitar}')`);
  }).fail( function(e) { ver_errores(e); } );
}

function detalle_servicio_maquina(id_tabla, name_tabla, name_id_tabla, id_tabla, accion, nombre_agregar_quitar) {
  $('.modal-eliminar-permanente').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  $('.modal-add-remove-visto-bueno').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  (accion == 'quitar'? $('.modal-add-remove-visto-bueno').removeClass('btn-outline-success').addClass('btn-outline-danger').html('<i class="fas fa-times"></i>').attr('data-original-title','Quitar visto bueno') :$('.modal-add-remove-visto-bueno').removeClass('btn-outline-danger').addClass('btn-outline-success').html('<i class="fas fa-check"></i>').attr('data-original-title','Dar visto bueno'));

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#print_pdf_compra").addClass('disabled').hide();
  $("#excel_compra").addClass('disabled').hide();
  $(".nombre-title-detalle-modal").html('Detalle - Servicio maquina');
  $('#modal-ver-compras .modal-dialog').addClass('modal-md').removeClass('modal-xl');
  $("#modal-ver-compras").modal("show");

  var comprobante=''; var btn_comprobante = '';

  $.post("../ajax/resumen_facturas.php?op=detalle_servicio_maquina", { 'id_tabla': id_tabla }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 
    
    if (e.status == true) {        

      if (e.data.comprobante == '' || e.data.comprobante == null ) {
        comprobante='No hay Comprobante';
        btn_comprobante='';
      } else {
        comprobante =  doc_view_extencion(e.data.comprobante, 'servicio_maquina', 'comprobante_servicio', '100%');        
        btn_comprobante=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/servicio_maquina/comprobante_servicio/${e.data.comprobante}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/servicio_maquina/comprobante_servicio/${e.data.comprobante}" download="Factura-${e.data.codigo} - ${removeCaracterEspecial(e.data.razon_social)}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      }     

      var retorno_html=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Proveedor</th> 
                  <td>${e.data.razon_social}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Maquina:</th> 
                  <td>${e.data.nombre_maquina}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo comprobante:</th>
                  <td>Factura - ${e.data.codigo}</td>
                </tr>                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Fecha emisión:</th>
                  <td>${ format_d_m_a(e.data.fecha_emision)}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Subtotal</th>
                  <td>${e.data.subtotal}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>IGV</th>
                  <td>${e.data.igv}</td>
                </tr>            
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Total</th>
                  <td>${e.data.total}</td>
                </tr>                                               
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Notal</th>
                  <td>${e.data.nota}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Descripción</th>
                  <td>${e.data.descripcion}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Comprobante</th>
                  <td> ${comprobante} <br>${btn_comprobante}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;
    
      $(".detalle_de_modulo").html(retorno_html);

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();

      $('.modal-eliminar-permanente').attr('onclick', `eliminar_permanente('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${nombre_agregar_quitar}')`);
      $('.modal-add-remove-visto-bueno').attr('onclick', `visto_bueno('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${accion}', '${nombre_agregar_quitar}')`);
      $('.jq_image_zoom').zoom({ on:'grab' });
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

function detalle_servicio_equipo(id_tabla, name_tabla, name_id_tabla, id_tabla, accion, nombre_agregar_quitar) {
  $('.modal-eliminar-permanente').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  $('.modal-add-remove-visto-bueno').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  (accion == 'quitar'? $('.modal-add-remove-visto-bueno').removeClass('btn-outline-success').addClass('btn-outline-danger').html('<i class="fas fa-times"></i>').attr('data-original-title','Quitar visto bueno') :$('.modal-add-remove-visto-bueno').removeClass('btn-outline-danger').addClass('btn-outline-success').html('<i class="fas fa-check"></i>').attr('data-original-title','Dar visto bueno'));

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  
  $("#print_pdf_compra").addClass('disabled').hide();
  $("#excel_compra").addClass('disabled').hide();
  $(".nombre-title-detalle-modal").html('Detalle - Servicio equipo');
  $('#modal-ver-compras .modal-dialog').addClass('modal-md').removeClass('modal-xl');
  $("#modal-ver-compras").modal("show");

  var comprobante=''; var btn_comprobante = '';

  $.post("../ajax/resumen_facturas.php?op=detalle_servicio_equipo", { 'id_tabla': id_tabla }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 
    
    if (e.status == true) {        

      if (e.data.comprobante == '' || e.data.comprobante == null ) {
        comprobante='No hay Comprobante';
        btn_comprobante='';
      } else {
        comprobante =  doc_view_extencion(e.data.comprobante, 'servicio_equipo', 'comprobante_servicio', '100%');        
        btn_comprobante=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/servicio_equipo/comprobante_servicio/${e.data.comprobante}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/servicio_equipo/comprobante_servicio/${e.data.comprobante}" download="Factura-${e.data.codigo} - ${removeCaracterEspecial(e.data.razon_social)}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      }     

      var retorno_html=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Proveedor</th> 
                  <td>${e.data.razon_social}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Equipo:</th> 
                  <td>${e.data.nombre_maquina}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo comprobante:</th>
                  <td>Factura - ${e.data.codigo}</td>
                </tr>                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Fecha emisión:</th>
                  <td>${ format_d_m_a(e.data.fecha_emision)}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Subtotal</th>
                  <td>${formato_miles(e.data.subtotal)}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>IGV</th>
                  <td>${formato_miles(e.data.igv)}</td>
                </tr>            
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Total</th>
                  <td>${formato_miles(e.data.total)}</td>
                </tr>                                               
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Nota</th>
                  <td>${e.data.nota}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Descripción</th>
                  <td>${e.data.descripcion}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Comprobante</th>
                  <td> ${comprobante} <br>${btn_comprobante}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;
    
      $(".detalle_de_modulo").html(retorno_html);

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();

      $('.modal-eliminar-permanente').attr('onclick', `eliminar_permanente('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${nombre_agregar_quitar}')`);
      $('.modal-add-remove-visto-bueno').attr('onclick', `visto_bueno('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${accion}', '${nombre_agregar_quitar}')`);
      $('.jq_image_zoom').zoom({ on:'grab' });
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

function detalle_sub_contrato(id_tabla, name_tabla, name_id_tabla, id_tabla, accion, nombre_agregar_quitar) {
  $('.modal-eliminar-permanente').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  $('.modal-add-remove-visto-bueno').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  (accion == 'quitar'? $('.modal-add-remove-visto-bueno').removeClass('btn-outline-success').addClass('btn-outline-danger').html('<i class="fas fa-times"></i>').attr('data-original-title','Quitar visto bueno') :$('.modal-add-remove-visto-bueno').removeClass('btn-outline-danger').addClass('btn-outline-success').html('<i class="fas fa-check"></i>').attr('data-original-title','Dar visto bueno'));

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  
  $("#print_pdf_compra").addClass('disabled').hide();
  $("#excel_compra").addClass('disabled').hide();
  $(".nombre-title-detalle-modal").html('Detalle - Sub Contrato');
  $('#modal-ver-compras .modal-dialog').addClass('modal-md').removeClass('modal-xl');
  $("#modal-ver-compras").modal("show");

  var comprobante=''; var btn_comprobante = '';

  $.post("../ajax/resumen_facturas.php?op=detalle_sub_contrato", { 'id_tabla': id_tabla }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 
    
    if (e.status == true) {        

      if (e.data.comprobante == '' || e.data.comprobante == null ) {
        comprobante='No hay Comprobante';
        btn_comprobante='';
      } else {
        comprobante =  doc_view_extencion(e.data.comprobante, 'sub_contrato', 'comprobante_subcontrato', '100%');        
        btn_comprobante=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/sub_contrato/comprobante_subcontrato/${e.data.comprobante}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/sub_contrato/comprobante_subcontrato/${e.data.comprobante}" download="${e.data.tipo_comprobante}-${e.data.numero_comprobante} - ${removeCaracterEspecial(e.data.razon_social)}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      }     

      var retorno_html=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Proveedor</th> 
                  <td><span class="text-primary">${e.data.razon_social}</span> <br> <b>${e.data.tipo_documento}</b>: ${e.data.ruc}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Forma de pago:</th> 
                  <td>${e.data.forma_de_pago}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo comprobante:</th>
                  <td>${e.data.tipo_comprobante} - ${e.data.numero_comprobante}</td>
                </tr>                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Fecha emisión:</th>
                  <td>${ format_d_m_a(e.data.fecha_subcontrato)}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Valor IGV</th>
                  <td>${ e.data.val_igv}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Subtotal</th>
                  <td>${formato_miles(e.data.subtotal)}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>IGV</th>
                  <td>${formato_miles(e.data.igv)}</td>
                </tr>            
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Total</th>
                  <td>${formato_miles(e.data.total)}</td>
                </tr>                                         
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Glosa</th>
                  <td>${e.data.glosa}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Descripción</th>
                  <td>${e.data.descripcion}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Comprobante</th>
                  <td> ${comprobante} <br>${btn_comprobante}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;
    
      $(".detalle_de_modulo").html(retorno_html);

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();

      $('.modal-eliminar-permanente').attr('onclick', `eliminar_permanente('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${nombre_agregar_quitar}')`);
      $('.modal-add-remove-visto-bueno').attr('onclick', `visto_bueno('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${accion}', '${nombre_agregar_quitar}')`);
      $('.jq_image_zoom').zoom({ on:'grab' });
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

function detalle_planilla_seguro(id_tabla, name_tabla, name_id_tabla, id_tabla, accion, nombre_agregar_quitar) {
  $('.modal-eliminar-permanente').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  $('.modal-add-remove-visto-bueno').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  (accion == 'quitar'? $('.modal-add-remove-visto-bueno').removeClass('btn-outline-success').addClass('btn-outline-danger').html('<i class="fas fa-times"></i>').attr('data-original-title','Quitar visto bueno') :$('.modal-add-remove-visto-bueno').removeClass('btn-outline-danger').addClass('btn-outline-success').html('<i class="fas fa-check"></i>').attr('data-original-title','Dar visto bueno'));

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  
  $("#print_pdf_compra").addClass('disabled').hide();
  $("#excel_compra").addClass('disabled').hide();
  $(".nombre-title-detalle-modal").html('Detalle - Planilla Seguro');
  $('#modal-ver-compras .modal-dialog').addClass('modal-md').removeClass('modal-xl');
  $("#modal-ver-compras").modal("show");

  var comprobante=''; var btn_comprobante = '';

  $.post("../ajax/resumen_facturas.php?op=detalle_planilla_seguro", { 'id_tabla': id_tabla }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 
    
    if (e.status == true) {        

      if (e.data.comprobante == '' || e.data.comprobante == null ) {
        comprobante='No hay Comprobante';
        btn_comprobante='';
      } else {
        comprobante =  doc_view_extencion(e.data.comprobante, 'planilla_seguro', 'comprobante', '100%');        
        btn_comprobante=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/planilla_seguro/comprobante/${e.data.comprobante}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/planilla_seguro/comprobante/${e.data.comprobante}" download="${e.data.tipo_comprobante}-${e.data.numero_comprobante} - ${removeCaracterEspecial(e.data.razon_social)}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      }     

      var retorno_html=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>                 
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Forma de pago:</th> 
                  <td>${e.data.forma_de_pago}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo comprobante:</th>
                  <td>${e.data.tipo_comprobante} - ${e.data.numero_comprobante}</td>
                </tr>                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Fecha emisión:</th>
                  <td>${ format_d_m_a(e.data.fecha_p_s)}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Valor IGV</th>
                  <td>${ e.data.val_igv}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Subtotal</th>
                  <td>${formato_miles(e.data.subtotal)}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>IGV</th>
                  <td>${formato_miles(e.data.igv)}</td>
                </tr>            
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Total</th>
                  <td>${formato_miles(e.data.total)}</td>
                </tr>                                         
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo gravada</th>
                  <td>${e.data.tipo_gravada}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Descripción</th>
                  <td>${e.data.descripcion}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Comprobante</th>
                  <td> ${comprobante} <br>${btn_comprobante}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;
    
      $(".detalle_de_modulo").html(retorno_html);

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();

      $('.modal-eliminar-permanente').attr('onclick', `eliminar_permanente('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${nombre_agregar_quitar}')`);
      $('.modal-add-remove-visto-bueno').attr('onclick', `visto_bueno('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${accion}', '${nombre_agregar_quitar}')`);
      $('.jq_image_zoom').zoom({ on:'grab' });
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

function detalle_otro_gasto(id_tabla, name_tabla, name_id_tabla, id_tabla, accion, nombre_agregar_quitar) {
  $('.modal-eliminar-permanente').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  $('.modal-add-remove-visto-bueno').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  (accion == 'quitar'? $('.modal-add-remove-visto-bueno').removeClass('btn-outline-success').addClass('btn-outline-danger').html('<i class="fas fa-times"></i>').attr('data-original-title','Quitar visto bueno') :$('.modal-add-remove-visto-bueno').removeClass('btn-outline-danger').addClass('btn-outline-success').html('<i class="fas fa-check"></i>').attr('data-original-title','Dar visto bueno'));

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  
  $("#print_pdf_compra").addClass('disabled').hide();
  $("#excel_compra").addClass('disabled').hide();
  $(".nombre-title-detalle-modal").html('Detalle - Otro Gasto');
  $('#modal-ver-compras .modal-dialog').addClass('modal-md').removeClass('modal-xl');
  $("#modal-ver-compras").modal("show");

  var comprobante=''; var btn_comprobante = '';

  $.post("../ajax/resumen_facturas.php?op=detalle_otro_gasto", { 'id_tabla': id_tabla }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 
    
    if (e.status == true) {        

      if (e.data.comprobante == '' || e.data.comprobante == null ) {
        comprobante='No hay Comprobante';
        btn_comprobante='';
      } else {
        comprobante =  doc_view_extencion(e.data.comprobante, 'otro_gasto', 'comprobante', '100%');        
        btn_comprobante=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/otro_gasto/comprobante/${e.data.comprobante}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/otro_gasto/comprobante/${e.data.comprobante}" download="${e.data.tipo_comprobante}-${e.data.numero_comprobante} - ${removeCaracterEspecial(e.data.razon_social)}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      }     

      var retorno_html=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Proveedor</th> 
                  <td><span class="text-primary">${e.data.razon_social}</span> <br> <b>Ruc</b>: ${e.data.ruc}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Forma de pago:</th> 
                  <td>${e.data.forma_de_pago}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo comprobante:</th>
                  <td>${e.data.tipo_comprobante} - ${e.data.numero_comprobante}</td>
                </tr>                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Fecha emisión:</th>
                  <td>${ format_d_m_a(e.data.fecha_g)}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Valor IGV</th>
                  <td>${ e.data.val_igv}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Subtotal</th>
                  <td>${formato_miles(e.data.subtotal)}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>IGV</th>
                  <td>${formato_miles(e.data.igv)}</td>
                </tr>            
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Total</th>
                  <td>${formato_miles(e.data.total)}</td>
                </tr>                                         
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Glosa</th>
                  <td>${e.data.glosa}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo gravada</th>
                  <td>${e.data.tipo_gravada}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Descripción</th>
                  <td>${e.data.descripcion}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Comprobante</th>
                  <td> ${comprobante} <br>${btn_comprobante}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;
    
      $(".detalle_de_modulo").html(retorno_html);

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();

      $('.modal-eliminar-permanente').attr('onclick', `eliminar_permanente('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${nombre_agregar_quitar}')`);
      $('.modal-add-remove-visto-bueno').attr('onclick', `visto_bueno('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${accion}', '${nombre_agregar_quitar}')`);
      $('.jq_image_zoom').zoom({ on:'grab' });
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

function detalle_transporte(id_tabla, name_tabla, name_id_tabla, id_tabla, accion, nombre_agregar_quitar) {
  $('.modal-eliminar-permanente').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  $('.modal-add-remove-visto-bueno').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  (accion == 'quitar'? $('.modal-add-remove-visto-bueno').removeClass('btn-outline-success').addClass('btn-outline-danger').html('<i class="fas fa-times"></i>').attr('data-original-title','Quitar visto bueno') :$('.modal-add-remove-visto-bueno').removeClass('btn-outline-danger').addClass('btn-outline-success').html('<i class="fas fa-check"></i>').attr('data-original-title','Dar visto bueno'));

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  
  $("#print_pdf_compra").addClass('disabled').hide();
  $("#excel_compra").addClass('disabled').hide();
  $(".nombre-title-detalle-modal").html('Detalle - Transporte');
  $('#modal-ver-compras .modal-dialog').addClass('modal-md').removeClass('modal-xl');
  $("#modal-ver-compras").modal("show");

  var comprobante=''; var btn_comprobante = '';

  $.post("../ajax/resumen_facturas.php?op=detalle_transporte", { 'id_tabla': id_tabla }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 
    
    if (e.status == true) {        

      if (e.data.comprobante == '' || e.data.comprobante == null ) {
        comprobante='No hay Comprobante';
        btn_comprobante='';
      } else {
        comprobante =  doc_view_extencion(e.data.comprobante, 'transporte', 'comprobante', '100%');        
        btn_comprobante=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/transporte/comprobante/${e.data.comprobante}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/transporte/comprobante/${e.data.comprobante}" download="${e.data.tipo_comprobante}-${e.data.numero_comprobante} - ${removeCaracterEspecial(e.data.razon_social)}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      }     

      var retorno_html=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Proveedor</th> 
                  <td><span class="text-primary">${e.data.razon_social}</span> <br> <b>Ruc</b>: ${e.data.ruc}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Forma de pago:</th> 
                  <td>${e.data.forma_de_pago}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo comprobante:</th>
                  <td>${e.data.tipo_comprobante} - ${e.data.numero_comprobante}</td>
                </tr>                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Fecha emisión:</th>
                  <td>${ format_d_m_a(e.data.fecha_viaje)}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Cantidad</th>
                  <td>${ e.data.cantidad}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Precio unitario</th>
                  <td>${ formato_miles(e.data.precio_unitario)}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Valor IGV</th>
                  <td>${ e.data.val_igv}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Subtotal</th>
                  <td>${formato_miles(e.data.subtotal)}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>IGV</th>
                  <td>${formato_miles(e.data.igv)}</td>
                </tr>            
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Total</th>
                  <td>${formato_miles(e.data.total)}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo viajero</th>
                  <td>${e.data.tipo_viajero}</td>
                </tr>    
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo ruta</th>
                  <td>${e.data.tipo_ruta}</td>
                </tr>    
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Ruta</th>
                  <td>${e.data.ruta}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Glosa</th>
                  <td>${e.data.glosa}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo gravada</th>
                  <td>${e.data.tipo_gravada}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Descripción</th>
                  <td>${e.data.descripcion}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Comprobante</th>
                  <td> ${comprobante} <br>${btn_comprobante}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;
    
      $(".detalle_de_modulo").html(retorno_html);

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();

      $('.modal-eliminar-permanente').attr('onclick', `eliminar_permanente('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${nombre_agregar_quitar}')`);
      $('.modal-add-remove-visto-bueno').attr('onclick', `visto_bueno('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${accion}', '${nombre_agregar_quitar}')`);
      $('.jq_image_zoom').zoom({ on:'grab' });
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

function detalle_hospedaje(id_tabla, name_tabla, name_id_tabla, id_tabla, accion, nombre_agregar_quitar) {
  $('.modal-eliminar-permanente').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  $('.modal-add-remove-visto-bueno').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  (accion == 'quitar'? $('.modal-add-remove-visto-bueno').removeClass('btn-outline-success').addClass('btn-outline-danger').html('<i class="fas fa-times"></i>').attr('data-original-title','Quitar visto bueno') :$('.modal-add-remove-visto-bueno').removeClass('btn-outline-danger').addClass('btn-outline-success').html('<i class="fas fa-check"></i>').attr('data-original-title','Dar visto bueno'));

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  
  $("#print_pdf_compra").addClass('disabled').hide();
  $("#excel_compra").addClass('disabled').hide();
  $(".nombre-title-detalle-modal").html('Detalle - Hospedaje');
  $('#modal-ver-compras .modal-dialog').addClass('modal-md').removeClass('modal-xl');
  $("#modal-ver-compras").modal("show");

  var comprobante=''; var btn_comprobante = '';

  $.post("../ajax/resumen_facturas.php?op=detalle_hospedaje", { 'id_tabla': id_tabla }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 
    
    if (e.status == true) {        

      if (e.data.comprobante == '' || e.data.comprobante == null ) {
        comprobante='No hay Comprobante';
        btn_comprobante='';
      } else {
        comprobante =  doc_view_extencion(e.data.comprobante, 'hospedaje', 'comprobante', '100%');        
        btn_comprobante=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/hospedaje/comprobante/${e.data.comprobante}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/hospedaje/comprobante/${e.data.comprobante}" download="${e.data.tipo_comprobante}-${e.data.numero_comprobante} - ${removeCaracterEspecial(e.data.razon_social)}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      }     

      var retorno_html=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Proveedor</th> 
                  <td><span class="text-primary">${e.data.razon_social}</span> <br> <b>Ruc</b>: ${e.data.ruc}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Forma de pago:</th> 
                  <td>${e.data.forma_de_pago}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo comprobante:</th>
                  <td>${e.data.tipo_comprobante} - ${e.data.numero_comprobante}</td>
                </tr>                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Fecha emisión:</th>
                  <td>${ format_d_m_a(e.data.fecha_comprobante)}</td>
                </tr> 
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Fecha estadia:</th>
                  <td>${format_d_m_a(e.data.fecha_inicio)} - ${format_d_m_a(e.data.fecha_fin)}</td>
                </tr> 
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Cantidad:</th>
                  <td>${ e.data.cantidad}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Unidad:</th>
                  <td>${ e.data.unidad}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Precio unitario:</th>
                  <td>${ e.data.precio_unitario}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Valor IGV:</th>
                  <td>${ e.data.val_igv}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Subtotal:</th>
                  <td>${formato_miles(e.data.subtotal)}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>IGV:</th>
                  <td>${formato_miles(e.data.igv)}</td>
                </tr>            
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Total:</th>
                  <td>${formato_miles(e.data.total)}</td>
                </tr>                                         
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Glosa:</th>
                  <td>${e.data.glosa}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo gravada:</th>
                  <td>${e.data.tipo_gravada}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Descripción:</th>
                  <td>${e.data.descripcion}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Comprobante:</th>
                  <td> ${comprobante} <br>${btn_comprobante}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;
    
      $(".detalle_de_modulo").html(retorno_html);

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();

      $('.modal-eliminar-permanente').attr('onclick', `eliminar_permanente('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${nombre_agregar_quitar}')`);
      $('.modal-add-remove-visto-bueno').attr('onclick', `visto_bueno('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${accion}', '${nombre_agregar_quitar}')`);
      $('.jq_image_zoom').zoom({ on:'grab' });
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

function detalle_pension(id_tabla, name_tabla, name_id_tabla, id_tabla, accion, nombre_agregar_quitar) {
  $('.modal-eliminar-permanente').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  $('.modal-add-remove-visto-bueno').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  (accion == 'quitar'? $('.modal-add-remove-visto-bueno').removeClass('btn-outline-success').addClass('btn-outline-danger').html('<i class="fas fa-times"></i>').attr('data-original-title','Quitar visto bueno') :$('.modal-add-remove-visto-bueno').removeClass('btn-outline-danger').addClass('btn-outline-success').html('<i class="fas fa-check"></i>').attr('data-original-title','Dar visto bueno'));

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  
  $("#print_pdf_compra").addClass('disabled').hide();
  $("#excel_compra").addClass('disabled').hide();
  $(".nombre-title-detalle-modal").html('Detalle - Pensión');
  $('#modal-ver-compras .modal-dialog').addClass('modal-md').removeClass('modal-xl');
  $("#modal-ver-compras").modal("show");

  var comprobante=''; var btn_comprobante = '';

  $.post("../ajax/resumen_facturas.php?op=detalle_pension", { 'id_tabla': id_tabla }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 
    
    if (e.status == true) {        

      if (e.data.comprobante == '' || e.data.comprobante == null ) {
        comprobante='No hay Comprobante';
        btn_comprobante='';
      } else {
        comprobante =  doc_view_extencion(e.data.comprobante, 'pension', 'comprobante', '100%');        
        btn_comprobante=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/pension/comprobante/${e.data.comprobante}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/pension/comprobante/${e.data.comprobante}" download="${e.data.tipo_comprobante}-${e.data.nro_comprobante} - ${removeCaracterEspecial(e.data.razon_social)}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      }     

      var retorno_html=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Proveedor</th> 
                  <td><span class="text-primary">${e.data.razon_social}</span> <br> <b>${e.data.tipo_documento}</b>: ${e.data.ruc}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Forma de pago:</th> 
                  <td>${e.data.forma_pago}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo comprobante:</th>
                  <td>${e.data.tipo_comprobante} - ${e.data.numero_comprobante}</td>
                </tr>                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Fecha emisión:</th>
                  <td>${ format_d_m_a(e.data.fecha_emision)}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Valor IGV</th>
                  <td>${ e.data.val_igv}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Subtotal</th>
                  <td>${formato_miles(e.data.subtotal)}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>IGV</th>
                  <td>${formato_miles(e.data.igv)}</td>
                </tr>            
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Total</th>
                  <td>${formato_miles(e.data.precio_parcial)}</td>
                </tr>                                         
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Glosa</th>
                  <td>${e.data.glosa}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo gravada</th>
                  <td>${e.data.tipo_gravada}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Descripción</th>
                  <td>${e.data.descripcion}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Comprobante</th>
                  <td> ${comprobante} <br>${btn_comprobante}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;
    
      $(".detalle_de_modulo").html(retorno_html);

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();

      $('.modal-eliminar-permanente').attr('onclick', `eliminar_permanente('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${nombre_agregar_quitar}')`);
      $('.modal-add-remove-visto-bueno').attr('onclick', `visto_bueno('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${accion}', '${nombre_agregar_quitar}')`);
      $('.jq_image_zoom').zoom({ on:'grab' });
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

function detalle_break(id_tabla, name_tabla, name_id_tabla, id_tabla, accion, nombre_agregar_quitar) {
  $('.modal-eliminar-permanente').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  $('.modal-add-remove-visto-bueno').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  (accion == 'quitar'? $('.modal-add-remove-visto-bueno').removeClass('btn-outline-success').addClass('btn-outline-danger').html('<i class="fas fa-times"></i>').attr('data-original-title','Quitar visto bueno') :$('.modal-add-remove-visto-bueno').removeClass('btn-outline-danger').addClass('btn-outline-success').html('<i class="fas fa-check"></i>').attr('data-original-title','Dar visto bueno'));

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  
  $("#print_pdf_compra").addClass('disabled').hide();
  $("#excel_compra").addClass('disabled').hide();
  $(".nombre-title-detalle-modal").html('Detalle - Breack');
  $('#modal-ver-compras .modal-dialog').addClass('modal-md').removeClass('modal-xl');
  $("#modal-ver-compras").modal("show");

  var comprobante=''; var btn_comprobante = '';

  $.post("../ajax/resumen_facturas.php?op=detalle_break", { 'id_tabla': id_tabla }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 
    
    if (e.status == true) {        

      if (e.data.comprobante == '' || e.data.comprobante == null ) {
        comprobante='No hay Comprobante';
        btn_comprobante='';
      } else {
        comprobante =  doc_view_extencion(e.data.comprobante, 'break', 'comprobante', '100%');        
        btn_comprobante=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/break/comprobante/${e.data.comprobante}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/break/comprobante/${e.data.comprobante}" download="${e.data.tipo_comprobante}-${e.data.nro_comprobante} - ${removeCaracterEspecial(e.data.razon_social)}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      }     

      var retorno_html=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Proveedor</th> 
                  <td><span class="text-primary">${e.data.razon_social}</span> <br> <b>Ruc</b>: ${e.data.ruc}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Forma de pago:</th> 
                  <td>${e.data.forma_de_pago}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo comprobante:</th>
                  <td>${e.data.tipo_comprobante} - ${e.data.nro_comprobante}</td>
                </tr>                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Fecha emisión:</th>
                  <td>${ format_d_m_a(e.data.fecha_emision)}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Valor IGV</th>
                  <td>${ e.data.val_igv}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Subtotal</th>
                  <td>${formato_miles(e.data.subtotal)}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>IGV</th>
                  <td>${formato_miles(e.data.igv)}</td>
                </tr>            
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Total</th>
                  <td>${formato_miles(e.data.total)}</td>
                </tr>                                         
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Glosa</th>
                  <td>${e.data.glosa}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo gravada</th>
                  <td>${e.data.tipo_gravada}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Descripción</th>
                  <td>${e.data.descripcion}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Comprobante</th>
                  <td> ${comprobante} <br>${btn_comprobante}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;
    
      $(".detalle_de_modulo").html(retorno_html);

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();

      $('.modal-eliminar-permanente').attr('onclick', `eliminar_permanente('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${nombre_agregar_quitar}')`);
      $('.modal-add-remove-visto-bueno').attr('onclick', `visto_bueno('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${accion}', '${nombre_agregar_quitar}')`);
      $('.jq_image_zoom').zoom({ on:'grab' });
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

function detalle_comida_extra(id_tabla, name_tabla, name_id_tabla, id_tabla, accion, nombre_agregar_quitar) {
  $('.modal-eliminar-permanente').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  $('.modal-add-remove-visto-bueno').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  (accion == 'quitar'? $('.modal-add-remove-visto-bueno').removeClass('btn-outline-success').addClass('btn-outline-danger').html('<i class="fas fa-times"></i>').attr('data-original-title','Quitar visto bueno') :$('.modal-add-remove-visto-bueno').removeClass('btn-outline-danger').addClass('btn-outline-success').html('<i class="fas fa-check"></i>').attr('data-original-title','Dar visto bueno'));

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  
  $("#print_pdf_compra").addClass('disabled').hide();
  $("#excel_compra").addClass('disabled').hide();
  $(".nombre-title-detalle-modal").html('Detalle - Comida extra');
  $('#modal-ver-compras .modal-dialog').addClass('modal-md').removeClass('modal-xl');
  $("#modal-ver-compras").modal("show");

  var comprobante=''; var btn_comprobante = '';

  $.post("../ajax/resumen_facturas.php?op=detalle_comida_extra", { 'id_tabla': id_tabla }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 
    
    if (e.status == true) {        

      if (e.data.comprobante == '' || e.data.comprobante == null ) {
        comprobante='No hay Comprobante';
        btn_comprobante='';
      } else {
        comprobante =  doc_view_extencion(e.data.comprobante, 'comida_extra', 'comprobante', '100%');        
        btn_comprobante=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/comida_extra/comprobante/${e.data.comprobante}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/comida_extra/comprobante/${e.data.comprobante}" download="${e.data.tipo_comprobante}-${e.data.numero_comprobante} - ${removeCaracterEspecial(e.data.razon_social)}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      }     

      var retorno_html=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Proveedor</th> 
                  <td><span class="text-primary">${e.data.razon_social}</span> <br> <b>Ruc</b>: ${e.data.ruc}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Forma de pago:</th> 
                  <td>${e.data.forma_de_pago}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo comprobante:</th>
                  <td>${e.data.tipo_comprobante} - ${e.data.numero_comprobante}</td>
                </tr>                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Fecha emisión:</th>
                  <td>${ format_d_m_a(e.data.fecha_comida)}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Valor IGV</th>
                  <td>${ e.data.val_igv}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Subtotal</th>
                  <td>${formato_miles(e.data.subtotal)}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>IGV</th>
                  <td>${formato_miles(e.data.igv)}</td>
                </tr>            
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Total</th>
                  <td>${formato_miles(e.data.total)}</td>
                </tr>                                         
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Glosa</th>
                  <td>${e.data.glosa}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo gravada</th>
                  <td>${e.data.tipo_gravada}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Descripción</th>
                  <td>${e.data.descripcion}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Comprobante</th>
                  <td> ${comprobante} <br>${btn_comprobante}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;
    
      $(".detalle_de_modulo").html(retorno_html);

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();

      $('.modal-eliminar-permanente').attr('onclick', `eliminar_permanente('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${nombre_agregar_quitar}')`);
      $('.modal-add-remove-visto-bueno').attr('onclick', `visto_bueno('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${accion}', '${nombre_agregar_quitar}')`);
      $('.jq_image_zoom').zoom({ on:'grab' });
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

function detalle_otro_ingreso(id_tabla, name_tabla, name_id_tabla, id_tabla, accion, nombre_agregar_quitar) {
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  
  $("#print_pdf_compra").addClass('disabled').hide();
  $("#excel_compra").addClass('disabled').hide();
  $(".nombre-title-detalle-modal").html('Detalle - Otro Ingreso');
  $('#modal-ver-compras .modal-dialog').addClass('modal-md').removeClass('modal-xl');
  $("#modal-ver-compras").modal("show");
}

function detalle_otra_factura(id_tabla, name_tabla, name_id_tabla, id_tabla, accion, nombre_agregar_quitar) {
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  
  $("#print_pdf_compra").addClass('disabled').hide();
  $("#excel_compra").addClass('disabled').hide();
  $(".nombre-title-detalle-modal").html('Detalle - Otra Factura');
  $('#modal-ver-compras .modal-dialog').addClass('modal-md').removeClass('modal-xl');
  $("#modal-ver-compras").modal("show");
}

function detalle_pago_administrador(id_tabla, name_tabla, name_id_tabla, id_tabla, accion, nombre_agregar_quitar) {
  $('.modal-eliminar-permanente').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  $('.modal-add-remove-visto-bueno').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  (accion == 'quitar'? $('.modal-add-remove-visto-bueno').removeClass('btn-outline-success').addClass('btn-outline-danger').html('<i class="fas fa-times"></i>').attr('data-original-title','Quitar visto bueno') :$('.modal-add-remove-visto-bueno').removeClass('btn-outline-danger').addClass('btn-outline-success').html('<i class="fas fa-check"></i>').attr('data-original-title','Dar visto bueno'));

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  
  $("#print_pdf_compra").addClass('disabled').hide();
  $("#excel_compra").addClass('disabled').hide();
  $(".nombre-title-detalle-modal").html('Detalle - Pago Administrador');
  $('#modal-ver-compras .modal-dialog').addClass('modal-md').removeClass('modal-xl');
  $("#modal-ver-compras").modal("show");

  var imagen_perfil =''; btn_imagen_perfil='';
  var recibos_x_honorarios=''; var btn_recibos_x_honorarios = '';

  $.post("../ajax/resumen_facturas.php?op=detalle_pago_administrador", { 'id_tabla': id_tabla }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 
    
    if (e.status == true) {        
      if (e.data.imagen_perfil != '') {

        imagen_perfil=`<img src="../dist/docs/all_trabajador/perfil/${e.data.imagen_perfil}" alt="" class="img-thumbnail w-130px" >`
        
        btn_imagen_perfil=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/all_trabajador/perfil/${e.data.imagen_perfil}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/all_trabajador/perfil/${e.data.imagen_perfil}" download="PERFIL - ${e.data.trabajador}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      
      } else {
        imagen_perfil='No hay imagen';
        btn_imagen_perfil='';
      }

      if (e.data.recibos_x_honorarios == '' || e.data.recibos_x_honorarios == null ) {
        recibos_x_honorarios='No hay Comprobante';
        btn_recibos_x_honorarios='';
      } else {
        recibos_x_honorarios =  doc_view_extencion(e.data.recibos_x_honorarios, 'pago_administrador', 'recibos_x_honorarios', '100%');        
        btn_recibos_x_honorarios=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/pago_administrador/recibos_x_honorarios/${e.data.recibos_x_honorarios}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/pago_administrador/recibos_x_honorarios/${e.data.recibos_x_honorarios}" download="${e.data.tipo_comprobante}-${e.data.numero_comprobante} - ${removeCaracterEspecial(e.data.trabajador)}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      }     

      var retorno_html=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th rowspan="2" class="text-center">${imagen_perfil}<br>${btn_imagen_perfil} </th>
                  <td><span class="text-primary">${e.data.trabajador}</span></td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <td> <b>${e.data.tipo_documento }</b>: ${e.data.numero_documento }</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Forma de pago:</th> 
                  <td>${e.data.forma_de_pago}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo comprobante:</th>
                  <td>${e.data.tipo_comprobante} ${ e.data.numero_comprobante==null||e.data.numero_comprobante==''? '':' - ' + e.data.numero_comprobante}</td>
                </tr>                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Fecha emisión:</th>
                  <td>${ format_d_m_a(e.data.fecha_pago)}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Valor IGV</th>
                  <td>${0.00}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Subtotal</th>
                  <td>${formato_miles(e.data.monto)}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>IGV</th>
                  <td>${0.00}</td>
                </tr>            
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Total</th>
                  <td>${formato_miles(e.data.monto)}</td>
                </tr>                                         
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Glosa</th>
                  <td></td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo gravada</th>
                  <td>${'NO GRAVADA'}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Descripción</th>
                  <td>${e.data.descripcion}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Comprobante</th>
                  <td> ${recibos_x_honorarios} <br>${btn_recibos_x_honorarios}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;
    
      $(".detalle_de_modulo").html(retorno_html);

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();

      $('.modal-eliminar-permanente').attr('onclick', `eliminar_permanente('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${nombre_agregar_quitar}')`);
      $('.modal-add-remove-visto-bueno').attr('onclick', `visto_bueno('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${accion}', '${nombre_agregar_quitar}')`);
      $('.jq_image_zoom').zoom({ on:'grab' });
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

function detalle_pago_obrero(id_tabla, name_tabla, name_id_tabla, id_tabla, accion, nombre_agregar_quitar) {
  $('.modal-eliminar-permanente').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  $('.modal-add-remove-visto-bueno').attr('onclick', `toastr_info("Espera!!","Espera la carga completa", 700)`);
  (accion == 'quitar'? $('.modal-add-remove-visto-bueno').removeClass('btn-outline-success').addClass('btn-outline-danger').html('<i class="fas fa-times"></i>').attr('data-original-title','Quitar visto bueno') :$('.modal-add-remove-visto-bueno').removeClass('btn-outline-danger').addClass('btn-outline-success').html('<i class="fas fa-check"></i>').attr('data-original-title','Dar visto bueno'));

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  
  $("#print_pdf_compra").addClass('disabled').hide();
  $("#excel_compra").addClass('disabled').hide();
  $(".nombre-title-detalle-modal").html('Detalle - Pago Administrador');
  $('#modal-ver-compras .modal-dialog').addClass('modal-md').removeClass('modal-xl');
  $("#modal-ver-compras").modal("show");

  var imagen_perfil =''; btn_imagen_perfil='';
  var recibos_x_honorarios=''; var btn_recibos_x_honorarios = '';

  $.post("../ajax/resumen_facturas.php?op=detalle_pago_obrero", { 'id_tabla': id_tabla }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 
    
    if (e.status == true) {        
      if (e.data.imagen_perfil != '') {

        imagen_perfil=`<img src="../dist/docs/all_trabajador/perfil/${e.data.imagen_perfil}" alt="" class="img-thumbnail w-130px" >`
        
        btn_imagen_perfil=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/all_trabajador/perfil/${e.data.imagen_perfil}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/all_trabajador/perfil/${e.data.imagen_perfil}" download="PERFIL - ${e.data.trabajador}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      
      } else {
        imagen_perfil='No hay imagen';
        btn_imagen_perfil='';
      }

      if (e.data.recibos_x_honorarios == '' || e.data.recibos_x_honorarios == null ) {
        recibos_x_honorarios='No hay Comprobante';
        btn_recibos_x_honorarios='';
      } else {
        recibos_x_honorarios =  doc_view_extencion(e.data.recibos_x_honorarios, 'pago_obrero', 'recibos_x_honorarios', '100%');        
        btn_recibos_x_honorarios=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/pago_obrero/recibos_x_honorarios/${e.data.recibos_x_honorarios}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/pago_obrero/recibos_x_honorarios/${e.data.recibos_x_honorarios}" download="${e.data.tipo_comprobante}-${e.data.numero_comprobante} - ${removeCaracterEspecial(e.data.trabajador)}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      }     

      var retorno_html=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th rowspan="2" class="text-center">${imagen_perfil}<br>${btn_imagen_perfil} </th>
                  <td><span class="text-primary">${e.data.trabajador}</span></td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <td> <b>${e.data.tipo_documento }</b>: ${e.data.numero_documento }</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Forma de pago:</th> 
                  <td>${e.data.forma_de_pago}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo comprobante:</th>
                  <td>${e.data.tipo_comprobante} ${ e.data.numero_comprobante==null||e.data.numero_comprobante==''? '':' - ' + e.data.numero_comprobante}</td>
                </tr>                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Fecha emisión:</th>
                  <td>${ format_d_m_a(e.data.fecha_pago)}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Valor IGV</th>
                  <td>${0.00}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Subtotal</th>
                  <td>${formato_miles(e.data.monto_deposito)}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>IGV</th>
                  <td>${0.00}</td>
                </tr>            
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Total</th>
                  <td>${formato_miles(e.data.monto_deposito)}</td>
                </tr>                                         
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Glosa</th>
                  <td></td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo gravada</th>
                  <td>${'NO GRAVADA'}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Descripción</th>
                  <td>${e.data.descripcion}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Comprobante</th>
                  <td> ${recibos_x_honorarios} <br>${btn_recibos_x_honorarios}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;
    
      $(".detalle_de_modulo").html(retorno_html);

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();

      $('.modal-eliminar-permanente').attr('onclick', `eliminar_permanente('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${nombre_agregar_quitar}')`);
      $('.modal-add-remove-visto-bueno').attr('onclick', `visto_bueno('${name_tabla}', '${name_id_tabla}', '${id_tabla}', '${accion}', '${nombre_agregar_quitar}')`);
      $('.jq_image_zoom').zoom({ on:'grab' });
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}


function eliminar_permanente(nombre_tabla, nombre_id_tabla, id_tabla, nombre) { 

  Swal.fire({
    title: "¿Está Seguro de Eliminar Permanente?",
    html: `<b class="text-danger"><del>${nombre}</del></b> <br> Al Eliminarlo, no podra recuperarlo.`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#6c757d",    
    confirmButtonText: `<i class="fas fa-skull-crossbones"></i> Eliminar`,
    showLoaderOnConfirm: true,
    preConfirm: (input) => {       
      return fetch(`../ajax/resumen_facturas.php?op=eliminar_comprobante&nombre_tabla=${nombre_tabla}&nombre_id_tabla=${nombre_id_tabla}&id_tabla=${id_tabla}`).then(response => {
        //console.log(response);
        if (!response.ok) { throw new Error(response.statusText) }
        return response.json();
      }).catch(error => { Swal.showValidationMessage(`<b>Solicitud fallida:</b> ${error}`); })
    },
    allowOutsideClick: () => !Swal.isLoading()
  }).then((result) => {
    console.log(result);
    if (result.isConfirmed) {
      if (result.value.status) {
        Swal.fire("Eliminado!", "Tu registro ha sido ELIMINADO PERMANENTEMENTE.", "success");
        if (tabla_principal) { tabla_principal.ajax.reload(null, false); } 
        if (tabla_visto_bueno) { tabla_visto_bueno.ajax.reload(null, false); } 
        $('#modal-ver-compras').modal('hide');
        sumas_totales(nube_idproyecto_r, fecha_1_r, fecha_2_r, id_proveedor_r, comprobante_r,  modulo_r);
        sumas_totales_visto_bueno(nube_idproyecto_r, fecha_1_r, fecha_2_r, id_proveedor_r, comprobante_r,  modulo_r);
        $(".tooltip").removeClass("show").addClass("hidde");
      }else{
        ver_errores(result.value);
      }
    }
  });
}

init();

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

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