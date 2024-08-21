<?php
include 'function.php';
include 'header.php';
session_start();

// Periksa apakah session transaksi tersedia
if (!isset($_SESSION['transaksi'])) {
    echo "Tidak ada transaksi yang ditemukan.";
    exit();
}

// Ambil data transaksi dari session
$transaksi = $_SESSION['transaksi'];

// Lakukan perhitungan total harga atau operasi lainnya sesuai kebutuhan
$total_harga = 0;
foreach ($transaksi['items'] as $item) {
    $total_harga += $item['subtotal'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* CSS kustom (opsional) */
        .countdown {
            font-size: 20px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <h1 class="mt-5">Detail Transaksi</h1>
        <div class="row mt-4">
            <div class="col-md-6">
                <table>
                    <tr>
                        <td valign="top" class="font-weight-bold">No. Transaksi</td>
                        <td valign="top" style="padding: 0 5px;">:</td>
                        <td valign="top"><?= htmlspecialchars($transaksi['no_transaksi']); ?></td>
                    </tr>
                    <tr>
                        <td valign="top" class="font-weight-bold">Alamat Pelanggan</td>
                        <td valign="top" style="padding: 0 5px;">:</td>
                        <td valign="top"><?= htmlspecialchars($transaksi['alamat_pelanggan']); ?></td>
                    </tr>
                    <tr>
                        <td valign="top" class="font-weight-bold">No. HP</td>
                        <td valign="top" style="padding: 0 5px;">:</td>
                        <td valign="top"><?= ubahNomorHP($transaksi['no_hp']) ?></td>
                    </tr>
                    <tr>
                        <td valign="top" class="font-weight-bold">Metode Pembayaran</td>
                        <td valign="top" style="padding: 0 5px;">:</td>
                        <td valign="top">
                            <?php
                            $metode_pembayaran =  ucwords(htmlspecialchars(str_replace('_', ' ', $transaksi['metode_pembayaran'])));
                            if ($metode_pembayaran == 'Bank Bca') {
                                echo "Bank BCA";
                            } else {
                                echo $metode_pembayaran;
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" class="font-weight-bold">Total Harga</td>
                        <td valign="top" style="padding: 0 5px;">:</td>
                        <td valign="top">Rp. <?= htmlspecialchars(number_format($total_harga, 0, ',', '.')); ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5>List Barang</h5>
                <ul class="list-group">
                    <?php foreach ($transaksi['items'] as $item) : ?>
                        <li class="list-group-item">
                            <?= htmlspecialchars($item['nama_produk']); ?> - <?= htmlspecialchars($item['qty']); ?> x Rp. <?= htmlspecialchars(number_format($item['harga'], 0, ',', '.')); ?> = Rp. <?= htmlspecialchars(number_format($item['subtotal'], 0, ',', '.')); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#nav-beranda').removeClass('active');
            $('#nav-lihat-transaksi').addClass('active');
        })
    </script>
</body>

</html>

<?php
include 'footer.php';
?>