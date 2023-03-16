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
        <title>Resumen Venta Producto | Admin JDL</title>       

        <?php $title = "Resumen Venta Producto"; require 'head.php';  ?>
        <link rel="stylesheet" href="../dist/css/switch_materiales.css">
      </head>
      <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['venta_abono']==1){
            //require 'enmantenimiento.php';
            ?>   
          
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper" >
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1 class="m-0 nombre-insumo"><img src="../dist/svg/negro-abono-ico.svg" class="nav-icon" alt="" style="width: 21px !important;"> Resumen de Producto</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="resumen_producto.php">Home</a></li>
                        <li class="breadcrumb-item active">Resumen</li>
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

                          <h3 class="card-title mensaje-tbla-principal" >                           
                            Lista de materiales usado en este proyecto                        
                          </h3>  

                          <!-- regresar "tabla principal" -->
                          <h3 class="card-title mr-3" id="btn-regresar" style="display: none; padding-left: 2px;" >
                            <button type="button" class="btn bg-gradient-warning btn-sm" onclick="table_show_hide(1);"  ><i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline-block">Regresar</span> </button>
                          </h3>

                          <!-- regresar "tabla principal" -->
                          <h3 class="card-title mr-3" id="btn-regresar-todo" style="display: none; padding-left: 2px;" data-toggle="tooltip" data-original-title="Regresar a la tabla principal">
                            <button type="button" class="btn btn-block btn-outline-warning btn-sm" onclick="table_show_hide(1);"><i class="fas fa-arrow-left"></i></button>
                          </h3>

                          <!-- regresar "tabla facuras" -->
                          <h3 class="card-title mr-3" id="btn-regresar-bloque" style="display: none; padding-left: 2px;" data-toggle="tooltip" data-original-title="Regresar a la tabla fechas">
                            <button type="button" class="btn bg-gradient-warning btn-sm" onclick="table_show_hide(2);"  ><i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline-block">Regresar</span> </button>
                          </h3>                    
                        </div>
                        
                        <div class="card-body">
                          <!-- TBLA PRINCIPAL  -->
                          <div id="tabla-principal">
                            <table id="tbla-resumen-insumos" class="table table-bordered table-striped" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Op</th>
                                  <th>Code</th>  
                                  <th class="">Producto</th>
                                  <th data-toggle="tooltip" data-original-title="Unidad de Medida">UM</th>
                                  <th class="">Cont. Neto</th>
                                  <th class="">Stock</th>                                  
                                  <th>Cantidad</th>
                                  <th>Compra</th> 
                                  <th>Precio venta</th> 
                                  <th>Descuento</th>    
                                  <th>Suma Total</th> 

                                  <th>Producto</th> 
                                  <th>Categoría</th> 
                                </tr>
                              </thead>
                              <tbody>                         
                                <!-- aqui la va el detalle de la tabla -->
                              </tbody>
                              <tfoot>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Op</th>
                                  <th>Code</th> 
                                  <th class="">Producto</th>
                                  <th>UM</th>
                                  <th class="">Cont. Neto</th>
                                  <th class="">Stock</th>                                  
                                  <th class="text-center">Cantidad</th> 
                                  <th>Compra</th>
                                  <th>Precio venta</th>
                                  <th class="text-nowrap px-2">0.00</th>   
                                  <th class="text-nowrap px-2"><i class="fas fa-spinner fa-pulse fa-sm"></i></th>       
                                  
                                  <th>Producto</th> 
                                  <th>Categoría</th> 
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                          <!-- TBLA FACTURAS  -->
                          <div id="tabla-factura" style="display: none !important;">
                            <table id="tbla-facura" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Op.</th>
                                  <th>Proveedor</th>
                                  <th>N° Comprob.</th>
                                  <th>Fecha compra</th>
                                  <th data-toggle="tooltip" data-original-title="Centidad">Cant.</th>
                                  <th>Precio</th>  
                                  <th data-toggle="tooltip" data-original-title="Descuento">Dcto.</th>
                                  <th>SubTotal</th>  
                                  <th>Tipo</th>
                                  <th>N° Comprob</th>
                                </tr>
                              </thead>
                              <tbody>                         
                                
                              </tbody>
                              <tfoot>
                                <tr>
                                  <th>#</th>
                                  <th>Op.</th>
                                  <th>Proveedor</th>
                                  <th>N° Comprob.</th>
                                  <th >Fecha compra</th>
                                  <th class="text-center px-2">0.00</th>
                                  <th class="text-nowrap px-2">Precio</th>  
                                  <th class="text-nowrap px-2">S/ 0.00</th> 
                                  <th class="text-nowrap px-2">S/ 0.00</th>                         
                                  <th>Tipo</th>
                                  <th>N° Comprob</th>
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

                <!-- MODAL - DETALLE MATERIALES O ACTIVOS FIJOS -->
                <div class="modal fade" id="modal-ver-detalle-material-activo-fijo">
                  <div class="modal-dialog modal-dialog-scrollable modal-xm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Datos Producto</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <div id="datosproductos" class="class-style">
                          <!-- vemos los datos del Producto -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Modal Ver compras - charge -->
                <div class="modal fade" id="modal-ver-compras">
                  <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Detalle Compra</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <div class="row detalle_de_compra" id="cargando-5-fomulario">                            
                          <!--detalle de la compra-->
                        </div>

                        <div class="row" id="cargando-6-fomulario" style="display: none;">
                          <div class="col-lg-12 text-center">
                            <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                            <br />
                            <h4>Cargando...</h4>
                          </div>
                        </div>

                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <a type="button" class="btn btn-success float-right" id="excel_compra" target="_blank" ><i class="far fa-file-excel"></i> Excel</a>
                        <a type="button" class="btn btn-info" id="print_pdf_compra" target="_blank" ><i class="fas fa-print"></i> Imprimir</a>
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
                      <div class="modal-body"> 
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

        <?php require 'script.php';  ?>

        <!-- table export -->
        <!-- <script src="../plugins/export-xlsx/xlsx.full.min.js"></script>
        <script src="../plugins/export-xlsx/FileSaver.min.js"></script>
        <script src="../plugins/export-xlsx/tableexport.min.js"></script> -->

        <script type="text/javascript" src="scripts/resumen_venta_producto.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html> 
    
    <?php  
  }
  ob_end_flush();

?>
