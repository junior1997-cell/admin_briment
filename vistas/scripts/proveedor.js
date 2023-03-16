var tabla;

//Función que se ejecuta al inicio
function init() {
  
  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

 listar(localStorage.getItem('nube_idproyecto'));

  // $("#bloc_Accesos").addClass("menu-open");
    //Mostramos los proveedores
    $.post("../ajax/proveedor.php?op=select2_proveedor", function (r) { $("#proveedor").html(r); });

  $("#mProveedor").addClass("active");

  // $("#lproveedor").addClass("active");

  $("#guardar_registro").on("click", function (e) {
    

    $("#submit-form-proveedor").submit();
  });

  // Formato para telefono
  $("[data-mask]").inputmask();

  //Initialize Select2 Elements
  $("#proveedor").select2({
    theme: "bootstrap4",
    placeholder: "Selecione proveedor",
    allowClear: true,
  });
  
  $("#proveedor").val("null").trigger("change");

}

function seleccion() {

  if ($("#proveedor").select2("val") == null && $("#proveedor_old").val() == null) {

    $("#proveedor_validar").show(); //console.log($("#proveedor").select2("val") + ", "+ $("#proveedor_old").val());

  } else {

    $("#proveedor_validar").hide();
  }
}

//Función limpiar
function limpiar() {
  //Mostramos los proveedores
  $.post("../ajax/proveedor.php?op=select2_proveedor", function (r) { $("#proveedor").html(r); });

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));
  $("#idproveedor_proyecto").val(""); 
  $("#proveedor").val("null").trigger("change"); 
  $("#proveedor_old").val(""); 
  
  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar
function listar( nube_idproyecto ) {

  tabla=$('#tabla-proveedores').dataTable({
    "responsive": true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
      url: '../ajax/proveedor.php?op=listar&nube_idproyecto='+nube_idproyecto,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    "bDestroy": true,
    "iDisplayLength": 5,//Paginación
    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
  }).DataTable();
}
//Función para guardar o editar

function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-proveedor")[0]);
 
  $.ajax({
    url: "../ajax/proveedor.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

				toastr.success('proveedor registrado correctamente')				 

	      tabla.ajax.reload(null, false);
         
				limpiar();

        $("#modal-agregar-proveedor").modal("hide");

			}else{

				toastr.error(datos)
			}
    },
  });
}

function mostrar(idproveedor_proyecto) {
  limpiar();
  
  $("#proveedor").val("").trigger("change"); 
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-proveedor").modal("show")

  $.post("../ajax/proveedor.php?op=mostrar", { idproveedor_proyecto: idproveedor_proyecto }, function (data, status) {

    data = JSON.parse(data);  console.log(data);   
    
    $("#proveedor").val(data.idproveedor).trigger("change"); 
    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#proveedor_old").val(data.idproveedor); 
    $("#idproveedor_proyecto").val(data.idproveedor_proyecto); 
    console.log(data.idproveedor);

  });
}

//Función para desactivar registros
function desactivar(idproveedor_proyecto) {
  Swal.fire({
    title: "¿Está Seguro de  Desactivar  el proveedor?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/proveedor.php?op=desactivar", { idproveedor_proyecto: idproveedor_proyecto }, function (e) {

        Swal.fire("Desactivado!", "Tu proveedor ha sido desactivado.", "success");
    
        tabla.ajax.reload(null, false);
      });      
    }
  });   
}

//Función para activar registros
function activar(idproveedor_proyecto) {
  Swal.fire({
    title: "¿Está Seguro de  Activar  el proveedor?",
    text: "Este proveedor tendra acceso al sistema",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/proveedor.php?op=activar", { idproveedor_proyecto: idproveedor_proyecto }, function (e) {

        Swal.fire("Activado!", "Tu proveedor ha sido activado.", "success");

        tabla.ajax.reload(null, false);
      });
      
    }
  });      
}

function ver_datos(idproveedor_proyecto) {
    //console.log(idproveedor_proyecto,idproyecto);
  $("#proveedor").val("").trigger("change"); 
  
  $('#datosproveedores').html('<div class="row" style="display: none;">'+
        '<div class="col-lg-12 text-center">'+
        '<i class="fas fa-spinner fa-pulse fa-6x"></i><br />'+
        '<br />'+
        '<h4>Cargando...</h4>'+
    '</div>'+
  '</div>');
  var verdatos='';

  $("#modal-ver-proveedores").modal("show");


  $.post("../ajax/proveedor.php?op=ver_datos", { idproveedor_proyecto: idproveedor_proyecto }, function (data, status) {

    data = JSON.parse(data); 
    console.log(data);
    verdatos=''+                                                                            
    '<div class="col-12">'+
      '<div class="card">'+
          '<div class="card-body ">'+
              '<table class="table table-hover table-bordered">'+          
                  '<tbody>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Empresa</th>'+
                          '<td>'+data.razon_social+'</td>'+ 
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Tipo Documento</th>'+
                          '<td>'+data.tipo_documento+'</td>'+ 
                       '</tr>'+
                       '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Número Documento</th>'+
                          '<td>'+data.ruc+'</td>'+ 
                        '</tr>'+
                        '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Teléfono</th>'+
                          '<td>'+data.telefono+'</td>'+ 
                        '</tr>'+
                        '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Dirección</th>'+
                          '<td>'+data.direccion+'</td>'+ 
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Banco</th>'+
                          '<td>'+data.nombre_banco+'</td>'+ 
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Cuenta Bancaria</th>'+
                          '<td>'+data.cuenta_bancaria+'</td>'+ 
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Cuenta Detracciones</th>'+
                          '<td>'+data.cuenta_detracciones+'</td>'+ 
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                      '<th>Titular Cuenta</th>'+
                      '<td>'+data.titular_cuenta+'</td>'+ 
                  '</tr>'+
                  '</tbody>'+
              '</table>'+
          '</div>'+
      '</div>'+
    '</div>';
  
  $("#datosproveedores").append(verdatos);   

  });
}

