<?php
$id = isset($_GET['id']) ? $_GET['id'] : die('<script>location.replace("?master_kamar")</script>');
$aksi_penyewa = '';

$s = "SELECT *,
(SELECT concat(x.id,';',
jatuh_tempo,';',
nominal,';',
tanggal_trx,';',
dibayar_oleh,';',
id_kamar,';',
nama_kamar,';',
kunci_dipinjam
) 
 from tb_trx x join tb_kamar on x.id_kamar=tb_kamar.id  
 WHERE id_penyewa=a.id order by tanggal_trx DESC limit 1) as last_bayar,

(SELECT keterangan_trx_kunci from tb_trx x join tb_kamar on x.id_kamar=tb_kamar.id  
 WHERE id_penyewa=a.id order by tanggal_trx DESC limit 1) as keterangan_trx_kunci 

FROM tb_penyewa a WHERE a.id=$id";
// die($s);

$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$d=mysqli_fetch_assoc($q);

echo "<div class='debug'>
last_bayar: $d[last_bayar]<br>
</div>
";
$keterangan_trx_kunci = $d['keterangan_trx_kunci']=='' ? '-' : $d['keterangan_trx_kunci'];

$warna = 'merah';
if($d['last_bayar']!=''){
  $rlast_bayar = explode(';', $d['last_bayar']);
  $id_last_bayar = $rlast_bayar[0];
  $jatuh_tempo = $rlast_bayar[1];
  $nominal = $rlast_bayar[2];
  $tanggal_trx_bayar = $rlast_bayar[3];
  $dibayar_oleh = $rlast_bayar[4];
  $id_kamar = $rlast_bayar[5];
  $nama_kamar = $rlast_bayar[6];
  $kunci_dipinjam = $rlast_bayar[7];


  $eta = intval((strtotime($jatuh_tempo)-strtotime('today'))/(60*60*24));
  $status_kunci = $kunci_dipinjam ? "Dipinjam oleh $d[nama_penyewa]." : "Ada (sudah dikembalikan dari $d[nama_penyewa])";

  if ($eta>$durasi_warning) {
      $warna = 'hijau';
      $jatuh_tempo_text =  format_tanggal($jatuh_tempo, 0)." (masih $eta hari lagi)";
  } elseif ($eta>0) {
      $warna = 'kuning';
      $jatuh_tempo_text =  format_tanggal($jatuh_tempo, 0)." (tinggal $eta hari lagi)";
  } elseif ($eta==0) {
      $jatuh_tempo_text =  format_tanggal($jatuh_tempo, 0)." (hari ini)";
  } else {
      $jatuh_tempo_text =  format_tanggal($jatuh_tempo, 0)." (nunggak selama ".($eta*-1)." hari)";
  }

  $last_trx = "
  <div class='judul-tabel'>Pembayaran Terakhir</div>
  <table class='table tabel-data'>
    <tr><td width=30%>Tanggal Trx</td><td>".format_tanggal($tanggal_trx_bayar, 1)."</td></tr>
    <tr><td>Nominal</td><td>".number_format($nominal)."</td></tr>
    <tr><td>Dibayar oleh</td><td>$dibayar_oleh</td></tr>
    <tr><td>Untuk Kamar</td><td><a href='?kamar_detail&id=$id_kamar'>$nama_kamar</a></td></tr>
    <tr><td>Jatuh Tempo</td><td class='gradasi-$warna'>$jatuh_tempo_text</td></tr>
    <tr><td>&nbsp;</td><td><a href='?trx_history&id=$d[id]'>Lihat History Pembayaran atas nama $d[nama_penyewa]</a></td></tr>
  </table>
  ";

  $tanggal_penyerahan = $kunci_dipinjam==1 ? $tanggal_trx_bayar : $tanggal_kembali_kunci;
  $tanggal_penyerahan = format_tanggal($tanggal_penyerahan, 1);

  $last_trx_kunci = "
  <div class='judul-tabel'>Serah Terima Terakhir Kunci</div>
  <table class='table tabel-data'>
    <tr><td width=30%>Tanggal Penyerahan</td><td>$tanggal_penyerahan</td></tr>
    <tr><td>Status Kunci</td><td>$status_kunci</td></tr>
    <tr><td>Keterangan</td><td>$keterangan_trx_kunci</td></tr>
  </table>
  ";

}else{
  $last_trx = "
  <div class='judul-tabel'>Pembayaran Terakhir</div>
  <table class='table tabel-data'>
    <tr><td class=merah>Belum ada data transaksi.</td></tr>
  </table>
  ";
  $last_trx_kunci = "
  <div class='judul-tabel'>Serah Terima Terakhir Kunci</div>
  <table class='table tabel-data'>
    <tr><td class=merah>Belum ada data transaksi.</td></tr>
  </table>
  ";
}


