var char_linea_subcontrato;

function init() {

  $('#mEscritorio').addClass("active");

  tablero();
  sumas_totales();

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════

  
  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════

  $("#valorizacion_filtro").select2({ theme: "bootstrap4", placeholder: "Filtro valorizacion", allowClear: true, });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

function tablero() {   

  $.post("../ajax/escritorio.php?op=tablero",  function (e, status) {

    e = JSON.parse(e);  //console.log(e);

    if (e.status == true) {
      $("#cantidad_box_producto").html(formato_miles(e.data.cant_producto));
      $("#cantidad_box_agricultor").html(formato_miles(e.data.cant_agricultor));
      $("#cantidad_box_trabajador").html(formato_miles(e.data.cant_trabajador));
      $("#cantidad_box_venta").html(formato_miles(e.data.cant_venta_producto));
    } else {
      ver_errores(e);
    } 

  }).fail( function(e) { ver_errores(e); } );
}

function sumas_totales() {   

  $.post("../ajax/escritorio.php?op=sumas_totales",  function (e, status) {

    e = JSON.parse(e);  console.log(e);

    if (e.status == true) {
      $(".footer_total_venta").html(formato_miles(e.data.total_venta));
      $(".footer_total_utilidad").html(formato_miles(e.data.total_utilidad));
      $(".footer_total_compra").html(formato_miles(e.data.total_compra));
      $(".footer_total_deuda").html(formato_miles(e.data.total_deposito_compra));
    } else {
      ver_errores(e);
    } 

  }).fail( function(e) { ver_errores(e); } );
}

init();

// :::::::::::::::::::::::::::::::::::::  C H A R T   L I N E A  -  S U B C O N T R A T O  ::::::::::::::::::::::::
$(function () {
  'use strict'

  var ticksStyle = { fontColor: '#495057', fontStyle: 'bold' };

  var mode = 'index';
  var intersect = true;

  function chart_producto() {   

    $.post("../ajax/escritorio.php?op=chart_producto",  function (e, status) {
  
      e = JSON.parse(e);  console.log(e);
  
      if (e.status == true) {

        // ================================== Grafico Lineas ==================================

        var $visitorsChart = $('#visitors-chart')
        // eslint-disable-next-line no-unused-vars
        var visitorsChart = new Chart($visitorsChart, {
          data: {
            labels: [ 'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
            datasets: [
              {
                type: 'line',
                data: e.data.total_venta,
                backgroundColor: 'transparent',
                borderColor: '#28a745',
                pointBorderColor: '#28a745',
                pointBackgroundColor: '#28a745',
                fill: false,
                label: 'Total venta'
                // pointHoverBackgroundColor: '#28a745',
                // pointHoverBorderColor    : '#28a745'
              },
              {
                type: 'line',
                data: e.data.total_pagos,
                backgroundColor: 'tansparent',
                borderColor: '#ced4da',
                pointBorderColor: '#ced4da',
                pointBackgroundColor: '#ced4da',
                fill: false,
                label: 'Total pago'
                // pointHoverBackgroundColor: '#ced4da',
                // pointHoverBorderColor    : '#ced4da'
              }
            ]
          },
          options: {
            maintainAspectRatio: false,
            tooltips: { mode: mode, intersect: intersect },
            hover: { mode: mode, intersect: intersect },
            legend: { display: true },
            scales: {
              yAxes: [{
                // display: false,
                gridLines: { display: true, lineWidth: '4px', color: 'rgba(0, 0, 0, .2)', zeroLineColor: 'transparent' },
                ticks: $.extend({ beginAtZero: true, callback: function (value) { if (value >= 1000) { value /= 1000; value += 'k';  }  return 'S/ ' + value; } }, ticksStyle)
              }],
              xAxes: [{
                display: true,
                gridLines: { display: false },
                ticks: ticksStyle
              }]
            }
          }
        });

        // ================================== Grafico Barras ==================================

        var $salesChart = $('#sales-chart')
        // eslint-disable-next-line no-unused-vars
        var salesChart = new Chart($salesChart, {
          type: 'bar',
          data: {
            labels: [ 'ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'],
            datasets: [
              { backgroundColor: '#28a745', borderColor: '#28a745', data: e.data.total_compra, label: 'Total compra' },
              { backgroundColor: '#000', borderColor: '#000', data: e.data.total_deposito, label: 'Total pago' },
              { backgroundColor: '#dc3545', borderColor: '#dc3545', data: e.data.total_kilos_pergamino, label: 'Kilos pergamino' },
              { backgroundColor: '#ffc107', borderColor: '#ffc107', data: e.data.total_kilos_coco, label: 'Kilos coco' },
            ]
          },
          options: {
            maintainAspectRatio: false,
            tooltips: {  mode: mode, intersect: intersect },
            hover: { mode: mode, intersect: intersect },
            legend: { display: true },
            scales: {
              yAxes: [{
                // display: false,
                gridLines: {
                  display: true,
                  lineWidth: '4px',
                  color: 'rgba(0, 0, 0, .2)',
                  zeroLineColor: 'transparent'
                },
                ticks: $.extend({
                  beginAtZero: true,
                  // Include a dollar sign in the ticks
                  callback: function (value) { if (value >= 1000) { value /= 1000; value += 'k';  }  return 'S/ ' + value; }
                }, ticksStyle)
              }],
              xAxes: [{
                display: true,
                gridLines: {
                  display: false
                },
                ticks: ticksStyle
              }]
            }
          }
        });

        $("#btn-download-chart-linea").on('click', function () {       
          var a = document.createElement('a');
          a.href = visitorsChart.toBase64Image();
          a.download = 'ventas_y_pagos_por_mes.png';
          // Trigger the download
          a.click();
        });

        $("#btn-download-chart-barra").on('click', function () {       
          var a = document.createElement('a');
          a.href = salesChart.toBase64Image();
          a.download = 'Compras_y_kilos_por_mes.png';
          // Trigger the download
          a.click();
        });
      } else {
        ver_errores(e);
      } 
  
    }).fail( function(e) { ver_errores(e); } );
  }       
  
  // Ejecutamos los CHARTS
  chart_producto();
  
})