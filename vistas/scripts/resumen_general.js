var tabla;
var tabla_1 = $("#tabla1_compras").DataTable();
var tabla_2 = $("#tabla2_maquinaria").DataTable();
var tabla_3 = $("#tabla3_equipo").DataTable();
var tabla_4 = $("#tabla4_transporte").DataTable();
var tabla_5 = $("#tabla5_hospedaje").DataTable();
var tabla_6 = $("#tabla6_comidas_ex").DataTable();
var tabla_7 = $("#tabla7_breaks").DataTable();
var tabla_8 = $("#tabla8_pension").DataTable();
var tabla_9 = $("#tabla9_per_adm").DataTable();
var tabla_10 = $("#tabla10_per_obr").DataTable();
var tabla_11 = $("#tabla11_otros_gastos").DataTable();
var tabla_12 = $("#tabla12_sub_contrato").DataTable();

var tabla_20 = $("#tabla20_all_sumas").DataTable();

var monto_compras = 0, pago_compras = 0, saldo_compras = 0;
var monto_serv_maq = 0, pago_serv_maq = 0, saldo_serv_maq = 0;
var monto_serv_equi = 0, pago_serv_equi = 0, saldo_serv_equi = 0;
var monto_transp = 0, pago_transp = 0, saldo_transp = 0;
var monto_hosped = 0, pago_hosped = 0, saldo_hosped = 0;
var monto_cextra = 0, pago_cextra = 0, saldo_cextra = 0;
var monto_break = 0, pago_break = 0, saldo_break = 0;
var monto_pension = 0, pago_pension = 0, saldo_pension = 0;
var monto_adm = 0, pago_adm = 0, saldo_adm = 0;
var monto_obrero = 0, pago_obrero = 0, saldo_obrero = 0;
var monto_otros_gastos = 0, pago_otros_gastos = 0, saldo_otros_gastos = 0;
var monto_sub_contrato = 0, pago_sub_contrato = 0, saldo_sub_contrato = 0;

var monto_all = 0;
var deposito_all = 0;
var saldo_all = 0;

$( ".export_all_table" ).click(function() { $('#tabla1_compras').tableExport({type:'excel'}); });

//Función que se ejecuta al inicio
function init() {   

  //Activamos el "aside"
  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#lresumen_general").addClass("active bg-green");

  //Mostramos los trabajadores
  $.post("../ajax/resumen_general.php?op=select2_trabajadores&idproyecto=" + localStorage.getItem("nube_idproyecto"), function (r) {
    $("#trabajador_filtro").html(r);
    $(".cargando_trabajador").html('Trabajador');
  }).fail( function(e) { ver_errores(e); } );

  $.post("../ajax/resumen_general.php?op=select2_proveedores", function (r) {
    $("#proveedor_filtro").html(r);    
    $(".cargando_proveedor").html('Proveedor');
  }).fail( function(e) { ver_errores(e); } );

  //Initialize: Select2 TRABAJDOR
  $("#trabajador_filtro").select2({ theme: "bootstrap4", placeholder: "Selecionar trabajador", allowClear: true, });

  //Initialize: Select2 PROVEEDOR
  $("#proveedor_filtro").select2({ theme: "bootstrap4", placeholder: "Selecionar proveedor", allowClear: true, });

  //Initialize: Select2 DEUDA
  $("#deuda_filtro").select2({ theme: "bootstrap4", placeholder: "Selecionar", allowClear: true, });

  filtros();
}

// TABLA - COMPRAS - -------------------------------------------------------
function tbla_compras(idproyecto, fecha_filtro_1, fecha_filtro_2, id_trabajador, id_proveedor, deuda) {   
  
  $.post("../ajax/resumen_general.php?op=tbla_compras", { 'idproyecto': idproyecto, 'fecha_filtro_1':fecha_filtro_1, 'fecha_filtro_2':fecha_filtro_2, 'id_proveedor':id_proveedor, 'deuda':deuda }, function (e, status) {
    
    e = JSON.parse(e); //console.log(e);    
     
    $("#monto_compras").html(formato_miles(e.data.t_monto.toFixed(2)));
    $("#pago_compras").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $("#saldo_compras").html(formato_miles(e.data.t_saldo.toFixed(2))); 

    monto_compras = parseFloat(e.data.t_monto.toFixed(2));
    pago_compras = parseFloat(e.data.t_pagos.toFixed(2));
    saldo_compras = parseFloat(e.data.t_saldo.toFixed(2));

    // acumulamos las sumas totales
    $(".monto_compras_all").html(formato_miles(e.data.t_monto.toFixed(2)));
    $(".pago_compras_all").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $(".saldo_compras_all").html(formato_miles(e.data.t_saldo.toFixed(2)));

    monto_all += parseFloat(e.data.t_monto);
    deposito_all += parseFloat(e.data.t_pagos);
    saldo_all += parseFloat(e.data.t_saldo);

    $("#monto_all").html(formato_miles(monto_all.toFixed(2)));
    $("#deposito_all").html(formato_miles(deposito_all.toFixed(2)));
    $("#saldo_all").html(formato_miles(saldo_all.toFixed(2)));

    tabla_1.destroy(); // Destruye las tablas de datos en el contexto actual.

    $('#compras').empty(); // Vacía en caso de que las columnas cambien

    tabla_1 = $('#tabla1_compras').dataTable({
      responsive: true,
      lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: [ { extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }],
      data: e.data.datatable,
      createdRow: function (row, data, ixdex) { 
        // columna: #
        if (data[0] != '') { $("td", row).eq(0).addClass("w-px-35 text-center"); }
        // columna: fecha
        if (data[2] != '') { $("td", row).eq(2).addClass("text-nowrap"); } 
        // columna: detalle
        if (data[4] != '') { $("td", row).eq(4).addClass("text-center"); } 
        // columna: montos
        if (data[5] != '') { $("td", row).eq(5).addClass("text-right"); }        
        // columna: depositos  
        if (data[6] != '') { $("td", row).eq(6).addClass("text-right"); }   
        // columna: saldos
        if (data[7] != '') {
          $("td", row).eq(7).addClass("text-right");
          var numero = quitar_formato_miles(data[7]);          
          if ( parseFloat(numero) < 0 ) {
            $("td", row).eq(7).addClass("text-right bg-danger");
          }else{              
            if ( parseFloat(numero) > 0 ) {
              $("td", row).eq(7).addClass("text-right bg-warning");
            } else {
              if ( parseFloat(numero) == 0 ) {
                $("td", row).eq(7).addClass("text-right bg-success");
              }
            }
          }
        }
      },
      language: {
        lengthMenu: "Mostrar: _MENU_ registros",
        buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
        sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
      },
      bDestroy: true,
      iDisplayLength: 5, //Paginación
      order: [[0, "asc"]], //Ordenar (columna,orden)
    }).DataTable();
    
    $('.cargando-compras').removeClass('bg-danger').addClass('backgff9100').html('Compras de Insumos');
    console.log(monto_compras, pago_compras, saldo_compras);

    tbla_maquinaria(idproyecto, fecha_filtro_1, fecha_filtro_2, id_trabajador, id_proveedor, deuda);
  }).fail( function(e) { ver_errores(e); } );
   
}

// TABLA - MAQUINARIA - -----------------------------------------------------
function tbla_maquinaria(idproyecto, fecha_filtro_1, fecha_filtro_2, id_trabajador, id_proveedor, deuda) {   
   
  $.post("../ajax/resumen_general.php?op=tbla_maquinaria", { 'idproyecto': idproyecto, 'fecha_filtro_1':fecha_filtro_1, 'fecha_filtro_2':fecha_filtro_2, 'id_proveedor':id_proveedor, 'deuda':deuda }, function (e, status) {
    
    e = JSON.parse(e); //console.log(e);
    
    $("#monto_serv_maq").html(formato_miles(e.data.t_monto.toFixed(2)));
    $("#pago_serv_maq").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $("#saldo_serv_maq").html(formato_miles(e.data.t_saldo.toFixed(2)));

    monto_serv_maq = e.data.t_monto.toFixed(2);
    pago_serv_maq = e.data.t_pagos.toFixed(2);
    saldo_serv_maq = e.data.t_saldo.toFixed(2);

    // acumulamos las sumas totales
    $(".monto_serv_maq_all").html(formato_miles(e.data.t_monto.toFixed(2)));
    $(".pago_serv_maq_all").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $(".saldo_serv_maq_all").html(formato_miles(e.data.t_saldo.toFixed(2)));

    monto_all += parseFloat(e.data.t_monto);
    deposito_all += parseFloat(e.data.t_pagos);
    saldo_all += parseFloat(e.data.t_saldo);

    $("#monto_all").html(formato_miles(monto_all.toFixed(2)));
    $("#deposito_all").html(formato_miles(deposito_all.toFixed(2)));
    $("#saldo_all").html(formato_miles(saldo_all.toFixed(2)));

    tabla_2.destroy(); // Destruye las tablas de datos en el contexto actual.

    $('#serv_maquinas').empty(); // Vacía en caso de que las columnas cambien

    tabla_2 = $("#tabla2_maquinaria").dataTable({
      responsive: true,
      lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }],
      data: e.data.datatable,
      createdRow: function (row, data, ixdex) { 
        // columna: #
        if (data[0] != '') { $("td", row).eq(0).addClass("w-px-35 text-center text-nowrap"); }
        // columna: fecha
        if (data[2] != '') { $("td", row).eq(2).addClass("text-nowrap"); } 
        // columna: detalle
        if (data[4] != '') { $("td", row).eq(4).addClass("text-center"); }  
        // columna: montos
        if (data[5] != '') { $("td", row).eq(5).addClass("text-right"); }        
        // columna: depositos  
        if (data[6] != '') { $("td", row).eq(6).addClass("text-right"); }   
        // columna: saldos
        if (data[7] != '') {
          $("td", row).eq(7).addClass("text-right");
          var numero = quitar_formato_miles(data[7]);          
          if ( parseFloat(numero) < 0 ) {
            $("td", row).eq(7).addClass("text-right bg-danger");
          }else{              
            if ( parseFloat(numero) > 0 ) {
              $("td", row).eq(7).addClass("text-right bg-warning");
            } else {
              if ( parseFloat(numero) == 0 ) {
                $("td", row).eq(7).addClass("text-right bg-success");
              }
            }
          }
        }
      },
      language: {
        lengthMenu: "Mostrar: _MENU_ registros",
        buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
        sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
      },
      bDestroy: true,
      iDisplayLength: 5, //Paginación
      order: [[0, "asc"]], //Ordenar (columna,orden)
    }).DataTable();

    $('.cargando-maquinas').removeClass('bg-danger').addClass('backgff9100').html('Servicios-Maquinaria');

    tbla_equipos(idproyecto, fecha_filtro_1, fecha_filtro_2, id_trabajador, id_proveedor, deuda);
  }).fail( function(e) { ver_errores(e); } );
   
}

