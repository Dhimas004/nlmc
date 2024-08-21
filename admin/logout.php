<?php
require '../function.php';
session_destroy();
setAlert("Anda sudah logout!", "Sukses logout!", "success");
header("Location: ../user/login.php");
