var tabla_categorias_af;

//Función que se ejecuta al inicio
function init() {
  listar_c_insumos_af();

  //Guardar  
  $("#guardar_registro_categoria_af").on("click", function (e) { $("#submit-form-cateogrias-af").submit(); });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

//Función limpiar 
function limpiar_c_af() {
  $("#guardar_registro_categoria_af").html('Guardar Cambios').removeClass('disabled');
  $("#idcategoria_producto").val("");
  $("#nombre_categoria").val(""); 
  $("#descripcion_cat").val(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función listar_c_insumos_af 
function listar_c_insumos_af () {

  tabla_categorias_af=$('#tabla-categorias-af').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5','pdf'],
    ajax:{
      url: '../ajax/categoria_p.php?op=listar_c_producto',
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: #
      if (data[1] != '') { $("td", row).eq(1).addClass("text-nowrap text-center"); }
      // columna: #
      if (data[4] != '') { $("td", row).eq(4).addClass("text-center"); }

    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 5,//Paginación
    order: [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();
}

//Función para guardar o editar

function guardaryeditar_c_insumos_af(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-categoria-af")[0]);
 
  $.ajax({
    url: "../ajax/categoria_p.php?op=guardaryeditar_c_insumos_af",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      e = JSON.parse(e);  console.log(e);
      if ( e.status == true) {

				Swal.fire("Correcto!", "Clasificación registrado correctamente.", "success");	 	 

	      tabla_categorias_af.ajax.reload(null, false);
         
				limpiar_c_af();

        $("#modal-agregar-categorias-af").modal("hide");

        $("#guardar_registro_categoria_af").html('Guardar Cambios').removeClass('disabled');

			}else{
				ver_errores(e);
			}
    },
    xhr: function () {

      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener("progress", function (evt) {

        if (evt.lengthComputable) {

          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_categoria_af").css({"width": percentComplete+'%'});

          $("#barra_progress_categoria_af").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_categoria_af").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_categoria_af").css({ width: "0%",  });
      $("#barra_progress_categoria_af").text("0%");
    },
    complete: function () {
      $("#barra_progress_categoria_af").css({ width: "0%", });
      $("#barra_progress_categoria_af").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar_c_insumos_af (idcategoria_producto ) {

  console.log(idcategoria_producto);

  $("#cargando-11-fomulario").hide();
  $("#cargando-12-fomulario").show();

  limpiar_c_af();

  $("#modal-agregar-categorias-af").modal("show")

  $.post("../ajax/categoria_p.php?op=mostrar", {idcategoria_producto : idcategoria_producto }, function (e, status) {

    e = JSON.parse(e);  console.log(e);

    if (e.status) {
      $("#idcategoria_producto").val(e.data.idcategoria_producto );
      $("#nombre_categoria").val(e.data.nombre);
      $("#descripcion_cat").val(e.data.descripcion); 

      $("#cargando-11-fomulario").show();
      $("#cargando-12-fomulario").hide();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );

}


//Función para desactivar y eliminar registros
function eliminar_c_insumos_af(idcategoria_producto, nombre ) {
  crud_eliminar_papelera(
    "../ajax/categoria_p.php?op=desactivar",
    "../ajax/categoria_p.php?op=delete", 
    idcategoria_producto, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){  tabla_categorias_af.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );

}


init();

$(function () {

  $("#form-categoria-af").validate({
    rules: { 
      nombre_categoria: { required: true } 
    },
    messages: {
      nombre_categoria: { required: "Campo requerido", },
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
      guardaryeditar_c_insumos_af(e);      
    },
  });
});

