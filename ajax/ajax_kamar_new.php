<?php
include '../conn.php';
include 'session_security.php';

$s = "SELECT MAX(no_kamar) as max_no_kamar from tb_kamar";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$d = mysqli_fetch_assoc($q);
$max_no_kamar = $d['max_no_kamar'] + 1;


$s = "INSERT INTO tb_kamar (no_kamar,nama_kamar,tarif) values ($max_no_kamar, 'AAAA - KAMAR BARU (CLICK TO EDIT)',500000)";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
?>
sukses