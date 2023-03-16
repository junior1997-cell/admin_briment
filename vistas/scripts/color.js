var tabla;

//Función que se ejecuta al inicio
function init() {
  
  $("#bloc_Recurso").addClass("menu-open");

  $("#mRecurso").addClass("active");

  listar();

  $("#guardar_registro_color").on("click", function (e) { $("#submit-form-color").submit(); });

  //color picker with addon
  $('.my-colorpicker2').colorpicker();
  $('.my-colorpicker2').on('colorpickerChange', function(event) { 
    var color = '#e9ecef';
    if (event.color == null || event.color == '') { } else { color = event.color.toString(); }
    $('.my-colorpicker2 .fa-square').css('color', color); 
  });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

//Función limpiar
function limpiar() {
  $("#guardar_registro_color").html('Guardar Cambios').removeClass('disabled');
  //Mostramos los Materiales
  $("#idcolor").val("");
  $("#nombre_color").val("");
  $("#hexadecimal").val("").trigger('change');

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar
function listar() {

  tabla=$('#tabla-colores').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 6, 10, 25, 75, 100, 200,], ["Todos", 6, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3], } }, { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,2,3], } } ,
    ],
    ajax:{
      url: '../ajax/color.php?op=listar',
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 6,//Paginación
    order: [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();
}

//Función para guardar o editar
function guardaryeditar_color(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-color")[0]);
 
  $.ajax({
    url: "../ajax/color.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      e = JSON.parse(e);  console.log(e);  
      if (e.status == true) {
        Swal.fire("Correcto!", "Color registrado correctamente.", "success");

	      tabla.ajax.reload(null, false);
         
				limpiar();

        $("#modal-agregar-color").modal("hide");
        $("#guardar_registro_color").html('Guardar Cambios').removeClass('disabled');
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
          $("#barra_progress_color").css({"width": percentComplete+'%'});

          $("#barra_progress_color").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_color").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_color").css({ width: "0%",  });
      $("#barra_progress_color").text("0%");
    },
    complete: function () {
      $("#barra_progress_color").css({ width: "0%", });
      $("#barra_progress_color").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar(idcolor) {
  $(".tooltip").removeClass("show").addClass("hidde");
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  
  limpiar();

  $("#modal-agregar-color").modal("show")

  $.post("../ajax/color.php?op=mostrar", { idcolor: idcolor }, function (e, status) {

    e = JSON.parse(e);  console.log(e);  

    if (e.status) {
      $("#idcolor").val(e.data.idcolor);
      $("#nombre_color").val(e.data.nombre_color); 

      if (e.data.hexadecimal == null || e.data.hexadecimal == '') {  } else {
        $("#hexadecimal").val(e.data.hexadecimal).trigger('change');
      }       

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );
}

//Función para desactivar registros
function eliminar_color(idcolor, nombre) {

  crud_eliminar_papelera(
    "../ajax/color.php?op=desactivar",
    "../ajax/color.php?op=eliminar", 
    idcolor, 
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

init();

$(function () {

  $("#form-color").validate({
    rules: {
      nombre_color: { required: true }      // terms: { required: true },
    },
    messages: {
      nombre_color: {  required: "Campo requerido.", },
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
      guardaryeditar_color(e);      
    },

  });
});

