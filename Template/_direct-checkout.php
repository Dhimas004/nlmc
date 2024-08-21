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
    $transaksi = [];
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

    $id_produk = $_POST['id_produk'];
    $qty = 1;
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

    $last_stock = $tb_produk['stok'];
    $stok = $last_stock - $qty;

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
    $_SESSION['transaksi'] = $transaksi;
    header('Location: transaksi.php');
}

// Ambil data form sebelumnya dari session
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Direct Checkout</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Direct Checkout Page</h2>

        <!-- Formulir Checkout -->
        <form method="POST" class="mb-4" enctype="multipart/form-data">
            <?php
            $form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
            $user_info = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT *, nama_lengkap as nama_pelanggan, alamat as alamat_pelanggan FROM tb_user WHERE id_user = '{$_SESSION['id_user']}'"));
            $form_data['nama_pelanggan'] = $user_info['nama_lengkap'];
            $form_data['no_hp'] = $user_info['no_hp'];
            $form_data['alamat_pelanggan'] = $user_info['alamat'];
            ?>
            <input type="hidden" name="id_produk" value="<?= $_SESSION['checkout_product']['item_id'] ?>">
            <div class="form-group">
                <label for="nama_pelanggan">Nama Pelanggan</label>
                <input type="text" id="nama_pelanggan" name="nama_pelanggan" class="form-control" value="<?= isset($form_data['nama_pelanggan']) ? htmlspecialchars($form_data['nama_pelanggan']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="no_hp">Nama Pelanggan</label>
                <input type="text" id="no_hp" name="no_hp" class="form-control" value="<?= isset($form_data['no_hp']) ? htmlspecialchars($form_data['no_hp']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="alamat_pelanggan">Alamat Pelanggan:</label>
                <textarea id="alamat_pelanggan" name="alamat_pelanggan" class="form-control" rows="3" required><?= isset($form_data['alamat_pelanggan']) ? htmlspecialchars($form_data['alamat_pelanggan']) : '' ?></textarea>
            </div>
            <div class="form-group">
                <label for="metode_pembayaran">Metode Pembayaran</label>
                <div>
                    <input type="radio" id="bank_bca" name="metode_pembayaran" value="bank_bca" <?= isset($form_data['metode_pembayaran']) && $form_data['metode_pembayaran'] == 'bank_bca' ? 'checked' : '' ?> required>
                    <label for="bank_bca">Bank BCA (1234567890)</label>
                </div>
            </div>
            <div class="form-group">
                <label for="bukti_pembayaran">Bukti Pembayaran</label>
                <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*" required>
            </div>
            <button type="submit" name="checkout" class="btn btn-primary">Checkout</button>
        </form>

        <!-- Direct Checkout Product -->
        <?php if (isset($_SESSION['checkout_product'])) : ?>
            <h3 class="mb-3">Direct Checkout Product:</h3>
            <?php
            $product_id = $_SESSION['checkout_product']['item_id'];
            $product_qty = $_SESSION['checkout_product']['qty'];
            // Query produk langsung dari detail_produk.php
            $query = "SELECT nama_produk, harga FROM tb_produk WHERE id_produk = $product_id";
            $result = mysqli_query($koneksi, $query);
            if ($result && mysqli_num_rows($result) > 0) {
                $product = mysqli_fetch_assoc($result);
                $subtotal = $product['harga'] * $product_qty;
            ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['nama_produk']); ?> - <?= $product_qty; ?> x Rp. <?= htmlspecialchars(number_format(round($product['harga'], 0), 0, ',', '.')); ?> = Rp. <?= htmlspecialchars(number_format(round($subtotal, 0), 0, ',', '.')); ?></h5>
                    </div>
                </div>
            <?php
            }
            ?>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>