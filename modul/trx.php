<?php

if (isset($_POST['btn_perpanjang_sewa'])) {
    echo '<pre>';
    var_dump($_POST);
    echo '</pre>';

    $id_trx_sebelumnya = $_POST['id_trx_sebelumnya']==0 ? 'NULL' : $_POST['id_trx_sebelumnya'];
    $jenis_trx = $_POST['id_trx_sebelumnya']==0 ? 'Sewa Baru' : 'Perpanjangan Sewa';


    $s = "INSERT INTO tb_trx 
    (
        id_kamar,
        petugas,
        id_penyewa,
        id_jenis_trx,
        jatuh_tempo,
        id_trx_sebelumnya,
        periode,
        nominal,
        dibayar_oleh,
        bayar_via,
        kunci_dipinjam
        ) VALUES 
    (
        '$_POST[id_kamar]',
        '$cusername',
        '$_POST[id_penyewa]',
        '$_POST[id_jenis_trx]',
        '$_POST[jatuh_tempo]',
        $id_trx_sebelumnya,
        '$_POST[periode]',
        '$_POST[nominal]',
        '$_POST[dibayar_oleh]',
        '$_POST[bayar_via]',
        1)";

    // die($s);
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    echo "<script>location.replace('?kamar_detail&id=$_POST[id_kamar]&info_type=success&info_text=$jenis_trx atas kamar ini sukses.')</script>";
    exit;
}

$id_trx = isset($_GET['id_trx']) ? $_GET['id_trx'] : die(erid('id_trx'));

if ($id_trx=='') {
    die('id_trx is empty');
} elseif ($id_trx<0) {
    // sewa baru
    include 'trx_ambil_kunci.php';
} elseif ($id_trx==0) {
    // sewa baru
    include 'trx_sewa_baru.php';
} else {
    // perpanjang sewa
    include 'trx_perpanjang_sewa.php';
}
