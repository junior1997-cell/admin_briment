var tabla;
var editando = false;
var editando2 = false;
////////////////////////////
var array_class = [];
var array_datosPost = [];
var array_fi_ff = [];
var f1_reload = "";
var f2_reload = "";
var i_reload = "";
var total_semanas = 0;
var array_guardar_fi_ff = [];

//Función que se ejecuta al inicio
function init() {
  //Activamos el "aside"
  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Viaticos").addClass("menu-open");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#mViatico").addClass("active bg-primary");

  $("#sub_bloc_comidas").addClass("menu-open bg-color-191f24");

  $("#sub_mComidas").addClass("active bg-primary");

  $("#lBreak").addClass("active");

  $("#idproyecto").val(localStorage.getItem("nube_idproyecto"));

  listar_botoness(localStorage.getItem("nube_idproyecto"));

  listar(localStorage.getItem("nube_idproyecto"));

  //=====Guardar factura=============
  $("#guardar_registro_comprobaante").on("click", function (e) { $("#submit-form-comprobante").submit(); });

  //Initialize Select2 Elements
  $("#tipo_comprobante").select2({
    theme: "bootstrap4",
    placeholder: "Selecione tipo comprobante",
    allowClear: true,
  });

  $("#forma_pago").select2({
    theme: "bootstrap4",
    placeholder: "Selecione una forma de pago",
    allowClear: true,
  });

  // Formato para telefono
  $("[data-mask]").inputmask();

}

// abrimos el navegador de archivos
$("#doc1_i").click(function () { $("#doc1").trigger("click"); });

$("#doc1").change(function (e) { addImageApplication(e, $("#doc1").attr("id")); });

// Eliminamos el doc 1
function doc1_eliminar() {
  $("#doc1").val("");

  $("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

  $("#doc1_nombre").html("");
}

function mostrar_form_table(estados) {
  if (estados == 1) {
    $("#mostrar-tabla").show();

    $("#tabla-registro").hide();

    $("#card-regresar").hide();
    $("#card-editar").hide();
    $("#card-guardar").hide();
  } else {
    if (estados == 2) {
      $("#card-registrar").hide();
      $("#card-regresar").show();
      $("#card-editar").show();

      $("#mostrar-tabla").hide();
      $("#tabla-registro").show();

      // $("#detalle_asistencia").hide();
    } else {
      $("#card-registrar").hide();
      $("#card-regresar").show();
      $("#card-editar").hide();
      $("#card-guardar").hide();
      $("#tabla-asistencia-trab").hide();
      $("#ver_asistencia").hide();
      $("#detalle_asistencia").show();
      $("#tabla-comprobantes").hide();
    }
  }
}

function editarbreak() {
  // ocultamos los span
  $(".span-visible").hide();
  // mostramos los inputs
  $(".input-visible").show();
  $(".textarea-visible").attr("readonly", false);

  $("#card-editar").hide();
  $("#card-guardar").show();
}

//Función Listar
function listar(nube_idproyecto) {

  tabla = $("#tabla-resumen-break-semanal") .dataTable({
    responsive: true,
    lengthMenu: [ [5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"], ], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
    { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,4,5,6,2], } }, 
    { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,4,5,6,2], } }, 
    { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,4,5,6,2], }, orientation: 'landscape', pageSize: 'LEGAL',  }, 
    {extend: "colvis"} ,
    ],
    ajax: {
      url: "../ajax/break.php?op=listar_totales_semana&nube_idproyecto=" + nube_idproyecto,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != "") {
        $("td", row).eq(0).addClass("text-center");
      }
      // columna:total
      if (data[2] != "") {
        $("td", row).eq(2).addClass("text-nowrap text-right");
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
    columnDefs: [
      { targets: [4,5,6], visible: false, searchable: false, },    
    ],
  }).DataTable();

}

