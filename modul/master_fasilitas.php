<?php
$s = "SELECT a.*,
(SELECT count(1) from tb_kamar where fasilitas like concat('%',a.id,'%')) as sum_pada_kamar  
from tb_fasilitas a ORDER BY nama_fasilitas";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));



$tb_fasilitas = '<table class="table">
<thead>
    <th>No</th>
    <th>Nama Fasilitas</th>
    <th>Jumlah Terpasang</th>
    <th>Aksi</th>
</thead>';

$tr='';
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
  $i++;
  $btn_delete = $d['sum_pada_kamar'] ? '<i class=abu>(terpasang)</i>' : "<button class='btn btn-danger btn-sm btn_aksi' id='hapus__$d[id]'><i class='bi bi-trash'></i> Hapus</button>";
  $tr .= "
  <tr id=tr__$d[id]>
    <td>$i</td>
    <td class='td-edit' id='nama_fasilitas__$d[id]'>$d[nama_fasilitas]</td>
    <td>$d[sum_pada_kamar]</td>
    <td>$btn_delete</td>
  </tr>
  ";
}

$tr = '';

if($tr==''){
  $tb_fasilitas .= '<tr><td class=merah>Belum ada data Fasilitas</td></tr></table>';
}else{
  $tb_fasilitas .= "$tr</table>";
}


















?>
<div class="pagetitle">
  <h1>Master Fasilitas</h1>
</div>

<section class="section dashboard">
  <button class="btn btn-success btn_aksi" id='tambah__0'>Tambah Fasilitas</button>
  <div class="card card-primary mt-2">
    <div class="card-body" style="padding-top:15px">
      <?=$tb_fasilitas?>
      <button class="btn btn-success btn_aksi" id='tambah__0'>Tambah Fasilitas</button>
    </div>
  </div><!-- End Card -->
</section>























<script>
  $(function(){
    $(".btn_aksi").click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id_fasilitas = rid[1];

      if(aksi=='hapus' || aksi=='delete'){
        let y = confirm('Yakin untuk menghapus data ini?');
        if(!y) return;

        let link_ajax = 'ajax/ajax_fasilitas_hapus.php?id='+id_fasilitas;
        $.ajax({
          url:link_ajax,
          success:function(a){
            if(a.trim()=='sukses'){
              $('#tr__'+id_fasilitas).fadeOut();
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
        let y = confirm('Ingin menambah data Fasilitas Baru?');
        if(!y) return;        


        let link_ajax = 'ajax/ajax_fasilitas_new.php';
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

    $(".td-edit").click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let kolom = rid[0];
      let id_fasilitas = rid[1];
      let isi = $(this).text();

      let petunjuk = `Data ${kolom} baru:`;

      let isi_baru = prompt(petunjuk,isi);
      if(!isi_baru || isi_baru.trim()==isi) return;
      
      // VALIDASI UPDATE DATA
      if(kolom=='no_wa' || kolom=='no_hp'){
        if((isi_baru.substring(0, 3)=='628' || isi_baru.substring(0, 2)=='08') && isi_baru.length>9 && isi_baru.length<15){
          // alert('OK');
          if(isi_baru.substring(0, 2)=='08'){
            isi_baru = '62'+ isi_baru.substring(1, isi_baru.length);
          }
        }else{
          alert('Format No. HP tidak tepat. Awali dengan 08xx, antara 10 s.d 13 digit.');
          return;
        }
      }

      let link_ajax = `ajax/ajax_fasilitas_update.php?id=${id_fasilitas}&kolom=${kolom}&isi_baru=${isi_baru}`;

      $.ajax({
        url:link_ajax,
        success:function(a){
          if(a.trim()=='sukses'){
            $("#"+kolom+"__"+id_fasilitas).text(isi_baru)
          }else{
            alert('Gagal mengubah data.')
          }
        }
      })


    });    
  })
</script>