// TABLA - EQUIPO
function tbla_equipos(idproyecto, fecha_filtro_1, fecha_filtro_2, id_trabajador, id_proveedor, deuda) {   
   
  $.post("../ajax/resumen_general.php?op=tbla_equipos", { 'idproyecto': idproyecto, 'fecha_filtro_1':fecha_filtro_1, 'fecha_filtro_2':fecha_filtro_2, 'id_proveedor':id_proveedor, 'deuda':deuda }, function (e, status) {
    e = JSON.parse(e); //console.log(data);    

    $("#monto_serv_equi").html(formato_miles(e.data.t_monto.toFixed(2)));
    $("#pago_serv_equi").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $("#saldo_serv_equi").html(formato_miles(e.data.t_saldo.toFixed(2)));

    monto_serv_equi = e.data.t_monto.toFixed(2);
    pago_serv_equi = e.data.t_pagos.toFixed(2);
    saldo_serv_equi = e.data.t_saldo.toFixed(2);

    // acumulamos las sumas totales
    $(".monto_serv_equi_all").html(formato_miles(e.data.t_monto.toFixed(2)));
    $(".pago_serv_equi_all").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $(".saldo_serv_equi_all").html(formato_miles(e.data.t_saldo.toFixed(2)));

    monto_all += parseFloat(e.data.t_monto);
    deposito_all += parseFloat(e.data.t_pagos);
    saldo_all += parseFloat(e.data.t_saldo);

    $("#monto_all").html(formato_miles(monto_all.toFixed(2)));
    $("#deposito_all").html(formato_miles(deposito_all.toFixed(2)));
    $("#saldo_all").html(formato_miles(saldo_all.toFixed(2)));

    tabla_3.destroy(); // Destruye las tablas de datos en el contexto actual.

    $('#serv_equipos').empty(); // Vacía en caso de que las columnas cambien

    tabla_3 = $("#tabla3_equipo").dataTable({
      responsive: true,
      lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }],
      data: e.data.datatable,
      createdRow: function (row, data, ixdex) { 
        // columna: #
        if (data[0] != '') { $("td", row).eq(0).addClass("w-px-35 text-center"); }
        // columna: Proveedor
        if (data[1] != '') { $("td", row).eq(1).removeClass("w-px-35 text-nowrap text-center"); }
        // columna: fecha
        if (data[2] != '') { $("td", row).eq(2).addClass("text-nowrap"); }
        // columna: detalle
        if (data[4] != '') { $("td", row).eq(4).addClass("text-center"); }
        // columna: montos
        if (data[5] != '') { $("td", row).eq(5).addClass("text-right"); }         
        // columna: depositos  
        if (data[6] != '') { $("td", row).eq(6).addClass("text-right"); }   
        // columna: saldos
        if (data[7] != '') {
          $("td", row).eq(7).addClass("text-right");
          var numero = quitar_formato_miles(data[7]);          
          if ( parseFloat(numero) < 0 ) {
            $("td", row).eq(7).addClass("text-right bg-danger");
          }else{              
            if ( parseFloat(numero) > 0 ) {
              $("td", row).eq(7).addClass("text-right bg-warning");
            } else {
              if ( parseFloat(numero) == 0 ) {
                $("td", row).eq(7).addClass("text-right bg-success");
              }
            }
          }
        }
      },
      language: {
        lengthMenu: "Mostrar: _MENU_ registros",
        buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
        sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
      },
      bDestroy: true,
      iDisplayLength: 5, //Paginación
      order: [[0, "asc"]], //Ordenar (columna,orden)
    }).DataTable();

    $('.cargando-equipos').removeClass('bg-danger').addClass('backgff9100').html('Servicios-Equipo');

    tbla_transportes(idproyecto, fecha_filtro_1, fecha_filtro_2, id_trabajador, id_proveedor, deuda);
  }).fail( function(e) { ver_errores(e); } );
  
}

// TABLA - TRANSPORTE - -------------------------------------------------------
function tbla_transportes(idproyecto, fecha_filtro_1, fecha_filtro_2, id_trabajador, id_proveedor, deuda) {
   
  $.post("../ajax/resumen_general.php?op=tbla_transportes", { 'idproyecto': idproyecto, 'fecha_filtro_1':fecha_filtro_1, 'fecha_filtro_2':fecha_filtro_2, 'id_proveedor':id_proveedor, 'deuda':deuda }, function (e, status) {
    
    e = JSON.parse(e); //console.log(data);   

    $("#monto_transp").html(formato_miles(e.data.t_monto.toFixed(2)));
    $("#pago_transp").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $("#saldo_transp").html(formato_miles(e.data.t_saldo.toFixed(2)));

    monto_transp = e.data.t_monto.toFixed(2); 
    pago_transp = e.data.t_pagos.toFixed(2); 
    saldo_transp = e.data.t_saldo.toFixed(2);

    // acumulamos las sumas totales
    $(".monto_transp_all").html(formato_miles(e.data.t_monto.toFixed(2)));
    $(".pago_transp_all").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $(".saldo_transp_all").html(formato_miles(e.data.t_saldo.toFixed(2)));

    monto_all += parseFloat(e.data.t_monto);
    deposito_all += parseFloat(e.data.t_pagos);
    saldo_all += parseFloat(e.data.t_saldo);

    $("#monto_all").html(formato_miles(monto_all.toFixed(2)));
    $("#deposito_all").html(formato_miles(deposito_all.toFixed(2)));
    $("#saldo_all").html(formato_miles(saldo_all.toFixed(2)));

    tabla_4.destroy(); // Destruye las tablas de datos en el contexto actual.

    $('#transportes').empty(); // Vacía en caso de que las columnas cambien

    tabla_4 = $("#tabla4_transporte").dataTable({
      responsive: true,
      lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }],
      data: e.data.datatable,
      createdRow: function (row, data, ixdex) {
        // columna: #
        if (data[0] != '') { $("td", row).eq(0).addClass("w-px-35 text-center text-nowrap"); }
        // columna: fecha
        if (data[2] != '') { $("td", row).eq(2).addClass("text-nowrap"); } 
        // columna: detalle
        if (data[4] != '') { $("td", row).eq(4).addClass("text-center"); }  
        // columna: montos
        if (data[5] != '') { $("td", row).eq(5).addClass("text-right"); }        
        // columna: depositos  
        if (data[6] != '') { $("td", row).eq(6).addClass("text-right"); }     
        // columna: saldos
        if (data[7] != '') {
          $("td", row).eq(7).addClass("text-right");
          var numero = quitar_formato_miles(data[7]);          
          if ( parseFloat(numero) < 0 ) {
            $("td", row).eq(7).addClass("text-right bg-danger");
          }else{              
            if ( parseFloat(numero) > 0 ) {
              $("td", row).eq(7).addClass("text-right bg-warning");
            } else {
              if ( parseFloat(numero) == 0 ) {
                $("td", row).eq(7).addClass("text-right bg-success");
              }
            }
          }
        }
      },
      language: {
        lengthMenu: "Mostrar: _MENU_ registros",
        buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
        sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
      },
      bDestroy: true,
      iDisplayLength: 5, //Paginación
      order: [[0, "asc"]], //Ordenar (columna,orden)
    }).DataTable();

    $('.cargando-transporte').removeClass('bg-danger').addClass('backgff9100').html('Transporte');

    tbla_hospedajes(idproyecto, fecha_filtro_1, fecha_filtro_2, id_trabajador, id_proveedor, deuda);
  }).fail( function(e) { ver_errores(e); } );
  
}

