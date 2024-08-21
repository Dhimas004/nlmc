<?php
session_start();
include 'header.php';
include 'koneksi.php';
include 'function.php';
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

$id_user = $_SESSION['id_user'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body>
    <div class="my-3 mx-5">
        <div class="row mb-3">
            <div class="col-3"></div>
            <div class="col-9">
                <center>
                    <h5><b>TRANSAKSI</b></h5>
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <?php
                    $list_proses = mysqli_query($koneksi, "SELECT * FROM tb_penjualan WHERE id_user = '$id_user' AND status_penjualan IN ('proses','tolak') ORDER BY tanggal_penjualan DESC");
                    $list_selesai = mysqli_query($koneksi, "SELECT * FROM tb_penjualan WHERE id_user = '$id_user' AND status_penjualan = 'selesai' ORDER BY tanggal_penjualan DESC");

                    $total_proses = mysqli_num_rows($list_proses);
                    $total_selesai = mysqli_num_rows($list_selesai);
                    ?>
                    <a class="nav-link active <?= ($total_proses > 0 ? 'd-flex justify-content-between align-items-center' : '') ?>" id="proses-tab" data-toggle="pill" href="#proses" role="tab" aria-controls="proses" aria-selected="false">
                        <span>Proses</span>
                        <?php if ($total_proses > 0) { ?>
                            <div>
                                <span class="badge badge-dark"><?= $total_proses ?></span>
                            </div>
                        <?php } ?>
                    </a>
                    <a class="nav-link <?= ($total_selesai > 0 ? 'd-flex justify-content-between align-items-center' : '') ?>" id="selesai-tab" data-toggle="pill" href="#selesai" role="tab" aria-controls="selesai" aria-selected="false">
                        <span>Selesai</span>
                        <?php if ($total_selesai > 0) { ?>
                            <div>
                                <span class="badge badge-dark"><?= $total_selesai ?></span>
                            </div>
                        <?php } ?>
                    </a>
                </div>
            </div>
            <div class="col-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="proses" role="tabpanel" aria-labelledby="proses-tab">
                        <?php

                        if (mysqli_num_rows($list_proses) > 0) {
                        ?>
                            <table class="table table-bordered datatable" style="font-size: 12px;" id="table_id">
                                <thead>
                                    <tr>
                                        <th class="text-center text-sm" style="width: 5%;">No</th>
                                        <th class="text-center text-sm">No Transaksi</th>
                                        <th class="text-center text-sm">Barang</th>
                                        <th class="text-center text-sm">Tanggal Pembelian</th>
                                        <th class="text-center text-sm">Metode Pembayaran</th>
                                        <th class="text-center text-sm">Status Pembayaran</th>
                                        <th class="text-center text-sm">Total Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $list_transaksi_proses = [];
                                    while ($data = mysqli_fetch_assoc($list_proses)) {
                                        $tanggal_pembelian = tgl_indo(date_format(date_create($data['tanggal_pembelian']), 'Y-m-d'));
                                        $metode_pembayaran = ucwords(htmlspecialchars(str_replace('_', ' ', $data['metode_pembayaran'])));
                                        $no_transaksi = $data['no_transaksi'];
                                        $user_approval_pembayaran = $data['user_approval_pembayaran'];
                                        $status_pembayaran = "Pembayaran Belum Dicek";
                                        if ($user_approval_pembayaran != '') {
                                            if ($status_penjualan == 'selesai') {
                                                $status_pembayaran = "Pembayaran Disetujui";
                                            } else {
                                                $status_pembayaran = "Pembayaran Ditolak";
                                            }
                                        }
                                        $list_transaksi_proses[] = $no_transaksi;
                                        $id_penjualan = $data['id_penjualan'];
                                        $total_pembayaran = 0;
                                        $list_detail_penjualan = mysqli_query($koneksi, "SELECT * FROM tb_detail_penjualan WHERE id_penjualan = '$id_penjualan'");
                                        while ($data2 = mysqli_fetch_assoc($list_detail_penjualan)) {
                                            $total_pembayaran += $data2['qty'] * $data2['harga'];
                                        }
                                    ?>
                                        <tr>
                                            <td align="center"><?= $no ?></td>
                                            <td align="center"><?= $no_transaksi ?></td>
                                            <td align="center"><a href="#" data-toggle="modal" data-target="#modal-detail-barang-<?= $id_penjualan ?>">Detail Barang</a></td>
                                            <td align="center"><?= $tanggal_pembelian ?></td>
                                            <td align="center">
                                                <?php
                                                if ($metode_pembayaran == 'Bank Bca') {
                                                    echo "Bank BCA";
                                                } else {
                                                    echo $metode_pembayaran;
                                                }
                                                ?>
                                            </td>
                                            <td align="center">
                                                <?php
                                                if ($status_pembayaran == 'Pembayaran Belum Dicek' || $status_pembayaran == 'Pembayaran Ditolak') {
                                                    echo "<button class='btn btn-danger btn-sm'>$status_pembayaran</button>";
                                                    if ($status_pembayaran == 'Pembayaran Ditolak') {
                                                        echo "<br /><a href='checkout.php?id=$id_penjualan'>Input Ulang Pembayaran</a>";
                                                    }
                                                } else {
                                                    echo "<button class='btn btn-success btn-sm'>$status_pembayaran</button>";
                                                }
                                                ?>
                                            </td>
                                            <td align="right">Rp. <?= number_format(round($total_pembayaran, 0), 0, ',', '.') ?></td>
                                        </tr>
                                    <?php
                                        $no++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        <?php
                        } else {
                            echo "<center><b>TIDAK ADA TRANSAKSI</b></center>";
                        }
                        ?>
                    </div>
                    <div class="tab-pane fade" id="selesai" role="tabpanel" aria-labelledby="selesai-tab">
                        <?php

                        if (mysqli_num_rows($list_selesai) > 0) {

                        ?>
                            <table class="table table-bordered datatable" style="font-size: 12px;" id="table_id">
                                <thead>
                                    <tr>
                                        <th class="text-center text-sm" style="width: 5%;">No</th>
                                        <th class="text-center text-sm">No Transaksi</th>
                                        <th class="text-center text-sm">Barang</th>
                                        <th class="text-center text-sm">Tanggal Pembelian</th>
                                        <th class="text-center text-sm">Metode Pembayaran</th>
                                        <th class="text-center text-sm">Status Pembayaran</th>
                                        <th class="text-center text-sm">Total Pembayaran</th>
                                        <th class="text-center text-sm">Download</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($data = mysqli_fetch_assoc($list_selesai)) {
                                        $tanggal_pembelian = tgl_indo(date_format(date_create($data['tanggal_pembelian']), 'Y-m-d'));
                                        $metode_pembayaran = ucwords(htmlspecialchars(str_replace('_', ' ', $data['metode_pembayaran'])));
                                        $no_transaksi = $data['no_transaksi'];
                                        $id_penjualan = $data['id_penjualan'];
                                        $user_approval_pembayaran = $data['user_approval_pembayaran'];
                                        $status_pembayaran = "Pembayaran Belum Dicek";
                                        if ($user_approval_pembayaran != '') $status_pembayaran = "Pembayaran Disetujui";
                                        $total_pembayaran = 0;
                                        $list_detail_penjualan = mysqli_query($koneksi, "SELECT * FROM tb_detail_penjualan WHERE id_penjualan = '$id_penjualan'");
                                        while ($data2 = mysqli_fetch_assoc($list_detail_penjualan)) {
                                            $total_pembayaran += $data2['qty'] * $data2['harga'];
                                        }
                                    ?>
                                        <tr>
                                            <td align="center"><?= $no ?></td>
                                            <td align="center"><?= $no_transaksi ?></td>
                                            <td align="center"><a href="#" data-toggle="modal" data-target="#modal-detail-barang-<?= $id_penjualan ?>">Detail Barang</a></td>
                                            <td align="center"><?= $tanggal_pembelian ?></td>
                                            <td align="center">
                                                <?php
                                                if ($metode_pembayaran == 'Bank Bca') {
                                                    echo "Bank BCA";
                                                } else {
                                                    echo $metode_pembayaran;
                                                }
                                                ?>
                                            </td>
                                            <td align="center"><?php
                                                                if ($status_pembayaran == 'Pembayaran Belum Dicek') {
                                                                    echo "<button class='btn btn-danger btn-sm'>$status_pembayaran</button>";
                                                                } else {
                                                                    echo "<button class='btn btn-success btn-sm'>$status_pembayaran</button>";
                                                                }
                                                                ?></td>
                                            <td align="right">Rp. <?= number_format(round($total_pembayaran, 0), 0, ',', '.') ?></td>
                                            <td align="center">
                                                <?php
                                                $list_tb_detail_penjualan = mysqli_query($koneksi, "SELECT * FROM tb_detail_penjualan WHERE id_penjualan = '$id_penjualan'");
                                                if (mysqli_num_rows($list_tb_detail_penjualan) == 1) {
                                                    $file = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_produk JOIN tb_detail_penjualan ON tb_detail_penjualan.id_produk = tb_produk.id_produk WHERE id_penjualan = '$id_penjualan'"))['file'];
                                                ?>
                                                    <a href="assets/file/<?= $file ?>" target="_blank">Download File</a>
                                                    <?php
                                                } else {
                                                    $no_download = 1;
                                                    while ($data2 = mysqli_fetch_assoc($list_tb_detail_penjualan)) {
                                                        $file = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_produk WHERE id_produk = '$data2[id_produk]'"))['file'];
                                                    ?>
                                                        <a href="assets/file/<?= $file ?>" target="_blank">Download File <?= $no_download ?></a><br />
                                                <?php
                                                        $no_download++;
                                                    }
                                                }
                                                ?>

                                            </td>
                                        </tr>
                                    <?php
                                        $no++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        <?php
                        } else {
                            echo "<center><b>TIDAK ADA TRANSAKSI</b></center>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $list_penjualan = mysqli_query($koneksi, "SELECT * FROM tb_penjualan WHERE id_user = '$id_user'");
        while ($data = mysqli_fetch_assoc($list_penjualan)) {
            $id_penjualan = $data['id_penjualan'];
            $no_transaksi = $data['no_transaksi'];
            $status_review = $data['status_review'];
        ?>
            <!-- Modal Detail Transaksi -->
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
                            <table class="table table-bordered" style="font-size: 10px; white-space: nowrap;">
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

        <?php
        }
        ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <?php
    $list_transaksi_proses = json_encode($list_transaksi_proses);
    ?>
    <script>
        $(document).ready(function() {
            $('#nav-beranda').removeClass('active');
            $('#nav-lihat-transaksi').addClass('active');

            if (getQueryParameter('page') != '') {
                $(`#${getQueryParameter('page')}`).click();
            }
        })

        function getQueryParameter(paramName) {
            // Get the query string from the URL
            let queryString = window.location.search;

            // Create a URLSearchParams object
            let urlParams = new URLSearchParams(queryString);

            // Get the value of the specified query parameter
            return urlParams.get(paramName);
        }
    </script>
</body>

</html>

<?php
include 'footer.php';
?>