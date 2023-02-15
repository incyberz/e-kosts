<?php
$homes = '';



$nominal_lunas = 0;
$pemasukan_bulan_ini = 0;
$piutang = 0;

$total_transaksi = 0;


$s = "SELECT *,
(SELECT kunci_dipinjam FROM tb_trx WHERE id_kamar=a.id order by tanggal_trx DESC limit 1) as kunci_dipinjam, 
(SELECT id FROM tb_trx WHERE id_kamar=a.id order by tanggal_trx DESC limit 1) as last_id_trx,  
(SELECT jatuh_tempo FROM tb_trx WHERE id_kamar=a.id order by tanggal_trx DESC limit 1) as jatuh_tempo,  
(SELECT nominal FROM tb_trx WHERE id_kamar=a.id order by tanggal_trx DESC limit 1) as nominal  

from tb_kamar a order by no_kamar";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));

$kamar_ok = 0;
$kamar_terisi = 0;
$terbayar = 0;
$jumlah_jt = 0;
$jumlah_hampir_jt = 0;
$kamar_total = 0;

while ($d=mysqli_fetch_assoc($q)) {
    $fill = '';
    $warna = 'biru';
    $dash = 'dash';



    // $eta = intval((strtotime($jatuh_tempo)-strtotime('today'))/(60*60*24));
    $eta = intval((strtotime($d['jatuh_tempo'])-strtotime('today'))/(60*60*24));


    $kamar_total++;
    if ($d['kondisi']==1) {
        $kamar_ok++;
        $dash = 'exclamation';

        if ($d['kunci_dipinjam']==1) {
            $kamar_terisi++;
            $fill='-fill';

            if ($eta<=0) {
                $jumlah_jt++;
                $warna = 'merah';
                $dash = 'exclamation';
            } elseif ($eta<=$durasi_warning) {
                $jumlah_hampir_jt++;
                $warna = 'kuning';
                $dash = 'exclamation';
            } else {
                $terbayar++;
                $warna = 'hijau';
                $dash = 'check';
            }
        }
    } else {
        // rusak
        $warna = 'merah';
    }



    $kamars[$d['id']] = $d;
    // $kamars[$d['id']]['eta'] = $eta;


    // ilustrasi kamar-kamar
    $no_kamar = $d['no_kamar']<10 ? '0'.$d['no_kamar'] : $d['no_kamar'];
    $homes .= "
        <div class='item-ilustrasi gradasi-$warna'>
          <a href='?kamar_detail&id=$d[id]'>
          <i class='bi bi-house-$dash$fill $warna'></i>
          <div class='item-ket'>
            $no_kamar
          </div>
          </a>
        </div>
    ";
}

$kamar_rusak = $kamar_total - $kamar_ok;
$kamar_kosong = $kamar_ok - $kamar_terisi;


// pendapatan
$periode = date('my');
$tanggal_awal = '2023-2-1'; //zzz debug
$tanggal_akhir = '2023-3-1'; //zzz debug
$s = "SELECT sum(nominal) as pendapatan_bulan_ini from tb_trx where tanggal_trx >= '$tanggal_awal' and tanggal_trx < '$tanggal_akhir' ";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$d = mysqli_fetch_assoc($q);
$pendapatan_bulan_ini = $d['pendapatan_bulan_ini'];


// echo '<pre>';
// echo "kamar_total : $kamar_total <br>";
// echo "kamar_rusak : $kamar_rusak <br>";
// echo "kamar_ok : $kamar_ok <br>";
// echo "kamar_terisi : $kamar_terisi <br>";
// echo "kamar_kosong : $kamar_kosong <br>";
// echo "<br>";
// echo "nominal_total : $nominal_total <br>";
// echo "nominal_lunas : $nominal_lunas <br>";
// echo "piutang : $piutang <br>";
// echo '<pre>';
// echo print_r($kamars);
// echo '</pre>';
































?>
<div class="pagetitle">
  <h1>Dashboard</h1>
</div>