// TABLA - HOSPEDAJES - -------------------------------------------------------
function tbla_hospedajes(idproyecto, fecha_filtro_1, fecha_filtro_2, id_trabajador, id_proveedor, deuda) {
    
  $.post("../ajax/resumen_general.php?op=tbla_hospedajes", { 'idproyecto': idproyecto, 'fecha_filtro_1':fecha_filtro_1, 'fecha_filtro_2':fecha_filtro_2, 'id_proveedor':id_proveedor, 'deuda':deuda }, function (e, status) {
    
    e = JSON.parse(e); //console.log(data);    

    $("#monto_hosped").html(formato_miles(e.data.t_monto.toFixed(2)));
    $("#pago_hosped").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $("#saldo_hosped").html(formato_miles(e.data.t_saldo.toFixed(2)));

    monto_hosped = e.data.t_monto.toFixed(2); 
    pago_hosped = e.data.t_pagos.toFixed(2); 
    saldo_hosped = e.data.t_saldo.toFixed(2);

    // acumulamos las sumas totales
    $(".monto_hosped_all").html(formato_miles(e.data.t_monto.toFixed(2)));
    $(".pago_hosped_all").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $(".saldo_hosped_all").html(formato_miles(e.data.t_saldo.toFixed(2)));

    monto_all += parseFloat(e.data.t_monto);
    deposito_all += parseFloat(e.data.t_pagos);
    saldo_all += parseFloat(e.data.t_saldo);

    $("#monto_all").html(formato_miles(monto_all.toFixed(2)));
    $("#deposito_all").html(formato_miles(deposito_all.toFixed(2)));
    $("#saldo_all").html(formato_miles(saldo_all.toFixed(2)));

    tabla_5.destroy(); // Destruye las tablas de datos en el contexto actual.

    $('#hospedaje').empty(); // Vacía en caso de que las columnas cambien

    tabla_5 = $("#tabla5_hospedaje").dataTable({
      responsive: true,
      lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }],
      data: e.data.datatable,
      createdRow: function (row, data, ixdex) {          

        // columna: #
        if (data[0] != '') { $("td", row).eq(0).addClass("w-px-35 text-center text-nowrap"); }
        // columna: fecha
        if (data[2] != '') { $("td", row).eq(2).addClass("text-nowrap");  } 
        // columna: detalle
        if (data[4] != '') { $("td", row).eq(4).addClass("text-center"); }  
        // columna: montos
        if (data[5] != '') { $("td", row).eq(5).addClass("text-right"); }        
        // columna: depositos  
        if (data[6] != '') { $("td", row).eq(6).addClass("text-right"); }  
        // columna: saldos
        if (data[7] != '') {
          $("td", row).eq(7).addClass("text-right");
          var numero = quitar_formato_miles(data[7]);          
          if ( parseFloat(numero) < 0 ) {
            $("td", row).eq(7).addClass("text-right bg-danger");
          }else{              
            if ( parseFloat(numero) > 0 ) {
              $("td", row).eq(7).addClass("text-right bg-warning");
            } else {
              if ( parseFloat(numero) == 0 ) {
                $("td", row).eq(7).addClass("text-right bg-success");
              }
            }
          }
        }
      },
      language: {
        lengthMenu: "Mostrar: _MENU_ registros",
        buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
        sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
      },
      bDestroy: true,
      iDisplayLength: 5, //Paginación
      order: [[0, "asc"]], //Ordenar (columna,orden)
    }).DataTable();

    $('.cargando-hospedaje').removeClass('bg-danger').addClass('backgff9100').html('Hospedaje');

    tbla_comidas_extras(idproyecto, fecha_filtro_1, fecha_filtro_2, id_trabajador, id_proveedor, deuda);

  }).fail( function(e) { ver_errores(e); } );
  
}

// TABLA - COMIDAS EXTRAS - ----------------------------------------------------
function tbla_comidas_extras(idproyecto, fecha_filtro_1, fecha_filtro_2, id_trabajador, id_proveedor, deuda) {
   
  $.post("../ajax/resumen_general.php?op=tbla_comidas_extras", { 'idproyecto': idproyecto, 'fecha_filtro_1':fecha_filtro_1, 'fecha_filtro_2':fecha_filtro_2, 'id_proveedor':id_proveedor, 'deuda':deuda }, function (e, status) {
    
    e = JSON.parse(e); //console.log(data);    

    $("#monto_cextra").html(formato_miles(e.data.t_monto.toFixed(2)));
    $("#pago_cextra").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $("#saldo_cextra").html(formato_miles(e.data.t_saldo.toFixed(2)));

    monto_cextra = e.data.t_monto.toFixed(2); 
    pago_cextra = e.data.t_pagos.toFixed(2); 
    saldo_cextra = e.data.t_saldo.toFixed(2);

    // acumulamos las sumas totales
    $(".monto_cextra_all").html(formato_miles(e.data.t_monto.toFixed(2)));
    $(".pago_cextra_all").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $(".saldo_cextra_all").html(formato_miles(e.data.t_saldo.toFixed(2)));

    monto_all += parseFloat(e.data.t_monto);
    deposito_all += parseFloat(e.data.t_pagos);
    saldo_all += parseFloat(e.data.t_saldo);

    $("#monto_all").html(formato_miles(monto_all.toFixed(2)));
    $("#deposito_all").html(formato_miles(deposito_all.toFixed(2)));
    $("#saldo_all").html(formato_miles(saldo_all.toFixed(2)));

    tabla_6.destroy(); // Destruye las tablas de datos en el contexto actual.

    $('#comida_extra').empty(); // Vacía en caso de que las columnas cambien

    tabla_6 = $("#tabla6_comidas_ex").dataTable({
      responsive: true,
      lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }],
      data: e.data.datatable,
      createdRow: function (row, data, ixdex) { 
        // columna: #
        if (data[0] != '') { $("td", row).eq(0).addClass("w-px-35 text-center text-nowrap"); }
        // columna: fecha
        if (data[2] != '') { $("td", row).eq(2).addClass("text-nowrap"); } 
        // columna: detalle
        if (data[4] != '') { $("td", row).eq(4).addClass("text-center"); }  
        // columna: montos
        if (data[5] != '') { $("td", row).eq(5).addClass("text-right"); }         
        // columna: depositos  
        if (data[6] != '') { $("td", row).eq(6).addClass("text-right"); } 
        // columna: saldos
        if (data[7] != '') {
          $("td", row).eq(7).addClass("text-right");
          var numero = quitar_formato_miles(data[7]);          
          if ( parseFloat(numero) < 0 ) {
            $("td", row).eq(7).addClass("text-right bg-danger");
          }else{              
            if ( parseFloat(numero) > 0 ) {
              $("td", row).eq(7).addClass("text-right bg-warning");
            } else {
              if ( parseFloat(numero) == 0 ) {
                $("td", row).eq(7).addClass("text-right bg-success");
              }
            }
          }
        }
      },
      language: {
        lengthMenu: "Mostrar: _MENU_ registros",
        buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
        sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
      },
      bDestroy: true,
      iDisplayLength: 5, //Paginación
      order: [[0, "asc"]], //Ordenar (columna,orden)
    }).DataTable();

    $('.cargando-comida-extra').removeClass('bg-danger').addClass('backgff9100').html('Comidas extras');

    tbla_breaks(idproyecto, fecha_filtro_1, fecha_filtro_2, id_trabajador, id_proveedor, deuda);
  }).fail( function(e) { ver_errores(e); } );
   
}

