var tabla;

//Función que se ejecuta al inicio
function init() {
  //Activamos el "aside"
  $("#bloc_ContableFinanciero").addClass("menu-open");

  $("#mContableFinanciero").addClass("active");

  $("#lOtroIngreso").addClass("active bg-green");

  lista_de_items();
  tbla_principal();

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/otro_ingreso.php?op=selecct_produc_o_provee", '#idpersona', null);
  lista_select2("../ajax/ajax_general.php?op=select2_cargo_trabajador", '#cargo_trabajador_per', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════ 
  $("#guardar_registro_persona").on("click", function (e) { $("#submit-form-persona").submit(); });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - OTRO INGRESO  ══════════════════════════════════════
  
  $("#idpersona").select2({ theme: "bootstrap4", placeholder: "Selecione un proveedor o productor", allowClear: true,   });
  $("#tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Seleccinar tipo comprobante", allowClear: true, });
  $("#forma_pago").select2({ theme: "bootstrap4", placeholder: "Seleccinar forma de pago", allowClear: true, });

  $("#tipo_documento_per").select2({theme:"bootstrap4", placeholder: "Selec. tipo Doc.", allowClear: true, });
  $("#cargo_trabajador_per").select2({theme:"bootstrap4", placeholder: "Selecione cargo", allowClear: true, });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

// abrimos el navegador de archivos
$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addImageApplication(e,$("#doc1").attr("id")) });