$ilustrasi_penyewa = "
<div class='item-ilustrasi gradasi-$warna'>
  <i class='bi bi-person $warna'></i>
</div>
";

$edit['nama_penyewa'] = "<td class='td-edit text-center' id='td-edit__nama_penyewa__$d[id]'><i class='bi bi-pencil'> edit</i></td>";
$tmp['nama_penyewa'] = "<td class='tmp' id='tmp__nama_penyewa'>$d[nama_penyewa]</td>";
$tampilan['nama_penyewa'] = "<td class='tampilan' id='tampilan__nama_penyewa'>$d[nama_penyewa]</td>";

$edit['no_ktp'] = "<td class='td-edit text-center' id='td-edit__no_ktp__$d[id]'><i class='bi bi-pencil'> edit</i></td>";
$tmp['no_ktp'] = "<td class='tmp' id='tmp__no_ktp'>$d[no_ktp]</td>";
$tampilan['no_ktp'] = "<td class='tampilan' id='tampilan__no_ktp'>$d[no_ktp]</td>";

$edit['no_wa'] = "<td class='td-edit text-center' id='td-edit__no_wa__$d[id]'><i class='bi bi-pencil'> edit</i></td>";
$tmp['no_wa'] = "<td class='tmp' id='tmp__no_wa'>$d[no_wa]</td>";
$tampilan['no_wa'] = "<td class='tampilan' id='tampilan__no_wa'>$d[no_wa]</td>";

$edit['no_hp'] = "<td class='td-edit text-center' id='td-edit__no_hp__$d[id]'><i class='bi bi-pencil'> edit</i></td>";
$tmp['no_hp'] = "<td class='tmp' id='tmp__no_hp'>$d[no_hp]</td>";
$tampilan['no_hp'] = "<td class='tampilan' id='tampilan__no_hp'>$d[no_hp]</td>";

$edit['alamat'] = "<td class='td-edit text-center' id='td-edit__alamat__$d[id]'><i class='bi bi-pencil'> edit</i></td>";
$tmp['alamat'] = "<td class='tmp' id='tmp__alamat'>$d[alamat]</td>";
$tampilan['alamat'] = "<td class='tampilan' id='tampilan__alamat'>$d[alamat]</td>";


$tbpenyewa = "
<div class='judul-tabel'>Detail Kamar</div>
<table class='table tabel-data'>
  <tr><td width=30%>Nama Penyewa</td>$tampilan[nama_penyewa]$tmp[nama_penyewa]$edit[nama_penyewa]</tr>
  <tr><td>Alamat</td>$tampilan[alamat]$tmp[alamat]$edit[alamat]</tr>
  <tr><td>No. KTP</td>$tampilan[no_ktp]$tmp[no_ktp]$edit[no_ktp]</tr>
  <tr><td>Whatsapp</td>$tampilan[no_wa]$tmp[no_wa]$edit[no_wa]</tr>
  <tr><td>No. HP</td>$tampilan[no_hp]$tmp[no_hp]$edit[no_hp]</tr>
</table>
";





































?>

<div class="pagetitle">
  <h1>Penyewa:</h1>
</div>

<section class="section dashboard">
  <div class="row">
    <div class="col-lg-2 pt-2 pb-4">
      <?=$ilustrasi_penyewa?>
      <h3><?=$d['nama_penyewa']?></h3>
      <?=$aksi_penyewa?>
    </div>
    <div class="col-lg-9">
      <?=$tbpenyewa?>
      <?=$last_trx?>
      <?=$last_trx_kunci?>
    </div>
  </div>
</section>




























<script>
  $(function(){
    $(".td-edit").click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let kolom = rid[1];
      let id_penyewa = rid[2];
      let isi = $("#tmp__"+kolom).text();

      let petunjuk = `Data ${kolom} baru:`;


      let isi_baru = prompt(petunjuk,isi);
      if(isi_baru.trim()==isi || !isi_baru) return;



      let link_ajax = `ajax/ajax_penyewa_update.php?id=${id_penyewa}&kolom=${kolom}&isi_baru=${isi_baru}`;

      $.ajax({
        url:link_ajax,
        success:function(a){
          if(a.trim()=='sukses'){
            $("#tmp__"+kolom).text(isi_baru)
            $("#tampilan__"+kolom).text(isi_baru)
          }else{
            alert(a)
          }
        }
      })


    });
  })
</script>