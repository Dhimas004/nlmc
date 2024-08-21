<?php
require '../function.php';

// Periksa apakah parameter id_testimoni sudah diberikan
if (isset($_GET['id_testimoni'])) {
    $id_testimoni = $_GET['id_testimoni'];
    mysqli_query($koneksi, "DELETE FROM tb_testimoni WHERE id_testimoni = '$id_testimoni'");
} else {
    // Jika id_testimoni tidak diberikan, set pesan gagal
    $_SESSION['pesan'] = "ID testimoni tidak ditemukan.";
}

// Redirect kembali ke halaman sebelumnya atau halaman lain
header("Location: testimoni.php");