// Eliminamos el doc 1
function doc1_eliminar() {
	$("#doc1").val("");
	$("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');
	$("#doc1_nombre").html("");
}

//Función limpiar
function limpiar_form() {
  $("#idotro_ingreso").val("");
  $("#fecha_i").val("");  
  $("#nro_comprobante").val("");
  $("#ruc").val("");
  $("#razon_social").val("");
  $("#direccion").val("");
  $("#subtotal").val("");
  $("#igv").val("");
  $("#precio_parcial").val("");
  $("#descripcion").val("");

  $("#doc_old_1").val("");
  $("#doc1").val("");  
  $('#doc1_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc1_nombre').html("");

  $("#idpersona").val("null").trigger("change");
  $("#tipo_comprobante").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change");

  $("#val_igv").val(""); 
  $("#tipo_gravada").val(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
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

//Función Listar
function tbla_principal() {
  tabla = $("#tabla-otro-ingreso").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>", //Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i>', className: "btn bg-gradient-info", action: function ( e, dt, node, config ) { tabla.ajax.reload(null, false); toastr_success('Exito!!', 'Actualizando tabla', 400); } },
      { extend: 'copyHtml5', exportOptions: { columns: [0,2,8,9,4,5,6,7,8], }, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray", footer: true,  }, 
      { extend: 'excelHtml5', exportOptions: { columns: [0,2,8,9,4,5,6,7,8], }, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success", footer: true,  }, 
      { extend: 'pdfHtml5', exportOptions: { columns: [0,2,8,9,4,5,6,7,8], }, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger", footer: false, orientation: 'landscape', pageSize: 'LEGAL',  },
      { extend: "colvis", text: `Columnas`, className: "btn bg-gradient-gray", exportOptions: { columns: "th:not(:last-child)", }, },
    ],
    ajax: {
      url: "../ajax/otro_ingreso.php?op=tbla_principal",
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); verer
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != "") { $("td", row).eq(0).addClass("text-center"); }
      // columna: Bontones
      if (data[1] != "") { $("td", row).eq(1).addClass("text-nowrap"); }
      // columna: Tipo comprobante
      if (data[5] != "") { $("td", row).eq(5).addClass("text-nowrap"); }
      // columna: igv
      if (data[6] != "") { $("td", row).eq(6).addClass("text-nowrap text-right"); }
      // columna: total
      if (data[7] != "") { $("td", row).eq(7).addClass("text-nowrap text-right"); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    footerCallback: function( tfoot, data, start, end, display ) {
      var api1 = this.api(); var total1 = api1.column( 6 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api1.column( 6 ).footer() ).html( `<span class="float-left">S/</span> <span class="float-right">${formato_miles(total1)}</span>` ); 
      
      var api2 = this.api(); var total2 = api2.column( 7 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api2.column( 7 ).footer() ).html( `<span class="float-left">S/</span> <span class="float-right">${formato_miles(total2)}</span>` );

      var api3 = this.api(); var total3 = api3.column( 8 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api3.column( 8 ).footer() ).html( `<span class="float-left">S/</span> <span class="float-right">${formato_miles(total3)}</span>` );
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
    columnDefs: [
      //{ targets: [], visible: false, searchable: false, }, 
      { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      { targets: [6,7,8], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },      
    ],
  }).DataTable();

}

//segun tipo de comprobante
function comprob_factura() {

  var precio_parcial = $("#precio_parcial").val(); 
  
  if ($("#tipo_comprobante").select2("val") == "" || $("#tipo_comprobante").select2("val") == null) {

    $(".nro_comprobante").html("Núm. Comprobante");

    $("#val_igv").val(""); $("#tipo_gravada").val(""); 

    if (precio_parcial == null || precio_parcial == "") {
      $("#subtotal").val(0);
      $("#igv").val(0);    
    } else {
      $("#subtotal").val(parseFloat(precio_parcial).toFixed(2));
      $("#igv").val(0);    
    }   

  } else if ($("#tipo_comprobante").select2("val") == "Ninguno") {     

    $(".nro_comprobante").html("Núm. de Operación");

    $("#val_igv").prop("readonly",true);

    if (precio_parcial == null || precio_parcial == "") {
      $("#subtotal").val(0);
      $("#igv").val(0);
      
      $("#val_igv").val("0"); 
      $("#tipo_gravada").val("NO GRAVADA");  

    } else {
      $("#subtotal").val(parseFloat(precio_parcial).toFixed(2));
      $("#igv").val(0); 

      $("#val_igv").val("0"); 
      $("#tipo_gravada").val("NO GRAVADA"); 
    }   

  } else if ($("#tipo_comprobante").select2("val") == "Factura") {    

    $(".nro_comprobante").html("Núm. Comprobante");
    $(".div_ruc").show(); $(".div_razon_social").show();  
    calculandototales_fact();     
  
  } else { 

    $("#val_igv").prop("readonly",true);

    if ($("#tipo_comprobante").select2("val") == "Boleta") {

      $(".nro_comprobante").html("Núm. Comprobante");

      $(".div_ruc").show(); $(".div_razon_social").show();
      
      if (precio_parcial == null || precio_parcial == "") {
        $("#subtotal").val(0);
        $("#igv").val(0); 
        $("#val_igv").val("0");   
      } else {
                
        $("#subtotal").val("");
        $("#igv").val("");

        $("#subtotal").val(parseFloat(precio_parcial).toFixed(2));
        $("#igv").val(0); 
        
        $("#val_igv").val("0"); 
        $("#tipo_gravada").val("NO GRAVADA"); 
      } 
        
    } else {
              
      $(".nro_comprobante").html("Núm. Comprobante");

      $(".div_ruc").hide(); $(".div_razon_social").hide();

      $("#ruc").val(""); $("#razon_social").val("");

      if (precio_parcial == null || precio_parcial == "") {
        
        $("#subtotal").val(0);
        $("#igv").val(0);

        $("#val_igv").val("0"); 
        $("#tipo_gravada").val("NO GRAVADA");  

      } else {

        $("#subtotal").val(parseFloat(precio_parcial).toFixed(2));
        $("#igv").val(0); 

        $("#val_igv").val("0"); 
        $("#tipo_gravada").val("NO GRAVADA");  

      } 
      
    }    
  }  
}

function validando_igv() {

  if ($("#tipo_comprobante").select2("val") == "Factura") {

    $("#val_igv").prop("readonly",false);
    $("#val_igv").val(0.18); 

  }else {

    $("#val_igv").val(0); 

  }  
}

function calculandototales_fact() {
  //----------------
  $("#tipo_gravada").val("GRAVADA"); 
         
  $(".nro_comprobante").html("Núm. Comprobante");

  var precio_parcial = $("#precio_parcial").val();

  var val_igv = $('#val_igv').val();

  if (precio_parcial == null || precio_parcial == "") {

    $("#subtotal").val(0);
    $("#igv").val(0); 

  } else {
 
    var subtotal = 0;
    var igv = 0;

    if (val_igv == null || val_igv == "") {

      $("#subtotal").val(parseFloat(precio_parcial));
      $("#igv").val(0);

    }else{

      $("subtotal").val("");
      $("#igv").val("");

      subtotal = quitar_igv_del_precio(precio_parcial, val_igv, 'decimal');
      igv = precio_parcial - subtotal;

      $("#subtotal").val(parseFloat(subtotal).toFixed(2));
      $("#igv").val(parseFloat(igv).toFixed(2));

    }

  }  

}

function quitar_igv_del_precio(precio , igv, tipo ) {
  console.log(precio , igv, tipo);
  var precio_sin_igv = 0;

  switch (tipo) {
    case 'decimal':

      if (parseFloat(precio) != NaN && igv > 0 && igv <= 1 ) {
        precio_sin_igv = ( parseFloat(precio) * 100 ) / ( ( parseFloat(igv) * 100 ) + 100 )
      }else{
        precio_sin_igv = precio;
      }
    break;

    case 'entero':

      if (parseFloat(precio) != NaN && igv > 0 && igv <= 100 ) {
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

//ver ficha tecnica
function modal_comprobante(comprobante,tipo,numero_comprobante) {

  var dia_actual = moment().format('DD-MM-YYYY');
  $(".nombre_comprobante").html(`${tipo}-${numero_comprobante}`);
  $('#modal-ver-comprobante').modal("show");
  $('#ver_fact_pdf').html(doc_view_extencion(comprobante, 'otro_ingreso', 'comprobante', '100%', '550'));

  if (DocExist(`dist/docs/otro_ingreso/comprobante/${comprobante}`) == 200) {
    $("#iddescargar").attr("href","../dist/docs/otro_ingreso/comprobante/"+comprobante).attr("download", `${tipo}-${numero_comprobante}  - ${dia_actual}`).removeClass("disabled");
    $("#ver_completo").attr("href","../dist/docs/otro_ingreso/comprobante/"+comprobante).removeClass("disabled");
  } else {
    $("#iddescargar").addClass("disabled");
    $("#ver_completo").addClass("disabled");
  }

  $('.jq_image_zoom').zoom({ on:'grab' }); 

}

//Función para guardar o editar
function guardar_y_editar_otros_ingresos(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-otro-ingreso")[0]);

  $.ajax({
    url: "../ajax/otro_ingreso.php?op=guardar_y_editar_otros_ingresos",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);
        if (e.status == true) {

          Swal.fire("Correcto!", "El registro se guardo correctamente.", "success");
          tabla.ajax.reload(null, false);
          limpiar_form(); show_hide_form(1);

        } else {
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
          $("#barra_progress_otro_ingreso").css({"width": percentComplete+'%'}).text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_otro_ingreso").css({ width: "0%",  }).text("0%").addClass('progress-bar-striped progress-bar-animated');
      $("#barra_progress_otro_ingreso_div").show();
    },
    complete: function () {
      $("#barra_progress_otro_ingreso").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');
      $("#barra_progress_otro_ingreso_div").hide();
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar(idotro_ingreso) {

  limpiar_form(); show_hide_form(2);
  
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-otro_ingreso").modal("show");

  $.post("../ajax/otro_ingreso.php?op=mostrar", { idotro_ingreso: idotro_ingreso }, function (e, status) {
    
    e = JSON.parse(e); console.log('jolll'); console.log(e);    

    $("#idpersona").val(e.data.idpersona).trigger("change");
    $("#tipo_comprobante").val(e.data.tipo_comprobante).trigger("change");
    $("#forma_pago").val(e.data.forma_de_pago).trigger("change");
    $("#idotro_ingreso").val(e.data.idotro_ingreso);
    $("#fecha_i").val(e.data.fecha_ingreso);
    $("#nro_comprobante").val(e.data.numero_comprobante);  

    $("#subtotal").val(e.data.precio_sin_igv);
    $("#igv").val(e.data.precio_igv);
    $("#val_igv").val(e.data.val_igv);
    $("#tipo_gravada").val(e.data.tipo_gravada);
    $("#precio_parcial").val(e.data.precio_con_igv);
    $("#descripcion").val(e.data.descripcion);    

    if (e.data.comprobante == "" || e.data.comprobante == null  ) {
      $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
      $("#doc1_nombre").html('');
      $("#doc_old_1").val(""); $("#doc1").val("");
    } else {
      $("#doc_old_1").val(e.data.comprobante);
      $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(e.data.comprobante)}</i></div></div>`);
      // cargamos la imagen adecuada par el archivo
      $("#doc1_ver").html(doc_view_extencion(e.data.comprobante,'otro_ingreso', 'comprobante', '100%', '210' ));            
    }

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();
  }).fail( function(e) { ver_errores(e); } );
}

function ver_datos(idotro_ingreso) {
  $("#modal-ver-otro-ingreso").modal("show");
  $('#datos_otro_ingreso').html(`<div class="row"><div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br/><br/><h4>Cargando...</h4></div></div>`);

  var comprobante=''; var btn_comprobante = '';

  $.post("../ajax/otro_ingreso.php?op=verdatos", { idotro_ingreso: idotro_ingreso }, function (e, status) {
    e = JSON.parse(e);  console.log(e);

    if (e.data.comprobante != '') {
        
      comprobante =  doc_view_extencion(e.data.comprobante, 'otro_ingreso', 'comprobante', '100%');
      
      btn_comprobante=`
      <div class="row">
        <div class="col-6"">
          <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/otro_ingreso/comprobante/${e.data.comprobante}"> <i class="fas fa-expand"></i></a>
        </div>
        <div class="col-6"">
          <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/otro_ingreso/comprobante/${e.data.comprobante}" download="comprobante - ${removeCaracterEspecial(e.data.razon_social)}"> <i class="fas fa-download"></i></a>
        </div>
      </div>`;
    
    } else {

      comprobante='Sin Ficha Técnica';
      btn_comprobante='';

    }

    var ver_datos_html = `                                                                            
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <table class="table table-hover table-bordered">        
            <tbody>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Nombres </th>
                <td>${e.data.nombres} <br> <b>${e.data.tipo_documento}:</b> ${e.data.numero_documento} </td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Forma Pago</th>
                <td>${e.data.forma_de_pago}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Tipo Comprobante</th>
                <td>${e.data.tipo_comprobante}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Núm. Comprobante</th>
                  <td>${e.data.numero_comprobante}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Fecha Emisión</th>
                <td>${e.data.fecha_ingreso}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Sub total</th>
                <td>${e.data.precio_sin_igv}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>IGV</th>
                <td>${e.data.precio_igv}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Monto total</th>
                <td>${parseFloat(e.data.precio_con_igv).toFixed(2)}</td>
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

    $("#datos_otro_ingreso").html(ver_datos_html);
  }).fail( function(e) { ver_errores(e); } );
}

//Función para desactivar registros
function eliminar(idotro_ingreso, nombre,numero_comprobante) {

  crud_eliminar_papelera(
    "../ajax/otro_ingreso.php?op=desactivar",
    "../ajax/otro_ingreso.php?op=eliminar", 
    idotro_ingreso, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre} : ${numero_comprobante}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );
}
// :::::::::::::::::::::::::::::::::::::::::::::::::::: S E C C I O N   P R O V E E D O R  ::::::::::::::::::::::::::::::::::::::::::::::::::::
// abrimos el navegador de archivos
$("#foto1_i").click(function() { $('#foto1').trigger('click'); });
$("#foto1").change(function(e) { addImage(e,$("#foto1").attr("id")) });

function foto1_eliminar() {

	$("#foto1").val("");
	$("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");
	$("#foto1_nombre").html("");
}

//Función limpiar
function limpiar_persona() {
  $("#guardar_registro_persona").html('Guardar Cambios').removeClass('disabled');

  $("#idpersona_per").val(""); 
  $("#tipo_documento_per").val("null").trigger("change");
  $("#cargo_trabajador_per").val("1").trigger("change");

  $("#num_documento_per").val(""); 
  $("#nombre_per").val(""); 
  $("#telefono_per").val(""); 
  $("#email_per").val("");   
  
  $("#sueldo_mensual_per").val("").trigger("change");
  $("#direccion_per").val(""); 

  $("#nacimiento_per").val("");
  $("#edad_per").val("");
  $(".edad").html("0.00");

  $("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");
	$("#foto1").val("");
	$("#foto1_actual").val("");  
  $("#foto1_nombre").html(""); 
  
  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function lista_de_items() { 

  $(".lista-items").html(`<li class="nav-item"><a class="nav-link active" role="tab" ><i class="fas fa-spinner fa-pulse fa-sm"></i></a></li>`); 

  $.post("../ajax/ajax_general.php?op=array_tipo_persona", function (e, status) {
    
    e = JSON.parse(e); //console.log(e);
    // e.data.idtipo_tierra
    if (e.status) {
      var data_html = '';

      e.data.forEach((val, index) => {
        var activar = index == 0 || index == '0' ? 'active' : '';
        data_html = data_html.concat(`
        <li class="nav-item">
          <a class="nav-link ${activar}" onclick="delay(function(){show_hide_btn_add('${val.idtipo_persona}', '${val.nombre}')}, 50 );" id="tabs-for-persona-tab" data-toggle="pill" href="#tabs-for-persona" role="tab" aria-controls="tabs-for-persona" aria-selected="false">${val.nombre}</a>
        </li>`);
      });

      $(".lista-items").html(` ${data_html} `); 
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

function show_hide_btn_add(tipo_persona, nombre_tipo) {
  limpiar_persona();

  $(".btn-agregar-persona").show();
  $("#id_tipo_persona_per").val(tipo_persona);

  if (tipo_persona=="todos") {
    $("#id_tipo_persona_per").val("1");
    $(".btn-agregar-persona").hide();    
  }else if (tipo_persona=="2") { //trabajador
    $(".cp_tipo_doc").show();
    $(".cp_num_doc").show();
    $(".cp_nombre").show();
    $(".cp_telefono").show();
    $(".cp_email").show();
    $(".cp_f_nacimiento").show();
    $(".cp_edad").show();
    $(".cp_cargo").show();
    $(".cp_s_mensual").show();
    $(".cp_s_diario").show();
    $(".cp_direccion").show();    
    $(".titulo-modal").html(`<i class="fas fa-plus"></i> Agregar Trabajador`);
  }else if (tipo_persona=="3") { //proveedor    
    $(".cp_tipo_doc").show();
    $(".cp_num_doc").show();
    $(".cp_nombre").show();
    $(".cp_telefono").show();
    $(".cp_email").show();
    $(".cp_f_nacimiento").hide();
    $(".cp_edad").hide();
    $(".cp_cargo").hide();
    $(".cp_s_mensual").hide();
    $(".cp_s_diario").hide();
    $(".cp_direccion").show();
    $(".titulo-modal").html(`<i class="fas fa-plus"></i> Agregar Proveedor`);
  }else if (tipo_persona=="4") { //cliente    
    $(".cp_tipo_doc").show();
    $(".cp_num_doc").show();
    $(".cp_nombre").show();
    $(".cp_telefono").show();
    $(".cp_email").show();
    $(".cp_f_nacimiento").show();
    $(".cp_edad").show();
    $(".cp_cargo").hide();
    $(".cp_s_mensual").hide();
    $(".cp_s_diario").hide();
    $(".cp_direccion").show();
    $(".titulo-modal").html(`<i class="fas fa-plus"></i> Agregar Cliente`);
  } else {
    $(".cp_tipo_doc").show();
    $(".cp_num_doc").show();
    $(".cp_nombre").show();
    $(".cp_telefono").show();
    $(".cp_email").show();
    $(".cp_f_nacimiento").show();
    $(".cp_edad").show();
    $(".cp_cargo").hide();
    $(".cp_s_mensual").hide();
    $(".cp_s_diario").hide();
    $(".cp_direccion").show();
    $(".titulo-modal").html(`<i class="fas fa-plus"></i> Agregar ${nombre_tipo}`);    
  }
}

//guardar proveedor
function guardar_persona(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-persona")[0]);

  $.ajax({
    url: "../ajax/otro_ingreso.php?op=guardar_persona",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      e = JSON.parse(e);
      try {
        if (e.status == true) {
          // toastr.success("proveedor registrado correctamente");
          Swal.fire("Correcto!", "Persona guardado correctamente.", "success");          
          limpiar_persona();
          $("#modal-agregar-persona").modal("hide");
          //Cargamos los items al select persona
          lista_select2("../ajax/otro_ingreso.php?op=selecct_produc_o_provee", '#idpersona', e.data);
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }       
      
      $("#guardar_registro_persona").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_persona").css({"width": percentComplete+'%'}).text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_persona").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_persona").css({ width: "0%",  }).text("0%").addClass('progress-bar-striped progress-bar-animated');
      $("#barra_progress_persona_div").show();
    },
    complete: function () {
      $("#barra_progress_persona").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');
      $("#barra_progress_persona_div").hide();
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function sueld_mensual(){

  var sueldo_mensual = $('#sueldo_mensual_per').val();
  var sueldo_diario=(sueldo_mensual/30).toFixed(1);
  var sueldo_horas=(sueldo_diario/8).toFixed(1);
  $("#sueldo_diario_per").val(sueldo_diario);

}

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..
$(function () {   

  // Aplicando la validacion del select cada vez que cambie
  $("#idpersona").on('change', function() { $(this).trigger('blur'); });
  $("#forma_pago").on("change", function () { $(this).trigger("blur"); });
  $("#tipo_comprobante").on("change", function () { $(this).trigger("blur"); });

  
  $("#tipo_documento_per").on('change', function() { $(this).trigger('blur'); });
  $("#cargo_trabajador_per").on('change', function() { $(this).trigger('blur'); });

  $("#form-otro-ingreso").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      idpersona:{ required: true },
      forma_pago: { required: true },
      tipo_comprobante: { required: true },
      fecha_i: { required: true },
      precio_parcial: { required: true },
      descripcion: { required: true },
      val_igv: { required: true, number: true, min:0, max:1 },
      // terms: { required: true },
    },
    messages: {
      idpersona:{ required: "Campo requerido", },
      forma_pago: { required: "Campo requerido", },
      tipo_comprobante: { required: "Campo requerido", },
      fecha_i: { required: "Campo requerido", },
      precio_parcial: { required: "Campo requerido",},
      descripcion: { required: "Es necesario rellenar el campo descripción", },
      val_igv: { required: "Campo requerido", number: 'Ingrese un número', min:'Mínimo 0', max:'Maximo 1' },
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
      guardar_y_editar_otros_ingresos(e);
    },

  });

  $("#form-persona").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      tipo_documento_per: { required: true },
      num_documento_per:  { required: true, minlength: 6, maxlength: 20 },
      nombre_per:         { required: true, minlength: 6, maxlength: 100 },
      email_per:          { email: true, minlength: 10, maxlength: 50 },
      direccion_per:      { minlength: 5, maxlength: 200 },
      telefono_per:       { minlength: 8 },
      sueldo_mensual_per: { required: true},
    },
    messages: {
      tipo_documento: { required: "Campo requerido.", },
      num_documento_per:  { required: "Campo requerido.", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 20 caracteres.", },
      nombre_per:         { required: "Campo requerido.", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 100 caracteres.", },
      email_per:          { required: "Campo requerido.", email: "Ingrese un coreo electronico válido.", minlength: "MÍNIMO 10 caracteres.", maxlength: "MÁXIMO 50 caracteres.", },
      direccion_per:      { minlength: "MÍNIMO 5 caracteres.", maxlength: "MÁXIMO 200 caracteres.", },
      telefono_per:       { minlength: "MÍNIMO 8 caracteres.", },
      sueldo_mensual_per: { required: "Campo requerido.", }
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
      guardar_persona(e);
    },
  });

  //agregando la validacion del select  ya que no tiene un atributo name el plugin 
  $("#idpersona").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#forma_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#tipo_comprobante").rules("add", { required: true, messages: { required: "Campo requerido" } });

  $("#tipo_documento_per").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#cargo_trabajador_per").rules('add', { required: true, messages: {  required: "Campo requerido" } });

  // restringimos la fecha para no elegir mañana
  no_select_tomorrow('#fecha_i');
  no_select_over_18('#nacimiento');

});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..




init();