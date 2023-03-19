<?php
  //Activamos el almacenamiento en el buffer
  ob_start();

  session_start();
  if (!isset($_SESSION["nombre"])){
    header("Location: index.php?file=".basename($_SERVER['PHP_SELF']));
  }else{
    ?>
    <!DOCTYPE html>
    <html lang="en">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Producto | Admin Briment</title>
        <?php $title = "Producto"; require 'head.php';  ?>       

        <link rel="stylesheet" href="../dist/css/switch_materiales.css">

      </head>
      <body class="hold-transition sidebar-mini">
        <div class="wrapper">

          <!-- Navbar -->
          <?php require 'nav.php';?>          
          <!-- /.navbar -->      

          <!-- Main Sidebar Container -->
          <?php require 'aside.php'; ?>

          <!-- Content Wrapper. Contains page content -->
          <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
              <div class="container-fluid">
                <div class="row mb-2">
                  <div class="col-sm-6">
                    <h1>DataTables</h1>
                  </div>
                  <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="#">Home</a></li>
                      <li class="breadcrumb-item active">DataTables</li>
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
                    <div class="card hidden">
                      <div class="card-header">
                        <h3 class="card-title">DataTable with minimal features & hover style</h3>
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">
                        <table id="example2" class="table table-bordered table-hover">
                          <thead>
                            <tr>
                              <th>Rendering engine</th>
                              <th>Browser</th>
                              <th>Platform(s)</th>
                              <th>Engine version</th>
                              <th>CSS grade</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>Trident</td>
                              <td>Internet Explorer 4.0</td>
                              <td>Win 95+</td>
                              <td>4</td>
                              <td>X</td>
                            </tr>
                            <tr>
                              <td>Trident</td>
                              <td>Internet Explorer 5.0</td>
                              <td>Win 95+</td>
                              <td>5</td>
                              <td>C</td>
                            </tr>
                            <tr>
                              <td>Trident</td>
                              <td>Internet Explorer 5.5</td>
                              <td>Win 95+</td>
                              <td>5.5</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Trident</td>
                              <td>Internet Explorer 6</td>
                              <td>Win 98+</td>
                              <td>6</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Trident</td>
                              <td>Internet Explorer 7</td>
                              <td>Win XP SP2+</td>
                              <td>7</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Trident</td>
                              <td>AOL browser (AOL desktop)</td>
                              <td>Win XP</td>
                              <td>6</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Gecko</td>
                              <td>Firefox 1.0</td>
                              <td>Win 98+ / OSX.2+</td>
                              <td>1.7</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Gecko</td>
                              <td>Firefox 1.5</td>
                              <td>Win 98+ / OSX.2+</td>
                              <td>1.8</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Gecko</td>
                              <td>Firefox 2.0</td>
                              <td>Win 98+ / OSX.2+</td>
                              <td>1.8</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Gecko</td>
                              <td>Firefox 3.0</td>
                              <td>Win 2k+ / OSX.3+</td>
                              <td>1.9</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Gecko</td>
                              <td>Camino 1.0</td>
                              <td>OSX.2+</td>
                              <td>1.8</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Gecko</td>
                              <td>Camino 1.5</td>
                              <td>OSX.3+</td>
                              <td>1.8</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Gecko</td>
                              <td>Netscape 7.2</td>
                              <td>Win 95+ / Mac OS 8.6-9.2</td>
                              <td>1.7</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Gecko</td>
                              <td>Netscape Browser 8</td>
                              <td>Win 98SE+</td>
                              <td>1.7</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Gecko</td>
                              <td>Netscape Navigator 9</td>
                              <td>Win 98+ / OSX.2+</td>
                              <td>1.8</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Gecko</td>
                              <td>Mozilla 1.0</td>
                              <td>Win 95+ / OSX.1+</td>
                              <td>1</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Gecko</td>
                              <td>Mozilla 1.1</td>
                              <td>Win 95+ / OSX.1+</td>
                              <td>1.1</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Gecko</td>
                              <td>Mozilla 1.2</td>
                              <td>Win 95+ / OSX.1+</td>
                              <td>1.2</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Gecko</td>
                              <td>Mozilla 1.3</td>
                              <td>Win 95+ / OSX.1+</td>
                              <td>1.3</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Gecko</td>
                              <td>Mozilla 1.4</td>
                              <td>Win 95+ / OSX.1+</td>
                              <td>1.4</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Gecko</td>
                              <td>Mozilla 1.5</td>
                              <td>Win 95+ / OSX.1+</td>
                              <td>1.5</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Gecko</td>
                              <td>Mozilla 1.6</td>
                              <td>Win 95+ / OSX.1+</td>
                              <td>1.6</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Gecko</td>
                              <td>Mozilla 1.7</td>
                              <td>Win 98+ / OSX.1+</td>
                              <td>1.7</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Gecko</td>
                              <td>Mozilla 1.8</td>
                              <td>Win 98+ / OSX.1+</td>
                              <td>1.8</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Gecko</td>
                              <td>Seamonkey 1.1</td>
                              <td>Win 98+ / OSX.2+</td>
                              <td>1.8</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Gecko</td>
                              <td>Epiphany 2.20</td>
                              <td>Gnome</td>
                              <td>1.8</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Webkit</td>
                              <td>Safari 1.2</td>
                              <td>OSX.3</td>
                              <td>125.5</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Webkit</td>
                              <td>Safari 1.3</td>
                              <td>OSX.3</td>
                              <td>312.8</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Webkit</td>
                              <td>Safari 2.0</td>
                              <td>OSX.4+</td>
                              <td>419.3</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Webkit</td>
                              <td>Safari 3.0</td>
                              <td>OSX.4+</td>
                              <td>522.1</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Webkit</td>
                              <td>OmniWeb 5.5</td>
                              <td>OSX.4+</td>
                              <td>420</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Webkit</td>
                              <td>iPod Touch / iPhone</td>
                              <td>iPod</td>
                              <td>420.1</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Webkit</td>
                              <td>S60</td>
                              <td>S60</td>
                              <td>413</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Presto</td>
                              <td>Opera 7.0</td>
                              <td>Win 95+ / OSX.1+</td>
                              <td>-</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Presto</td>
                              <td>Opera 7.5</td>
                              <td>Win 95+ / OSX.2+</td>
                              <td>-</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Presto</td>
                              <td>Opera 8.0</td>
                              <td>Win 95+ / OSX.2+</td>
                              <td>-</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Presto</td>
                              <td>Opera 8.5</td>
                              <td>Win 95+ / OSX.2+</td>
                              <td>-</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Presto</td>
                              <td>Opera 9.0</td>
                              <td>Win 95+ / OSX.3+</td>
                              <td>-</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Presto</td>
                              <td>Opera 9.2</td>
                              <td>Win 88+ / OSX.3+</td>
                              <td>-</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Presto</td>
                              <td>Opera 9.5</td>
                              <td>Win 88+ / OSX.3+</td>
                              <td>-</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Presto</td>
                              <td>Opera for Wii</td>
                              <td>Wii</td>
                              <td>-</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Presto</td>
                              <td>Nokia N800</td>
                              <td>N800</td>
                              <td>-</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Presto</td>
                              <td>Nintendo DS browser</td>
                              <td>Nintendo DS</td>
                              <td>8.5</td>
                              <td>C/A<sup>1</sup></td>
                            </tr>
                            <tr>
                              <td>KHTML</td>
                              <td>Konqureror 3.1</td>
                              <td>KDE 3.1</td>
                              <td>3.1</td>
                              <td>C</td>
                            </tr>
                            <tr>
                              <td>KHTML</td>
                              <td>Konqureror 3.3</td>
                              <td>KDE 3.3</td>
                              <td>3.3</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>KHTML</td>
                              <td>Konqureror 3.5</td>
                              <td>KDE 3.5</td>
                              <td>3.5</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Tasman</td>
                              <td>Internet Explorer 4.5</td>
                              <td>Mac OS 8-9</td>
                              <td>-</td>
                              <td>X</td>
                            </tr>
                            <tr>
                              <td>Tasman</td>
                              <td>Internet Explorer 5.1</td>
                              <td>Mac OS 7.6-9</td>
                              <td>1</td>
                              <td>C</td>
                            </tr>
                            <tr>
                              <td>Tasman</td>
                              <td>Internet Explorer 5.2</td>
                              <td>Mac OS 8-X</td>
                              <td>1</td>
                              <td>C</td>
                            </tr>
                            <tr>
                              <td>Misc</td>
                              <td>NetFront 3.1</td>
                              <td>Embedded devices</td>
                              <td>-</td>
                              <td>C</td>
                            </tr>
                            <tr>
                              <td>Misc</td>
                              <td>NetFront 3.4</td>
                              <td>Embedded devices</td>
                              <td>-</td>
                              <td>A</td>
                            </tr>
                            <tr>
                              <td>Misc</td>
                              <td>Dillo 0.8</td>
                              <td>Embedded devices</td>
                              <td>-</td>
                              <td>X</td>
                            </tr>
                            <tr>
                              <td>Misc</td>
                              <td>Links</td>
                              <td>Text only</td>
                              <td>-</td>
                              <td>X</td>
                            </tr>
                            <tr>
                              <td>Misc</td>
                              <td>Lynx</td>
                              <td>Text only</td>
                              <td>-</td>
                              <td>X</td>
                            </tr>
                            <tr>
                              <td>Misc</td>
                              <td>IE Mobile</td>
                              <td>Windows Mobile 6</td>
                              <td>-</td>
                              <td>C</td>
                            </tr>
                            <tr>
                              <td>Misc</td>
                              <td>PSP browser</td>
                              <td>PSP</td>
                              <td>-</td>
                              <td>C</td>
                            </tr>
                            <tr>
                              <td>Other browsers</td>
                              <td>All others</td>
                              <td>-</td>
                              <td>-</td>
                              <td>U</td>
                            </tr>
                          </tbody>
                          <tfoot>
                            <tr>
                              <th>Rendering engine</th>
                              <th>Browser</th>
                              <th>Platform(s)</th>
                              <th>Engine version</th>
                              <th>CSS grade</th>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                      <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">DataTable con: EXCEL,PDF,COPY, PRINT,CSV</h3>
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th class="pb-1 text-center">#</th>
                              <th class="pb-1">Acciones</th>
                              <th class="pb-1">Code</th>
                              <th class="pb-1">Nombre</th>
                              <th class="pb-1">Categoria</th>
                              <th class="pb-1" data-toggle="tooltip" data-original-title="Unidad Medida">UM</th>
                              <th class="pb-1">Precio </th>
                              <th class="pb-1">Stock</th>
                              <th class="pb-1">Contenido Neto</th>
                              <th class="pb-1">Descripción</th> 

                              <th>Nombre</th>
                              <th>Marca</th>
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
                              <th >Precio </th>
                              <th>Stock</th>
                              <th>Contenido Neto</th>
                              <th>Descripción</th> 

                              <th>Nombre</th>
                              <th>Marca</th>
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
            </section>
            <!-- /.content -->
          </div>
          <!-- /.content-wrapper -->
          <footer class="main-footer">
            <div class="float-right d-none d-sm-block"><b>Version</b> 3.1.0</div>
            <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
          </footer>

          <!-- Control Sidebar -->
          <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
          </aside>
          <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->

        <?php  require 'script.php'; ?>   

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>

        <!-- Page specific script -->
        <script>
          $(function () {
            var tabla_data = $("#example1").DataTable({
              ajax: {
                url: `../ajax/producto.php?op=tbla_principal&idcategoria=todos`,
                type: "get",
                dataType: "json",
                error: function (e) {
                  console.log(e.responseText); ver_errores(e);
                },
              },
              dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
              responsive: true,
              lengthChange: true,
              autoWidth: false,
              "deferRender": true,
              'stateSave': true,
              buttons: [
                { extend: "copy", text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn btn-primary", exportOptions: { columns: "th:not(:last-child)", }, },
                { extend: "excel", text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn btn-success", exportOptions: { columns: "th:not(:last-child)", }, },
                { extend: "pdf", text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn btn-warning", exportOptions: { columns: "th:not(:last-child)", }, },
                { extend: "colvis", text: `Columnas`, className: "btn btn-primary", exportOptions: { columns: "th:not(:last-child)", }, },
              ],                  
            });

            tabla_data.buttons().container().appendTo( $('.col-sm-6:eq(0)', tabla_data.table().container() ) );
              
            $("#example2").DataTable({
              paging: true,
              lengthChange: false,
              searching: false,
              ordering: true,
              info: true,
              autoWidth: false,
              responsive: true,
            });

            $('[data-toggle="tooltip"]').tooltip();
          });
        </script>
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>