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
        <title>Otros Ingresos | Admin Briment</title>
        
        <?php $title = "Otros Ingresos"; require 'head.php'; ?>
          
      </head>
      <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['otro_ingreso']==1){
            //require 'enmantenimiento.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper" >
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1>Otros Ingresos</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="otro_ingreso.php">Home</a></li>
                        <li class="breadcrumb-item active">Otros Ingresos</li>
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
                            <button type="button" class="btn bg-gradient-warning" onclick="limpiar_form(); show_hide_form(1);"><i class="fas fa-arrow-left"></i> Regresar</button>                            
                          </h3>
                          <h3 class="card-title btn-agregar">
                            <button type="button" class="btn bg-gradient-primary" onclick="limpiar_form(); show_hide_form(2);"><i class="fas fa-plus-circle"></i> Agregar</button>
                            Administra de manera eficiente otros ingresos.
                          </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                          <div id="mostrar-tabla">
                            <table id="tabla-otro-ingreso" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th class="">Acciones</th>
                                  <th class="">Persona</th>
                                  <th data-toggle="tooltip" data-original-title="Forma Pago">Forma P.</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Tipo Comp.</th>
                                  <th>Fecha</th>
                                  <th>Subtotal</th>
                                  <th>IGV</th>
                                  <th>Total</th>
                                  <th>Descripción</th>
                                  <th data-toggle="tooltip" data-original-title="Comprobante">Comp.</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th class="">Acciones</th>
                                  <th class="">Persona</th>
                                  <th data-toggle="tooltip" data-original-title="Forma Pago">Forma P.</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Tipo Comp.</th>
                                  <th>Fecha</th>
                                  <th class="text-nowrap px-2">0.00</th>
                                  <th class="text-nowrap px-2">0.00</th>
                                  <th class="text-nowrap px-2">0.00</th>
                                  <th>Descripción</th>
                                  <th data-toggle="tooltip" data-original-title="Comprobante">Comp.</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                          <div id="mostrar-form" style="display: none;">
                            
                            <!-- form start -->
                            <form id="form-otro-ingreso" name="form-otro-ingreso" method="POST">
                              <div class="card-body">
                                <div class="row" id="cargando-1-fomulario">
                                  <!-- id hospedaje -->
                                  <input type="hidden" name="idotro_ingreso" id="idotro_ingreso" />

                                  <!--Proveedor-->                                   
                                  <div class="col-sm-12 col-md-9 col-lg-7 col-xl-7">
                                    <div class="form-group">
                                      <label for="idpersona">Persona <sup class="text-danger">*</sup></label>
                                      <!-- <div class="input-group"> -->
                                        <select name="idpersona" id="idpersona" class="form-control select2" placeholder="Seleccinar un proveedor"> </select>
                                    </div>
                                  </div>  

                                  <!-- adduser -->
                                  <div class="col-sm-12 col-md-3 col-lg-1 col-xl-1">
                                    <div class="form-group">
                                      <label class="text-white d-none show-min-width-576px">.</label> 
                                      <label class="d-none show-max-width-576px" >Nuevo proveedor</label>
                                      <a data-toggle="modal" href="#modal-agregar-persona" >
                                        <button type="button" class="btn btn-success btn-block" data-toggle="tooltip" data-original-title="Agregar nuevo Provedor" onclick="limpiar_persona();">
                                          <i class="fa fa-user-plus" aria-hidden="true"></i>
                                        </button>
                                      </a>
                                    </div>
                                  </div>  
                                  
                                  <!--forma pago-->
                                  <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                      <label for="forma_pago">Forma Pago</label>
                                      <select name="forma_pago" id="forma_pago" class="form-control select2" style="width: 100%;">
                                        <option value="Transferencia">Transferencia</option>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Crédito">Crédito</option>
                                      </select>
                                    </div>
                                  </div>

                                  <!-- Tipo de comprobante -->
                                  <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4" id="content-t-comprob">
                                    <div class="form-group">
                                      <label for="tipo_comprobante">Tipo Comprobante</label>
                                      <select name="tipo_comprobante" id="tipo_comprobante" class="form-control select2" onchange="comprob_factura(); validando_igv();" onkeyup="comprob_factura();" placeholder="Seleccinar un tipo de comprobante">
                                        <option value="Ninguno">Ninguno</option>
                                        <option value="Boleta">Boleta</option>
                                        <option value="Factura">Factura</option>
                                        <option value="Nota de venta">Nota de venta</option>
                                      </select>
                                    </div>
                                  </div>                                  

                                  <!-- Código-->
                                  <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                      <label class="nro_comprobante" for="nro_comprobante">Núm. comprobante </label>
                                      <input type="text" name="nro_comprobante" id="nro_comprobante" class="form-control" placeholder="Código" />
                                    </div>
                                  </div>                                  

                                  <!-- Fecha 1  -->
                                  <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                      <label for="fecha_i">Fecha Emisión</label>
                                      <input type="date" name="fecha_i" class="form-control" id="fecha_i" />
                                    </div>
                                  </div>

                                  <!-- Sub total -->
                                  <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                      <label for="subtotal">Sub total</label>
                                      <input class="form-control" type="number" id="subtotal" name="subtotal" placeholder="Sub total" readonly />                                   
                                    </div>
                                  </div>

                                  <!-- IGV -->
                                  <div class="col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                    <div class="form-group">
                                      <label for="igv">IGV</label>
                                      <input class="form-control igv" type="number" id="igv" name="igv" placeholder="IGV" readonly />
                                    </div>
                                  </div>

                                  <!-- valor IGV -->
                                  <div class="col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                    <div class="form-group">
                                        <label for="val_igv" class="text-gray" style="font-size: 13px;">Valor - IGV </label>
                                        <input type="text" name="val_igv" id="val_igv" value="0.18" class="form-control" readonly onkeyup="calculandototales_fact();"> 
                                        <input class="form-control" type="hidden"  id="tipo_gravada" name="tipo_gravada"/>
                                    </div>
                                  </div>
                                  
                                  <!--Precio Parcial-->
                                  <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                      <label for="marca">Monto total </label>
                                      <input type="number" name="precio_parcial" id="precio_parcial" class="form-control" onchange="comprob_factura();" onkeyup="comprob_factura();" placeholder="Precio Parcial" />                                  
                                    </div>
                                  </div>

                                  <!--Descripcion-->
                                  <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="form-group ">
                                      <label for="descripcion_pago">Descripción</label> <br />
                                      <textarea name="descripcion" id="descripcion" class="form-control" rows="2"></textarea>
                                    </div>
                                  </div>
                                  <!-- Factura -->
                                  <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4" >   
                                    <!-- linea divisoria -->
                                    <div class="borde-arriba-naranja mt-4"> </div>                            
                                    <div class="row text-center">
                                      <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                        <label for="cip" class="control-label" > Comprobante </label>
                                      </div>
                                      <div class="col-6 col-md-6 text-center">
                                        <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i"> <i class="fas fa-upload"></i> Subir.</button>
                                        <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                        <input style="display: none;" id="doc1" type="file" name="doc1" accept="application/pdf, image/*" class="docpdf" /> 
                                      </div>
                                      <div class="col-6 col-md-6 text-center">
                                        <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(1, 'otro_ingreso', 'comprobante');">
                                        <i class="fas fa-redo"></i> Recargar.
                                        </button>
                                      </div>
                                    </div>                              
                                    <div id="doc1_ver" class="text-center mt-4">
                                      <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >
                                    </div>
                                    <div class="text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>
                                  </div>

                                  <!-- Progress -->
                                  <div class="col-md-12 m-t-20px" id="barra_progress_otro_ingreso_div" style="display: none;">
                                    <div class="form-group">
                                      <div class="progress" >
                                        <div id="barra_progress_otro_ingreso" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
                              <div class=" justify-content-between">
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
                
                <!-- Modal agregar proveedores -->
                <div class="modal fade" id="modal-agregar-persona">
                  <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Agregar Persona</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
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
                                        <div class="col-12">
                                          <!-- form start -->
                                          <form id="form-persona" name="form-persona" method="POST">
                                            <div class="card-body">

                                              <div class="row" id="cargando-1-fomulario">
                                                <!-- id persona -->
                                                <input type="hidden" name="idpersona_per" id="idpersona_per" />
                                                <!-- tipo persona  -->
                                                <input type="hidden" name="id_tipo_persona_per" id="id_tipo_persona_per" value="2" />
                                                <!-- Tipo de documento -->
                                                <div class="col-12 col-sm-6 col-md-6 col-lg-2 cp_tipo_doc">
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
                                                <div class="col-12 col-sm-6 col-md-6 col-lg-3 cp_num_doc">
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
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-7 cp_nombre">
                                                  <div class="form-group">
                                                    <label for="nombre_per">Nombres/Razon Social</label>
                                                    <input type="text" name="nombre_per" id="nombre_per" class="form-control" placeholder="Nombres y apellidos" />
                                                  </div>
                                                </div>
                                                <!-- Telefono -->
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-2 cp_telefono">
                                                  <div class="form-group">
                                                    <label for="telefono_per">Teléfono</label>
                                                    <input type="text" name="telefono_per" id="telefono_per" class="form-control" data-inputmask="'mask': ['999-999-999', '+51 999 999 999']" data-mask />
                                                  </div>
                                                </div>

                                                <!-- Correo electronico -->
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-4 cp_email">
                                                  <div class="form-group">
                                                    <label for="email_per">Correo electrónico</label>
                                                    <input type="email" name="email_per" class="form-control" id="email_per" placeholder="Correo electrónico" onkeyup="convert_minuscula(this);" />
                                                  </div>
                                                </div>

                                                <!-- fecha de nacimiento -->
                                                <div class="col-12 col-sm-10 col-md-6 col-lg-3 cp_f_nacimiento">
                                                  <div class="form-group">
                                                    <label for="nacimiento_per">Fecha Nacimiento</label>
                                                    <input
                                                      type="date"
                                                      class="form-control"
                                                      name="nacimiento_per"
                                                      id="nacimiento_per"
                                                      placeholder="Fecha de Nacimiento"
                                                      onclick="calcular_edad('#nacimiento_per', '#edad_per', '.edad_per');"
                                                      onchange="calcular_edad('#nacimiento_per', '#edad_per', '.edad_per');"
                                                    />
                                                    <input type="hidden" name="edad_per" id="edad_per" />
                                                  </div>
                                                </div>

                                                <!-- edad -->
                                                <div class="col-12 col-sm-2 col-md-6 col-lg-3 cp_edad">
                                                  <div class="form-group">
                                                    <label for="edad_per">Edad</label>
                                                    <p class="edad_per" style="border: 1px solid #ced4da; border-radius: 4px; padding: 5px;">0 años.</p>
                                                  </div>
                                                </div>
                                                
                                                <!-- cargo_trabajador  -->
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 cp_cargo">
                                                  <div class="form-group">
                                                    <label for="cargo_trabajador_per">Cargo </label>
                                                    <select name="cargo_trabajador_per" id="cargo_trabajador_per" class="form-control select2 cargo_trabajador" style="width: 100%;">
                                                      <!-- Aqui listamos los cargo_trabajador -->
                                                    </select>
                                                  </div>
                                                </div>

                                                <!-- Sueldo(Mensual) -->
                                                <div class="col-12 col-sm-6 col-md-3 col-lg-3 cp_s_mensual">
                                                  <div class="form-group">
                                                    <label for="sueldo_mensual_per">Sueldo(Mensual)</label>
                                                    <input type="number" step="any" name="sueldo_mensual_per" class="form-control" id="sueldo_mensual_per" onclick="sueld_mensual();" onkeyup="sueld_mensual();" />
                                                  </div>
                                                </div>

                                                <!-- Sueldo(Diario) -->
                                                <div class="col-12 col-sm-6 col-md-3 col-lg-3 cp_s_diario">
                                                  <div class="form-group">
                                                    <label for="sueldo_diario_per">Sueldo(Diario)</label>
                                                    <input type="number" step="any" name="sueldo_diario_per" class="form-control" id="sueldo_diario_per" readonly />
                                                  </div>
                                                </div>                              

                                                <!-- Direccion -->
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-12 cp_direccion">
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
                                                <div class="col-md-12 m-t-20px" id="barra_progress_persona_div" style="display: none;">
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
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_persona">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div> 

                <!--===============Modal-ver-comprobante =========-->
                <div class="modal fade" id="modal-ver-comprobante">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Otros Ingresos: <span class="nombre_comprobante text-bold"></span> </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="row">
                          <div class="col-6 col-md-6">
                            <a class="btn btn-xs btn-block btn-warning" href="#" id="iddescargar" download="" type="button"><i class="fas fa-download"></i> Descargar</a>
                          </div>
                          <div class="col-6 col-md-6">
                            <a class="btn btn-xs btn-block btn-info" href="#" id="ver_completo"  target="_blank" type="button"><i class="fas fa-expand"></i> Ver completo.</a>
                          </div>
                          <div class="col-12 col-md-12 mt-2">
                            <div id="ver_fact_pdf" width="auto"></div>
                          </div>
                        </div>                          
                      </div>
                    </div>
                  </div>
                </div>

                <!--MODAL - VER DETALLE DE OTRO INGRESO -->
                <div class="modal fade" id="modal-ver-otro-ingreso">
                  <div class="modal-dialog modal-dialog-scrollable modal-xm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Datos otros Ingresos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body"> 
                        <div id="datos_otro_ingreso" class="class-style">
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
        <script type="text/javascript" src="scripts/otro_ingreso.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