// TABLA - BREAKS - -------------------------------------------------------------
function tbla_breaks(idproyecto, fecha_filtro_1, fecha_filtro_2, id_trabajador, id_proveedor, deuda) {
   
  $.post("../ajax/resumen_general.php?op=tbla_breaks", { 'idproyecto': idproyecto, 'fecha_filtro_1':fecha_filtro_1, 'fecha_filtro_2':fecha_filtro_2, 'id_proveedor':id_proveedor, 'deuda':deuda }, function (e, status) {
    
    e = JSON.parse(e); //console.log(data);    

    $("#monto_break").html(formato_miles(e.data.t_monto.toFixed(2)));
    $("#pago_break").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $("#saldo_break").html(formato_miles(e.data.t_saldo.toFixed(2)));

    monto_break = e.data.t_monto.toFixed(2); 
    pago_break = e.data.t_pagos.toFixed(2); 
    saldo_break = e.data.t_saldo.toFixed(2);

    // acumulamos las sumas totales
    $(".monto_break_all").html(formato_miles(e.data.t_monto.toFixed(2)));
    $(".pago_break_all").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $(".saldo_break_all").html(formato_miles(e.data.t_saldo.toFixed(2)));

    monto_all += parseFloat(e.data.t_monto);
    deposito_all += parseFloat(e.data.t_pagos);
    saldo_all += parseFloat(e.data.t_saldo);

    $("#monto_all").html(formato_miles(monto_all.toFixed(2)));
    $("#deposito_all").html(formato_miles(deposito_all.toFixed(2)));
    $("#saldo_all").html(formato_miles(saldo_all.toFixed(2)));

    tabla_7.destroy(); // Destruye las tablas de datos en el contexto actual.

    $('#breaks').empty(); // Vacía en caso de que las columnas cambien

    tabla_7 = $("#tabla7_breaks").dataTable({
      responsive: true,
      lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }],
      data: e.data.datatable,
      createdRow: function (row, data, ixdex) { 
        // columna: #
        if (data[0] != '') { $("td", row).eq(0).addClass("w-px-35 text-center text-nowrap"); }
        // columna: fecha
        if (data[2] != '') { $("td", row).eq(2).addClass("text-nowrap"); } 
        // columna: detalle
        if (data[4] != '') { $("td", row).eq(4).addClass("text-center"); } 
        // columna: montos
        if (data[5] != '') { $("td", row).eq(5).addClass("text-right"); }         
        // columna: depositos  
        if (data[6] != '') { $("td", row).eq(6).addClass("text-right"); }  
        // columna: saldos
        if (data[7] != '') {
          $("td", row).eq(7).addClass("text-right");
          var numero = quitar_formato_miles(data[7]);          
          if ( parseFloat(numero) < 0 ) {
            $("td", row).eq(7).addClass("text-right bg-danger");
          }else{              
            if ( parseFloat(numero) > 0 ) {
              $("td", row).eq(7).addClass("text-right bg-warning");
            } else {
              if ( parseFloat(numero) == 0 ) {
                $("td", row).eq(7).addClass("text-right bg-success");
              }
            }
          }
        }
      },
      language: {
        lengthMenu: "Mostrar: _MENU_ registros",
        buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
        sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
      },
      bDestroy: true,
      iDisplayLength: 5, //Paginación
      order: [[0, "asc"]], //Ordenar (columna,orden)
    }).DataTable();

    $('.cargando-breaks').removeClass('bg-danger').addClass('backgff9100').html('Breaks');

    tbla_pensiones(idproyecto, fecha_filtro_1, fecha_filtro_2, id_trabajador, id_proveedor, deuda);

  }).fail( function(e) { ver_errores(e); } );
   
}

// TABLA - PENSIONES - -----------------------------------------------------------
function tbla_pensiones(idproyecto, fecha_filtro_1, fecha_filtro_2, id_trabajador, id_proveedor, deuda) {
   
  $.post("../ajax/resumen_general.php?op=tbla_pensiones", { 'idproyecto': idproyecto, 'fecha_filtro_1':fecha_filtro_1, 'fecha_filtro_2':fecha_filtro_2, 'id_proveedor':id_proveedor, 'deuda':deuda }, function (e, status) {
    
    e = JSON.parse(e); //console.log(data);    

    $("#monto_pension").html(formato_miles(e.data.t_monto.toFixed(2)));
    $("#pago_pension").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $("#saldo_pension").html(formato_miles(e.data.t_saldo.toFixed(2)));

    monto_pension = e.data.t_monto.toFixed(2); 
    pago_pension = e.data.t_pagos.toFixed(2); 
    saldo_pension = e.data.t_saldo.toFixed(2);

    // acumulamos las sumas totales
    $(".monto_pension_all").html(formato_miles(e.data.t_monto.toFixed(2)));
    $(".pago_pension_all").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $(".saldo_pension_all").html(formato_miles(e.data.t_saldo.toFixed(2)));

    monto_all += parseFloat(e.data.t_monto);
    deposito_all += parseFloat(e.data.t_pagos);
    saldo_all += parseFloat(e.data.t_saldo);

    $("#monto_all").html(monto_all.toFixed(2));
    $("#deposito_all").html(deposito_all.toFixed(2));
    $("#saldo_all").html(saldo_all.toFixed(2));

    tabla_8.destroy(); // Destruye las tablas de datos en el contexto actual.

    $('#pension').empty(); // Vacía en caso de que las columnas cambien

    tabla_8 = $("#tabla8_pension").dataTable({
      responsive: true,
      lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }],
      data: e.data.datatable,
      createdRow: function (row, data, ixdex) {          

        // columna: #
        if (data[0] != '') {
          $("td", row).eq(0).addClass("w-px-35 text-center text-nowrap");         
        }

        // columna: fecha
        if (data[2] != '') {
          $("td", row).eq(2).addClass("text-nowrap");         
        } 

        // columna: detalle
        if (data[4] != '') {
          $("td", row).eq(4).addClass("text-center");         
        }  

        // columna: montos
        if (data[5] != '') {
          $("td", row).eq(5).addClass("text-right");         
        }   
        
        // columna: depositos  
        if (data[6] != '') {
          $("td", row).eq(6).addClass("text-right");
        }              
  
        // columna: saldos
        if (data[7] != '') {
          $("td", row).eq(7).addClass("text-right");
          var numero = quitar_formato_miles(data[7]);
          
          if ( parseFloat(numero) < 0 ) {
            $("td", row).eq(7).addClass("text-right bg-danger");
          }else{              
            if ( parseFloat(numero) > 0 ) {
              $("td", row).eq(7).addClass("text-right bg-warning");
            } else {
              if ( parseFloat(numero) == 0 ) {
                $("td", row).eq(7).addClass("text-right bg-success");
              }
            }
          }
        }
      },
      language: {
        lengthMenu: "Mostrar: _MENU_ registros",
        buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
        sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
      },
      bDestroy: true,
      iDisplayLength: 5, //Paginación
      order: [[0, "asc"]], //Ordenar (columna,orden)
    }).DataTable();

    $('.cargando-pension').removeClass('bg-danger').addClass('backgff9100').html('Pensión');

    tbla_administrativo(idproyecto, fecha_filtro_1, fecha_filtro_2, id_trabajador, id_proveedor, deuda);
  }).fail( function(e) { ver_errores(e); } );
   
}

// TABLA - ADMINISTRAIVOS - -------------------------------------------------------
function tbla_administrativo(idproyecto, fecha_filtro_1, fecha_filtro_2, id_trabajador, id_proveedor, deuda) {
   
  $.post("../ajax/resumen_general.php?op=tbla_administrativo", { 'idproyecto': idproyecto, 'fecha_filtro_1':fecha_filtro_1, 'fecha_filtro_2':fecha_filtro_2, 'id_trabajador':id_trabajador, 'deuda':deuda }, function (e, status) {
    
    e = JSON.parse(e);  //console.log(data);     

    $("#monto_adm").html(formato_miles(e.data.t_monto.toFixed(2)));
    $("#pago_adm").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $("#saldo_adm").html(formato_miles(e.data.t_saldo.toFixed(2)));

    monto_adm = e.data.t_monto.toFixed(2); 
    pago_adm = e.data.t_pagos.toFixed(2); 
    saldo_adm = e.data.t_saldo.toFixed(2);

    // acumulamos las sumas totales
    $(".monto_adm_all").html(formato_miles(e.data.t_monto.toFixed(2)));
    $(".pago_adm_all").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $(".saldo_adm_all").html(formato_miles(e.data.t_saldo.toFixed(2)));

    monto_all += parseFloat(e.data.t_monto);
    deposito_all += parseFloat(e.data.t_pagos);
    saldo_all += parseFloat(e.data.t_saldo);

    $("#monto_all").html(formato_miles(monto_all.toFixed(2)));
    $("#deposito_all").html(formato_miles(deposito_all.toFixed(2)));
    $("#saldo_all").html(formato_miles(saldo_all.toFixed(2)));

    // actualizamos el DATA-TABLE
    tabla_9.destroy(); // Destruye las tablas de datos en el contexto actual.

    $('#administrativo').empty(); // Vacía en caso de que las columnas cambien

    tabla_9 = $("#tabla9_per_adm").dataTable({
      responsive: true,
      lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }],
      data: e.data.datatable,
      createdRow: function (row, data, ixdex) {          

        // columna: #
        if (data[0] != '') {
          $("td", row).eq(0).addClass("w-px-35 text-center text-nowrap");         
        }

        // columna: fecha
        if (data[2] != '') {
          $("td", row).eq(2).addClass("text-nowrap");         
        } 

        // columna: detalle
        if (data[4] != '') {
          $("td", row).eq(4).addClass("text-center");         
        }  

        // columna: montos
        if (data[5] != '') {
          $("td", row).eq(5).addClass("text-right");         
        }   
        
        // columna: depositos  
        if (data[6] != '') {
          $("td", row).eq(6).addClass("text-right");
        }              
  
        // columna: saldos
        if (data[7] != '') {
          $("td", row).eq(7).addClass("text-right");
          var numero = quitar_formato_miles(data[7]);
          
          if ( parseFloat(numero) < 0 ) {
            $("td", row).eq(7).addClass("text-right bg-danger");
          }else{              
            if ( parseFloat(numero) > 0 ) {
              $("td", row).eq(7).addClass("text-right bg-warning");
            } else {
              if ( parseFloat(numero) == 0 ) {
                $("td", row).eq(7).addClass("text-right bg-success");
              }
            }
          }
        }
      },
      language: {
        lengthMenu: "Mostrar: _MENU_ registros",
        buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
        sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
      },
      bDestroy: true,
      iDisplayLength: 5, //Paginación
      order: [[0, "asc"]], //Ordenar (columna,orden)
    }).DataTable();

    $('.cargando-administrativo').removeClass('bg-danger').addClass('backgff9100').html('Personal Administrativo');

    tbla_obrero(idproyecto, fecha_filtro_1, fecha_filtro_2, id_trabajador, id_proveedor, deuda);

  }).fail( function(e) { ver_errores(e); } );
   
}