//Función Listar
function listar_botoness(nube_idproyecto) {
  //array_fi_ff=[];
  //Listar semanas(botones)
  $.post("../ajax/break.php?op=listar_semana_botones", { nube_idproyecto: nube_idproyecto }, function (e, status) {
    e = JSON.parse(e); console.log(e);

    if (e.status == true) {
      // validamos la existencia de DATOS
      if (e) {
        var dia_regular = 0;
        var weekday_regular = extraer_dia_semana(e.data.fecha_inicio);
        var estado_regular = false;

        if (weekday_regular == "Domingo") {
          dia_regular = -1;
        } else {
          if (weekday_regular == "Lunes") {
            dia_regular = -2;
          } else {
            if (weekday_regular == "Martes") {
              dia_regular = -3;
            } else {
              if (weekday_regular == "Miercoles") {
                dia_regular = -4;
              } else {
                if (weekday_regular == "Jueves") {
                  dia_regular = -5;
                } else {
                  if (weekday_regular == "Viernes") {
                    dia_regular = -6;
                  } else {
                    if (weekday_regular == "Sábado") {
                      dia_regular = -7;
                    }
                  }
                }
              }
            }
          }
        }
        // console.log(e.data.fecha_inicio, dia_regular, weekday_regular);

        $("#Lista_breaks").html("");

        var fecha = format_d_m_a(e.data.fecha_inicio);
        var fecha_f = "";
        var fecha_i = ""; //e.data.fecha_inicio

        var cal_mes = false;
        var i = 0;
        var cont = 0;

        while (cal_mes == false) {
          cont = cont + 1;
          fecha_i = fecha;

          if (estado_regular) {
            fecha_f = sumaFecha(6, fecha_i);
          } else {
            fecha_f = sumaFecha(7 + dia_regular, fecha_i);
            estado_regular = true;
          }

          let val_fecha_f = new Date(format_a_m_d(fecha_f));
          let val_fecha_proyecto = new Date(e.data.fecha_fin);

          // console.log(fecha_f + ' - '+e.data.fecha_fin);
          array_fi_ff.push({ fecha_in: format_a_m_d(fecha_i), fecha_fi: format_a_m_d(fecha_f), num_semana: cont });
          //array_data_fi_ff.push()

          $("#Lista_breaks").append(
            ` <button id="boton-${i}" type="button" class="mb-2 btn bg-gradient-info text-center" onclick="datos_semana('${fecha_i}', '${fecha_f}', '${i}', '${cont}');"><i class="far fa-calendar-alt"></i> Semana ${cont}<br>${fecha_i} // ${fecha_f}</button>`
          );

          if (val_fecha_f.getTime() >= val_fecha_proyecto.getTime()) {
            cal_mes = true;
          } else {
            cal_mes = false;
          }

          fecha = sumaFecha(1, fecha_f);

          i++;
        }
      } else {
        $("#Lista_breaks").html(`<div class="info-box shadow-lg w-600px"> 
          <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span> 
          <div class="info-box-content"> 
            <span class="info-box-text">Alerta</span> 
            <span class="info-box-number">No has definido los bloques de fechas del proyecto. <br>Ingresa al ESCRITORIO y EDITA tu proyecto selecionado.</span> 
          </div> 
        </div>`);
      }
      console.log(array_fi_ff);

    } else {
      ver_errores(e);
    }


  }).fail( function(e) { ver_errores(e); } );
}

//Función para guardar o editar
function guardaryeditar_semana_break() {
  $("#modal-cargando").modal("show");
  $.ajax({
    url: "../ajax/break.php?op=guardaryeditar",
    type: "POST",
    data: {
      array_break: JSON.stringify(array_datosPost),
      fechas_semanas_btn: JSON.stringify(array_guardar_fi_ff),
      idproyecto: localStorage.getItem("nube_idproyecto"),
    },
    // contentType: false,
    // processData: false,
    success: function (e) {
            
      try {

        e = JSON.parse(e);        console.log(e); 

        if (e.status == true) {

          datos_semana(f1_reload, f2_reload, i_reload);
          listar(localStorage.getItem("nube_idproyecto"));
  
          $("#icono-respuesta").html(
            `<div class="swal2-icon swal2-success swal2-icon-show" style="display: flex;"> <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div> <span class="swal2-success-line-tip"></span> <span class="swal2-success-line-long"></span> <div class="swal2-success-ring"></div> <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div> <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div> </div>  <div  class="text-center"> <h2 class="swal2-title" id="swal2-title" >Correcto!</h2> <div id="swal2-content" class="swal2-html-container" style="display: block;">Asistencia registrada correctamente</div> </div>`
          );
  
          $(".progress-bar").addClass("bg-success");
          $("#barra_progress").text("100% Completado!");

        }else{  

          ver_errores(e);
        } 

      } catch (err) {

        console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>');
      } 

    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener(
        "progress",
        function (evt) {
          if (evt.lengthComputable) {
            var percentComplete = (evt.loaded / evt.total) * 100;
            /*console.log(percentComplete + '%');*/
            $("#barra_progress").css({ width: percentComplete + "%" });

            $("#barra_progress").text(percentComplete.toFixed(2) + " %");

            if (percentComplete === 100) {
              setTimeout(l_m, 600);
            }
          }
        },
        false
      );
      return xhr;
    },
  });
}

function l_m() {
  $(".progress-bar").removeClass("progress-bar-striped");
}

function cerrar_modal() {
  $("#modal-cargando").modal("hide");
  $(".progress-bar").removeClass("bg-success bg-danger");
  $(".progress-bar").addClass("progress-bar-striped");
}

