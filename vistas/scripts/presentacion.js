var tabla_presentacion;

//Función que se ejecuta al inicio
function init() {
  
  $("#bloc_Recurso").addClass("menu-open");

  $("#mRecurso").addClass("active");

  listar_presentacion();

  $("#guardar_registro_presentacion").on("click", function (e) { $("#submit-form-presentacion").submit(); });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

//Función limpiar
function limpiar_presentacion() {
  $("#guardar_registro_presentacion").html('Guardar Cambios').removeClass('disabled');
  //Mostramos los Materiales
  $("#idpresentacion").val("");
  $("#nombre_presentacion").val(""); 
  $("#descripcion_p").val(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
  $(".form-control").removeClass('is-invalid');
}

//Función Listar
function listar_presentacion() {

  tabla_presentacion=$('#tabla-presentacion').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",//Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i> ', className: "btn bg-gradient-info", action: function ( e, dt, node, config ) { tabla_presentacion.ajax.reload(null, false); toastr_success('Exito!!', 'Actualizando tabla', 400); } },
      { extend: 'copyHtml5', exportOptions: { columns: [0,2,3,4], },footer: true, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray"  }, 
      { extend: 'excelHtml5', exportOptions: { columns: [0,2,3,4], }, footer: true, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success", }, 
      { extend: 'pdfHtml5', exportOptions: { columns: [0,2,3,4], }, footer: false, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger", } ,
    ],
    ajax:{
      url: '../ajax/presentacion.php?op=tbla_presentacion',
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
      //if (data[2] != '') { $("td", row).eq(2).addClass("text-center"); }
      // columna: #
      if (data[3] != '') { $("td", row).eq(3).addClass("text-center"); }
      // columna: #
      if (data[4] != '') { $("td", row).eq(4).addClass("text-center"); }
      // columna: #
      if (data[5] != '') { $("td", row).eq(5).addClass("text-center"); }
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

function guardaryeditar_presentacion(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-presentacion")[0]);
 
  $.ajax({
    url: "../ajax/presentacion.php?op=guardaryeditar_presentacion",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      e = JSON.parse(e);  console.log(e);  
      if (e.status == true) {

				Swal.fire("Correcto!", "Presentación registrado correctamente.", "success");

	      tabla_presentacion.ajax.reload(null, false);
         
				limpiar_presentacion();

        $("#modal-agregar-presentacion").modal("hide");
        $("#guardar_registro_presentacion").html('Guardar Cambios').removeClass('disabled');

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
          $("#barra_progress_um").css({"width": percentComplete+'%'});

          $("#barra_progress_um").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_presentacion").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_um").css({ width: "0%",  });
      $("#barra_progress_um").text("0%");
    },
    complete: function () {
      $("#barra_progress_um").css({ width: "0%", });
      $("#barra_progress_um").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar_presentacion(idpresentacion) {
  $(".tooltip").removeClass("show").addClass("hidde");
  $("#cargando-9-fomulario").hide();
  $("#cargando-10-fomulario").show();

  limpiar_presentacion();

  $("#modal-agregar-presentacion").modal("show")

  $.post("../ajax/presentacion.php?op=mostrar_presentacion", { idpresentacion: idpresentacion }, function (e, status) {

    e = JSON.parse(e);  console.log(e);  

    if (e.status) {
      $("#idpresentacion").val(e.data.idpresentacion);
      $("#nombre_presentacion").val(e.data.nombre); 
      $("#descripcion_p").val(e.data.descripcion); 

      $("#cargando-9-fomulario").show();
      $("#cargando-10-fomulario").hide();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

//Función para desactivar registros
function eliminar_presentacion(idpresentacion, nombre_presentacion) {
  crud_eliminar_papelera(
    "../ajax/presentacion.php?op=desactivar_presentacion",
    "../ajax/presentacion.php?op=eliminar_presentacion", 
    idpresentacion, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre_presentacion}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){  tabla_presentacion.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );

}

init();

$(function () {

  $("#form-presentacion").validate({
    rules: {
      nombre_presentacion: { required: true }      // terms: { required: true },
    },
    messages: {
      nombre_presentacion: { required: "Campo requerido.", },
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
      guardaryeditar_presentacion(e);      
    },

  });
});