// TABLA - OBRERO - ----------------------------------------------------------------
function tbla_obrero(idproyecto, fecha_filtro_1, fecha_filtro_2, id_trabajador, id_proveedor, deuda) {
   
  $.post("../ajax/resumen_general.php?op=tbla_obrero", { 'idproyecto': idproyecto, 'fecha_filtro_1':fecha_filtro_1, 'fecha_filtro_2':fecha_filtro_2, 'id_trabajador':id_trabajador, 'deuda':deuda }, function (e, status) {
    
    e = JSON.parse(e); //  console.log(data);
    
    $("#monto_obrero").html(formato_miles(e.data.t_monto.toFixed(2)));
    $("#pago_obrero").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $("#saldo_obrero").html(formato_miles(e.data.t_saldo.toFixed(2)));

    monto_obrero = e.data.t_monto.toFixed(2); 
    pago_obrero = e.data.t_pagos.toFixed(2); 
    saldo_obrero = e.data.t_saldo.toFixed(2);

    // acumulamos las sumas totales
    $(".monto_obrero_all").html(formato_miles(e.data.t_monto.toFixed(2)));
    $(".pago_obrero_all").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $(".saldo_obrero_all").html(formato_miles(e.data.t_saldo.toFixed(2)));

    monto_all += parseFloat(e.data.t_monto);
    deposito_all += parseFloat(e.data.t_pagos);
    saldo_all += parseFloat(e.data.t_saldo);

    $("#monto_all").html(formato_miles(monto_all.toFixed(2)));
    $("#deposito_all").html(formato_miles(deposito_all.toFixed(2)));
    $("#saldo_all").html(formato_miles(saldo_all.toFixed(2)));

    // actualizamos el DATA-TABLE
    tabla_10.destroy(); // Destruye las tablas de datos en el contexto actual.

    $('#obrero').empty(); // Vacía en caso de que las columnas cambien

    tabla_10 = $("#tabla10_per_obr").dataTable({
      responsive: true,
      lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }],
      data: e.data.datatable,
      createdRow: function (row, data, ixdex) {          

        // columna: #
        if (data[0] != '') {
          $("td", row).eq(0).addClass("w-px-35 text-center text-nowrap");         
        }

        // columna: fecha
        if (data[2] != '') {
          $("td", row).eq(2).addClass("text-nowrap");         
        } 

        // columna: detalle
        if (data[4] != '') {
          $("td", row).eq(4).addClass("text-center");         
        }  

        // columna: montos
        if (data[5] != '') {
          $("td", row).eq(5).addClass("text-right");         
        }   
        
        // columna: depositos  
        if (data[6] != '') {
          $("td", row).eq(6).addClass("text-right");
        }              
  
        // columna: saldos
        if (data[7] != '') {
          $("td", row).eq(7).addClass("text-right");
          var numero = quitar_formato_miles(data[7]);
          
          if ( parseFloat(numero) < 0 ) {
            $("td", row).eq(7).addClass("text-right bg-danger");
          }else{              
            if ( parseFloat(numero) > 0 ) {
              $("td", row).eq(7).addClass("text-right bg-warning");
            } else {
              if ( parseFloat(numero) == 0 ) {
                $("td", row).eq(7).addClass("text-right bg-success");
              }
            }
          }
        }
      },
      language: {
        lengthMenu: "Mostrar: _MENU_ registros",
        buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
        sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
      },
      bDestroy: true,
      iDisplayLength: 5, //Paginación
      order: [[0, "asc"]], //Ordenar (columna,orden)
    }).DataTable();

    $('.cargando-obrero').removeClass('bg-danger').addClass('backgff9100').html('Personal Obrero');

    tbla_otros_gastos(idproyecto, fecha_filtro_1, fecha_filtro_2, id_trabajador, id_proveedor, deuda);

  }).fail( function(e) { ver_errores(e); } );
   
}

// TABLA - OTROS GASTOS - -------------------------------------------------------
function tbla_otros_gastos(idproyecto, fecha_filtro_1, fecha_filtro_2, id_trabajador, id_proveedor, deuda) {
   
  $.post("../ajax/resumen_general.php?op=tbla_otros_gastos", { 'idproyecto': idproyecto, 'fecha_filtro_1':fecha_filtro_1, 'fecha_filtro_2':fecha_filtro_2, 'id_proveedor':id_proveedor, 'deuda':deuda }, function (e, status) {
    
    e = JSON.parse(e); //console.log(data);   

    $("#monto_otros_gastos").html(formato_miles(e.data.t_monto.toFixed(2)));
    $("#pago_otros_gastos").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $("#saldo_otros_gastos").html(formato_miles(e.data.t_saldo.toFixed(2)));

    monto_otros_gastos = e.data.t_monto.toFixed(2); 
    pago_otros_gastos = e.data.t_pagos.toFixed(2); 
    saldo_otros_gastos = e.data.t_saldo.toFixed(2);

    // acumulamos las sumas totales
    $(".monto_otros_gastos_all").html(formato_miles(e.data.t_monto.toFixed(2)));
    $(".pago_otros_gastos_all").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $(".saldo_otros_gastos_all").html(formato_miles(e.data.t_saldo.toFixed(2)));

    monto_all += parseFloat(e.data.t_monto);
    deposito_all += parseFloat(e.data.t_pagos);
    saldo_all += parseFloat(e.data.t_saldo);

    $("#monto_all").html(formato_miles(monto_all.toFixed(2)));
    $("#deposito_all").html(formato_miles(deposito_all.toFixed(2)));
    $("#saldo_all").html(formato_miles(saldo_all.toFixed(2)));

    tabla_11.destroy(); // Destruye las tablas de datos en el contexto actual.

    $('#otros_gastos').empty(); // Vacía en caso de que las columnas cambien

    tabla_11 = $("#tabla11_otros_gastos").dataTable({
      responsive: true,
      lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }],
      data: e.data.datatable,
      createdRow: function (row, data, ixdex) {          

        // columna: #
        if (data[0] != '') {
          $("td", row).eq(0).addClass("w-px-35 text-center text-nowrap");         
        }

        // columna: fecha
        if (data[2] != '') {
          $("td", row).eq(2).addClass("text-nowrap");         
        } 

        // columna: detalle
        if (data[4] != '') {
          $("td", row).eq(4).addClass("text-center");         
        }  

        // columna: montos
        if (data[5] != '') {
          $("td", row).eq(5).addClass("text-right");         
        }   
        
        // columna: depositos  
        if (data[6] != '') {
          $("td", row).eq(6).addClass("text-right");
        }              
  
        // columna: saldos
        if (data[7] != '') {
          $("td", row).eq(7).addClass("text-right");
          var numero = quitar_formato_miles(data[7]);
          
          if ( parseFloat(numero) < 0 ) {
            $("td", row).eq(7).addClass("text-right bg-danger");
          }else{              
            if ( parseFloat(numero) > 0 ) {
              $("td", row).eq(7).addClass("text-right bg-warning");
            } else {
              if ( parseFloat(numero) == 0 ) {
                $("td", row).eq(7).addClass("text-right bg-success");
              }
            }
          }
        }
      },
      language: {
        lengthMenu: "Mostrar: _MENU_ registros",
        buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
        sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
      },
      bDestroy: true,
      iDisplayLength: 5, //Paginación
      order: [[0, "asc"]], //Ordenar (columna,orden)
    }).DataTable();

    $('.cargando-otros-gastos').removeClass('bg-danger').addClass('backgff9100').html('Otros Gastos');
    
    tbla_sub_contrato(idproyecto, fecha_filtro_1, fecha_filtro_2, id_trabajador, id_proveedor, deuda);
  }).fail( function(e) { ver_errores(e); } );
  
}