////////////////////////////datos_semana////////////////////////////////////////////////
// listamos la data de una quincena selecionada
function datos_semana(f1, f2, i, cont) {
  console.log("i" + i);
  array_guardar_fi_ff = [];
  array_guardar_fi_ff.push({ fecha_in_btn: format_a_m_d(f1), fecha_fi_btn: format_a_m_d(f2), num_semana: cont });
  console.log(array_guardar_fi_ff);
  var tabla_bloc_dia_1 = "";
  var tabla_bloc_cantidad_2 = "";
  var tabla_bloc_precio_3 = "";
  var tabla_bloc_descripcion_4 = "";
  var tabla_bloc_semana = "";
  f1_reload = f1;
  f2_reload = f2;
  i_reload = i;

  $("#card-editar").show();
  $("#card-guardar").hide();

  // vaciamos el array
  array_datosPost = [];

  // pintamos el botón
  pintar_boton_selecionado(i);

  //capturamos el id del proyecto.
  var nube_idproyect = localStorage.getItem("nube_idproyecto"); //console.log('Quicena: '+f1 + ' al ' +f2 + ' proyect-id: '+nube_idproyect);

  var fecha_inicial_semana = f1;
  var count_numero_dia = 1;

  var dia_regular = 0;
  var total_pago = 0;

  var weekday_regular = extraer_dia_semana(format_a_m_d(fecha_inicial_semana));
  //console.log(weekday_regular);
  // asignamos un numero para restar y llegar al dia DOMIGO
  if (weekday_regular == "Domingo") {
    dia_regular = -0;
  } else {
    if (weekday_regular == "Lunes") {
      dia_regular = -1;
    } else {
      if (weekday_regular == "Martes") {
        dia_regular = -2;
      } else {
        if (weekday_regular == "Miercoles") {
          dia_regular = -3;
        } else {
          if (weekday_regular == "Jueves") {
            dia_regular = -4;
          } else {
            if (weekday_regular == "Viernes") {
              dia_regular = -5;
            } else {
              if (weekday_regular == "Sábado") {
                dia_regular = -6;
              }
            }
          }
        }
      }
    }
  }

  var fecha_inicial_semana_regular = sumaFecha(dia_regular, fecha_inicial_semana);
  //Regulamos los días hasta el inicio del dia del inicio del proyecto

  for (var j = 1; j <= dia_regular * -1; j++) {

    var weekday = extraer_dia_semana(format_a_m_d(fecha_inicial_semana_regular));

    tabla_bloc_dia_1 = `<td class="bg-color-b4bdbe47"> <b>${count_numero_dia}. ${weekday} : </b> ${fecha_inicial_semana_regular}</td>`;

    tabla_bloc_cantidad_2 = `<td class="bg-color-b4bdbe47"><span> - </span></td>`;

    tabla_bloc_precio_3 = `<td class="bg-color-b4bdbe47"><span> - </span></td>`;

    tabla_bloc_descripcion_4 = `<td class="bg-color-b4bdbe47"><textarea class="bg-color-b4bdbe47" cols="30" rows="1" readonly style="width: 100%;"></textarea></td>`;

    //fila
    tabla_bloc_semana = tabla_bloc_semana.concat(`<tr>${tabla_bloc_dia_1}${tabla_bloc_cantidad_2}${tabla_bloc_precio_3}${tabla_bloc_descripcion_4}</tr>`);

    // aumentamos mas un dia hasta llegar al dia "dia_regular"
    fecha_inicial_semana_regular = sumaFecha(1, fecha_inicial_semana_regular);

    count_numero_dia++;
  }
  // ocultamos las tablas
  mostrar_form_table(2);

  $.post("../ajax/break.php?op=ver_datos_semana", { f1: format_a_m_d(f1), f2: format_a_m_d(f2), nube_idproyect: nube_idproyect }, function (e, status) {
    e = JSON.parse(e);    console.log(e);

    if (e.status == true) {

      // existe alguna asistencia -------
      if (e.data.length != 0) {

        var i;
        var fecha = f1; //console.log("tiene data");

        for (i = 1; i <= 7 + dia_regular; i++) {

          var estado_fecha = false;
          var fecha_compra_encontrado = "";
          var costo_parcial_encontrado = 0;
          var descripcion_encontrado = "";
          var cantidad_encontrado = 0;
          var idbreak = "";

          // buscamos las fechas
          for (let i = 0; i < e.data.length; i++) {

            let split_f = e.data[i]["fecha_compra"];

            let fecha_semana = new Date(format_a_m_d(fecha));
            let fecha_asistencia = new Date(split_f);

            if (fecha_semana.getTime() == fecha_asistencia.getTime()) {
              total_pago = total_pago + parseFloat(e.data[i]["costo_parcial"]);

              fecha_compra_encontrado = e.data[i]["fecha_compra"];
              costo_parcial_encontrado = e.data[i]["costo_parcial"];
              descripcion_encontrado = e.data[i]["descripcion"];
              cantidad_encontrado = e.data[i]["cantidad"];
              idbreak = e.data[i]["idbreak"];
              estado_fecha = true;
            }
          } //end for

          // imprimimos la fecha compra encontrada
          if (estado_fecha) {
            var weekday = extraer_dia_semana(fecha_compra_encontrado); //console.log(weekday);

            if (weekday != "Sábado") {
              //-------------------------------------------------------------
              tabla_bloc_dia_1 = `<td> <b>${count_numero_dia}. ${weekday}:</b>  ${format_d_m_a(
                fecha_compra_encontrado
              )} <input type="hidden" class="fecha_compra_${count_numero_dia}" value="${fecha_compra_encontrado}"><input type="hidden" class="idbreak_${count_numero_dia}" value="${idbreak}"> <input type="hidden" class="dia_semana_${count_numero_dia}" value="${weekday}"> </td>`;

              tabla_bloc_cantidad_2 = `<td><span class="span-visible">${cantidad_encontrado}</span><input type="number" value="${cantidad_encontrado}" class="cantidad_compra_${count_numero_dia} hidden input-visible" onkeyup="obtener_datos_semana();" onchange="obtener_datos_semana();"></td>`;

              tabla_bloc_precio_3 = `<td><span class="span-visible">${costo_parcial_encontrado}</span><input type="number" value="${costo_parcial_encontrado}" class="precio_compra_${count_numero_dia} hidden input-visible"  onkeyup="obtener_datos_semana();" onchange="obtener_datos_semana();" ></td>`;

              tabla_bloc_descripcion_4 = `<td><textarea cols="30" rows="1" readonly class="textarea-visible descripcion_compra_${count_numero_dia}" onkeyup="obtener_datos_semana();" value="${descripcion_encontrado}" style="width: 100%;;">${descripcion_encontrado}</textarea></td>`;

              tabla_bloc_semana = tabla_bloc_semana.concat(`<tr>${tabla_bloc_dia_1}${tabla_bloc_cantidad_2}${tabla_bloc_precio_3}${tabla_bloc_descripcion_4}</tr>`);
              //
            } else {
              tabla_bloc_dia_1 = `<td class="bg-color-b4bdbe47"> <b>${count_numero_dia}. ${weekday} : </b> ${format_d_m_a(fecha_compra_encontrado)}</td>`;

              tabla_bloc_cantidad_2 = `<td class="bg-color-b4bdbe47"><span> - </span></td>`;

              tabla_bloc_precio_3 = `<td class="bg-color-b4bdbe47"><span> - </span></td>`;

              tabla_bloc_descripcion_4 = `<td class="bg-color-b4bdbe47"><textarea class="bg-color-b4bdbe47" cols="30" rows="1" readonly style="width: 100%;"></textarea></td>`;

              //fila
              tabla_bloc_semana = tabla_bloc_semana.concat(`<tr>
                      ${tabla_bloc_dia_1}
                      ${tabla_bloc_cantidad_2}
                      ${tabla_bloc_precio_3}
                      ${tabla_bloc_descripcion_4}
                  </tr>`);
            }
          } else {
            var weekday = extraer_dia_semana(format_a_m_d(fecha)); //console.log(weekday);

            if (weekday != "Sábado") {
              tabla_bloc_dia_1 = `<td> <b>${count_numero_dia}. ${weekday}:</b>  ${fecha} <input type="hidden" class="fecha_compra_${count_numero_dia}" value="${format_a_m_d(
                fecha
              )}"> <input type="hidden" class="dia_semana_${count_numero_dia}" value="${weekday}"> </td>`;

              tabla_bloc_cantidad_2 = `<td><span class="span-visible">-</span><input type="number" value="" class=" cantidad_compra_${count_numero_dia} hidden input-visible" onkeyup="obtener_datos_semana();" onchange="obtener_datos_semana();"></td>`;

              tabla_bloc_precio_3 = `<td><span class="span-visible">-</span><input type="number" value="" class=" precio_compra_${count_numero_dia} hidden input-visible"  onkeyup="obtener_datos_semana();" onchange="obtener_datos_semana();" ></td>`;

              tabla_bloc_descripcion_4 = `<td><textarea cols="30" rows="1" readonly class="textarea-visible descripcion_compra_${count_numero_dia}" onkeyup="obtener_datos_semana();" value="" style=" width: 100%;"></textarea></td>`;

              tabla_bloc_semana = tabla_bloc_semana.concat(`<tr>${tabla_bloc_dia_1}${tabla_bloc_cantidad_2}${tabla_bloc_precio_3}${tabla_bloc_descripcion_4}</tr>`);
              //
            } else {
              tabla_bloc_dia_1 = `<td class="bg-color-b4bdbe47"> <b>${count_numero_dia}. ${weekday} : </b> ${fecha}</td>`;

              tabla_bloc_cantidad_2 = `<td class="bg-color-b4bdbe47"><span> - </span></td>`;

              tabla_bloc_precio_3 = `<td class="bg-color-b4bdbe47"><span> - </span></td>`;

              tabla_bloc_descripcion_4 = `<td class="bg-color-b4bdbe47"><textarea class="bg-color-b4bdbe47" cols="30" rows="1" readonly style="width: 100%;"></textarea></td>`;

              //fila
              tabla_bloc_semana = tabla_bloc_semana.concat(`<tr>
                      ${tabla_bloc_dia_1}
                      ${tabla_bloc_cantidad_2}
                      ${tabla_bloc_precio_3}
                      ${tabla_bloc_descripcion_4}
                  </tr>`);
            }
          }
          //aumentamos el número de días
          count_numero_dia++;
          // aumentamos mas un dia hasta llegar al dia 15
          fecha = sumaFecha(1, fecha);
        } //end for

        // no existe ninguna asistencia -------
      } else {

        var fecha = f1; //console.log("no ninguna fecha asistida");

        for (i = 1; i <= 7 + dia_regular; i++) {

          var weekday = extraer_dia_semana(format_a_m_d(fecha));

          if (weekday != "Sábado") {

            tabla_bloc_dia_1 = `<td> <b>${count_numero_dia}. ${weekday}:</b>  ${fecha} <input type="hidden" class="fecha_compra_${count_numero_dia}" value="${format_a_m_d(
              fecha
            )}"> <input type="hidden" class="dia_semana_${count_numero_dia}" value="${weekday}"> </td>`;

            tabla_bloc_cantidad_2 = `<td><span class="span-visible">-</span><input type="number" value="" class="cantidad_compra_${count_numero_dia} hidden input-visible" onkeyup="obtener_datos_semana();" onchange="obtener_datos_semana();"></td>`;

            tabla_bloc_precio_3 = `<td><span class="span-visible">-</span><input type="number" value="" class="precio_compra_${count_numero_dia} hidden input-visible"  onkeyup="obtener_datos_semana();" onchange="obtener_datos_semana();" ></td>`;

            tabla_bloc_descripcion_4 = `<td><textarea cols="30" rows="1" readonly class="textarea-visible descripcion_compra_${count_numero_dia}" onkeyup="obtener_datos_semana();" value="" style="width:100%;"></textarea></td>`;

            tabla_bloc_semana = tabla_bloc_semana.concat(`<tr>${tabla_bloc_dia_1}${tabla_bloc_cantidad_2}${tabla_bloc_precio_3}${tabla_bloc_descripcion_4}</tr>`);
            //
          } else {
            tabla_bloc_dia_1 = `<td class="bg-color-b4bdbe47"> <b>${count_numero_dia}. ${weekday} : </b> ${fecha}</td>`;

            tabla_bloc_cantidad_2 = `<td class="bg-color-b4bdbe47"><span> - </span></td>`;

            tabla_bloc_precio_3 = `<td class="bg-color-b4bdbe47"><span> - </span></td>`;

            tabla_bloc_descripcion_4 = `<td class="bg-color-b4bdbe47"><textarea class="bg-color-b4bdbe47" cols="30" rows="1" readonly style="width: 100%;"></textarea></td>`;

            //fila
            tabla_bloc_semana = tabla_bloc_semana.concat(`<tr>
                    ${tabla_bloc_dia_1}
                    ${tabla_bloc_cantidad_2}
                    ${tabla_bloc_precio_3}
                    ${tabla_bloc_descripcion_4}
                </tr>`);
          }
          //contamos el número del día
          count_numero_dia++;
          // aumentamos mas un dia hasta llegar al dia 15
          fecha = sumaFecha(1, fecha);
        } //end for
      }
    } else {

      ver_errores(e);
    }

    $("#monto_total").html(formato_miles(total_pago.toFixed(2)));
    $("#data_table_body").html(tabla_bloc_semana);

  }).fail( function(e) { ver_errores(e); } ); //end post - ver_datos_semana

  $("#cargando-1-fomulario").show();
  $("#cargando-2-fomulario").hide();
  $('[data-toggle="tooltip"]').tooltip();

  count_dias_asistidos = 0;
  horas_nomr_total = 0;
  horas_extr_total = 0;

}

