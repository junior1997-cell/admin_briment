<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="escritorio.php" class="brand-link">
    <img src="../dist/svg/logo-short-jdl.svg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: 0.8;" />
    <span class="brand-text font-weight-light">Admin JDL</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar"> 
    <!-- Sidebar user panel (optional) -->
    <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="../dist/svg/empresa-logo.svg" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">Construccion del baño portodoloque parte de no se</a>
      </div>
    </div>     -->

    <!-- SidebarSearch Form -->
    <div class="form-inline mt-4">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search" />
        <div class="input-group-append"><button class="btn btn-sidebar"><i class="fas fa-search fa-fw"></i></button></div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column /*nav-flat*/" data-widget="treeview" role="menu" data-accordion="false">
        <!-- MANUAL DE USUARIO -->
        <!-- <li class="nav-item">
          <a href="manual_de_usuario.php" class="nav-link pl-2" id="mManualDeUsuario">
            <i class="nav-icon fas fa-book"></i>
            <p>
              Manual de Usuario
              <span class="right badge badge-success">new</span>
            </p>
          </a>
        </li> -->
        <?php if ($_SESSION['escritorio']==1) {  ?>
          <!-- ESCRITORIO -->
          <li class="nav-item">
            <a href="escritorio.php" class="nav-link pl-2" id="mEscritorio">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Escritorio
                <span class="right badge badge-danger">Home</span>
              </p>
            </a>
          </li>
        <?php  }  ?>

        <?php if ($_SESSION['acceso']==1) {  ?>
          <!-- ACCESOS -->
          <li class="nav-item  b-radio-3px" id="bloc_Accesos">
            <a href="#" class="nav-link pl-2" id="mAccesos">
              <i class="nav-icon fas fa-shield-alt"></i>
              <p>
                Accesos
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">2</span>
              </p>
            </a>
            <ul class="nav nav-treeview ">
              <!-- Usuarios del sistema -->
              <li class="nav-item ">
                <a href="usuario.php" class="nav-link " id="lUsuario">
                  <i class="nav-icon fas fa-users-cog"></i>
                  <p>Usuarios</p>
                </a>
              </li>
              <!-- Permisos de los usuarios del sistema -->
              <li class="nav-item ">
                <a href="permiso.php" class="nav-link" id="lPermiso">
                  <i class="nav-icon fas fa-lock"></i>
                  <p>Permisos</p>
                </a>
              </li>      
            </ul>
          </li>
        <?php  }  ?>


        <?php if ($_SESSION['recurso']==1) {  ?>
          <!-- Recursos -->
          <li class="nav-item  b-radio-3px" id="bloc_Recurso">
            <a href="#" class="nav-link pl-2" id="mRecurso">
              <i class="nav-icon fas fa-project-diagram"></i>
              <p>
                Recursos <i class="fas fa-angle-left right"></i> <span class="badge badge-info right">6</span>
              </p>
            </a>
            <ul class="nav nav-treeview ">

              <!-- Usuarios del sistema -->
              <!-- <li class="nav-item ">
                <a href="trabajador.php" class="nav-link" id="lTrabajador">
                  <i class="nav-icon fas fa-users"></i>
                  <p>Trabajador</p>
                </a>
              </li> -->

              <!-- Proveedores y clientes de la empresa -->
              <li class="nav-item ">
                <a href="persona.php" class="nav-link" id="lClienteProveedor">
                  <i class="nav-icon fas fa-users"></i>
                  <p>Personas </p>
                </a>
              </li>  
              
              <!-- Producto para la empresa -->
              <li class="nav-item ">
                <a href="producto.php" class="nav-link" id="lAllProducto">                  
                  <img src="../dist/svg/plomo-abono-ico.svg" class="nav-icon lAllProducto-img" alt="" style="width: 21px !important;" >
                  <p>Producto</p>
                </a>
              </li>              
              
              <!-- Datos Generales Bancos y color -->
              <li class="nav-item ">
                <a href="otros.php" class="nav-link" id="lOtros">
                  <i class="nav-icon fas fa-coins"></i>
                  <p>Otros</p>
                </a>
              </li>
            </ul>
          </li>
        <?php  }  ?> 
        
        <?php if ($_SESSION['papelera']==1) {  ?>
          <li class="nav-item">
            <a href="papelera.php" class="nav-link pl-2" id="mPapelera">
              <i class="nav-icon fas fa-trash-alt"></i>
              <p>Papelera</p>
            </a>
          </li>
        <?php  }  ?>
        
        <li class="nav-header">MÓDULOS</li>
        
        <!-- LOGÍSTICA Y ADQUISICIONES -->      
        <li class="nav-item " id="bloc_LogisticaAdquisiciones">
          <a href="#" class="nav-link bg-color-2c2c2c" id="mLogisticaAdquisiciones" style="padding-left: 7px;">
            <i class="nav-icon far fa-circle"></i>
            <p class="font-size-14px">LOGÍSTICA Y ADQUISICIONES <i class="fas fa-angle-left right"></i></p>
          </a>
          <ul class="nav nav-treeview">

            <?php if ($_SESSION['almacen_abono']==1) {  ?>   
              <!-- COMPRAS -->      
              <li class="nav-item  b-radio-3px" id="bloc_Compras">
                <a href="#" class="nav-link pl-2" id="mCompra">
                <i class="fa-solid fa-boxes-stacked"></i>
                  <p>Almacén Abono <i class="fas fa-angle-left right"></i> <span class="badge badge-info right">3</span></p>
                </a>
                <ul class="nav nav-treeview">
                  <!-- Compras producto -->
                  <li class="nav-item ">
                    <a href="compra_producto.php" class="nav-link" id="lCompras">
                      <i class="nav-icon fas fa-cart-plus"></i> <p>Compras Producto</p>
                    </a>
                  </li>
                  <!-- Resumend de Productos -->
                  <li class="nav-item ">
                    <a href="resumen_compra_producto.php" class="nav-link" id="lResumenProducto">
                      <i class="nav-icon fas fa-tasks"></i> <p>Resumen de Productos</p>
                    </a>
                  </li> 
                  
                  <!-- Graficos Productos -->
                  <li class="nav-item ">
                    <a href="chart_compra_producto.php" class="nav-link" id="lChartCompraProducto">
                      <i class="nav-icon fas fa-chart-line"></i> <p>Gráficos</p>
                    </a>
                  </li> 
                </ul>
              </li>
            <?php  }  ?>  

            <?php if ($_SESSION['venta_abono']==1) {  ?>   
              <!-- Ventas -->      
              <li class="nav-item  b-radio-3px" id="bloc_Ventas">
                <a href="#" class="nav-link pl-2" id="mVentas">
                  <i class="fas fa-shopping-cart nav-icon"></i>
                  <p>Venta Abonos <i class="fas fa-angle-left right"></i> <span class="badge badge-info right">3</span></p>
                </a>
                <ul class="nav nav-treeview">
                  <!-- Ventas Producto -->
                  <li class="nav-item ">
                    <a href="venta_producto.php" class="nav-link" id="lVentasProductos">
                      <i class="nav-icon fas fa-cart-plus"></i> <p>Ventas Producto</p>
                    </a>
                  </li>
                  <!-- Resumend de Producto -->
                  <li class="nav-item ">
                    <a href="resumen_venta_producto.php" class="nav-link" id="lResumenVentasProductos">
                      <i class="nav-icon fas fa-tasks"></i> <p>Resumen de Productos</p>
                    </a>
                  </li> 
                  
                  <!-- Graficos Producto -->
                  <li class="nav-item ">
                    <a href="chart_venta_producto.php" class="nav-link" id="lChartVentaProducto">
                      <i class="nav-icon fas fa-chart-line"></i> <p>Gráficos</p>
                    </a>
                  </li> 
                </ul>
              </li>
            <?php  }  ?>      
            
            <?php if ($_SESSION['compra_grano']==1) {  ?>   
              <!-- COMPRAS -->      
              <li class="nav-item  b-radio-3px" id="bloc_ComprasGrano">
                <a href="#" class="nav-link pl-2" id="mCompraGrano">
                  <img src="../dist/svg/plomo-grano-cafe-ico.svg" class="nav-icon lComprasGrano-img" alt="" style="width: 21px !important;" >
                  <p>Compras Café <i class="fas fa-angle-left right"></i> <span class="badge badge-info right">2</span></p>
                </a>
                <ul class="nav nav-treeview">
                  <!-- Compras del proyecto -->
                  <li class="nav-item ">
                    <a href="compra_cafe.php" class="nav-link" id="lComprasGrano">
                      <i class="nav-icon fas fa-cart-plus"></i> 
                      <p>Compras</p>
                    </a>
                  </li>       
                  <li class="nav-item ">
                    <a href="compra_cafe_v2.php" class="nav-link" id="lComprasGranoV2">
                      <i class="nav-icon fas fa-cart-plus"></i> 
                      <p>Compras v2 <span class="right badge badge-danger">Nuevo</span></p>
                    </a>
                  </li>                  
                  
                  <!-- graficos insumos -->
                  <li class="nav-item ">
                    <a href="chart_compra_grano.php" class="nav-link" id="lChartCompraGrano">
                      <i class="nav-icon fas fa-chart-line"></i> <p>Gráficos</p>
                    </a>
                  </li> 
                </ul>
              </li>
            <?php  }  ?>             

            <?php /* if ($_SESSION['otro_gasto']==1) { */ ?>
              <!-- OTROS GASTOS -->       
              <!-- <li class="nav-item ">
                <a href="otro_gasto.php" class="nav-link pl-2" id="lOtroGasto">
                  <i class="nav-icon fas fa-network-wired"></i>
                  <p>Otros Gastos </p>
                </a>
              </li> -->
            <?php /* } */ ?>            
            
          </ul>
        </li> 

        <!-- CONTABLE Y FINANCIERO -->   
        <li class="nav-item " id="bloc_ContableFinanciero">
          <a href="#" class="nav-link bg-color-2c2c2c" id="mContableFinanciero" style="padding-left: 7px;">
            <i class="nav-icon far fa-circle"></i>
            <p class="font-size-14px">CONTABLE Y FINANCIERO<i class="fas fa-angle-left right"></i></p>
          </a>
          <ul class="nav nav-treeview">

            <?php if ($_SESSION['pago_trabajador']==1) {  ?>
              <!-- RESUMEN DE GASTOS -->
              <li class="nav-item ">
                <a href="pago_trabajador.php" class="nav-link pl-2" id="lPagoTrabajador">
                  <i class="fas fa-dollar-sign nav-icon"></i>
                  <p>Pago Trabajador</p>
                </a>
              </li>
            <?php  }  ?>

            <?php if ($_SESSION['otro_ingreso']==1) {  ?>
              <li class="nav-item ">
                <a href="otro_ingreso.php" class="nav-link pl-2" id="lOtroIngreso">             
                  <i class="nav-icon fas fa-hand-holding-usd"></i>
                  <p>Otro ingreso </p>
                </a>
              </li>
            <?php  }  ?>
            
          </ul>
        </li>

      </ul>      
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