// TABLA - SUB CONTRATO - -------------------------------------------------------
function tbla_sub_contrato(idproyecto, fecha_filtro_1, fecha_filtro_2, id_trabajador, id_proveedor, deuda) {
   
  $.post("../ajax/resumen_general.php?op=tbla_sub_contrato", { 'idproyecto': idproyecto, 'fecha_filtro_1':fecha_filtro_1, 'fecha_filtro_2':fecha_filtro_2, 'id_proveedor':id_proveedor, 'deuda':deuda }, function (e, status) {
    
    e = JSON.parse(e); //console.log(e);   

    $("#monto_sub_contrato").html(formato_miles(e.data.t_monto.toFixed(2)));
    $("#pago_sub_contrato").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $("#saldo_sub_contrato").html(formato_miles(e.data.t_saldo.toFixed(2)));

    monto_sub_contrato = e.data.t_monto.toFixed(2); 
    pago_sub_contrato = e.data.t_pagos.toFixed(2); 
    saldo_sub_contrato = e.data.t_saldo.toFixed(2);

    // acumulamos las sumas totales
    $(".monto_sub_contrato_all").html(formato_miles(e.data.t_monto.toFixed(2)));
    $(".pago_sub_contrato_all").html(formato_miles(e.data.t_pagos.toFixed(2)));
    $(".saldo_sub_contrato_all").html(formato_miles(e.data.t_saldo.toFixed(2)));

    monto_all += parseFloat(e.data.t_monto);
    deposito_all += parseFloat(e.data.t_pagos);
    saldo_all += parseFloat(e.data.t_saldo);

    $("#monto_all").html(formato_miles(monto_all.toFixed(2)));
    $("#deposito_all").html(formato_miles(deposito_all.toFixed(2)));
    $("#saldo_all").html(formato_miles(saldo_all.toFixed(2)));

    tabla_12.destroy(); // Destruye las tablas de datos en el contexto actual.

    $('#sub_contrato').empty(); // Vacía en caso de que las columnas cambien

    tabla_12 = $("#tabla12_sub_contrato").dataTable({
      responsive: true,
      lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }],
      data: e.data.datatable,
      createdRow: function (row, data, ixdex) {          

        // columna: #
        if (data[0] != '') { $("td", row).eq(0).addClass("w-px-35 text-center text-nowrap"); }
        // columna: fecha
        if (data[2] != '') { $("td", row).eq(2).addClass("text-nowrap"); } 
        // columna: detalle
        if (data[4] != '') { $("td", row).eq(4).addClass("text-center"); } 
        // columna: montos
        if (data[5] != '') { $("td", row).eq(5).addClass("text-right");  }          
        // columna: depositos  
        if (data[6] != '') { $("td", row).eq(6).addClass("text-right"); }              
  
        // columna: saldos
        if (data[7] != '') {
          $("td", row).eq(7).addClass("text-right");
          var numero = quitar_formato_miles(data[7]);
          
          if ( parseFloat(numero) < 0 ) {
            $("td", row).eq(7).addClass("text-right bg-danger");
          }else{              
            if ( parseFloat(numero) > 0 ) {
              $("td", row).eq(7).addClass("text-right bg-warning");
            } else {
              if ( parseFloat(numero) == 0 ) {
                $("td", row).eq(7).addClass("text-right bg-success");
              }
            }
          }
        }
      },
      language: {
        lengthMenu: "Mostrar: _MENU_ registros",
        buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
        sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
      },
      bDestroy: true,
      iDisplayLength: 5, //Paginación
      order: [[0, "asc"]], //Ordenar (columna,orden)
    }).DataTable();

    $('.cargando-sub-contrato').removeClass('bg-danger').addClass('backgff9100').html('Sub Contrato');

    table_all_sumas();
  }).fail( function(e) { ver_errores(e); } );
  
}

// TABLA - RESUMEN TOTAL - -------------------------------------------------------
function table_all_sumas() {
  $('.cargando-sumas').removeClass('backgff9100').addClass('bg-danger').html('Sumas totales - calculando <i class="fas fa-spinner fa-pulse fa-sm"></i>');
  console.log(monto_all ,  deposito_all   ,saldo_all );        
   
  var insert_table = [    
    {
      '0': '1', '1': '--', '2': '--', '3': '--',
      '4': 'Compras de Insumos',
      '5': formato_miles(monto_compras),
      '6': formato_miles(pago_compras),
      '7': formato_miles(saldo_compras),
    },
    {
      '0': '2', '1': '--', '2': '--', '3': '--',
      '4': 'Servicios Maquinaria',
      '5': formato_miles(monto_serv_maq),
      '6': formato_miles(pago_serv_maq),
      '7': formato_miles(saldo_serv_maq),
    },
    {
      '0': '3', '1': '--', '2': '--', '3': '--',
      '4': 'Servicios Equipo',
      '5': formato_miles(monto_serv_equi),
      '6': formato_miles(pago_serv_equi),
      '7': formato_miles(saldo_serv_equi),
    },
    {
      '0': '4', '1': '--', '2': '--', '3': '--',
      '4': 'Transporte',
      '5': formato_miles(monto_transp),
      '6': formato_miles(pago_transp),
      '7': formato_miles(saldo_transp),
    },
    {
      '0': '5', '1': '--', '2': '--', '3': '--',
      '4': 'Hospedaje',
      '5': formato_miles(monto_hosped),
      '6': formato_miles(pago_hosped),
      '7': formato_miles(saldo_hosped),
    },
    {
      '0': '6', '1': '--', '2': '--', '3': '--',
      '4': 'Comidas extras',
      '5': formato_miles(monto_cextra),
      '6': formato_miles(pago_cextra),
      '7': formato_miles(saldo_cextra),
    },
    {
      '0': '7', '1': '--', '2': '--', '3': '--',
      '4': 'Breaks',
      '5': formato_miles(monto_break),
      '6': formato_miles(pago_break),
      '7': formato_miles(saldo_break),
    },
    {
      '0': '8', '1': '--', '2': '--', '3': '--',
      '4': 'Pensión',
      '5': formato_miles(monto_pension),
      '6': formato_miles(pago_pension),
      '7': formato_miles(saldo_pension),
    },
    {
      '0': '9', '1': '--', '2': '--', '3': '--',
      '4': 'Personal Administrativo',
      '5': formato_miles(monto_adm),
      '6': formato_miles(pago_adm),
      '7': formato_miles(saldo_adm),
    },
    {
      '0': '10', '1': '--', '2': '--', '3': '--',
      '4': 'Personal Obrero',
      '5': formato_miles(monto_obrero),
      '6': formato_miles(pago_obrero),
      '7': formato_miles(saldo_obrero),
    },
    {
      '0': '11', '1': '--', '2': '--', '3': '--',
      '4': 'Otros Gastos',
      '5': formato_miles(monto_otros_gastos),
      '6': formato_miles(pago_otros_gastos),
      '7': formato_miles(saldo_otros_gastos),
    },
    {
      '0': '12', '1': '--', '2': '--', '3': '--',
      '4': 'Sub Contrato',
      '5': formato_miles(monto_sub_contrato),
      '6': formato_miles(pago_sub_contrato),
      '7': formato_miles(saldo_sub_contrato),
    },
  ];

  tabla_20.destroy(); // Destruye las tablas de datos en el contexto actual.

  $('#tbody20_all_sumas').empty(); // Vacía en caso de que las columnas cambien
    
  tabla_20 = $("#tabla20_all_sumas").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }],
    data: insert_table,
    createdRow: function (row, data, ixdex) {   
      // columna: detalle
      if (data[4] != '') {   

        if (data[4] == 'Compras de Insumos') {
          $("td", row).eq(5).removeClass('text-right monto_compras_all').addClass('text-right monto_compras_all');
          $("td", row).eq(6).removeClass('text-right pago_compras_all').addClass('text-right pago_compras_all');
          $("td", row).eq(7).removeClass('text-right saldo_compras_all').addClass('text-right saldo_compras_all');
        }

        if (data[4] == 'Servicios Maquinaria') {
          $("td", row).eq(5).removeClass('text-right monto_serv_maq_all').addClass('text-right monto_serv_maq_all');
          $("td", row).eq(6).removeClass('text-right monto_serv_maq_all').addClass('text-right monto_serv_maq_all');
          $("td", row).eq(7).removeClass('text-right saldo_serv_maq_all').addClass('text-right saldo_serv_maq_all');
        }

        if (data[4] == 'Servicios Equipo') {
          $("td", row).eq(5).removeClass('text-right monto_serv_equi_all').addClass('text-right monto_serv_equi_all');
          $("td", row).eq(6).removeClass('text-right pago_serv_equi_all').addClass('text-right pago_serv_equi_all');
          $("td", row).eq(7).removeClass('text-right saldo_serv_equi_all').addClass('text-right saldo_serv_equi_all');
        }

        if (data[4] == 'Transporte') {
          $("td", row).eq(5).removeClass('text-right monto_transp_all').addClass('text-right monto_transp_all');
          $("td", row).eq(6).removeClass('text-right pago_transp_all').addClass('text-right pago_transp_all');
          $("td", row).eq(7).removeClass('text-right saldo_transp_all').addClass('text-right saldo_transp_all');
        }

        if (data[4] == 'Hospedaje') {
          $("td", row).eq(5).removeClass('text-right monto_hosped_all').addClass('text-right monto_hosped_all');
          $("td", row).eq(6).removeClass('text-right pago_hosped_all').addClass('text-right pago_hosped_all');
          $("td", row).eq(7).removeClass('text-right saldo_hosped_all').addClass('text-right saldo_hosped_all');
        }

        if (data[4] == 'Comidas extras') {
          $("td", row).eq(5).removeClass('text-right monto_cextra_all').addClass('text-right monto_cextra_all');
          $("td", row).eq(6).removeClass('text-right pago_cextra_all').addClass('text-right pago_cextra_all');
          $("td", row).eq(7).removeClass('text-right saldo_cextra_all').addClass('text-right saldo_cextra_all');
        }

        if (data[4] == 'Breaks') {
          $("td", row).eq(5).removeClass('text-right monto_break_all').addClass('text-right monto_break_all');
          $("td", row).eq(6).removeClass('text-right pago_break_all').addClass('text-right pago_break_all');
          $("td", row).eq(7).removeClass('text-right saldo_break_all').addClass('text-right saldo_break_all');
        }

        if (data[4] == 'Pensión') {
          $("td", row).eq(5).removeClass('text-right monto_pension_all ').addClass('text-right monto_pension_all ');
          $("td", row).eq(6).removeClass('text-right pago_pension_all').addClass('text-right pago_pension_all');
          $("td", row).eq(7).removeClass('text-right saldo_pension_all').addClass('text-right saldo_pension_all');
        }

        if (data[4] == 'Personal Administrativo') {
          $("td", row).eq(5).removeClass('text-right monto_adm_all').addClass('text-right monto_adm_all');
          $("td", row).eq(6).removeClass('text-right pago_adm_all').addClass('text-right pago_adm_all');
          $("td", row).eq(7).removeClass('text-right saldo_adm_all').addClass('text-right saldo_adm_all');
        }

        if (data[4] == 'Personal Obrero') {
          $("td", row).eq(5).removeClass('text-right monto_obrero_all').addClass('text-right monto_obrero_all');
          $("td", row).eq(6).removeClass('text-right pago_obrero_all').addClass('text-right pago_obrero_all');
          $("td", row).eq(7).removeClass('text-right saldo_obrero_all').addClass('text-right saldo_obrero_all');    
        }
        
        if (data[4] == 'Otros Gastos') {
          $("td", row).eq(5).removeClass('text-right monto_otros_gastos_all').addClass('text-right monto_otros_gastos_all');
          $("td", row).eq(6).removeClass('text-right pago_otros_gastos_all').addClass('text-right pago_otros_gastos_all');
          $("td", row).eq(7).removeClass('text-right saldo_otros_gastos_all').addClass('text-right saldo_otros_gastos_all');    
        }

        if (data[4] == 'Sub Contrato') {
          $("td", row).eq(5).removeClass('text-right monto_sub_contrato_all').addClass('text-right monto_sub_contrato_all');
          $("td", row).eq(6).removeClass('text-right pago_sub_contrato_all').addClass('text-right pago_sub_contrato_all');
          $("td", row).eq(7).removeClass('text-right saldo_sub_contrato_all').addClass('text-right saldo_sub_contrato_all');    
        }
      }  

      // columna: montos
      if (data[5] != '') { $("td", row).eq(5).addClass("text-right"); }      
      // columna: depositos  
      if (data[6] != '') { $("td", row).eq(6).addClass("text-right"); }              

      // columna: saldos
      if (data[7] != '') {
        $("td", row).eq(7).addClass("text-right");
        var numero_deposito = quitar_formato_miles(data[6]);
        var numero_saldo = quitar_formato_miles(data[7]);
        // console.log(numero_saldo);
        if ( parseFloat(numero_saldo) < 0 ) {
          $("td", row).eq(7).addClass("text-right bg-danger");
        }else{              
          if ( parseFloat(numero_saldo) > 0 ) {
            $("td", row).eq(7).addClass("text-right bg-warning");
          } else {            
            if ( parseFloat(numero_saldo) == 0 && numero_deposito > 0) {
              $("td", row).eq(7).addClass("text-right bg-success");
            }else{
              $("td", row).eq(7).removeClass("bg-success");
            }
          }
        }
      }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 25, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
  }).DataTable();  

  $('.cargando-sumas').removeClass('bg-danger').addClass('backgff9100').html('Sumas totales');
}

