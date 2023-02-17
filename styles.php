<style>
  .debug{background:red; font-family: consolas; font-size: small; padding:10px; <?php if (!$dm) {
      echo 'display:none;';
  } ?>}

  .tebal,.bold{font-weight:bold}
  .small,.kecil{font-size:small}


  .merah{color:red}
  .hijau{color:green}
  .kuning{color:orange}
  .biru{color:blue}
  .ungu{color:purple}
  .abu{color:gray}
  .gradasi-biru{
    background:linear-gradient(#eef,#ccf)
  }
  .gradasi-toska{
    background:linear-gradient(#eff,#cff)
  }
  .gradasi-hijau{
    background:linear-gradient(#efe,#cfc)
  }
  .gradasi-merah{
    background:linear-gradient(#fee,#fcc)
  }
  .gradasi-kuning{
    background:linear-gradient(#ffe,#ffc)
  }

  .btn-block { width:100%}
  .wadah{
    border: solid 1px #ccc;
    border-radius: 10px;
    padding: 12px;
    margin-bottom: 15px;
  }
  .system {font-family:consolas}
  .hideit{display:none}

  .item-ilustrasi{ border-radius:50%; height:100px; width:100px; font-size:60px; text-align:center; margin-right:10px; margin-bottom:10px; transition:.2s}
  .tabel-data{margin-bottom:30px}
  .judul-tabel{padding:5px; background: linear-gradient(#efc,#cfc); letter-spacing:2px; text-transform: uppercase}
  .tmp{background:yellow !important; display:none}

  .td-edit{ background: linear-gradient(#efe,#cfc); cursor:pointer; transition:.2s}
  .td-edit:hover{ background: linear-gradient(#fef,#fcf); letter-spacing:1px}
  .fasilitas{color:gray; transition: .2s}
  .fasilitas label{cursor: pointer;}
  .fasilitas:hover{color:darkblue; text-transform: uppercase; letter-spacing: .3px}
  .fas_tersedia{color:green; font-weight:bold; background:linear-gradient(#efe,#cfc); text-transform: uppercase}
  .profile-petugas, .profile-big{box-shadow:1px 1px 5px gray; border-radius:50%; background:white; padding: 2px; transition:.2s; object-fit:cover}
  .profile-petugas:hover,.profile-big:hover{transform:scale(1.2)}
  .profile-petugas{height: 50px; width:50px;}
  .profile-big{height: 150px; width:150px;}
  .nama-kolom{font-family:consolas;font-size:small; color:gray; font-style:italic}
</style>