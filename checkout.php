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
if (isset($_GET['id'])) {
    include('Template/_checkout_ulang.php');
} else {
    include('Template/_checkout.php');
}
/*  include cart items if it is not empty */

?>

<?php
// include footer.php file
include('footer.php');
?>
