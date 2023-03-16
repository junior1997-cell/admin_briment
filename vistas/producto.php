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
        <title>Producto | Admin JDL</title>
        <?php $title = "Producto"; require 'head.php';  ?>       

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
                      <h1><img src="../dist/svg/negro-abono-ico.svg" class="nav-icon" alt="" style="width: 21px !important;"> Producto</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Insumos</li>
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
                            <button type="button" class="btn bg-gradient-primary" data-toggle="modal" data-target="#modal-agregar-material" onclick="limpiar_form_material();"><i class="fas fa-plus-circle"></i> Agregar</button>
                            Admnistra de manera eficiente de tus Insumos.
                          </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body px-1 py-1">
                          <div class="row">
                            <div class="col-12 col-sm-12">
                              <div class="card card-primary card-outline card-outline-tabs mb-0">
                                <div class="card-header p-0 border-bottom-0">
                                  <ul class="nav nav-tabs lista-items" id="tabs-for-tab" role="tablist">
                                    <li class="nav-item">
                                      <a class="nav-link active" role="tab" ><i class="fas fa-spinner fa-pulse fa-sm"></i></a>
                                    </li>           
                                  </ul>
                                </div>
                                <div class="card-body" >                                  
                                  <div class="tab-content" id="tabs-for-tabContent">
                                    <!-- TABLA - RESUMEN -->
                                    <div class="tab-pane fade show active" id="tabs-for-activo-fijo" role="tabpanel" aria-labelledby="tabs-for-activo-fijo-tab">
                                      <div class="row">                                        
                                        <div class="col-12">
                                          <table id="tabla-materiales" class="table table-bordered table-striped display" style="width: 100% !important;">
                                            <thead>
                                              <tr>
                                                <th class="py-1text-center">#</th>
                                                <th class="py-1">Acciones</th>
                                                <th class="py-1">Code</th>
                                                <th class="py-1">Nombre</th>
                                                <th class="py-1">Categoria</th>
                                                <th class="py-1" data-toggle="tooltip" data-original-title="Unidad Medida">UM</th>
                                                <th class="py-1">Precio </th>
                                                <th class="py-1">Stock</th>
                                                <th class="py-1">Contenido Neto</th>
                                                <th class="py-1">Descripción</th> 

                                                <th>Nombre</th>
                                                <th>Marca</th>
                                              </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                              <tr>
                                              <th class="py-1 text-center">#</th>
                                              <th class="py-1">Acciones</th>
                                              <th class="py-1">Code</th>
                                              <th class="py-1">Nombre</th>
                                              <th class="py-1">Categoria</th>
                                              <th class="py-1" data-toggle="tooltip" data-original-title="Unidad Medida">UM</th>
                                              <th class="py-1">Precio </th>
                                              <th class="py-1">Stock</th>
                                              <th class="py-1">Contenido Neto</th>
                                              <th class="py-1">Descripción</th>

                                              <th>Nombre</th>
                                              <th>Marca</th>
                                              
                                              </tr>
                                            </tfoot>
                                          </table>
                                        </div>
                                        <!-- /.col -->
                                      </div>
                                      <!-- /.row -->
                                    </div>                                    
                                  </div>
                                  <!-- /.tab-content -->
                                </div>
                                <!-- /.card-body -->
                              </div>
                              <!-- /.card -->
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

                <!-- MODAL - AGREGAR MATERIAL - charge-1 -->
                <div class="modal fade" id="modal-agregar-material">
                  <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title name-modal-title-agregar">Agregar Producto</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-materiales" name="form-materiales" method="POST" autocomplete="off">
                          <div class="card-body">
                            <div class="row" id="cargando-1-fomulario">
    
                              <!-- id proveedores -->
                              <input type="hidden" name="idproducto" id="idproducto" />
                              <!-- Nombre -->
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                  <label for="nombre_producto">Nombre <sup class="text-danger">(unico*)</sup></label>
                                  <input type="text" name="nombre_producto" class="form-control" id="nombre_producto" placeholder="Nombre del Insumo." />
                                </div>
                              </div>

                              <!-- Categoria -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="categoria_producto">Categoria <sup class="text-danger">(unico*)</sup></label>
                                  <select name="categoria_producto" id="categoria_producto" class="form-control select2" style="width: 100%;"> 
                                  </select>
                                </div>
                              </div>                               

                              <!-- Unnidad de medida-->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-6" >
                                <div class="form-group">
                                  <label for="Unidad_medida">Unidad medida <sup class="text-danger">(unico*)</sup></label>
                                  <select name="unidad_medida" id="unidad_medida" class="form-control select2" style="width: 100%;"> </select>
                                </div>
                              </div>

                               <!-- Marca -->
                               <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="marca">Marca <sup class="text-danger">(unico*)</sup></label>
                                  <input type="text" name="marca" class="form-control" id="marca" placeholder="Marca." />
                                </div>
                              </div>

                              <!-- ContenidoNeto -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="contenido_neto">Contenido Neto <sup class="text-danger">(unico*)</sup></label>
                                  <input type="text" name="contenido_neto" id="contenido_neto" class="form-control"  placeholder="Contenido Neto." />
                                </div>
                              </div>

                              <!--Precio U-->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="precio_unitario">Precio <sup class="text-danger">*</sup></label>
                                  <input type="text" name="precio_unitario" id="precio_unitario" value="0" class="form-control" readonly  />
                                </div>
                              </div>

                              <!-- Stock -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-6x">
                                <div class="form-group">
                                  <label for="stock">Stock: </label>
                                  <input type="text" name="stock" id="stock" value="0.00"  class="form-control" readonly="readonly">                                  
                                </div>
                              </div> 

                              <!-- Descripcion-->
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                  <label for="descripcion">Descripción </label> <br />
                                  <textarea name="descripcion" id="descripcion" class="form-control" rows="2"></textarea>
                                </div>
                              </div>

                              <!--imagen-material-->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                <label for="foto1">Imagen</label>
                                <div style="text-align: center;">
                                  <img onerror="this.src='../dist/img/default/img_defecto_producto.jpg';" src="../dist/img/default/img_defecto_producto.jpg"
                                    class="img-thumbnail" id="foto1_i" style="cursor: pointer !important; height: 100% !important;" width="auto" />
                                  <input style="display: none;" type="file" name="foto1" id="foto1" accept="image/*" />
                                  <input type="hidden" name="foto1_actual" id="foto1_actual" />
                                  <div class="text-center" id="foto1_nombre"><!-- aqui va el nombre de la FOTO --></div>
                                </div>
                              </div>
                              
                              <!-- barprogress -->
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                                <div class="progress" id="barra_progress_div">
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

                <!-- MODAL - VER DETALLE INSUMO-->
                <div class="modal fade" id="modal-ver-insumo">
                  <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Datos del Producto</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <div id="datosinsumo" class="class-style">
                          <!-- vemos los datos del trabajador -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - VER PERFIL INSUMO-->
                <div class="modal fade" id="modal-ver-perfil-insumo">
                  <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content bg-color-0202022e shadow-none border-0">
                      <div class="modal-header">
                        <h4 class="modal-title text-white foto-insumo">Foto Insumo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-white cursor-pointer" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body "> 
                        <div id="perfil-insumo" class="text-center">
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

        <?php  require 'script.php'; ?>        

        <script type="text/javascript" src="scripts/producto.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>

      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
