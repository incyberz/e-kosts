<?php
include '../conn.php';
include 'session_security.php';

$id = isset($_GET['id']) ? $_GET['id'] : die(erid('id'));
$kolom = isset($_GET['kolom']) ? $_GET['kolom'] : die(erid('kolom'));
$isi_baru = isset($_GET['isi_baru']) ? $_GET['isi_baru'] : die(erid('isi_baru'));

$s = "UPDATE tb_trx set $kolom='$isi_baru' where id='$id'";
if ($kolom=='last_notif') {
    $s = "UPDATE tb_trx set last_notif=CURRENT_TIMESTAMP where id='$id'";
}
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
?>
sukses