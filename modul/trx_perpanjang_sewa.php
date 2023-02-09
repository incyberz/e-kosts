<?php
// perpanjang sewa
$s = "SELECT *,
(SELECT concat(k.id,';',id_jenis_trx_kunci,';',k.tanggal_trx,';',keterangan) 
    FROM tb_trx_kunci k 
    JOIN tb_trx_bayar y ON k.id_trx_bayar=y.id 
    WHERE y.id=$id_trx ) as trx_kunci
FROM tb_trx_bayar a 
JOIN tb_kamar b ON a.id_kamar=b.id 
JOIN tb_penyewa c ON a.id_penyewa=c.id 
WHERE a.id=$id_trx";
// die($s);

$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)!=1) {
    die('Transaksi tidak ditemukan.');
}
$d=mysqli_fetch_assoc($q);

// echo "<div class='debug'>
// last_bayar: $d[last_bayar]<br>
// trx_kunci: $d[trx_kunci]<br>
// </div>
// ";


$id_last_bayar = $id_trx;
$jatuh_tempo = $d['jatuh_tempo'];
$nominal = $d['nominal'];
$tanggal_trx_bayar = $d['tanggal_trx'];
$dibayar_oleh = $d['dibayar_oleh'];
$id_kamar = $d['id_kamar'];
$nama_kamar = $d['nama_kamar'];




if ($d['trx_kunci'] != '') {
    $rtrx_kunci = explode(';', $d['trx_kunci']);
    $id_trx_kunci = $rtrx_kunci[0];
    $kunci_dipinjam = $rtrx_kunci[1];
    $tanggal_trx_kunci = format_tanggal($rtrx_kunci[2], 1);
    $keterangan_trx_kunci = $rtrx_kunci[3];
} else {
    $id_trx_kunci = 0;
    $kunci_dipinjam = 0;
    $tanggal_trx_kunci = '-';
    $keterangan_trx_kunci = 'Kunci belum pernah dipinjam.';
}

$eta = intval((strtotime($jatuh_tempo)-strtotime('today'))/(60*60*24));
$status_kunci = $kunci_dipinjam ? "Dipinjam oleh $d[nama_penyewa]." : "Ada (sudah dikembalikan dari $d[nama_penyewa])";

$warna = 'merah';
if ($eta>$durasi_warning) {
    $warna = 'hijau';
    $jatuh_tempo_show =  format_tanggal($jatuh_tempo, 0)." (masih $eta hari lagi)";
} elseif ($eta>0) {
    $warna = 'kuning';
    $jatuh_tempo_show =  format_tanggal($jatuh_tempo, 0)." (tinggal $eta hari lagi)";
} elseif ($eta==0) {
    $jatuh_tempo_show =  format_tanggal($jatuh_tempo, 0)." (hari ini)";
} else {
    $jatuh_tempo_show =  format_tanggal($jatuh_tempo, 0)." (nunggak selama ".($eta*-1)." hari)";
}

$last_trx = "
<div class='judul-tabel mb-2'>Pembayaran Terakhir</div>
<table class='table tabel-data'>
  <tr><td width=30%>Tanggal Trx</td><td>".format_tanggal($tanggal_trx_bayar, 1)."</td></tr>
  <tr><td>Nominal</td><td>$nominal</td></tr>
  <tr><td>Dibayar oleh</td><td>$dibayar_oleh</td></tr>
  <tr><td>Untuk Kamar</td><td><a href='?kamar_detail&id=$id_kamar'>$nama_kamar</a></td></tr>
  <tr><td>Jatuh Tempo</td><td class='gradasi-$warna $warna'>$jatuh_tempo_show</td></tr>
</table>
";

$jt_tgl = intval(date('d', strtotime($jatuh_tempo)));
$jt_bln = intval(date('m', strtotime($jatuh_tempo)));
$jt_thn = intval(date('Y', strtotime($jatuh_tempo)));

if ($jt_bln==12) {
    $jt_bln = 1;
    $jt_thn++;
} else {
    $jt_bln++;
}
$jatuh_tempo_baru = "$jt_thn-$jt_bln-$jt_tgl";
$jatuh_tempo_baru_show = format_tanggal($jatuh_tempo_baru, 0);