function obtener_datos_semana() {

  var fecha_compra = "";
  var dia_semana = "";
  var cantidad_compra = 0;
  var precio_compra = 0;
  var descripcion_compra = "";
  var monto_total = 0;
  var idbreak = "";

  array_datosPost = [];

  for (let j = 1; j <= 7; j++) {
    //console.log(j);
    fecha_compra = $(`.fecha_compra_${j}`).val();
    dia_semana = $(`.dia_semana_${j}`).val();
    cantidad_compra = $(`.cantidad_compra_${j}`).val();
    precio_compra = $(`.precio_compra_${j}`).val();
    descripcion_compra = $(`.descripcion_compra_${j}`).val();

    if ($(`.idbreak_${j}`).val() != undefined) {
      idbreak = $(`.idbreak_${j}`).val();
    }

    if (cantidad_compra != undefined) {
      if (precio_compra >= 0 && precio_compra != "") {
        monto_total = monto_total + parseFloat(precio_compra);
      } else {
        monto_total += 0;
      }

      array_datosPost.push({
        fecha_compra: fecha_compra,
        dia_semana: dia_semana,
        cantidad_compra: cantidad_compra,
        precio_compra: precio_compra,
        descripcion_compra: descripcion_compra,
        idbreak: idbreak,
      });
    }
  }
  console.log(array_datosPost);
  $("#monto_total").html(formato_miles(monto_total.toFixed(2)));
  //$("#monto_total").html('100.00');
}

// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------   
// ------------------C O M P R O B A N T E S   B R E A K ------------------------------
// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------

function ocultar() {

  $("#regresar_aprincipal").show();
  $("#Lista_breaks").hide();
  $("#mostrar-tabla").hide();
  $("#tabla-registro").hide();
  $("#tabla-comprobantes").show();
  $("#guardar").show();
}

function regresar() {
  $("#regresar_aprincipal").hide();
  $("#Lista_breaks").show();
  $("#mostrar-tabla").show();
  $("#tabla-registro").hide();
  $("#tabla-comprobantes").hide();
  $("#guardar").hide();
}

function limpiar_comprobante() {
  $("#nro_comprobante").val("");
  $("#monto").val("");
  $("#idfactura_break").val("");
  $("#fecha_emision").val("");
  $("#descripcion").val("");
  $("#subtotal").val("");
  $("#igv").val("");
  $("#val_igv").val(""); 
  $("#tipo_gravada").val(""); 
  $("#tipo_comprobante").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change");

  $("#num_documento").val("");
  $("#razon_social").val("");
  $("#direccion").val("");

  $("#doc_old_1").val("");
  $("#doc1").val("");
  $("#doc1_ver").html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $("#doc1_nombre").html("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function guardaryeditar_factura(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-agregar-comprobante")[0]);

  $.ajax({
    url: "../ajax/break.php?op=guardaryeditar_Comprobante",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {

        e = JSON.parse(e); console.log(e); 

        if (e.status == true) {

          toastr.success("servicio registrado correctamente");

          tabla.ajax.reload(null, false);
  
          $("#modal-agregar-comprobante").modal("hide");
          listar_comprobantes(localStorage.getItem("idsemana_break_nube"));
          total_monto(localStorage.getItem("idsemana_break_nube"));
          limpiar_comprobante();
        }else{  
          ver_errores(e);
        } 

      } catch (err) {

        console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>');
      } 

    },
  });
}

