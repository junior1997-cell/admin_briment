<!-- MDOAL - AGREGAR USUARIO -->
<div class="modal fade" id="modal-contacto-desarrollador">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Contacto con el Ing. de Sistemas</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="text-danger" aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="card-body">
          <div class="row" >
            <!-- Tipo de documento -->
            <div class="col-lg-12">
              <!-- Widget: user widget style 1 -->
              <div class="card card-widget widget-user shadow">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-info">
                  <h3 class="widget-user-username">JDL Technology S.A.C</h3>
                  <h5 class="widget-user-desc">Desarrolladores de sofware</h5>
                </div>
                <div class="widget-user-image" style="margin-left: -65px !important;">
                  <img class="img-circle elevation-2" src="../dist/img/desarrolladores/jdl_logo.jpg" alt="User Avatar" style="width: 130px !important;" />
                </div>
                <div class="card-footer mt-4">
                  <div class="row justify-content-center">
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-3 border-right">
                      <div class="description-block">
                        <h5 class="description-header nombre_contacto mt-2 mb-2">WhatsApp</h5>
                        <a href="https://api.whatsapp.com/send?phone=+51921305769&text=*Hola buenos dias amigo desarrollador!!*" target="_blank" class="description-text desarrollador-whatsapp"><i class="fab fa-whatsapp fa-3x"></i></a>
                      </div>
                      <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-3 border-right">
                      <div class="description-block">
                        <h5 class="description-header nombre_contacto mt-2 mb-2">Facebook</h5>
                        <a href="https://www.facebook.com/profile.php?id=100086343481837"  target="_blank" class="description-text desarrollador-facebook"><i class="fab fa-facebook fa-3x"></i></a>
                      </div>
                      <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-3 border-right">
                      <div class="description-block">
                        <h5 class="description-header nombre_contacto mt-2 mb-2">Tiktok</h5>
                        <a href="https://www.tiktok.com/@jdltechnology"  target="_blank" class="description-text"><i class="desarrollador-tiktok fa-brands fa-tiktok fa-3x"></i></a>
                      </div>
                      <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                      <div class="description-block">
                        <h5 class="description-header nombre_contacto mt-2 mb-2">Instagram</h5>
                        <a href="https://www.instagram.com/jdltechnology/" target="_blank" class="description-text desarrollador-instagram"><i class="fa-brands fa-instagram fa-3x"></i></a>
                      </div>
                      <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                  </div>
                  <!-- /.row -->
                </div>
              </div>
              <!-- /.widget-user -->
            </div>
            <!-- /.col -->            
          </div> 
          <!-- /.row -->
        </div>
      </div>

      <div class="modal-footer justify-content-end">
        <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- MDOAL - VER SUCURSALES -->
<div class="modal fade" id="modal-para-todos-los-modulos-sucursal">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Sucursales</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="text-danger" aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <table id="tabla-para-todos-los-modulos-sucursal" class="table table-bordered table-striped display" style="width: 100% !important;">
              <thead>
                <tr>
                  <th class="">#</th>
                  <th class="">Aciones</th>
                  <th>Nombre</th>
                  <th>Dirección</th>
                  <th>Estado</th>
                </tr>
              </thead>
              <tbody>                         
                
              </tbody>
              <tfoot>
                <tr>
                  <th>#</th>
                  <th>Aciones</th>
                  <th>Nombre</th>
                  <th>Dirección</th>
                  <th>Estado</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>

      <div class="modal-footer justify-content-between">
        <!-- <button type="button" class="btn bg-dark" onclick="salir_proyecto_para_todos_los_modulos();" >Salir de proyecto</button> -->
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>

  function abrir_sucursal_para_todos_los_modulos(idpersona_sucursal, id_usuario, nombre_sucursal, codigo_sucusal, direcion_sucursal) {

    Swal.fire({
      title: "¿Está Seguro de  Activar esta sucursal?",
      html: `<b class="text-success">${nombre_sucursal}</b> <br> Los datos que realice el usuario estara sujeto a esta sucursal.`,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#28a745",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, Guardar!",
      preConfirm: (login) => {
        return fetch(`../ajax/usuario.php?op=activar_sucursal&idpersona_sucursal=${idpersona_sucursal}&id_usuario=${id_usuario}`).then(response => {
          if (!response.ok) { throw new Error(response.statusText); }
          return response.json();
        }).catch(error => { Swal.showValidationMessage( `<b>Solicitud fallida:</b> ${error}`); });
      },
      showLoaderOnConfirm: true,
    }).then((result) => {
      console.log(result);
      if (result.isConfirmed) {
        if (result.value.status == true){   

          Swal.fire("Correcto!", "Compra guardada correctamente", "success");
          localStorage.setItem('nube_id_sucursal', idpersona_sucursal);
          localStorage.setItem('nube_nombre_sucursal', nombre_sucursal);
          localStorage.setItem('nube_codigo_sucusal', codigo_sucusal);
          localStorage.setItem('nube_direcion_sucursal', direcion_sucursal);        

          // mostramos el nombre en el NAV
          $("#ver-proyecto").html(`<i class="fas fa-store-alt"></i> ${nombre_sucursal}`);
          
          window.location.reload(); 
        } else {
          ver_errores(result.value);
        }      
      }
    });

    $(".tooltip").removeClass("show").addClass("hidde");
  }

  //Función Listar en curso o no empezados
  function tbla_principal_para_todos_los_modulos() {    

    var tabla_sucursal_todos = $('#tabla-para-todos-los-modulos-sucursal').dataTable({
      responsive: true,
      lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
      aProcessing: true,//Activamos el procesamiento del datatables
      aServerSide: true,//Paginación y filtrado realizados por el servidor
      dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>", //Definimos los elementos del control de tabla
      buttons: [
        { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i>', className: "btn bg-gradient-info", action: function ( e, dt, node, config ) { tabla_sucursal_todos.ajax.reload(); toastr_success('Exito!!', 'Actualizando tabla', 400); } },
        { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,4], }, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray", }, 
        { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,4], }, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success", }, 
        { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,2,3,4], }, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger", } ,
        // { extend: "colvis", text: `Columnas`, className: "btn bg-gradient-gray", exportOptions: { columns: "th:not(:last-child)", }, },
      ],
      ajax:{
        url: `../ajax/usuario.php?op=tbla_principal_sucursal_todos&id_persona=${localStorage.getItem('nube_id_usuario')}`,
        type : "get",
        dataType : "json",						
        error: function(e){        
          console.log(e.responseText); ver_errores(e);
        }
      },
      createdRow: function (row, data, ixdex) {
        // columna: 0
        if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
        // columna: 1
        if (data[1] != '') { $("td", row).eq(1).addClass("text-center"); }
      },    
      bDestroy: true,
      iDisplayLength: 10,//Paginación
      order: [[ 0, "asc" ]],//Ordenar (columna,orden)
      columnDefs: [ 
        //{ targets: [6], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
        //{ targets: [12], visible: false, searchable: false },
      ],
    }).DataTable();

  }

</script>
