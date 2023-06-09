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
        <title>Otros | Admin Briment</title>

        <?php $title = "Otros"; require 'head.php'; ?>

        <!--CSS  switch_MATERIALES-->
        <link rel="stylesheet" href="../dist/css/switch_materiales.css" />
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

              <!-- Main content -->
              <section class="content">
                <div class="container-fluid">
                  <div class="row">                    
                    
                    <div class="col-12 col-sm-12 mt-4">
                      <div class="card card-primary card-outline card-tabs">
                        <div class="card-header p-0 pt-1 border-bottom-0">
                          <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                            <li class="nav-item">
                              <a class="nav-link active" id="custom-tabs-three-producto-tab" data-toggle="pill" href="#custom-tabs-three-producto" role="tab" aria-controls="custom-tabs-three-producto" aria-selected="true"><i class="fas fa-syringe"></i> Producto</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="custom-tabs-three-persona-tab" data-toggle="pill" href="#custom-tabs-three-persona" role="tab" aria-controls="custom-tabs-three-persona" aria-selected="false"><i class="fas fa-user"></i> Persona</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="custom-tabs-three-compra-tab" data-toggle="pill" href="#custom-tabs-three-compra" role="tab" aria-controls="custom-tabs-three-compra" aria-selected="false"><i class="fas fa-shopping-cart"></i> Compra</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="custom-tabs-three-venta-tab" data-toggle="pill" href="#custom-tabs-three-venta" role="tab" aria-controls="custom-tabs-three-venta" aria-selected="false"><i class="fas fa-shopping-cart"></i> Venta</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="custom-tabs-three-sucursal-tab" data-toggle="pill" href="#custom-tabs-three-sucursal" role="tab" aria-controls="custom-tabs-three-sucursal" aria-selected="false"><i class="fas fa-store-alt"></i> Sucursal</a>
                            </li>
                          </ul>
                        </div>
                        
                        <!-- /.card -->
                      </div>
                    </div>

                    <div class="col-12">
                      <div class="tab-content" id="custom-tabs-three-tabContent">
                        <div class="tab-pane fade show active" id="custom-tabs-three-producto" role="tabpanel" aria-labelledby="custom-tabs-three-producto-tab">
                          <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">                           
                              <!-- TBLA - UNIDAD DE MEDIDA-->
                              <div class="row">
                                <div class="col-sm-6">
                                  <h2>Unidades de Medida</h2>
                                </div>
                                <div class="col-sm-6">
                                  <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Unidad de Medida</li>
                                  </ol>
                                </div>
                                <div class="col-12">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-primary" data-toggle="modal" data-target="#modal-agregar-unidad-m" onclick="limpiar_unidades_m();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Admnistrar Unidad de medidas.
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-unidades-m" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Abreviación</th>
                                            <th>Descripciòn</th>
                                            <th>Estado</th>
                                            
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Abreviación</th>
                                            <th>Descripciòn</th>
                                            <th>Estado</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6"> 
                              <!-- TBLA - LABORATORIO -->
                              <div class="row">
                                <div class="col-sm-6">
                                  <h2>Laboratorio (Marcas)</h2>
                                </div>
                                <!-- /.col-6 -->
                                <div class="col-sm-6">
                                  <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Laboratorio</li>
                                  </ol>
                                </div>
                                <!-- /.col-6 -->
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-primary" data-toggle="modal" data-target="#modal-agregar-laboratorio" onclick="limpiar_form_laboratorio();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Lista de Laboratorio.
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-laboratorio-af" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Estado</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Estado</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                              </div> 
                            </div>

                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                              <!-- TBLA - PRESENTACION-->
                              <div class="row">
                                <div class="col-sm-6">
                                  <h2>Presentación</h2>
                                </div>
                                <div class="col-sm-6">
                                  <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Presentación</li>
                                  </ol>
                                </div>
                                <div class="col-12">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-primary" data-toggle="modal" data-target="#modal-agregar-presentacion" onclick="limpiar_presentacion();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Admnistrar Presentaciones.
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-presentacion" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Descripciòn</th>
                                            <th>Estado</th>
                                            
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Descripciòn</th>
                                            <th>Estado</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- /.row -->
                        </div>
                        <!-- /.tab-1 -->

                        <div class="tab-pane fade" id="custom-tabs-three-persona" role="tabpanel" aria-labelledby="custom-tabs-three-persona-tab">
                          <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                              <!-- TBLA - TIPO TRABAJADOR-->
                              <div class="row">
                                <div class="col-sm-6">
                                  <h2>Tipo Persona</h2>
                                </div>
                                <div class="col-sm-6">
                                  <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Tipo Persona</li>
                                  </ol>
                                </div>
                                <div class="col-12">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-primary" data-toggle="modal" data-target="#modal-agregar-tipo" onclick="limpiar_tipo();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Admnistrar Tipo* .
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-tipo" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Descripcion</th>
                                            <th>Estado</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Descripcion</th>
                                            <th>Estado</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                              <!-- TBLA - CARGO PERSONA-->
                              <div class="row">
                                
                                <div class="col-sm-6">
                                  <h2>Cargos</h2>
                                </div>
                                <!-- /.col-6 -->

                                <div class="col-sm-6">
                                  <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Cargos</li>
                                  </ol>
                                </div>
                                <!-- /.col-6 -->

                                <div class="col-12">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-primary" data-toggle="modal" data-target="#modal-agregar-cargo" onclick="limpiar_cargo();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Admnistrar Cargos.
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-cargo" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>                                
                                            <th>Nombre</th>
                                            <th>Estado</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>                                
                                            <th>Nombre</th>
                                            <th>Estado</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>    
                                <!-- /.col-12 -->
                              </div>
                            </div>
                          </div>
                              
                        </div>
                        <!-- /.tab-2 -->

                        <div class="tab-pane fade" id="custom-tabs-three-compra" role="tabpanel" aria-labelledby="custom-tabs-three-compra-tab">
                          <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                              <!-- TBLA - LOTE-->
                              <div class="row">
                                <div class="col-sm-6">
                                  <h2>Lote</h2>
                                </div>
                                <div class="col-sm-6">
                                  <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Lote</li>
                                  </ol>
                                </div>
                                <div class="col-12">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-primary" data-toggle="modal" data-target="#modal-agregar-lote" onclick="limpiar_lote();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Admnistrar Lote.
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-lote" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Stock</th>
                                            <th>Expira</th>
                                            <th>Descripciòn</th>                                                                                        
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Stock</th>
                                            <th>Expira</th>
                                            <th>Descripciòn</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            </div>
                          </div>
                        </div>
                        <!-- /.tab-3 -->

                        <div class="tab-pane fade" id="custom-tabs-three-venta" role="tabpanel" aria-labelledby="custom-tabs-three-venta-tab">
                          -- vacio --
                        </div>

                        <div class="tab-pane fade" id="custom-tabs-three-sucursal" role="tabpanel" aria-labelledby="custom-tabs-three-sucursal-tab">
                          <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                              <!-- TBLA - SUCURSAL-->
                              <div class="row">
                                <div class="col-sm-6">
                                  <h2>Sucursal</h2>
                                </div>
                                <div class="col-sm-6">
                                  <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Sucursal</li>
                                  </ol>
                                </div>
                                <div class="col-12">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-primary" data-toggle="modal" data-target="#modal-agregar-sucursal" onclick="limpiar_sucursal();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Admnistrar Sucursal.
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-sucursal" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Codigo</th>
                                            <th>Direccion</th>
                                            <th>Estado</th>                                            
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Codigo</th>
                                            <th>Direccion</th>
                                            <th>Estado</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    

                    
                  </div>                  
                </div>
                <!-- /.container-fluid -->
              </section>
              <!-- /.content -->     

              <!-- MODAL - BANCOS -->
              

              <!-- MODAL - COLOR -->
              

              <!-- MODAL - UNIDAD DE MEDIDA - chargue 1 -->
              <div class="modal fade" id="modal-agregar-unidad-m">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregar Unidad de Medida</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-unidad-m" name="form-unidad-m" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-1-fomulario">
                            <!-- id idunidad_medida -->
                            <input type="hidden" name="idunidad_medida" id="idunidad_medida" />

                            <!-- nombre_medida -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" name="nombre_medida" class="form-control" id="nombre_medida" placeholder="Nombre de la medida" />
                              </div>
                            </div>

                            <!-- abreviacion -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="abreviatura">Abreviación</label>
                                <input type="text" name="abreviatura" class="form-control" id="abreviatura" placeholder="abreviatura." />
                              </div>
                            </div>

                            <!-- Descripciòn -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="descripcion_m">Descripciòn</label>
                                <textarea name="descripcion_m" id="descripcion_m" class="form-control" rows="2"></textarea>                              
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                              <div class="progress" id="div_barra_progress_um">
                                <div id="barra_progress_um" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                        <button type="submit" style="display: none;" id="submit-form-unidad-m">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_unidades_m();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_unidad_m">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>             

              <!-- MODAL - TIPO DE PERSONA - chargue 3 -->
              <div class="modal fade" id="modal-agregar-tipo">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Tipo Persona</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-tipo" name="form-tipo" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-3-fomulario">
                            <!-- id idunidad_medida -->
                            <input type="hidden" name="idtipo_persona" id="idtipo_persona" />

                            <!-- nombre_medida -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="nombre_tipo">Nombre Tipo Persona</label>
                                <input type="text" name="nombre_tipo" id="nombre_tipo" class="form-control" placeholder="Nombre tipo Persona" />
                              </div>
                            </div>

                              <!-- Descripciòn -->
                              <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="descripcion_t">Descripciòn</label>
                                <textarea name="descripcion_t" id="descripcion_t" class="form-control" rows="2"></textarea>
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                              <div class="progress" id="div_barra_progress_tipo">
                                <div id="barra_progress_tipo" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                        </div>
                        <!-- /.card-body -->
                        <button type="submit" style="display: none;" id="submit-form-tipo">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_tipo();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_tipo">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MODAL - CARGO TRABAJDOR - chargue 5 -->
              <div class="modal fade" id="modal-agregar-cargo">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Cargo Trabajdor</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-cargo" name="form-cargo" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-5-fomulario">
                            <!-- id idunidad_medida -->
                            <input type="hidden" name="idcargo_trabajador" id="idcargo_trabajador" />


                            <!-- nombre_trabajador -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="nombre_cargo">Nombre Cargo</label>
                                <input type="text" name="nombre_cargo" id="nombre_cargo" class="form-control" placeholder="Nombre Cargo" />
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                              <div class="progress" id="div_barra_progress_cargo">
                                <div id="barra_progress_cargo" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                  0%
                                </div>
                              </div>
                            </div>

                          </div>

                          <div class="row" id="cargando-6-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                        </div>
                        <!-- /.card-body -->
                        <button type="submit" style="display: none;" id="submit-form-cargo">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_cargo();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_cargo">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MODAL - LABORATORIO - chargue 7 -->
              <div class="modal fade" id="modal-agregar-laboratorio">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregar Laboratorio</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-laboratorio" name="form-laboratorio" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-7-fomulario">
                            <!-- id categoria_insumos_af -->
                            <input type="hidden" name="idlaboratorio" id="idlaboratorio" />

                            <!-- nombre categoria -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="nombre_laboratorio">Nombre Marca</label>
                                <input type="text" name="nombre_laboratorio" id="nombre_laboratorio" class="form-control" placeholder="Nombre Marca" />
                              </div>
                            </div>
                            
                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                              <div class="progress" id="div_barra_progress_laboratorio">
                                <div id="barra_progress_laboratorio" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                  0%
                                </div>
                              </div>
                            </div>

                          </div>

                          <div class="row" id="cargando-8-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                        </div>
                        <!-- /.card-body -->
                        <button type="submit" style="display: none;" id="submit-form-laboratorio">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_form_laboratorio();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_laboratorio">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MODAL - PRESENTACIÓN - chargue 9 -->
              <div class="modal fade" id="modal-agregar-presentacion">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregar Presentación</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-presentacion" name="form-presentacion" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-9-fomulario">
                            <!-- id idpresentacion -->
                            <input type="hidden" name="idpresentacion" id="idpresentacion" />

                            <!-- nombre_presentacion -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="nombre_presentacion">Nombre</label>
                                <input type="text" name="nombre_presentacion" class="form-control" id="nombre_presentacion" placeholder="Nombre de la Presentacion" />
                              </div>
                            </div>

                            <!-- Descripciòn -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="descripcion_p">Descripciòn</label>
                                <textarea name="descripcion_p" id="descripcion_p" class="form-control" rows="2"></textarea>                              
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                              <div class="progress" id="div_barra_progress_um">
                                <div id="barra_progress_um" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                  0%
                                </div>
                              </div>
                            </div>

                          </div>

                          <div class="row" id="cargando-10-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                        </div>
                        <!-- /.card-body -->
                        <button type="submit" style="display: none;" id="submit-form-presentacion">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_presentacion();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_presentacion">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MODAL - LOTE - chargue 11 -->
              <div class="modal fade" id="modal-agregar-lote">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregar Lote</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-lote" name="form-lote" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-11-fomulario">
                            <!-- id idunidad_medida -->
                            <input type="hidden" name="idlote" id="idlote" />

                            <!-- nombre_medida -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="nombre_lote">Nombre</label>
                                <input type="text" name="nombre_lote" class="form-control" id="nombre_lote" placeholder="Nombre del Lote" />
                              </div>
                            </div>

                            <!-- Fecha de vencimiento -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="fecha_vencimiento">Fecha de Vencimiento </label>
                                <input class="form-control" type="date" id="fecha_vencimiento" name="fecha_vencimiento" min=<?php $hoy=date("Y-m-d"); echo $hoy;?> />
                              </div>
                            </div>                            

                            <!-- Descripciòn -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="descripcion_lot">Descripción</label>
                                <textarea name="descripcion_lot" id="descripcion_lot" class="form-control" rows="2"></textarea>                              
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                              <div class="progress" id="barra_progress_lote_div">
                                <div id="barra_progress_lote" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                  0%
                                </div>
                              </div>
                            </div>

                          </div>

                          <div class="row" id="cargando-12-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                        </div>
                        <!-- /.card-body -->
                        <button type="submit" style="display: none;" id="submit-form-lote">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_lote();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_lote">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MODAL - SUCURSAL - chargue 13 -->
              <div class="modal fade" id="modal-agregar-sucursal">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregar Sucursal</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-sucursal" name="form-sucursal" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-13-fomulario">
                            <!-- id idunidad_medida -->
                            <input type="hidden" name="idsucursal" id="idsucursal" />

                            <!-- nombre_medida -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="nombre_sucursal">Nombre</label>
                                <input type="text" name="nombre_sucursal" class="form-control" id="nombre_sucursal" placeholder="Nombre del sucursal" />
                              </div>
                            </div>

                            <!-- Codigo -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="codigo_sucursal">Codigo</label>
                                <input type="text" name="codigo_sucursal" class="form-control" id="codigo_sucursal" placeholder="Codigo del sucursal" />
                              </div>
                            </div>                         

                            <!-- Direccion -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="direccion_sucursal">Direccion</label>
                                <textarea name="direccion_sucursal" id="direccion_sucursal" class="form-control" rows="2"></textarea>                              
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="barra_progress_sucursal_div">
                              <div class="progress" >
                                <div id="barra_progress_sucursal" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                  0%
                                </div>
                              </div>
                            </div>

                          </div>

                          <div class="row" id="cargando-14-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                        </div>
                        <!-- /.card-body -->
                        <button type="submit" style="display: none;" id="submit-form-sucursal">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_sucursal();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_sucursal">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MODAL - VER PERFIL INSUMO-->
              <div class="modal fade" id="modal-ver-perfil-banco">
                <div class="modal-dialog modal-dialog-centered modal-md">
                  <div class="modal-content bg-color-0202022e shadow-none border-0">
                    <div class="modal-header">
                      <h4 class="modal-title text-white foto-banco">Foto Insumo</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-white cursor-pointer" aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body"> 
                      <div id="perfil-banco" class="class-style">
                        <!-- vemos los datos del trabajador -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>

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
        
        <script type="text/javascript" src="scripts/otros.js"></script>
        <script type="text/javascript" src="scripts/unidades_m.js"></script>
        <script type="text/javascript" src="scripts/tipo_persona.js"></script>
        <script type="text/javascript" src="scripts/cargo.js"></script>
        <script type="text/javascript" src="scripts/laboratorio.js"></script>
        <script type="text/javascript" src="scripts/presentacion.js"></script>
        <script type="text/javascript" src="scripts/lote.js"></script>
        <script type="text/javascript" src="scripts/sucursal.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
