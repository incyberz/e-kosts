<?php
include '../conn.php';
include 'session_security.php';

$new_user = 'user'.date('ymdHis');

$s = "INSERT INTO tb_petugas (username,password,nama_petugas) values ('$new_user','$new_user','AAA USER BARU (CLICK TO EDIT)')";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
?>
sukses