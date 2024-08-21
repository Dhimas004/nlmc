<?php
require '../function.php';
checkLogin();
$riwayat = mysqli_query($koneksi, "SELECT * FROM tb_penjualan ORDER BY tanggal_penjualan DESC");

if (isset($_GET['status']) && isset($_GET['id_penjualan'])) {
  $id_penjualan = $_GET['id_penjualan'];
  $status = $_GET['status'];

  mysqli_query($koneksi, "UPDATE tb_penjualan SET status_penjualan = '$status' WHERE id_penjualan = '$id_penjualan'");
  setAlert("Update Status", "Berhasil Update Status", "success");
  echo "<script>window.location.href='riwayat_penjualan.php'</script>";
}

if (isset($_GET['setujui_pembayaran'])) {
  $id_penjualan = $_GET['setujui_pembayaran'];
  $username = $_SESSION['username'];
  mysqli_query($koneksi, "UPDATE tb_penjualan SET user_approval_pembayaran = '$username', timestamp_approval_pembayaran = NOW(), status_penjualan = 'selesai' WHERE id_penjualan = '$id_penjualan'");
  setAlert("Update Status Pembayaran", "Berhasil Menyetujui Pembayaran", "success");
  echo "<script>window.location.href='riwayat_penjualan.php'</script>";
}
if (isset($_GET['tolak_pembayaran'])) {
  $id_penjualan = $_GET['tolak_pembayaran'];
  $username = $_SESSION['username'];
  mysqli_query($koneksi, "UPDATE tb_penjualan SET user_approval_pembayaran = '$username', timestamp_approval_pembayaran = NOW(), status_penjualan = 'tolak' WHERE id_penjualan = '$id_penjualan'");
  setAlert("Update Status Pembayaran", "Berhasil Menolak Pembayaran", "success");
  echo "<script>window.location.href='riwayat_penjualan.php'</script>";
}
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
              <h1 class="m-0 text-dark">Riwayat Penjualan</h1>
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
                <table class="table table-bordered table-hover table-striped" id="table_id" style="font-size: 12px;">
                  <thead>
                    <tr>
                      <th style="vertical-align: middle; text-align: center;">No.</th>
                      <th style="vertical-align: middle; text-align: center;">No. Transaksi</th>
                      <th style="vertical-align: middle; text-align: center;">Status Barang</th>
                      <th style="vertical-align: middle; text-align: center;">Barang</th>
                      <th style="vertical-align: middle; text-align: center;">Nama Pelanggan</th>
                      <th style="vertical-align: middle; text-align: center;">Alamat Pelanggan</th>
                      <th style="vertical-align: middle; text-align: center;">Metode Pembayaran</th>
                      <th style="vertical-align: middle; text-align: center;">Total Pembayaran</th>
                      <th style="vertical-align: middle; text-align: center;">Tanggal Penjualan</th>
                      <th style="vertical-align: middle; text-align: center;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $i = 1; ?>
                    <?php
                    while ($data = mysqli_fetch_assoc($riwayat)) :
                      $status_penjualan = $data['status_penjualan'];
                      $no_transaksi = $data['no_transaksi'];
                      $metode_pembayaran = ucwords(str_replace('_', ' ', $data['metode_pembayaran']));
                      $id_penjualan = $data['id_penjualan'];
                      $user_approval_pembayaran = $data['user_approval_pembayaran'];
                      $status_pembayaran = "Pembayaran Belum Dicek";
                      if ($user_approval_pembayaran != '') {
                        if ($status_penjualan == 'selesai') {
                          $status_pembayaran = "Pembayaran Disetujui";
                        } else {
                          $status_pembayaran = "Pembayaran Ditolak";
                        }
                      }
                      $list_detail_penjualan = mysqli_query($koneksi, "SELECT * FROM tb_detail_penjualan WHERE id_penjualan = '$id_penjualan'");
                      while ($data2 = mysqli_fetch_assoc($list_detail_penjualan)) {
                        $total_pembayaran += $data2['qty'] * $data2['harga'];
                      }

                      $status_text_color = "";
                      $status_badge = "";

                      if ($status_penjualan == 'proses') {
                        $status_text_color = "text-white";
                        $status_badge = "badge-primary";
                      } else if ($status_penjualan == 'selesai') {
                        $status_text_color = "text-white";
                        $status_badge = "badge-success";
                      }
                    ?>
                      <tr>
                        <td>
                          <center><?= $i++; ?></center>
                        </td>
                        <td align="center"><?= $no_transaksi ?></td>
                        <td align="center">
                          <?php
                          if ($status_pembayaran == 'Pembayaran Belum Dicek' || $status_pembayaran == 'Pembayaran Ditolak') {
                            echo "<span style='color: red'>$status_pembayaran</span>";
                          } else {
                            echo "<span style='color: green'>$status_pembayaran</span>";
                          }
                          ?>
                        </td>
                        <td align="center"><a href="#" data-toggle="modal" data-target="#modal-detail-barang-<?= $id_penjualan ?>">Detail Barang</a></td>
                        <td style="text-align: left;"><?= $data['nama_pelanggan']; ?></td>
                        <td style="text-align: left;"><?= $data['alamat_pelanggan']; ?></td>
                        <td align="center">
                          <?php
                          if ($metode_pembayaran == 'Bank Bca') {
                            echo "Bank BCA";
                          } else {
                            echo $metode_pembayaran;
                          } ?>
                        </td>
                        <td align="right">
                          Rp. <?= number_format($total_pembayaran, 0, ',', '.'); ?>
                          <br />
                          <a href="#" data-toggle="modal" data-target="#modal-bukti-pembayaran-<?= $id_penjualan ?>">Lihat Bukti Pembayaran</a>
                        </td>
                        <td><?= date('d-m-Y H:i:s', strtotime($data['tanggal_penjualan'])); ?></td>
                        <td align="center"><a href="print_laporan.php?id_penjualan=<?= $id_penjualan ?>" class="btn btn-primary" target="_blank">Print Laporan</a></td>
                      </tr>
                    <?php endwhile; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <?php
          $list_penjualan = mysqli_query($koneksi, "SELECT * FROM tb_penjualan");
          while ($data = mysqli_fetch_assoc($list_penjualan)) {
            $id_penjualan = $data['id_penjualan'];
            $no_transaksi = $data['no_transaksi'];
            $bukti_pembayaran = $data['bukti_pembayaran'];
            $user_approval_pembayaran = $data['user_approval_pembayaran'];
          ?>
            <!-- Modal Detail Barang -->
            <div class="modal fade" id="modal-detail-barang-<?= $id_penjualan ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detail Barang (<?= $no_transaksi ?>)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <table class="table table-bordered" style="font-size: 10px;">
                      <thead>
                        <tr>
                          <th style="width: 5%;">No</th>
                          <th>Nama Produk</th>
                          <th>Jumlah</th>
                          <th>Harga</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $list_produk = mysqli_query($koneksi, "SELECT * FROM tb_detail_penjualan WHERE id_penjualan = '$id_penjualan'");
                        $no = 1;
                        $grandtotal = 0;
                        while ($data2 = mysqli_fetch_assoc($list_produk)) {
                          $id_produk = $data2['id_produk'];
                          $qty = $data2['qty'];
                          $harga = $data2['harga'];
                          $total = $qty * $harga;
                          $grandtotal += $total;
                          $nama_produk = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_produk WHERE id_produk = '$id_produk'"))['nama_produk'];
                        ?>
                          <tr>
                            <td valign="top" align="center"><?= $no ?></td>
                            <td valign="top"><?= $nama_produk ?></td>
                            <td valign="top" align="center"><?= $qty ?></td>
                            <td valign="top" align="right">Rp. <?= number_format(round($harga, 0), 0, ',', '.') ?></td>
                            <td valign="top" align="right">Rp. <?= number_format(round($total, 0), 0, ',', '.') ?></td>
                          </tr>
                        <?php
                          $no++;
                        }
                        ?>
                        <tr>
                          <td valign="top" colspan="4" align="center">Total</td>
                          <td valign="top" colspan="4" align="right">Rp. <?= number_format(round($grandtotal, 0), 0, ',', '.') ?></td>
                        </tr>
                      </tbody>

                    </table>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="modal-bukti-pembayaran-<?= $id_penjualan ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Bukti Pembayaran (<?= $no_transaksi ?>)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <center>
                      <img src="../assets/bukti_pembayaran/<?= $bukti_pembayaran ?>" alt="Bukti Pembayaran" width="250" height="250">
                    </center>
                  </div>
                  <div class="modal-footer">
                    <?php
                    if ($user_approval_pembayaran == '') {
                    ?>
                      <a href="riwayat_penjualan.php?setujui_pembayaran=<?= $id_penjualan ?>" class="btn btn-success">Setujui Pembayaran</a>
                      <a href="riwayat_penjualan.php?tolak_pembayaran=<?= $id_penjualan ?>" class="btn btn-danger">Tolak Pembayaran</a>
                    <?php
                    }
                    ?>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                  </div>
                </div>
              </div>
            </div>
          <?php
          }
          ?>
        </div>
      </section>
      <!-- /.content -->
    </div>

  </div>
  <!-- ./wrapper -->
  <script>
    $(document).ready(function() {
      $('#nav-riwayat-penjualan').addClass('active')
    })
  </script>
</body>

</html>