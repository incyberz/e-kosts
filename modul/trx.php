<?php

if (isset($_POST['btn_perpanjang_sewa'])) {
    echo '<pre>';
    var_dump($_POST);
    echo '</pre>';

    $s = "INSERT INTO tb_trx_bayar 
    (id_kamar,id_penyewa,id_jenis_trx,jatuh_tempo,id_trx_sebelumnya,periode,nominal,dibayar_oleh,bayar_via) VALUES 
    ('$_POST[id_kamar]','$_POST[id_penyewa]','$_POST[id_jenis_trx]','$_POST[jatuh_tempo]','$_POST[id_trx_sebelumnya]','$_POST[periode]','$_POST[nominal]','$_POST[dibayar_oleh]','$_POST[bayar_via]')";

    // die($s);
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    echo "<script>location.replace('?kamar_detail&id=13&info_type=success&info_text=Perpanjangan Sewa atas kamar ini sukses.')</script>";
    exit;
}

$id_trx = isset($_GET['id_trx']) ? $_GET['id_trx'] : die(erid('id_trx'));

if ($id_trx=='') {
    die('id_trx is empty');
} elseif ($id_trx==0) {
    // sewa baru
    include 'trx_sewa_baru.php';
} else {
    // perpanjang sewa
    include 'trx_perpanjang_sewa.php';
}
