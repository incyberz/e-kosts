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
$saat_ini = date("Y-m-d H:i:s");
$jam_skg = date("H:i:s");
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


# ============================================================
# DEFAULT FUNCTION
# ============================================================
function erid($a)
{
    return "Error, index $a belum terdefinisi.";
}


function frp($x)
{
    return "Rp ".fnum($x).",-";
}

function fnum($x)
{
    switch (strlen($x)) {
        case 1:
        case 2:
        case 3: $y = $x;
            break;

        case 4: $y = substr($x, 0, 1).".".substr($x, 1, 3);
            break;
        case 5: $y = substr($x, 0, 2).".".substr($x, 2, 3);
            break;
        case 6: $y = substr($x, 0, 3).".".substr($x, 3, 3);
            break;

        case 7: $y = substr($x, 0, 1).".".substr($x, 1, 3).".".substr($x, 4, 3);
            break;
        case 8: $y = substr($x, 0, 2).".".substr($x, 2, 3).".".substr($x, 5, 3);
            break;
        case 9: $y = substr($x, 0, 3).".".substr($x, 3, 3).".".substr($x, 6, 3);
            break;

        default: $y = "Out of length digit.";
            break;
    }

    if ($y == 0) {
        return "-";
    } else {
        return "$y";
    }
}