function filtros() {

  monto_all = 0; deposito_all = 0; saldo_all = 0;
  
  var fecha_1        = $("#fecha_filtro_1").val();
  var fecha_2        = $("#fecha_filtro_2").val();  
  var id_trabajador  = $("#trabajador_filtro").select2('val');
  var id_proveedor   = $("#proveedor_filtro").select2('val');
  var deuda          = $("#deuda_filtro").select2('val');

  // filtro de fechas
  if (fecha_1 == "" || fecha_1 == null) { fecha_1 = ""; }
  if (fecha_2 == "" || fecha_2 == null) { fecha_2 = ""; }

  // filtro de trabajdor
  if (id_trabajador == '' || id_trabajador == 0 || id_trabajador == null) { id_trabajador = ""; }

  // filtro de proveedor
  if (id_proveedor == '' || id_proveedor == 0 || id_proveedor == null) { id_proveedor = ""; }

  // filtro deuda
  if (deuda == "" || deuda == null) { deuda = ""; }

  console.log(fecha_1, fecha_2, id_trabajador, id_proveedor, deuda);
  
  $('.cargando-compras').removeClass('backgff9100').addClass('bg-danger').html('Compras de Insumos - calculando <i class="fas fa-spinner fa-pulse fa-sm"></i>');

  $('.cargando-maquinas').removeClass('backgff9100').addClass('bg-danger').html('Servicios-Maquinaria - calculando <i class="fas fa-spinner fa-pulse fa-sm"></i>');

  $('.cargando-equipos').removeClass('backgff9100').addClass('bg-danger').html('Servicios-Equipo - calculando <i class="fas fa-spinner fa-pulse fa-sm"></i>');

  $('.cargando-transporte').removeClass('backgff9100').addClass('bg-danger').html('Transporte - calculando <i class="fas fa-spinner fa-pulse fa-sm"></i>');

  $('.cargando-hospedaje').removeClass('backgff9100').addClass('bg-danger').html('Hospedaje - calculando <i class="fas fa-spinner fa-pulse fa-sm"></i>');

  $('.cargando-comida-extra').removeClass('backgff9100').addClass('bg-danger').html('Comidas extras - calculando <i class="fas fa-spinner fa-pulse fa-sm"></i>');

  $('.cargando-breaks').removeClass('backgff9100').addClass('bg-danger').html('Breaks - calculando <i class="fas fa-spinner fa-pulse fa-sm"></i>');
  
  $('.cargando-pension').removeClass('backgff9100').addClass('bg-danger').html('Pensión - calculando <i class="fas fa-spinner fa-pulse fa-sm"></i>');
  
  $('.cargando-administrativo').removeClass('backgff9100').addClass('bg-danger').html('Personal Administrativo - calculando <i class="fas fa-spinner fa-pulse fa-sm"></i>');

  $('.cargando-obrero').removeClass('backgff9100').addClass('bg-danger').html('Personal Obrero - calculando <i class="fas fa-spinner fa-pulse fa-sm"></i>');
  
  $('.cargando-otros-gastos').removeClass('backgff9100').addClass('bg-danger').html('Otros Gastos - calculando <i class="fas fa-spinner fa-pulse fa-sm"></i>');
  
  $('.cargando-sub-contrato').removeClass('backgff9100').addClass('bg-danger').html('Sub Contrato - calculando <i class="fas fa-spinner fa-pulse fa-sm"></i>');

  $('.cargando-sumas').removeClass('backgff9100').addClass('bg-danger').html('Sumas totales - calculando <i class="fas fa-spinner fa-pulse fa-sm"></i>');

  // ejecutamos las funcioes a filtrar
  tbla_compras(localStorage.getItem("nube_idproyecto"), fecha_1, fecha_2, id_trabajador, id_proveedor, deuda);
  
}

// ::::::::::::::::::::::: MODALS ::::::::::::::::::::::::::::::::
//MODAL - COMPRAS
function mostrar_detalle_compras(idcompra_proyecto) {
  $('#cargando-1-fomulario').hide();
  $('#cargando-2-fomulario').show();

  $("#print_pdf_compra").addClass('disabled');
  $("#excel_compra").addClass('disabled');

  $("#modal-ver-compras").modal("show");  

  $.post("../ajax/resumen_general.php?op=mostrar_detalle_compras&id_compra=" + idcompra_proyecto, function (r) {
    r = JSON.parse(r);
    if (r.status == true) {
      $(".detalles_compra").html(r.data);
      $('#cargando-1-fomulario').show();
      $('#cargando-2-fomulario').hide();

      $("#print_pdf_compra").removeClass('disabled');
      $("#print_pdf_compra").attr('href', `../reportes/pdf_compra_activos_fijos.php?id=${idcompra_proyecto}&op=insumo` );
      $("#excel_compra").removeClass('disabled');
    }else {
      ver_errores(e);
    }  

  }).fail( function(e) { ver_errores(e); } );
}

