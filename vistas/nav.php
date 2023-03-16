<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="escritorio.php" class="nav-link"> <i class="fas fa-home"></i> <p class="d-inline-block hide-max-width-1080px">Home</p> </a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="#" data-toggle="modal" data-target="#modal-contacto-desarrollador" class="nav-link"><i class="fas fa-user-secret"></i> <p class="d-inline-block hide-max-width-1080px">Contacto</p></a>
    </li>

    <!-- <li class="nav-item b-radio-10px font-size-18px font-weight-bold mr-1" >
      <button  data-toggle="modal" data-target="#modal-para-todos-los-modulos-proyecto" class="nav-link btn bg-color-8eff27 b-radio-10px text-bold" id="ver-proyecto" onclick="delay(function(){tbla_principal_para_todos_los_modulos(3)}, 50 );"><i class="fas fa-tools"></i> Proyecto: <i class="fas fa-spinner fa-pulse "></i> </button>
    </li> -->

    <li class="nav-item d-none  d-md-inline-block b-radio-10px font-size-18px font-weight-bold bg-warning" >
      <a href="#" data-toggle="modal" data-target="#modal-ver---" class="nav-link" id="ver-sesion"><i class="fas fa-spinner fa-pulse "></i> </a>
    </li>
  </ul>
  <!-- <button></button> -->

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Navbar Search -->
    <li class="nav-item" style="display: none !important;">
      <a class="nav-link" data-widget="navbar-search" href="#" role="button">
        <i class="fas fa-search">Buscar</i>
      </a>
      <div class="navbar-search-block">
        <form class="form-inline">
          <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search" />
            <div class="input-group-append">
              <button class="btn btn-navbar" type="submit">
                <i class="fas fa-search"></i>
              </button>
              <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </li>

    <!-- Messages Dropdown Menu -->
    <li class="nav-item dropdown ">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <img src="../dist/docs/persona/perfil/<?php echo $_SESSION['imagen']; ?>" class="user-image img-circle" alt="User Image" width="30" onerror="this.src='../dist/svg/user_default.svg';"> 
             
        <span class="hidden-xs d-none show-min-width-1200px"><?php echo $_SESSION['nombre']; ?></span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <!-- Widget: user widget style 1 -->
        <div class="card card-widget widget-user">
          <!-- Add the bg color to the header using any of the bg-* classes -->
          <div class="widget-user-header bg-info">
            <h3 class="widget-user-username"><?php echo $_SESSION['nombre']; ?></h3>
            <h5 class="widget-user-desc"><?php echo $_SESSION['cargo']; ?></h5>
          </div>
          <div class="widget-user-image">
            <img class="img-circle elevation-2" src="../dist/docs/persona/perfil/<?php echo $_SESSION['imagen']; ?>" alt="User Avatar" onerror="this.src='../dist/svg/user_default.svg';" />
          </div>
          <div class="card-body pt-5">
            <span class="dropdown-item dropdown-header">Info personal</span>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-address-card"></i>
              <?php echo $_SESSION['tipo_documento']; ?>: <?php echo $_SESSION['num_documento']; ?>               
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-phone-alt"></i> Telefono: <?php echo $_SESSION['telefono']; ?>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item w-280px recorte-text">
              <i class="fas fa-envelope"></i> Email: <?php echo $_SESSION['email']; ?>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer"> <small>Más informacion cominicarse con el administrador </small>  </a>
            <!-- /.row -->
            <a href="../ajax/usuario.php?op=salir&file=<?php echo basename($_SERVER['PHP_SELF']); ?> " class="btn btn-danger btn-block">Cerrar sesion</a>
          </div>
          <div class="card-footer py-1 text-center ">
            <a class="hove-negrita" href="javascript:void(0)" onclick="abrir_calculadora()"><i class="fas fa-calculator"></i> Abrir calculadora</a>
          </div>
        </div>
        <!-- /.widget-user -->
        
      </div>
    </li>
    <!-- Notifications Dropdown Menu -->
    <li class="nav-item dropdown d-none d-sm-inline-block">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge">15</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">15 Notifications</span>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-envelope mr-2"></i> 4 new messages
          <span class="float-right text-muted text-sm">3 mins</span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-users mr-2"></i> 8 friend requests
          <span class="float-right text-muted text-sm">12 hours</span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-file mr-2"></i> 3 new reports
          <span class="float-right text-muted text-sm">2 days</span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
      </div>
    </li>

    <!-- Pantalla completa -->
    <li class="nav-item d-none d-sm-inline-block">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
    <!-- FIN Pantalla completa -->

    <!-- <li class="nav-item">
      <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
        <i class="fas fa-th-large"></i>
      </a>
    </li> -->
  </ul>
</nav>
<!-- /.navbar -->
