<?php
require '../function.php';
checkLogin();
$riwayat = mysqli_query($koneksi, "SELECT * FROM tb_riwayat INNER JOIN tb_admin ON tb_riwayat.id_user = tb_admin.id_user ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html>

<head>
  <?php include '../include_admin/css.php'; ?>
  <title>Riwayat</title>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <?php include '../include_admin/navbar.php'; ?>

    <?php include '../include_admin/sidebar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm">
              <h1 class="m-0 text-dark">Riwayat</h1>
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
                    <?php $i = 1; ?>
                    <?php foreach ($riwayat as $dr) : ?>
                      <tr>
                        <td>
                          <center><?= $i++; ?></center>
                        </td>
                        <td><?= $dr['username']; ?></td>
                        <td><?= $dr['tindakan']; ?></td>
                        <td><?= date('d-m-Y, H:i:s', $dr['tanggal']); ?></td>
                      </tr>
                    <?php endforeach ?>
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
      $('#nav-riwayat').addClass('active')
    })
  </script>
</body>

</html>