<section class="section dashboard">
  <div class="row">

    <!-- Left side columns -->
    <div class="col-lg-12">
      <div class="row">

        <!-- Terisi -->
        <div class="col-xxl-4 col-md-4">
          <div class="card info-card sales-card gradasi-hijau">
            <div class="card-body">
              <a href="?master_kamar">
              <h5 class="card-title">Terisi <span>| Hari ini</span></h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-speedometer"></i>
                </div>
                <div class="ps-3">
                  <h6><?=$kamar_terisi?> of <?=$kamar_total?></h6>
                  <!-- <span class="text-success small pt-1 fw-bold">85%</span>  -->
                  <span class="text-muted small pt-2 ps-1">terisi</span>
                </div>
              </div>
              </a>
            </div>
          </div>
        </div>

        <!-- Status Kamar -->
        <div class="col-xxl-4 col-md-4">
          <div class="card info-card sales-card gradasi-toska">
            <div class="card-body ">
              <h5 class="card-title">Kamar Kosong <span>| Hari ini</span></h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-house-exclamation "></i>
                </div>
                <div class="ps-3">
                  <h6><?=$kamar_kosong?></h6>
                  <span class="text-muted small pt-2 ps-1">siap huni</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Status Kamar -->
        <div class="col-xxl-4 col-md-4">
          <div class="card info-card sales-card gradasi-merah">
            <div class="card-body">
              <h5 class="card-title">Sedang Perbaikan <span>| Hari ini</span></h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-house-dash merah"></i>
                </div>
                <div class="ps-3">
                  <h6><?=$kamar_rusak?></h6>
                  <span class="text-muted small pt-2 ps-1">kamar</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12">

          <div class="card card-primary">
            <div class="card-header">Dari <?=$kamar_terisi ?> kamar terisi</div>
            <div class="card-body" style="padding-top:15px">
              <div class="row">
                <!-- Terbayar -->
                <div class="col-xxl-4 col-md-4">
                  <div class="card info-card sales-card gradasi-hijau">
                    <div class="card-body">
                      <h5 class="card-title">Terbayar <span>| Bulan ini</span></h5>
                      <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                          <i class="bi bi-house-check-fill hijau"></i>
                        </div>
                        <div class="ps-3">
                          <h6><?=$terbayar ?></h6>
                          <!-- <span class="text-success small pt-1 fw-bold">29%</span>  -->
                          <span class="text-muted small pt-2 ps-1">telah lunas</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Hampir JT -->
                <div class="col-xxl-4 col-md-4">
                  <div class="card info-card sales-card gradasi-kuning">
                    <div class="card-body">
                      <h5 class="card-title">Hampir JT <span>| <?=$durasi_warning ?> hari lagi</span></h5>
                      <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                          <i class="bi bi-house-exclamation-fill kuning"></i>
                        </div>
                        <div class="ps-3">
                          <h6><?=$jumlah_hampir_jt ?></h6>
                          <!-- <span class="text-success small pt-1 fw-bold">53%</span>  -->
                          <span class="text-muted small pt-2 ps-1">belum perpanjangan</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Jatuh Tempo -->
                <div class="col-xxl-4 col-md-4">
                  <div class="card info-card sales-card gradasi-merah">
                    <div class="card-body">
                      <h5 class="card-title">Jatuh Tempo <span>| Bulan ini</span></h5>
                      <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                          <i class="bi bi-house-exclamation-fill merah"></i>
                        </div>
                        <div class="ps-3">
                          <h6><?=$jumlah_jt ?></h6>
                          <!-- <span class="text-success small pt-1 fw-bold">18%</span>  -->
                          <span class="text-muted small pt-2 ps-1">nunggak</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>            
              </div>

              <!-- ilustrasi -->
              <style>
                .ilustrasi{display:flex; flex-wrap:wrap}
                .item-ilustrasi{ border-radius:50%; height:70px; width:70px; font-size:40px; text-align:center; margin-right:10px; margin-bottom:10px; transition:.2s}
                .item-ilustrasi:hover{font-size:45px}
                .item-ket{font-size:12px; margin-top:-10px}
              </style>
              <div class="ilustrasi">
                <?=$homes?>
              </div>
              
            </div>
          </div>


        </div>

        <!-- Revenue Card -->
        <div class="col-xxl-4 col-md-6">
          <div class="card info-card revenue-card">

            <div class="card-body">
              <h5 class="card-title">Pemasukan <span>| Bulan ini</span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="ps-3">
                  <h6>Rp <?=number_format($pendapatan_bulan_ini, 0) ?></h6>
                  <span class="text-muted small pt-2 ps-1">piutang: </span> <span class="text-success small pt-1 fw-bold">Rp 24.780.000</span>

                </div>
              </div>
            </div>

          </div>
        </div><!-- End Revenue Card -->

        <!-- Customers Card -->
        <div class="col-xxl-4 col-xl-12">

          <div class="card info-card customers-card">

            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body">
              <h5 class="card-title">Total Transaksi Sewa <span>| Tahun ini</span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-card-checklist"></i>
                </div>
                <div class="ps-3">
                  <h6>124</h6>
                  <span class="text-muted small pt-2 ps-1">transaksi lunas</span>

                </div>
              </div>

            </div>
          </div>

        </div><!-- End Customers Card -->

        <!-- Reports -->
        <!-- <div class="col-12">
          <div class="card">

            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body">
              <h5 class="card-title">Grafik <span>/Tahun ini</span></h5>

              < !-- Line Chart -- >
              <div id="reportsChart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  new ApexCharts(document.querySelector("#reportsChart"), {
                    series: [{
                      name: 'Terisi',
                      data: [12, 15, 17, 16, 19, 15, 17],
                    }],
                    chart: {
                      height: 350,
                      type: 'area',
                      toolbar: {
                        show: false
                      },
                    },
                    markers: {
                      size: 4
                    },
                    colors: ['#4154f1', '#2eca6a', '#ff771d'],
                    fill: {
                      type: "gradient",
                      gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.3,
                        opacityTo: 0.4,
                        stops: [0, 90, 100]
                      }
                    },
                    dataLabels: {
                      enabled: false
                    },
                    stroke: {
                      curve: 'smooth',
                      width: 2
                    },
                    xaxis: {
                      type: 'string',
                      categories: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul"]
                    },
                    tooltip: {
                      x: {
                        format: 'dd/MM/yy HH:mm'
                      },
                    }
                  }).render();
                });
              </script>
              < !-- End Line Chart -- >

            </div>

          </div>
        </div> -->
        <!-- End Reports -->

        <!-- Recent Sales -->
        <!-- <div class="col-12">
          <div class="card recent-sales overflow-auto">

            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body">
              <h5 class="card-title">History Pembayaran <span>| Today</span></h5>

              <table class="table table-borderless datatable">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Penyewa</th>
                    <th scope="col">Transaksi</th>
                    <th scope="col">Nominal</th>
                    <th scope="col">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row"><a href="#">#2457</a></th>
                    <td>Ahmad Firdaus</td>
                    <td><a href="#" class="text-primary">Sewa Baru Kamar No. 07</a></td>
                    <td>Rp 550.000</td>
                    <td><span class="badge bg-success">Approved</span></td>
                  </tr>
                  <tr>
                    <th scope="row"><a href="#">#2147</a></th>
                    <td>Budi Santoso</td>
                    <td><a href="#" class="text-primary">Perpanjangan Sewa Kamar No. 04</a></td>
                    <td>Rp 470.000</td>
                    <td><span class="badge bg-warning">Belum Diperiksa</span></td>
                  </tr>
                  <tr>
                    <th scope="row"><a href="#">#2049</a></th>
                    <td>Charlie Sigit</td>
                    <td><a href="#" class="text-primary">Perpanjangan Sewa Kamar No. 12</a></td>
                    <td>Rp 600.000</td>
                    <td><span class="badge bg-success">Approved</span></td>
                  </tr>
                  <tr>
                    <th scope="row"><a href="#">#2644</a></th>
                    <td>Deni Siregar</td>
                    <td><a href="#" class="text-primar">Terima Kunci Kamar No. 08</a></td>
                    <td>Rp 600.000</td>
                    <td><span class="badge bg-danger">Rejected</span></td>
                  </tr>
                  <tr>
                    <th scope="row"><a href="#">#2644</a></th>
                    <td>Erwin Gutawa</td>
                    <td><a href="#" class="text-primary">Terima Kunci Kamar No. 19</a></td>
                    <td>Rp 700.000</td>
                    <td><span class="badge bg-success">Approved</span></td>
                  </tr>
                </tbody>
              </table>

            </div>

          </div>
        </div> -->
        <!-- End Recent Sales -->
      </div>
    </div><!-- End Left side columns -->
  </div>
</section>