<?php
$s = "SELECT * from tb_penyewa ORDER BY nama_penyewa";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));



$tb_penyewa = '<table class="table">
<thead>
    <th>No</th>
    <th>Nama Penyewa</th>
    <th>Alamat</th>
    <th>WhatsApp</th>
    <th>No HP</th>
    <th>No KTP</th>
    <th>Aksi</th>
</thead>';

$tr='';
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
  $i++;
  $tr .= "
  <tr id=tr__$d[id]>
    <td>$i</td>
    <td class='editable' id='nama_penyewa__$d[id]'>$d[nama_penyewa]</td>
    <td class='editable' id='alamat__$d[id]'>$d[alamat]</td>
    <td class='editable' id='no_wa__$d[id]'>$d[no_wa]</td>
    <td class='editable' id='no_hp__$d[id]'>$d[no_hp]</td>
    <td class='editable' id='no_ktp__$d[id]'>$d[no_ktp]</td>
    <td>
      <button class='btn btn-danger btn-sm btn_aksi' id='hapus__$d[id]'><i class='bi bi-trash'></i> Hapus</button>
      <a href='?penyewa&id=$d[id]' class='btn btn-info btn-sm'><i class='bi bi-person'></i> Detail</button>
    </td>
  </tr>
  ";
}

if($tr==''){
  $tb_penyewa .= '<tr><td class=merah>Belum ada data Penyewa</td></tr></table>';
}else{
  $tb_penyewa .= "$tr</table>";
}


















?>
<div class="pagetitle">
  <h1>Master Penyewa</h1>
</div>

<section class="section dashboard">
  <button class="btn btn-success btn_aksi" id='tambah__0'>Tambah Penyewa</button>
  <div class="card card-primary mt-2">
    <div class="card-body" style="padding-top:15px">
      <?=$tb_penyewa?>
      <button class="btn btn-success btn_aksi" id='tambah__0'>Tambah Penyewa</button>
    </div>
  </div><!-- End Card -->
</section>























<script>
  $(function(){
    $(".btn_aksi").click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id_penyewa = rid[1];

      if(aksi=='hapus' || aksi=='delete'){
        let y = confirm('Yakin untuk menghapus data ini?');
        if(!y) return;

        let link_ajax = 'ajax/ajax_penyewa_hapus.php?id='+id_penyewa;
        $.ajax({
          url:link_ajax,
          success:function(a){
            if(a.trim()=='sukses'){
              $('#tr__'+id_penyewa).fadeOut();
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
        let y = confirm('Ingin menambah data Penyewa Baru?');
        if(!y) return;        


        let link_ajax = 'ajax/ajax_penyewa_new.php';
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

    $(".editable").click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let kolom = rid[0];
      let id_penyewa = rid[1];
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

      let link_ajax = `ajax/ajax_penyewa_update.php?id=${id_penyewa}&kolom=${kolom}&isi_baru=${isi_baru}`;

      $.ajax({
        url:link_ajax,
        success:function(a){
          if(a.trim()=='sukses'){
            $("#"+kolom+"__"+id_penyewa).text(isi_baru)
          }else{
            alert('Gagal mengubah data.')
          }
        }
      })


    });    
  })
</script>