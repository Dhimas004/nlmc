<?php
require '../function.php';
?>
<!DOCTYPE html>
<html>

<head>
  <?php include '../include_user/css.php'; ?>
  <title>Dashboard</title>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <?php include '../include_user/navbar.php'; ?>

    <?php include '../include_user/sidebar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm">
              <h1 class="m-0 text-dark">Dashboard</h1>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row my-2">
            <div class="col-lg-3">
              <div class="card">
                <div class="card-body">
                  <h5><i class="fas fa-fw fa-theater-masks"></i> Kategori Produk</h5>
                  <h6 class="mb-2 text-muted">Jumlah Kategori: <?= $jumlah_kategori; ?></h6>
                  <a href="kategori_produk.php" class="card-link btn btn-primary"><i class="fas fa-fw fa-align-justify"></i></a>
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="card">
                <div class="card-body">
                  <h5><i class="fas fa-fw fa-book"></i> Produk</h5>
                  <h6 class="mb-2 text-muted">Jumlah Produk: <?= $jumlah_produk; ?></h6>
                  <a href="produk.php" class="card-link btn btn-primary"><i class="fas fa-fw fa-align-justify"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- /.content -->
    </div>

  </div>
  <script>
    $(document).ready(function() {
      $('#nav-dashboard').addClass('active');
      $('#navbar-dashboard').addClass('active');
    })
  </script>
</body>

</html>