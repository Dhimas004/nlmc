<?php
require '../function.php';
checkLoginAtLogin();

if (isset($_POST['btnLogin'])) {
	$username = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);

	$checkAdmin = mysqli_query($koneksi, "SELECT * FROM tb_admin WHERE username = '$username'");
	if ($data = mysqli_fetch_assoc($checkAdmin)) {
		if (password_verify($password, $data['password'])) {
			$_SESSION = [
				'id_user' => $data['id_user'],
				'username' => $data['username'],
				'level' => 'admin'
			];
			header("Location: ../admin/index.php");
			die;
		}
	}


	$checkUsername = mysqli_query($koneksi, "SELECT * FROM tb_user WHERE username = '$username'");
	if ($data = mysqli_fetch_assoc($checkUsername)) {
		if (password_verify($password, $data['password'])) {
			$_SESSION = [
				'id_user' => $data['id_user'],
				'username' => $data['username'],
				'level' => 'user'
			];
			header("Location: ../index.php");
		} else {
			setAlert("Gagal login!", "Password yang anda masukkan salah!", "error");
			header("Location: login.php");
		}
	} else {
		setAlert("Gagal login!", "Username belum terdaftar!", "error");
		header("Location: index.php");
	}
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
						<label for="username"><i class="fas fa-fw fa-user"></i> Username</label>
						<input required class="form-control rounded-pill" type="text" name="username" id="username">
					</div>
					<div class="form-group">
						<label for="password"><i class="fas fa-fw fa-lock"></i> Password</label>
						<input required class="form-control rounded-pill" type="password" name="password" id="password">
					</div>
					<div class="form-group">
						<a href="../index.php" class="btn btn-info rounded-pill text-left"><i class="fas fa-fw fa-arrow-left"></i> Back</a>
						<button class="btn btn-success rounded-pill float-right" type="submit" name="btnLogin"><i class="fas fa-fw fa-sign-in-alt"></i> Login</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>

</html>