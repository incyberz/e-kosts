<?php
$homes = '';
$tbhomes = '';
$kamar_total = 0;
$kamar_ok = 0;
$kamar_terisi = 0;
$terbayar = 0;
$jumlah_jt = 0;
$jumlah_hampir_jt=0;

$fasilitas = [];
$s = "SELECT * from tb_fasilitas";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($d=mysqli_fetch_assoc($q)) {
  $fasilitas[$d['id']] = $d['nama_fasilitas'];
}



$s = "SELECT *,
(SELECT kunci_dipinjam FROM tb_trx WHERE id_kamar=a.id order by tanggal_trx DESC limit 1) as kunci_dipinjam, 
(SELECT concat(id,';',jatuh_tempo,';',nominal) FROM tb_trx WHERE id_kamar=a.id order by tanggal_trx DESC limit 1) as trx,  
(SELECT concat(id_penyewa,';',nama_penyewa) FROM tb_trx b JOIN tb_penyewa p ON b.id_penyewa=p.id WHERE id_kamar=a.id order by tanggal_trx DESC limit 1) as penyewa  

from tb_kamar a ";
// die($s);

$q = mysqli_query($cn, $s) or die(mysqli_error($cn));


$tbhomes = '<table class="table">
<thead>
    <th>No. Kamar</th>
    <th>Nama Kamar</th>
    <th colspan=2>Status</th>
    <th>Aksi</th>
</thead>';

while ($d=mysqli_fetch_assoc($q)) {
    // echo "<div class='debug'>
    // trx: $d[trx]<br>
    // penyewa: $d[penyewa]<br>
    // </div>
    // ";

    $fill = '';
    $warna = 'biru';
    $dash = 'dash';
    $is_terisi = 'Kosong';
    $is_kondisi = 'Rusak';
    $status_kunci = 'Ada';
    $jatuh_tempo_ket = '-';

    if($d['trx']!=''){
      $rpenyewa = explode(';', $d['penyewa']);
      $id_penyewa = $rpenyewa[0];
      $nama_penyewa = $rpenyewa[1];

      $rtrx = explode(';', $d['trx']);
      $id_trx = $rtrx[0];
      $jatuh_tempo = $rtrx[1];
      $nominal = $rtrx[2];

      $eta = intval((strtotime($jatuh_tempo)-strtotime('now'))/(60*60*24));
    }else{
      $eta=0;
    }


    $kamar_total++;
    if ($d['kondisi']==1) {
        $kamar_ok++;
        $dash = 'exclamation';
        $is_kondisi = 'OK';

        if ($d['kunci_dipinjam']==1) {
            $kamar_terisi++;
            $fill='-fill';
            $is_terisi='Terisi';
            $status_kunci = "dipinjam oleh <b><a href='?penyewa&id=$id_penyewa'>$nama_penyewa</a></b>";

            if ($eta<=0) {
                $jumlah_jt++;
                $warna = 'merah';
                $dash = 'exclamation';
                if ($eta==0) {
                    $jatuh_tempo_ket = "hari ini ($jatuh_tempo)";
                } else {
                    $jatuh_tempo_ket = "nunggak ".($eta*-1)." hari ($jatuh_tempo)";
                }
            } elseif ($eta<=$durasi_warning) {
                $jumlah_hampir_jt++;
                $warna = 'kuning';
                $dash = 'exclamation';
                $jatuh_tempo_ket = "$eta hari lagi ($jatuh_tempo)";
            } else {
                $terbayar++;
                $warna = 'hijau';
                $dash = 'check';
                $jatuh_tempo_ket = "$eta hari lagi ($jatuh_tempo)";
            }
        }
    } else {
        // rusak
        $warna = 'merah';
    }





    // master kamar
    $no_kamar = $d['no_kamar']<10 ? '0'.$d['no_kamar'] : $d['no_kamar'];
    $d['deskripsi'] = $d['deskripsi']=='' ? '-' : $d['deskripsi'];

    // fasilitas list
    if($d['fasilitas']==''){
      $li_fasilitas = '<i class=abu>(belum ada)</i>';
    }else{
      $li_fasilitas = '';
      $ridfas = explode(';',$d['fasilitas']);
      for ($i=0; $i < count($ridfas); $i++) {
        if($ridfas[$i]==''){
          continue; 
        }else{
          $li_fasilitas.= "<li>".$fasilitas[$ridfas[$i]]."</li>";
        }
      }
    }
    $ul_fasilitas = "<ul>$li_fasilitas</ul>";


    $tbhomes .= "
        <tr>
            <td>$no_kamar</td>
            <td>
                <a href='?kamar_detail&id=$d[id]'>$d[nama_kamar] ~ <i class='bi bi-pencil'></i></a>
                <br>
                <small>
                    <i>Rp ".number_format($d['tarif'])."/bulan</i>
                    <br/>
                    <b>Fasilitas</b>: <a href='?kamar_detail&id=$d[id]'><i class='bi bi-pencil'></i></a> 
                    $ul_fasilitas
                </small>
            </td>
            <td class='gradasi-$warna text-center' style='font-size:50px'>
                <i class='bi bi-house-$dash$fill $warna'></i>
            </td>
            <td style='font-size:12px'>
                <b>Status kamar</b>: $is_kondisi + $is_terisi
                <br/>
                <b>Status kunci</b>: $status_kunci
                <br/>
                <b>Jatuh Tempo</b>: $jatuh_tempo_ket
            </td>
            <td>
                <button class='btn btn-danger btn-sm btn_aksi' id='hapus__$d[id]'><i class='bi bi-trash'></i> Hapus</button>
            </td>
        </tr>
    ";
}
$tbhomes.='</table>';

