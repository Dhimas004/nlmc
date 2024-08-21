<?php
session_start();
include_once "function.php";

$product = new Product($koneksi);
$cart = new Cart($koneksi);

// Ambil id_user dari sesi atau pengguna yang sedang login
$id_user = $_SESSION['id_user']; // Contoh: Anda bisa mengambil id_user dari sesi atau pengguna yang sedang login

// Fungsi untuk menambah item ke dalam keranjang
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_to_cart'])) {
    $cart->addToCart($id_user, $_POST['product_id']);
}

// Fungsi untuk menghapus item dari keranjang
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['delete_item'])) {
    $cart->deleteCart($_POST['id_cart']);
}

$query = "SELECT c.id_cart, p.nama_produk, p.harga, c.qty, p.gambar_produk, p.brand
          FROM tb_cart c
          INNER JOIN tb_produk p ON c.id_produk = p.id_produk
          WHERE c.id_user = $id_user";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error dalam query: " . mysqli_error($koneksi));
}

// Menyimpan hasil query dalam array
$cart_items = [];
$total_price = 0;
$total_items = 0; // Initialize total items count

while ($row = mysqli_fetch_assoc($result)) {
    $row['subtotal'] = $row['harga'] * $row['qty'];
    $total_price += $row['subtotal'];
    $total_items++; // Increment total items count
    $cart_items[] = $row;
}
?>

<!-- Shopping cart section  -->
<section id="cart" class="py-3 mb-5">
    <div class="container-fluid w-75">
        <h5 class="font-baloo font-size-20">Keranjang</h5>

        <!--  shopping cart items   -->
        <div class="row">
            <div class="col-sm-9">
                <!-- Cart Items Display -->
                <?php foreach ($cart_items as $cart_item) : ?>
                    <!-- cart item -->
                    <div class="row border-top py-3 mt-3 align-items-center">
                        <div class="col-sm-2 text-center">
                            <img src="assets/img/products/<?= htmlspecialchars($cart_item['gambar_produk']); ?>" style="height: 120px;" alt="<?= htmlspecialchars($cart_item['nama_produk']); ?>" class="img-fluid">
                        </div>
                        <div class="col-sm-7">
                            <h5 class="font-baloo font-size-20"><?= htmlspecialchars($cart_item['nama_produk']); ?></h5>
                            <small><?= htmlspecialchars($cart_item['brand']); ?></small>
                            <!-- product qty -->
                            <div class="qty d-flex pt-2">
                                <form method="post">
                                    <input type="hidden" name="id_cart" value="<?= htmlspecialchars($cart_item['id_cart']); ?>">
                                    <button type="submit" name="delete_item" class="btn font-baloo text-danger" style="padding: 0;">Delete</button>
                                </form>
                            </div>
                            <!-- !product qty -->

                        </div>

                        <div class="col-sm-3 text-right">
                            <div class="font-size-20 text-danger font-baloo">
                                Rp. <span class="product_price" data-id="<?= htmlspecialchars($cart_item['id_cart']); ?>"><?= htmlspecialchars(number_format($cart_item['subtotal'], 0, ',', '.')); ?></span>
                            </div>
                        </div>
                    </div>
                    <!-- !cart item -->
                <?php endforeach; ?>
                <!-- !Cart Items Display -->

            </div>
            <!-- subtotal section-->
            <div class="col-sm-3">
                <div class="sub-total border text-center mt-2">
                    <div class="border-top py-4">
                        <h5 class="font-baloo font-size-20">Subtotal ( <?= $total_items; ?> items):&nbsp; <span class="text-danger"><br />Rp. <span class="text-danger" id="deal-price"><?= htmlspecialchars(number_format(round($total_price, 0), 0, ',', '.')); ?></span> </span> </h5>
                        <!-- Tambahkan link checkout -->
                        <a href="checkout.php" class="btn btn-warning">Checkout</a>

                    </div>
                </div>
            </div>
            <!-- !subtotal section-->
        </div>
        <!--  !shopping cart items   -->
    </div>
</section>
<!-- !Shopping cart section  -->

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT3BGzJu0gDgJS5vAnm6RYIVOpV49jFOeRTuWTvdE0H8F9CZ4F" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-Ksvt7BlTKrFcC1mFp3tPRgdt2n04fjNa25o+QQfIUPA9E+s0hEM+lHD1aQ5dPt4X" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"></script>

</body>

</html>