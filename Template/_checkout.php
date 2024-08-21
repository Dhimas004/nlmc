<?php
include_once "function.php";
$cart = new Cart($koneksi);

// Inisialisasi variabel untuk produk individual
$product_id = isset($_POST['item_id']) ? $_POST['item_id'] : null;
$product_qty = isset($_POST['qty']) ? $_POST['qty'] : 1;

// Initialize user ID from session or the logged-in user
$id_user = $_SESSION['id_user'];
// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST['add_to_cart'])) {
        $cart->addToCart($id_user, $_POST['product_id']);
    } elseif (isset($_POST['delete_item'])) {
        $cart->deleteCart($_POST['id_cart']);
    } elseif (isset($_POST['qty_up']) || isset($_POST['qty_down'])) {
        // Handle quantity update
    } elseif (isset($_POST['checkout'])) {

        // Hapus data form dari session setelah checkout berhasil
        unset($_SESSION['form_data']);
    }

    // Simpan data form sebelumnya dalam session
    $_SESSION['form_data'] = $_POST;
}

// Ambil data form sebelumnya dari session
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

// Query untuk mengambil item dari keranjang
$query = "SELECT c.id_cart, p.id_produk, p.nama_produk, p.harga, c.qty, p.gambar_produk
            FROM tb_cart c
            INNER JOIN tb_produk p ON c.id_produk = p.id_produk
            WHERE c.id_user = $id_user";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error in query: " . mysqli_error($koneksi));
}

