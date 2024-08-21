<?php
include_once "function.php";
if (isset($_POST['checkout'])) {
    $id_penjualan = $_POST['id_penjualan'];
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $no_hp = $_POST['no_hp'];
    $alamat_pelanggan = $_POST['alamat_pelanggan'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $penjualan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT  * FROM tb_penjualan WHERE id_penjualan = '$id_penjualan'"));
    $old_bukti_pembayaran = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_penjualan WHERE id_penjualan = '$id_penjualan'"))['bukti_pembayaran'];
    unlink('assets/bukti_pembayaran/' . $old_bukti_pembayaran);

    $bukti_pembayaran = $_FILES['bukti_pembayaran'];
    $ext = explode('.', $bukti_pembayaran['name']);
    $ext = strtolower(end($ext));
    $bukti_pembayaran = uniqid() . "." . $ext;
    move_uploaded_file($_FILES['bukti_pembayaran']['tmp_name'], 'assets/bukti_pembayaran/' . $bukti_pembayaran);
    mysqli_query($koneksi, "UPDATE tb_penjualan SET status_penjualan = 'proses', nama_pelanggan = '$nama_pelanggan', no_hp = '$no_hp', alamat_pelanggan = '$alamat_pelanggan', metode_pembayaran = '$metode_pembayaran', user_approval_pembayaran = NULL, timestamp_approval_pembayaran = NULL, bukti_pembayaran = '$bukti_pembayaran' WHERE id_penjualan = '$id_penjualan'");

    $_SESSION['transaksi'] = [];
    $list_produk = mysqli_query($koneksi, "SELECT * FROM tb_detail_penjualan WHERE id_penjualan = '$id_penjualan'");
    while ($row = mysqli_fetch_assoc($list_produk)) {
        $nama_produk = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_produk WHERE id_produk = '$row[id_produk]'"))['nama_produk'];
        $_SESSION['transaksi']['items'][] = [
            'subtotal' => $row['harga'] * $row['qty'],
            'harga' => $row['harga'],
            'nama_produk' => $nama_produk
        ];
    }
    $_SESSION['transaksi']['no_transaksi'] = $penjualan['no_transaksi'];
    $_SESSION['transaksi']['alamat_pelanggan'] = $penjualan['alamat_pelanggan'];
    $_SESSION['transaksi']['no_hp'] = $penjualan['no_hp'];
    $_SESSION['transaksi']['metode_pembayaran'] = $penjualan['metode_pembayaran'];

    header('Location: transaksi.php');
}

?>
<!-- Checkout section -->
<section id="checkout" class="py-3 mb-5">
    <div class="container-fluid w-75">
        <h5 class="font-baloo font-size-20">Checkout</h5>
        <form method="post" id="checkout-form" enctype="multipart/form-data">
            <?php
            $id_penjualan = $_GET['id'];
            $penjualan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_penjualan WHERE id_penjualan = '$id_penjualan'"));
            ?>
            <input type="hidden" name="id_penjualan" value="<?= $id_penjualan ?>">
            <div class="form-group">
                <label for="nama_pelanggan">Nama Pelanggan</label>
                <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" value="<?= $penjualan['nama_pelanggan'] ?>" required>
            </div>
            <div class="form-group">
                <label for="no_hp">No. HP/Whatsapp</label>
                <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?= $penjualan['no_hp'] ?>" required>
            </div>
            <div class="form-group">
                <label for="alamat_pelanggan">Alamat Pelanggan</label>
                <textarea class="form-control" id="alamat_pelanggan" name="alamat_pelanggan" rows="3"><?= $penjualan['alamat_pelanggan'] ?></textarea>
            </div>
            <div class="form-group">
                <label for="metode_pembayaran">Metode Pembayaran</label>
                <div>
                    <input type="radio" id="bank_bca" name="metode_pembayaran" value="bank_bca" <?= ($penjualan['metode_pembayaran'] = 'bank_bca' ? 'checked' : '') ?> required>
                    <label for="bank_bca">Bank BCA (1234567890)</label>
                </div>
            </div>
            <div class="form-group">
                <label for="bukti_pembayaran">Bukti Pembayaran</label>
                <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*" required>
            </div>
            <div class=" row">
                <div class="col-sm-9">
                    <?php
                    $total_price = 0;
                    $list_produk = mysqli_query($koneksi, "SELECT * FROM tb_detail_penjualan  WHERE id_penjualan = '$id_penjualan'");
                    $total_produk = mysqli_num_rows($list_produk);
                    while ($row = mysqli_fetch_assoc($list_produk)) {
                        $id_produk = $row['id_produk'];
                        $produk = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_produk WHERE id_produk = '$id_produk'"));
                        $total_price += $row['harga'] * $row['qty'];
                    ?>
                        <!-- cart item -->
                        <div class="row border-top py-3 mt-3">
                            <div class="col-sm-2">
                                <img src="assets/img/products/<?= htmlspecialchars($produk['gambar_produk']); ?>" style="height: 120px;" alt="<?= htmlspecialchars($produk['nama_produk']); ?>" class="img-fluid">
                            </div>
                            <div class="col-sm-7">
                                <h5 class="font-baloo font-size-20"><?= htmlspecialchars($produk['nama_produk']); ?></h5>
                                <small>
                                    <?php
                                    $id_produk = $produk['id_produk'];
                                    $brand = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_produk WHERE id_produk = '$id_produk'"))['brand'];
                                    echo $brand;
                                    ?>
                                </small>
                                <!-- product qty -->
                                <div class="qty d-flex pt-2">
                                </div>
                            </div>

                            <div class="col-sm-3 text-right">
                                <div class="font-size-20 text-danger font-baloo">
                                    Rp. <span class="product_price"><?= htmlspecialchars(number_format($row['harga'] * $row['qty'], 0, ',', '.')); ?></span>
                                </div>
                            </div>
                        </div>
                        <!-- !cart item -->
                    <?php } ?>
                </div>
                <!-- subtotal section-->
                <div class="col-sm-3">
                    <div class="sub-total border text-center mt-2">
                        <div class="border-top py-4">
                            <h5 class="font-baloo font-size-20">Subtotal ( <?= $total_produk; ?> items):<br /><span class="text-danger">Rp. <span class="text-danger" id="deal-price"><?= htmlspecialchars(number_format($total_price, 0, ',', '.')); ?></span> </span> </h5>
                            <button type="submit" id="btn-checkout" name="checkout" class="btn btn-warning mt-3">Proses Transaksi</button>
                        </div>
                    </div>
                </div>
                <!-- !subtotal section-->
            </div>
        </form>
    </div>
</section>
<!-- !Checkout section -->


<!-- !Checkout section -->
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT3BGzJu0gDgJS5vAnm6RYIVOpV49jFOeRTuWTvdE0H8F9CZ4F" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-Ksvt7BlTKrFcC1mFp3tPRgdt2n04fjNa25o+QQfIUPA9E+s0hEM+lHD1aQ5dPt4X" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"></script>
</body>

<script>
    $(document).ready(function() {
        $('#btn-qty-up').click(function() {
            removeRequired();
        })
        $('#btn-qty-down').click(function() {
            removeRequired();
        })
        $('#btn-delete').click(function() {
            removeRequired();
        })
    })

    function removeRequired() {
        $("[required]").each(function() {
            $(this).removeAttr('required');
        })
    }
</script>

</html>