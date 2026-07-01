<?php

include "../config/koneksi.php";

include "../includes/header.php";

include "../includes/sidebar.php";

$totalProduk = $pdo
    ->query("SELECT COUNT(*) FROM produk")
    ->fetchColumn();

$totalKategori = $pdo
    ->query("SELECT COUNT(*) FROM kategori")
    ->fetchColumn();

$totalTransaksi = $pdo
    ->query("
SELECT COUNT(*)
FROM transaksi
WHERE DATE(tanggal)=CURDATE()
")
    ->fetchColumn();

$totalPendapatan = $pdo
    ->query("
SELECT IFNULL(SUM(total),0)
FROM transaksi
WHERE DATE(tanggal)=CURDATE()
")
    ->fetchColumn();

?>

<div class="col-md-10 p-4">

    <h3 class="mb-4">

        Dashboard

    </h3>

    <div class="row">

        <div class="col-md-3">

            <div class="card card-dashboard">

                <div class="card-body">

                    <h6>Total Produk</h6>

                    <h2>

                        <?= $totalProduk ?>

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card card-dashboard">

                <div class="card-body">

                    <h6>Total Kategori</h6>

                    <h2>

                        <?= $totalKategori ?>

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card card-dashboard">

                <div class="card-body">

                    <h6>Transaksi Hari Ini</h6>

                    <h2>

                        <?= $totalTransaksi ?>

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card card-dashboard">

                <div class="card-body">

                    <h6>Pendapatan Hari Ini</h6>

                    <h4>

                        Rp <?= number_format($totalPendapatan) ?>

                    </h4>

                </div>

            </div>

        </div>

    </div>

    <div class="card mt-4">

        <div class="card-header">

            Grafik Penjualan

        </div>

        <div class="card-body">

            <canvas id="grafikPenjualan"></canvas>

        </div>

    </div>

</div>

<script>

    const ctx =
        document.getElementById(
            'grafikPenjualan'
        );

    new Chart(ctx, {

        type: 'bar',

        data: {

            labels: [
                'Sen',
                'Sel',
                'Rab',
                'Kam',
                'Jum',
                'Sab',
                'Min'
            ],

            datasets: [{

                label: 'Penjualan',

                data: [
                    12,
                    19,
                    3,
                    5,
                    8,
                    14,
                    10
                ]

            }]

        }

    });

</script>

<?php

include "../includes/footer.php";

?>