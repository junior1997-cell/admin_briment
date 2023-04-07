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
        <title>Usuarios | Admin Briment</title>

        <?php $title = "Usuarios"; require 'head.php'; ?>

        <!-- CSS  switch persona -->
        <link rel="stylesheet" href="../dist/css/switch_persona.css" />        
      </head>
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed">
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
                      <h1>Usuarios</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="usuario.php">Home</a></li>
                        <li class="breadcrumb-item active">Usuarios</li>
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
                          <h3 class="card-title btn-regresar" style="display: none;">
                            <button type="button" class="btn bg-gradient-warning" onclick="limpiar_form_usuario(); show_hide_form(1);"><i class="fas fa-arrow-left"></i> Regresar</button>                            
                            <b class="trabajador-name"></b>
                          </h3>
                          <h3 class="card-title btn-agregar">
                            <button type="button" class="btn bg-gradient-primary" onclick="permisos(); limpiar_form_usuario(); show_hide_form(2);"><i class="fas fa-user-plus"></i> Agregar</button>
                            Usuarios que administran el sistema  
                          </h3>                
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                          <div id="mostrar-tabla">
                            <table id="tabla-usuarios" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Aciones</th>
                                  <th>Nombres</th>
                                  <th>Sucursal</th>
                                  <th>Telefono</th>
                                  <th>Usuario</th>
                                  <th>Cargo</th>
                                  <th>Ultm. Sesion</th>
                                  <th>Estado</th>
                                </tr>
                              </thead>
                              <tbody>                         
                                
                              </tbody>
                              <tfoot>
                                <tr>
                                  <th>#</th>
                                  <th>Aciones</th>
                                  <th>Nombres</th>
                                  <th>Sucursal</th>
                                  <th>Telefono</th>
                                  <th>Usuario</th>
                                  <th>Cargo</th>
                                  <th>Ultm. Sesion</th>
                                  <th>Estado</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                          <div id="mostrar-form" style="display: none;">                            
                             
                            <form id="form-usuario" name="form-usuario"  method="POST" >                      
                              <div class="card-body">
                                <div class="row" id="cargando-1-fomulario">
                                  <!-- id usuario -->
                                  <input type="hidden" name="idusuario" id="idusuario" />

                                  <!-- Trabajador -->
                                  <div class="col-12 col-sm-9 col-md-9 col-lg-7 col-xl-7">
                                    <div class="form-group">
                                      <label for="trabajador" id="trabajador_c">Trabajador <sup class="text-danger">*</sup></label>                               
                                      <select name="trabajador" id="trabajador" class="form-control select2" style="width: 100%;"  onchange="cargo_trabajador();" > </select>
                                      <input type="hidden" name="trabajador_old" id="trabajador_old">
                                    </div>                                                        
                                  </div>

                                  <!-- adduser --> 
                                  <div class="col-12 col-sm-3 col-md-3 col-lg-1 col-xl-1">
                                    <div class="form-group">
                                      <label class="text-white d-none show-min-width-576px">.</label> 
                                      <label class="d-none show-max-width-576px" >Nuevo Trabajador</label>
                                      <a data-toggle="modal" href="#modal-agregar-trabajador" >
                                        <button type="button" class="btn btn-success btn-block" data-toggle="tooltip" data-original-title="Agregar nuevo Trabajador" onclick="limpiar_form_trabajador();">
                                          <i class="fa fa-user-plus" aria-hidden="true"></i>
                                        </button>
                                      </a>
                                    </div>
                                  </div>

                                  <!-- cargo -->
                                  <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                      <label for="cargo" >Cargo <span class="charge-cargo"> </span></label>    
                                      <span class="form-control cursor-not-allowed" id="cargo" ></span>
                                    </div>                                                     
                                  </div>

                                  <!-- Login -->
                                  <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                      <label for="login">Login <small class="text-danger">(Dato para ingresar al sistema)</small></label>
                                      <input type="text" name="login" class="form-control" id="login" placeholder="Login" autocomplete="off" onkeyup="convert_minuscula(this);" onchange="convert_minuscula(this);">
                                    </div>
                                  </div>

                                  <!-- Contraseña -->
                                  <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                      <label for="password">Contraseña <small class="text-danger">(por defecto "123456")</small></label>
                                      <div class="input-group">
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña" autocomplete="off" />
                                        <div class="input-group-append cursor-pointer" data-toggle="tooltip" data-original-title="Ver contraseña" onclick="ver_password(this);">
                                          <span class="input-group-text" id="icon-view-password">
                                            <i class="fa-solid fa-eye text-primary"></i>
                                          </span>
                                        </div>
                                      </div>
                                      <input type="hidden" name="password-old"   id="password-old"  >
                                    </div>
                                  </div>  

                                  <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                      <label for="confirm_password">Repetir Contraseña</label>
                                      <input type="password" name="confirm_password" id="confirm_password" class="form-control"  placeholder="Repetir Contraseña" autocomplete="off">                                      
                                    </div>
                                  </div>  

                                  <!-- permisos -->
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="mb-0">
                                      <label class="ml-1" for="permisos">Permisos</label>                               
                                      <div id="permisos">
                                        <i class="fas fa-spinner fa-pulse fa-2x"></i>
                                      </div>
                                    </div>
                                  </div>

                                  <!-- Progress -->
                                  <div class="col-md-12">
                                    <div class="form-group">
                                      <div class="progress" id="div_barra_progress_usuario" style="display: none !important;">
                                        <div id="barra_progress_usuario" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
                              <div class="card-footer justify-content-between">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="show_hide_form(1);"> <i class="fas fa-arrow-left"></i> Close</button>
                                <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                              </div>                      
                            </form>
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

                <!-- MODAL - AGREGAR TRABAJADOR - chargue 3 -->
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

                            <div class="row" id="cargando-3-fomulario">
                              <!-- id persona -->
                              <input type="hidden" name="idpersona_per" id="idpersona_per" />                                

                              <!-- tipo persona  -->
                              <input type="hidden" name="id_tipo_persona_per" id="id_tipo_persona_per" value="4" /> 

                              <!-- Tipo de documento -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-2">
                                <div class="form-group">
                                  <label for="tipo_documento_per">Tipo Doc.</label>
                                  <select name="tipo_documento_per" id="tipo_documento_per" class="form-control" placeholder="Tipo de documento">
                                    <option selected value="DNI">DNI</option>
                                    <option value="RUC">RUC</option>
                                    <option value="CEDULA">CEDULA</option>
                                    <option value="OTRO">OTRO</option>
                                  </select>
                                </div>
                              </div>
                              
                              <!-- N° de documento -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                                <div class="form-group">
                                  <label for="num_documento_per">N° de documento</label>
                                  <div class="input-group">
                                    <input type="number" name="num_documento_per" class="form-control" id="num_documento_per" placeholder="N° de documento" />
                                    <div class="input-group-append" data-toggle="tooltip" data-original-title="Buscar Reniec/SUNAT" onclick="buscar_sunat_reniec('_per');">
                                      <span class="input-group-text" style="cursor: pointer;">
                                        <i class="fas fa-search text-primary" id="search_per"></i>
                                        <i class="fa fa-spinner fa-pulse fa-fw fa-lg text-primary" id="charge_per" style="display: none;"></i>
                                      </span>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <!-- Nombre -->
                              <div class="col-12 col-sm-12 col-md-12 col-lg-7">
                                <div class="form-group">
                                  <label for="nombre_per">Nombres/Razon Social</label>
                                  <input type="text" name="nombre_per" class="form-control" id="nombre_per" placeholder="Nombres y apellidos" />
                                </div>
                              </div>
                              
                              <!-- Telefono -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-2">
                                <div class="form-group">
                                  <label for="telefono_per">Teléfono</label>
                                  <input type="text" name="telefono_per" id="telefono_per" class="form-control" data-inputmask="'mask': ['999-999-999', '+51 999 999 999']" data-mask />
                                </div>
                              </div>

                              <!-- Correo electronico -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="email_per">Correo electrónico</label>
                                  <input type="email" name="email_per" class="form-control" id="email_per" placeholder="Correo electrónico" onkeyup="convert_minuscula(this);" />
                                </div>
                              </div>

                              <!-- fecha de nacimiento -->
                              <div class="col-12 col-sm-10 col-md-6 col-lg-3">
                                <div class="form-group">
                                  <label for="nacimiento_per">Fecha Nacimiento</label>
                                  <input type="date" class="form-control" name="nacimiento_per" id="nacimiento_per" placeholder="Fecha de Nacimiento"
                                    onclick="calcular_edad('#nacimiento_per', '#edad_per', '.edad_per');" onchange="calcular_edad('#nacimiento_per', '#edad_per', '.edad_per');" />
                                  <input type="hidden" name="edad_per" id="edad_per" />
                                </div>
                              </div>

                              <!-- edad -->
                              <div class="col-12 col-sm-2 col-md-6 col-lg-3">
                                <div class="form-group">
                                  <label for="edad_per">Edad</label>
                                  <p class="edad_per" style="border: 1px solid #ced4da; border-radius: 4px; padding: 5px;">0 años.</p>
                                </div>
                              </div> 
                              
                              <!-- cargo_trabajador  -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-6 campos_trabajador">
                                <div class="form-group">
                                  <label for="cargo_trabajador_per">Cargo </label>
                                  <select name="cargo_trabajador_per" id="cargo_trabajador_per" class="form-control select2" style="width: 100%;">
                                    <!-- Aqui listamos los cargo_trabajador -->
                                  </select>
                                </div>
                              </div>

                              <!-- Sueldo(Mensual) -->
                              <div class="col-12 col-sm-6 col-md-3 col-lg-3 campos_trabajador">
                                <div class="form-group">
                                  <label for="sueldo_mensual_per">Sueldo(Mensual)</label>
                                  <input type="number" step="any" name="sueldo_mensual_per" id="sueldo_mensual_per" class="form-control"  onclick="sueld_mensual(this); this.select();" onkeyup="sueld_mensual(this);" />
                                </div>
                              </div>

                              <!-- Sueldo(Diario) -->
                              <div class="col-12 col-sm-6 col-md-3 col-lg-3 campos_trabajador">
                                <div class="form-group">
                                  <label for="sueldo_diario_per">Sueldo(Diario)</label>
                                  <input type="number" step="any" name="sueldo_diario_per" id="sueldo_diario_per" class="form-control cursor-not-allowed"  readonly />
                                </div>
                              </div>

                              <!-- Direccion -->
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12 classdirecc">
                                <div class="form-group">
                                  <label for="direccion_per">Dirección</label>                                  
                                  <textarea name="direccion_per" id="direccion_per" class="form-control" placeholder="Dirección" rows="2"></textarea>
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
                              <div class="col-md-12" id="barra_progress_trabajador_div" style="display: none !important;">
                                <div class="form-group">
                                  <div class="progress" >
                                    <div id="barra_progress_trabajador" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
                          <button type="submit" style="display: none;" id="submit-form-trabajador">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" onclick="limpiar_form_trabajador();" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_trabajador">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - SUCURSAL - chargue 5 -->
                <div class="modal fade" id="modal-agregar-sucursal">
                  <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title titulo-modal-sucursal">Sucursal</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <button type="button" class="btn bg-gradient-primary btn-agregar-sucursal mb-3" onclick="limpiar_form_sucursal(); show_hide_form_sucursal(2);"><i class="fas fa-user-plus"></i> Agregar</button>
                        <button type="button" class="btn bg-gradient-warning btn-regresar-sucursal mb-2" onclick="limpiar_form_sucursal(); show_hide_form_sucursal(1);" style="display: none !important;"><i class="fas fa-arrow-left"></i> Regresar</button>                            

                        <div class="div_tabla_sucursal">
                          <table id="tabla-sucursal" class="table table-bordered table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th class="">#</th>
                                <th class="">Aciones</th>
                                <th>Nombre</th>
                                <th>Dirección</th>
                                <th>Estado</th>
                              </tr>
                            </thead>
                            <tbody>                         
                              
                            </tbody>
                            <tfoot>
                              <tr>
                                <th>#</th>
                                <th>Aciones</th>
                                <th>Nombre</th>
                                <th>Dirección</th>
                                <th>Estado</th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                        <!-- form start -->
                        <form id="form-sucursal" name="form-sucursal" method="POST" style="display: none;">
                          <div class="card-body">

                            <div class="row" id="cargando-5-fomulario">
                              <!-- id persona -->
                              <input type="hidden" name="idpersona_sucursal" id="idpersona_sucursal" />  
                              <input type="hidden" name="idpersona_suc" id="idpersona_suc" />                            

                              <!-- Sucursal -->
                              <div class="col-12 col-sm-9 col-md-9 col-lg-12 col-xl-12">
                                <div class="form-group">
                                  <label for="sucursal" >Sucursal <sup class="text-danger">*</sup></label>                               
                                  <select name="sucursal" id="sucursal" class="form-control select2" style="width: 100%;" > </select>                                      
                                </div>                                                        
                              </div>              

                              <!-- Progress -->
                              <div class="col-md-12" id="barra_progress_sucursal_div" style="display: none !important;">
                                <div class="form-group">
                                  <div class="progress" >
                                    <div id="barra_progress_sucursal" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row" id="cargando-6-fomulario" style="display: none;" >
                              <div class="col-lg-12 text-center">
                                <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                                <h4>Cargando...</h4>
                              </div>
                            </div>
                                  
                          </div>
                          <!-- /.card-body -->
                          <button type="submit" style="display: none;" id="submit-form-sucursal">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" onclick="limpiar_form_sucursal();" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_sucursal">Guardar Cambios</button>
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

        <script type="text/javascript" src="scripts/usuario.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }) </script>

      </body>
    </html> 
    
    <?php  
  }
  ob_end_flush();

?>
