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
        <title>Activos fijos | Admin JDL</title>
        
        <?php $title = "Activos fijos"; require 'head.php'; ?>
        
        <!--CSS  switch_MATERIALES-->
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
                      <h1>Activos fijos</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Activos fijos</li>
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
                            <button type="button" class="btn bg-gradient-primary" data-toggle="modal" data-target="#modal-agregar-activos-fijos" onclick="limpiar();"><i class="fas fa-plus-circle"></i> Agregar</button>
                            Admnistra de manera eficiente a tus activos fijos.
                          </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body px-1 py-1">
                          <div class="row">                              
                            <div class=" col-12 col-sm-12">
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
                                          <table id="tabla-activos" class="table table-bordered table-striped display" style="width: 100% !important;">
                                            <thead> 
                                              <tr>
                                                <th class="text-center">#</th>
                                                <th class="">Acciones</th>
                                                <th class="">Code</th>
                                                <th>Nombre</th>
                                                <th>Categoria</th>
                                                <th data-toggle="tooltip" data-original-title="Unidad Medida">UM</th>
                                                <th class="text-center" data-toggle="tooltip" data-original-title="Precio Unitario">Precio ingresado</th>
                                                <th class="text-center" data-toggle="tooltip" data-original-title="Sub total">Sub total</th>
                                                <th class="text-center" data-toggle="tooltip" data-original-title="IGV">IGV</th>
                                                <th class="text-center" data-toggle="tooltip" data-original-title="Precio real">Precio real</th>
                                                <th data-toggle="tooltip" data-original-title="Ficha técnica">FT</th>
                                                <th>Nombre</th>
                                                <th>Marca</th>
                                                <th>Color</th>
                                                <th>Descripción</th>
                                              </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                              <tr>
                                                <th class="text-center">#</th>
                                                <th class="">Acciones</th>
                                                <th class="">Code</th>
                                                <th>Nombre</th>
                                                <th>Categoria</th>
                                                <th data-toggle="tooltip" data-original-title="Unidad Medida">UM</th>
                                                <th class="text-center" data-toggle="tooltip" data-original-title="Precio Ingresado">Precio ingresado</th>
                                                <th class="text-center" data-toggle="tooltip" data-original-title="Sub total">Sub total</th>
                                                <th class="text-center" data-toggle="tooltip" data-original-title="IGV">IGV</th>
                                                <th class="text-center" data-toggle="tooltip" data-original-title="Precio real">Precio real</th>
                                                <th data-toggle="tooltip" data-original-title="Ficha técnica">FT</th>
                                                <th>Nombre</th>
                                                <th>Marca</th>
                                                <th>Color</th>
                                                <th>Descripción</th>
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

                <!-- MODAL - AGREGAR ACTIVOS FIJOS -->
                <div class="modal fade" id="modal-agregar-activos-fijos">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Agregar Activo fijo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-materiales-activos-fijos" name="form-materiales-activos-fijos" method="POST">
                          <div class="card-body">
                            <div class="row" id="cargando-1-fomulario">
                              <!--  -->
                              <input type="hidden" name="idproducto" id="idproducto" />   
                              <input type="hidden" name="idtipo_tierra_concreto" id="idtipo_tierra_concreto" value="1">   
                              
                              <input type="hidden" id="modelo" name="modelo" />
                              <input type="hidden" id="serie" name="serie" />
                              <input type="hidden" id="color" name="color" value="1" />

                              <!-- Nombre -->
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                  <label for="nombre">Nombre <sup class="text-danger">(unico*)</sup></label>
                                  <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombre del activo." />
                                </div>
                              </div>

                              <!-- Categoria -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="categoria_insumos_af">Clasificación <sup class="text-danger">(unico*)</sup></label>
                                  <select name="categoria_insumos_af" id="categoria_insumos_af" class="form-control select2" style="width: 100%;"> 
                                  </select>
                                </div>
                              </div>

                              <!-- Modelo -->
                              <!-- <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="modelo">Modelo <sup class="text-danger">*</sup> </label>
                                  <input class="form-control" type="text" id="modelo" name="modelo" placeholder="Modelo." />
                                </div>
                              </div> -->

                              <!-- Serie -->
                              <!-- <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="serie">Serie </label>
                                  <input class="form-control" type="text" id="serie" name="serie" placeholder="Serie." />
                                </div>
                              </div> -->

                              <!-- Marca
                              <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="marca">Marca </label>
                                  <input class="form-control" type="text" id="marca" name="marca" placeholder="Marca de activo." />
                                </div>
                              </div> -->

                              <!-- Marca -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <label for="marca">Marca <sup class="text-danger">(unico*)</sup></label>
                                  <select name="marca" id="marca" class="form-control select2" style="width: 100%;"> </select>
                                </div>
                              </div>
                              
                              <!-- Unnidad de medida-->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-6" >
                                <div class="form-group">
                                  <label for="Unidad-medida">Unidad-medida <sup class="text-danger">(unico*)</sup></label>
                                  <select name="unid_medida" id="unid_medida" class="form-control select2" style="width: 100%;"> </select>
                                </div>
                              </div>

                              <!--Precio Unitario-->
                              <div class="col-7 col-sm-7 col-md-8 col-lg-4">
                                <div class="form-group">
                                  <label for="precio_unitario">Precio <sup class="text-danger">*</sup></label>
                                  <input type="text" name="precio_unitario" class="form-control miimput" id="precio_unitario" placeholder="Precio Unitario." onchange="precio_con_igv();" onkeyup="precio_con_igv();" />
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

                              <!--Sub Total subtotal igv total-->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="precio_sin_igv">Sub Total</label>
                                  <input type="text" class="form-control" name="precio_sin_igv" id="precio_sin_igv" placeholder="Subtotal" onchange="precio_con_igv();" onkeyup="precio_con_igv();" readonly />
                                </div>
                              </div>

                              <!--IGV-->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="precio_igv">IGV</label>
                                  <input type="text" class="form-control" name="precio_igv" id="precio_igv" placeholder="IGV" onchange="precio_con_igv();" onkeyup="precio_con_igv();" readonly />
                                </div>
                              </div>

                              <!--Total-->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="precio_total">Total</label>
                                  <input type="text" class="form-control" name="precio_total" id="precio_total" placeholder="Precio real." readonly />
                                </div>
                              </div>

                              <!--Descripcion-->
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                  <label for="descripcion_pago">Descripción </label> <br />
                                  <textarea name="descripcion" id="descripcion" class="form-control" rows="2"></textarea>
                                </div>
                              </div>

                              <!--imagen-material-->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                <label for="foto1">Imagen</label>
                                <div style="text-align: center;">
                                  <img
                                    onerror="this.src='../dist/img/default/img_defecto_activo_fijo.png';"
                                    src="../dist/img/default/img_defecto_activo_fijo.png"
                                    class="img-thumbnail"
                                    id="foto1_i"
                                    style="cursor: pointer !important; height: 100% !important;"
                                    width="auto"
                                  />
                                  <input style="display: none;" type="file" name="foto1" id="foto1" accept="image/*" />
                                  <input type="hidden" name="foto1_actual" id="foto1_actual" />
                                  <div class="text-center" id="foto1_nombre"><!-- aqui va el nombre de la FOTO --></div>
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
                                    <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(2, 'ficha_tecnica');">
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
                          <button type="submit" style="display: none;" id="submit-form-activos-fijos">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar();">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL-->
                <div class="modal fade" id="modal-ver-activos-fijos">
                  <div class="modal-dialog modal-dialog-scrollable modal-xm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Datos Activos Fijos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <div id="datos-activos-fjos" class=""></div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - FICHA TECNICA-->
                <div class="modal fade" id="modal-ver-ficha_tec">
                  <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Ficha Técnica</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="class-style" style="text-align: center;">
                          <a class="btn btn-warning btn-block" href="#" id="iddescargar" download="Ficha Técnica" style="padding: 0px 12px 0px 12px !important;" type="button"><i class="fas fa-download"></i></a>
                          <br />
                          <img onerror="this.src='../dist/img/default/img_defecto.png';" src="../dist/img/default/img_defecto.png" class="img-thumbnail" id="img-factura" style="cursor: pointer !important;" width="auto" />
                          <div id="ver_fact_pdf" style="cursor: pointer !important;" width="auto"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - VER PERFIL INSUMO-->
                <div class="modal fade" id="modal-ver-perfil-activo-fijo">
                  <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content bg-color-0202022e shadow-none border-0">
                      <div class="modal-header">
                        <h4 class="modal-title text-white foto-insumo">Foto Activo Fijo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-white cursor-pointer" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body"> 
                        <div id="perfil-insumo" class="class-style">
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

        <script type="text/javascript" src="scripts/activos_fijos.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
