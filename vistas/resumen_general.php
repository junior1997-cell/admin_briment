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
        <title>Admin JDL | Resumen General</title>

        <?php $title = "Resumen General"; require 'head.php'; ?>
          
      </head>
      <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
            require 'nav.php';
            require 'aside.php';
            if ($_SESSION['resumen_general']==1){
              //require 'enmantenimiento.php';
              ?>

              <!-- Content Wrapper. Contains page content -->
              <div class="content-wrapper" >
                <!-- Content Header (Page header) -->
                <section class="content-header">
                  <div class="container-fluid">
                    <div class="row mb-2">
                      <div class="col-sm-12 tex-center" style="font-weight: bold;">
                        <div class="row">
                          <div class="col-6 text-right">
                            <h1 style="font-weight: bold;">RESUMEN GENERAL</h1>
                          </div>
                          <div class="col-6">
                            <button class="btn btn-success btn-md /*export_all_table*/" style="font-weight: bold;">Exportar <i class="fas fa-file-excel"></i></button>
                          </div>
                        </div>
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
                        <div class="card card-primary card-outline" style="border: 2px solid #f60c;">
                          <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <div class="card-header" style="border: 1px solid #f60c !important; background-color: #f60c; color: #ffffff;">
                                <div class="row">

                                  <!-- filtro por: fecha -->
                                  <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
                                    <label for="filtros" class="mb-0" >Filtar por Fecha </label>
                                    <!-- fecha inicial -->
                                    <input name="fecha_filtro" id="fecha_filtro_1" type="date" class="form-control form-control-sm m-b-1px" placeholder="Seleccionar fecha" onchange="filtros()" />
                                  </div> 
                                  <!-- filtro por: fecha -->
                                  <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
                                    <label for="filtros" class="mb-0" >Filtar por Fecha </label>
                                    <!-- fecha final -->
                                    <input name="fecha_filtro" id="fecha_filtro_2" type="date" class="form-control form-control-sm" placeholder="Seleccionar fecha" onchange="filtros()" />
                                  </div>                                    

                                  <!-- filtro por: trabajador -->
                                  <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                    <label for="filtros" class="cargando_trabajador mb-0">Trabajador &nbsp;<i class="text-dark fas fa-spinner fa-pulse fa-lg"></i><br /></label>
                                    <select name="trabajador_filtro" id="trabajador_filtro" class="form-control select2" onchange="filtros()" style="width: 100%;"> 
                                    </select>
                                  </div>

                                  <!-- filtro por: proveedor -->
                                  <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                    <label for="filtros" class="cargando_proveedor mb-0">Proveedor &nbsp;<i class="text-dark fas fa-spinner fa-pulse fa-lg"></i><br /></label>
                                    <select name="proveedor_filtro" id="proveedor_filtro" class="form-control select2" onchange="filtros()" style="width: 100%;"> 
                                    </select>
                                  </div>

                                  <!-- filtro por: deuda - no deuda -->
                                  <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
                                    <label for="filtros" class="mb-0">Filtrar por.</label>
                                    <select name="deuda_filtro" id="deuda_filtro" class="form-control select2" onchange="filtros()" style="width: 100%;">
                                      <option value="todos">Todos</option>  
                                      <option value="sindeuda">Sin deuda</option>
                                      <option value="condeuda">Con deuda</option>
                                      <option value="conexcedente">Con excedente</option>
                                    </select>
                                  </div>

                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body row-vertica disenio-scroll h-700px">
                            
                            <div class="/*container*/">
                              <!--Compras-->
                              <table id="tabla1_compras" class="display" style="width: 100%;">
                                <thead>
                                  <tr>
                                    <th colspan="8" class="cargando-compras text-center w-300px clas_pading backgff9100">Compras de Insumos</th>
                                  </tr>
                                  <tr>
                                    <th class="text-center clas_pading">#</th>
                                    <th class="text-center w-300px clas_pading">PROVEEDOR</th>
                                    <th class="text-center clas_pading">FECHA</th>
                                    <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                    <th class="text-center clas_pading">DETALLE</th>
                                    <th class="text-center clas_pading">MONTOS</th>
                                    <th class="text-center clas_pading">PAGOS</th>
                                    <th class="text-center clas_pading">SALDOS</th>
                                  </tr>
                                </thead>

                                <tbody id="compras"></tbody>                                

                                <tfoot>
                                  <tr>
                                    <th colspan="4" class="clas_pading"></th>
                                    <th class="clas_pading text-right">Total</th>
                                    <th class="clas_pading text-right" id="monto_compras"><i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                    <th class="clas_pading text-right" id="pago_compras"><i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                    <th class="clas_pading text-right" id="saldo_compras"><i class="fas fa-spinner fa-pulse fa-sm"></i></th>
                                  </tr>
                                </tfoot>
                              </table>

                              <br />

                              <!--Servicios-Maquinaria-->
                              <table id="tabla2_maquinaria" class="display" style="width: 100%;">
                                <thead>
                                  <tr>
                                    <th colspan="8" class="cargando-maquinas text-center w-300px clas_pading backgff9100">Servicios-Maquinaria</th>
                                  </tr>
                                  <tr>
                                    <th class="text-center clas_pading">#</th>
                                    <th class="text-center w-300px clas_pading">PROVEEDOR</th>
                                    <th class="text-center clas_pading">FECHA</th>
                                    <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                    <th class="text-center clas_pading">DETALLE</th>
                                    <th class="text-center clas_pading">MONTOS</th>
                                    <th class="text-center clas_pading">PAGOS</th>
                                    <th class="text-center clas_pading">SALDOS</th>
                                  </tr>
                                </thead>

                                <tbody id="serv_maquinas"></tbody>

                                <tfoot>
                                  <tr>
                                    <th colspan="4" class="clas_pading"></th>
                                    <th class="clas_pading text-right">Total</th>
                                    <th class="clas_pading text-right" id="monto_serv_maq"></th>
                                    <th class="clas_pading text-right" id="pago_serv_maq"></th>
                                    <th class="clas_pading text-right" id="saldo_serv_maq"></th>
                                  </tr>
                                </tfoot>
                              </table>

                              <br />

                              <!--Servicios-Equipo-->
                              <table id="tabla3_equipo" class="display" style="width: 100%;">
                                <thead>
                                  <tr>
                                    <th colspan="8" class="cargando-equipos text-center w-300px clas_pading backgff9100">Servicios-Equipo</th>
                                  </tr>
                                  <tr>
                                    <th class="text-center clas_pading">#</th>
                                    <th class="text-center w-300px clas_pading">PROVEEDOR</th>
                                    <th class="text-center clas_pading">FECHA</th>
                                    <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                    <th class="text-center clas_pading">DETALLE</th>
                                    <th class="text-center clas_pading">MONTOS</th>
                                    <th class="text-center clas_pading">PAGOS</th>
                                    <th class="text-center clas_pading">SALDOS</th>
                                  </tr>
                                </thead>

                                <tbody id="serv_equipos"></tbody>                               

                                <tfoot>
                                  <tr>
                                    <th colspan="4" class="clas_pading"></th>
                                    <th class="clas_pading text-right">Total</th>
                                    <th class="clas_pading text-right" id="monto_serv_equi"></th>
                                    <th class="clas_pading text-right" id="pago_serv_equi"></th>
                                    <th class="clas_pading text-right" id="saldo_serv_equi"></th>
                                  </tr>
                                </tfoot>
                              </table>

                              <br />

                              <!--Transporte-->
                              <table id="tabla4_transporte" class="display" style="width: 100%;">
                                <thead>
                                  <tr>
                                    <th colspan="8" class="cargando-transporte text-center w-300px clas_pading backgff9100">Transporte</th>
                                  </tr>
                                  <tr>
                                    <th class="text-center clas_pading">#</th>
                                    <th class="text-center w-300px clas_pading">PROVEEDOR</th>
                                    <th class="text-center clas_pading">FECHA</th>
                                    <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                    <th class="text-center clas_pading">DETALLE</th>
                                    <th class="text-center clas_pading">MONTOS</th>
                                    <th class="text-center clas_pading">PAGOS</th>
                                    <th class="text-center clas_pading">SALDOS</th>
                                  </tr>
                                </thead>

                                <tbody id="transportes"></tbody>                               

                                <tfoot>
                                  <tr>
                                    <th colspan="4" class="clas_pading"></th>
                                    <th class="clas_pading text-right">Total</th>
                                    <th class="clas_pading text-right" id="monto_transp"></th>
                                    <th class="clas_pading text-right" id="pago_transp"></th>
                                    <th class="clas_pading text-right" id="saldo_transp"></th>
                                  </tr>
                                </tfoot>
                              </table>

                              <br />

                              <!--Hospedaje-->
                              <table id="tabla5_hospedaje" class="display" style="width: 100%;">
                                <thead>
                                  <tr>
                                    <th colspan="8" class="cargando-hospedaje text-center w-300px clas_pading backgff9100">Hospedaje</th>
                                  </tr>
                                  <tr>
                                    <th class="text-center clas_pading">#</th>
                                    <th class="text-center w-300px clas_pading">PROVEEDOR</th>
                                    <th class="text-center clas_pading">FECHA</th>
                                    <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                    <th class="text-center clas_pading">DETALLE</th>
                                    <th class="text-center clas_pading">MONTOS</th>
                                    <th class="text-center clas_pading">PAGOS</th>
                                    <th class="text-center clas_pading">SALDOS</th>
                                  </tr>
                                </thead>

                                <tbody id="hospedaje"></tbody>                                 

                                <tfoot>
                                  <tr>
                                    <th colspan="4" class="clas_pading"></th>
                                    <th class="clas_pading text-right">Total</th>
                                    <th class="clas_pading text-right" id="monto_hosped"></th>
                                    <th class="clas_pading text-right" id="pago_hosped"></th>
                                    <th class="clas_pading text-right" id="saldo_hosped"></th>
                                  </tr>
                                </tfoot>
                              </table>

                              <br />

                              <!--Comidas extras-->
                              <table id="tabla6_comidas_ex" class="display" style="width: 100%;">
                                <thead>
                                  <tr>
                                    <th colspan="8" class="cargando-comida-extra text-center w-300px clas_pading backgff9100">Comidas extras</th>
                                  </tr>
                                  <tr>
                                    <th class="text-center clas_pading">#</th>
                                    <th class="text-center w-300px clas_pading">PROVEEDOR</th>
                                    <th class="text-center clas_pading">FECHA</th>
                                    <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                    <th class="text-center clas_pading">DETALLE</th>
                                    <th class="text-center clas_pading">MONTOS</th>
                                    <th class="text-center clas_pading">PAGOS</th>
                                    <th class="text-center clas_pading">SALDOS</th>
                                  </tr>
                                </thead>

                                <tbody id="comida_extra"></tbody>
 
                                <tfoot>
                                  <tr>
                                    <th colspan="4" class="clas_pading"></th>
                                    <th class="clas_pading text-right">Total</th>
                                    <th class="clas_pading text-right" id="monto_cextra"></th>
                                    <th class="clas_pading text-right" id="pago_cextra"></th>
                                    <th class="clas_pading text-right" id="saldo_cextra"></th>
                                  </tr>
                                </tfoot>
                              </table>

                              <br />

                              <!--Breaks-->
                              <table id="tabla7_breaks" class="display" style="width: 100%;">
                                <thead>
                                  <tr>
                                    <th colspan="8" class="cargando-breaks text-center w-300px clas_pading backgff9100">Breaks</th>
                                  </tr>
                                  <tr>
                                    <th class="text-center clas_pading">#</th>
                                    <th class="text-center w-300px clas_pading">PROVEEDOR</th>
                                    <th class="text-center clas_pading">FECHA</th>
                                    <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                    <th class="text-center clas_pading">DETALLE</th>
                                    <th class="text-center clas_pading">MONTOS</th>
                                    <th class="text-center clas_pading">PAGOS</th>
                                    <th class="text-center clas_pading">SALDOS</th>
                                  </tr>
                                </thead>

                                <tbody id="breaks"></tbody>                               

                                <tfoot>
                                  <tr>
                                    <th colspan="4" class="clas_pading"></th>
                                    <th class="clas_pading text-right">Total</th>
                                    <th class="clas_pading text-right" id="monto_break"></th>
                                    <th class="clas_pading text-right" id="pago_break"></th>
                                    <th class="clas_pading text-right" id="saldo_break"></th>
                                  </tr>
                                </tfoot>
                              </table>

                              <br />

                              <!--Pensión-->
                              <table id="tabla8_pension" class="display" style="width: 100%;">
                                <thead>
                                  <tr>
                                    <th colspan="8" class="cargando-pension text-center w-300px clas_pading backgff9100">Pensión</th>
                                  </tr>
                                  <tr>
                                    <th class="text-center clas_pading">#</th>
                                    <th class="text-center w-300px clas_pading">PROVEEDOR</th>
                                    <th class="text-center clas_pading">FECHA</th>
                                    <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                    <th class="text-center clas_pading">DETALLE</th>
                                    <th class="text-center clas_pading">MONTOS</th>
                                    <th class="text-center clas_pading">PAGOS</th>
                                    <th class="text-center clas_pading">SALDOS</th>
                                  </tr>
                                </thead>

                                <tbody id="pension"></tbody>
 
                                <tfoot>
                                  <tr>
                                    <th colspan="4" class="clas_pading"></th>
                                    <th class="clas_pading text-right">Total</th>
                                    <th class="clas_pading text-right" id="monto_pension"></th>
                                    <th class="clas_pading text-right" id="pago_pension"></th>
                                    <th class="clas_pading text-right" id="saldo_pension"></th>
                                  </tr>
                                </tfoot>
                              </table>

                              <br />

                              <!--Personal Administrativo-->
                              <table id="tabla9_per_adm" class="display" style="width: 100%;">
                                <thead>
                                  <tr>
                                    <th colspan="8" class="cargando-administrativo text-center w-300px clas_pading backgff9100">Personal Administrativo</th>
                                  </tr>
                                  <tr>
                                    <th class="text-center clas_pading">#</th>
                                    <th class="text-center w-300px clas_pading">TRABAJADOR</th>
                                    <th class="text-center clas_pading">FECHA</th>
                                    <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                    <th class="text-center clas_pading">DETALLE</th>
                                    <th class="text-center clas_pading">MONTOS</th>
                                    <th class="text-center clas_pading">PAGOS</th>
                                    <th class="text-center clas_pading">SALDOS</th>
                                  </tr>
                                </thead>

                                <tbody id="administrativo"></tbody>

                                <tfoot>
                                  <tr>
                                    <th colspan="4" class="clas_pading"></th>
                                    <th class="clas_pading text-right">Total</th>
                                    <th class="clas_pading text-right" id="monto_adm"></th>
                                    <th class="clas_pading text-right" id="pago_adm"></th>
                                    <th class="clas_pading text-right" id="saldo_adm"></th>
                                  </tr>
                                </tfoot>
                              </table>

                              <br />

                              <!--Personal obrero-->
                              <table id="tabla10_per_obr" class="display" style="width: 100%;">
                                <thead>
                                  <tr>
                                    <th colspan="8" class="cargando-obrero text-center w-300px clas_pading backgff9100">Personal Obrero</th>
                                  </tr>
                                  <tr>
                                    <th class="text-center clas_pading">#</th>
                                    <th class="text-center w-300px clas_pading">TRABAJADOR</th>
                                    <th class="text-center clas_pading">FECHA</th>
                                    <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                    <th class="text-center clas_pading">DETALLE</th>
                                    <th class="text-center clas_pading">MONTOS</th>
                                    <th class="text-center clas_pading">PAGOS</th>
                                    <th class="text-center clas_pading">SALDOS</th>
                                  </tr>
                                </thead>

                                <tbody id="obrero"></tbody>

                                <tfoot>
                                  <tr>
                                    <th colspan="4" class="clas_pading"></th>
                                    <th class="clas_pading text-right">Total</th>
                                    <th class="clas_pading text-right" id="monto_obrero"></th>
                                    <th class="clas_pading text-right" id="pago_obrero"></th>
                                    <th class="clas_pading text-right" id="saldo_obrero"></th>
                                  </tr>
                                </tfoot>
                              </table>

                              <br>

                              <!-- Otros Gastos -->
                              <table id="tabla11_otros_gastos" class="display" style="width: 100%;">
                                <thead>
                                  <tr>
                                    <th colspan="8" class="cargando-otros-gastos text-center w-300px clas_pading backgff9100">Otros Gastos</th>
                                  </tr>
                                  <tr>
                                    <th class="text-center clas_pading">#</th>
                                    <th class="text-center w-300px clas_pading">PROVEEDOR</th>
                                    <th class="text-center clas_pading">FECHA</th>
                                    <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                    <th class="text-center clas_pading">DETALLE</th>
                                    <th class="text-center clas_pading">MONTOS</th>
                                    <th class="text-center clas_pading">PAGOS</th>
                                    <th class="text-center clas_pading">SALDOS</th>
                                  </tr>
                                </thead>

                                <tbody id="otros_gastos"></tbody>                                 

                                <tfoot>
                                  <tr>
                                    <th colspan="4" class="clas_pading"></th>
                                    <th class="clas_pading text-right">Total</th>
                                    <th class="clas_pading text-right" id="monto_otros_gastos"></th>
                                    <th class="clas_pading text-right" id="pago_otros_gastos"></th>
                                    <th class="clas_pading text-right" id="saldo_otros_gastos"></th>
                                  </tr>
                                </tfoot>
                              </table>

                              <br>

                              <!-- Sub Contrato -->
                              <table id="tabla12_sub_contrato" class="display" style="width: 100%;">
                                <thead>
                                  <tr>
                                    <th colspan="8" class="cargando-sub-contrato text-center w-300px clas_pading backgff9100">Sub Contrato</th>
                                  </tr>
                                  <tr>
                                    <th class="text-center clas_pading">#</th>
                                    <th class="text-center w-300px clas_pading">PROVEEDOR</th>
                                    <th class="text-center clas_pading">FECHA</th>
                                    <th class="text-center clas_pading">DESCRIPCIÓN</th>
                                    <th class="text-center clas_pading">DETALLE</th>
                                    <th class="text-center clas_pading">MONTOS</th>
                                    <th class="text-center clas_pading">PAGOS</th>
                                    <th class="text-center clas_pading">SALDOS</th>
                                  </tr>
                                </thead>

                                <tbody id="sub_contrato"></tbody>                                 

                                <tfoot>
                                  <tr>
                                    <th colspan="4" class="clas_pading"></th>
                                    <th class="clas_pading text-right">Total</th>
                                    <th class="clas_pading text-right" id="monto_sub_contrato"></th>
                                    <th class="clas_pading text-right" id="pago_sub_contrato"></th>
                                    <th class="clas_pading text-right" id="saldo_sub_contrato"></th>
                                  </tr>
                                </tfoot>
                              </table>

                              <br />

                              <!--SUMAS TOTALES-->
                              <table id="tabla20_all_sumas" class="display" style="width: 100%;">
                                <thead>
                                  <tr>
                                    <th colspan="8" class="cargando-sumas text-center w-300px clas_pading backgff9100">Sumas totales</th>
                                  </tr>
                                  <tr>
                                    <th class="text-center clas_pading">#</th>
                                    <th class="text-center w-300px clas_pading">---</th>
                                    <th class="text-center clas_pading">---</th>
                                    <th class="text-center clas_pading">---</th>
                                    <th class="text-center clas_pading">DETALLE</th>
                                    <th class="text-center clas_pading">MONTOS</th>
                                    <th class="text-center clas_pading">PAGOS</th>
                                    <th class="text-center clas_pading">SALDOS</th>
                                  </tr>
                                </thead>

                                <tbody id="tbody20_all_sumas">
                                  <tr>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>Compras de Insumos</td>
                                    <td class="text-right monto_compras_all">0.00</td>
                                    <td class="text-right pago_compras_all">0.00</td>
                                    <td class="text-right saldo_compras_all">0.00</td>
                                  </tr>
                                  <tr>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>Servicios-Maquinaria</td>
                                    <td class="text-right monto_serv_maq_all">0.00</td>
                                    <td class="text-right monto_serv_maq_all">0.00</td>
                                    <td class="text-right saldo_serv_maq_all">0.00</td>
                                  </tr>
                                  <tr>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>Servicios-Equipo</td>
                                    <td class="text-right monto_serv_equi_all">0.00</td>
                                    <td class="text-right pago_serv_equi_all">0.00</td>
                                    <td class="text-right saldo_serv_equi_all">0.00</td>
                                  </tr>
                                  <tr>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>Transporte</td>
                                    <td class="text-right monto_transp_all">0.00</td>
                                    <td class="text-right pago_transp_all">0.00</td>
                                    <td class="text-right saldo_transp_all">0.00</td>
                                  </tr>
                                  <tr>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>Hospedaje</td>
                                    <td class="text-right monto_hosped_all">0.00</td>
                                    <td class="text-right pago_hosped_all">0.00</td>
                                    <td class="text-right saldo_hosped_all">0.00</td>
                                  </tr>
                                  <tr>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>Comidas extras</td>
                                    <td class="text-right monto_cextra_all">0.00</td>
                                    <td class="text-right pago_cextra_all">0.00</td>
                                    <td class="text-right saldo_cextra_all">0.00</td>
                                  </tr>
                                  <tr>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>Breaks</td>
                                    <td class="text-right monto_break_all">0.00</td>
                                    <td class="text-right pago_break_all">0.00</td>
                                    <td class="text-right saldo_break_all">0.00</td>
                                  </tr>
                                  <tr>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>Pensión</td>
                                    <td class="text-right monto_pension_all">0.00</td>
                                    <td class="text-right pago_pension_all">0.00</td>
                                    <td class="text-right saldo_pension_all">0.00</td>
                                  </tr>
                                  <tr>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>Personal Administrativo</td>
                                    <td class="text-right monto_adm_all">0.00</td>
                                    <td class="text-right pago_adm_all">0.00</td>
                                    <td class="text-right saldo_adm_all">0.00</td>
                                  </tr>
                                  <tr>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>Personal Obrero</td>
                                    <td class="text-right monto_obrero_all">0.00</td>
                                    <td class="text-right pago_obrero_all">0.00</td>
                                    <td class="text-right saldo_obrero_all">0.00</td>
                                  </tr>
                                  <tr>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>Otros Gastos</td>
                                    <td class="text-right monto_otros_gastos_all">0.00</td>
                                    <td class="text-right pago_otros_gastos_all">0.00</td>
                                    <td class="text-right saldo_otros_gastos_all">0.00</td>
                                  </tr>
                                  <tr>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>Sub Contrato</td>
                                    <td class="text-right monto_sub_contrato_all">0.00</td>
                                    <td class="text-right pago_sub_contrato_all">0.00</td>
                                    <td class="text-right saldo_sub_contrato_all">0.00</td>
                                  </tr>
                                </tbody>

                                <tfoot>
                                  <tr>
                                    <th colspan="4" class=""></th>
                                    <th class="celda-b-t-1px text-right p-r-10px">Total</th>
                                    <th class="celda-b-t-1px text-right p-r-10px" id="monto_all">500</th>
                                    <th class="celda-b-t-1px text-right p-r-10px" id="deposito_all">800</th>
                                    <th class="celda-b-t-1px text-right p-r-10px" id="saldo_all">9000</th>
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

                  <!--MODAL DETALLE - COMPRAS-->
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
                          <div class="row detalles_compra" id="cargando-1-fomulario">
                            
                            <!--detalle de la compra-->
                                                         
                          </div>
                          <div class="row" id="cargando-2-fomulario" style="display: none;">
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

                  <!-- MODAL DETALLE - MAQUINARIA EQUIPO -->
                  <div class="modal fade" id="modal_ver_detalle_maq_equ">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title"><span id="detalle_"></span> <b id="nombre_proveedor_"></b></h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div> 

                        <div class="modal-body">
                          <!--la tabla-->
                          <table id="tabla-detalle-m" class="table table-bordered table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th>Fecha</th>
                                <th>Unidad M.</th>
                                <th>Cantidad</th>
                                <th>Costo Unitario</th>
                                <th>Costo Parcial</th>
                                <th>Descripción</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th>Fecha</th>
                                <th>Unidad M.</th>
                                <th>Cantidad</th>
                                <th>Costo Unitario</th>
                                <th>Costo Parcial</th>
                                <th>Descripción</th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- MODAL DETALLE - BREAKS -->
                  <div class="modal fade" id="modal_ver_breaks">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title"><b>BREAKS:</b></h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>

                        <div class="modal-body">
                          <!--la tabla-->
                          <table id="t-comprobantes" class="table table-bordered table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th data-toggle="tooltip" data-original-title="Forma Pago">Forma P.</th>
                                <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Tipo</th>
                                <th data-toggle="tooltip" data-original-title="Número Comprobante">Número</th>
                                <th data-toggle="tooltip" data-original-title="Fecha Emisión">Fecha</th>
                                <th>Sub total</th>
                                <th>IGV</th>
                                <th>Monto</th>
                                <th>Descripción</th>
                                <th>Comprobante</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th data-toggle="tooltip" data-original-title="Forma Pago">Forma P.</th>
                                <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Tipo</th>
                                <th data-toggle="tooltip" data-original-title="Número Comprobante">Número</th>
                                <th data-toggle="tooltip" data-original-title="Fecha Emisión">Fecha</th>
                                <th>Sub total</th>
                                <th>IGV</th>
                                <th>Monto</th>
                                <th>Descripción</th>
                                <th>Comprobante</th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!--MODAL DETALLE - PENSION-->
                  <div class="modal fade" id="modal-ver-detalle-semana">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Detalles por semana</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <div class="class-style" style="text-align: center;">
                            <table id="tabla-detalles-semanal" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Descripcion</th>
                                  <th>Fecha</th>
                                  <th>Cantidad</th>
                                  <th>Monto</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Descripcion</th>
                                  <th>Fecha</th>
                                  <th>Cantidad</th>
                                  <th>Monto</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!--MODAL COMPROBANTES - PENSION-->
                  <div class="modal fade" id="modal-ver-comprobantes_pension">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Comprobantes</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <div class="class-style" style="text-align: center;">
                            <table id="t-comprobantes-pension" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th data-toggle="tooltip" data-original-title="Forma de pago">Forma</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Factura</th>
                                  <th data-toggle="tooltip" data-original-title="Fecha Emisión">F. Emisión</th>
                                  <th>Sub total</th>
                                  <th>IGV</th>
                                  <th>Monto</th>
                                  <th>Descripción</th>
                                  <th>CFDI.</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th data-toggle="tooltip" data-original-title="Forma de pago">Forma</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Factura</th>
                                  <th data-toggle="tooltip" data-original-title="Fecha Emisión">F. Emisión</th>
                                  <th>Sub total</th>
                                  <th>IGV</th>
                                  <th>Monto</th>
                                  <th>Descripción</th>
                                  <th>CFDI.</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!--MODAL DETALLE - ADMINISTRADOR-->
                  <div class="modal fade" id="modal-ver-detalle-t-administ">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Detalles: <b id="nombre_trabajador_detalle"></b></h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">

                          <div class="class-style tabla" id="cargando-3-fomulario" style="text-align: center;">
                            <div class="table-responsive" id="tbl-fechas">
                              <div class="table-responsive-lg">
                                <table class="table styletabla table-hover text-nowrap" style="border: black 1px solid;">
                                  <thead>
                                    <tr class="bg-gradient-info">
                                      <th class="stile-celda">N°</th>
                                      <th class="stile-celda">Mes</th>
                                      <th colspan="2" class="stile-celda">Fechas Inicial/Final</th>
                                      <th class="stile-celda text-center">Días laborables</th>
                                      <th class="stile-celda text-center">Sueldo estimado</th>
                                      <th class="stile-celda">Depósitos</th>
                                    </tr>
                                  </thead>
                                  <tbody class="tcuerpo data-detalle-pagos-administador">
                                    <!--deatlle de los pagos adm-->
                                    <tr>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td class="text-right sueldo_estimado"></td>
                                      <td class="text-right depositos"></td>
                                    </tr>
                                  </tbody>
                                  <tfoot class="">
                                    <tr>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th class="stile-celda-right sueldo_estimado"></th>
                                      <th class="stile-celda-right depositos"></th>
                                    </tr>
                                  </tfoot>
                                </table>
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

                          <div style="display: none;" class="alerta">
                            <div class="alert alert-warning alert-dismissible">
                              <h5><i class="icon fas fa-exclamation-triangle fa-3x text-white"></i> <b>No hay pagos!</b></h5>
                              No hay detalles de pagos para mostrar, puede registrar pagos en el módulo <b>pagos trabajador.</b>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!--MODAL DETALLE - OBRERO-->
                  <div class="modal fade" id="modal-ver-detalle-t-obrero">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Detalles: <b id="nombre_trabajador_ob_detalle"></b></h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">

                          <div class="class-style " id="cargando-5-fomulario">
                            <div class="table-responsive tabla_obrero" >
                              <div class="row-horizon disenio-scroll">
                                <table class="table styletabla table-hover text-nowrap" style="border: black 1px solid;">
                                  <thead>
                                    <tr class="bg-gradient-info">
                                      <th rowspan="2" class="stile-celda">N°</th>
                                      <th colspan="3" class="stile-celda pt-0 pb-0 nombre-bloque-asistencia"><b> Quincena </b></th>
                                      <th rowspan="2" class="stile-celda text-center">Sueldo <br> Hora</th>
                                      <th rowspan="2" class="stile-celda text-center">Horas Normal/Extra</th>
                                      <th rowspan="2" class="stile-celda text-center">Sab.</th>
                                      <th rowspan="2" class="stile-celda">Monto Normal/Extra</th>
                                      <th rowspan="2" class="stile-celda text-center">Adicional</th>
                                      <th rowspan="2" class="stile-celda">Monto total</th>
                                      <th rowspan="2" class="stile-celda">Deposito</th>
                                      <th rowspan="2" class="stile-celda">Saldo</th>
                                    </tr>
                                    <tr class="bg-gradient-info">
                                      <th class="stile-celda pt-0 pb-0">N°</th>
                                      <th class="stile-celda pt-0 pb-0">Inicial</th>
                                      <th class="stile-celda pt-0 pb-0">Final</th>
                                    </tr>
                                  </thead>
                                  <tbody class="tcuerpo detalle-data-q-s"></tbody>
                                  <tfoot>
                                    <tr>
                                      <th colspan="5"></th>
                                      <th class="stile-celda total_hn_he"></th>
                                      <th class="stile-celda total_sabatical"></th>
                                      <th class="stile-celda total_monto_hn_he"></th>
                                      <th class="stile-celda-right total_descuento"></th>
                                      <th class="stile-celda-right total_quincena"></th>
                                      <th class="stile-celda-right total_deposito"></th>
                                      <th class="stile-celda-right total_saldo"></th>
                                    </tr>
                                  </tfoot>
                                </table>
                              </div>
                            </div>

                            <div style="display: none;" class="alerta_obrero">
                              <div class="alert alert-warning alert-dismissible">
                                <h5><i class="icon fas fa-exclamation-triangle fa-3x text-white"></i> <b>No hay pagos!</b></h5>
                                No hay detalles de pagos para mostrar, puede registrar pagos en el módulo <b>pagos trabajador.</b>
                              </div>
                            </div>
                          </div>

                          <div class="row" id="cargando-6-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
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

        <style>
          table.colapsado {
            border-collapse: collapse;
          }
          .clas_pading {
            padding: 0.2rem 0.75rem 0.2rem 0.75rem !important;
          }
          .backgff9100 {
            background-color: #ffe300;
          }
          .colorf0f8ff00 {
            color: #f0f8ff00;
          }          
          .bg-red-resumen {
            background-color: #ff2036 !important;
            color: #ffffff !important;
          }
        </style>

        <style>
          .class-style label{
            font-size: 14px;
          }
          .class-style small {
            background-color: #f4f7ee;
            border: solid 1px #ce542a21;
            margin-left: 3px;
            padding: 5px;
            border-radius: 6px;
          }
        </style>

        <!-- table export -->
        <!-- <script src="../plugins/table_export_jquery/libs/FileSaver/FileSaver.min.js"></script>
        <script src="../plugins/table_export_jquery/libs/js-xlsx/xlsx.core.min.js"></script>
        <script src="../plugins/table_export_jquery/libs/html2canvas/html2canvas.min.js"></script>
        <script src="../plugins/table_export_jquery/tableExport.min.js"></script> -->

        <!-- table export EXCEL -->
        <script src="../plugins/export-xlsx/xlsx.full.min.js"></script>
        <script src="../plugins/export-xlsx/FileSaver.min.js"></script>
        <script src="../plugins/export-xlsx/tableexport.min.js"></script>

        <!-- <script type="text/javascript" src="scripts/moment.min.js"></script>-->
        <script type="text/javascript" src="scripts/resumen_general.js"></script>

        <script>  $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
