<?php
include '../conn.php';
include 'session_security.php';

$s = "INSERT INTO tb_penyewa (nama_penyewa) values ('AAAA - PENYEWA BARU (CLICK TO EDIT)')";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
?>
sukses