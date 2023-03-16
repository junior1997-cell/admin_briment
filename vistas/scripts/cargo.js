var tabla_cargos;

//Función que se ejecuta al inicio
function init() {
  
  $("#bloc_Recurso").addClass("menu-open");

  $("#mRecurso").addClass("active");
  
  // $("#lBancoColor").addClass("active");

  listar_cargo();

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro_cargo").on("click", function (e) {$("#submit-form-cargo").submit(); });

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  
  // Formato para telefono
  $("[data-mask]").inputmask();
}

//Función limpiar
function limpiar_cargo() {
  $("#guardar_registro_cargo").html('Guardar Cambios').removeClass('disabled');
  $("#idcargo_trabajador").val("");
  $("#nombre_cargo").val(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función listar_cargo
function listar_cargo() {

  tabla_cargos=$('#tabla-cargo').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2], } }, { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,2,3], } } ,
    ],
    ajax:{
      url: '../ajax/cargo.php?op=listar_cargo',
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText); ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {    
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: #
      if (data[1] != '') { $("td", row).eq(1).addClass("text-nowrap text-center"); }
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
function guardaryeditar_cargo(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-cargo")[0]);
 
  $.ajax({
    url: "../ajax/cargo.php?op=guardaryeditar_cargo",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) { 
      e = JSON.parse(e);  console.log(e);            
      if (e.status == true) {

				Swal.fire("Correcto!", "Cargo registrado correctamente.", "success");	 

	      tabla_cargos.ajax.reload(null, false);
         
				limpiar_cargo();

        $("#modal-agregar-cargo").modal("hide");
        $("#guardar_registro_cargo").html('Guardar Cambios').removeClass('disabled');
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
          $("#barra_progress_cargo").css({"width": percentComplete+'%'});

          $("#barra_progress_cargo").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_cargo").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_cargo").css({ width: "0%",  });
      $("#barra_progress_cargo").text("0%");
    },
    complete: function () {
      $("#barra_progress_cargo").css({ width: "0%", });
      $("#barra_progress_cargo").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar_cargo(idcargo_trabajador) {
  $(".tooltip").removeClass("show").addClass("hidde");
  $("#cargando-9-fomulario").hide();
  $("#cargando-10-fomulario").show();

  limpiar_cargo();

  $("#modal-agregar-cargo").modal("show")
  $("#idtipo_trabjador_c").val("null").trigger("change");

  $.post("../ajax/cargo.php?op=mostrar", {idcargo_trabajador: idcargo_trabajador}, function (e, status) {

    e = JSON.parse(e);  console.log(e);  

    if (e.status) {
      $("#idcargo_trabajador").val(e.data.idcargo_trabajador);
      $("#nombre_cargo").val(e.data.nombre); 

      $("#cargando-9-fomulario").show();
      $("#cargando-10-fomulario").hide();
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

//Función para desactivar registros
function eliminar_cargo(idcargo_trabajador, nombre) {

  crud_eliminar_papelera(
    "../ajax/cargo.php?op=desactivar",
    "../ajax/cargo.php?op=eliminar", 
    idcargo_trabajador, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){  tabla_cargos.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );
 
}

init();

$(function () {

  $("#form-cargo").validate({
    rules: {
      
      nombre_cargo: { required: true }
    },
    messages: {
      
      nombre_cargo:       { required: "Campo requerido", },
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
      guardaryeditar_cargo(e);      
    },
  });
});

