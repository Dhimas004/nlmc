<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>NLMC</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- Owl-carousel CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha256-UhQQ4fxEeABh4JrcmAJ1+16id/1dnlOEVCFOxDef9Lw=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha256-kksNxjDRxd/5+jGurZUJd1sdR2v+ClrCl3svESBaJqw=" crossorigin="anonymous" />

    <!-- font awesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />

    <!-- Custom CSS file -->
    <link rel="stylesheet" href="assets/css/style.css">

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <style>
        .image-sold {
            position: relative;
            display: inline-block;
        }

        .image-sold::after {
            content: "Sold";
            position: absolute;
            top: 50%;
            /* Menempatkan di tengah secara vertikal */
            left: 50%;
            /* Menempatkan di tengah secara horizontal */
            transform: translate(-50%, -50%) rotate(-30deg);
            /* Memiringkan teks sebesar 45 derajat dan menempatkannya di tengah */
            background-color: rgba(255, 0, 0, 0.7);
            /* Latar belakang merah dengan sedikit transparansi */
            color: white;
            padding: 10px 30px;
            font-size: 24px;
            font-weight: bold;
            border-radius: 5px;
            z-index: 10;
            /* Pastikan elemen ini berada di atas gambar */
            text-transform: uppercase;
            /* Menjadikan teks semua huruf besar */
        }
    </style>

</head>

<body>

    <!-- start #header -->
    <header id="header">
        <!-- Primary Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark color-primary-bg p-3">
            <a class="navbar-brand font-weight-bold" href="index.php">NLMC</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav m-auto font-rubik">
                    <li class="nav-item active" id="nav-beranda">
                        <a class="nav-link" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item" id="nav-produk">
                        <a class="nav-link" href="index.php#produk">Produk</a>
                    </li>
                    <li class="nav-item" id="nav-testimoni">
                        <a class="nav-link" href="index.php#testimoni">Testimoni</i></a>
                    </li>
                    <li class="nav-item" id="nav-kontak">
                        <a class="nav-link" href="index.php#footer">Kontak</a>
                    </li>
                    <?php if ($_SESSION['id_user'] != '' && $_SESSION['level'] == 'user') { ?>
                        <li class="nav-item" id="nav-lihat-transaksi">
                            <a class="nav-link" href="lihat_transaksi.php">Transaksi</a>
                        </li>
                        <li class="nav-item" id="nav-keranjang">
                            <a class="nav-link" href="cart.php">Keranjang</a>
                        </li>
                    <?php } ?>
                    <?php if ($_SESSION['id_user'] != '' && $_SESSION['level'] == 'admin') { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/index.php">Panel Admin</a>
                        </li>
                    <?php } ?>

                </ul>
                <div class="d-flex">
                    <!-- <form action="#" class="font-size-14 font-rale">
                        <a href="cart.php" class="py-2 rounded-pill color-primary-bg">
                            <span class="font-size-16 px-2 text-white"><i class="fas fa-shopping-cart"></i></span>
                        </a>
                    </form> -->
                    <?php if ($_SESSION['id_user'] == '') : ?>
                        <a href="user/login.php" class="text-white font-weight-bold text-decoration-none">Login</a>
                        <a href="user/register.php" class="ml-2 text-white font-weight-bold text-decoration-none">Register</a>
                    <?php else : ?>
                        <div class="d-flex align-items-center">
                            <!-- <a href="cart.php" class="py-2 rounded-pill color-primary-bg">
                                <span class="font-size-16 px-2 text-white"><i class="fas fa-shopping-cart"></i></span>
                            </a> -->
                            <div class="dropdown dropleft">
                                <a class="font-weight-bold text-white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?= ucwords($_SESSION['username']) ?>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="logout.php">Logout</a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </nav>
        <!-- !Primary Navigation -->
        <!-- Modal Login -->
        <div class="modal fade" id="modal-login" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold" id="exampleModalLabel">LOGIN</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Regiter -->
        <div class="modal fade" id="modal-register" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold" id="exampleModalLabel">REGISTER</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </header>


    <!-- !start #header -->

    <!-- start #main-site -->
    <main id="main-site">