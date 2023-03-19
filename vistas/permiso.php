<?php
  //Activamos el almacenamiento en el buffer
  ob_start();

  session_start();
  if (!isset($_SESSION["nombre"])){
    header("Location: index.php?file=".basename($_SERVER['PHP_SELF']));
  }else{
    ?>
    <!doctype html>
    <html lang="es">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Permisos | Admin Briment</title>
        <?php $title = "Permisos";  require 'head.php'; ?>
        
      </head>
      <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['acceso']==1){
            //require 'enmantenimiento.php';
            ?>    
          
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1>Permisos</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="permiso.php">Home</a></li>
                        <li class="breadcrumb-item active">Permisos</li>
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
                          <h3 class="card-title " >
                            <!-- <button type="button" class="btn bg-gradient-primary" data-toggle="modal" data-target="#modal-agregar-usuario" onclick="limpiar();">
                              <i class="fas fa-user-plus"></i> Agregar
                            </button> -->
                            Lista de permisos para el sistema                        
                          </h3>                      
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                          <table id="tabla-permiso" class="table table-bordered table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th class="text-center">#</th>
                                <th class="">Usuarios que acceden</th>
                                <th>Nombre de permiso</th>                               
                              </tr>
                            </thead>
                            <tbody>                         
                              
                            </tbody>
                            <tfoot>
                              <tr>
                                <th class="text-center">#</th>
                                <th class="">Usuarios que acceden</th>
                                <th>Nombre de permiso</th>                               
                              </tr>
                            </tfoot>
                          </table>
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

                <!-- Modal agregar usuario -->
                <div class="modal fade" id="modal-ver-usuarios">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">

                      <div class="modal-header">
                        <h4 class="modal-title">Usuarios que tienen acceso a este permiso</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      
                      <div class="modal-body">
                        <div class="card-body">
                          <div class="row" id="cargando-1-fomulario">
                            <!-- Trabajador -->
                            <div class="col-lg-12">
                              <table id="tabla-usuarios" class="table table-bordered table-striped display" style="width: 100% !important;">
                                <thead>
                                  <tr>
                                    <th class="text-center" >#</th>
                                    <th class="">Usuarios que acceden</th>
                                    <th>Cargo</th>  
                                    <th>Fecha de creacion</th>                               
                                  </tr>
                                </thead>
                                <tbody>                         
                                  
                                </tbody>
                                <tfoot>
                                  <tr>
                                    <th class="text-center" >#</th>
                                    <th class="">Usuarios que acceden</th>
                                    <th>Cargo</th> 
                                    <th>Fecha de creacion</th>                          
                                  </tr>
                                </tfoot>
                              </table>                                                        
                            </div>
                          </div>  

                          <div class="row" id="cargando-2-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                          
                        </div>
                        <!-- /.card-body -->
                      </div>

                      <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <!-- <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button> -->
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

        <script type="text/javascript" src="scripts/permiso.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); })</script>
      
      </body>
    </html> 
    
    <?php  
  }
  ob_end_flush();

?>
