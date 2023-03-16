var tabla;
var fecha_1_r="", fecha_2_r="", id_proveedor_r="", comprobante_r="";
//Función que se ejecuta al inicio
function init() {

  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Viaticos").addClass("menu-open");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#mViatico").addClass("active bg-green");

  $("#sub_bloc_comidas").addClass("menu-open bg-color-191f24");

  $("#sub_mComidas").addClass("active bg-green");

  $("#lComidasExtras").addClass("active");

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════  
  lista_select2(`../ajax/comidas_extras.php?op=selecct2_prov_comidas_ex&idproyecto=${localStorage.getItem("nube_idproyecto")}`, '#filtro_proveedor', null);

    // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════

  $("#guardar_registro").on("click", function (e) {$("#submit-form-comidas-ex").submit();});

  // ══════════════════════════════════════ INITIALIZE SELECT2 - FILTROS ══════════════════════════════════════
  $("#filtro_tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione comprobante", allowClear: true, });
  $("#filtro_proveedor").select2({ theme: "bootstrap4", placeholder: "Selecione proveedor", allowClear: true, });
  
  //Initialize Select2 Elements
  $("#tipo_comprobante").select2({  theme: "bootstrap4", placeholder: "Selecione tipo comprobante", allowClear: true, });
  $("#forma_pago").select2({ theme: "bootstrap4", placeholder: "Selecione forma de pago", allowClear: true, });

  // Inicializar - Date picker  
  $('#filtro_fecha_inicio').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });
  $('#filtro_fecha_fin').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });



  // Formato para telefono
  $("[data-mask]").inputmask();

}

$('.click-btn-fecha-inicio').on('click', function (e) {$('#filtro_fecha_inicio').focus().select(); });
$('.click-btn-fecha-fin').on('click', function (e) {$('#filtro_fecha_fin').focus().select(); });


$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addImageApplication(e,$("#doc1").attr("id")) });

// Eliminamos el doc 1
function doc1_eliminar() {

	$("#doc1").val("");

	$("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc1_nombre").html("");
}

//Función limpiar
function limpiar() {

  $("#idcomida_extra").val("");
  $("#fecha").val(""); 
  $("#precio_parcial").val("");  
  
  $("#descripcion").val("");
  $("#tipo_comprobante").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change"); 
  $("#nro_comprobante").val("");
  
  $("#num_documento").val("");
  $("#razon_social").val("");
  $("#direccion").val("");

  $("#subtotal").val("");

  $("#igv").val("");
  //$("#val_igv").val(""); 
  $("#tipo_gravada").val("");  

  $("#doc_old_1").val("");
  $("#doc1").val("");  
  $('#doc1_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc1_nombre').html("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function listar(fecha_1, fecha_2, id_proveedor, comprobante) { 

  fecha_1_r=fecha_1; fecha_2_r=fecha_2; id_proveedor_r=id_proveedor, comprobante_r=comprobante;
  $("#total_monto").html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');

  var idproyecto=localStorage.getItem('nube_idproyecto');
  
  tabla=$('#tabla-comidas_extras').dataTable({
    "responsive": true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,12,11,13,14,2,5,6,7,8,16,17,9], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,12,11,13,14,2,5,6,7,8,16,17,9], } }, 
      { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,12,11,13,14,2,5,6,7,8,16,17,9], }, orientation: 'landscape', pageSize: 'LEGAL',  }, 
      {extend: "colvis"} ,
    ],
    "ajax":{
        url: `../ajax/comidas_extras.php?op=listar&idproyecto=${idproyecto}&fecha_1=${fecha_1}&fecha_2=${fecha_2}&id_proveedor=${id_proveedor}&comprobante=${comprobante}`,
        type : "get",
        dataType : "json",						
        error: function(e){
          console.log(e.responseText);	ver_errores(e);
        }
      },
      createdRow: function (row, data, ixdex) {
        // columna: #
        if (data[0] != '') { $("td", row).eq(0).addClass('text-center'); }
        // columna: 1
        if (data[1] != "") { $("td", row).eq(1).addClass("text-nowrap"); }
        // columna: sub total
        if (data[6] != '') { $("td", row).eq(6).addClass('text-nowrap text-right'); }
        // columna: igv
        if (data[7] != '') { $("td", row).eq(7).addClass('text-nowrap text-right'); }
        // columna: total
        if (data[8] != '') { $("td", row).eq(8).addClass('text-nowrap text-right'); }
      },
      language: {
        lengthMenu: "Mostrar: _MENU_ registros",
        buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
        sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
      },
    "bDestroy": true,
    "iDisplayLength": 10,//Paginación
    "order": [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [
      { targets: [11,12,13,14,15,16,17], visible: false, searchable: false, },    
    ],
  }).DataTable();
  total(fecha_1_r,fecha_2_r,id_proveedor_r,comprobante_r);
  $(tabla).ready(function () {  $('.cargando').hide(); });
}

