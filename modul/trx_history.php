<?php
// sewa baru
$id_kamar = isset($_GET['id_kamar']) ? $_GET['id_kamar'] : die(erid('id_kamar'));
$s = "SELECT a.*,b.nama_penyewa as atas_nama from tb_trx a join tb_penyewa b on a.id_penyewa=b.id WHERE a.id_kamar=$id_kamar";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr='';
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
    $i++;
    $jenis_trx = $d['id_jenis_trx']==1 ? 'Sewa Baru' : 'Perpanjangan';
    $tanggal_trx = format_tanggal($d['tanggal_trx'], 1);
    $jatuh_tempo = format_tanggal($d['jatuh_tempo'], 0);
    $id_trx_sebelumnya = $d['id_trx_sebelumnya'];
    $periode = $d['periode'];
    $nominal = frp($d['nominal']);
    $dibayar_oleh = $d['dibayar_oleh'];
    $atas_nama = $d['atas_nama'];
    $last_notif = $d['last_notif'];
    $bayar_via = $d['bayar_via']=='c' ? 'Cash' : 'Transfer';


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


    $tr .= "
      <tr>
        <td>$i</td>
        <td>$jenis_trx</td>
        <td>$tanggal_trx</td>
        <td>$jatuh_tempo</td>
        <td>$id_trx_sebelumnya</td>
        <td>$periode</td>
        <td>$nominal</td>
        <td>$dibayar_oleh</td>
        <td>$atas_nama</td>
        <td>$last_notif_show</td>
        <td>$bayar_via</td>
      </tr>
    ";
}

echo "
<table class='table'>
  <tr>
    <td>No</td>
    <td>Jenis Trx</td>
    <td>Tanggal</td>
    <td>Jatuh Tempo</td>
    <td>Reff-id</td>
    <td>Periode</td>
    <td>Nominal</td>
    <td>Dibayar oleh</td>
    <td>Atas nama</td>
    <td>Last WA</td>
    <td>Bayar via</td>
  </tr>
  $tr
</table>
";
exit;

$jt_tgl = intval(date('d', strtotime($saat_ini)));
$jt_bln = intval(date('m', strtotime($saat_ini)));
$jt_thn = intval(date('Y', strtotime($saat_ini)));

if ($jt_bln==12) {
    $jt_bln = 1;
    $jt_thn++;
} else {
    $jt_bln++;
}
$jatuh_tempo_baru = "$jt_thn-$jt_bln-$jt_tgl";
$jatuh_tempo_baru_show = format_tanggal($jatuh_tempo_baru, 0);

$jt_bln = $jt_bln<10 ? "0$jt_bln" : $jt_bln;
$jt_thn = substr($jt_thn, 2, 2);
$periode = "$jt_bln$jt_thn";

$bayar_via_select = "
<select class='form-control' name=bayar_via>
  <option value=t>Transfer</option>
  <option value=c>Cash</option>
</select>
";

$options_penyewa='';
$li_wa_hp='';
$s = 'SELECT a.* from tb_penyewa a 
LEFT JOIN tb_trx b ON a.id=b.id_penyewa 
WHERE b.id is null 
order by a.nama_penyewa';
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($p=mysqli_fetch_assoc($q)) {
    $options_penyewa.="<option value=$p[id]>$p[nama_penyewa]</option>";
    $li_wa_hp.="
    <li class='wahp hideit wahp$p[id]'>WhatsApp: $p[no_wa]</li>
    <li class='wahp hideit wahp$p[id]'>No. Hp: $p[no_hp]</li>
    <li class='wahp hideit wahp$p[id]'>
      <a href='?penyewa&id=$p[id]'>-- Ubah Data Penyewa --</a>
    </li>
    ";
}

