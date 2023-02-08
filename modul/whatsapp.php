<?php
$pesan = isset($_GET['pesan']) ? $_GET['pesan'] : die(erid('pesan'));
$type = isset($_GET['type']) ? $_GET['type'] : die(erid('type'));

$jenis_pesan = $type=='success' ? 'Selamat' : 'Peringatan';
?>
<div class="pagetitle">
  <h1>Notifikasi WhatsApp</h1>
</div>

<section class="section dashboard">
  <div class="alert alert-<?=$type?>">
    <textarea class="form-control" id="pesan" rows=10><?=$pesan?></textarea>
    <button class="btn btn-primary mt-2" id="btn_whatsapp"><span style="display:inline-block;background: linear-gradient(#efe,#cfc); padding:0 8px; font-size:30px; border-radius: 5px; margin-right: 10px; box-shadow: 0 0 3px gray"><i class="bi bi-whatsapp hijau"></i></span> Kirim Pesan <?=$jenis_pesan?></button>

  </div>
</section>












<script>
  $(function(){
    $("#btn_whatsapp").click(function(){
      let text = $("#pesan").text();
      text = text.replace(/(?:\n)/g, '%0a');
      window.open("https://api.whatsapp.com/send?phone=6281318316793&text="+text);
    })
  })
</script>