if(mysqli_num_rows($q)==0){
  $tbhomes = "<div class='alert alert-danger mt-2 mb-2'>Belum ada Data Kamar. Silahkan Anda klik Tambah Kamar!</div>";
}


$kamar_rusak = $kamar_total - $kamar_ok;
$kamar_kosong = $kamar_ok - $kamar_terisi;

















?>
<div class="pagetitle">
  <h1>Master Kamar</h1>
</div>

<section class="section dashboard">
  <div class="card card-primary">

  <div class="card-body" style="padding-top:15px">

      <!-- ilustrasi -->
      <style>
        .ilustrasi{display:flex; flex-wrap:wrap}
        .item-ilustrasi{ border-radius:50%; height:70px; width:70px; font-size:40px; text-align:center; margin-right:10px; margin-bottom:10px; transition:.2s}
        .item-ilustrasi:hover{font-size:45px}
        .item-ket{font-size:12px; margin-top:-10px}
      </style>
      <button class='btn btn-success btn-sm btn_aksi' id='tambah__0'>Tambah Kamar</button>
      <div class="ilustrasi">
        <?=$tbhomes?>
      </div>
      <button class='btn btn-success btn-sm btn_aksi' id='tambah__0'>Tambah Kamar</button>
    </div>
  </div><!-- End Card -->
</section>























<script>
  $(function(){
    $(".btn_aksi").click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id_kamar = rid[1];

      if(aksi=='hapus' || aksi=='delete'){
        let y = confirm('Yakin untuk menghapus data ini?');
        if(!y) return;

        let link_ajax = 'ajax/ajax_kamar_hapus.php?id='+id_kamar;
        $.ajax({
          url:link_ajax,
          success:function(a){
            if(a.trim()=='sukses'){
              $('#tr__'+id_kamar).fadeOut();
            }else{
              if(a.toLowerCase().search('cannot delete or update a parent row')){
                alert('Gagal menghapus data. Data ini dibutuhkan untuk relasi data ke tabel lain.');
              }else{
                alert('Gagal menghapus data.');
              }
            }
          }
        })
      } // end of hapus

      if(aksi=='tambah' || aksi=='add'){
        let y = confirm('Ingin menambah data Kamar Baru?');
        if(!y) return;        


        let link_ajax = 'ajax/ajax_kamar_new.php';
        $.ajax({
          url:link_ajax,
          success:function(a){
            if(a.trim()=='sukses'){
              alert('Add Rows Data Baru sukses. Silahkan Edit isi data tersebut!');
              location.reload();
            }else{
              alert('Gagal menambah data.');
            }
          }
        })        
      }

    }) // end btn_aksi

  })
</script>