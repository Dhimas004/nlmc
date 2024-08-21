<?php
require '../function.php';
checkLogin();

$id_produk = $_GET['id_produk'];

if (isset($_POST['tambah_gambar'])) :
  $gambar = $_FILES['photo'];
  $nama_gambar = uniqid() . '_' . $gambar['name'];
  // Move uploaded file to desired location
  $upload_path = '../assets/img/gambar_lainnya/' . $nama_gambar;
  echo $upload_path;
  if (!move_uploaded_file($gambar['tmp_name'], $upload_path)) {
    // Gagal memindahkan file, beri tahu pengguna atau lakukan tindakan yang sesuai
    return false;
  }

  mysqli_query($koneksi, "INSERT INTO `tb_gambar_produk` (
  `id_produk`,
  `gambar`
)
VALUES
  (
    '$id_produk',
    '$nama_gambar'
  );");
  setAlert("Berhasil Tambah Gambar", "Berhasil menambahkan gambar", "success");
  echo "<script>window.location.href='tambah_gambar_produk.php?id_produk=$id_produk'</script>";
endif;

if (isset($_GET['hapus'])) :
  $id_gambar_produk = $_GET['hapus'];
  $gambar = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_gambar_produk WHERE id_gambar_produk = '$id_gambar_produk'"))['gambar'];
  unlink("../assets/img/gambar_lainnya/$gambar");
  mysqli_query($koneksi, "DELETE FROM tb_gambar_produk WHERE id_gambar_produk = '$id_gambar_produk'");
  setAlert("Berhasil Hapus", "Berhasil hapus gambar", "success");
  echo "<script>window.location.href='tambah_gambar_produk.php?id_produk=$id_produk'</script>";
endif;
?>

<!DOCTYPE html>
<html>

<head>
  <?php include '../include_admin/css.php'; ?>
  <title>Produk</title>
  <style>
    .form-control {
      height: 43px;
    }
  </style>
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
              <h1 class="m-0 text-dark text-center font-weight-bold">TAMBAH GAMBAR PRODUK</h1>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid ">
          <div class="row px-4">
            <div class="col-lg-4 col-md-12 ">
              <div>
                <center><b>DATA</b></center>
              </div>
              <?php
              $produk  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_produk WHERE id_produk = '$id_produk'"));
              $id_produk = $produk['id_produk'];
              $brand = $produk['brand'];
              $nama_produk = $produk['nama_produk'];
              $harga = $produk['harga'];
              $stok = $produk['stok'];
              $deskripsi = $produk['deskripsi'];
              $gambar_produk = $produk['gambar_produk'];
              $id_kategori = $produk['id_kategori'];
              $kategori = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_kategori WHERE id_kategori = '$id_kategori'"))['nama_kategori'];
              ?>
              <table style="width: 100%;">
                <tr>
                  <td valign="top" style="width: 30%;">Nama Produk</td>
                  <td valign="top" style="width: 5%;" class="text-center">:</td>
                  <td valign="top"><?= $nama_produk ?></td>
                </tr>
                <tr>
                  <td valign="top" style="width: 30%;">Brand</td>
                  <td valign="top" style="width: 5%;" class="text-center">:</td>
                  <td valign="top"><?= $brand ?></td>
                </tr>
                <tr>
                  <td valign="top" style="width: 30%;">Kategori</td>
                  <td valign="top" style="width: 5%;" class="text-center">:</td>
                  <td valign="top"><?= $kategori ?></td>
                </tr>
                <tr>
                  <td valign="top" style="width: 30%;">Harga</td>
                  <td valign="top" style="width: 5%;" class="text-center">:</td>
                  <td valign="top">Rp. <?= number_format(round($harga, 2), 2, ',', '.') ?></td>
                </tr>
                <tr>
                  <td valign="top" style="width: 30%;">Stok</td>
                  <td valign="top" style="width: 5%;" class="text-center">:</td>
                  <td valign="top"><?= number_format(round($stok, 0), 0, ',', '.') ?></td>
                </tr>
                <tr>
                  <td valign="top" style="width: 30%;">Deskripsi</td>
                  <td valign="top" style="width: 5%;" class="text-center">:</td>
                  <td valign="top"><?= $deskripsi ?></td>
                </tr>
              </table>
              <form class="mt-4" method="POST" enctype="multipart/form-data">
                <center>
                  <img id="check_photo" src="../assets/img/products/default.png" width="150">
                </center>
                <div class="form-group text-center">
                  <label for="photo">Gambar Baru</label>
                  <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                </div>
                <button type="submit" name="tambah_gambar" class="btn btn-primary float-right">Tambah Gambar</button>
              </form>
            </div>
            <div class="col-lg-8 col-md-12 d-flex flex-column align-items-center mt-5 mt-lg-0">
              <?php
              $list_gambar_produk = mysqli_query($koneksi, "SELECT * FROM tb_gambar_produk WHERE id_produk = '$id_produk'");
              if (mysqli_num_rows($list_gambar_produk) > 0) :
              ?>
                <div>
                  <center><b>GAMBAR LAINNYA</b></center>
                </div>
                <div class="table-responsive">
                  <table style="border-collapse: collapse; width: 100%;">
                    <?php
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($list_gambar_produk)) :
                      if ($no == 1) echo "<tr>";
                      $id_gambar_produk = $row['id_gambar_produk'];
                      $gambar = $row['gambar'];
                    ?>
                      <td valign="top" align="center" style="border: 1px solid black;">
                        <img src="../assets/img/gambar_lainnya/<?= $gambar ?>" width="150" height="150" style="border-radius: 10px; padding: 5px;">
                        <br />
                        <a href="tambah_gambar_produk.php?id_produk=<?= $id_produk ?>&hapus=<?= $id_gambar_produk ?>">Hapus</a>
                      </td>
                    <?php
                      $no++;
                      if ($no == 5) {
                        echo "<tr />";
                        $no = 1;
                      }
                    endwhile;
                    ?>
                  </table>
                </div>
              <?php
              else : echo "<div><center><b>TIDAK ADA GAMBAR LAIN</b></center></div>";
              endif; ?>
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
      $('#nav-data-master').addClass('active').parent().addClass('menu-open');
      $('#nav-produk').addClass('active');
      $('#navbar-kategori-produk').addClass('active');
    })
  </script>
</body>

</html>