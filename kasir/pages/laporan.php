<?php

include "../config/koneksi.php";
include "../includes/header.php";
include "../includes/sidebar.php";

$awal = $_GET['awal'] ?? date('Y-m-01');
$akhir = $_GET['akhir'] ?? date('Y-m-d');

/*
|--------------------------------------------------------------------------
| STATISTIK
|--------------------------------------------------------------------------
*/

$stat = $pdo->prepare("
SELECT
COUNT(*) as total_transaksi,
IFNULL(SUM(total),0) as total_pendapatan,
IFNULL(AVG(total),0) as rata_transaksi
FROM transaksi
WHERE DATE(tanggal)
BETWEEN ? AND ?
");

$stat->execute([$awal, $akhir]);

$s = $stat->fetch(PDO::FETCH_ASSOC);

$produkTerjual = $pdo->prepare("
SELECT IFNULL(SUM(dt.qty),0) as total_produk
FROM detail_transaksi dt
JOIN transaksi t
ON t.id_transaksi = dt.id_transaksi
WHERE DATE(t.tanggal)
BETWEEN ? AND ?
");

$produkTerjual->execute([$awal, $akhir]);

$pj = $produkTerjual->fetch(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| DATA TRANSAKSI
|--------------------------------------------------------------------------
*/

$transaksi = $pdo->prepare("
SELECT
t.*,
u.nama as kasir
FROM transaksi t
LEFT JOIN users u
ON t.user_id = u.id
WHERE DATE(t.tanggal)
BETWEEN ? AND ?
ORDER BY t.id_transaksi DESC
");

$transaksi->execute([$awal, $akhir]);

/*
|--------------------------------------------------------------------------
| PRODUK TERLARIS
|--------------------------------------------------------------------------
*/

$terlaris = $pdo->prepare("
SELECT
p.nama_produk,
p.harga_jual,
p.stok,
SUM(dt.qty) total_terjual
FROM detail_transaksi dt
JOIN produk p
ON p.id_produk = dt.id_produk
JOIN transaksi t
ON t.id_transaksi = dt.id_transaksi
WHERE DATE(t.tanggal)
BETWEEN ? AND ?
GROUP BY dt.id_produk
ORDER BY total_terjual DESC
LIMIT 10
");

$terlaris->execute([$awal, $akhir]);

/*
|--------------------------------------------------------------------------
| GRAFIK
|--------------------------------------------------------------------------
*/

$grafik = $pdo->prepare("
SELECT
DATE(tanggal) as tanggal,
SUM(total) as total
FROM transaksi
WHERE DATE(tanggal)
BETWEEN ? AND ?
GROUP BY DATE(tanggal)
ORDER BY DATE(tanggal)
");

$grafik->execute([$awal, $akhir]);

$labels = [];
$dataGrafik = [];

while ($g = $grafik->fetch(PDO::FETCH_ASSOC)) {

    $labels[] = date(
        'd/m',
        strtotime($g['tanggal'])
    );

    $dataGrafik[] = $g['total'];
}

?>

<div class="col-md-10 p-4">

    <h3 class="mb-4">
        Laporan Penjualan
    </h3>

    <div class="card mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row">

                    <div class="col-md-3">

                        <label>Tanggal Awal</label>

                        <input type="date" name="awal" value="<?= $awal ?>" class="form-control">

                    </div>

                    <div class="col-md-3">

                        <label>Tanggal Akhir</label>

                        <input type="date" name="akhir" value="<?= $akhir ?>" class="form-control">

                    </div>

                    <div class="col-md-3">

                        <label> </label>

                        <button class="btn btn-primary d-block">

                            Tampilkan

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card shadow">

                <div class="card-body">

                    <h6>Total Transaksi</h6>

                    <h3><?= $s['total_transaksi'] ?></h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow">

                <div class="card-body">

                    <h6>Total Pendapatan</h6>

                    <h5>
                        Rp <?= number_format($s['total_pendapatan']) ?>
                    </h5>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow">

                <div class="card-body">

                    <h6>Produk Terjual</h6>

                    <h3><?= $pj['total_produk'] ?></h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow">

                <div class="card-body">

                    <h6>Rata-rata Transaksi</h6>

                    <h5>
                        Rp <?= number_format($s['rata_transaksi']) ?>
                    </h5>

                </div>

            </div>

        </div>

    </div>

    <div class="card mb-4">

        <div class="card-header">

            Grafik Penjualan

        </div>

        <div class="card-body">

            <canvas id="grafikLaporan"></canvas>

        </div>

    </div>

    <div class="card mb-4">

        <div class="card-header">

            Data Transaksi

        </div>

        <div class="card-body">

            <table class="table table-bordered table-striped">

                <thead>

                    <tr>

                        <th>No</th>
                        <th>No Transaksi</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th>Total</th>
                        <th>Bayar</th>
                        <th>Kembalian</th>

                    </tr>

                </thead>

                <tbody>

                    <?php
                    $no = 1;

                    while ($row = $transaksi->fetch(PDO::FETCH_ASSOC)):
                        ?>

                        <tr>

                            <td><?= $no++ ?></td>

                            <td><?= $row['no_transaksi'] ?></td>

                            <td><?= $row['tanggal'] ?></td>

                            <td><?= $row['kasir'] ?></td>

                            <td>
                                Rp <?= number_format($row['total']) ?>
                            </td>

                            <td>
                                Rp <?= number_format($row['bayar']) ?>
                            </td>

                            <td>
                                Rp <?= number_format($row['kembalian']) ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

    <div class="card">

        <div class="card-header">

            Top 10 Produk Terlaris

        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <thead>

                    <tr>

                        <th>No</th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Terjual</th>

                    </tr>

                </thead>

                <tbody>

                    <?php
                    $no = 1;

                    while ($p = $terlaris->fetch(PDO::FETCH_ASSOC)):
                        ?>

                        <tr>

                            <td><?= $no++ ?></td>

                            <td><?= $p['nama_produk'] ?></td>

                            <td>
                                Rp <?= number_format($p['harga_jual']) ?>
                            </td>

                            <td><?= $p['stok'] ?></td>

                            <td><?= $p['total_terjual'] ?></td>

                        </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>

    new Chart(
        document.getElementById(
            'grafikLaporan'
        ),
        {
            type: 'line',

            data: {

                labels:
                    <?= json_encode($labels); ?>,

                datasets: [{

                    label: 'Penjualan',

                    data:
                        <?= json_encode($dataGrafik); ?>

                }]

            }

        });

</script>

<?php
include "../includes/footer.php";
?>