var tabla;

//Función que se ejecuta al inicio
function init() {
  
  $("#bloc_Recurso").addClass("menu-open bg-color-191f24");

  $("#mRecurso").addClass("active");

  $("#lAllProducto").addClass("active");
  $('.lAllProducto-img').attr('src', '../dist/svg/negro-abono-ico.svg');
  lista_de_items();
  tbla_principal();
  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════

  lista_select2("../ajax/ajax_general.php?op=select2UnidaMedida", '#unidad_medida', null);
  lista_select2("../ajax/ajax_general.php?op=select2laboratorio", '#laboratorio', null);
  lista_select2("../ajax/ajax_general.php?op=select2presentacion", '#presentacion', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro").on("click", function (e) { $("#submit-form-producto").submit(); });

   // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  $("#unidad_medida").select2({ theme: "bootstrap4", placeholder: "Seleccinar una unidad", allowClear: true, });
  $("#laboratorio").select2({ theme: "bootstrap4", placeholder: "Seleccinar laboratorio", allowClear: true, });
  $("#presentacion").select2({ theme: "bootstrap4", placeholder: "Seleccinar presentacion", allowClear: true, });
// ══════════════════════════════════════ I N I T I A L I Z E   N U M B E R   F O R M A T ══════════════════════════════════════
  //$('#precio_unitario').number( true, 2 );
  //$('#precio_unitario').number( true, 2 );
  //formato_miles_input('#precio_unitario');
  $('.jq_image_zoom').zoom({ on:'grab' });
  // Formato para telefono
  $("[data-mask]").inputmask();
}
//presentacion,laboratorio
function templateColor (state) {
  if (!state.id) { return state.text; }
  var color_bg = state.title != '' ? `${state.title}`: '#ffffff00';   
  var $state = $(`<span ><b style="background-color: ${color_bg}; color: ${color_bg};" class="mr-2"><i class="fas fa-square"></i><i class="fas fa-square"></i></b>${state.text}</span>`);
  return $state;
}
// abrimos el navegador de archivos
$("#foto1_i").click(function () { $("#foto1").trigger("click"); });
$("#foto1").change(function (e) { addImage(e, $("#foto1").attr("id"), "../dist/img/default/img_defecto_producto.jpg"); });

function foto1_eliminar() {
  $("#foto1").val("");
  $("#foto1_i").attr("src", "../dist/img/default/img_defecto_producto.jpg");
  $("#foto1_nombre").html("");
}

//Función limpiar
function limpiar_form_producto() {

  $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');
  $('.name-modal-title-agregar').html('Agregar Producto');

  //Mostramos los Materiales
  $("#idproducto").val("");  
  $("#codigo").val("");  
  $("#nombre_producto").val(""); 
  $("#laboratorio").val("null").trigger("change");
  $("#presentacion").val("null").trigger("change");
  $("#unidad_medida").val("null").trigger("change");
  $("#sub_total").val(""); 
  $("#igv").val(""); 
  $("#precio_unitario").val('0.00');
  $("#lote").val("");
  $("#descripcion").val(""); 

  $("#foto1_i").attr("src", "../dist/img/default/img_defecto_producto.jpg");
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

  $.post("../ajax/producto.php?op=lista_de_categorias", function (e, status) {
    
    e = JSON.parse(e); console.log(e);
    // e.data.idtipo_tierra
    if (e.status) {
      var data_html = '';

      e.data.forEach((val, index) => {
        data_html = data_html.concat(`
        <li class="nav-item">
          <a class="nav-link" onclick="delay(function(){tbla_principal('${val.idcategoria_producto}')}, 50 );" id="tabs-for-activo-fijo-tab" data-toggle="pill" href="#tabs-for-activo-fijo" role="tab" aria-controls="tabs-for-activo-fijo" aria-selected="false">${val.nombre}</a>
        </li>`);
      });

      $(".lista-items").html(`
        <li class="nav-item">
          <a class="nav-link active" onclick="delay(function(){tbla_principal('todos')}, 50 );" id="tabs-for-activo-fijo-tab" data-toggle="pill" href="#tabs-for-activo-fijo" role="tab" aria-controls="tabs-for-activo-fijo" aria-selected="true">Todos</a>
        </li>
        ${data_html}
      `); 
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

//Función Listar
function tbla_principal(idcategoria = 'todos') {
  

  tabla = $("#tabla-materiales").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    // dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i>', className: "btn bg-gradient-info", action: function ( e, dt, node, config ) { tabla.ajax.reload(); toastr_success('Exito!!', 'Actualizando tabla', 400); } },
      { extend: 'copyHtml5', exportOptions: { columns: [0,10,11,4,5,6,7,8,9], }, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray", footer: true,  }, 
      { extend: 'excelHtml5', exportOptions: { columns: [0,10,11,4,5,6,7,8,9], }, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success", footer: true,  }, 
      { extend: 'pdfHtml5', exportOptions: { columns: [0,10,11,4,5,6,7,8,9], }, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger", footer: false, orientation: 'landscape', pageSize: 'LEGAL',  },
      { extend: "colvis", text: `Columnas`, className: "btn bg-gradient-gray", exportOptions: { columns: "th:not(:last-child)", }, },
    ],
    ajax: {
      url: `../ajax/producto.php?op=tbla_principal&idcategoria=${idcategoria}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {    
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: opciones
      if (data[1] != '') { $("td", row).eq(1).addClass("text-center text-nowrap"); }
      // columna: code
      if (data[2] != '') { $("td", row).eq(2).addClass("text-center"); }
      // columna: precio unitario
      if (data[7] != '') { $("td", row).eq(7).addClass("text-nowrap text-center"); }
      // columna: precio sin igv
      if (data[8] != '') { $("td", row).eq(8).addClass("text-nowrap text-center"); }
      // columna: monto igv
      if (data[9] != '') { $("td", row).eq(9).addClass("text-nowrap"); }
      // columna: precio total
      if (data[10] != '') { $("td", row).eq(10).addClass("text-nowrap"); }
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
      { targets: [10,11], visible: false, searchable: true, },   
      { targets: [6], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
    ],
  }).DataTable();
}


//Función para guardar o editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-producto")[0]);

  $.ajax({
    url: "../ajax/producto.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {         

          tabla.ajax.reload(null, false);

          limpiar_form_producto();

          Swal.fire("Correcto!", "Insumo guardado correctamente", "success");

          $("#modal-agregar-producto").modal("hide");          
          
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

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
      $("#barra_progress").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress").css({ width: "0%", });
      $("#barra_progress").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar(idproducto) {
  limpiar_form_producto(); //console.log(idproducto);

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $('.name-modal-title-agregar').html('Editar Producto');

  $("#modal-agregar-producto").modal("show");

  $.post("../ajax/producto.php?op=mostrar", { 'idproducto': idproducto }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);

    if (e.status == true) {
      $("#idproducto").val(e.data.idproducto);
      $("#nombre_producto").val(e.data.nombre);
      $("#laboratorio").val(e.data.laboratorio).trigger("change");  
      $("#descripcion").val(e.data.descripcion);
      $("#stock").val(e.data.stock); 
      $("#contenido_neto").val(e.data.contenido_neto);  
      $('#precio_unitario').val(parseFloat(e.data.precio_unitario));
      
      $("#unidad_medida").val(e.data.idunidad_medida).trigger("change");
      
      $("#categoria_producto").val(e.data.idcategoria_producto).trigger("change");

      if (e.data.imagen != "") {        
        $("#foto1_i").attr("src", "../dist/docs/producto/img_perfil/" + e.data.imagen);  
        $("#foto1_actual").val(e.data.imagen);
      }

      $('.jq_image_zoom').zoom({ on:'grab' });
      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

// ver detallles del registro
function verdatos(idproducto){

  $(".tooltip").remove("show");

  $('#datosinsumo').html(`<div class="row"><div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br/><br/><h4>Cargando...</h4></div></div>`);

  var verdatos=''; 

  var imagen_perfil =''; var btn_imagen_perfil = '';

  $("#modal-ver-insumo").modal("show");

  $.post("../ajax/producto.php?op=mostrar", { 'idproducto': idproducto }, function (e, status) {

    e = JSON.parse(e);  //console.log(e); 
    
    if (e.status == true) {     
    
      if (e.data.imagen != '') {

        imagen_perfil=`<img src="../dist/docs/producto/img_perfil/${e.data.imagen}" onerror="this.src='../dist/svg/404-v2.svg';" alt="" class="img-thumbnail w-150px">`
        
        btn_imagen_perfil=`
        <div class="row mt-1">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/producto/img_perfil/${e.data.imagen}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/producto/img_perfil/${e.data.imagen}" download="PERFIL - ${removeCaracterEspecial(e.data.nombre_producto)}"> <i class="fas fa-download"></i></a>
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
                  <td> <b>laboratorio: </b> ${e.data.laboratorio}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Categoria</th>
                  <td>${e.data.categoria}</td>
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
                  <th>Contenido Neto:</th>
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
    
      $("#datosinsumo").html(retorno_html);
      $('.jq_image_zoom').zoom({ on:'grab' });
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

function ver_perfil(file, nombre) {
  $('.foto-insumo').html(nombre);
  $(".tooltip").removeClass("show").addClass("hidde");
  $("#modal-ver-perfil-insumo").modal("show");
  $('#perfil-insumo').html(`<span class="jq_image_zoom"><img class="img-thumbnail" src="${file}" onerror="this.src='../dist/svg/404-v2.svg';" alt="Perfil" width="100%"></span>`);
  $('.jq_image_zoom').zoom({ on:'grab' });
}

//Función para desactivar registros
function eliminar(idproducto, nombre) {

  crud_eliminar_papelera(
    "../ajax/producto.php?op=desactivar",
    "../ajax/producto.php?op=eliminar", 
    idproducto, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla.ajax.reload(null, false) },
    false, 
    false, 
    false,
    false
  );
}

function cal_igv_subtotal() {

  var precio_unit = $("#precio_unitario").val();
  var igv, subtotal;

  if (precio_unit ==null || precio_unit=='0') {
    
  }else{
    
    subtotal = precio_unit/1.18 ;
    igv = precio_unit-subtotal;
    $("#igv").val(redondearExp(igv));
    $("#sub_total").val( redondearExp(subtotal) );

  }
  
}


init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {   

  $('#laboratorio').on('change', function() { $(this).trigger('blur'); });
  $('#presentacion').on('change', function() { $(this).trigger('blur'); });
  $('#Unidad_medida').on('change', function() { $(this).trigger('blur'); });

  $("#form-producto").validate({
    rules: {
      nombre_producto:    { required: true, minlength:3, maxlength:200},
      laboratorio:        { required: true },
      presentacion:       { required: true },
      unidad_medida:      { required: true },
      // contenido_neto:     {  min: 1, number: true },
      precio_unitario:    { required: true },
      descripcion:        { minlength: 4 },
      
    },
    messages: {
      nombre_producto:    { required: "Por favor ingrese nombre", minlength:"Minimo 3 caracteres", maxlength:"Maximo 200 caracteres" },
      laboratorio:        { required: "Campo requerido" },
      presentacion:       { required: "Campo requerido", },
      unidad_medida:      { required: "Campo requerido" },
      // contenido_neto:     { minlength: "Minimo 3 caracteres", number:"Tipo nùmerico" },
      precio_unitario:    { required: "Ingresar precio compra", },      
      descripcion:        { minlength: "Minimo 4 caracteres" },
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

  $('#laboratorio').rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $('#presentacion').rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $('#Unidad_medida').rules('add', { required: true, messages: {  required: "Campo requerido" } });

});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