$id_penyewa_select = "
  <div class=row>
    <div class=col-2>
      Penyewa baru:
    </div>
    <div class=col-7>
      <select class='form-control' name=id_penyewa id=id_penyewa>
        <option value=0>--Select--</option>
        $options_penyewa
      </select>
      <ul class='mb-0 mt-2'>
        $li_wa_hp
      </ul>
    </div>
    <div class=col-3>
      <a href='?tambah_penyewa&id_kamar=$id_kamar&from=sewa_baru' class='btn btn-success btn-sm btn-block' id=btn_tambah_penyewa><i class='bi bi-plus'></i> Tambah</a>
    </div>
  </div>
";

$d['deskripsi'] = $d['deskripsi']=='' ? '-' : $d['deskripsi'];
$no_kamar = $d['no_kamar']<10 ? "0$d[no_kamar]" : $d['no_kamar'];

$new_trx = "
<form method=post>
<table class='table tabel-data'>
  <tr><td width=30%>Tanggal Trx</td><td><i class=abu>(saat ini)</i></td></tr>
  <tr><td colspan=2>
    <div class=wadah>
      <span class=debug>ID-Kamar: <input name=id_kamar value=$d[id]><br></span>
      <span class=debug>ID-trx-sebelumnya: <input name=id_trx_sebelumnya value=0><br></span>
      <i class=system>No. / Nama Kamar :</i> $no_kamar / $d[nama_kamar]<br>
      <i class=system>Tarif:</i> <span style='font-size:25px'>".frp($d['tarif'])."</span><br>
      <i class=system>Deskripsi:</i> $d[deskripsi]<br>
    </div>
  </td></tr>
  <tr><td colspan=2><div class=wadah>$id_penyewa_select</div></td></tr>
  <tr class=debug><td>id_jenis_trx</td><td><input name=id_jenis_trx value=1></td></tr>
  <tr><td>Nominal</td><td><input class='form-control' name=nominal value='$d[tarif]' minlength=6 maxlength=7 required></td></tr>
  <tr><td>Dibayar oleh</td><td><input class='form-control' name=dibayar_oleh id=dibayar_oleh value='' minlength=3 maxlength=50 required><small>Atas nama: <span id=atas_nama>???</span></small></td></tr>
  <tr><td>Periode</td><td><input class='form-control' name=periode value='$periode' minlength=4 maxlength=4 required><small>Format: MMYY</small></td></tr>
  <tr><td>Jatuh Tempo Baru</td><td class='gradasi-hijau'>$jatuh_tempo_baru_show</td></tr>
  <tr class=debug><td>Jatuh Tempo Baru</td><td><input name=jatuh_tempo value=$jatuh_tempo_baru></td></tr>
  <tr><td>Bayar via</td><td>$bayar_via_select</td></tr>
</table>
<div class='mb-2'><input type=checkbox id=cek> <label for=cek>Saya sudah menerima Bukti Transfer / Uang Cash untuk pembayaran sewa baru diatas dan sudah menyerahkan Kunci Kamar No. $no_kamar ($d[nama_kamar])</label></div>
<button class='btn btn-primary btn-block' name=btn_perpanjang_sewa id=btn_perpanjang_sewa disabled>Tambah Data Sewa</button>
</form>
";






?>
<div class="pagetitle">
  <h1>Sewa Baru Kamar <?=$d['nama_kamar']?></h1>
</div>

<section class="section dashboard">
  <div class="row">
    <div class="col-lg-6">
      <div class="wadah gradasi-biru">
      <?=$new_trx?>
      </div>
    </div>
  </div>
</section>




























<script>
  $(function(){
    $("#cek").click(function(){
      let c = $(this).prop('checked');
      // alert(c)
      $("#btn_perpanjang_sewa").prop("disabled",!c);

    });
    
    $("#id_penyewa").change(function(){
      let id_penyewa = $(this).val();
      let nama_penyewa = $(this).find(':selected').text();
      // alert(nama_penyewa)
      $("#dibayar_oleh").val(nama_penyewa);
      $("#atas_nama").text(nama_penyewa);

      $(".wahp").hide();
      $(".wahp"+id_penyewa).fadeIn();

    });

  })
</script>