function listar_comprobantes(idsemana_break) {
  localStorage.setItem("idsemana_break_nube", idsemana_break);

  ocultar();
  $("#idsemana_break").val(idsemana_break);

  tabla = $("#t-comprobantes")
    .dataTable({
      responsive: true,
      lengthMenu: [
        [5, 10, 25, 75, 100, 200, -1],
        [5, 10, 25, 75, 100, 200, "Todos"],
      ], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: [
        { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,11,10,12,13,14,2,5,6,7,15,16,8], } }, 
        { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,11,10,12,13,14,2,5,6,7,15,16,8], } }, 
        { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,11,10,12,13,14,2,5,6,7,15,16,8], }, orientation: 'landscape', pageSize: 'LEGAL',  }, 
        {extend: "colvis"} ,
        ],
      ajax: {
        url: "../ajax/break.php?op=listar_comprobantes&idsemana_break=" + idsemana_break,
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        },
      },
      createdRow: function (row, data, ixdex) {
        // columna: #
        if (data[0] != "") {
          $("td", row).eq(0).addClass("text-center");
        }
        // columna: 1
        if (data[1] != "") {
          $("td", row).eq(1).addClass("text-nowrap");
        }
        // columna: sub total
        if (data[5] != "") {
          $("td", row).eq(5).addClass("text-nowrap text-right");
        }
        // columna: igv
        if (data[6] != "") {
          $("td", row).eq(6).addClass("text-nowrap text-right");
        }
        // columna: total
        if (data[7] != "") {
          $("td", row).eq(7).addClass("text-nowrap text-right");
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
      columnDefs: [
        { targets: [10,11,12,13,14,15,16], visible: false, searchable: false, },    
      ],
    })
    .DataTable();
  total_monto(localStorage.getItem("idsemana_break_nube"));
}

