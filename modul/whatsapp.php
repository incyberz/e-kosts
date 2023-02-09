<?php
$pesan = isset($_GET['pesan']) ? $_GET['pesan'] : die(erid('pesan'));
$type = isset($_GET['type']) ? $_GET['type'] : die(erid('type'));
$id_trx = isset($_GET['id_trx']) ? $_GET['id_trx'] : die(erid('id_trx'));

$jenis_pesan = $type=='success' ? 'Selamat' : 'Peringatan';
?>
<div class="pagetitle">
  <h1>Notifikasi WhatsApp</h1>
</div>

<section class="section dashboard">
  <div class="alert alert-<?=$type?>">
    <p>Id-trx: <span id="id_trx"><?=$id_trx?></span> || type: <?=$type?></p>
    <textarea class="form-control" id="pesan" rows=10><?=$pesan?></textarea>
    <button class="btn btn-primary mt-2" id="btn_whatsapp"><span style="display:inline-block;background: linear-gradient(#efe,#cfc); padding:0 8px; font-size:30px; border-radius: 5px; margin-right: 10px; box-shadow: 0 0 3px gray"><i class="bi bi-whatsapp hijau"></i></span> Kirim Pesan <?=$jenis_pesan?></button>
    <div>
      <small>
        Saat kirim pesan maka Last Notif dari id-trx diatas akan terupdate.
      </small>
    </div>

  </div>
</section>












<script>
  $(function(){
    $("#btn_whatsapp").click(function(){
      let id_trx = $("#id_trx").text();
      let link_ajax = `ajax/ajax_update_trx.php?id=${id_trx}&kolom=last_notif&isi_baru=1`;
      $.ajax({
        url:link_ajax,
        success:function(a){
          if(a.trim()=='sukses'){
            let text = $("#pesan").text();
            text = text.replace(/(?:\n)/g, '%0a');
            window.open("https://api.whatsapp.com/send?phone=6281318316793&text="+text);
          }else{
            alert(a)
          }
        }
      })
    })
  })
</script>