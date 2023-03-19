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
        <title>Trabajadores | Admin Briment</title>

        <?php $title = "Trabajadores"; require 'head.php'; ?>
        <link rel="stylesheet" href="../dist/css/switch_domingo.css">

      </head>
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed">
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
                      <h1>Trabajadores</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="trabajador.php">Home</a></li>
                        <li class="breadcrumb-item active">Trabajadores</li>
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
                            <button type="button" class="btn bg-gradient-primary" data-toggle="modal" data-target="#modal-agregar-trabajador" onclick="limpiar_form_trabajador();"><i class="fas fa-user-plus"></i> Agregar</button>
                            
                            
                            Admnistra de manera eficiente a los trabajdores
                          </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                          <!-- Lista de trabajdores activos -->                      
                          <table id="tabla-trabajador" class="table table-bordered table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th class="text-center">#</th>
                                <th class="">Aciones</th>
                                <th>Nombres</th>
                                <th>Cargo</th>
                                <th>Sueldo</th>
                                <th>Telefono</th>
                                <th>Fecha Nac. / Edad</th>
                                <th>Cuenta bancaria</th>
                                <th>Estado</th>
                                <th>Nombres</th>
                                <th>Tipo</th>
                                <th>Num Doc.</th>
                                <th>Nacimiento</th>
                                <th>Edad</th>
                                <th>Banco</th>
                                <th>Cta. Cte.</th>
                                <th>CCI</th>
                                <th>Sueldo mensual</th>
                                <th>Sueldo Diario</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th class="text-center">#</th>
                                <th>Aciones</th>
                                <th>Nombres</th>
                                <th>Cargo</th>
                                <th>Sueldo</th>
                                <th>Telefono</th>
                                <th>Fecha Nac. / Edad</th>
                                <th>Cuenta bancaria</th>
                                <th>Estado</th>
                                <th>Nombres</th>
                                <th>Tipo</th>
                                <th>Num Doc.</th>
                                <th>Nacimiento</th>
                                <th>Edad</th>
                                <th>Banco</th>
                                <th>Cta. Cte.</th>
                                <th>CCI</th>
                                <th>Sueldo mensual</th>
                                <th>Sueldo Diario</th>
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

                <!-- Modal agregar trabajador -->
                <div class="modal fade" id="modal-agregar-trabajador">
                  <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Agregar trabajador</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-trabajador" name="form-trabajador" method="POST">
                          <div class="card-body">

                            <div class="row" id="cargando-1-fomulario">
                              <!-- id trabajador -->
                              <input type="hidden" name="idtrabajador" id="idtrabajador" />
                              <!-- Tipo de documento -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="tipo_documento">Tipo de documento</label>
                                  <select name="tipo_documento" id="tipo_documento" class="form-control" placeholder="Tipo de documento">
                                    <option selected value="DNI">DNI</option>
                                    <option value="RUC">RUC</option>
                                    <option value="CEDULA">CEDULA</option>
                                    <option value="OTRO">OTRO</option>
                                  </select>
                                </div>
                              </div>

                              <!-- N° de documento -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="num_documento">N° de documento</label>
                                  <div class="input-group">
                                    <input type="number" name="num_documento" class="form-control" id="num_documento" placeholder="N° de documento" />
                                    <div class="input-group-append" data-toggle="tooltip" data-original-title="Buscar Reniec/SUNAT" onclick="buscar_sunat_reniec('');">
                                      <span class="input-group-text" style="cursor: pointer;">
                                        <i class="fas fa-search text-primary" id="search"></i>
                                        <i class="fa fa-spinner fa-pulse fa-fw fa-lg text-primary" id="charge" style="display: none;"></i>
                                      </span>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <!-- Nombre -->
                              <div class="col-12 col-sm-12 col-md-12 col-lg-4">
                                <div class="form-group">
                                  <label for="nombre">Nombre y Apellidos/Razon Social</label>
                                  <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombres y apellidos" />
                                </div>
                              </div>
                              <!-- Swichs permanente -->
                              <!-- <div class="col-4 col-sm-5 col-md-6 col-lg-2">
                                  <label for="socio" class="d-none show-min-width-576px">Socio <smclass="text-danger sino">(NO)</small> </label>
                                  <div class="switch-toggle">
                                    <input type="checkbox" id="socio" >
                                    <label for="socio" onclick="habilitando_socio();" onchange="habilitando_socio();"></label>
                                  </div>
                                  <input type="hidden" name="input_socio" id="input_socio" value="0"  >
                              </div> -->

                              <!-- Correo electronico -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="email">Correo electrónico</label>
                                  <input type="email" name="email" class="form-control" id="email" placeholder="Correo electrónico" onkeyup="convert_minuscula(this);" />
                                </div>
                              </div>

                              <!-- Telefono -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="telefono">Teléfono</label>
                                  <input type="text" name="telefono" id="telefono" class="form-control" data-inputmask="'mask': ['999-999-999', '+51 999 999 999']" data-mask />
                                </div>
                              </div>

                              <!-- fecha de nacimiento -->
                              <div class="col-12 col-sm-10 col-md-6 col-lg-3">
                                <div class="form-group">
                                  <label for="nacimiento">Fecha Nacimiento</label>
                                  <input
                                    type="date"
                                    class="form-control"
                                    name="nacimiento"
                                    id="nacimiento"
                                    placeholder="Fecha de Nacimiento"
                                    onclick="calcular_edad('#nacimiento', '#edad', '.edad');"
                                    onchange="calcular_edad('#nacimiento', '#edad', '.edad');"
                                  />
                                  <input type="hidden" name="edad" id="edad" />
                                </div>
                              </div>

                              <!-- edad -->
                              <div class="col-12 col-sm-2 col-md-6 col-lg-1">
                                <div class="form-group">
                                  <label for="edad">Edad</label>
                                  <p class="edad" style="border: 1px solid #ced4da; border-radius: 4px; padding: 5px;">0 años.</p>
                                </div>
                              </div>

                              <!-- banco -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="banco">Banco</label>
                                  <select name="banco" id="banco" class="form-control select2 banco" style="width: 100%;" onchange="formato_banco();">
                                    <!-- Aqui listamos los bancos -->
                                  </select>
                                </div>
                              </div>

                              <!-- Cuenta bancaria -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="cta_bancaria" class="chargue-format-1">Cuenta Bancaria</label>
                                  <input type="text" name="cta_bancaria" class="form-control" id="cta_bancaria" placeholder="Cuenta Bancaria" data-inputmask="" data-mask />
                                </div>
                              </div>

                              <!-- CCI -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="cci" class="chargue-format-2">CCI</label>
                                  <input type="text" name="cci" class="form-control" id="cci" placeholder="CCI" data-inputmask="" data-mask />
                                </div>
                              </div>

                              <!-- Titular de la cuenta -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="titular_cuenta">Titular de la cuenta</label>
                                  <input type="text" name="titular_cuenta" class="form-control" id="titular_cuenta" placeholder="Titular de la cuenta" />
                                </div>
                              </div>

                              <!-- Ruc -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="ruc">Ruc</label>
                                  <input type="number" name="ruc" class="form-control" id="ruc" placeholder="Ingrese número de ruc" />
                                </div>
                              </div>
                              <!-- cargo_trabajador  -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="cargo_trabajador">Cargo </label>
                                  <select name="cargo_trabajador" id="cargo_trabajador" class="form-control select2 cargo_trabajador" style="width: 100%;">
                                    <!-- Aqui listamos los cargo_trabajador -->
                                  </select>
                                </div>
                              </div>

                              <!-- Sueldo(Mensual) -->
                              <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                                <div class="form-group">
                                  <label for="sueldo_mensual">Sueldo(Mensual)</label>
                                  <input type="number" step="any" name="sueldo_mensual" class="form-control" id="sueldo_mensual" onclick="sueld_mensual();" onkeyup="sueld_mensual();" />
                                </div>
                              </div>

                              <!-- Sueldo(Diario) -->
                              <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                                <div class="form-group">
                                  <label for="sueldo_diario">Sueldo(Diario)</label>
                                  <input type="number" step="any" name="sueldo_diario" class="form-control" id="sueldo_diario" readonly />
                                </div>
                              </div>

                              <!-- Direccion -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-8">
                                <div class="form-group">
                                  <label for="direccion">Dirección</label>
                                  <input type="text" name="direccion" class="form-control" id="direccion" placeholder="Dirección" />
                                </div>
                              </div>

                              <!-- imagen perfil -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="col-lg-12 borde-arriba-naranja mt-2 mb-2"></div>
                                <label for="foto1">Foto de perfil</label> <br />
                                <img onerror="this.src='../dist/img/default/img_defecto.png';" src="../dist/img/default/img_defecto.png" class="img-thumbnail" id="foto1_i" style="cursor: pointer !important;" width="auto" />
                                <input style="display: none;" type="file" name="foto1" id="foto1" accept="image/*" />
                                <input type="hidden" name="foto1_actual" id="foto1_actual" />
                                <div class="text-center" id="foto1_nombre"><!-- aqui va el nombre de la FOTO --></div>
                              </div>

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
                          <button type="submit" style="display: none;" id="submit-form-trabajador">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" onclick="limpiar_form_trabajador();" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!--Modal ver trabajador-->
                <div class="modal fade" id="modal-ver-trabajador">
                  <div class="modal-dialog modal-dialog-scrollable modal-xm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Datos trabajador</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <div id="datostrabajador" class="class-style">
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
        <script type="text/javascript" src="scripts/trabajador.js"></script>

        <script> $(function () {  $('[data-toggle="tooltip"]').tooltip();  }); </script>
        
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
