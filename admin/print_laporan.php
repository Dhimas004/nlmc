<?php
require_once('../koneksi.php');
function tgl_indo($tanggal)
{
    $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);

    // variabel pecahkan 0 = tanggal
    // variabel pecahkan 1 = bulan
    // variabel pecahkan 2 = tahun

    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}
$id_penjualan = $_GET['id_penjualan'];
$tb_penjualan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_penjualan WHERE id_penjualan = '$id_penjualan'"));
$id_user = $tb_penjualan['id_user'];
$no_transaksi = $tb_penjualan['no_transaksi'];
$nama_pelanggan = $tb_penjualan['nama_pelanggan'];
$no_hp = $tb_penjualan['no_hp'];
$deskripsi = $tb_penjualan['deskripsi'];
$alamat_pelanggan = $tb_penjualan['alamat_pelanggan'];
$status_penjualan = $tb_penjualan['status_penjualan'];
$metode_pembayaran = $tb_penjualan['metode_pembayaran'];
$bukti_pembayaran = $tb_penjualan['bukti_pembayaran'];
$tanggal_penjualan = $tb_penjualan['tanggal_penjualan'];
$user_approval_pembayaran = $tb_penjualan['user_approval_pembayaran'];
$timestamp_approval_pembayaran = $tb_penjualan['timestamp_approval_pembayaran'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Livery Bus</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 21cm;
            height: 29.7cm;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }

        .container {
            padding: 20px;
            flex: 1;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .header p {
            margin: 5px 0;
            font-size: 16px;
            color: #555;
        }

        .info {
            margin-bottom: 20px;
        }

        .info p {
            margin: 5px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .total {
            text-align: right;
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #777;
            margin-top: auto;
        }

        @media print {
            #print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header" style="position: relative;">
            <h1>Laporan Penjualan Livery Bus</h1>
            <button id="print" style="position: absolute; right: 0; top: 0;">Print</button>
        </div>

        <div class="info">
            <table style="font-weight: bold;">
                <tr>
                    <td>No. Transaksi</td>
                    <td>&nbsp;:&nbsp;</td>
                    <td><?= $no_transaksi ?></td>
                </tr>
                <tr>
                    <td>Nama Pembeli</td>
                    <td>&nbsp;:&nbsp;</td>
                    <td><?= ucwords(strtolower($nama_pelanggan)) ?></td>
                </tr>
                <tr>
                    <td>Tanggal Pembelian</td>
                    <td>&nbsp;:&nbsp;</td>
                    <td><?= tgl_indo(date_format(date_create($tanggal_penjualan), 'Y-m-d')) ?></td>
                </tr>
                <tr>
                    <td>No. HP</td>
                    <td>&nbsp;:&nbsp;</td>
                    <td><?= $no_hp ?></td>
                </tr>
                <tr>
                    <td>Metode Pembayaran</td>
                    <td>&nbsp;:&nbsp;</td>
                    <td><?php
                        if ($metode_pembayaran == 'bank_bca') echo "Bank BCA";
                        ?></td>
                </tr>
                <tr>
                    <td>Status Pembayaran</td>
                    <td>&nbsp;:&nbsp;</td>
                    <td><?php
                        if ($user_approval_pembayaran == '') {
                            echo "Belum Dicek";
                        } else if ($user_approval_pembayaran != '') {
                            if ($status_penjualan == 'selesai') {
                                echo "Lunas";
                            } else {
                                echo "Ditolak";
                            }
                        }
                        ?></td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>&nbsp;:&nbsp;</td>
                    <td><?= $alamat_pelanggan ?></td>
                </tr>
            </table>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Brand</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $grandtotal = 0;
                $list_detail_penjualan = mysqli_query($koneksi, "SELECT * FROM tb_detail_penjualan WHERE id_penjualan = '$id_penjualan'");
                while ($data = mysqli_fetch_assoc($list_detail_penjualan)) {
                    $id_produk = $data['id_produk'];
                    $tb_produk = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_produk WHERE id_produk = '$id_produk'"));
                    $nama_produk = $tb_produk['nama_produk'];
                    $id_kategori = $tb_produk['id_kategori'];
                    $nama_kategori = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_kategori WHERE id_kategori = '$id_kategori'"))['nama_kategori'];
                    $brand = $tb_produk['brand'];
                    $qty = $data['qty'];
                    $harga = $data['harga'];
                    $total = $qty * $harga;
                    $grandtotal += $total;
                ?>
                    <tr>
                        <td><?= $no ?></td>
                        <td><?= $nama_produk ?></td>
                        <td><?= $nama_kategori ?></td>
                        <td><?= $brand ?></td>
                        <td style="text-align: right;">Rp. <?= number_format(round($total), 0, ',', '.') ?></td>
                    </tr>
                <?php
                    $no++;
                }
                ?>
            </tbody>
        </table>

        <div class="total">
            <strong>Total Penjualan: Rp. <?= number_format(round($grandtotal, 0), 0, ',', '.') ?></strong>
        </div>

        <div class="footer">
            <p>Dicetak pada: <?= tgl_indo(date('Y-m-d')) . " " . date('H:i:s') ?></p>
            <p>Untuk informasi lebih lanjut, jangan ragu untuk menghubungi kami.</p>
            <p>Terima kasih atas dukungan dan kepercayaan Anda kepada kami.</p>
        </div>
    </div>
    <script>
        document.querySelector('#print').addEventListener('click', function() {
            window.print();
        })
    </script>
</body>

</html>