init();

$(function () {

  
  $.validator.setDefaults({

    submitHandler: function (e) {

      if ($("#proveedor").select2("val") == null && $("#proveedor_old").val() == null) {
        
        $("#proveedor_validar").show(); //console.log($("#proveedor").select2("val") + ", "+ $("#proveedor_old").val());
        console.log('holaaa""2222');
      } else {

        $("#proveedor_validar").hide();
       

        guardaryeditar(e);
      }
    },
  });

  $("#form-proveedor").validate({
    rules: {
      proveedor: { required: true }

      // terms: { required: true },
    },
    messages: {
      proveedor: {
        required: "Por favor selecione un proveedor", 
      },

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

      if ($("#proveedor").select2("val")== null  && $("#proveedor_old").val() == "") {
         
        $("#proveedor_validar").show(); //console.log($("#proveedor").select2("val") + ", "+ $("#proveedor_old").val());

      } else {

        $("#proveedor_validar").hide();
      }       
    },


  });
});





// Buscar Reniec SUNAT
function buscar_sunat_reniec() {
  $("#search").hide();

  $("#charge").show();

  let tipo_doc = $("#tipo_documento").val();

  let dni_ruc = $("#num_documento").val(); 
   
  if (tipo_doc == "DNI") {

    if (dni_ruc.length == "8") {

      $.post("../ajax/persona.php?op=reniec", { dni: dni_ruc }, function (data, status) {

        data = JSON.parse(data);

        console.log(data);

        if (data.success == false) {

          $("#search").show();

          $("#charge").hide();

          toastr.error("Es probable que el sistema de busqueda esta en mantenimiento o los datos no existe en la RENIEC!!!");

        } else {

          $("#search").show();

          $("#charge").hide();

          $("#nombre").val(data.nombres + " " + data.apellidoPaterno + " " + data.apellidoMaterno);

          toastr.success("Cliente encontrado!!!!");
        }
      });
    } else {

      $("#search").show();

      $("#charge").hide();

      toastr.info("Asegurese de que el DNI tenga 8 dígitos!!!");
    }
  } else {
    if (tipo_doc == "RUC") {

      if (dni_ruc.length == "11") {
        $.post("../ajax/persona.php?op=sunat", { ruc: dni_ruc }, function (data, status) {

          data = JSON.parse(data);

          console.log(data);
          if (data.success == false) {

            $("#search").show();

            $("#charge").hide();

            toastr.error("Datos no encontrados en la SUNAT!!!");
            
          } else {

            if (data.estado == "ACTIVO") {

              $("#search").show();

              $("#charge").hide();

              $("#nombre").val(data.razonSocial);

              data.nombreComercial == null ? $("#apellidos_nombre_comercial").val("-") : $("#apellidos_nombre_comercial").val(data.nombreComercial);
              
              data.direccion == null ? $("#direccion").val("-") : $("#direccion").val(data.direccion);
              // $("#direccion").val(data.direccion);
              toastr.success("Cliente encontrado");
            } else {

              toastr.info("Se recomienda no generar BOLETAS o Facturas!!!");

              $("#search").show();

              $("#charge").hide();

              $("#nombre").val(data.razonSocial);

              data.nombreComercial == null ? $("#apellidos_nombre_comercial").val("-") : $("#apellidos_nombre_comercial").val(data.nombreComercial);
              
              data.direccion == null ? $("#direccion").val("-") : $("#direccion").val(data.direccion);

              // $("#direccion").val(data.direccion);
            }
          }
        });
      } else {
        $("#search").show();

        $("#charge").hide();

        toastr.info("Asegurese de que el RUC tenga 11 dígitos!!!");
      }
    } else {
      if (tipo_doc == "CEDULA" || tipo_doc == "OTRO") {

        $("#search").show();

        $("#charge").hide();

        toastr.info("No necesita hacer consulta");

      } else {

        $("#tipo_doc").addClass("is-invalid");

        $("#search").show();

        $("#charge").hide();

        toastr.error("Selecione un tipo de documento");
      }
    }
  }
}
