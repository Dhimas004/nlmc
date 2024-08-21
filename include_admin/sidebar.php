<?php
$dataUser = dataUser('admin');
?>
<!-- Datatable -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="index.php" class="brand-link text-center">
    <span class="navbar-brand" href="index.php">NLMC</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
      <div class="image">
        <img style="width: 45px; height: 45px" src="../assets/img/img_profiles/<?= $dataUser['photo_profile']; ?>" class="img-circle elevation-2 img-profile" alt="User Image">
      </div>
      <div class="info">
        <a href="profile.php" class="d-block"><?= $dataUser['username']; ?></a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item has-treeview">
          <a href="index.php" class="nav-link" id="nav-dashboard">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link" id="nav-data-master">
            <i class="nav-icon fas fa-copy"></i>
            <p>
              Data Master
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="kategori_produk.php" class="nav-link" id="nav-kategori-produk">
                <i class="far fa-circle nav-icon"></i>
                <p>Kategori Produk</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="produk.php" class="nav-link" id="nav-produk">
                <i class="far fa-circle nav-icon"></i>
                <p>Produk</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="testimoni.php" class="nav-link" id="nav-testimoni">
                <i class="far fa-circle nav-icon"></i>
                <p>Testimoni</p>
              </a>
            </li>
          </ul>
        </li>
        <div class="dropdown-divider"></div>
        <li class="nav-item">
          <a href="riwayat_penjualan.php" class="nav-link" id="nav-riwayat-penjualan">
            <i class="nav-icon fas fa-receipt"></i>
            <p>
              Riwayat Penjualan
            </p>
          </a>
        </li>
        <div class="dropdown-divider"></div>
        <li class="nav-item">
          <a href="../index.php" class="nav-link">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>
              Kembali
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="logout.php" class="nav-link">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>
              Logout
            </p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>