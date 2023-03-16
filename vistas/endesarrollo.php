<?php 
  $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' . $_SERVER["REQUEST_URI"]; 
?>

<!--Contenido-->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 nombre-trabajador"> <?php echo $title; ?></h1>
        </div>
        <!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">En desarrollo</li>
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

          <div class="error-page">
            <h2 class="headline text-danger">307</h2>

            <div class="error-content">
              <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Estamos en <b class="text-danger">Desarrollo</b>.</h3>

              <p>
                Estamos trabajando para que lo utilice de inmediato.
                Mientras tanto, puede <a href="escritorio.php">volver al panel de control</a> o intentar usar el formulario de búsqueda.
              </p>

              <form class="search-form">
                <div class="input-group">
                  <input type="text" name="search" class="form-control" placeholder="Search">

                  <div class="input-group-append">
                    <button type="submit" name="submit" class="btn btn-danger"><i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
                <!-- /.input-group -->
              </form>
            </div>
          </div>
          <!-- /.error-page -->

        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->             

              

  </section>
  <!-- /.content -->
</div>
<!--Fin-Contenido-->