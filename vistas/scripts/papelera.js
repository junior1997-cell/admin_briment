var tabla_principal; 

//Función que se ejecuta al inicio
function init() {

  // $("#bloc_PagosTrabajador").addClass("menu-open");

  $("#mPapelera").addClass("active");

  // $("#lPagosAdministrador").addClass("active");

  listar_tbla_principal(localStorage.getItem('nube_idproyecto'));

} 

//Función Listar - tabla principal
function listar_tbla_principal(nube_idproyecto) {

  tabla_principal = $('#tabla-principal').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }, "colvis"],
    ajax:{
      url: `../ajax/papelera.php?op=listar_tbla_principal&nube_idproyecto=${nube_idproyecto}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: sueldo mensual
      if (data[0] != '') { $("td", row).eq(0).addClass('text-nowrap'); }           
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();  
}


function eliminar_permanente(nombre_tabla, nombre_id_tabla, id_tabla) { 

  Swal.fire({
    title: "¿Está Seguro de Eliminar Permanente?",
    html: "Al Eliminarlo, no podra recuperarlo.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#6c757d",    
    confirmButtonText: `<i class="fas fa-skull-crossbones"></i> Eliminar`,
    showLoaderOnConfirm: true,
    preConfirm: (input) => {       
      return fetch(`../ajax/papelera.php?op=eliminar_permanente&nombre_tabla=${nombre_tabla}&nombre_id_tabla=${nombre_id_tabla}&id_tabla=${id_tabla}`).then(response => {
        //console.log(response);
        if (!response.ok) { throw new Error(response.statusText) }
        return response.json();
      }).catch(error => { Swal.showValidationMessage(`<b>Solicitud fallida:</b> ${error}`); })
    },
    allowOutsideClick: () => !Swal.isLoading()
  }).then((result) => {
    console.log(result);
    if (result.isConfirmed) {
      if (result.value.status) {
        Swal.fire("Anulado!", "Tu registro ha sido ELIMINADO PERMANENTEMENTE.", "success");
        tabla_principal.ajax.reload(null, false);
        $(".tooltip").removeClass("show").addClass("hidde");
      }else{
        ver_errores(result.value);
      }
    }
  });
}

function recuperar(nombre_tabla, nombre_id_tabla, id_tabla) {

  Swal.fire({
    title: "¿Está Seguro de Recuperar este registro?",
    html: "Al Recuperarlo, podras ver este registro en tu modulo correspondiente.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#6c757d",    
    confirmButtonText: `Si, Recuperar!`,
    showLoaderOnConfirm: true,
    preConfirm: (input) => {       
      return fetch(`../ajax/papelera.php?op=recuperar&nombre_tabla=${nombre_tabla}&nombre_id_tabla=${nombre_id_tabla}&id_tabla=${id_tabla}`).then(response => {
        //console.log(response);
        if (!response.ok) { throw new Error(response.statusText) }
        return response.json();
      }).catch(error => { Swal.showValidationMessage(`<b>Solicitud fallida:</b> ${error}`); })
    },
    allowOutsideClick: () => !Swal.isLoading()
  }).then((result) => {
    console.log(result);
    if (result.isConfirmed) {
      if (result.value.status) {
        Swal.fire("ReActivado!", "Tu registro ha sido RECUPERADO.", "success");
        tabla_principal.ajax.reload(null, false);
        $(".tooltip").removeClass("show").addClass("hidde");
      }else{
        ver_errores(result.value);
      }
    }
  });

}

init();
// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..