function calc_total() {

  $(".nro_comprobante").html("Núm. Comprobante");
  $( "#num_documento" ).rules( "remove","required" );

  var total         = es_numero($('#monto').val()) == true? parseFloat($('#monto').val()) : 0;
  var val_igv       = es_numero($('#val_igv').val()) == true? parseFloat($('#val_igv').val()) : 0;
  var subtotal      = 0; 
  var igv           = 0;

  console.log(total, val_igv); console.log($('#monto').val(), $('#val_igv').val()); console.log('----------');

  if ($("#tipo_comprobante").select2("val")=="" || $("#tipo_comprobante").select2("val")==null) {
    $("#subtotal").val(redondearExp(total));
    $("#igv").val("0.00"); 
    $("#val_igv").val("0.00"); 
    $("#tipo_gravada").val("NO GRAVADA"); $(".tipo_gravada").html("(NO GRAVADA)"); 
    $("#val_igv").prop("readonly",true);

    $(".div_ruc").hide(); $(".div_razon_social").hide();
    $("#num_documento").val(""); $("#razon_social").val("");
    
  }else if ($("#tipo_comprobante").select2("val") =="Ninguno") {  
    $("#subtotal").val(redondearExp(total));
    $("#igv").val("0.00"); 
    $("#val_igv").val("0.00"); 
    $("#tipo_gravada").val("NO GRAVADA"); $(".tipo_gravada").html("(NO GRAVADA)"); 
    $("#val_igv").prop("readonly",true);
    $(".nro_comprobante").html("Núm. de Operación");

    $(".div_ruc").hide(); $(".div_razon_social").hide();
    $("#num_documento").val(""); $("#razon_social").val("");

  }else if ($("#tipo_comprobante").select2("val") =="Boleta") {  
    $("#subtotal").val(redondearExp(total));
    $("#igv").val("0.00"); 
    $("#val_igv").val("0.00"); 
    $("#tipo_gravada").val("NO GRAVADA"); $(".tipo_gravada").html("(NO GRAVADA)"); 
    $("#val_igv").prop("readonly",true);
    $(".nro_comprobante").html("Núm. de Operación");

    $(".div_ruc").show(); $(".div_razon_social").show();
    $("#num_documento").val(""); $("#razon_social").val("");
    $("#num_documento").rules("add", { required: true, messages: { required: "Campo requerido" } });


  }else if ($("#tipo_comprobante").select2("val") =="Factura") {  

    $("#val_igv").prop("readonly",false);    

    if (total == null || total == "") {
      $("#subtotal").val(0.00);
      $("#igv").val(0.00); 
      $("#tipo_gravada").val('NO GRAVADA'); $(".tipo_gravada").html("(NO GRAVADA)");
    } else if (val_igv == null || val_igv == "") {  
      $("#subtotal").val(redondearExp(total));
      $("#igv").val(0.00);
      $("#tipo_gravada").val('NO GRAVADA'); $(".tipo_gravada").html("(NO GRAVADA)");
    }else{     

      subtotal = quitar_igv_del_precio(total, val_igv, 'decimal');
      igv = total - subtotal;

      $("#subtotal").val(redondearExp(subtotal));
      $("#igv").val(redondearExp(igv));

      if (val_igv > 0 && val_igv <= 1) {
        $("#tipo_gravada").val('GRAVADA'); $(".tipo_gravada").html("(GRAVADA)")
      } else {
        $("#tipo_gravada").val('NO GRAVADA'); $(".tipo_gravada").html("(NO GRAVADA)");
      }    
    }
    $(".div_ruc").show(); $(".div_razon_social").show();

    $("#num_documento").rules("add", { required: true, messages: { required: "Campo requerido" } });

  } else {
    $("#subtotal").val(redondearExp(total));
    $("#igv").val("0.00");
    $("#val_igv").val("0.00"); 
    $("#tipo_gravada").val("NO GRAVADA"); $(".tipo_gravada").html("(NO GRAVADA)");
    $("#val_igv").prop("readonly",true);
    $(".div_ruc").hide(); $(".div_razon_social").hide();   
  }
  if (val_igv > 0 && val_igv <= 1) {
    $("#tipo_gravada").val('GRAVADA'); $(".tipo_gravada").html("(GRAVADA)")
  } else {
    $("#tipo_gravada").val('NO GRAVADA'); $(".tipo_gravada").html("(NO GRAVADA)");
  }

}

function select_comprobante() {
  if ($("#tipo_comprobante").select2("val") == "Factura") {
    $("#val_igv").prop("readonly",false);
    $("#val_igv").val(0.18); 
    $("#tipo_gravada").val('GRAVADA'); $(".tipo_gravada").html("(GRAVADA)");
  }else {
    $("#val_igv").val(0.00); 
    $("#tipo_gravada").val('NO GRAVADA'); $(".tipo_gravada").html("(NO GRAVADA)");
  }  
}

function quitar_igv_del_precio(precio , igv, tipo ) {
  console.log(precio , igv, tipo);
  var precio_sin_igv = 0;

  switch (tipo) {
    case 'decimal':

      if (parseFloat(precio) != NaN && igv > 0 && igv <= 1 ) {
        precio_sin_igv = ( parseFloat(precio) * 100 ) / ( ( parseFloat(igv) * 100 ) + 100 )
      }else{
        precio_sin_igv = precio;
      }
    break;

    case 'entero':

      if (parseFloat(precio) != NaN && igv > 0 && igv <= 100 ) {
        precio_sin_igv = ( parseFloat(precio) * 100 ) / ( parseFloat(igv)  + 100 )
      }else{
        precio_sin_igv = precio;
      }
    break;
  
    default:
      $(".val_igv").html('IGV (0%)');
      toastr.success('No has difinido un tipo de calculo de IGV.')
    break;
  } 
  
  return precio_sin_igv; 
}

