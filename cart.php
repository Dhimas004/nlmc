<?php
ob_start();

include('header.php');
// Include the connection file
include('function.php');



// Create a Product object
$product = new Product($koneksi);

?>

<?php
/*  include cart items if it is not empty */
$product->getData('tb_cart') ? include('Template/_cart-template.php') :  include('Template/notFound/_cart_notFound.php');
/*  include cart items if it is not empty */

?>

<?php
// include footer.php file
include('footer.php');
?>
<script>
    $(document).ready(function() {
        $('#nav-beranda').removeClass('active');
        $('#nav-keranjang').addClass('active');
    })
</script>