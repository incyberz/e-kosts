<?php
$id_trx = -$id_trx;
// perpanjang sewa
$s = "SELECT * 
FROM tb_trx a 
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
$nama_penyewa_link = "<a href=?penyewa&id=$d[id_penyewa]>$d[nama_penyewa]</a>";

$kunci_dipinjam = $d['kunci_dipinjam'];
$tanggal_kembali_kunci = format_tanggal($d['tanggal_kembali_kunci'], 1);
$keterangan_trx_kunci = $d['keterangan_trx_kunci'];


$eta = intval((strtotime($jatuh_tempo)-strtotime('today'))/(60*60*24));
$status_kunci = $kunci_dipinjam ? "Dipinjam oleh $nama_penyewa_link." : "Ada (sudah dikembalikan dari $d[nama_penyewa])";

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


$trx_kunci = "
<div class='judul-tabel mb-2'>Trx Ambil Kunci</div>
<form method=post>
<table class='table tabel-data'>
  <tr><td width=30%>Tanggal Trx</td><td><i class=abu>(saat ini)</i></td></tr>
  <tr class=debug><td>id_kamar</td><td><input name=id_kamar value=$id_kamar></td></tr>
  <tr class=debug><td>id_penyewa</td><td><input name=id_penyewa value=$d[id_penyewa]></td></tr>
  <tr class=debug><td>id_trx_sebelumnya</td><td><input name=id_trx_sebelumnya value=$id_trx></td></tr>
  <tr class=debug><td>id_jenis_trx</td><td><input name=id_jenis_trx value=2></td></tr>
  <tr><td>Dari Kamar</td><td><a href='?kamar_detail&id=$id_kamar'>$nama_kamar</a></td></tr>
  <tr><td>Atas nama</td><td>$nama_penyewa_link</td></tr>
  <tr><td>Alasan Pengambilan</td><td>
  <textarea class='form-control' name=keterangan_trx_kunci minlength=6 required>Telah jatuh tempo.</textarea>
  <small>Jatuh Tempo: $jatuh_tempo_show</small></td></tr>
</table>
<div class='mb-2'><input type=checkbox id=cek> <label for=cek>Kunci kamar ini sudah ada di tangan saya</label></div>
<button class='btn btn-primary btn-block' name=btn_ambil_kunci id=btn_ambil_kunci disabled>Simpan</button>
</form>
";


$tanggal_penyerahan = $kunci_dipinjam==1 ? $tanggal_trx_bayar : $tanggal_kembali_kunci;
$tanggal_penyerahan = format_tanggal($tanggal_penyerahan, 1);

$last_trx_kunci = "
<div class='judul-tabel mb-2'>Serah Terima Terakhir Kunci</div>
<table class='table tabel-data'>
  <tr><td width=30%>Tanggal Penyerahan</td><td>$tanggal_penyerahan</td></tr>
  <tr><td>Status Kunci</td><td>$status_kunci</td></tr>
  <tr><td>Keterangan</td><td>$keterangan_trx_kunci</td></tr>
</table>
";

































?>

<div class="pagetitle">
  <h1>Ambil Kunci</h1>
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
      <?=$trx_kunci?>
      </div>
    </div>
  </div>
</section>




























<script>
  $(function(){
    $("#cek").click(function(){
      let c = $(this).prop('checked');
      // alert(c)
      $("#btn_ambil_kunci").prop("disabled",!c);

    });
  })
</script>