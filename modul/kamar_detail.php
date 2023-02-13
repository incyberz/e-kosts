<?php
$id = isset($_GET['id']) ? $_GET['id'] : die('<script>location.replace("?master_kamar")</script>');

$info_type = isset($_GET['info_type']) ? $_GET['info_type'] : ''; //from perpanjangan sewa
$info_text = isset($_GET['info_text']) ? $_GET['info_text'] : ''; //from perpanjangan sewa

if ($info_type!='') {
    die("
    <div class='alert alert-$info_type' id=info>$info_text<hr><a href='?kamar_detail&id=$id' class='btn btn-primary'>Close</a></div>
    ");
}

$aksi_kamar = '';

$s = "SELECT *,
(SELECT concat(
  id,';',
  jatuh_tempo,';',
  nominal,';',
  tanggal_trx,';',
  dibayar_oleh,';',
  kunci_dipinjam) 
  FROM tb_trx WHERE id_kamar=a.id order by tanggal_trx DESC limit 1) as trx,  
(SELECT tanggal_kembali_kunci FROM tb_trx WHERE id_kamar=a.id order by tanggal_trx DESC limit 1) as tanggal_kembali_kunci,  
(SELECT keterangan_trx_kunci FROM tb_trx WHERE id_kamar=a.id order by tanggal_trx DESC limit 1) as keterangan_trx_kunci,  
(SELECT concat(id_penyewa,';',nama_penyewa,';',no_ktp) FROM tb_trx b JOIN tb_penyewa p ON b.id_penyewa=p.id WHERE id_kamar=a.id order by tanggal_trx DESC limit 1) as penyewa,  
(SELECT last_notif FROM tb_trx WHERE id_kamar=a.id order by tanggal_trx DESC limit 1) as last_notif  

from tb_kamar a where a.id=$id";
// die($s);

$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$d=mysqli_fetch_assoc($q);

$fill = '';
$warna = 'biru';
$dash = 'dash';
$is_terisi = 'Kosong';
$kondisi_text = 'Rusak';
$jatuh_tempo_ket = '-';
$kunci_dipinjam = 0;


$tanggal_kembali_kunci =  format_tanggal($d['tanggal_kembali_kunci'], 1);
$keterangan_trx_kunci = $d['keterangan_trx_kunci']=='' ? '-' : $d['keterangan_trx_kunci'];

$no_kamar = $d['no_kamar']<10 ? '0'.$d['no_kamar'] : $d['no_kamar'];
$d['deskripsi'] = $d['deskripsi']=='' ? '-' : $d['deskripsi'];

if ($d['penyewa'] != '') {
    $rpenyewa = explode(';', $d['penyewa']);
    $id_penyewa = $rpenyewa[0];
    $nama_penyewa = $rpenyewa[1];
    $nama_penyewa_link = "<a href='?penyewa&id=$id_penyewa'>$nama_penyewa</a>";
}

if ($d['trx'] != '') {
    $rtrx = explode(';', $d['trx']);
    $id_trx = $rtrx[0];
    $jatuh_tempo = $rtrx[1];
    $nominal = $rtrx[2];
    $tanggal_trx_bayar = $rtrx[3];
    $dibayar_oleh = $rtrx[4];
    $kunci_dipinjam = $rtrx[5];

    $status_kunci = $kunci_dipinjam ? "Dipinjam oleh $nama_penyewa_link" : 'Ada';
    $nominal_rp = frp($nominal);

    $id_trx_show = $id_trx<1000 ? "0$id_trx" : $id_trx;
    $id_trx_show = $id_trx<100 ? "00$id_trx" : $id_trx_show;
    $id_trx_show = $id_trx<10 ? "000$id_trx" : $id_trx_show;


    $eta = intval((strtotime($jatuh_tempo)-strtotime('today'))/(60*60*24));
    $eta_negatif = -$eta;

    // $warna = 'merah';
    if ($eta>$durasi_warning) {
        // $warna = 'hijau';
        $jatuh_tempo_text =  format_tanggal($jatuh_tempo, 0)." (masih $eta hari lagi)";
    } elseif ($eta>0) {
        // $warna = 'kuning';
        $jatuh_tempo_text =  format_tanggal($jatuh_tempo, 0)." (<b>tinggal $eta hari lagi</b>)";
    } elseif ($eta==0) {
        $jatuh_tempo_text =  format_tanggal($jatuh_tempo, 0)." (<b class=merah>hari ini</b>)";
    } else {
        $jatuh_tempo_text =  format_tanggal($jatuh_tempo, 0)." (<b class=merah>nunggak selama ".($eta*-1)." hari</b>)";
    }


    echo "<div class='debug'>
    penyewa: $d[penyewa]<br>
    trx: $d[trx]<br>
    <br>
    </div>
    ";

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

    $last_trx = "
    <div class='judul-tabel'>Pembayaran Terakhir</div>
    <table class='table tabel-data'>
      <tr><td width=30%>Trx-id</td><td>$id_trx_show</td></tr>
      <tr><td>Tanggal Trx</td><td>".format_tanggal($tanggal_trx_bayar, 1)."</td></tr>
      <tr><td>Nominal</td><td>".number_format($nominal)."</td></tr>
      <tr><td>Dibayar oleh</td><td>$dibayar_oleh</td></tr>
      <tr><td>Atas nama</td><td>$nama_penyewa_link</td></tr>
      <tr><td>Jatuh Tempo</td><td class='gradasi-$warna'>$jatuh_tempo_text</td></tr>
      <tr><td>&nbsp;</td><td><a href='?trx_history&id_kamar=$d[id]'>Lihat History Pembayaran kamar ini</a></td></tr>
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
} else {
    $last_trx = "
    <div class='judul-tabel'>Pembayaran Terakhir</div>
    <table class='table tabel-data'>
      <tr><td class=merah>Belum ada Data Transaksi</td></tr>
    </table>
    ";
    $last_trx_kunci = "
    <div class='judul-tabel'>Serah Terima Terakhir Kunci</div>
    <table class='table tabel-data'>
      <tr><td class=merah>Belum ada Penyerahan Kunci</td></tr>
    </table>
    ";
}





if ($d['kondisi']==1) {
    $dash = 'exclamation';
    $kondisi_text = 'Bagus';

    if ($kunci_dipinjam==1) {
        $fill='-fill';
        $is_terisi='Terisi';
        $status_kunci = "dipinjam oleh <b><a href='?penyewa&id=$id_penyewa'>$nama_penyewa</a></b>";


        $pesan = "Yth. $nama_penyewa (Penyewa Kamar No. $no_kamar $d[nama_kamar])";
        $jatuh_tempo_indo = format_tanggal($jatuh_tempo, 0);

        if ($eta<=0) {
            $warna = 'merah';
            $dash = 'exclamation';
            $primary = 'danger';
            $whatsapp = 'Pesan Peringatan';
            if ($eta==0) {
                $jatuh_tempo_ket = "hari ini ($jatuh_tempo)";

                $pesan .= "%0a%0a*Sewa atas kamar $d[nama_kamar] telah Jatuh Tempo hari ini ($jatuh_tempo_indo). Harap segera memperpanjang sewa!*";
            } else {
                $jatuh_tempo_ket = "lewat ".($eta*-1)." hari ($jatuh_tempo)";

                $pesan .= "%0a%0a*Masa Sewa atas kamar $d[nama_kamar] telah Jatuh Tempo lebih dari $eta_negatif hari (Jatuh Tempo: $jatuh_tempo_indo). Harap segera memperpanjang sewa!!*";
            }
        } elseif ($eta<=$durasi_warning) {
            $warna = 'kuning';
            $dash = 'exclamation';
            $jatuh_tempo_ket = "$eta hari lagi ($jatuh_tempo)";
            $primary = 'warning';
            $whatsapp = 'Pesan Notif';

            $pesan .= "%0a%0a*Masa Sewa atas kamar $d[nama_kamar] akan segera berakhir dalam $eta hari lagi. Harap segera memperpanjang sewa!*";
        } else {
            $warna = 'hijau';
            $dash = 'check';
            $jatuh_tempo_ket = "$eta hari lagi ($jatuh_tempo)";
            $primary = 'success';
            $whatsapp = 'Pesan Info';

            $pesan .= "%0a%0a*Terimakasih atas pembayaran sewa Kamar $d[nama_kamar]*. Informasi Jatuh Tempo Anda masih dalam $eta hari lagi.";
        }

        $pesan .= " Untuk memperpanjang Jatuh Tempo bulan berikutnya dapat Anda akses pada laman $link_upload . Terimakasih.";
        $pesan .= "%0a";
        $pesan .= "%0aDetail Pembayaran Terakhir:";
        $pesan .= "%0a~ Trx-id: $id_trx_show";
        $pesan .= "%0a~ Tanggal: $tanggal_trx_bayar";
        $pesan .= "%0a~ Nominal: $nominal_rp";
        $pesan .= "%0a~ Jatuh Tempo: $jatuh_tempo_indo";
        $pesan .= "%0a~ Dibayar oleh: $dibayar_oleh";
        $pesan .= "%0a~ Atas nama: $nama_penyewa";
        $pesan .= "%0a";
        $pesan .= "%0a[Admin e-Kost Pelangi Yes :: $saat_ini]";


        $aksi_kamar = "
        <div class='mb-2'><a href='?trx&id_trx=$id_trx&id_jenis_trx=2' class='btn btn-primary btn-sm btn-block'>Perpanjang Sewa</a></div>
        <div class='mb-2'><a href='?trx&id_trx=-$id_trx' class='btn btn-warning btn-sm btn-block'>Ambil Kunci</a></div>
        <div class='mb-2'>
          <a href='?whatsapp&id_trx=$id_trx&type=$primary&pesan=$pesan' class='btn btn-$primary btn-sm btn-block'>$whatsapp</a>
          <div>Last Notif: $last_notif_show</div>
        </div>
        ";
    } else {
        // tidak disewakan
        $aksi_kamar = "
        <div class='mb-2'><a href='?trx&id_trx=0&id_kamar=$d[id]' class='btn btn-primary btn-sm btn-block'>Sewakan</a></div>
        ";
    }
} else {
    // rusak
    $warna = 'merah';
}






$ilustrasi_kamar = "
<div class='item-ilustrasi gradasi-$warna'>
  <i class='bi bi-house-$dash$fill $warna'></i>
</div>
";

$edit['no_kamar'] = "<td class='td-edit text-center' id='td-edit__no_kamar__$d[id]'><i class='bi bi-pencil'> edit</i></td>";
$edit['nama_kamar'] = "<td class='td-edit text-center' id='td-edit__nama_kamar__$d[id]'><i class='bi bi-pencil'> edit</i></td>";
$edit['lokasi'] = "<td class='td-edit text-center' id='td-edit__lokasi__$d[id]'><i class='bi bi-pencil'> edit</i></td>";
$edit['kondisi'] = "<td class='td-edit text-center' id='td-edit__kondisi__$d[id]'><i class='bi bi-pencil'> edit</i></td>";
$edit['tarif'] = "<td class='td-edit text-center' id='td-edit__tarif__$d[id]'><i class='bi bi-pencil'> edit</i></td>";
$edit['deskripsi'] = "<td class='td-edit text-center' id='td-edit__deskripsi__$d[id]'><i class='bi bi-pencil'> edit</i></td>";

$tmp['no_kamar'] = "<td class='tmp' id='tmp__no_kamar'>$d[no_kamar]</td>";
$tmp['nama_kamar'] = "<td class='tmp' id='tmp__nama_kamar'>$d[nama_kamar]</td>";
$tmp['lokasi'] = "<td class='tmp' id='tmp__lokasi'>$d[lokasi]</td>";
$tmp['kondisi'] = "<td class='tmp' id='tmp__kondisi'>$d[kondisi]</td>";
$tmp['tarif'] = "<td class='tmp' id='tmp__tarif'>$d[tarif]</td>";
$tmp['deskripsi'] = "<td class='tmp' id='tmp__deskripsi'>$d[deskripsi]</td>";

$tampilan['no_kamar'] = "<td class='tampilan' id='tampilan__no_kamar'>$no_kamar</td>";
$tampilan['nama_kamar'] = "<td class='tampilan' id='tampilan__nama_kamar'>$d[nama_kamar]</td>";
$tampilan['lokasi'] = "<td class='tampilan' id='tampilan__lokasi'>$d[lokasi]</td>";
$tampilan['kondisi'] = "<td class='tampilan' id='tampilan__kondisi'>$kondisi_text</td>";
$tampilan['tarif'] = "<td class='tampilan' id='tampilan__tarif'>".number_format($d['tarif'])."/bulan</td>";
$tampilan['deskripsi'] = "<td class='tampilan' id='tampilan__deskripsi'>$d[deskripsi]</td>";


$tbkamar = "
<div class='judul-tabel'>Detail Kamar</div>
<table class='table tabel-data'>
  <tr><td width=30%>No. Kamar</td>$tampilan[no_kamar]$tmp[no_kamar]$edit[no_kamar]</tr>
  <tr><td>Nama Kamar</td>$tampilan[nama_kamar]$tmp[nama_kamar]$edit[nama_kamar]</tr>
  <tr><td>Lokasi</td>$tampilan[lokasi]$tmp[lokasi]$edit[lokasi]</tr>
  <tr><td>Kondisi</td>$tampilan[kondisi]$tmp[kondisi]$edit[kondisi]</tr>
  <tr><td>Tarif</td>$tampilan[tarif]$tmp[tarif]$edit[tarif]</tr>
  <tr><td>Deskripsi</td>$tampilan[deskripsi]$tmp[deskripsi]$edit[deskripsi]</tr>
</table>
";

$li_fas = '';
$s = "SELECT * from tb_fasilitas order by nama_fasilitas";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($f=mysqli_fetch_assoc($q)) {
  // $fas_checked = 'unchecked';

  if(strpos(';'.$d['fasilitas'],$f['id'])){
    $fas_checked = 'checked';
    $tersedia_sty = 'fas_tersedia';
  }else{
    $fas_checked = 'unchecked';
    $tersedia_sty = '';
  }

  $li_fas .= "<li class='fasilitas $tersedia_sty p-2'><input type=checkbox id='fas__$f[id]' class='fas_kamar' $fas_checked value='$f[id]'> <label for='fas__$f[id]'>$f[nama_fasilitas] </label></li>";
}


$fas = "
<ul style='list-style:none; padding:0'>
  $li_fas
</ul>
";

$tbfas = "
<div class='judul-tabel'>Fasilitas</div>
<div class='rowsd'>
  $fas
</div>
";






































?>

<div class="pagetitle">
  <h1>Kamar No. <?=$no_kamar?></h1>
</div>

<section class="section dashboard">
  <div class="row">
    <div class="col-lg-2 pt-2 pb-4">
      <?=$ilustrasi_kamar?>
      <h3><?=$d['nama_kamar']?></h3>
      <div class='mb-2'>Status: <?=$is_terisi?></div>
      <?=$aksi_kamar?>
    </div>
    <div class="col-lg-9">
      <?=$tbkamar?>
      <?=$tbfas?>
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
      let id_kamar = rid[2];
      let isi = $("#tmp__"+kolom).text();

      let petunjuk = `Data ${kolom} baru:`;

      if(kolom=='kondisi') petunjuk += ' 1=Bagus, 0=Rusak';
      if(kolom=='tarif') petunjuk += ' (masukan hanya angka)';

      let isi_baru = prompt(petunjuk,isi);
      if(isi_baru.trim()==isi || !isi_baru) return;
      
      if(kolom=='kondisi'){
        isi_baru = parseInt(isi_baru);
        if(isi_baru>1 || isi_baru<0){
        alert('Untuk kondisi hanya boleh berisi nilai 1=Bagus dan 0=Rusak\n\nSilahkan coba kembali'+isi_baru);
        return;

        }
      }

      if (kolom=='tarif') {
        isi_baru = parseInt(isi_baru);
        if(isNaN(isi_baru)){
          alert('Untuk tarif hanya boleh angka.\n\nSilahkan coba kembali');
          return;
        }
        if(isi_baru<100000 || isi_baru>1000000){
          alert('Untuk tarif hanya berkisar antara 100.000 s.d 1.000.000\n\nSilahkan coba kembali');
          return;
        }
      }


      let link_ajax = `ajax/ajax_update_kamar.php?id=${id_kamar}&kolom=${kolom}&isi_baru=${isi_baru}`;

      $.ajax({
        url:link_ajax,
        success:function(a){
          if(a.trim()=='sukses'){
            if(kolom=='kondisi'){ location.reload(); return; }
            $("#tmp__"+kolom).text(isi_baru)
            if(kolom=='tarif') isi_baru = 'Rp '+ Intl.NumberFormat('de-DE').format(isi_baru) +'/bulan';
            if(kolom=='kondisi') isi_baru = isi_baru=='1' ? 'Bagus' : 'Rusak';
            $("#tampilan__"+kolom).text(isi_baru)
          }else{
            alert(a)
          }
        }
      })


    }); // end td-edit

    $(".fasilitas").change(function(){
      let tid = $(this).find(":checkbox")[0].id;
      let rid = tid.split('__');
      let id = rid[1];
      // alert(id); return;
      
      let tid = $(this).find(":checkbox")[0].id;
      
      let isi = $("#tmp__"+kolom).text();

      let petunjuk = `Data ${kolom} baru:`;

      if(kolom=='kondisi') petunjuk += ' 1=Bagus, 0=Rusak';
      if(kolom=='tarif') petunjuk += ' (masukan hanya angka)';

      let isi_baru = prompt(petunjuk,isi);
      if(isi_baru.trim()==isi || !isi_baru) return;
      
      if(kolom=='kondisi'){
        isi_baru = parseInt(isi_baru);
        if(isi_baru>1 || isi_baru<0){
        alert('Untuk kondisi hanya boleh berisi nilai 1=Bagus dan 0=Rusak\n\nSilahkan coba kembali'+isi_baru);
        return;

        }
      }

      if (kolom=='tarif') {
        isi_baru = parseInt(isi_baru);
        if(isNaN(isi_baru)){
          alert('Untuk tarif hanya boleh angka.\n\nSilahkan coba kembali');
          return;
        }
        if(isi_baru<100000 || isi_baru>1000000){
          alert('Untuk tarif hanya berkisar antara 100.000 s.d 1.000.000\n\nSilahkan coba kembali');
          return;
        }
      }


      let link_ajax = `ajax/ajax_update_kamar.php?id=${id_kamar}&kolom=${kolom}&isi_baru=${isi_baru}`;

      $.ajax({
        url:link_ajax,
        success:function(a){
          if(a.trim()=='sukses'){
            if(kolom=='kondisi'){ location.reload(); return; }
            $("#tmp__"+kolom).text(isi_baru)
            if(kolom=='tarif') isi_baru = 'Rp '+ Intl.NumberFormat('de-DE').format(isi_baru) +'/bulan';
            if(kolom=='kondisi') isi_baru = isi_baru=='1' ? 'Bagus' : 'Rusak';
            $("#tampilan__"+kolom).text(isi_baru)
          }else{
            alert(a)
          }
        }
      })


    });    
  })
</script>