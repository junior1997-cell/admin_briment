<?php
  //Activamos el almacenamiento en el buffer
  ob_start();

  session_start();
  if (!isset($_SESSION["nombre"])){
    header("Location: index.php?file=".basename($_SERVER['PHP_SELF']));
  }else{
    ?>
    <!DOCTYPE html>
    <html lang="es">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Pago Trabajador | Admin Briment</title>

        <?php $title = "Trabajadores"; require 'head.php'; ?>
        <link rel="stylesheet" href="../dist/css/switch_domingo.css">

      </head>
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['pago_trabajador']==1){
            //require 'enmantenimiento.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1> <i class="fas fa-dollar-sign nav-icon"></i> Pago Trabajador <b class="texto-parpadeante nombre_trabajador_view" ></b> </h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="trabajador.php">Home</a></li>
                        <li class="breadcrumb-item active">Pago</li>
                      </ol>
                    </div>
                  </div>
                </div>
                <!-- /.container-fluid -->
              </section>

              <!-- Main content -->
              <section class="content">
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-12">
                      <div class="card card-primary card-outline">
                        <div class="card-header">
                          <h3 class="card-title">
                            
                            <button type="button" class="btn bg-gradient-warning" id="btn-regresar" style="display: none;" onclick="show_hide_table(1);"><i class="fas fa-arrow-left"></i> Regresar</button>
                            <button type="button" class="btn bg-gradient-primary" id="btn-agregar-mes"data-toggle="modal" style="display: none;" data-target="#modal-agregar-mes" ><i class="fa-solid fa-circle-plus"></i> Agregar Mes</button>
                          </h3>
                          <!--Regresar a la tabla principal  -->
                          <h3 class="card-title mr-3"  style="padding-left: 2px;"  data-toggle="tooltip" data-original-title="Regresar a la tabla principal">
                            <button type="button" id="btn-regresar-todo" class="btn btn-block btn-outline-warning btn-sm" style="display: none;" onclick="show_hide_table(1);"><i class="fas fa-arrow-left"></i></button>
                          </h3>
                          <!--Regresar a la tabla mes-->
                          <h3 class="card-title mr-3"  style="padding-left: 2px;"  data-toggle="tooltip" data-original-title="Regresar a la tabla mes">
                            <button type="button" class="btn bg-gradient-warning" id="btn-regresar-meses" style="display: none;" onclick="show_hide_table(2);"><i class="fas fa-arrow-left"></i> Regresar a meses</button>
                          </h3>
                          <!--Agregar pago -->
                          <h3 class="card-title mr-3"  style="padding-left: 2px;"  data-toggle="tooltip" data-original-title="Agregar pago">
                            <button type="button" class="btn bg-gradient-primary" id="btn-agregar-pago" data-toggle="modal" style="display: none;" onclick="limpiar_form_pago();" data-target="#modal-agregar-pago-trabajdor" ><i class="fa-solid fa-circle-plus"></i> Agregar Pago</button>
                          </h3>
                          <div class="sueldo_trab_view" style="text-align: right; display:none; "> Total a pagar: <b class="texto-parpadeante val_sueldo"> S/ 100</b> </div>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">

                          <div id="div-tabla-trabajador">
                            <!-- Lista de trabajdores activos -->                      
                            <table id="tabla-trabajador" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th>Nombres</th>
                                  <th>Cargo</th>
                                  <th>Sueldo</th>
                                  <th>Teléfono</th>
                                  <th>Pagar</th>
                                  <th>Pago total</th>

                                  <th>Estado</th>
                                  <th>Nombres</th>
                                  <th>Tipo Doc.</th>
                                  <th>Num Doc.</th>
                                  <th>Nacimiento</th>
                                  <th>Edad</th>                                  
                                  <th>Sueldo mensual</th>
                                  <th>Sueldo Diario</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th>Nombres</th>
                                  <th>Cargo</th>
                                  <th>Sueldo</th>
                                  <th>Teléfono</th>
                                  <th>Pagar</th>
                                  <th>Pago total</th>

                                  <th>Estado</th>
                                  <th>Nombres</th>
                                  <th>Tipo Doc.</th>
                                  <th>Num Doc.</th>
                                  <th>Nacimiento</th>
                                  <th>Edad</th>                                  
                                  <th>Sueldo mensual</th>
                                  <th>Sueldo Diario</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                          
                          <div id="div-tabla-mes-pago" style="display: none !important;">
                            <!-- Lista de trabajdores activos -->                      
                            <table id="tabla-mes-pago" class="table table-bordered table-striped display" style="width: 100% !important; ">
                              <thead>
                                <tr>
                                  <th class="text-center">#</th>                                  
                                  <th>Año</th>
                                  <th>Mes</th>
                                  <th>Ver Detalle</th>
                                  <th >Pagos</th>                                  
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="text-center">#</th>                                  
                                  <th>Año</th>
                                  <th>Mes</th>
                                  <th>Ver Detalle</th>
                                  <th class="px-2">Pagos</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                                                    
                          <div id="div-tabla-pagos" style="display: none !important;">
                            <!-- Lista de trabajdores activos -->                      
                            <table id="tabla-ingreso-pagos" class="table table-bordered  table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr> 
                                  <th class="text-center">#</th> 
                                  <th>Op.</th> 
                                  <th>Fecha </th> 
                                  <th>Monto</th>
                                  <th>Descripcion</th>     
                                  <th>Comprobante</th>                                                  
                                </tr>
                              </thead>
                              <tbody>                         
                                
                              </tbody>
                              <tfoot>
                                <tr> 
                                  <th class="text-center">#</th> 
                                  <th>Op.</th> 
                                  <th>Fecha </th> 
                                  <th class="px-2">Monto</th>
                                  <th>Descripcion</th>     
                                  <th>Comprobante</th>                                                  
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                        </div>
                        <!-- /.card-body -->
                      </div>
                      <!-- /.card -->
                    </div>
                    <!-- /.col -->
                  </div>
                  <!-- /.row -->
                </div>
                <!-- /.container-fluid -->

                <!-- Modal agregar mes -->
                <div class="modal fade" id="modal-agregar-mes">
                  <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Agregando mes de pago</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-mes" name="form-mes" method="POST">
                          <div class="card-body" style="padding: none;">

                            <div class="row" id="cargando-1-fomulario">

                              <!-- id mes_pago_trabajador -->
                              <input type="hidden" name="idmes_pago_trabajador" id="idmes_pago_trabajador" />
                              <!-- id persona -->
                              <input type="hidden" name="idpersona" id="idpersona" />

                              <!-- Nombre -->
                              <div class="col-12 col-sm-12 col-md-12 "  style="display: none;">
                                <div class="form-group">
                                  <label for="nombre_trab">Nombre y Apellidos</label>
                                  <input type="text" name="nombre_trabajador" class="form-control" id="nombre_trabajador" />                                  
                                </div>
                              </div>

                              <!-- Sueldo(Mensual) -->
                              <div class="col-12 col-sm-6 col-md-6"  style="display: none;">
                                <div class="form-group">
                                  <label for="extraer_cargo">Cargo Trabajador</label>
                                  <input type="text" disabled step="any" name="extraer_cargo" class="form-control" id="extraer_cargo" />
                                </div>
                              </div>
                              
                              <!-- Sueldo(Mensual) -->
                              <div class="col-12 col-sm-6 col-md-6" style="display: none;">
                                <div class="form-group">
                                  <label for="sueldo_mensual">Sueldo Mensual</label>
                                  <input type="number" disabled step="any" name="sueldo_mensual" class="form-control" id="sueldo_mensual"/>
                                </div>
                              </div>
                              <!-- input que muestra el mes -->
                              <div class="col-6 col">
                                <div class="form-group">
                                  <label for="mes">Mes</label>
                                  <input type="text"  name="mes" class="form-control" id="mes" readonly/>
                                </div>
                              </div>
                              <!-- input que muestra el mes -->
                              <div class="col-6 col">
                                <div class="form-group">
                                  <label for="anio">Año</label>
                                  <input type="text"  name="anio" class="form-control" id="anio" readonly/>
                                </div>
                              </div>
                              
                              <!-- idpersona,mes,anio -->
                              <!-- Progress -->
                              <div class="col-md-12">
                                <div class="form-group">
                                  <div class="progress" id="div_barra_progress_trabajador" style="display: none !important;">
                                    <div id="barra_progress_trabajador" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row" id="cargando-2-fomulario" style="display: none;" >
                              <div class="col-lg-12 text-center">
                                <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                                <h4>Cargando...</h4>
                              </div>
                            </div>
                                  
                          </div>
                          <!-- /.card-body -->
                          <button type="submit" style="display: none;" id="submit-form-mes">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" onclick="limpiar_form_pago();" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_mes">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Modal agregar pagos -->
                <div class="modal fade bg-color-02020280" id="modal-agregar-pago-trabajdor">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg shadow-0px1rem3rem-rgb-0-0-0-50 rounded">
                    <div class="modal-content shadow-none border-0">
                      <div class="modal-header">
                        <h4 class="modal-title">
                          Agregar pago: <b class="nombre_de_trabajador_modal"> <!-- NOMBRE DEL TRABAJDOR--> </b>
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form class="mx-2" id="form-pagos-trabajdor" name="form-pagos-trabajdor" method="POST">
                          <div class="row" id="cargando-3-fomulario">
                            <!-- id pago_trabajador   -->
                            <input type="hidden" name="idpago_trabajador" id="idpago_trabajador" />

                            <!-- id mes_pago_trabajador  -->
                            <input type="hidden" name="idmes_pago_trabajador_p" id="idmes_pago_trabajador_p" />

                            <!-- Cuenta deposito enviada -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="nombre_mes">Nombre mes</label>
                                <input type="text" name="nombre_mes" id="nombre_mes" class="form-control" placeholder="Nombre mes" readonly />
                              </div>
                            </div>

                            <!-- Monto (de cantidad a depositado)-->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="monto">Monto <small> (Monto a pagar) </small> </label>
                                <input type="number" name="monto" id="monto" class="form-control" placeholder="Monto a pagar" />
                              </div>
                            </div>

                            <!-- Fecha de deposito -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="fecha_pago">Fecha de deposito </label>
                                <input class="form-control" type="date" id="fecha_pago" name="fecha_pago" />
                              </div>
                            </div>

                            <!-- Descripción -->
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="nombre_mes">Descripción</label>
                                <textarea class="form-control" name="descripcion" id="descripcion" cols="3" rows="2"></textarea>
                              </div>
                            </div>

                            <!-- Pdf 1 -->
                            <div class="col-md-6">
                              <div class="row text-center">
                                <div class="col-md-12 p-t-15px p-b-5px">
                                  <label for="doc1_i" class="control-label"> Baucher de deposito </label>
                                </div>
                                <div class="col-6 col-md-6 text-center">
                                  <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i"><i class="fas fa-upload"></i> Subir.</button>
                                  <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                  <input style="display: none;" id="doc1" type="file" name="doc1" accept="application/pdf, image/*" class="docpdf" />
                                </div>
                                <div class="col-6 col-md-6 text-center">
                                  <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(1,'pago_trabajador', 'comprobante');"><i class="fas fa-redo"></i> Recargar.</button>
                                </div>
                              </div>
                              <div id="doc1_ver" class="text-center mt-4">
                                <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" />
                              </div>
                              <div class="text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px;">
                              <div class="progress" id="barra_progress_pagos_x_mes_div">
                                <div id="barra_progress_pagos_x_mes" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                  0%
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="row" id="cargando-4-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                          <button type="submit" style="display: none;" id="submit-form-pagos">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_pagos">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- MODAL - VER PERFIL PERSONA-->
                <div class="modal fade bg-color-02020280" id="modal-ver-perfil-persona">
                  <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content bg-color-0202022e shadow-none border-0">
                      <div class="modal-header">
                        <h4 class="modal-title text-white foto-persona">Foto Persona</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-white cursor-pointer" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body"> 
                        <div id="perfil-persona" class="text-center">
                          <!-- vemos los datos del trabajador -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div> 

              </section>
              <!-- /.content -->
            </div>

            <?php
          }else{
            require 'noacceso.php';
          }
          require 'footer.php';
          ?>
        </div>
        <!-- /.content-wrapper -->
        
        <?php require 'script.php'; ?>       
        
        <!-- Funciones del modulo -->
        <script type="text/javascript" src="scripts/pago_trabajador.js"></script>

        <script> $(function () {  $('[data-toggle="tooltip"]').tooltip();  }); </script>
        
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
