<?php
  //Activamos el almacenamiento en el buffer
  ob_start();
  session_start();

  if (!isset($_SESSION["nombre"])){
    header("Location: index.php?file=".basename($_SERVER['PHP_SELF']));
  }else{ ?>
    <!DOCTYPE html>
    <html lang="es">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Estado Financiero | Admin JDL </title>

        <?php $title = "Estado Financiero"; require 'head.php'; ?>

      </head>
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed ">
        
        <div class="wrapper">
          <!-- Preloader -->
          <!-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../dist/svg/logo-principal.svg" alt="AdminLTELogo" width="360" />
          </div> -->
        
          <?php
            require 'nav.php';
            require 'aside.php';
            if ($_SESSION['estado_financiero']==1){
              //require 'endesarrollo.php';
              ?>           
              <!--Contenido-->
              <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                  <div class="container-fluid">
                    <div class="row mb-2">
                      <div class="col-sm-6">
                        <h1 class="m-0 nombre-trabajador">Estado Financiero</h1>
                      </div>
                      <!-- /.col -->
                      <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                          <li class="breadcrumb-item"><a href="estado_financiero.php">Home</a></li>
                          <li class="breadcrumb-item active">Estado Financiero</li>
                        </ol>
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->
                  </div>
                  <!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->
                
                <!-- Main content -->
                <section class="content">
                  <div class="container-fluid">
                    <div class="row">
                      <div class="col-12">
                        <div class="card card-primary card-outline">
                          <div class="card-header"> 

                            <!-- Editar -->
                            <h3 class="card-title mr-3 p-l-2px" id="btn-editar-ef" >
                              <button type="button" class="btn bg-gradient-orange btn-sm " onclick="show_hide_span_input_ef(2);"><i class="fas fa-pencil-alt"></i> <span class="d-none d-sm-inline-block">Editar</span> </button>
                            </h3>
                            <!-- Guardar -->
                            <h3 class="card-title mr-3 p-l-2px" id="btn-guardar-ef" style="display: none;">
                              <button type="button" class="btn bg-gradient-primary btn-guardar-asistencia btn-sm " onclick="guardar_y_editar_estado_financiero();" ><i class="far fa-save"></i> <span class="d-none d-sm-inline-block"> Guardar </span> </button>
                            </h3>

                            <h3 class="card-title mr-3 p-l-2px">
                              <button type="button" class="btn bg-gradient-gray btn-sm " onclick="html_table_to_excel('tabla_estado_financiero', 'xlsx', 'Estado Financiero Actual', 'detalle');"><i class="far fa-file-excel"></i> <span class="d-none d-sm-inline-block"> Exportar</span></button>
                            </h3>

                          </div>
                          <!-- /.card-header -->
                          <div class="card-body">
                            <div class="row">
                              <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <!-- tabla principal --> 
                                <div class="table-responsive">                                                        
                                  <table  class="table table-bordered /*table-striped*/ table-hover text-nowrap" id="tabla_estado_financiero" >
                                    <thead class="bg-info">
                                      <tr> 
                                        <th class="py-1 text-center" colspan="3">ESTADO FINANCIERO ACTUAL</th>                                                 
                                      </tr>
                                      <tr> 
                                        <th class="py-1 text-center">#</th> 
                                        <th class="py-1">DESCRIPCIÓN</th>
                                        <th class="py-1 text-center">MONTO</th>                                                          
                                      </tr>
                                    </thead>
                                    <tbody>                         
                                      <tr>
                                        <td class="py-1 text-center">1</td>
                                        <td class="py-1">CAJA</td>
                                        <td class="py-1">
                                          <div class="formato-numero-conta span_ef"><span>S/</span> <span class="caja_ef"><i class="fas fa-spinner fa-pulse"></i></span> </div> 
                                          <input type="text" id="caja_ef" class="numberIndistintoFixed hidden input_ef w-100" onkeyup="delay(function(){update_interes_y_ganancia_ef()}, 200 );" autocomplete="off" onfocus="this.select();">
                                          <input type="hidden" id="idestado_financiero" >
                                        </td> 
                                      </tr>
                                      <tr>
                                        <td class="py-1 text-center">2</td>
                                        <td class="py-1">PRESTAMOS Y CRÉDITOS (por pagar)</td> 
                                        <td class="py-1">
                                          <div class="formato-numero-conta"><span>S/</span> <span class="prestamo_y_credito_ef"><i class="fas fa-spinner fa-pulse"></i></span> </div>
                                        </td> 
                                      </tr>
                                      <tr>
                                        <td class="py-1 text-center">3</td>
                                        <td class="py-1">GASTOS ACTUALIZADOS</td>              
                                        <td class="py-1">
                                          <div class="formato-numero-conta"><span>S/</span> <span class="gastos_actuales_ef"><i class="fas fa-spinner fa-pulse"></i></span> </div>
                                        </td> 
                                      </tr>
                                      <tr>
                                        <td class="py-1 text-center">4</td>
                                        <td class="py-1">VALORIZACIONES COBRADAS (<span class="cant_cobradas"><i class="fas fa-spinner fa-pulse"></i></span>)</td>      
                                        <td class="py-1">
                                          <div class="formato-numero-conta"><span>S/</span> <span class="valorizacion_cobrada_ef"><i class="fas fa-spinner fa-pulse"></i></span></div>
                                      </td> 
                                      </tr>
                                      <tr>
                                        <td class="py-1 text-center">5</td>
                                        <td class="py-1">VALORIZACIONES POR COBRAR (<span class="cant_por_cobrar"><i class="fas fa-spinner fa-pulse"></i></span>)</td>    
                                        <td class="py-1">
                                          <div class="formato-numero-conta"><span>S/</span> <span class="valorizacion_por_cobrar_ef"><i class="fas fa-spinner fa-pulse"></i></span> </div>
                                        </td> 
                                      </tr>
                                      <tr>
                                        <td class="py-1 text-center">6</td>
                                        <td class="py-1">GARANTÍA</td>                         
                                        <td class="py-1">
                                          <div class="formato-numero-conta"><span>S/</span> <span class="garantia_ef"><i class="fas fa-spinner fa-pulse"></i></span> </div>
                                        </td > 
                                      </tr>
                                      <tr>
                                        <td class="py-1 text-center">7</td>
                                        <td class="py-1">MONTO DE OBRA</td>                    
                                        <td class="py-1">
                                          <div class="formato-numero-conta"><span>S/</span> <span class="monto_de_obra_ef"><i class="fas fa-spinner fa-pulse"></i></span> </div>
                                        </td > 
                                      </tr>

                                    </tbody>
                                    <tfoot>
                                      <tr>                                       
                                        <th class="py-1 celda-b-t-2px" colspan="2">INTERÉS PAGADO</th>
                                        <th class="py-1 celda-b-t-2px"><div class="formato-numero-conta"><span>S/</span><span class="interes_pagado"><i class="fas fa-spinner fa-pulse"></i></span></div> </th>      
                                      </tr>
                                      <tr>                                       
                                        <th class="py-1" colspan="2" rowspan="2">GANANCIA ACTUAL (SIN DESCONTAR INTERÉS POR PAGAR)</th>
                                        <th class="py-1"><div class="formato-numero-conta"><span>S/</span><span class="ganacia_actual"><i class="fas fa-spinner fa-pulse"></i></span></div></th>      
                                      </tr>
                                      <tr>                                       
                                        <th class="py-1 text-right"><span class="ganacia_actual_porcentaje"><i class="fas fa-spinner fa-pulse"></i></span></th>      
                                      </tr>
                                    </tfoot>
                                  </table> 
                                  <!-- /.table -->
                                </div>   
                                <!-- /.table-responsive -->
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
                      <div class="col-12">
                        <h2>Proyecciones</h2>
                      </div>
                      <!-- /.col -->

                      <div class="col-12">
                        <div class="card card-primary card-outline">
                          <div class="card-header"> 

                            <!-- agregar pago  -->
                            <h3 class="card-title " id="btn-agregar" >
                              <button type="button" class="btn bg-gradient-primary btn-sm" data-toggle="modal" data-target="#modal-agregar-proyecciones" onclick="limpiar_form_proyecciones();">
                              <i class="fas fa-plus-circle"></i> Agregar Proyecciones
                              </button>                     
                            </h3> 

                          </div>
                          <!-- /.card-header -->
                          <div class="card-body px-1 py-1">
                            <div class="row">
                              <div class=" col-12 col-sm-12">
                                <div class="card card-primary card-outline card-outline-tabs mb-0">
                                  <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-tabs lista-fechas-proyeccion" id="tabs-for-tab" role="tablist">
                                      <li class="nav-item">
                                        <a class="nav-link active" role="tab" ><i class="fas fa-spinner fa-pulse fa-sm"></i></a>
                                      </li>           
                                    </ul>
                                  </div>
                                  <div class="card-body" >                                  
                                    <div class="tab-content" id="tabs-for-tabContent">

                                      <!-- TABLA - FECHAS PROYECCION -->
                                      <div class="tab-pane fade show active" id="tabs-for-fecha-proyeccion" role="tabpanel" aria-labelledby="tabs-for-fecha-proyeccion-tab">
                                        <div class="row">                                        
                                          <div class="col-12 row-horizon disenio-scroll">
                                            <table id="tabla-fecha-proyeccion" class="table table-bordered table-striped display" style="width: 100% !important;">
                                              <thead>                                              
                                                <tr>
                                                  <th class="text-center">#</th>
                                                  <th>OPCIONES</th>                                                 
                                                  <th>FECHA</th>
                                                  <th data-toggle="tooltip" data-original-title="CAJA">CAJA</th>
                                                  <th >TOTAL PROYECCIÓN</th>
                                                  <th>DESCIPCION</th>
                                                  <th data-toggle="tooltip" data-original-title="ESTADO">ESTADO</th>
                                                </tr>
                                              </thead>
                                              <tbody></tbody>
                                              <tfoot>
                                                <tr>
                                                  <th class="text-center">#</th>
                                                  <th>OPCIONES</th>
                                                  <th>FECHA</th>
                                                  <th data-toggle="tooltip" data-original-title="CAJA">CAJA</th>
                                                  <th >TOTAL PROYECCIÓN</th>
                                                  <th>DESCIPCION</th>
                                                  <th data-toggle="tooltip" data-original-title="ESTADO">ESTADO</th>
                                                </tr>
                                              </tfoot>
                                            </table>
                                          </div>
                                          <!-- /.col -->
                                        </div>
                                        <!-- /.row -->
                                      </div>

                                      <!-- TABLA - DETALLE PROYECCION -->
                                      <div class="tab-pane fade" id="tabs-for-detalle-proyeccion" role="tabpanel" aria-labelledby="tabs-for-detalle-proyeccion-tab">
                                        <div class="row">      
                                          <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-2">
                                            <button type="button" class="btn bg-gradient-warning btn-sm btn-editar-p" onclick="show_hide_span_input_p(2,1);"><i class="fas fa-pencil-alt"></i> <span class="d-none d-sm-inline-block">Editar </span></button>
                                            <button type="button" class="btn bg-gradient-primary btn-sm btn-guardar-p hidden" onclick=""><i class="far fa-save"></i> <span class="d-none d-sm-inline-block"> Guardar</span></button>                                
                                            <!-- <button type="button" class="btn bg-gradient-danger btn-sm" onclick=""><i class="fas fa-skull-crossbones"></i> <span class="d-none d-sm-inline-block">Eliminar</span></button> -->
                                            <button type="button" class="btn bg-gradient-gray btn-sm " onclick="html_table_to_excel('proyeccion-1', 'xlsx', 'detalle excel', 'hoja 1');"><i class="far fa-file-excel"></i> <span class="d-none d-sm-inline-block">Exportar</span></button>
                                          </div>                                  
                                          <div class="col-12 row-horizon disenio-scroll ">
                                            <!-- tabla principal -->                            
                                            <table  class="table table-bordered /*table-striped*/ table-hover text-nowrap" id="proyeccion-1">
                                              <thead >
                                                <tr class="bg-info">                                    
                                                  <th class="py-1 text-center" colspan="3">ESTADO FINANCIERO - PROYECCIÓN AL</th> 
                                                  <th class="py-1 text-center" >
                                                    <span class=" fecha_pd"><i class="fas fa-spinner fa-pulse"></i></span>  
                                                  </th>                                                
                                                </tr>
                                                <tr class="bg-info"> 
                                                  <th class="py-1 text-left" colspan="4">
                                                    <span class="detalle_pd"><i class="fas fa-spinner fa-pulse"></i></span> 
                                                  </th>               
                                                </tr>
                                                <tr class="bg-info"> 
                                                  <th class="py-1 text-center w-25px">#</th> 
                                                  <th class="py-1">DESCRIPCIÓN</th>
                                                  <th class="py-1 ">DETALLE</th>
                                                  <th class="py-1 text-center">MONTO</th>                                                   
                                                  <th class="py-1 bg-gradient-white">
                                                    <button type="button" class="btn btn-xs bg-gradient-primary w-40px btn-add-detalle " onclick="" data-toggle="tooltip" data-original-title="Agregar Item" ><i class="fas fa-plus"></i> </button>     
                                                    <input type="hidden" name="" id="cant_detalle">                                 
                                                  </th>                                                          
                                                </tr>
                                              </thead>
                                              <!-- /.thead -->
                                              <tbody class="tbody_proyeccion 0">                                                 
                                                <tr>
                                                  <td colspan="4"> 
                                                    <div class="row" ><div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-4x"></i><br/><br/><h4>Cargando...</h4></div></div>
                                                  </td>                                                  
                                                </tr>
                                                <!-- <tr class="data_1 data_bloque_2 detalle_tr_2 sub_2_0" >
                                                  <td class="py-1 text-center detalle_td_num_2" data-widget="expandable-table" aria-expanded="true" onclick="delay(function(){show_hide_tr('.detalle_td_num_2','.sub_detalle_tr_2')}, 200 );" > <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>2 </td>
                                                  <td class="py-1">
                                                    <span class="span_p_1">DEVOLUCIÓN DE PRESTAMOS</span> 
                                                    <input type="text" id="" class="hidden input_p_1 w-100" value="DEVOLUCIÓN DE PRESTAMOS">
                                                  </td>
                                                  <td class="py-1"> </td>                           
                                                  <td class="py-1">
                                                    <div class="formato-numero-conta ">
                                                      <span>S/</span> <span >130,000.00</span>
                                                    </div> 
                                                  </td>   
                                                  <td class="py-1">
                                                    <button type="button" class="btn btn-xs bg-gradient-primary detalle_btn_2" onclick="add_tr_sub_detalle(1,2,1)"><i class="fas fa-plus"></i> </button>
                                                    <button type="button" class="btn btn-xs bg-gradient-danger" onclick="remove_tr_detalle(1,2,0)"><i class="far fa-trash-alt"></i> </button>
                                                  </td>                                  
                                                </tr> -->
                                                <!-- /.tr --> 

                                                <!-- <tr class="data_bloque_2 sub_detalle_tr_2 sub_2_1">
                                                  <td class="py-1 text-center"></td>
                                                  <td class="py-1 text-right"> 
                                                    <span class="span_p_1">DAVID REQUEJO</span> 
                                                    <input type="text" id="" class="hidden input_p_1 w-100" value="DAVID REQUEJO">
                                                  </td>                                                            
                                                  <td class="py-1">
                                                    <div class="formato-numero-conta span_p_1">
                                                      <span>S/</span>10,000.00
                                                    </div> 
                                                    <input type="text" id="" class="hidden input_p_1 w-100" value="10,000.00">
                                                  </td> 
                                                  <td class="py-1"> </td> 
                                                  <td class="py-1">
                                                    <button type="button" class="btn bg-gradient-danger btn-xs" onclick="remove_tr_sub_detalle(1,2, 1)" ><i class="far fa-trash-alt"></i> </button>
                                                  </td>
                                                </tr> -->
                                                <!-- /.tr -->                   
                                                
                                              </tbody>
                                              <!-- /.tbody -->
                                              <tr>
                                                <td class="py-1 celda-b-y-2px "></td>
                                                <td class="py-1 celda-b-y-2px">GASTOS PROYECTADOS</td>
                                                <td class="py-1 celda-b-y-2px text-center fecha_pd"><i class="fas fa-spinner fa-pulse"></i></td>                           
                                                <td class="py-1 celda-b-y-2px">
                                                  <div class="formato-numero-conta">
                                                    <span>S/</span><span class="gasto_proyectado"><i class="fas fa-spinner fa-pulse"></i></span>
                                                  </div> 
                                                </td> 
                                              </tr>
                                              <!-- /.tr -->
                                              <tbody>
                                                <tr> 
                                                  <td class="py-1 text-center ">1</td>
                                                  <td class="py-1">CAJA</td>
                                                  <td class="py-1 text-center fecha_pd"><i class="fas fa-spinner fa-pulse"></i></td>
                                                  <td class="py-1">
                                                    <div class="formato-numero-conta"><span>S/</span><span class="caja_pry"><i class="fas fa-spinner fa-pulse"></i></span></div>
                                                  </td> 
                                                </tr>
                                                <tr>
                                                  <td class="py-1 text-center">2</td>
                                                  <td class="py-1">PRESTAMOS Y CRÉDITOS (por pagar)</td>
                                                  <td class="py-1 text-center fecha_pd"><i class="fas fa-spinner fa-pulse"></i></td>
                                                  <td class="py-1"><div class="formato-numero-conta"><span>S/</span><span class="prestamo_credito_pry"><i class="fas fa-spinner fa-pulse"></i></span></div></td> 
                                                </tr>
                                                <tr>
                                                  <td class="py-1 text-center">3</td><td class="py-1">GASTOS ACTUALIZADOS</td>
                                                  <td class="py-1 text-center fecha_pd"><i class="fas fa-spinner fa-pulse"></i></td>
                                                  <td class="py-1"><div class="formato-numero-conta"><span>S/</span><span class="gasto_actualizado_pry"><i class="fas fa-spinner fa-pulse"></i></span></div></td> 
                                                </tr>
                                                <tr>
                                                  <td class="py-1 text-center">4</td>
                                                  <td class="py-1">VALORIZACIONES COBRADAS (<span class="cant_cobradas_pry"><i class="fas fa-spinner fa-pulse"></i></span>)</td>
                                                  <td class="py-1 text-center fecha_pd"><i class="fas fa-spinner fa-pulse"></i></td>
                                                  <td class="py-1"><div class="formato-numero-conta"><span>S/</span><span class="valorizacion_cobrada_pry"><i class="fas fa-spinner fa-pulse"></i></span></div></td> 
                                                </tr>
                                                <tr>
                                                  <td class="py-1 text-center">5</td>
                                                  <td class="py-1">VALORIZACIONES POR COBRAR (<span class="cant_por_cobrar_pry"><i class="fas fa-spinner fa-pulse"></i></span>)</td>
                                                  <td class="py-1 text-center fecha_pd"><i class="fas fa-spinner fa-pulse"></i></td>
                                                  <td class="py-1"><div class="formato-numero-conta"><span>S/</span><span class="valorizacion_por_cobrar_pry"><i class="fas fa-spinner fa-pulse"></i></span></div></td> 
                                                </tr>
                                                <tr>
                                                  <td class="py-1 text-center">6</td>
                                                  <td class="py-1">GARANTÍA</td> 
                                                  <td class="py-1 text-center fecha_pd"><i class="fas fa-spinner fa-pulse"></i></td>
                                                  <td class="py-1"><div class="formato-numero-conta"><span>S/</span><span class="garantia_pry"><i class="fas fa-spinner fa-pulse"></i></span></div></td >
                                                </tr>
                                                <tr>
                                                  <td class="py-1 text-center">7</td>
                                                  <td class="py-1">MONTO DE OBRA</td>
                                                  <td class="py-1 text-center fecha_pd"><i class="fas fa-spinner fa-pulse"></i></td>
                                                  <td class="py-1"><div class="formato-numero-conta"><span>S/</span><span class="monto_obra_pry"><i class="fas fa-spinner fa-pulse"></i></span></div></td > 
                                                </tr>

                                              </tbody>
                                              <!-- /.tbody -->
                                              <tfoot>
                                                <tr>                                       
                                                  <th class="py-1 celda-b-t-2px" colspan="2">INTERÉS PAGADO</th>
                                                  <th class="py-1 celda-b-t-2px text-center fecha_pd" ><i class="fas fa-spinner fa-pulse"></i></th>
                                                  <th class="py-1 celda-b-t-2px"><div class="formato-numero-conta"><span>S/</span><span class="interes_pagado_pry"><i class="fas fa-spinner fa-pulse"></i></span></div> </th>      
                                                </tr>
                                                <tr>                                       
                                                  <th class="py-1" colspan="2" rowspan="2">GANANCIA ACTUAL (SIN DESCONTAR INTERÉS POR PAGAR)</th>
                                                  <th class="py-1 text-center fecha_pd" rowspan="2"><i class="fas fa-spinner fa-pulse"></i></th>
                                                  <th class="py-1"><div class="formato-numero-conta"><span>S/</span><span class="ganancia_actual_pry"><i class="fas fa-spinner fa-pulse"></i></span></div></th>      
                                                </tr>
                                                <tr>                                       
                                                  <th class="py-1 text-right"><span class="porcentaje_pry"><i class="fas fa-spinner fa-pulse"></i></span></th>      
                                                </tr>
                                              </tfoot>
                                              <!-- /.tfoot -->
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

                  <!-- Modal agregar PAGOS POR MES -->
                  <div class="modal fade" id="modal-agregar-proyecciones">
                    <div class="modal-dialog modal-dialog-scrollable modal-md">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Agregar Proyecciones</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        
                        <div class="modal-body">
                          <!-- form start -->
                          <form id="form-proyecciones" name="form-proyecciones"  method="POST" >                      
                            <div class="card-body">
                              <div class="row" id="cargando-1-fomulario">

                                <!-- id proyecccion -->
                                <input type="hidden" name="idproyeccion_p" id="idproyeccion_p" /> 
                                <!-- id proyecto -->
                                <input type="hidden" name="idproyecto_p" id="idproyecto_p" />                   

                                <!-- fecha de proyeccion-->
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label for="fecha_p">Proyección al <sup class="text-danger">*</sup></label>
                                    <div class="input-group date "  data-target-input="nearest">
                                      <input type="text" class="form-control" id="fecha_p" name="fecha_p" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask   />
                                      <div class="input-group-append click-btn-fecha-p cursor-pointer" for="fecha_p" >
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                    </div>                                 
                                  </div>
                                </div>

                                <!-- caja-->
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label for="caja_p">Caja</label>
                                    <input type="number" class="form-control" name="caja_p" id="caja_p" readonly placeholder="Caja" />
                                  </div>
                                </div>
                                
                                <!-- Descripcion-->
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <label for="descripcion_p">Descripción </label> <br>
                                    <textarea name="descripcion_p" id="descripcion_p" class="form-control" rows="2"></textarea>
                                  </div>                                                        
                                </div>

                                <!-- barprogress -->
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                                  <div class="progress" id="barra_progress_proyeccion_div">
                                    <div id="barra_progress_proyeccion" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                      0%
                                    </div>
                                  </div>
                                </div>                                          

                              </div>  

                              <div class="row" id="cargando-2-fomulario" style="display: none;">
                                <div class="col-lg-12 text-center">
                                  <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                                  <h4>Cargando...</h4>
                                </div>
                              </div>
                              
                            </div>
                            <!-- /.card-body -->                      
                            <button type="submit" style="display: none;" id="submit-form-proyecciones">Submit</button>                      
                          </form>
                        </div>
                        <div class="modal-footer justify-content-between" >
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-success" id="guardar_registro_proyecciones">Guardar Cambios</button>
                        </div>                  
                      </div>
                    </div>
                  </div>              

                </section>
                <!-- /.content -->
              </div>
              <!--Fin-Contenido-->
              <?php
            }else{
              require 'noacceso.php';
            }
            require 'footer.php';
          ?>

        </div>

        <?php require 'script.php'; ?>     

        <script type="text/javascript" src="../plugins/xlsx/xlsx.full.min.js"></script>

        <script type="text/javascript" src="scripts/estado_financiero.js"></script>
         
        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
        <!-- para el ICONO - gira al hacer clic -->
        <style>
          [data-widget="expandable-table"][aria-expanded="true"] i.expandable-table-caret[class*="right"] { transform: rotate(90deg); }
          [data-widget="expandable-table"][aria-expanded="true"] i.expandable-table-caret[class*="left"] { transform: rotate(-90deg); }
        </style>
        
      </body>
    </html>
    <?php    
  }
  ob_end_flush();
?>
