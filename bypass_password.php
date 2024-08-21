<?php
require_once('function.php');
if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $password = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT);
    mysqli_query($koneksi, "UPDATE tb_admin SET password = '$password' WHERE username = '$nama'");
    mysqli_query($koneksi, "UPDATE tb_user SET password = '$password' WHERE username = '$nama'");
}
?>

<form action="" method="POST">
    <table>
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td><input type="text" name="nama"></td>
        </tr>
        <tr>
            <td>Password</td>
            <td>:</td>
            <td><input type="text" name="password"></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><button type="submit" name="submit">Submit</button></td>
        </tr>
    </table>
</form>