/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 10.4.32-MariaDB : Database - nlmc
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `tb_admin` */

DROP TABLE IF EXISTS `tb_admin`;

CREATE TABLE `tb_admin` (
  `id_user` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `photo_profile` varchar(255) DEFAULT 'user.jpg',
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tb_admin` */

insert  into `tb_admin`(`id_user`,`username`,`password`,`nama_lengkap`,`photo_profile`) values 
(11,'admin','$2y$10$l8W24rR6EGSnWzuvYy/FKOmdWniMZdzkZak6QwNfM/mFPsWOGJ2VK','','user.jpg');

/*Table structure for table `tb_cart` */

DROP TABLE IF EXISTS `tb_cart`;

CREATE TABLE `tb_cart` (
  `id_cart` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `datetime` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_cart`),
  KEY `id_user` (`id_user`),
  KEY `id_produk` (`id_produk`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tb_cart` */

insert  into `tb_cart`(`id_cart`,`id_user`,`id_produk`,`qty`,`datetime`) values 
(6,11,6,1,'2024-07-20 13:39:45'),
(21,11,7,1,'2024-07-22 20:29:27');

/*Table structure for table `tb_detail_penjualan` */

DROP TABLE IF EXISTS `tb_detail_penjualan`;

CREATE TABLE `tb_detail_penjualan` (
  `id_detail_penjualan` int(11) NOT NULL AUTO_INCREMENT,
  `id_penjualan` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `harga` double DEFAULT NULL,
  PRIMARY KEY (`id_detail_penjualan`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `tb_detail_penjualan` */

insert  into `tb_detail_penjualan`(`id_detail_penjualan`,`id_penjualan`,`id_produk`,`qty`,`harga`) values 
(1,1,7,1,800000),
(2,2,5,1,850000),
(3,3,6,1,800000),
(4,4,7,1,800000),
(5,5,6,1,800000),
(6,6,7,1,800000),
(7,7,12,1,100000);

/*Table structure for table `tb_gambar_produk` */

DROP TABLE IF EXISTS `tb_gambar_produk`;

CREATE TABLE `tb_gambar_produk` (
  `id_gambar_produk` int(11) NOT NULL AUTO_INCREMENT,
  `id_produk` int(11) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_gambar_produk`),
  KEY `tb_gambar_produk_ibfk_1` (`id_produk`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `tb_gambar_produk` */

/*Table structure for table `tb_kategori` */

DROP TABLE IF EXISTS `tb_kategori`;

CREATE TABLE `tb_kategori` (
  `id_kategori` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) DEFAULT NULL,
  `gambar_kategori` text DEFAULT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tb_kategori` */

insert  into `tb_kategori`(`id_kategori`,`nama_kategori`,`gambar_kategori`) values 
(1,'SINGLE GLASS','66a8fcf82d9d3.png'),
(2,'DOUBLE GLASS','66a8fd0361c12.png');

/*Table structure for table `tb_penjualan` */

DROP TABLE IF EXISTS `tb_penjualan`;

CREATE TABLE `tb_penjualan` (
  `id_penjualan` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `no_transaksi` varchar(50) DEFAULT NULL,
  `nama_pelanggan` varchar(255) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `alamat_pelanggan` text DEFAULT NULL,
  `status_penjualan` varchar(20) DEFAULT 'proses',
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `bukti_pembayaran` varchar(225) DEFAULT NULL,
  `tanggal_penjualan` timestamp NULL DEFAULT current_timestamp(),
  `user_approval_pembayaran` varchar(50) DEFAULT NULL,
  `timestamp_approval_pembayaran` datetime DEFAULT NULL,
  PRIMARY KEY (`id_penjualan`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tb_penjualan` */

insert  into `tb_penjualan`(`id_penjualan`,`id_user`,`no_transaksi`,`nama_pelanggan`,`no_hp`,`deskripsi`,`alamat_pelanggan`,`status_penjualan`,`metode_pembayaran`,`bukti_pembayaran`,`tanggal_penjualan`,`user_approval_pembayaran`,`timestamp_approval_pembayaran`) values 
(1,1,'2024/INV/0001','User','082311563036','','ini alamat','selesai','bank_bca','669e91d390022.jpg','2024-07-23 00:07:31','admin','2024-07-23 00:07:40'),
(2,1,'2024/INV/0002','User','082311563036','','ini alamat','selesai','bank_bca','66a3d346098db.jpg','2024-07-26 23:07:19','admin','2024-07-30 11:01:11'),
(3,1,'2024/INV/0003','User','082311563036','','ini alamat','tolak','bank_bca','66a8f9c43e5db.png','2024-07-30 21:33:40','admin','2024-07-30 21:33:48'),
(4,1,'2024/INV/0004','User','082311563036','','ini alamat','selesai','bank_bca','66a8f9f06c0c7.jpg','2024-07-30 21:34:24','admin','2024-07-30 21:34:31'),
(5,1,'2024/INV/0005','User','082311563036','','ini alamat','proses','bank_bca','66a8fa392b56a.jpg','2024-07-30 21:35:37','',NULL),
(6,1,'2024/INV/0006','User','082311563036','','ini alamattest','proses','bank_bca','66adb7599cae8.jpg','2024-08-03 11:50:54','',NULL),
(7,1,'2024/INV/0007','User','082311563036',NULL,'ini alamat','selesai','bank_bca','66b966ceeb181.jpg','2024-08-12 08:35:10','admin','2024-08-12 08:35:36');

/*Table structure for table `tb_produk` */

DROP TABLE IF EXISTS `tb_produk`;

CREATE TABLE `tb_produk` (
  `id_produk` int(11) NOT NULL AUTO_INCREMENT,
  `brand` varchar(25) DEFAULT NULL,
  `nama_produk` varchar(100) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar_produk` text DEFAULT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_produk`),
  KEY `fk_kategori_produk` (`id_kategori`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tb_produk` */

insert  into `tb_produk`(`id_produk`,`brand`,`nama_produk`,`harga`,`deskripsi`,`gambar_produk`,`id_kategori`,`id_user`,`file`) values 
(5,'PO HARYANTO','LIVERY PO HARYANTO 2020',850000,'Livery bus PO Haryanto tahun 2020 biasanya didesain dengan tema yang modern dan eye-catching, mencerminkan identitas perusahaan serta memberikan kesan yang menarik bagi penumpang dan pengguna jalan.','669b5b7a0fb4d_design_2.jpg',1,11,'1.jpg'),
(6,'MADU KISMA','LIVERY MADU KISMA 2020',800000,'Livery bus Madu Kisma tahun 2020 biasanya didesain dengan tema yang modern dan eye-catching, mencerminkan identitas perusahaan serta memberikan kesan yang menarik bagi penumpang dan pengguna jalan.','669b5b424a334_design_1.jpg',1,11,'669e93845905f_banner3.jpg'),
(7,'SUDIRO TUNGGA JAYA','LIVERY SUDIRO TUNGGA JAYA 2020',800000,'Livery bus Sudiro Tungga Jaya tahun 2020 biasanya didesain dengan tema yang modern dan eye-catching, mencerminkan identitas perusahaan serta memberikan kesan yang menarik bagi penumpang dan pengguna jalan.','669b5b9a063d8_design_3.jpg',2,11,'3.jpg'),
(12,'test jetbus','dhimas produk',100000,'deskripsi','66b966b519874_banner2.jpg',1,11,'66b966b51987b_banner2.jpg');

/*Table structure for table `tb_testimoni` */

DROP TABLE IF EXISTS `tb_testimoni`;

CREATE TABLE `tb_testimoni` (
  `id_testimoni` int(11) NOT NULL AUTO_INCREMENT,
  `deskripsi` text DEFAULT NULL,
  `gambar_produk` varchar(255) NOT NULL,
  `id_user` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_testimoni`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `tb_testimoni` */

insert  into `tb_testimoni`(`id_testimoni`,`deskripsi`,`gambar_produk`,`id_user`,`created_at`) values 
(5,'livery tungga jaya, dengan tema new futuristic yang memanjakan mata, dibalut body jetbus 5 single glass, yang membuat perjalanan anda lebih berbeda..','66b430fcdc985_banner.jpg',11,'2024-08-08 09:42:09');

/*Table structure for table `tb_user` */

DROP TABLE IF EXISTS `tb_user`;

CREATE TABLE `tb_user` (
  `id_user` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `photo_profile` varchar(50) DEFAULT 'user.jpg',
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tb_user` */

insert  into `tb_user`(`id_user`,`username`,`password`,`nama_lengkap`,`no_hp`,`alamat`,`photo_profile`) values 
(1,'user','$2y$10$t3d49BU4gqkhUX0kEyNUiOCp4SrdmV/25k/iWZFGBCP4KMc5sLyxS','User','082311563036','ini alamat','user.jpg'),
(2,'123','$2y$10$FyITFQWQS5lAiDb/G2Y4J.6GMxucMksl3uqi1fShD2SICP2o.zT5u','123','123','','user.jpg'),
(3,'DHIMAS','$2y$10$4kQZSKPHybXu0robf9pQweEszgdWfGWqWlnTUWD0XG2e63326TEuC','DHIMAS YUDHATAMA','082311563036',NULL,'user.jpg');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
