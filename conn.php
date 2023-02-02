<?php

$online_version = 1;
if ($_SERVER['SERVER_NAME'] == "localhost") {
    $online_version = 0;
}


if ($online_version) {
    $db_server = "localhost";
    $db_user = "qwab7246_admekost";
    $db_pass = "EkostCepLukman2023";
    $db_name = "qwab7246_ekost";
} else {
    $db_server = "localhost";
    $db_user = "root";
    $db_pass = 'mylocalhost2023';
    $db_name = "db_ekost";
}

$cn = new mysqli($db_server, $db_user, $db_pass, $db_name);
if ($cn -> connect_errno) {
    echo "Error Config# Failed to connect to MySQL";
    exit();
}


# ============================================================
# DATE AND TIMEZONE
# ============================================================
date_default_timezone_set("Asia/Jakarta");
$tanggal_skg = date("Y-m-d");
$saat_ini = date("Y-m-d H:i:sa");
$jam_skg = date("H:i:sa");
$tahun_skg = date("Y");
$thn_skg = date("y");
$waktu = "Pagi";
if (date("H")>=9) {
    $waktu = "Siang";
}
if (date("H")>=15) {
    $waktu = "Sore";
}
if (date("H")>=18) {
    $waktu = "Malam";
}


# ============================================================
# DEFAULT UI
# ============================================================
$link_back = "<a href='javascript:history.go(-1)'>Kembali</a>";
$btn_back = "<a href='javascript:history.go(-1)'><button class='btn btn-primary' style='margin-top:5px;margin-bottom:5px'>Kembali</button></a>";
