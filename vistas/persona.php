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
        <title>Personas | Admin Briment</title>

        <?php $title = "Personas"; require 'head.php'; ?>
        <link rel="stylesheet" href="../dist/css/switch_persona.css">

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
                      <h1><i class="nav-icon fas fa-users"></i> Personas</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="persona.php">Home</a></li>
                        <li class="breadcrumb-item active">Personas</li>
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
                        <!-- /.card-header -->
                        <div class="card-body px-1 py-1">
                          <div class="row">                              
                            <div class=" col-12 col-sm-12">
                              <div class="card card-success card-outline card-outline-tabs mb-0">
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
                                    <div class="tab-pane fade show active" id="tabs-for-persona" role="tabpanel" aria-labelledby="tabs-for-persona-tab">
                                      <div class="row">
                                        <div class="col-12 mb-2">                                          
                                          <button type="button" class="btn bg-gradient-primary mb-2 btn-agregar-persona" data-toggle="modal" data-target="#modal-agregar-persona" onclick="limpiar_form_persona();"><i class="fas fa-user-plus"></i> Agregar</button>                                                 
                                        </div>                                        
                                        <div class="col-12">
                                          <table id="tabla-persona" class="table table-bordered table-striped display" style="width: 100% !important;">
                                            <thead> 
                                              <tr>                                              
                                                <th class="text-center">#</th>
                                                <th class=""><i class="fa fa-cogs" aria-hidden="true"></i></th>
                                                <th class="">Nombres</th>
                                                <th>Dirección</th>
                                                <th>Telefono</th>
                                                <th>Sueldo</th>

                                                <th>Estado</th>
                                                <th>Nombres</th>
                                                <th>Tipo Doc.</th>
                                                <th>Num. Doc</th>

                                              </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                              <tr>
                                                <th class="text-center">#</th>
                                                <th class=""><i class="fa fa-cogs" aria-hidden="true"></i></th>
                                                <th class="">Nombres</th>
                                                <th>Dirección</th>
                                                <th>Telefono</th>
                                                <th>Sueldo</th>

                                                <th>Estado</th>
                                                <th>Nombres</th>
                                                <th>Tipo Doc.</th>
                                                <th>Num. Doc.</th>

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

                <!-- Modal agregar persona -->
                <div class="modal fade" id="modal-agregar-persona">
                  <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title titulo-modal">Agregar persona</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-persona" name="form-persona" method="POST">
                          <div class="card-body">

                            <div class="row" id="cargando-1-fomulario">
                              <!-- id persona -->
                              <input type="hidden" name="idpersona" id="idpersona" />
                              <!-- tipo persona  -->
                              <input type="hidden" name="id_tipo_persona" id="id_tipo_persona" />
                              <!-- Tipo de documento -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-2 cp_tipo_doc">
                                <div class="form-group">
                                  <label for="tipo_documento">Tipo Doc.</label>
                                  <select name="tipo_documento" id="tipo_documento" class="form-control" placeholder="Tipo de documento">
                                    <option selected value="DNI">DNI</option>
                                    <option value="RUC">RUC</option>
                                    <option value="CEDULA">CEDULA</option>
                                    <option value="OTRO">OTRO</option>
                                  </select>
                                </div>
                              </div>
                              
                              <!-- N° de documento -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-3 cp_num_doc">
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
                              <div class="col-12 col-sm-12 col-md-12 col-lg-7 cp_nombre">
                                <div class="form-group">
                                  <label for="nombre">Nombres/Razon Social</label>
                                  <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombres y apellidos" />
                                </div>
                              </div>
                              <!-- Telefono -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-2 cp_telefono">
                                <div class="form-group">
                                  <label for="telefono">Teléfono</label>
                                  <input type="text" name="telefono" id="telefono" class="form-control" data-inputmask="'mask': ['999-999-999', '+51 999 999 999']" data-mask />
                                </div>
                              </div>

                              <!-- Correo electronico -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4 cp_email">
                                <div class="form-group">
                                  <label for="email">Correo electrónico</label>
                                  <input type="email" name="email" class="form-control" id="email" placeholder="Correo electrónico" onkeyup="convert_minuscula(this);" />
                                </div>
                              </div>

                              <!-- fecha de nacimiento -->
                              <div class="col-12 col-sm-10 col-md-6 col-lg-3 cp_f_nacimiento">
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
                              <div class="col-12 col-sm-2 col-md-6 col-lg-3 cp_edad">
                                <div class="form-group">
                                  <label for="edad">Edad</label>
                                  <p class="edad" style="border: 1px solid #ced4da; border-radius: 4px; padding: 5px;">0 años.</p>
                                </div>
                              </div>
                              
                              <!-- cargo_trabajador  -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-6 cp_cargo">
                                <div class="form-group">
                                  <label for="cargo_trabajador">Cargo </label>
                                  <select name="cargo_trabajador" id="cargo_trabajador" class="form-control select2 cargo_trabajador" style="width: 100%;">
                                    <!-- Aqui listamos los cargo_trabajador -->
                                  </select>
                                </div>
                              </div>

                              <!-- Sueldo(Mensual) -->
                              <div class="col-12 col-sm-6 col-md-3 col-lg-3 cp_s_mensual">
                                <div class="form-group">
                                  <label for="sueldo_mensual">Sueldo(Mensual)</label>
                                  <input type="number" step="any" name="sueldo_mensual" class="form-control" id="sueldo_mensual" onclick="sueld_mensual();" onkeyup="sueld_mensual();" />
                                </div>
                              </div>

                              <!-- Sueldo(Diario) -->
                              <div class="col-12 col-sm-6 col-md-3 col-lg-3 cp_s_diario">
                                <div class="form-group">
                                  <label for="sueldo_diario">Sueldo(Diario)</label>
                                  <input type="number" step="any" name="sueldo_diario" class="form-control" id="sueldo_diario" readonly />
                                </div>
                              </div>                              

                              <!-- Direccion -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-12 cp_direccion">
                                <div class="form-group">
                                  <label for="direccion">Dirección</label>                                  
                                  <textarea name="direccion" id="direccion" class="form-control" placeholder="Dirección" rows="2"></textarea>
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
                              <div class="col-md-12 m-t-20px" id="barra_progress_persona_div" style="display: none !important;">
                                <div class="form-group">
                                  <div class="progress" >
                                    <div id="barra_progress_persona" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
                          <button type="submit" style="display: none;" id="submit-form-persona">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" onclick="limpiar_form_persona();" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!--Modal ver persona-->
                <div class="modal fade" id="modal-ver-persona">
                  <div class="modal-dialog modal-dialog-scrollable modal-xm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Datos persona</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <div id="datospersona" class="class-style">
                          <!-- vemos los datos del persona -->
                        </div>
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

                <!--Modal ver persona-->
                <div class="modal fade" id="modal-cambiar-tipo-persona">
                  <div class="modal-dialog modal-dialog-scrollable modal-xm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title titulo-modal-tp">Datos persona</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-cambiar-tipo-persona" name="form-cambiar-tipo-persona" method="POST">
                          <div class="card-body">

                            <div class="row" id="cargando-3-fomulario">
                              <!-- id persona -->
                              <input type="hidden" name="idpersona_tp" id="idpersona_tp" />

                              <!-- Tipo de documento -->
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                  <label for="tipo_persona_cambio">Tipo persona.</label>
                                  <select name="tipo_persona_cambio" id="tipo_persona_cambio" class="form-control" placeholder="Tipo de persona">
                                    
                                  </select>
                                </div>
                              </div> 

                              <!-- Progress -->
                              <div class="col-md-12" id="barra_progress_tp_div">
                                <div class="form-group">
                                  <div class="progress" style="display: none !important;">
                                    <div id="barra_progress_tp" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row" id="cargando-4-fomulario" style="display: none;" >
                              <div class="col-lg-12 text-center">
                                <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                                <h4>Cargando...</h4>
                              </div>
                            </div>
                                  
                          </div>
                          <!-- /.card-body -->
                          <button type="submit" style="display: none;" id="submit-form-cambiar-tipo-persona">Submit</button>
                        </form>
                      </div>

                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_tipo_persona">Guardar Cambios</button>
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
        <script type="text/javascript" src="scripts/persona.js"></script>

        <script> $(function () {  $('[data-toggle="tooltip"]').tooltip();  }); </script>
        
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
