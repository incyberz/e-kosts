<?php
include '../conn.php';
include 'session_security.php';

$username = isset($_GET['username']) ? $_GET['username'] : die(erid('username'));

$s = "DELETE FROM tb_petugas where username='$username'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
?>
sukses