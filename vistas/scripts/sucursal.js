var tabla_sucursal;

//Función que se ejecuta al inicio
function init() {
  
  $("#bloc_Recurso").addClass("menu-open");
  $("#mRecurso").addClass("active");

  listar_sucursal();

  $("#guardar_registro_sucursal").on("click", function (e) { $("#submit-form-sucursal").submit(); });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

//Función limpiar
function limpiar_sucursal() {
  $("#guardar_registro_sucursal").html('Guardar Cambios').removeClass('disabled');
  //Mostramos los Materiales
  $("#idsucursal").val("");
  $("#nombre_sucursal").val(""); 
  $("#codigo_sucursal").val(""); 
  $("#direccion_sucursal").val(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
  $(".form-control").removeClass('is-invalid');
}

//Función Listar
function listar_sucursal() {

  tabla_sucursal=$('#tabla-sucursal').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",//Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i> ', className: "btn bg-gradient-info", action: function ( e, dt, node, config ) { tabla_sucursal.ajax.reload(null, false); toastr_success('Exito!!', 'Actualizando tabla', 400); } },
      { extend: 'copyHtml5', exportOptions: { columns: [0,2,3,4], },footer: true, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray"  }, 
      { extend: 'excelHtml5', exportOptions: { columns: [0,2,3,4], }, footer: true, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success", }, 
      { extend: 'pdfHtml5', exportOptions: { columns: [0,2,3,4], }, footer: false, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger", } ,
    ],
    ajax:{
      url: '../ajax/sucursal.php?op=tbla_sucursal',
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
      if (data[4] != '') { $("td", row).eq(4).addClass("text-left"); }
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
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs:[
      // { targets: [3], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD-MM-YYYY'), },
    ]
  }).DataTable();
}

//Función para guardar o editar

function guardaryeditar_sucursal(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-sucursal")[0]);
 
  $.ajax({
    url: "../ajax/sucursal.php?op=guardar_y_editar_sucursal",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      e = JSON.parse(e);  console.log(e);  
      if (e.status == true) {

				Swal.fire("Correcto!", "Sucursal registrado correctamente.", "success");

	      tabla_sucursal.ajax.reload(null, false);
         
				limpiar_sucursal();
        $("#modal-agregar-sucursal").modal("hide");        

			}else{
        ver_errores(e);				 
			}

      $("#guardar_registro_sucursal").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_sucursal").css({"width": percentComplete+'%'});
          $("#barra_progress_sucursal").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_sucursal").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_sucursal").css({ width: "0%",  });
      $("#barra_progress_sucursal").text("0%");
    },
    complete: function () {
      $("#barra_progress_sucursal").css({ width: "0%", });
      $("#barra_progress_sucursal").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar_sucursal(idsucursal) {
  $(".tooltip").removeClass("show").addClass("hidde");
  $("#cargando-13-fomulario").hide();
  $("#cargando-14-fomulario").show();

  limpiar_sucursal();

  $("#modal-agregar-sucursal").modal("show")

  $.post("../ajax/sucursal.php?op=mostrar_sucursal", { idsucursal: idsucursal }, function (e, status) {

    e = JSON.parse(e);  console.log(e);  

    if (e.status) {
      $("#idsucursal").val(e.data.idsucursal);
      $("#nombre_sucursal").val(e.data.nombre); 
      $("#codigo_sucursal").val(e.data.codigo);
      $("#direccion_sucursal").val(e.data.direccion); 

      $("#cargando-13-fomulario").show();
      $("#cargando-14-fomulario").hide();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

//Función para desactivar registros
function eliminar_sucursal(idsucursal, nombre_sucursal) {
  crud_eliminar_papelera(
    "../ajax/sucursal.php?op=desactivar_sucursal",
    "../ajax/sucursal.php?op=eliminar_sucursal", 
    idsucursal, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre_sucursal}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){  tabla_sucursal.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );

}

init();

$(function () {

  $("#form-sucursal").validate({
    rules: {
      nombre_sucursal:    { required: true },
      codigo_sucursal:    { required: true },
      direccion_sucursal: { required: true },
    },
    messages: {
      nombre_sucursal:    { required: "Campo requerido.", },
      codigo_sucursal:    { required: "Campo requerido.", },
      direccion_sucursal: { required: "Campo requerido.", },
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
      guardaryeditar_sucursal(e);      
    },

  });
});