$jt_bln = $jt_bln<10 ? "0$jt_bln" : $jt_bln;
$jt_thn = substr($jt_thn, 2, 2);
$periode = "$jt_bln$jt_thn";

$bayar_via_select = "
<select class='form-control' name=bayar_via>
  <option value=t>Transfer</option>
  <option value=c>Cash</option>
</select>
";

$new_trx = "
<div class='judul-tabel mb-2'>Perpanjangan Sewa</div>
<form method=post>
<table class='table tabel-data'>
  <tr><td width=30%>Tanggal Trx</td><td><i class=abu>(saat ini)</i></td></tr>
  <tr class=debug><td>id_kamar</td><td><input name=id_kamar value=$id_kamar></td></tr>
  <tr class=debug><td>id_penyewa</td><td><input name=id_penyewa value=$d[id_penyewa]></td></tr>
  <tr class=debug><td>id_trx_sebelumnya</td><td><input name=id_trx_sebelumnya value=$id_trx></td></tr>
  <tr class=debug><td>id_jenis_trx</td><td><input name=id_jenis_trx value=2></td></tr>
  <tr><td>Nominal</td><td><input class='form-control' name=nominal value='$nominal' minlength=6 maxlength=7 required><small>Tarif Kamar: $d[tarif]</small></td></tr>
  <tr><td>Dibayar oleh</td><td><input class='form-control' name=dibayar_oleh value='$d[nama_penyewa]' minlength=3 maxlength=50 required><small>Atas nama: $d[nama_penyewa]</small></td></tr>
  <tr><td>Periode</td><td><input class='form-control' name=periode value='$periode' minlength=4 maxlength=4 required><small>Format: MMYY</small></td></tr>
  <tr><td>Untuk Kamar</td><td><a href='?kamar_detail&id=$id_kamar'>$nama_kamar</a></td></tr>
  <tr><td>Jatuh Tempo Baru</td><td class='gradasi-hijau'>$jatuh_tempo_baru_show</td></tr>
  <tr class=debug><td>Jatuh Tempo Baru</td><td><input name=jatuh_tempo value=$jatuh_tempo_baru></td></tr>
  <tr><td>Bayar via</td><td>$bayar_via_select</td></tr>
</table>
<div class='mb-2'><input type=checkbox id=cek> <label for=cek>Saya sudah menerima Bukti Transfer / Uang Cash untuk pembayaran sewa diatas</label></div>
<button class='btn btn-primary btn-block' name=btn_perpanjang_sewa id=btn_perpanjang_sewa disabled>Perpanjang Sewa</button>
</form>
";

$last_trx_kunci = "
<div class='judul-tabel mb-2'>Serah Terima Terakhir Kunci</div>
<table class='table tabel-data'>
  <tr><td width=30%>Tanggal Penyerahan</td><td>$tanggal_trx_kunci</td></tr>
  <tr><td>Status Kunci</td><td>$status_kunci</td></tr>
  <tr><td>Keterangan</td><td>$keterangan_trx_kunci</td></tr>
</table>
";

































?>

<div class="pagetitle">
  <h1>Perpanjangan Sewa</h1>
</div>

<section class="section dashboard">
  <div class="row">
    <style>
      .tabel-data{margin-bottom:30px}
      .judul-tabel{padding:5px; background: linear-gradient(#efc,#cfc); letter-spacing:2px; text-transform: uppercase}
      .td-edit{ background: linear-gradient(#efe,#cfc); cursor:pointer; text-align:center; transition:.2s}
      .td-edit:hover{ background: linear-gradient(#fef,#fcf); letter-spacing:1px}
      .tmp{background:yellow !important; display:none}
    </style>

    <div class="col-lg-6">
      <div class="wadah gradasi-hijau">
      <?=$last_trx?>
      <?=$last_trx_kunci?>

      </div>
    </div>
    <div class="col-lg-6">
      <div class="wadah gradasi-biru">
      <?=$new_trx?>
      </div>
    </div>
  </div>
</section>




























<script>
  $(function(){
    $("#cek").click(function(){
      let c = $(this).prop('checked');
      // alert(c)
      $("#btn_perpanjang_sewa").prop("disabled",!c);

    });
  })
</script>