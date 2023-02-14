<?php
$s = "SELECT * from tb_user ORDER BY nama_user";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));



$tb_user = '<table class="table">
<thead>
    <th>No</th>
    <th>Nama User</th>
    <th>Alamat</th>
    <th>WhatsApp</th>
    <th>Username</th>
    <th>Role</th>
    <th>Aksi</th>
</thead>';

$tr='';
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
  $i++;
  $role_show = $d['role']==1 ? 'Admin' : '-';
  $tr .= "
  <tr id=tr__$d[username]>
    <td>$i</td>
    <td class='td-edit' id='nama_user__$d[username]'>$d[nama_user]</td>
    <td class='td-edit' id='alamat__$d[username]'>$d[alamat]</td>
    <td class='td-edit' id='no_wa__$d[username]'>$d[no_wa]</td>
    <td class='td-edit' id='username__$d[username]'>$d[username]</td>
    <td class='' id='role__$d[username]'>$role_show</td>
    <td>
      <button class='btn btn-danger btn-sm btn_aksi' id='hapus__$d[username]'><i class='bi bi-trash'></i> Hapus</button>
      <a href='?user&username=$d[username]' class='btn btn-info btn-sm'><i class='bi bi-pencil'></i> Ubah Password</button>
    </td>
  </tr>
  ";
}

if($tr==''){
  $tb_user .= '<tr><td class=merah>Belum ada data User</td></tr></table>';
}else{
  $tb_user .= "$tr</table>";
}


















?>
<div class="pagetitle">
  <h1>Manage User</h1>
</div>

<section class="section dashboard">
  <button class="btn btn-success btn_aksi" id='tambah__0'>Tambah User</button>
  <div class="card card-primary mt-2">
    <div class="card-body" style="padding-top:15px">
      <?=$tb_user?>
      <button class="btn btn-success btn_aksi" id='tambah__0'>Tambah User</button>
    </div>
  </div><!-- End Card -->
</section>























<script>
  $(function(){
    $(".btn_aksi").click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let username = rid[1];

      if(aksi=='hapus' || aksi=='delete'){
        let y = confirm('Yakin untuk menghapus data ini?');
        if(!y) return;

        let link_ajax = 'ajax/ajax_user_hapus.php?username='+username;
        $.ajax({
          url:link_ajax,
          success:function(a){
            if(a.trim()=='sukses'){
              $('#tr__'+username).fadeOut();
            }else{
              if(a.toLowerCase().search('cannot delete or update a parent row')){
                alert('Gagal menghapus data. \n\nData ini dibutuhkan untuk relasi data ke tabel lain.\n\n'+a);
              }else{
                alert('Gagal menghapus data.');
              }
            }
          }
        })
      } // end of hapus

      if(aksi=='tambah' || aksi=='add'){
        let y = confirm('Ingin menambah data User Baru?');
        if(!y) return;        


        let link_ajax = 'ajax/ajax_user_new.php';
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
      let username = rid[1];
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

      let link_ajax = `ajax/ajax_user_update.php?username=${username}&kolom=${kolom}&isi_baru=${isi_baru}`;

      $.ajax({
        url:link_ajax,
        success:function(a){
          if(a.trim()=='sukses'){
            $("#"+kolom+"__"+username).text(isi_baru)
          }else{
            alert('Gagal mengubah data.\n\n'+a)
          }
        }
      })


    });    
  })
</script>