function modal_comprobante(comprobante,tipo,numero_comprobante){
  limpiar();

  var dia_actual = moment().format('DD-MM-YYYY');

  $(".nombre_comprobante").html(`${tipo}-${numero_comprobante}`);
  $('#modal-ver-comprobante').modal("show");

  $('#ver_fact_pdf').html(doc_view_extencion(comprobante, 'comida_extra', 'comprobante', '100%', '550'));

  if (DocExist(`dist/docs/comida_extra/comprobante/${comprobante}`) == 200) {
    $("#iddescargar").attr("href","../dist/docs/comida_extra/comprobante/"+comprobante).attr("download", `${tipo}-${numero_comprobante}  - ${dia_actual}`).removeClass("disabled");
    $("#ver_completo").attr("href","../dist/docs/comida_extra/comprobante/"+comprobante).removeClass("disabled");
  } else {
    $("#iddescargar").addClass("disabled");
    $("#ver_completo").addClass("disabled");
  }

  $('.jq_image_zoom').zoom({ on:'grab' }); 

}

function calc_total() {

  $(".nro_comprobante").html("Núm. Comprobante");
  $( "#num_documento" ).rules( "remove","required" );

  var total         = es_numero($('#precio_parcial').val()) == true? parseFloat($('#precio_parcial').val()) : 0;
  var val_igv       = es_numero($('#val_igv').val()) == true? parseFloat($('#val_igv').val()) : 0;
  var subtotal      = 0; 
  var igv           = 0;

  console.log(total, val_igv); console.log($('#precio_parcial').val(), $('#val_igv').val()); console.log('----------');

  if ($("#tipo_comprobante").select2("val")=="" || $("#tipo_comprobante").select2("val")==null) {
    $("#subtotal").val(redondearExp(total));
    $("#igv").val("0.00"); 
    $("#val_igv").val("0.00"); 
    $("#tipo_gravada").val("NO GRAVADA"); $(".tipo_gravada").html("(NO GRAVADA)"); 
    $("#val_igv").prop("readonly",true);

    $(".div_ruc").hide(); $(".div_razon_social").hide();
    $("#num_documento").val(""); $("#razon_social").val("");
    
  }else if ($("#tipo_comprobante").select2("val") =="Ninguno") {  
    $("#subtotal").val(redondearExp(total));
    $("#igv").val("0.00"); 
    $("#val_igv").val("0.00"); 
    $("#tipo_gravada").val("NO GRAVADA"); $(".tipo_gravada").html("(NO GRAVADA)"); 
    $("#val_igv").prop("readonly",true);
    $(".nro_comprobante").html("Núm. de Operación");

    $(".div_ruc").hide(); $(".div_razon_social").hide();
    $("#num_documento").val(""); $("#razon_social").val("");

  }else if ($("#tipo_comprobante").select2("val") =="Boleta") {  
    $("#subtotal").val(redondearExp(total));
    $("#igv").val("0.00"); 
    $("#val_igv").val("0.00"); 
    $("#tipo_gravada").val("NO GRAVADA"); $(".tipo_gravada").html("(NO GRAVADA)"); 
    $("#val_igv").prop("readonly",true);
    $(".nro_comprobante").html("Núm. de Operación");

    $(".div_ruc").show(); $(".div_razon_social").show();
    $("#num_documento").val(""); $("#razon_social").val("");
    $("#num_documento").rules("add", { required: true, messages: { required: "Campo requerido" } });


  }else if ($("#tipo_comprobante").select2("val") =="Factura") {  

    $("#val_igv").prop("readonly",false);    

    if (total == null || total == "") {
      $("#subtotal").val(0.00);
      $("#igv").val(0.00); 
      $("#tipo_gravada").val('NO GRAVADA'); $(".tipo_gravada").html("(NO GRAVADA)");
    } else if (val_igv == null || val_igv == "") {  
      $("#subtotal").val(redondearExp(total));
      $("#igv").val(0.00);
      $("#tipo_gravada").val('NO GRAVADA'); $(".tipo_gravada").html("(NO GRAVADA)");
    }else{     

      subtotal = quitar_igv_del_precio(total, val_igv, 'decimal');
      igv = total - subtotal;

      $("#subtotal").val(redondearExp(subtotal));
      $("#igv").val(redondearExp(igv));

      if (val_igv > 0 && val_igv <= 1) {
        $("#tipo_gravada").val('GRAVADA'); $(".tipo_gravada").html("(GRAVADA)")
      } else {
        $("#tipo_gravada").val('NO GRAVADA'); $(".tipo_gravada").html("(NO GRAVADA)");
      }    
    }
    $(".div_ruc").show(); $(".div_razon_social").show();

    $("#num_documento").rules("add", { required: true, messages: { required: "Campo requerido" } });

  } else {
    $("#subtotal").val(redondearExp(total));
    $("#igv").val("0.00");
    $("#val_igv").val("0.00"); 
    $("#tipo_gravada").val("NO GRAVADA"); $(".tipo_gravada").html("(NO GRAVADA)");
    $("#val_igv").prop("readonly",true);
    $(".div_ruc").hide(); $(".div_razon_social").hide();   
  }
  if (val_igv > 0 && val_igv <= 1) {
    $("#tipo_gravada").val('GRAVADA'); $(".tipo_gravada").html("(GRAVADA)")
  } else {
    $("#tipo_gravada").val('NO GRAVADA'); $(".tipo_gravada").html("(NO GRAVADA)");
  }

}

