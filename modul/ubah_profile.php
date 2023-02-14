<?php
if(isset($_FILES['file_profile'])){

  $username = $_POST['username'];
  $new_file = "petugas__$username.jpg";
  $target = "uploads/$new_file";

  if(move_uploaded_file($_FILES['file_profile']['tmp_name'],$target)){
    $s = "UPDATE tb_petugas set profile='$new_file' where username='$username'";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    $pesan = "Sukses upload dan update profile petugas.";
    $type = 'success';
  }else{
    $type = 'danger';
    $pesan = "Gagal upload.";
  }
  die("
  <div class='alert alert-$type'>
    $pesan
    <hr/>
    <a class='btn btn-primary btn-sm' href='?manage_petugas'>Back to Manage Petugas</a>
  </div>
  ");
}

$username = isset($_GET['username']) ? $_GET['username'] : die(erid('username'));
$profile = isset($_GET['profile']) ? $_GET['profile'] : die(erid('profile'));

$s = "SELECT nama_petugas, profile from tb_petugas WHERE username='$username'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$d=mysqli_fetch_assoc($q);

$profile_na = "uploads/profile_na.gif";
$path_profile = "uploads/$d[profile]";
$profile = (file_exists($path_profile) and $d['profile']!='') ? $path_profile : $profile_na;
$img_profile = "<img class='profile-big' src='$profile' />";
?>
<div class="pagetitle">
  <h1>Ubah Profile: <?=$d['nama_petugas']?></h1>
</div>

<section class="section dashboard">
  <div class="card card-primary mt-2">
    <div class="card-body" style="padding-top:15px">
      <div class="text-center p-4">
        <?=$img_profile?>
      </div>
      <form method="post" enctype="multipart/form-data">
        <input name=username class=debug value=<?=$username?>>
        <div>
          <input type="file" name="file_profile" accept="image/jpeg" class="form-control" required>
        </div>
        <div class='mt-2'>
          <button class="btn btn-primary btn-block">Upload</button>
        </div>
      </form>
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