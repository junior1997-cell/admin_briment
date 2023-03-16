var tabla_unidades_m;

//Función que se ejecuta al inicio
function init() {
  
  $("#bloc_Recurso").addClass("menu-open");

  $("#mRecurso").addClass("active");

  listar_unidades_m();

  $("#guardar_registro_unidad_m").on("click", function (e) { $("#submit-form-unidad-m").submit(); });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

//Función limpiar
function limpiar_unidades_m() {
  $("#guardar_registro_unidad_m").html('Guardar Cambios').removeClass('disabled');
  //Mostramos los Materiales
  $("#idunidad_medida").val("");
  $("#nombre_medida").val(""); 
  $("#abreviatura").val(""); 
  $("#descripcion_m").val(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
  $(".form-control").removeClass('is-invalid');
}

//Función Listar
function listar_unidades_m() {

  tabla_unidades_m=$('#tabla-unidades-m').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    // dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
    buttons: [
      { extend: 'copyHtml5', exportOptions: { columns: [0,2,3,4], },footer: true, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray"  }, 
      { extend: 'excelHtml5', exportOptions: { columns: [0,2,3,4], }, footer: true, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success", }, 
      { extend: 'pdfHtml5', exportOptions: { columns: [0,2,3,4], }, footer: false, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger", } ,
    ],
    ajax:{
      url: '../ajax/unidades_m.php?op=tbla_unidad_medida',
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

function guardaryeditar_unidades_m(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-unidad-m")[0]);
 
  $.ajax({
    url: "../ajax/unidades_m.php?op=guardaryeditar_unidades_m",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      e = JSON.parse(e);  console.log(e);  
      if (e.status == true) {

				Swal.fire("Correcto!", "Unidad de Medida registrado correctamente.", "success");

	      tabla_unidades_m.ajax.reload(null, false);
         
				limpiar_unidades_m();

        $("#modal-agregar-unidad-m").modal("hide");
        $("#guardar_registro_unidad_m").html('Guardar Cambios').removeClass('disabled');

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
      $("#guardar_registro_unidad_m").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
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

function mostrar_unidades_m(idunidad_medida) {
  $(".tooltip").removeClass("show").addClass("hidde");
  $("#cargando-3-fomulario").hide();
  $("#cargando-4-fomulario").show();

  limpiar_unidades_m();

  $("#modal-agregar-unidad-m").modal("show")

  $.post("../ajax/unidades_m.php?op=mostrar_unidades_m", { idunidad_medida: idunidad_medida }, function (e, status) {

    e = JSON.parse(e);  console.log(e);  

    if (e.status) {
      $("#idunidad_medida").val(e.data.idunidad_medida);
      $("#nombre_medida").val(e.data.nombre); 
      $("#abreviatura").val(e.data.abreviatura);
      $("#descripcion_m").val(e.data.descripcion); 

      $("#cargando-3-fomulario").show();
      $("#cargando-4-fomulario").hide();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

//Función para desactivar registros
function eliminar_unidades_m(idunidad_medida, nombre_medida) {
  crud_eliminar_papelera(
    "../ajax/unidades_m.php?op=desactivar_unidades_m",
    "../ajax/unidades_m.php?op=eliminar_unidades_m", 
    idunidad_medida, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre_medida}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){  tabla_unidades_m.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );

}

init();

$(function () {

  $("#form-unidad-m").validate({
    rules: {
      nombre_medida: { required: true }      // terms: { required: true },
    },
    messages: {
      nombre_medida: { required: "Campo requerido.", },
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
      guardaryeditar_unidades_m(e);      
    },

  });
});

