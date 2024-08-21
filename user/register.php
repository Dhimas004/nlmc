<?php
require '../function.php';

if (isset($_POST['btnRegister'])) {
    $nama_lengkap = $_POST['nama_lengkap'];
    $no_hp = $_POST['no_hp'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $retype_password = $_POST['retype_password'];

    // Check Password
    if ($password != $retype_password) {
        echo "<script>alert('Password dan Retype Password Tidak Sama!'); window.location.href='register.php'</script>";
    }

    // Check Username
    $check_username = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_user WHERE username = '$username'"));
    if ($check_username > 0) {
        echo "<script>alert('Username sudah terdaftar!'); window.location.href='register.php'</script>";
    }

    // Check No. HP
    $check_no_hp = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_user WHERE no_hp = '$no_hp'"));
    if ($check_no_hp > 0) {
        echo "<script>alert('No. HP sudah terdaftar!'); window.location.href='register.php'</script>";
    }

    $password = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT);
    mysqli_query($koneksi, "INSERT INTO `tb_user` (
        `username`,
        `password`,
        `nama_lengkap`,
        `no_hp`
        )
        VALUES
        (
            '$username',
            '$password',
            '$nama_lengkap',
            '$no_hp'
        );
    ");

    echo "<script>alert('Berhasil Daftar Silahkan Login!'); window.location.href='login.php'</script>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <?php include '../include_admin/css.php'; ?>
    <title>Login User</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background-size: cover;
            background-repeat: no-repeat;
            background-image: url(../assets/img/img_properties/bg-login.jpg);
        }

        .container {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -55%);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-4 mx-5 py-4 px-5 text-dark rounded border border-dark" style="background-color: rgba(180,190,196,.6);">
                <h2 class="text-center">Selamat Datang</h2>
                <h3 class="text-center">User</h3>
                <form method="post">

                    <div class="form-group">
                        <label for="nama_lengkap"><i class="fas fa-fw fa-user"></i> Nama Lengkap</label>
                        <input required class="form-control rounded-pill" type="text" name="nama_lengkap" id="nama_lengkap" value="<?= $_POST['nama_lengkap'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="no_hp"><i class="fas fa-fw fa-user"></i> No. HP</label>
                        <input required class="form-control rounded-pill" type="text" name="no_hp" id="no_hp" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" value="<?= $_POST['no_hp'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="username"><i class="fas fa-fw fa-user"></i> Username</label>
                        <input required class="form-control rounded-pill" type="text" name="username" id="username" value="<?= $_POST['username'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="password"><i class="fas fa-fw fa-lock"></i> Password</label>
                        <input required class="form-control rounded-pill" type="password" name="password" id="password">
                    </div>
                    <div class="form-group">
                        <label for="retype_password"><i class="fas fa-fw fa-lock"></i>Retype Password</label>
                        <input required class="form-control rounded-pill" type="password" name="retype_password" id="retype_password">
                    </div>
                    <div class="form-group">
                        <a href="../index.php" class="btn btn-info rounded-pill text-left"><i class="fas fa-fw fa-arrow-left"></i> Kembali</a>
                        <button class="btn btn-success rounded-pill float-right" type="submit" name="btnRegister"><i class="fas fa-fw fa-sign-in-alt"></i> Daftar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>