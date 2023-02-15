<?php
if(isset($_POST['clogout'])){
  session_unset();
  echo '<script>location.replace("?")</script>';
  exit;
}
?>
<div class="pagetitle">
  <h1>Logout</h1>
</div>

<section class="section dashboard">
  Halo <?=$d_petugas['nama_petugas'] ?>! Jika Anda ingin logout silahkan tekan Logout.
  <hr>
  <form method=post>
    <button class="btn btn-warning" name=clogout>Ya, Logout</button>
  </form>
  
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