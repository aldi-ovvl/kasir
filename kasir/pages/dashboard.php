<?php

$produk = mysqli_query(
    $conn,
    "SELECT COUNT(*) total FROM produk"
);
$totalProduk =
    mysqli_fetch_assoc($produk);

$kategori = mysqli_query(
    $conn,
    "SELECT COUNT(*) total FROM kategori"
);
$totalKategori =
    mysqli_fetch_assoc($kategori);

$transaksi = mysqli_query(
    $conn,
    "SELECT COUNT(*) total
FROM transaksi
WHERE DATE(tanggal)=CURDATE()"
);
$totalTransaksi =
    mysqli_fetch_assoc($transaksi);

$pendapatan = mysqli_query(
    $conn,
    "SELECT SUM(total) total
FROM transaksi
WHERE DATE(tanggal)=CURDATE()"
);
$totalPendapatan =
    mysqli_fetch_assoc($pendapatan);
?>

<div class="content">

    <h2 class="mb-4">
        Dashboard
    </h2>

    <div class="row">

        <div class="col-md-3 mb-3">

            <div class="card stat-card bg-primary text-white shadow">

                <div class="card-body">

                    <h6>Total Produk</h6>

                    <h2>
                        <?= $totalProduk['total']; ?>
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="card stat-card bg-success text-white shadow">

                <div class="card-body">

                    <h6>Total Kategori</h6>

                    <h2>
                        <?= $totalKategori['total']; ?>
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="card stat-card bg-warning text-white shadow">

                <div class="card-body">

                    <h6>Transaksi Hari Ini</h6>

                    <h2>
                        <?= $totalTransaksi['total']; ?>
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="card stat-card bg-danger text-white shadow">

                <div class="card-body">

                    <h6>Pendapatan</h6>

                    <h4>

                        Rp <?= number_format(
                            $totalPendapatan['total']
                            ?? 0,
                            0,
                            ',',
                            '.'
                        ); ?>

                    </h4>

                </div>

            </div>

        </div>

    </div>

    <div class="card shadow mt-4">

        <div class="card-header">
            Grafik Penjualan
        </div>

        <div class="card-body">

            <canvas id="salesChart"></canvas>

        </div>

    </div>

</div>