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
        <title>Manual de Usuario | Admin JDL</title>
        <?php $title = "Manual de usuario"; require 'head.php';  ?>       

        <link rel="stylesheet" href="../dist/css/switch_materiales.css">

      </head>
      <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['recurso']==1){
            //require 'enmantenimiento.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1><i class="nav-icon fas fa-book"></i> Manual de Usuario</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Manual</li>
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

                    <!-- ::::::::::::::::::::::::::::: R E C U R S O  ::::::::::::::::::::::::::::: -->
                    <div class="col-12">
                      <div class="card card-warning card-outline">
                        <div class="card-header">
                          <h2 class="card-title text-warning text-bold font-size-20px"><i class="nav-icon far fa-circle"></i> Recursos </h2>
                          <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                              <i class="fas fa-minus"></i>
                            </button>
                          </div>
                          <!-- /.card-tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                          <div class="row">
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="../dist/docs/manual_de_usuario/pdf/recursos/proyecto.pdf" class="info-box-icon bg-warning"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-th"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Escritorio</span>
                                  <span class="info-box-text">Manual de usuario</span> 
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="../dist/docs/manual_de_usuario/pdf/recursos/usuarios.pdf" class="info-box-icon bg-warning"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-users-cog"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Usuarios</span>
                                  <span class="info-box-text">Manual de usuario</span> 
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="../dist/docs/manual_de_usuario/pdf/recursos/acceso.pdf" class="info-box-icon bg-warning"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-lock"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Permisos</span>
                                  <span class="info-box-text">Manual de usuario</span> 
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="../dist/docs/manual_de_usuario/pdf/recursos/trabajador.pdf" class="info-box-icon bg-warning"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-users"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">All-Trabajador</span>
                                  <span class="info-box-text">Manual de usuario</span> 
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="../dist/docs/manual_de_usuario/pdf/recursos/proveedor.pdf" class="info-box-icon bg-warning"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-truck"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">All-Proveedor</span>
                                  <span class="info-box-text">Manual de usuario</span> 
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="../dist/docs/manual_de_usuario/pdf/recursos/maquinaria_equipos.pdf" class="info-box-icon bg-warning"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-tractor"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Maquinas y Equipos</span>
                                  <span class="info-box-text">Manual de usuario</span> 
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="../dist/docs/manual_de_usuario/pdf/recursos/insumos.pdf" class="info-box-icon bg-warning"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><img src="../dist/svg/negro-palana-ico.svg" class="nav-icon" alt="" style="width: 31px !important;" ></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Insumos</span>
                                  <span class="info-box-text">Manual de usuario</span> 
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="../dist/docs/manual_de_usuario/pdf/recursos/activos_fijos.pdf" class="info-box-icon bg-warning"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-truck-pickup"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Activos Fijos</span>
                                  <span class="info-box-text">Manual de usuario</span> 
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="../dist/docs/manual_de_usuario/pdf/recursos/all_calendario.pdf" class="info-box-icon bg-warning"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="far fa-calendar-alt"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">All-Calendario</span>
                                  <span class="info-box-text">Manual de usuario</span> 
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="../dist/docs/manual_de_usuario/pdf/recursos/otros.pdf" class="info-box-icon bg-warning"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-coins"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Otros</span>
                                  <span class="info-box-text">Manual de usuario</span> 
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="../dist/docs/manual_de_usuario/pdf/recursos/compras_activos_fijos.pdf" class="info-box-icon bg-warning"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-hand-holding-usd"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Compras de Activos Fijos</span>
                                  <span class="info-box-text">Manual de usuario</span> 
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="../dist/docs/manual_de_usuario/pdf/recursos/resumen_activos_fijos.pdf" class="info-box-icon bg-warning"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-tasks"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Resumen de activos Fijos</span>
                                  <span class="info-box-text">Manual de usuario</span> 
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="../dist/docs/manual_de_usuario/pdf/recursos/otras_facturas.pdf" class="info-box-icon bg-warning"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-receipt"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Otras Facturas</span>
                                  <span class="info-box-text">Manual de usuario</span> 
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="../dist/docs/manual_de_usuario/pdf/recursos/resumen_facturas.pdf" class="info-box-icon bg-warning"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-poll"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Resumen de Facturas</span>
                                  <span class="info-box-text">Manual de usuario</span> 
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="#" class="info-box-icon bg-warning"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-poll"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Resumen de RH</span>
                                  <span class="info-box-text">Manual de usuario</span>                                  
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 mx-auto">
                              <div class="info-box shadow">
                                <a href="../dist/docs/manual_de_usuario/pdf/recursos/papelera.pdf" class="info-box-icon bg-warning"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-trash-alt"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Papelera</span>
                                  <span class="info-box-text">Manual de usuario</span> 
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 mx-auto">
                              <div class="info-box shadow">
                                <a href="../dist/docs/manual_de_usuario/pdf/recursos/especificaciones_generales.pdf" class="info-box-icon bg-warning"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-cogs"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Especificaciones generales</span>
                                  <span class="info-box-text">Manual de usuario</span> 
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <!-- <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 mx-auto">
                              <div class="info-box shadow">
                                <a href="../dist/docs/manual_de_usuario/pdf/recursos/papelera.pdf" class="info-box-icon bg-warning"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-book-open"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Plantilla manual usuario</span>
                                  <span class="info-box-text">Manual de usuario</span> 
                                </div> -->
                                <!-- /.info-box-content -->
                              <!-- </div> -->
                              <!-- /.info-box -->
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

                    <!-- ::::::::::::::::::::::::::::: T E C N I C O  ::::::::::::::::::::::::::::: -->
                    <div class="col-12">
                      <div class="card card-danger card-outline">
                        <div class="card-header">
                          <h2 class="card-title text-danger text-bold font-size-20px"><i class="nav-icon far fa-circle"></i> Técnico</h2>
                          <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                              <i class="fas fa-minus"></i>
                            </button>
                          </div>
                          <!-- /.card-tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                          <div class="row ">
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="../dist/docs/manual_de_usuario/pdf/tecnico/valorizaciones.pdf" class="info-box-icon bg-danger"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="far fa-file-alt"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Valorizaciones</span>
                                  <span class="info-box-text">Manual de usuario</span> 
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="../dist/docs/manual_de_usuario/pdf/tecnico/graficos.pdf" class="info-box-icon bg-danger"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-chart-line"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Grafico Valorizaciones</span>
                                  <span class="info-box-text">Manual de usuario</span> 
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="../dist/docs/manual_de_usuario/pdf/tecnico/" class="info-box-icon bg-danger"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-clipboard-list"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Asistencia del Obrero</span>
                                  <span class="info-box-text">Manual de usuario</span> 
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="../dist/docs/manual_de_usuario/pdf/tecnico/calendario.pdf" class="info-box-icon bg-danger"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="far fa-calendar-alt"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Calendario</span>
                                  <span class="info-box-text">Manual de usuario</span> 
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 mx-auto">
                              <div class="info-box shadow">
                                <a href="../dist/docs/manual_de_usuario/pdf/tecnico/planos_otros.pdf" class="info-box-icon bg-danger"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-map-marked-alt"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Planos y otros</span>
                                  <span class="info-box-text">Manual de usuario</span> 
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                          </div>
                        </div>
                        <!-- /.card-body -->
                      </div>
                      <!-- /.card -->
                    </div>
                    <!-- /.col -->

                    <!-- ::::::::::::::::::::::::::::: L O G I S T I C A   Y   A D Q U I C I O N E S  ::::::::::::::::::::::::::::: -->                    
                    <div class="col-12">
                      <div class="card card-success card-outline">
                        <div class="card-header">
                          <h2 class="card-title text-success text-bold font-size-20px"><i class="nav-icon far fa-circle"></i> Logistica y Adquisiciones </h2>
                          <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                              <i class="fas fa-minus"></i>
                            </button>
                          </div>
                          <!-- /.card-tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                          <div class="row">
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="#" class="info-box-icon bg-success"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><img src="../dist/svg/blanco-constructor-ico.svg" class="nav-icon" alt="" style="width: 31px !important;" ></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Trabajador</span>
                                  <span class="info-box-text">Manual de usuario</span>                                  
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="#" class="info-box-icon bg-success"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-cart-plus"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Compras</span>
                                  <span class="info-box-text">Manual de usuario</span>                                  
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="#" class="info-box-icon bg-success"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-tasks"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Resumen de insumos</span>
                                  <span class="info-box-text">Manual de usuario</span>                                  
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="#" class="info-box-icon bg-success"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-tasks"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Resumen de Activos Fijos</span>
                                  <span class="info-box-text">Manual de usuario</span>                                  
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="#" class="info-box-icon bg-success"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><img src="../dist/svg/blanco-excabadora-ico.svg" class="nav-icon" alt="" style="width: 31px !important;" ></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Servicio - Maquina</span>
                                  <span class="info-box-text">Manual de usuario</span>                                  
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="#" class="info-box-icon bg-success"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><img src="../dist/svg/blanco-estacion-total-ico.svg" class="nav-icon" alt="" style="width: 31px !important;" ></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Servicio - Equipos</span>
                                  <span class="info-box-text">Manual de usuario</span>                                  
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="#" class="info-box-icon bg-success"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-hands-helping"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Sub Contrato</span>
                                  <span class="info-box-text">Manual de usuario</span>                                  
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="#" class="info-box-icon bg-success"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><img src="../dist/svg/blanco-planilla-seguro-ico.svg" class="nav-icon" alt="" style="width: 31px !important;" ></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Planillas y seguros</span>
                                  <span class="info-box-text">Manual de usuario</span>                                  
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="#" class="info-box-icon bg-success"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-network-wired"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Otros Gastos</span>
                                  <span class="info-box-text">Manual de usuario</span>                                  
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="#" class="info-box-icon bg-success"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-list-ul"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Resumen general</span>
                                  <span class="info-box-text">Manual de usuario</span>                                  
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="#" class="info-box-icon bg-success"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-shuttle-van"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Transporte</span>
                                  <span class="info-box-text">Manual de usuario</span>                                  
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                              <div class="info-box shadow">
                                <a href="#" class="info-box-icon bg-success"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-hotel"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Hospedaje</span>
                                  <span class="info-box-text">Manual de usuario</span>                                  
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 mx-auto">
                              <div class="info-box shadow">
                                <a href="#" class="info-box-icon bg-success"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-utensils"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Pensión</span>
                                  <span class="info-box-text">Manual de usuario</span>                                  
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 mx-auto">
                              <div class="info-box shadow">
                                <a href="#" class="info-box-icon bg-success"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-hamburger"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Break</span>
                                  <span class="info-box-text">Manual de usuario</span>                                  
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 mx-auto">
                              <div class="info-box shadow">
                                <a href="#" class="info-box-icon bg-success"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-drumstick-bite"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Comidas - extras</span>
                                  <span class="info-box-text">Manual de usuario</span>                                  
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                          </div>                          
                        </div>
                        <!-- /.card-body -->
                      </div>
                      <!-- /.card -->
                    </div>
                    <!-- /.col -->

                    <!-- ::::::::::::::::::::::::::::: C O N T A B L E   Y   F I N A N C I E R O  ::::::::::::::::::::::::::::: -->
                    <div class="col-12">
                      <div class="card card-info card-outline">
                        <div class="card-header">
                          <h2 class="card-title text-info text-bold font-size-20px"><i class="nav-icon far fa-circle"></i> Contable y financiero </h2>
                          <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                              <i class="fas fa-minus"></i>
                            </button>
                          </div>
                          <!-- /.card-tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">                          
                          <div class="row">

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 mx-auto">
                              <div class="info-box shadow">
                                <a href="#" class="info-box-icon bg-info"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-comments-dollar"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Resumen de Gastos</span>
                                  <span class="info-box-text">Manual de usuario</span>                                  
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 mx-auto">
                              <div class="info-box shadow">
                                <a href="#" class="info-box-icon bg-info"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-users"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Pago Obreros</span>
                                  <span class="info-box-text">Manual de usuario</span>                                  
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 mx-auto">
                              <div class="info-box shadow">
                                <a href="#" class="info-box-icon bg-info"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-briefcase"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Pago Administrador</span>
                                  <span class="info-box-text">Manual de usuario</span>                                  
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 mx-auto">
                              <div class="info-box shadow">
                                <a href="#" class="info-box-icon bg-info"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-university"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Prestamos</span>
                                  <span class="info-box-text">Manual de usuario</span>                                  
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 mx-auto">
                              <div class="info-box shadow">
                                <a href="#" class="info-box-icon bg-info"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-balance-scale-left"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Estado Financiero</span>
                                  <span class="info-box-text">Manual de usuario</span>                                  
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 mx-auto">
                              <div class="info-box shadow">
                                <a href="#" class="info-box-icon bg-info"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-hand-holding-usd"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Otro ingreso</span>
                                  <span class="info-box-text">Manual de usuario</span>                                  
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 mx-auto">
                              <div class="info-box shadow">
                                <a href="#" class="info-box-icon bg-info"  target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-original-title="Ver manual"><i class="fas fa-dollar-sign"></i></a>

                                <div class="info-box-content">
                                  <span class="info-box-number">Pago Valorización</span>
                                  <span class="info-box-text">Manual de usuario</span>                                  
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

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

                <!-- MODAL - AGREGAR MATERIAL -->
                <div class="modal fade" id="modal-agregar-material">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Agregar Insumos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-materiales" name="form-materiales" method="POST">
                          <div class="card-body">
                            <div class="row" id="cargando-1-fomulario">
                              <!-- id proyecto -->
                              <input type="hidden" name="idproyecto" id="idproyecto" />
                              <!-- id proveedores -->
                              <input type="hidden" name="idproducto" id="idproducto" />
                              <!-- id categoria_insumos_af -->
                              <input type="hidden" name="idcategoria_insumos_af" id="idcategoria_insumos_af" value="1"/>

                              <!-- Nombre -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-12">
                                <div class="form-group">
                                  <label for="nombre_material">Nombre <sup class="text-danger">(unico*)</sup></label>
                                  <input type="text" name="nombre_material" class="form-control" id="nombre_material" placeholder="Nombre del Insumo." />
                                </div>
                              </div>

                              <!-- Modelo -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="modelo">Modelo</label>
                                  <input class="form-control" type="text" id="modelo" name="modelo" placeholder="Modelo." />
                                </div>
                              </div>

                              <!-- Serie -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="serie">Serie </label>
                                  <input class="form-control" type="text" id="serie" name="serie" placeholder="Serie." />
                                </div>
                              </div>

                              <!--Marca-->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="marca">Marca</label>
                                  <input type="text" name="marca" class="form-control" id="marca" placeholder="Marca" />
                                </div>
                              </div>

                              <!-- Color -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="color">Color <sup class="text-danger">(unico*)</sup></label>
                                  <select name="color" id="color" class="form-control select2" style="width: 100%;"> </select>
                                  <!--<input type="hidden" name="color_old" id="color_old" />-->
                                </div>
                              </div>

                              <!-- Unnidad de medida  -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="Unidad-medida">Unidad-medida <sup class="text-danger">(unico*)</sup></label>
                                  <select name="unidad_medida" id="unidad_medida" class="form-control select2" style="width: 100%;"> </select>
                                </div>
                              </div>

                              <!--Precio U-->
                              <div class="col-7 col-sm-7 col-md-8 col-lg-4">
                                <div class="form-group">
                                  <label for="precio_unitario">Precio <sup class="text-danger">*</sup></label>
                                  <input type="number" name="precio_unitario" class="form-control" id="precio_unitario" placeholder="Precio Unitario." onchange="precio_con_igv();" onkeyup="precio_con_igv();" />
                                </div>
                              </div>

                              <!-- Rounded switch -->
                              <div class="col-5 col-sm-5 col-md-4 col-lg-2">
                                <div class="form-group">
                                  <label for="" class="labelswitch">Sin o Con (Igv)</label>
                                  <div id="switch_igv">
                                    <div class="myestilo-switch">
                                      <div class="switch-toggle">
                                        <input type="checkbox" id="my-switch_igv" checked />
                                        <label for="my-switch_igv"></label>
                                      </div>
                                    </div>
                                  </div>
                                  <input type="hidden" name="estado_igv" id="estado_igv" />
                                </div>
                              </div>

                              <!--Sub Total-->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="precio_real">Sub Total <sup class="text-danger">*</sup></label>
                                  <input type="number" class="form-control precio_real" placeholder="Precio real." onchange="precio_con_igv();" onkeyup="precio_con_igv();" readonly />
                                  <input type="hidden" name="precio_real" class="form-control" id="precio_real" placeholder="Precio real." />
                                </div>
                              </div>

                              <!--IGV-->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="monto_igv">IGV <sup class="text-danger">*</sup></label>
                                  <input type="number" class="form-control monto_igv" placeholder="Monto igv." onchange="precio_con_igv();" onkeyup="precio_con_igv();" readonly />
                                  <input type="hidden" name="monto_igv" class="form-control" id="monto_igv" />
                                </div>
                              </div>

                              <!--Total-->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="total_precio">Total <sup class="text-danger">*</sup></label>
                                  <input type="number" class="form-control total_precio" placeholder="Precio real." readonly />
                                  <input type="hidden" name="total_precio" id="total_precio" class="form-control total" placeholder="Precio real." readonly />
                                </div>
                              </div>

                              <!--descripcion_material-->
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                  <label for="descripcion_material">Descripción </label> <br />
                                  <textarea name="descripcion_material" id="descripcion_material" class="form-control" rows="2"></textarea>
                                </div>
                              </div>
                              
                              <!--img-material-->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                <label for="imagen1">Imagen</label>
                                <div style="text-align: center;">
                                  <img
                                    onerror="this.src='../dist/img/default/img_defecto_materiales.png';"
                                    src="../dist/img/default/img_defecto_materiales.png"
                                    class="img-thumbnail"
                                    id="imagen1_i"
                                    style="cursor: pointer !important; height: 100% !important;"
                                    width="auto"
                                  />
                                  <input style="display: none;" type="file" name="imagen1" id="imagen1" accept="image/*" />
                                  <input type="hidden" name="imagen1_actual" id="imagen1_actual" />
                                  <div class="text-center" id="imagen1_nombre"><!-- aqui va el nombre de la FOTO --></div>
                                </div>
                              </div>

                              <!-- Ficha tecnica -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                <label for="doc2_i" >Ficha técnica <b class="text-danger">(Imagen o PDF)</b> </label>  
                                <div class="row text-center">                               
                                  <!-- Subir documento -->
                                  <div class="col-6 col-md-6 col-lg-6 col-xl-6 text-center">
                                    <button type="button" class="btn btn-success btn-block btn-xs" id="doc2_i">
                                      <i class="fas fa-upload"></i> Subir.
                                    </button>
                                    <input type="hidden" id="doc_old_2" name="doc_old_2" />
                                    <input style="display: none;" id="doc2" type="file" name="doc2" accept="application/pdf, image/*" class="docpdf" /> 
                                  </div>
                                  <!-- Recargar -->
                                  <div class="col-6 col-md-6 col-lg-6 col-xl-6 text-center comprobante">
                                    <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(2, 'material', 'ficha_tecnica');">
                                    <i class="fas fa-redo"></i> Recargar.
                                  </button>
                                  </div>                                  
                                </div>

                                <div id="doc2_ver" class="text-center mt-4">
                                  <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >
                                </div>
                                <div class="text-center" id="doc2_nombre"><!-- aqui va el nombre del pdf --></div>
                              </div>

                              <!-- barprogress -->
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                                <div class="progress" id="div_barra_progress">
                                  <div id="barra_progress" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                    0%
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="row" id="cargando-2-fomulario" style="display: none;">
                              <div class="col-lg-12 text-center">
                                <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                <br />
                                <h4>Cargando...</h4>
                              </div>
                            </div>
                          </div>
                          <!-- /.card-body -->
                          <button type="submit" style="display: none;" id="submit-form-materiales">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_form_material();">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
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

        <?php  require 'script.php'; ?>        

        <script type="text/javascript" src="scripts/manual_de_usuario.js"></script>

        <script> $(function () {  $('[data-toggle="tooltip"]').tooltip(); }); </script>

      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
