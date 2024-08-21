<?php
require '../function.php';
checkLogin();
// $produk = mysqli_query($koneksi, "SELECT tb_produk.*, tb_kategori.nama_kategori, tb_admin.username
//   FROM tb_produk
//   INNER JOIN tb_kategori ON tb_produk.id_kategori = tb_kategori.id_kategori
//   INNER JOIN tb_admin ON tb_produk.id_user = tb_admin.id_user
//   ORDER BY tb_produk.nama_produk ASC");


// $kategori = mysqli_query($koneksi, "SELECT * FROM tb_kategori ORDER BY id_kategori ASC");

// jika tombol ubah produk ditekan

if (isset($_POST['btnubahTestimoni'])) {

    if (ubahTestimoni($_POST) > 0) {
        setAlert("Berhasil diubah", "Testimoni berhasil diubah", "success");
        header("Location: testimoni.php");
    }
}

// jika tombol tambah Produk ditekan
if (isset($_POST['btnTambahTestimoni'])) {
    // Panggil fungsi tambahProduk dengan data Produk
    if (tambahTestimoni($_POST) > 0) {
        setAlert("Berhasil ditambahkan", "Testimoni berhasil ditambahkan", "success");
        header("Location: testimoni.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <?php include '../include_admin/css.php'; ?>
    <title>Daftar Testimoni</title>
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
                            <h1 class="m-0 text-dark">Daftar Testimoni</h1>
                        </div><!-- /.col -->
                        <div class="col-sm text-right">
                            <button type="button" data-toggle="modal" data-target="#tambahTestimoniModal" class="btn btn-primary"><i class="fas fa-fw fa-plus"></i> Tambah Testimoni</button>
                            <!-- Modal -->
                            <div class="modal fade text-left" id="tambahTestimoniModal" tabindex="-1" role="dialog" aria-labelledby="tambahTestimoniModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="tambahTestimoniModalLabel">Tambah Testimoni</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group text-center">
                                                    <a href="../assets/img/products/default.png" class="enlarge" id="check_enlarge_photo">
                                                        <img src="../assets/img/products/default.png" height="200px" width="200px" class="img-profile rounded" id="check_photo" alt="cover produk">
                                                    </a>
                                                    <div class="form-group">
                                                        <label for="photo">Gambar Produk</label>
                                                        <input type="file" name="gambar_produk" id="photo" class="btn btn-sm btn-primary form-control form-control-file" accept="image/*">

                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="deskripsi">Deskripsi</label>
                                                    <textarea name="deskripsi" required class="form-control" id="deskripsi" cols="30" rows="10"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Batal</button>
                                                <button type="submit" name="btnTambahTestimoni" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>


                        </div>
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
                                        <tr class="text-center align-middle">
                                            <th class="align-middle text-center">No.</th>
                                            <th class="align-middle text-center">Cover Produk</th>
                                            <th class="align-middle text-center">Deksripsi</th>
                                            <th class="align-middle text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        <?php
                                        $list_testimoni = mysqli_query($koneksi, "SELECT * FROM tb_testimoni");
                                        while ($data = mysqli_fetch_assoc($list_testimoni)) {
                                            $id_testimoni = $data['id_testimoni'];

                                        ?>

                                            <tr>
                                                <td>
                                                    <center><?= $i++; ?></center>
                                                </td>
                                                <td align="center">
                                                    <center>
                                                        <img class="img-list-cover" style="border-radius: 5px;" src="../assets/testimoni/<?= $data['gambar_produk']; ?>" alt="<?= $data['gambar_produk']; ?>" height="150" width="150">
                                                    </center>
                                                </td>
                                                <td style="text-align: left;">
                                                    <?= $data['deskripsi'] ?>
                                                </td>
                                                <td align="center" class="text-center">
                                                    <button class="btn btn-sm m-1 text-center mx-auto btn-success" type="button" data-toggle="modal" data-target="#ubahTestimoniModal<?= $data['id_testimoni']; ?>"><i class="fas fa-fw fa-edit"></i> Ubah</button>
                                                    <!-- Modal -->
                                                    <a href="hapus_testimoni.php?id_testimoni=<?= $data['id_testimoni']; ?>" data-nama="produk produk: <?= $data['nama_produk']; ?>" class="btn-hapus btn btn-sm m-1 text-center mx-auto btn-danger"><i class="fas fa-fw fa-trash"></i> Hapus</a>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="ubahTestimoniModal<?= $data['id_testimoni']; ?>" tabindex="-1" role="dialog" aria-labelledby="ubahTestimoniModalLabel<?= $data['id_testimoni']; ?>" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form method="post" enctype="multipart/form-data">
                                                        <input type="hidden" name="id_testimoni" value="<?= $data['id_testimoni']; ?>">
                                                        <input type="hidden" name="gambar_produk_lama" value="<?= $data['gambar_produk']; ?>">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="tambahProdukModalLabel">Ubah produk</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group text-center">
                                                                    <a href="../assets/testimoni/<?= $data['id_testimoni']; ?>" class="enlarge check_enlarge_photo">
                                                                        <img src="../assets/testimoni/<?= $data['gambar_produk']; ?>" class="img-profile rounded check_photo" alt="cover produk" height="200px" width="200px">
                                                                    </a>
                                                                    <div class="form-group">
                                                                        <label for="cover_produk<?= $data['id_testimoni']; ?>">Cover Produk</label>
                                                                        <input type="file" name="gambar_produk" id="cover_produk<?= $data['id_testimoni']; ?>" class="photo btn btn-sm btn-primary form-control form-control-file" accept="image/*">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="deskripsi<?= $data['id_testimoni']; ?>">Deskripsi</label>
                                                                    <textarea name="deskripsi" required class="form-control" id="deskripsi<?= $data['id_testimoni']; ?>" cols="30" rows="10"><?= $data['deskripsi']; ?></textarea>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Batal</button>
                                                                    <button type="submit" name="btnubahTestimoni" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Simpan</button>
                                                                </div>
                                                            </div>
                                                    </form>
                                                </div>
                                            </div>
                                        <?php } ?>
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
            $('#nav-data-master').addClass('active').parent().addClass('menu-open');
            $('#nav-testimoni').addClass('active');
            $('#navbar-testimoni').addClass('active');
        })
    </script>
</body>

</html>