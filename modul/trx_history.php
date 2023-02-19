<?php
// sewa baru
$id_kamar = isset($_GET['id_kamar']) ? $_GET['id_kamar'] : die(erid('id_kamar'));
$s = "SELECT a.*,b.nama_penyewa as atas_nama from tb_trx a join tb_penyewa b on a.id_penyewa=b.id WHERE a.id_kamar=$id_kamar";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr='';
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
    $i++;
    $jenis_trx = $d['id_jenis_trx']==1 ? 'Sewa Baru' : 'Perpanjangan';
    $tanggal_trx = format_tanggal($d['tanggal_trx'], 1);
    $jatuh_tempo = format_tanggal($d['jatuh_tempo'], 0);
    $id_trx_sebelumnya = $d['id_trx_sebelumnya'];
    $periode = $d['periode'];
    $nominal = frp($d['nominal']);
    $dibayar_oleh = $d['dibayar_oleh'];
    $atas_nama = $d['atas_nama'];
    $last_notif = $d['last_notif'];
    $bayar_via = $d['bayar_via']=='c' ? 'Cash' : 'Transfer';


    if ($d['last_notif']=='') {
        $last_notif_show =  '<i class=abu>(none)</i>' ;
    } else {
        $selisih = strtotime('now') - strtotime($d['last_notif']);
        if ($selisih> 60*60*24) {
            $last_notif_show =  intval($selisih/(60*60*24)) . ' hari yang lalu' ;
        } elseif ($selisih> 60*60) {
            $last_notif_show =  intval($selisih/(60*60)) . ' jam yang lalu' ;
        } elseif ($selisih> 60) {
            $last_notif_show =  intval($selisih/60) . ' menit yang lalu' ;
        } else {
            $last_notif_show =  "$selisih detik yang lalu" ;
        }
    }

    $id_pdf = md5($d['tanggal_trx']);

    $tr .= "
      <tr>
        <td>$i</td>
        <td>
          $jenis_trx
          </td>
          <td>
          <span class='nama-kolom'>Tanggal:</span> $tanggal_trx
          <br><span class='nama-kolom'>Jatuh Tempo:</span> $jatuh_tempo
          <br><span class='nama-kolom'>Periode:</span> $periode
          <br><span class='nama-kolom'>Trx Sebelumnya:</span> $id_trx_sebelumnya
        </td>
        <td>
        </td>
        <td>
          <span class='nama-kolom'>Nominal:</span> $nominal
          <br><span class='nama-kolom'>Dibayar oleh:</span> $dibayar_oleh
          <br><span class='nama-kolom'>Atas nama:</span> $atas_nama
          <br><span class='nama-kolom'>Bayar via:</span> $bayar_via
        </td>
        <td>
          <a href='pdf/bukti_bayar.php?id=$id_pdf' class='btn btn-success btn-sm' target='_blank'>Cetak Bukti Bayar</a>
        </td>
      </tr>
    ";
}

echo "
<table class='table'>
  $tr
</table>
";
exit;
