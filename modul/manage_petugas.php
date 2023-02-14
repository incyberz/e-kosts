<?php
$s = "SELECT a.*,
(SELECT 1 from tb_trx where petugas=a.username limit 1) as is_punya_trx from tb_petugas a ORDER BY nama_petugas";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));



$tb_petugas = '<table class="table">
<thead>
    <th>No</th>
    <th colspan=2>Nama Petugas</th>
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
  // echo "is_punya_trx==$d[is_punya_trx]<br>";
  $td_edit = $d['is_punya_trx'] ? '' : 'td-edit';
  $btn_hapus = $cusername==$d['username'] ? '' : "<button class='btn btn-danger btn-sm btn_aksi' id='hapus__$d[username]'><i class='bi bi-trash'></i> Hapus</button>";

  $ubah_password = "<button class='btn btn-info btn-sm btn_aksi' id='ubah_password__$d[username]'><i class='bi bi-pencil'></i> Ubah Password</button>";

  $profile_na = "uploads/profile_na.gif";
  $path_profile = "uploads/$d[profile]";
  $profile = (file_exists($path_profile) and $d['profile']!='') ? $path_profile : $profile_na;
  $img_profile = "<img class='profile-petugas' src='$profile' />";

  $link_img_profile = "<a href='?ubah_profile&profile=$profile&username=$d[username]'>$img_profile</a>";

  $tr .= "
  <tr id=tr__$d[username]>
    <td>$i</td>
    <td>$link_img_profile</td>
    <td class='td-edit' id='nama_petugas__$d[username]'>$d[nama_petugas]</td>
    <td class='td-edit' id='alamat__$d[username]'>$d[alamat]</td>
    <td class='td-edit' id='no_wa__$d[username]'>$d[no_wa]</td>
    <td class='$td_edit' id='username__$d[username]'>$d[username]</td>
    <td class='' id='role__$d[username]'>$role_show</td>
    <td>$btn_hapus $ubah_password</td>
  </tr>
  ";
}

if($tr==''){
  $tb_petugas .= '<tr><td class=merah>Belum ada data Petugas</td></tr></table>';
}else{
  $catatan_tabel = '<div class=mb-3><small>Catatan: username petugas yang sudah melakukan transaksi tidak dapat diubah kembali.</small></div>';
  $tb_petugas .= "$tr</table>$catatan_tabel";
}


















?>
<div class="pagetitle">
  <h1>Manage Petugas</h1>
</div>

<section class="section dashboard">
  <button class="btn btn-success btn_aksi" id='tambah__0'>Tambah Petugas</button>
  <div class="card card-primary mt-2">
    <div class="card-body" style="padding-top:15px">
      <?=$tb_petugas?>
      <button class="btn btn-success btn_aksi" id='tambah__0'>Tambah Petugas</button>
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
        let y = confirm('Ingin menambah data Petugas Baru?');
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

      if(aksi=='ubah_password'){
        let y = confirm(`Ingin mengubah password atas username: ${username}?`);
        if(!y) return;

        let np = prompt('Password baru Anda:');
        if(!y) return;

        let cp = prompt('Konfirmasi Password baru Anda:');
        if(!y) return;

        if(np!==cp){
          alert('Maaf, password baru dan konfirmasi password tidak sama.');
          return;
        }

        let link_ajax = `ajax/ajax_user_update.php?username=${username}&kolom=password&isi_baru=${np}`;

        $.ajax({
          url:link_ajax,
          success:function(a){
            if(a.trim()=='sukses'){
              alert('Sukses mengubah password. \n\nSilahkan diingat dan dicatat password tersebut agar tidak lupa.');
            }else{
              alert('Gagal mengubah data.\n\n'+a)
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