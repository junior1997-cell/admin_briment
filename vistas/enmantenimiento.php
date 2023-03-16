
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1> <?php echo $title; ?> </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">307 En mantenimiento</li>
          </ol>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="error-page"  >
      <h2 class="headline text-success">307</h2>

      <div class="error-content m-l-150px">
        <h3><i class="fas fa-exclamation-triangle text-success"></i> ¡Hola! Estamos en <b>Mantenimiento</b>.</h3>

        <p>Estamos actualizando el <b>sistema</b> para una mejor experiencia. Puedes <a href="escritorio.php">regresar al inicio</a> o comunicate con el <a href="#" data-toggle="modal" data-target="#modal-contacto-desarrollador" ><i class="fas fa-user-secret"></i> administrador</a>  de este sistema.</p>

        <form class="search-form">
          <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search" />

            <div class="input-group-append">
              <button type="submit" name="submit" class="btn btn-success"><i class="fas fa-search"></i></button>
            </div>
          </div>
          <!-- /.input-group -->
        </form>
      </div>
      <!-- /.error-content -->
    </div>
    <!-- /.error-page -->
  </section>
  <!-- /.content -->
</div>
