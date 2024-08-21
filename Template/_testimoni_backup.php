<?php
include_once "function.php";
// Mengecek apakah produk sudah ada di keranjang
?>

<!-- New Phones Section -->
<section id="testimoni" class="container mb-5">
    <div class="container card shadow py-3">
        <h4 class="font-rubik bg-primary w-lg-25 two-values py-2 px-5 text-white" style="border-radius: 2px 40px; text-indent: 4px;">Testimoni</h4>

        <!-- Owl Carousel -->
        <div class="owl-carousel owl-theme">
            <?php
            $list_testimoni = mysqli_query($koneksi, "SELECT * FROM tb_testimoni JOIN tb_produk ON tb_produk.id_produk = tb_testimoni.id_produk JOIN tb_user ON tb_user.id_user = tb_testimoni.id_user");
            while ($testimoni = mysqli_fetch_assoc($list_testimoni)) :
            ?>
                <div class="item py-2 bg-white m-3 img-fluid">
                    <div class="product font-rale card p-5 card-item text-center" style="min-height: 450px; max-height: 450px; position: relative;">
                        <a href="detail_produk.php?item=<?= urlencode($testimoni['nama_produk']) ?>">
                            <img class="img-list-cover d-block mx-auto" src="assets/img/products/<?= htmlspecialchars($testimoni['gambar_produk']); ?>" alt="<?= htmlspecialchars($testimoni['gambar_produk']); ?>" style="max-width: 150px; max-height: 150px; border-radius: 10px;">
                        </a>

                        <div class="text-center mt-3">
                            <h6 class="font-weight-bold"><?= htmlspecialchars($testimoni['nama_produk']) ?></h6>
                        </div>
                        <div style="display: flex; align-items: end; justify-content: center;">
                            <div style="font-size: 12px;">
                                [<?= ucwords($testimoni['username']) ?>]<br />
                                <span><?= $testimoni['testimoni'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            <!-- !Item -->
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