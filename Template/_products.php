<?php
include_once "function.php";

$cart = new Cart($koneksi);
$id_user = $_SESSION['id_user']; // Ganti dengan id_user yang sesuai
$product_name = isset($_GET['item']) ? $_GET['item'] : '';

if (!empty($product_name)) {
    $query = "SELECT p.*, k.nama_kategori AS kategori 
              FROM tb_produk p 
              INNER JOIN tb_kategori k ON p.id_kategori = k.id_kategori 
              WHERE p.nama_produk = ?";

    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 's', $product_name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        die("Produk tidak ditemukan.");
    }

    mysqli_stmt_close($stmt);
} else {
    die("Nama produk tidak valid.");
}

// Handle form submission untuk add to cart
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['detail_produk_submit'])) {
    $cart->addToCart($id_user, $product['id_produk']);

    header("Location: detail_produk.php?item=" . urlencode($product_name));
    exit();
}

// Handle form submission untuk checkout langsung
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['checkout_submit'])) {
    $item_id = isset($_POST['item_id']) ? $_POST['item_id'] : null;
    $qty = isset($_POST['qty']) ? $_POST['qty'] : 1;

    // Simpan data produk ke dalam session
    $_SESSION['checkout_product'] = [
        'item_id' => $item_id,
        'qty' => $qty
    ];

    // Redirect ke halaman direct checkout
    header("Location: direct_checkout.php");
    exit();
}
?>

<?php include "header.php" ?>

<!-- Bagian Produk -->
<section id="products" class="py-3 mb-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 d-flex flex-column align-items-center">
                <?php
                $list_gambar_lainnya = mysqli_query($koneksi, "SELECT * FROM tb_gambar_produk WHERE id_produk = '$product[id_produk]'");
                if (mysqli_num_rows($list_gambar_lainnya) == 0) {
                ?>
                    <img src="assets/img/products/<?= $product['gambar_produk']; ?>" alt="<?= htmlspecialchars($product['nama_produk']) ?>" width="400px" height="400px">
                <?php
                } else {
                ?>

                    <div class="owl-carousel owl-theme" style="width: 400px;">
                        <div class="item">
                            <img src="assets/img/products/<?= $product['gambar_produk']; ?>" alt="<?= htmlspecialchars($product['nama_produk']) ?>" width="400px" height="400px">
                        </div>
                        <?php
                        while ($row = mysqli_fetch_assoc($list_gambar_lainnya)) {
                        ?>
                            <div class="item">
                                <img src="assets/img/gambar_lainnya/<?= $row['gambar']; ?>" alt="<?= htmlspecialchars($row['gambar']) ?>" width="400px" height="400px" style="min-height: 400px; min-width: 400px;">
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="col-sm-6 px-5">
                <h3 class="font-baloo font-size-24 font-weight-bold text-uppercase"><?= htmlspecialchars($product['nama_produk']); ?></h3>
                <table class="font-rale">
                    <tr>
                        <td valign="top">Brand</td>
                        <td valign="top" style="padding: 5px 10px;">:</td>
                        <td valign="top"><?= $product['brand'] ?></td>
                    </tr>
                    <tr>
                        <td valign="top">Kategori</td>
                        <td valign="top" style="padding: 5px 10px;">:</td>
                        <td valign="top"><?= $product['kategori'] ?></td>
                    </tr>
                    <tr>
                        <td valign="top">Deskripsi</td>
                        <td valign="top" style="padding: 5px 10px;">:</td>
                        <td valign="top"><?= $product['deskripsi'] ?></td>
                    </tr>
                </table>
                <hr class="m-0 my-3">

                <table class="my-3" style="width: 100%;">
                    <tr>
                        <td valign="top" class="font-weight-bold text-danger" align="right">
                            <h3 class="font-weight-bold">RP. <?= number_format(round($product['harga'], 0), 0, ',', '.'); ?></h3>
                        </td>
                    </tr>
                </table>
                <div class="form-row pt-4 font-size-16 font-baloo">
                    <div class="col-12">
                        <form method="post" action="">
                            <input type="hidden" name="item_id" value="<?= $product['id_produk']; ?>">
                            <input type="hidden" name="qty" value="1">
                            <button type="submit" name="checkout_submit" class="btn btn-danger form-control"><?= ($id_user == '' ? 'Login Terlebih Dahulu' : 'Checkout') ?></button>
                        </form>
                    </div>

                    <div class="col">
                        <form method="post">
                            <?php $product_in_cart = $cart->isInCart($id_user, $product['id_produk']);
                            $product_stock = $cart->checkStock($product['id_produk']); ?>

                            <input type="hidden" name="id_produk" value="<?= htmlspecialchars($product['id_produk']); ?>">
                            <input type="hidden" name="id_user" value="<?= $id_user; ?>">

                            <?php if (!$product_stock) : ?>
                                <button type="button" class="btn btn-danger form-control" disabled>Add to Cart</button>
                            <?php elseif ($product_in_cart) : ?>
                                <button type="button" class="btn btn-secondary form-control" disabled>In Cart</button>
                            <?php else : ?>
                                <button type="submit" name="detail_produk_submit" class="btn btn-primary form-control" <?= ($id_user == '' ? 'disabled' : '') ?>><?= ($id_user == '' ? 'Login Terlebih Dahulu' : 'Tambah ke Keranjang') ?></button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>


            </div>
        </div>


    </div>
</section>
<!-- jQUERY -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<!-- Owl Carousel JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha256-pY4aD6W1uNg6MSjRZp5lKCCzUmw7n8cg5pxtlFThDUc=" crossorigin="anonymous"></script>
<script src="index.js"></script>
<?php include "footer.php" ?>