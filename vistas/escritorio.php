<?php
  //Activamos el almacenamiento en el buffer
  ob_start();
  session_start();

  if (!isset($_SESSION["nombre"])){
    header("Location: index.php?file=".basename($_SERVER['PHP_SELF']));
  }else{ ?>
    <!DOCTYPE html>
    <html lang="es">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Escritorio | Admin Briment</title>

        <?php $title = "Escritorio"; require 'head.php'; ?>
     
      </head>
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed ">
        
        <div class="wrapper">
          <!-- Preloader -->
          <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../dist/svg/logo-briment.svg" alt="Briment Logo" width="360" />
          </div>
        
          <?php
            require 'nav.php';
            require 'aside.php';
            if ($_SESSION['escritorio']==1){
              //require 'enmantenimiento.php';
              ?>           
              <!--Contenido-->
              <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                  <div class="container-fluid">
                    <div class="row mb-2">
                      <div class="col-sm-6">
                        <h1 class="m-0">Tablero</h1>
                      </div>
                      <!-- /.col -->
                      <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                          <li class="breadcrumb-item"><a href="escritorio.php">Home</a></li>
                          <li class="breadcrumb-item active">Tablero</li>
                        </ol>
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->
                  </div>
                  <!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->

                <!-- Main content -->
                <section class="content">
                  <div class="container-fluid">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">

                      <!-- CANIDAD DE PROYECTOS -->
                      <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                          <div class="inner">
                            <h3 id="cantidad_box_producto" > <i class="fas fa-spinner fa-pulse "></i> </h3>
                            <p>Total de Productos</p>
                          </div>
                          <div class="icon">
                            <i class="fas fa-th"></i>
                          </div>
                          <a href="producto.php" data-toggle="tooltip" data-original-title="Click para visitar" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>

                      <!-- CANTIDAD DE PROVEEDORES -->
                      <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                          <div class="inner">
                            <h3 id="cantidad_box_agricultor"> <i class="fas fa-spinner fa-pulse "></i>   </h3>
                            <p>Total de Proveedores</p>
                          </div>
                          <div class="icon"><i class="fas fa-users"></i> </div>
                          <a href="persona.php" data-toggle="tooltip" data-original-title="Click para visitar" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>

                      <!-- CANTIDAD DE TRABAJADORES -->
                      <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                          <div class="inner">
                            <h3 id="cantidad_box_trabajador"> <i class="fas fa-spinner fa-pulse "></i> </h3>
                            <p>Total de Trabajadores</p>
                          </div>
                          <div class="icon"> <i class="fa-solid fa-briefcase"></i> </div>
                          <a href="persona.php" data-toggle="tooltip" data-original-title="Click para visitar" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>

                      <!-- CANTIDAD DE SERVICIOS -->
                      <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                          <div class="inner">
                            <h3 id="cantidad_box_venta"> <i class="fas fa-spinner fa-pulse "></i> </h3>
                            <p>Total de Ventas de Productos </p>
                          </div>
                          <div class="icon"> <i class="fas fa-shopping-cart"></i> </div>
                          <a href="venta_producto.php" class="small-box-footer" data-toggle="tooltip" data-original-title="Click para visitar">Más info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>

                    </div>
                    <!-- /.row -->
                  </div>
                  <!-- /.container-fluid -->
                </section>
                <!-- /.content -->

                <!-- Main content -->
                <section class="content">
                  <div class="container-fluid">
                    <div class="row">
                      <div class="col-12">
                        <div class="card card-primary card-outline">
                          <div class="card-header">
                            <h1 class="mb-0 text-success text-bold font-size-16px"><i class="fa-solid fa-chart-column"></i> REPORTES</h1>                      
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body">

                            <div class="row mb-3">
                              <div class="col-md-6 pr-3">
                                <div class="row">
                                  <div class="col-md-12">
                                    <div class="d-flex">
                                      <p class="d-flex flex-column">
                                        <span class="text-bold text-lg">Ventas y Pagos</span> <span>de productos por mes</span>
                                      </p>
                                      <p class="ml-auto d-flex flex-column text-right">
                                        <span class="text-success cursor-pointer" id="btn-download-chart-linea" data-toggle="tooltip" data-original-title="Descargar gráfico"><i class="fas fa-download fa-xl"></i></span>
                                        <span class="text-muted">Descarga</span>
                                      </p>
                                      
                                    </div>
                                    <!-- /.d-flex -->
                                    <hr>
                                    <div class="position-relative mb-4">
                                      <canvas id="visitors-chart" height="350"></canvas>
                                    </div>

                                    <div class="d-flex flex-row justify-content-end">
                                      <span class="mr-2" ><i class="fas fa-square" style="color: #28a745;" ></i> Total venta</span>
                                      <span class="mr-2"><i class="fas fa-square" style="color: #ced4da;"></i> Total pago</span>
                                    </div>
                                  </div>
                                  <!-- linea divisoria -->
                                  <div class="col-lg-12 borde-arriba-naranja mt-3 mb-3"> </div>

                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Venta Total</strong> </p>
                                    <div class="progress-group text-center mb-4">
                                      <h2 class="footer_total_venta" >S/. 0.00</h2>
                                    </div>
                                    <!-- /.progress-group -->                                    
                                  </div>                                  
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Utilidad Total</strong> </p>
                                    <div class="progress-group text-center">
                                      <h2 class="footer_total_utilidad" >S/. 0.00</h2>
                                    </div>
                                    <!-- /.progress-group -->
                                  </div>
                                </div>    
                                <!-- /.row -->                      
                              </div>     
                              <!-- /.col -->

                              <div class="col-md-6 pl-3">
                                <div class="row">
                                  <div class="col-md-12 ">
                                    <div class="d-flex">
                                      <p class="d-flex flex-column">
                                        <span class="text-bold text-lg">Compras de productos</span> <span>por mes</span>
                                      </p>
                                      <p class="ml-auto d-flex flex-column text-right">
                                        <span class="text-success cursor-pointer" id="btn-download-chart-barra" data-toggle="tooltip" data-original-title="Descargar gráfico"><i class="fas fa-download fa-xl"></i></span>
                                        <span class="text-muted">Descarga</span>
                                      </p>
                                    </div>
                                    <!-- /.d-flex -->
                                    <hr>
                                    <div class="position-relative mb-4">
                                      <canvas id="sales-chart" height="350"></canvas>
                                    </div>

                                    <div class="d-flex flex-row justify-content-end">
                                      <span class="mr-2"><i class="fas fa-square" style="color: #28a745 !important;"></i> Total compra</span>
                                      <span class="mr-2"><i class="fas fa-square" style="color: #000 !important;"></i> Total pago</span>
                                      <span class="mr-2"><i class="fas fa-square" style="color: #dc3545 !important;"></i> Kg. pergamino</span>
                                      <span class="mr-2"><i class="fas fa-square" style="color: #ffc107 !important;"></i> Kg. coco</span>
                                    </div>
                                  </div>
                                  <!-- linea divisoria -->
                                  <div class="col-lg-12 borde-arriba-naranja mt-3 mb-3"> </div>

                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Compra Total</strong> </p>
                                    <div class="progress-group text-center mb-4">
                                      <h2 class="footer_total_compra" >S/. 0.00</h2>
                                    </div>
                                    <!-- /.progress-group -->                                    
                                  </div>                                  
                                  <div class="col-md-6">
                                    <p class="text-center"> <strong>Deuda Total</strong> </p>
                                    <div class="progress-group text-center">
                                      <h2 class="footer_total_deuda" >S/. 0.00</h2>
                                    </div>
                                    <!-- /.progress-group -->
                                  </div>
                                </div>    
                                <!-- /.row -->                      
                              </div>     
                              <!-- /.col -->
                            </div>
                            <!-- /.row --> 
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

                  <!-- Modal agregar proyecto -->
                 
                </section>
                <!-- /.content -->
              </div>
              <!--Fin-Contenido-->
              <?php
            }else{
              require 'noacceso.php';
            }
            require 'footer.php';
          ?>

        </div>

        <?php require 'script.php'; ?>

        <!-- OPTIONAL SCRIPTS -->
        <script src="../plugins/chart.js/Chart.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="../dist/js/demo.js"></script>

        <script type="text/javascript" src="scripts/escritorio.js"></script>

        <script>  $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>
    <?php    
  }
  ob_end_flush();
?>
