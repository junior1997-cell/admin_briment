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
        <title> Compras Cafe | Admin JDL </title>

        <?php $title = "Compras"; require 'head.php'; ?>

        <!--CSS  switch_MATERIALES-->
        <link rel="stylesheet" href="../dist/css/switch_materiales.css" />
        <link rel="stylesheet" href="../dist/css/leyenda.css" />
        
      </head>
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed">
        <div class="wrapper">
          <!-- Preloader -->
          <!-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../dist/svg/logo-principal.svg" alt="AdminLTELogo" width="360" />
          </div> -->

          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['compra_grano']==1){
            //require 'enmantenimiento.php';
            ?>
            <!--Contenido-->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <div class="content-header">
                <div class="container-fluid"> 
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1 class="m-0"> 
                        <img src="../dist/svg/negro-grano-cafe-ico.svg" class="nav-icon" alt="" style="width: 21px !important;" > Compras Café 
                        <span class="h1-nombre-cliente"></span>  
                      </h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="compra_insumos.php">Home</a></li>
                        <li class="breadcrumb-item active">Compras Café</li>
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
                        <!-- Start Main Top -->
                        <div class="main-top">
                          <div class="container-fluid border-bottom">
                            <div class="row">
                              <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"> 
                                <div class="card-header">
                                  <h3 class="card-title">
                                    <!--data-toggle="modal" data-target="#modal-agregar-compra"  onclick="limpiar();"-->
                                    <button type="button" class="btn bg-gradient-primary" id="btn_agregar" onclick="show_hide_form(3); limpiar_form_compra();">
                                      <i class="fas fa-plus-circle"></i> Agregar
                                    </button>                                    
                                    <button type="button" class="btn bg-gradient-warning" id="btn_regresar" style="display: none;" onclick="show_hide_form(1);">
                                      <i class="fas fa-arrow-left"></i> Regresar
                                    </button>
                                    <button type="button" class="btn bg-gradient-primary" id="btn_pagar" style="display: none;" data-toggle="modal"  data-target="#modal-agregar-pago-compra" onclick="limpiar_form_pago_compra(); calcular_deuda();">
                                      <i class="fas fa-dollar-sign"></i> Agregar Pago
                                    </button>                                     
                                  </h3>
                                </div>
                              </div>
                              <!-- Leyecnda pagos -->
                              <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 hiddenn leyecnda_pagos" style="background-color: aliceblue;">
                                <div class="text-slid-box">
                                  <div id="offer-box" class="contenedor">
                                    <div> <b>Leyenda-pago</b> </div>
                                    <ul class="offer-box cls-ul">
                                      <li>
                                        <span class="text-center badge badge-danger" >Pago sin iniciar </span> 
                                      </li>
                                      <li>
                                        <span class="text-center badge badge-warning" >Pago en proceso </span>
                                      </li>
                                      <li>
                                        <span class="text-center badge badge-success" >Pago completo</span>
                                      </li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                              <!-- Leyecnda saldos -->
                              <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 hiddenn leyecnda_saldos" style="background-color: #f0f8ff7d;">
                                <div class="text-slid-box">
                                  <div id="offer-box" class="contenedorr">
                                    <div> <b>Leyenda-saldos</b> </div>
                                    <ul class="offer-box clss-ul">
                                      <li>
                                        <span class="text-center badge badge-warning " >Pago nulo o pago en proceso </span> 
                                      </li>
                                      <li>
                                        <span class="text-center badge badge-success" >Pago Completo </span>
                                      </li>
                                      <li>
                                        <span class="text-center badge badge-danger" >Pago excedido</span>
                                      </li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- End Main Top -->

                        <!-- /.card-header -->
                        <div class="card-body">
                          <!-- TABLA - COMPRAS -->
                          <div id="div_tabla_compra">
                            <h5><b>Lista de compras Por Facturas</b></h5>
                            <!-- filtros -->
                            <div class="filtros-inputs row mb-4">

                              <!-- filtro por: fecha inicial -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-2">    
                                <div class="form-group">
                                  <!-- <label for="filtro_fecha_inicio" >Fecha inicio </label> -->
                                  <div class="input-group date"  >
                                    <div class="input-group-append cursor-pointer click-btn-fecha-inicio" >
                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input type="text" class="form-control"  id="filtro_fecha_inicio" onchange="cargando_search(); delay(function(){filtros()}, 50 );" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask autocomplete="off" />                                    
                                  </div>
                                </div>                                
                              </div>

                              <!-- filtro por: fecha final -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-2">                                
                                <div class="form-group">
                                  <!-- <label for="filtro_fecha_inicio" >Fecha fin </label> -->
                                  <div class="input-group date"  >
                                    <div class="input-group-append cursor-pointer click-btn-fecha-fin" >
                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input type="text" class="form-control"  id="filtro_fecha_fin" onchange="cargando_search(); delay(function(){filtros()}, 50 );" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask autocomplete="off" />                                    
                                  </div>
                                </div> 
                              </div>

                              <!-- filtro por: proveedor -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <!-- <label for="filtros" class="cargando_proveedor">Proveedor &nbsp;<i class="text-dark fas fa-spinner fa-pulse fa-lg"></i><br /></label> -->
                                  <select id="filtro_cliente" class="form-control select2" onchange="cargando_search(); delay(function(){filtros()}, 50 );" style="width: 100%;"> 
                                  </select>
                                </div>                                
                              </div>

                              <!-- filtro por: proveedor -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-2">
                                <div class="form-group">
                                  <!-- <label for="filtros" >Tipo comprobante </label> -->
                                  <select id="filtro_tipo_comprobante" class="form-control select2" onchange="cargando_search(); delay(function(){filtros()}, 50 );" style="width: 100%;"> 
                                    <option value="0">Todos</option>
                                    <option value="Ninguno">Ninguno</option>
                                    <option value="Boleta">Boleta</option>
                                    <option value="Factura">Factura</option>
                                    <option value="Nota de venta">Nota de venta</option>
                                  </select>
                                </div>
                                
                              </div>
                            </div>
                            <!-- /.filtro -->
                            
                            <!-- TABLA - principal -->
                            <table id="tabla-compra-grano" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th colspan="15" class="cargando text-center bg-danger"><i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando... </th>
                                </tr>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th>
                                  <th>Fecha</th>
                                  <th>Cliente</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo de Persona">Tipo</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo y Número Comprobante">Comprobante</th>
                                  <th>Metodo de Pago</th>
                                  <th>Total</th>                                  
                                  <th>Pagos</th>
                                  <th>Saldo</th>

                                  <th>Tipo Doc.</th>
                                  <th>Num. Doc.</th>
                                  <th>Tipo Comprobante</th>
                                  <th>Num. Comprobante</th>
                                  <th>Pagos</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th>
                                  <th>Fecha</th>
                                  <th>Cliente</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo de Persona">Tipo</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo y Número Comprobante">Comprobante</th>
                                  <th>Metodo de Pago</th>
                                  <th><span>S/</span><span>0.00</span></th>
                                  <th>Pagos</th> 
                                  <th>Saldo</th>
                                  
                                  <th>Tipo Doc.</th>
                                  <th>Num. Doc.</th>
                                  <th>Tipo Comprobante</th>
                                  <th>Num. Comprobante</th>
                                  <th>Pagos</th>
                                </tr>
                              </tfoot>
                            </table>
                            <br />
                            <h4><b>Lista de Compras Por Cliente</b></h4>
                            <table id="tabla-compra-cliente" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th>
                                  <th>Proveedor</th>
                                  <th>Cant</th>
                                  <th>Cel.</th>
                                  <th>Total</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th> 
                                  <th>Proveedor</th>
                                  <th>Cant</th>
                                  <th>Cel.</th>
                                  <th>Total</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                          <!-- TABLA - COMPRAS POR CLIENTE -->
                          <div id="div_tabla_detalle_compra_x_cliente" style="display: none;">
                            <h5><b>Lista de compras Por Facturas</b></h5>
                            <table id="tabla-detalle-compra-grano-x-cliente" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th>
                                  <th>Fecha</th>
                                  <th>Comprobante</th>
                                  <th data-toggle="tooltip" data-original-title="Número Comprobante">Num. Comprobante</th>
                                  <th>Total</th>
                                  <th>descripcion</th>
                                  <th>Estado</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th>
                                  <th>Fecha</th>
                                  <th>Comprobante</th>
                                  <th data-toggle="tooltip" data-original-title="Número Comprobante">Num. Comprobante</th>
                                  <th>Total</th>
                                  <th>Descripcion</th>
                                  <th>Estado</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                          <!-- FORM - AGREGAR COMPRA GRANO-->
                          <div id="div_form_agregar_compras_grano" style="display: none;">
                            <div class="modal-body p-0px mb-2">
                              <!-- form start -->
                              <form id="form-compras" name="form-compras" method="POST">
                                 
                                <div class="row" id="cargando-1-fomulario">
                                  <input type="hidden" name="idcompra_grano" id="idcompra_grano" /> 

                                  <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="row">
                                    
                                      <!-- Tipo de Empresa -->
                                      <div class="col-lg-10">
                                        <div class="form-group">
                                          <label for="idcliente">Cliente <sup class="text-danger">(*)</sup></label>
                                          <select id="idcliente" name="idcliente" class="form-control select2" data-live-search="true" required title="Seleccione cliente" onchange="extrae_ruc();"> </select>
                                          <input type="hidden" name="ruc_dni_cliente" id="ruc_dni_cliente" /> 
                                        </div>
                                      </div>

                                      <!-- adduser -->
                                      <div class="col-lg-2">
                                        <div class="form-group">                                          
                                          <label for="opciones">Opciones</label>                                          
                                          <div class="row">
                                            <div class="col-6 text-center">
                                              <a data-toggle="modal" href="#modal-agregar-cliente" >
                                                <button type="button" class="btn btn-success" data-toggle="tooltip" data-original-title="Agregar Provedor" onclick="limpiar_form_cliente();">
                                                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                                                </button>
                                              </a>
                                            </div>
                                            <div class="col-6 text-center">
                                              <button type="button" class="btn btn-warning btn-editar-cliente" data-toggle="tooltip" data-original-title="Editar:" onclick="mostrar_para_editar_cliente();">
                                                <i class="fa-solid fa-pencil" aria-hidden="true"></i>
                                              </button>
                                            </div>       
                                          </div>                                           
                                        </div>
                                      </div>

                                      <!-- fecha -->
                                      <div class="col-lg-4" >
                                        <div class="form-group">
                                          <label for="fecha_compra">Fecha <sup class="text-danger">*</sup></label>
                                          <input type="date" name="fecha_compra" id="fecha_compra" class="form-control" placeholder="Fecha" onchange="capturar_pago_compra();" />
                                        </div>
                                      </div>                                        
                                      
                                      <!-- Tipo de comprobante -->
                                      <div class="col-lg-4" id="content-tipo-comprobante">
                                        <div class="form-group">
                                          <label for="tipo_comprobante">Tipo Comprob. <sup class="text-danger">(*)</sup></label>
                                          <select name="tipo_comprobante" id="tipo_comprobante" class="form-control select2"  onchange="default_val_igv(); modificarSubtotales(); ocultar_comprob(); autoincrement_comprobante(this);" placeholder="Seleccinar un tipo de comprobante">
                                            <option value="Ninguno">Ninguno</option>
                                            <option value="Boleta">Boleta</option>
                                            <option value="Factura">Factura</option>
                                            <option value="Nota de venta">Nota de venta</option>
                                          </select>
                                        </div>
                                      </div>                                       

                                      <!-- IGV-->
                                      <div class="col-lg-4" id="content-igv">
                                        <div class="form-group">
                                          <label for="val_igv">IGV <sup class="text-danger">*</sup></label>
                                          <input type="text" name="val_igv" id="val_igv" class="form-control" value="0.18" onkeyup="modificarSubtotales();" />
                                        </div>
                                      </div>

                                      <!-- numero_comprobante -->
                                      <div class="col-lg-6" id="content-serie-comprobante">
                                        <div class="form-group">
                                          <label for="numero_comprobante">N° de Comprob. <sup class="text-danger">(único*)</sup></label>
                                          <input type="text" name="numero_comprobante" id="numero_comprobante" class="form-control" placeholder="N° de Comprobante" readonly />
                                        </div>
                                      </div>

                                      <!-- Establecimiento-->
                                      <div class="col-lg-12" id="content-establecimiento">
                                        <div class="form-group">
                                          <label for="establecimiento">Establecimiento </label> <br />
                                          <textarea name="establecimiento" id="establecimiento" class="form-control" rows="1">PASAJE Sr. DE SIPAN #140 - SECTOR SAN CAMILO</textarea>
                                        </div>
                                      </div> 

                                      <!-- descripcion-->
                                      <div class="col-lg-12">
                                        <div class="form-group">
                                          <label for="descripcion">Base </label> <br />
                                          <textarea name="descripcion" id="descripcion" class="form-control" rows="2"></textarea>
                                        </div>
                                      </div>    
                                      
                                      <!-- metodo de pago -->
                                      <div class="col-sm-12 col-md-12 col-lg-4">
                                        <div class="form-group">
                                          <label for="metodo_pago">Método de pago <sup class="text-danger">*</sup></label>
                                          <select id="metodo_pago" name="metodo_pago" class="form-control select2" data-live-search="true" required title="Seleccione glosa" onchange="capturar_pago_compra();"> 
                                            <option title="fas fa-hammer" value="CONTADO">CONTADO</option>
                                            <option title="fas fa-gas-pump" value="CREDITO">CREDITO</option>
                                          </select>
                                        </div> 
                                      </div>

                                      <!-- Fecha pago -->
                                      <div class="col-sm-12 col-md-6 col-lg-4" >
                                        <div class="form-group">
                                          <label for="fecha_proximo_pago">Próximo pago<sup class="text-danger">*</sup></label>
                                          <input type="date" name="fecha_proximo_pago" id="fecha_proximo_pago" class="form-control" placeholder="Fecha" />
                                        </div>
                                      </div>

                                      <!-- Pago a realizar -->
                                      <div class="col-sm-12 col-md-6 col-lg-4 ">
                                        <div class="form-group">
                                          <label for="monto_pago_compra">Pago <small class="span-pago-compra"></small> </label>
                                          <input type="text" name="monto_pago_compra" id="monto_pago_compra" class="form-control" readonly onClick="this.select();" placeholder="Pago realizado" />
                                        </div>
                                      </div>

                                    </div>
                                    <!-- /.row -->
                                  </div> 
                                  <!-- /.col -->

                                  <!--tabla detalles compra-->
                                  <div class="col-12 col-sm-12 col-md-6 col-lg-6 table-responsive ">
                                    
                                    <table id="detalles" class="table /*table-striped*/ table-bordered table-condensed table-hover">                                      
                                      <tbody>
                                        <tr >
                                          <th>TIPO DE CAFE</th>
                                          <td class="py-1 text-right form-group">
                                            <select class="form-control tipo_grano" name="tipo_grano" onchange="modificarSubtotales()">
                                              <option >PERGAMINO</option>
                                              <option>COCO</option>
                                            </select>
                                          </td>
                                        </tr>
                                        <tr>
                                          <th class="py-1">UNDIAD</th>
                                          <td class="py-1 text-right form-group">
                                            <input type="text" class="form-control form-control-sm input-no-border unidad_medida"  name="unidad_medida" id="unidad_medida" value="KILO" readonly required>
                                          </td>
                                        </tr>
                                        <tr>
                                          <th class="py-1">KILOS BRUTOS </th>
                                          <td class="py-1 text-right form-group"><input type="number" class="form-control form-control-sm peso_bruto text-bold" name="peso_bruto" value="0" min="0.01" step="0.01" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
                                        </tr>
                                        <tr>
                                          <th class="py-1">SACOS</th>
                                          <td class="py-1 text-right form-group"><input type="number" class="form-control form-control-sm sacos" name="sacos" value="0" min="0.00" step="0.01" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
                                        </tr>
                                        <tr>
                                          <th class="py-1">HUMEDAD(%)</th>
                                          <td class="py-1 text-right form-group"><input type="number" class="form-control form-control-sm dcto_humedad" name="dcto_humedad" value="0" min="0.00" step="0.01" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
                                        </tr>
                                        <tr>
                                          <th class="py-1">RENDIMINETO(%)</th>
                                          <td class="py-1 text-right form-group"><input type="number" class="form-control form-control-sm dcto_rendimiento" name="dcto_rendimiento" value="0" min="0.00" step="0.01" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
                                        </tr>
                                        <tr>
                                          <th class="py-1">SEGUNDA(%)</th>
                                          <td class="py-1 text-right form-group"><input type="number" class="form-control form-control-sm dcto_segunda" name="dcto_segunda" value="0" min="0.00" step="0.01" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
                                        </tr>
                                        <tr>
                                          <th class="py-1">CASCARA(%)</th>
                                          <td class="py-1 text-right form-group"><input type="number" class="form-control form-control-sm dcto_cascara" name="dcto_cascara" value="0" min="0.00" step="0.01" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
                                        </tr>
                                        <tr>
                                          <th class="py-1">TAZA(%)</th>
                                          <td class="py-1 text-right form-group"><input type="number" class="form-control form-control-sm dcto_taza" name="dcto_taza" value="0" min="0.00" step="0.01" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
                                        </tr>
                                        <tr>
                                          <th class="py-1">TARA(SACOS + HUMEDAD)</th>
                                          <td class="py-1 text-right form-group"><input type="number" class="form-control form-control-sm dcto_tara" name="dcto_tara" value="0" min="0.00" step="0.01" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
                                        </tr>
                                        <tr>
                                          <th class="py-1">KG. NETOS </th>
                                          <td class="py-1 text-right form-group"><input type="number" class="form-control form-control-sm input-no-border-right peso_neto" name="peso_neto" value="0" min="0.01" readonly required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
                                        </tr>
                                        <tr>
                                          <th class="py-1">QUINTAL NETO <span class="convert_a_q"></span></th>
                                          <td class="py-1 text-right form-group"><input type="number" class="form-control form-control-sm input-no-border-right text-bold quintal_neto" name="quintal_neto" value="0" min="0.01" readonly required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
                                        </tr>
                                        <tr>
                                          <th class="py-1">PRECIO</th>
                                          <td class="py-1 text-right form-group">
                                            <input type="hidden" class="precio_sin_igv" name="precio_sin_igv" value="0">
                                            <input type="hidden" class="precio_igv" name="precio_igv" value="0">
                                            <input type="number" class="form-control form-control-sm precio_con_igv" name="precio_con_igv" value="0" min="0.01" step="0.01"  required onkeyup="modificarSubtotales();" onchange="modificarSubtotales();">
                                          </td>
                                        </tr>
                                        <tr>
                                          <th class="py-1">DESCUENTO <small>(adicional)</small></th>
                                          <td class="py-1 text-right form-group"><input type="number" class="form-control form-control-sm descuento" name="descuento" value="0" min="0" step="0.01" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
                                        </tr>
                                        <tr>
                                          <th class="py-1 text-right">SUBTOTAL</th>
                                          <td class="py-1 text-right"><span class="text-right subtotal_producto">0.00</span> <input type="hidden" class="input_subtotal_producto" name="subtotal_producto" id="subtotal_producto"></td>
                                        </tr>
                                      </tbody>
                                      <tfoot>
                                        
                                        <th class="text-right">
                                          <h6 class="tipo_gravada">GRAVADA</h6>
                                          <h6 class="val_igv">IGV (18%)</h6>
                                          <h5 class="font-weight-bold">TOTAL</h5>
                                        </th>
                                        <th class="text-right"> 
                                          <h6 class="font-weight-bold subtotal_compra">S/ 0.00</h6>
                                          <input type="hidden" name="subtotal_compra" id="subtotal_compra" />
                                          <input type="hidden" name="tipo_gravada" id="tipo_gravada" />

                                          <h6 class="font-weight-bold igv_compra">S/ 0.00</h6>
                                          <input type="hidden" name="igv_compra" id="igv_compra" />
                                          
                                          <h5 class="font-weight-bold total_compra">S/ 0.00</h5>
                                          <input type="hidden" name="total_compra" id="total_compra" />
                                          
                                        </th>
                                      </tfoot>
                                    </table>
                                    <!-- /.table -->
                                  </div>
                                  <!-- /.col -->
                                </div>
                                <!-- /.row -->

                                <div class="row" id="cargando-2-fomulario" style="display: none;">
                                  <div class="col-lg-12 text-center">
                                    <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                    <br />
                                    <h4>Cargando...</h4>
                                  </div>
                                </div>                                 
                                 
                                <button type="submit" style="display: none;" id="submit-form-compras">Submit</button>
                              </form>
                            </div>

                            <div class="modal-footer justify-content-between pl-0 pb-0 ">
                              <button type="button" class="btn btn-danger" onclick="show_hide_form(1);" data-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-success" id="guardar_registro_compras">Guardar Cambios</button>
                            </div>
                          </div>                          

                          <!-- TABLA - PAGOS COMPRAS GRANO -->
                          <div id="div_tabla_pago_compras_grano" style="display: none;">                            
                            <div style="text-align: center;">
                              <div>
                                <h4>Total a pagar: <b id="total_de_compra"></b></h4>
                              </div>
                              <table id="tabla-pagos-compras" class="table table-bordered table-striped display" style="width: 100% !important;">
                                <thead>
                                  <tr>
                                    <th>#</th>
                                    <th>Acciones</th>
                                    <th data-toggle="tooltip" data-original-title="Fecha de pago">Fecha</th>
                                    <th>Forma de pago</th>
                                    <th>Monto</th>
                                    <th>Descripción</th>
                                    <th data-toggle="tooltip" data-original-title="Comprobante">Doc.</th>
                                    <th>Estado</th>
                                  </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                  <tr>
                                    <th>#</th>
                                    <th>Acciones</th>
                                    <th data-toggle="tooltip" data-original-title="Fecha de pago">Fecha</th>
                                    <th>Forma de pago</th>
                                    <th class="text-nowrap px-2"><span>S/</span><span>0.00</span></th>
                                    <th>Descripción</th>
                                    <th data-toggle="tooltip" data-original-title="Comprobante">Doc.</th>
                                    <th>Estado</th>
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

                  <!-- MODAL - agregar cliente -->
                  <div class="modal fade" id="modal-agregar-cliente">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Agregar Cliente</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>

                        <div class="modal-body">
                          <!-- form start -->
                          <form id="form-proveedor" name="form-proveedor" method="POST">
                            <div class="card-body ">  
                              <div class="row" id="cargando-3-fomulario">
                                <!-- id persona -->
                                <input type="hidden" name="idpersona_per" id="idpersona_per" />
                                <!-- tipo persona  -->
                                <input type="hidden" name="id_tipo_persona_per" id="id_tipo_persona_per" value="2" />
                                <input type="hidden" name="cargo_trabajador_per" id="cargo_trabajador_per" value="1">
                                <input type="hidden" name="sueldo_mensual_per" id="sueldo_mensual_per">
                                <input type="hidden" name="sueldo_diario_per" id="sueldo_diario_per">
                                <input type="hidden" name="input_socio_per" id="input_socio_per" value="0"  >

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
                                <div class="col-12 col-sm-12 col-md-12 col-lg-5">
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
                                <div class="col-12 col-sm-2 col-md-6 col-lg-1">
                                  <div class="form-group">
                                    <label for="edad_per">Edad</label>
                                    <p class="edad_per" style="border: 1px solid #ced4da; border-radius: 4px; padding: 5px;">0 años.</p>
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
                                    <label for="titular_cuenta_per">Titular de la cuenta</label>
                                    <input type="text" name="titular_cuenta_per" class="form-control" id="titular_cuenta_per" placeholder="Titular de la cuenta" />
                                  </div>
                                </div>                                 

                                <!-- Direccion -->
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 classdirecc">
                                  <div class="form-group">
                                    <label for="direccion_per">Dirección</label>
                                    <input type="text" name="direccion_per" class="form-control" id="direccion_per" placeholder="Dirección" />
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
                                    <div class="progress" id="barra_progress_proveedor_div" style="display: none !important;">
                                      <div id="barra_progress_proveedor" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
                            <button type="submit" style="display: none;" id="submit-form-proveedor">Submit</button>
                          </form>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-success" id="guardar_registro_proveedor">Guardar Cambios</button>
                        </div>
                      </div>
                    </div>
                  </div>                   

                  <!-- MODAL - DETALLE COMPRAS - charge -->
                  <div class="modal fade" id="modal-ver-detalle-compras-grano">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Detalle Compra</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>

                        <div class="modal-body">
                          <div class="row detalle_de_compra_grano" id="cargando-5-fomulario">                            
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
                          <button type="button" class="btn btn-success float-right" id="excel_compra" onclick="export_excel_detalle_factura()" ><i class="far fa-file-excel"></i> Excel</button>
                          <a type="button" class="btn btn-info" id="print_pdf_compra" target="_blank" ><i class="fas fa-print"></i> Imprimir/PDF</a>
                        </div>
                      </div>
                    </div>
                  </div>                  
                  
                  <!-- MODAL - agregar Pagos - charge -->
                  <div class="modal fade" id="modal-agregar-pago-compra">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title"><b>Agregar: </b> pago compra</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>

                        <div class="modal-body">
                          <!-- form start -->
                          <form id="form-pago-compras" name="form-pago-compras" method="POST">
                            <div class="card-body">
                              <div class="row" id="cargando-7-fomulario">
                                <!-- idpago_compra_grano -->
                                <input type="hidden" name="idpago_compra_grano_p" id="idpago_compra_grano_p" />
                                <input type="hidden" name="idcompra_grano_p" id="idcompra_grano_p" />

                                <!-- Fecha 1 onchange="calculando_cantidad(); restrigir_fecha_ant();" onkeyup="calculando_cantidad(); -->
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label for="forma_pago_p">Forma Pago <sup class="text-danger">*</sup></label>
                                    <select name="forma_pago_p" id="forma_pago_p" class="form-control select2" style="width: 100%;">
                                      <option value="Transferencia">Transferencia</option>
                                      <option value="Efectivo">Efectivo</option>
                                    </select>
                                  </div>
                                </div>

                                <!--Fecha-->
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label for="fecha_pago_p">Fecha <sup class="text-danger">*</sup></label>
                                    <input type="date" name="fecha_pago_p" class="form-control" id="fecha_pago_p" />
                                  </div>
                                </div>

                                <!--Precio Parcial-->
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label for="monto_p">Monto total </label>
                                    <input type="number" class="form-control" name="monto_p" id="monto_p" onkeyup="delay(function(){calcular_deuda();}, 100 );" onchange="delay(function(){calcular_deuda();}, 100 );" placeholder="Precio Parcial" />
                                  </div>
                                </div>

                                <!--Precio Parcial-->
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label for="Deuda">Deuda pendiente </label>
                                    <span class="form-control-mejorado deuda-actual" placeholder="Deuda"> 0.00</span>
                                  </div>
                                </div>

                                <!--Descripcion-->
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <label for="descripcion_p">Descripción <sup class="text-danger">*</sup> </label> <br />
                                    <textarea name="descripcion_p" id="descripcion_p" class="form-control" rows="2"></textarea>
                                  </div>
                                </div>
                                <!-- Factura -->
                                <div class="col-md-6">
                                  <div class="row text-center">
                                    <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                      <label for="cip" class="control-label"> Comprobante </label>
                                    </div>
                                    <div class="col-6 col-md-6 text-center">
                                      <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i"><i class="fas fa-upload"></i> Subir.</button>
                                      <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                      <input style="display: none;" id="doc1" type="file" name="doc1" accept="application/pdf, image/*" class="docpdf" />
                                    </div>
                                    <div class="col-6 col-md-6 text-center">
                                      <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(1, 'comida_extra', 'comprobante');"><i class="fas fa-redo"></i> Recargar.</button>
                                    </div>
                                  </div>
                                  <div id="doc1_ver" class="text-center mt-4">
                                    <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" />
                                  </div>
                                  <div class="text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>
                                </div>

                                <!-- barprogress -->
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                                  <div class="progress" id="barra_progress_pago_compra_div">
                                    <div id="barra_progress_pago_compra" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                            <button type="submit" style="display: none;" id="submit-form-pago-compra">Submit</button>
                          </form>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_form_pago_compra();">Close</button>
                          <button type="submit" class="btn btn-success" id="guardar_registro_pago_compra">Guardar Cambios</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <!-- MODAL - VER CMPROBANTE -->
                  <div class="modal fade" id="modal-ver-comprobante-pago">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                      <div class="modal-content">
                        <div class="modal-header" style="background-color: #0811190a;">
                          <h4 class="modal-title">Comprobante: <span class="text-bold tile-modal-comprobante"></span> </h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body div-view-comprobante-pago">
                          <!-- aqui se visualizara el comprobante -->
                        </div>
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

        <!-- table export EXCEL -->
        <script src="../plugins/export-xlsx/xlsx.full.min.js"></script>
        <script src="../plugins/export-xlsx/FileSaver.min.js"></script>
        <script src="../plugins/export-xlsx/tableexport.min.js"></script>

        <!-- ZIP -->
        <script src="../plugins/jszip/jszip.js"></script>
        <script src="../plugins/jszip/dist/jszip-utils.js"></script>
        <script src="../plugins/FileSaver/dist/FileSaver.js"></script>
        
        <script type="text/javascript" src="scripts/compra_cafe_v2.js"></script>         

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>
    <?php    
  }

  ob_end_flush();
?>