function mostrar_comprobante(idfactura_break) {
  limpiar_comprobante();

  $("#modal-agregar-comprobante").modal("show");
  $("#tipo_comprobante").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change");

  $.post("../ajax/break.php?op=mostrar_comprobante", { idfactura_break: idfactura_break }, function (e, status) {

    e = JSON.parse(e); console.log(e);   

    if (e.status == true) {

      $("#tipo_comprobante").val(e.data.tipo_comprobante).trigger("change");
      $("#forma_pago").val(e.data.forma_de_pago).trigger("change");

      $("#idfactura_break ").val(e.data.idfactura_break);
      $("#nro_comprobante").val(e.data.nro_comprobante);    
      $("#fecha_emision").val(e.data.fecha_emision);
      $("#descripcion").val(e.data.descripcion);    
      $("#num_documento").val(e.data.ruc);
      $("#razon_social").val(e.data.razon_social);
      $("#direccion").val(e.data.direccion);

      $("#monto").val(redondearExp(e.data.monto));
      $("#subtotal").val(redondearExp(e.data.subtotal));
      $("#igv").val(redondearExp(e.data.igv));
      $("#val_igv").val(e.data.val_igv).trigger("change");  

      if (e.data.comprobante == "" || e.data.comprobante == null) {
        $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

        $("#doc1_nombre").html("");

        $("#doc_old_1").val("");
        $("#doc1").val("");
      } else {
        $("#doc_old_1").val(e.data.comprobante);

        $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(e.data.comprobante)}</i></div></div>`);

        // cargamos la imagen adecuada par el archivo
        $("#doc1_ver").html(doc_view_extencion(e.data.comprobante,'break', 'comprobante', '100%', '210' ));
        
      }

    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

//Función para desactivar registros
function eliminar_comprobante(idfactura_break, tipo,numero) {

  crud_eliminar_papelera(
    "../ajax/break.php?op=desactivar_comprobante",
    "../ajax/break.php?op=eliminar_comprobante", 
    idfactura_break, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del> ${tipo} - ${numero}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ total_monto(localStorage.getItem("idsemana_break_nube")); },
    function(){ tabla.ajax.reload(null, false);; },   
    false, 
    false,
    false
  );

}

function ver_modal_comprobante(comprobante,tipo,numero_comprobante) {

  var dia_actual = moment().format('DD-MM-YYYY');
  $(".nombre_comprobante").html(`${tipo} ${numero_comprobante}`);
  $('#modal-ver-comprobante').modal("show");
  $('#ver_fact_pdf').html(doc_view_extencion(comprobante, 'break', 'comprobante', '100%', '550'));

  if (DocExist(`dist/docs/break/comprobante/${comprobante}`) == 200) {
    $("#iddescargar").attr("href","../dist/docs/break/comprobante/"+comprobante).attr("download", `${tipo}-${numero_comprobante}  - ${dia_actual}`).removeClass("disabled");
    $("#ver_completo").attr("href","../dist/docs/break/comprobante/"+comprobante).removeClass("disabled");
  } else {
    $("#iddescargar").addClass("disabled");
    $("#ver_completo").addClass("disabled");
  }

  $('.jq_image_zoom').zoom({ on:'grab' }); 
  $(".tooltip").removeClass("show").addClass("hidde");

}

//-total Pagos
function total_monto(idsemana_break) {

  $("#monto_total_f").html("00.0");

  $.post("../ajax/break.php?op=total_monto", { idsemana_break: idsemana_break }, function (e, status) {

    e = JSON.parse(e); console.log(e);   

    if (e.status == true) {

      num = e.data.total;
      if (!num || num == "NaN") return "0.00";
      if (num == "Infinity") return "&#x221e;";
      num = num.toString().replace(/\$|\,/g, "");
      if (isNaN(num)) num = "0";
      sign = num == (num = Math.abs(num));
      num = Math.floor(num * 100 + 0.50000000001);
      cents = num % 100;
      num = Math.floor(num / 100).toString();
      if (cents < 10) cents = "0" + cents;
      for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++) num = num.substring(0, num.length - (4 * i + 3)) + "," + num.substring(num.length - (4 * i + 3));
      total_mont_f = (sign ? "" : "-") + num + "." + cents;

      $("#monto_total_f").html('S/ '+total_mont_f);

    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

init();

$(function () {
  // Aplicando la validacion del select cada vez que cambie
  $("#forma_pago").on("change", function () { $(this).trigger("blur"); });
  $("#tipo_comprobante").on("change", function () { $(this).trigger("blur"); });

  $("#form-agregar-comprobante").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      forma_pago: { required: true },
      tipo_comprobante: { required: true },
      monto: { required: true },
      fecha_emision: { required: true },
      descripcion: { minlength: 1 },
      foto2_i: { required: true },
      val_igv: { required: true, number: true, min:0, max:1 },
    },
    messages: {
      forma_pago: { required: "Seleccionar una forma de pago", },
      tipo_comprobante: { required: "Seleccionar un tipo de comprobante", },
      monto: { required: "Por favor ingresar el monto", },
      fecha_emision: { required: "Por favor ingresar la fecha de emisión", },
      val_igv: { required: "Campo requerido", number: 'Ingrese un número', min:'Mínimo 0', max:'Maximo 1' },
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
      guardaryeditar_factura(e);
    },
  });

  //agregando la validacion del select  ya que no tiene un atributo name el plugin
  $("#forma_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#tipo_comprobante").rules("add", { required: true, messages: { required: "Campo requerido" } });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

//extraer_dia_semana
function extraer_dia_semana(fecha) {
  const fechaComoCadena = fecha; // día fecha
  const dias = ["Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado", "Domingo"]; //
  const numeroDia = new Date(fechaComoCadena).getDay();
  const nombreDia = dias[numeroDia];
  return nombreDia;
}

function pintar_boton_selecionado(i) {
  localStorage.setItem("i", i); //enviamos el ID-BOTON al localStorage
  // validamos el id para pintar el boton
  if (localStorage.getItem("boton_id")) {
    let id = localStorage.getItem("boton_id"); //console.log('id-nube-boton '+id);

    $("#boton-" + id).removeClass("click-boton");

    localStorage.setItem("boton_id", i);

    $("#boton-" + i).addClass("click-boton");
  } else {
    localStorage.setItem("boton_id", i);

    $("#boton-" + i).addClass("click-boton");
  }
}

//despintar_btn_select
function despintar_btn_select() {
  if (localStorage.getItem("boton_id")) {
    let id = localStorage.getItem("boton_id");
    $("#boton-" + id).removeClass("click-boton");
  }
}
