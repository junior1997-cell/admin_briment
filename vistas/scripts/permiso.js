var tabla;
var tabla2;

//Función que se ejecuta al inicio
function init() {
  listar();

  $("#bloc_Accesos").addClass("menu-open bg-color-191f24");

  $("#mAccesos").addClass("active");

  $("#lPermiso").addClass("active");
}

//Función Listar
function listar() {
  tabla = $("#tabla-permiso").dataTable({
    responsive: true,
    lengthMenu: [ [-1, 5, 10, 25, 75, 100, 200], ["Todos", 5, 10, 25, 75, 100, 200], ], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: ["copyHtml5", "excelHtml5", "pdf"],
    ajax: {
      url: "../ajax/permiso.php?op=listar",
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: #0
      if (data[0] != "") { $("td", row).eq(0).addClass("text-center"); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
  }).DataTable();
}

function mostrar_usuarios(id) {
  $("#modal-ver-usuarios").modal("show");

  tabla2 = $("#tabla-usuarios").dataTable({
    responsive: true,
    lengthMenu: [[-1, 5, 10, 25, 75, 100, 200],["Todos", 5, 10, 25, 75, 100, 200],], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [],
    ajax: {
      url: "../ajax/permiso.php?op=listar_usuario&id=" + id,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: #0
      if (data[0] != "") {
        $("td", row).eq(0).addClass("text-center");
      }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
  }).DataTable();
}

init();
