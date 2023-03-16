var tabla_tipo;

//Función que se ejecuta al inicio
function init() {
  
  $("#bloc_Recurso").addClass("menu-open");

  $("#mRecurso").addClass("active");

  //$("#lAllMateriales").addClass("active");

  listar_tipo();

  $("#guardar_registro_tipo").on("click", function (e) { $("#submit-form-tipo").submit(); });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

//Función limpiar
function limpiar_tipo() {
  $("#guardar_registro_tipo").html('Guardar Cambios').removeClass('disabled');
  //Mostramos los Materiales
  $("#idtipo_persona").val("");
  $("#nombre_tipo").val(""); 
  $("#descripcion_t").val(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar
function listar_tipo() {

  tabla_tipo=$('#tabla-tipo').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3], } }, { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,2], } } ,
    ],
    ajax:{
      url: '../ajax/tipo.php?op=listar_tipo',
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
      if (data[1] != '') { $("td", row).eq(1).addClass("text-nowrap"); }
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

function guardaryeditar_tipo(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-tipo")[0]);
 
  $.ajax({
    url: "../ajax/tipo.php?op=guardaryeditar_tipo",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      e = JSON.parse(e);  console.log(e);  
      if (e.status == true) {

				Swal.fire("Correcto!", "Tipo trabajado registrado correctamente.", "success");

	      tabla_tipo.ajax.reload(null, false);
         
				limpiar_tipo();

        $("#modal-agregar-tipo").modal("hide");        
        
        $("#guardar_registro_tipo").html('Guardar Cambios').removeClass('disabled');
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
          $("#barra_progress_tipo").css({"width": percentComplete+'%'});

          $("#barra_progress_tipo").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_tipo").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_tipo").css({ width: "0%",  });
      $("#barra_progress_tipo").text("0%");
    },
    complete: function () {
      $("#barra_progress_tipo").css({ width: "0%", });
      $("#barra_progress_tipo").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar_tipo(idtipo_persona) {
  $(".tooltip").removeClass("show").addClass("hidde");
  $("#cargando-7-fomulario").hide();
  $("#cargando-8-fomulario").show();

  limpiar_tipo();

  $("#modal-agregar-tipo").modal("show")

  $.post("../ajax/tipo.php?op=mostrar_tipo", { idtipo_persona: idtipo_persona }, function (e, status) {

    e = JSON.parse(e);  console.log(e);  

    if (e.status) {
      $("#idtipo_persona").val(e.data.idtipo_persona);
      $("#nombre_tipo").val(e.data.nombre);
      $("#descripcion_t").val(e.data.descripcion);

      $("#cargando-7-fomulario").show();
      $("#cargando-8-fomulario").hide();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

//Función para eliminar registros
function eliminar_tipo(idtipo_persona, nombre) {  
  
  crud_eliminar_papelera(
    "../ajax/tipo.php?op=desactivar_tipo",
    "../ajax/tipo.php?op=eliminar_tipo", 
    idtipo_persona, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){  tabla_tipo.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );

}

init();

$(function () {

  $("#form-tipo").validate({
    rules: {
      nombre_tipo: { required: true }      // terms: { required: true },
    },
    messages: {
      nombre_tipo: { required: "Por favor ingrese nombre.", },
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
      guardaryeditar_tipo(e);      
    },
  });
});

