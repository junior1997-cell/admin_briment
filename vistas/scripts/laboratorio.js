var tabla_laboratorio_af;

//Función que se ejecuta al inicio
function init() {
  listar_tabla_laboratorio();

  //Guardar  
  $("#guardar_registro_laboratorio").on("click", function (e) { $("#submit-form-laboratorio").submit(); });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

//Función limpiar 
function limpiar_form_laboratorio() {
  $("#guardar_registro_laboratorio").html('Guardar Cambios').removeClass('disabled');
  $("#idlaboratorio").val("");
  $("#nombre_laboratorio").val(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función listar_tabla_laboratorio 
function listar_tabla_laboratorio () {

  tabla_laboratorio_af=$('#tabla-laboratorio-af').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",//Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i> ', className: "btn bg-gradient-info", action: function ( e, dt, node, config ) { tabla_tipo.ajax.reload(null, false); toastr_success('Exito!!', 'Actualizando tabla', 400); } },
      { extend: 'copyHtml5', exportOptions: { columns: [0,1,2,3,4], }, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray", footer: true,  }, 
      { extend: 'excelHtml5', exportOptions: { columns: [0,1,2,3,4], }, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success", footer: true,  }, 
      { extend: 'pdfHtml5', exportOptions: { columns: [0,1,2,3,4], }, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger", footer: false, orientation: 'landscape', pageSize: 'LEGAL',  },
    ],
    ajax:{
      url: '../ajax/laboratorio.php?op=listar_tabla_laboratorio',
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
      if (data[2] != '') { $("td", row).eq(2).addClass("text-left"); }
      // columna: #
      if (data[3] != '') { $("td", row).eq(3).addClass("text-center"); }

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

function guardar_y_editar_laboratorio(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-laboratorio")[0]);
 
  $.ajax({
    url: "../ajax/laboratorio.php?op=guardar_y_editar_laboratorio",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      e = JSON.parse(e);  console.log(e);
      if ( e.status == true) {

				Swal.fire("Correcto!", "Clasificación registrado correctamente.", "success");	 	 

	      tabla_laboratorio_af.ajax.reload(null, false);
         
				limpiar_form_laboratorio();

        $("#modal-agregar-laboratorio").modal("hide");

        $("#guardar_registro_laboratorio").html('Guardar Cambios').removeClass('disabled');

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
          $("#barra_progress_laboratorio").css({"width": percentComplete+'%'});

          $("#barra_progress_laboratorio").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_laboratorio").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_laboratorio").css({ width: "0%",  });
      $("#barra_progress_laboratorio").text("0%");
    },
    complete: function () {
      $("#barra_progress_laboratorio").css({ width: "0%", });
      $("#barra_progress_laboratorio").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar_editar_laboratorio (idlaboratorio ) {

  console.log(idlaboratorio);

  $("#cargando-7-fomulario").hide();
  $("#cargando-8-fomulario").show();

  limpiar_form_laboratorio();

  $("#modal-agregar-laboratorio").modal("show")

  $.post("../ajax/laboratorio.php?op=mostrar", {idlaboratorio : idlaboratorio }, function (e, status) {

    e = JSON.parse(e);  console.log(e);

    if (e.status) {
      $("#idlaboratorio").val(e.data.idlaboratorio );
      $("#nombre_laboratorio").val(e.data.nombre);

      $("#cargando-7-fomulario").show();
      $("#cargando-8-fomulario").hide();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );

}


//Función para desactivar y eliminar registros
function eliminar_laboratorio(idlaboratorio, nombre ) {
  crud_eliminar_papelera(
    "../ajax/laboratorio.php?op=desactivar",
    "../ajax/laboratorio.php?op=delete", 
    idlaboratorio, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){  tabla_laboratorio_af.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );

}


init();

$(function () {

  $("#form-laboratorio").validate({
    rules: { 
      nombre_laboratorio: { required: true } 
    },
    messages: {
      nombre_laboratorio: { required: "Campo requerido", },
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
      guardar_y_editar_laboratorio(e);      
    },
  });
});