function select_comprobante() {
  if ($("#tipo_comprobante").select2("val") == "Factura") {
    $("#val_igv").prop("readonly",false);
    $("#val_igv").val(0.18); 
    $("#tipo_gravada").val('GRAVADA'); $(".tipo_gravada").html("(GRAVADA)");
  }else {
    $("#val_igv").val(0.00); 
    $("#tipo_gravada").val('NO GRAVADA'); $(".tipo_gravada").html("(NO GRAVADA)");
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

function mostrar(idcomida_extra ) {
  limpiar();

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-comidas_ex").modal("show")
  $("#tipo_comprobante").val("").trigger("change");
  $("#forma_pago").val("null").trigger("change");

  $.post("../ajax/comidas_extras.php?op=mostrar", { idcomida_extra : idcomida_extra  }, function (e, status) {

    e = JSON.parse(e); console.log(e);   
    if (e.status == true) {
        
      $("#tipo_comprobante").val(e.data.tipo_comprobante).trigger("change");
      $("#idcomida_extra").val(e.data.idcomida_extra);      
      $("#forma_pago").val(e.data.forma_de_pago).trigger("change");
      $("#nro_comprobante").val(e.data.numero_comprobante);
      $("#fecha").val(e.data.fecha_comida);
      $("#num_documento").val(e.data.ruc).trigger("change");
      $("#razon_social").val(e.data.razon_social);
      $("#direccion").val(e.data.direccion);
      $("#descripcion").val(e.data.descripcion);

      $("#precio_parcial").val(redondearExp(e.data.costo_parcial));
      $("#subtotal").val(redondearExp(e.data.subtotal));
      $("#igv").val(redondearExp(e.data.igv));
      $("#tipo_gravada").val(e.data.tipo_gravada)
      
      $("#val_igv").val(e.data.val_igv).trigger("change");
    
      if (e.data.comprobante == "" || e.data.comprobante == null  ) {

        $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
    
        $("#doc1_nombre").html('');
    
        $("#doc_old_1").val(""); $("#doc1").val("");
    
      } else {
    
        $("#doc_old_1").val(e.data.comprobante); 
    
        $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(e.data.comprobante)}</i></div></div>`);
        
        // cargamos la imagen adecuada par el archivo
        $("#doc1_ver").html(doc_view_extencion(e.data.comprobante,'comida_extra', 'comprobante', '100%', '210' ));
              
      }

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);
    }
  $('.jq_image_zoom').zoom({ on:'grab' });  

  }).fail( function(e) { ver_errores(e); } );
}

function total(fecha_1_r,fecha_2_r,id_proveedor_r,comprobante_r) {
  $("#total_monto").html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  var idproyecto=localStorage.getItem('nube_idproyecto');
  $("#total_monto").html("");
  $.post("../ajax/comidas_extras.php?op=total", { idproyecto: idproyecto, fecha_1:fecha_1_r, fecha_2:fecha_2_r, id_proveedor:id_proveedor_r, comprobante:comprobante_r  }, function (e, status) {

    e = JSON.parse(e); console.log(e);   
    if (e.status == true) {

      $("#total_monto").html('S/ '+ formato_miles(e.data.precio_parcial));

    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

function eliminar(idcomida_extra, tipo, numero) {

  crud_eliminar_papelera(
    "../ajax/comidas_extras.php?op=desactivar",
    "../ajax/comidas_extras.php?op=eliminar", 
    idcomida_extra, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del> ${tipo} N° ${numero} </del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla.ajax.reload(null, false);total(fecha_1_r,fecha_2_r,id_proveedor_r,comprobante_r); },
    false, 
    false, 
    false,
    false
  );

}



init();

$(function () {

  // Aplicando la validacion del select cada vez que cambie
  $("#forma_pago").on("change", function () { $(this).trigger("blur"); });
  $("#tipo_comprobante").on("change", function () { $(this).trigger("blur"); });

  $("#form-comidas_ex").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      forma_pago:       { required: true },
      tipo_comprobante: { required: true },
      fecha:            { required: true },
      precio_parcial:   {required: true},
      descripcion:      {required: true},
      val_igv:          { required: true, number: true, min:0, max:1 },
      // terms: { required: true },
    },
    messages: {

      forma_pago: { required: "Por favor seleccionar una forma de pago",},
      tipo_comprobante: { required: "Por favor seleccionar tipo comprobante", },
      fecha: { required: "Por favor ingrese una fecha", },
      precio_parcial:  { required: "Ingresar precio unitario", },
      descripcion:  { required: "Es necesario rellenar el campo descripción", },
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
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      guardaryeditar(e);      
    },

  });
  
  //agregando la validacion del select  ya que no tiene un atributo name el plugin
  $("#forma_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#tipo_comprobante").rules("add", { required: true, messages: { required: "Campo requerido" } });

});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..
no_select_tomorrow("#fecha")

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

  listar(fecha_1, fecha_2, id_proveedor, comprobante);
}

function extrae_ruc() {
  if ($('#idproveedor').select2("val") == null || $('#idproveedor').select2("val") == '') { }  else{    
    var ruc = $('#idproveedor').select2('data')[0].element.attributes.ruc.value; //console.log(ruc);
    $('#ruc_proveedor').val(ruc);
  }
}



