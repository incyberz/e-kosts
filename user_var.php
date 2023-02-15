<?php
// get session data and profile
$cusername = isset($_SESSION['ekost_username']) ? $_SESSION['ekost_username'] : die('Anda belum login.');
$s = "SELECT * from tb_petugas WHERE username='$cusername'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$d_petugas=mysqli_fetch_assoc($q);

$profile_na = "uploads/profile_na.gif";
$path_profile = "uploads/$d_petugas[profile]";
$my_profile = (file_exists($path_profile) and $d_petugas['profile']!='') ? $path_profile : $profile_na;
