<?php
include_once "function.php";
$cart = new Cart($koneksi);
$id_user = $_SESSION['id_user']; // Ganti dengan id_user yang sebenarnya, misalnya dari sesi

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['pc_ready_submit'])) {
        // Menggunakan objek $cart untuk memanggil metode addToCart
        $cart->addToCart($_POST['id_user'], $_POST['id_produk']);
        // Menyimpan informasi produk yang telah ditambahkan ke sesi
        $_SESSION['cart'][$_POST['id_produk']] = true;

        // Redirect ke halaman yang sama untuk menghindari pengiriman ulang form
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}

$query = "SELECT * FROM tb_produk JOIN tb_kategori ON tb_kategori.id_kategori = tb_produk.id_kategori WHERE nama_kategori = 'SINGLE GLASS' ORDER BY RAND() LIMIT 20";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error dalam query: " . mysqli_error($koneksi));
}

// Menyimpan hasil query dalam array
$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['menarik_submit'])) {
        // Menggunakan objek $cart untuk memanggil metode addToCart
        $cart->addToCart($_POST['id_user'], $_POST['id_produk']);
        // Menyimpan informasi produk yang telah ditambahkan ke sesi
        $_SESSION['cart'][$_POST['id_produk']] = true;
    }
}



// Mengecek apakah produk sudah ada di keranjang
?>

<style>
    .owl-carousel .item {
        display: flex;
        justify-content: center;
    }

    .card-item {
        height: auto;
        /* Adjust height for better responsiveness */
    }

    .img-fluid {
        width: 100%;
        height: auto;
    }
</style>

<!-- New Phones Section -->
<section id="produk" class="container mb-5">
    <div class="container card shadow py-3">
        <h4 class="font-rubik bg-primary w-lg-25 two-values py-2 px-5 text-white" style="border-radius: 2px 40px; text-indent: 4px;">Produk Single Glass</h4>
        <!-- Owl Carousel -->
        <div class="owl-carousel owl-theme">
            <?php foreach ($products as $product) : ?>

                <!-- Item -->
                <?php
                // Mengecek apakah produk sudah ada di keranjang
                $product_in_cart = $cart->isInCart($id_user, $product['id_produk']);
                $status_sudah_beli = 0;
                if (mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id_detail_penjualan FROM tb_detail_penjualan JOIN tb_penjualan ON tb_penjualan.id_penjualan = tb_detail_penjualan.id_penjualan WHERE id_user = '$id_user' AND id_produk = '$product[id_produk]'"))) $status_sudah_beli = 1;
                // Mengecek stok produk
                $gambar_produk = $product['gambar_produk'];
                ?>
                <div class="item py-2 bg-white m-3 img-fluid">
                    <div class="product font-rale card p-5 card-item" style="height: 421px; position: relative;">
                        <a class="<?= ($status_sudah_beli ? 'image-sold' : '') ?>" <?= ($status_sudah_beli ? 'href="#" onclick="return false"' : '') ?> href="detail_produk.php?item=<?= urlencode($product['nama_produk']) ?>">
                            <img src="assets/img/products/<?= $gambar_produk ?>" alt="Image 1" width="150" height="150" style="border-radius: 10px;">
                        </a>

                        <div class="text-center mt-3">
                            <h6 class="font-weight-bold"><?= htmlspecialchars($product['nama_produk']) ?></h6>
                        </div>
                        <div class="text-center">
                            <div class="price py-2">
                                <span class="font-weight-bold">Rp. <?= number_format(round($product['harga'], 0), 0, ',', '.') ?></span>
                            </div>
                            <form method="post" class="py-3">
                                <div class="d-flex justify-content-center">
                                    <input type="hidden" name="id_produk" value="<?= htmlspecialchars($product['id_produk']); ?>">
                                    <input type="hidden" name="id_user" value="<?= $id_user; ?>">
                                    <?php if ($product_in_cart) : ?>
                                        <button type="button" class="btn btn-secondary font-size-12 mr-2" disabled>In Cart</button>
                                    <?php else : ?>
                                        <?php
                                        $logged_in = false;
                                        if ($_SESSION['id_user'] != '') $logged_in = true;
                                        if ($status_sudah_beli) {
                                        ?>
                                            <button type="button" class="btn btn-primary font-size-12 mr-2 disabled">SOLD</button>
                                        <?php
                                        } else {
                                        ?>
                                            <button type="submit" name="menarik_submit" class="btn btn-primary font-size-12 mr-2" <?= (!$logged_in ? 'disabled' : '') ?>><?= ($logged_in ? 'Tambah Ke Keranjang' : 'Login Terlebih Dahulu') ?></button>
                                        <?php
                                        }
                                        ?>

                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- !Item -->
            <?php endforeach; ?>
        </div>
        <!-- !Owl Carousel -->
    </div>
</section>
<!-- !New Phones Section -->

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT3BGzJu0gDgJS5vAnm6RYIVOpV49jFOeRTuWTvdE0H8F9CZ4F" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-Ksvt7BlTKrFcC1mFp3tPRgdt2n04fjNa25o+QQfIUPA9E+s0hEM+lHD1aQ5dPt4X" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"></script>

<!-- Owl Carousel JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha256-pY4aD6W1uNg6MSjRZp5lKCCzUmw7n8cg5pxtlFThDUc=" crossorigin="anonymous"></script>
<style>
    .card-item {
        width: 100%;
        max-width: 250px;
        /* Atur lebar maksimal */
        min-height: 400px;
        /* Atur tinggi minimal */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.3s ease;
        /* Menambahkan transisi halus */
    }

    .card-item img {
        max-width: 150px;
        max-height: 150px;
    }

    .card-item:hover {
        transform: scale(1.05);
        /* Menambahkan efek perbesaran sebesar 5% */
    }

    .card-item .text-center {
        margin-top: auto;
    }
</style>

<script>
    $(document).ready(function() {
        $(".owl-carousel2").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: true,
            dots: false,
        });

        $(document).delegate('.owl-nav', 'click', function() {
            return false;
        })
    })
</script>