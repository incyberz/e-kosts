<?php
include '../conn.php';
include 'session_security.php';

$username = isset($_GET['username']) ? $_GET['username'] : die(erid('username'));
$kolom = isset($_GET['kolom']) ? $_GET['kolom'] : die(erid('kolom'));
$isi_baru = isset($_GET['isi_baru']) ? $_GET['isi_baru'] : die(erid('isi_baru'));

$s = "UPDATE tb_petugas set $kolom='$isi_baru' where username='$username'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
?>
sukses