//MODAL - MAQUINARIA y EQUIPO
function mostrar_detalle_maquinaria_equipo(idmaquinaria, idproyecto, servicio, proveedor, maquina) {
  $("#nombre_proveedor_").html("");

  $("#modal_ver_detalle_maq_equ").modal("show");

  $("#detalle_").html(servicio);
  $("#nombre_proveedor_").html(`${maquina} - <i>${proveedor}</i>`);

  tabla2 = $("#tabla-detalle-m").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: ["copyHtml5", "excelHtml5", "pdf",],
    ajax: {
      url: "../ajax/resumen_general.php?op=mostrar_detalle_maquinaria_equipo&idmaquinaria=" + idmaquinaria + "&idproyecto=" + idproyecto,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: unidad
      if (data[1] != '') { $("td", row).eq(1).addClass("text-center"); }       
      // columna: cantidad
      if (data[2] != '') { $("td", row).eq(2).addClass("text-center"); }
      // columna: costo unitario
      if (data[3] != '') { $("td", row).eq(3).addClass("text-right"); }
      // columna: costo parcial
      if (data[4] != '') { $("td", row).eq(4).addClass("text-right"); } 
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 5, //Paginación
    order: [[0, "desc"]], //Ordenar (columna,orden)
  }).DataTable();
}

// MODAL - BREAKS
function mostrar_comprobantes_breaks(idsemana_break) {
  $("#modal_ver_breaks").modal("show");

  tabla1 = $("#t-comprobantes").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: ["copyHtml5", "excelHtml5", "pdf",],
    ajax: {
      url: "../ajax/resumen_general.php?op=mostrar_comprobantes_breaks&idsemana_break=" + idsemana_break,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 5, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
  }).DataTable();
}

// MODAL - PENSIONES
function mostrar_detalle_pension(idpension) {
  //console.log(numero_semana,nube_idproyecto);
  $("#modal-ver-detalle-semana").modal("show");
  tabla_detalle_s = $("#tabla-detalles-semanal")
    .dataTable({
      responsive: true,
      lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: ["copyHtml5", "excelHtml5",  "pdf",],
      ajax: {
        url: "../ajax/resumen_general.php?op=mostrar_detalle_pension&idpension=" + idpension,
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        },
      },
      language: {
        lengthMenu: "Mostrar: _MENU_ registros",
        buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
        sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
      },
      bDestroy: true,
      iDisplayLength: 5, //Paginación
      order: [[0, "asc"]], //Ordenar (columna,orden)
    })
    .DataTable();
}

// MODAL - PENSIONES
function mostrar_comprobantes_pension(idpension) {
  $("#modal-ver-comprobantes_pension").modal("show");

  tabla2 = $("#t-comprobantes-pension").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: ["copyHtml5", "excelHtml5",  "pdf"],
    ajax: {
      url: "../ajax/resumen_general.php?op=mostrar_comprobantes_pension&idpension=" + idpension,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 5, //Paginación
    order: [[0, "desc"]], //Ordenar (columna,orden)
  }).DataTable();
}

// MODAL - ADMINISTRAIVOS
function mostrar_detalle_administrativo(idtrabajador_por_proyecto, nombres) {
  detalle = "";
  var sueldo_estimado = 0;
  var depositos = 0;

  $('#cargando-3-fomulario').hide();
  $('#cargando-4-fomulario').show();

  $("#modal-ver-detalle-t-administ").modal("show");

  $(".data-detalle-pagos-administador").html("");
  $("#nombre_trabajador_detalle").html(nombres);

  $.post("../ajax/resumen_general.php?op=mostrar_detalle_administrativo", { idtrabajador_por_proyecto: idtrabajador_por_proyecto }, function (e, status) {
    e = JSON.parse(e); console.log(e);
    $(".sueldo_estimado").html("");
    $(".depositos").html("");

    if (e.data.length != 0) {
      $(".alerta").hide();
      $(".tabla").show();
      e.data.forEach((value, index) => {
        detalle = `<tr>
          <td>${index + 1}</td>
          <td>${value.nombre_mes}</td>
          <td>${format_d_m_a(value.fecha_inicial)}</td>
          <td>${format_d_m_a(value.fecha_final)}</td>
          <td>${value.cant_dias_laborables}</td>
          <td class="m-r-10px" style="text-align: end !important;">S/ ${formato_miles(parseFloat(value.monto_x_mes).toFixed(2))}</td>
          <td class="m-r-10px" style="text-align: end !important;">S/ ${formato_miles(parseFloat(value.return_monto_pago).toFixed(2))}</td>
        </tr>`;

        $(".data-detalle-pagos-administador").append(detalle);
        sueldo_estimado += parseFloat(value.monto_x_mes);
        console.log(value.return_monto_pago);
        depositos += parseFloat(value.return_monto_pago);
      });

      $(".sueldo_estimado").html("S/ " + formato_miles(sueldo_estimado));
      $(".depositos").html("S/ " + formato_miles(depositos));
    } else {
      $(".tabla").hide();
      $(".alerta").show();
    }

    $('#cargando-3-fomulario').show();
    $('#cargando-4-fomulario').hide();
  }).fail( function(e) { ver_errores(e); } );
}

// MODAL - OBRERO
function mostrar_detalle_obrero(idtrabajador_por_proyecto, nombres) {
  detalle = "";
  var pago_parcial_hn = 0;
  var pago_parcial_he = 0;
  var saldo = 0;
  var sabatical = 0;
  var pago_quincenal = 0;
  var adicional_descuento = 0;
  var deposito = 0;
  var total_hn_he = 0;

  $('#cargando-5-fomulario').hide();
  $('#cargando-6-fomulario').show();

  $("#modal-ver-detalle-t-obrero").modal("show");

  $(".detalle-data-q-s").html("");
  $("#nombre_trabajador_ob_detalle").html(nombres);

  $.post("../ajax/resumen_general.php?op=mostrar_detalle_obrero", { idtrabajador_por_proyecto: idtrabajador_por_proyecto }, function (e, status) {
    //obrero
    e = JSON.parse(e); console.log(e);

    $(".total_hn_he").html("");
    $(".total_sabatical").html("");
    $(".total_monto_hn_he").html("");
    $(".total_descuento").html("");
    $(".total_quincena").html("");
    $(".total_deposito").html("");
    $(".total_saldo").html("");

    if (e.data.length != 0) {
      $(".alerta_obrero").hide();
      $(".tabla_obrero").show();
      e.data.forEach((value, index) => {
        detalle = `<tr>
          <td>${index + 1}</td>
          <td>${value.numero_q_s}</td>
          <td>${format_d_m_a(value.fecha_q_s_inicio)}</td>
          <td>${format_d_m_a(value.fecha_q_s_fin)}</td>
          <td>${value.sueldo_hora}</td>
          <td>${value.total_hn}<b> / </b>${value.total_he}</td>
          <td>${value.sabatical}</td>          
          <td> ${formato_miles(value.pago_parcial_hn)}<b> / </b>${formato_miles(value.pago_parcial_he)}</td>
          <td style="text-align: right !important;">${formato_miles(value.adicional_descuento)}</td>
          <td style="text-align: right !important;">${formato_miles(value.pago_quincenal)}</td>
          <td style="text-align: right !important;">${formato_miles(value.deposito)}</td>
          <td style="text-align: right !important;">${formato_miles(parseFloat(value.pago_quincenal) - parseFloat(value.deposito))}</td>
        </tr>`;

        $(".detalle-data-q-s").append(detalle);

        total_hn_he += parseFloat(value.total_hn) + parseFloat(value.total_he);
        sabatical += parseFloat(value.sabatical);
        pago_parcial_hn += parseFloat(value.pago_parcial_hn);
        pago_parcial_he += parseFloat(value.pago_parcial_he);
        adicional_descuento += parseFloat(value.adicional_descuento);
        pago_quincenal += parseFloat(value.pago_quincenal);
        deposito += parseFloat(value.deposito);
        saldo += parseFloat(value.pago_quincenal) - parseFloat(value.deposito);
      });

      $(".total_hn_he").html(total_hn_he);
      $(".total_sabatical").html(sabatical);
      $(".total_monto_hn_he").html("<sup>S/ </sup>" + formato_miles(pago_parcial_hn + pago_parcial_he));
      $(".total_descuento").html("<sup>S/ </sup>" + formato_miles(adicional_descuento));
      $(".total_quincena").html("<sup>S/ </sup>" + formato_miles(pago_quincenal));
      $(".total_deposito").html("<sup>S/ </sup>" + formato_miles(deposito));
      $(".total_saldo").html("<sup>S/ </sup>" + formato_miles(saldo));

      
    } else {
      $(".tabla_obrero").hide();
      $(".alerta_obrero").show();
    }  

    $('#cargando-5-fomulario').show();
    $('#cargando-6-fomulario').hide();

  }).fail( function(e) { ver_errores(e); } );
}

init();


// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

function export_excel_detalle_factura() {
  $tabla = document.querySelector("#tabla_detalle_factura");
  let tableExport = new TableExport($tabla, {
    exportButtons: false, // No queremos botones
    filename: "Detalle comprobante", //Nombre del archivo de Excel
    sheetname: "detalle factura", //Título de la hoja
  });
  let datos = tableExport.getExportData(); console.log(datos);
  let preferenciasDocumento = datos.tabla_detalle_factura.xlsx;
  tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);

}