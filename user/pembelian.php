<?php
require '../function.php';
?>
<!DOCTYPE html>
<html>

<head>
  <?php include '../include_user/css.php'; ?>
  <title>Riwayat</title>
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
              <h1 class="m-0 text-dark">Pembelian</h1>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg">
              <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="table_id">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Username</th>
                      <th>Tindakan</th>
                      <th>Tanggal</th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- ./wrapper -->
  <script>
    $(document).ready(function() {
      $('#nav-pembelian').addClass('active')
    })
  </script>
</body>

</html>