$cart_items = [];
$total_price = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $subtotal = $row['harga'] * $row['qty'];
    $total_price += $subtotal;
    $row['subtotal'] = $subtotal;
    $cart_items[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['checkout'])) {
    $transaksi = [];
    $id_user = $_POST['id_user'];
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $no_hp = $_POST['no_hp'];
    $alamat_pelanggan = $_POST['alamat_pelanggan'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $bukti_pembayaran = $_FILES['bukti_pembayaran'];
    $ext = explode('.', $bukti_pembayaran['name']);
    $ext = strtolower(end($ext));
    $bukti_pembayaran = uniqid() . "." . $ext;

    $tahun = date('Y');
    $jumlah_transaksi = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_penjualan WHERE YEAR(tanggal_penjualan) = '$tahun'"));
    $jumlah_transaksi++;

    $no_transaksi = $tahun . "/INV/" . str_pad($jumlah_transaksi, 4, "0", STR_PAD_LEFT);
    move_uploaded_file($_FILES['bukti_pembayaran']['tmp_name'], 'assets/bukti_pembayaran/' . $bukti_pembayaran);

    $id_penjualan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT `AUTO_INCREMENT`
        FROM  INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME   = 'tb_penjualan'; "))['AUTO_INCREMENT'];

    mysqli_query($koneksi, "INSERT INTO `tb_penjualan` (
        `no_transaksi`,
        `id_penjualan`,
        `id_user`,
        `nama_pelanggan`,
        `no_hp`,
        `alamat_pelanggan`,
        `metode_pembayaran`,
        `bukti_pembayaran`
        )
        VALUES
        (
            '$no_transaksi',
            '$id_penjualan',
            '$id_user',
            '$nama_pelanggan',
            '$no_hp',
            '$alamat_pelanggan',
            '$metode_pembayaran',
            '$bukti_pembayaran'
        );
    ");

    $total = 0;

    $list_cart = mysqli_query($koneksi, "SELECT * FROM tb_cart WHERE id_user = '$id_user'");
    while ($data = mysqli_fetch_assoc($list_cart)) :
        $id_cart = $data['id_cart'];
        $id_produk = $data['id_produk'];
        $qty = $data['qty'];
        $tb_produk = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_produk WHERE id_produk = '$id_produk'"));
        $nama_produk = $tb_produk['nama_produk'];
        $harga = $tb_produk['harga'];

        $transaksi['no_transaksi'] = $no_transaksi;
        $transaksi['alamat_pelanggan'] = $alamat_pelanggan;
        $transaksi['no_hp'] = $no_hp;
        $transaksi['metode_pembayaran'] = $metode_pembayaran;
        $transaksi['items'][] = [
            'nama_produk' => $nama_produk,
            'qty' => $qty,
            'harga' => $harga,
            'subtotal' => round($qty * $harga),
        ];
        $total += round($qty * $harga);


        mysqli_query($koneksi, "INSERT INTO `tb_detail_penjualan` (
            `id_penjualan`,
            `id_produk`,
            `qty`,
            `harga`
            )
            VALUES
            (
                '$id_penjualan',
                '$id_produk',
                '$qty',
                '$harga'
            );
        ");
    endwhile;
    $_SESSION['transaksi'] = $transaksi;
    mysqli_query($koneksi, "DELETE FROM tb_cart WHERE id_user = '$id_user'");
    header('Location: transaksi.php');
}

if (empty($cart_items)) {
    // Redirect to index.php if cart is empty
    header('Location: index.php');
    exit();
}
?>

<!-- Checkout section -->
<section id="checkout" class="py-3 mb-5">
    <div class="container-fluid w-75">
        <h5 class="font-baloo font-size-20">Checkout</h5>
        <?php
        $form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
        $user_info = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT *, nama_lengkap as nama_pelanggan, alamat as alamat_pelanggan FROM tb_user WHERE id_user = '{$_SESSION['id_user']}'"));
        $form_data['nama_pelanggan'] = $user_info['nama_lengkap'];
        $form_data['no_hp'] = $user_info['no_hp'];
        $form_data['alamat_pelanggan'] = $user_info['alamat'];
        ?>

        <form method="post" id="checkout-form" enctype="multipart/form-data">
            <input type="hidden" name="id_user" value="<?= $_SESSION['id_user'] ?>">
            <div class="form-group">
                <label for="nama_pelanggan">Nama Pelanggan</label>
                <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" value="<?= isset($form_data['nama_pelanggan']) ? htmlspecialchars($form_data['nama_pelanggan']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="no_hp">No. HP/Whatsapp</label>
                <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?= isset($form_data['no_hp']) ? htmlspecialchars($form_data['no_hp']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="alamat_pelanggan">Alamat Pelanggan</label>
                <textarea class="form-control" id="alamat_pelanggan" name="alamat_pelanggan" rows="3"><?= isset($form_data['alamat_pelanggan']) ? htmlspecialchars($form_data['alamat_pelanggan']) : '' ?></textarea>
            </div>
            <div class="form-group">
                <label for="metode_pembayaran">Metode Pembayaran</label>
                <div>
                    <input type="radio" id="bank_bca" name="metode_pembayaran" value="bank_bca" <?= isset($form_data['metode_pembayaran']) && $form_data['metode_pembayaran'] == 'bank_bca' ? 'checked' : '' ?> checked required>
                    <label for="bank_bca">Bank BCA (1234567890)</label>
                </div>
            </div>
            <div class="form-group">
                <label for="bukti_pembayaran">Bukti Pembayaran</label>
                <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*" required>
            </div>
            <div class=" row">
                <div class="col-sm-9">
                    <?php foreach ($cart_items as $cart_item) : ?>
                        <!-- cart item -->
                        <div class="row border-top py-3 mt-3">
                            <div class="col-sm-2">
                                <img src="assets/img/products/<?= htmlspecialchars($cart_item['gambar_produk']); ?>" style="height: 120px;" alt="<?= htmlspecialchars($cart_item['nama_produk']); ?>" class="img-fluid">
                            </div>
                            <div class="col-sm-7">
                                <h5 class="font-baloo font-size-20"><?= htmlspecialchars($cart_item['nama_produk']); ?></h5>
                                <small>
                                    <?php
                                    $id_produk = $cart_item['id_produk'];
                                    $brand = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_produk WHERE id_produk = '$id_produk'"))['brand'];
                                    echo $brand;
                                    ?>
                                </small>
                                <!-- product qty -->
                                <div class="qty d-flex pt-2">
                                    <form method="post" class="border-right">
                                        <input type="hidden" name="id_cart" value="<?= htmlspecialchars($cart_item['id_cart']); ?>">
                                        <button type="submit" id="btn-delete" name="delete_item" class="btn font-baloo text-danger" style="padding: 0;">Delete</button>
                                    </form>
                                </div>
                            </div>

                            <div class="col-sm-3 text-right">
                                <div class="font-size-20 text-danger font-baloo">
                                    Rp. <span class="product_price" data-id="<?= htmlspecialchars($cart_item['id_cart']); ?>"><?= htmlspecialchars(number_format($cart_item['subtotal'], 0, ',', '.')); ?></span>
                                </div>
                            </div>
                        </div>
                        <!-- !cart item -->
                    <?php endforeach; ?>
                </div>
                <!-- subtotal section-->
                <div class="col-sm-3">
                    <div class="sub-total border text-center mt-2">
                        <div class="border-top py-4">
                            <h5 class="font-baloo font-size-20">Subtotal ( <?= count($cart_items); ?> items):<br /><span class="text-danger">Rp. <span class="text-danger" id="deal-price"><?= htmlspecialchars(number_format($total_price, 0, ',', '.')); ?></span> </span> </h5>
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