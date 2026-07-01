<?php

include "../config/koneksi.php";

$id = $_GET['id'] ?? 0;

if (!$id) {
    die("ID Transaksi tidak ditemukan");
}

/*
|--------------------------------------------------------------------------
| DATA TOKO
|--------------------------------------------------------------------------
*/

$toko = $pdo->query("
SELECT *
FROM pengaturan_toko
LIMIT 1
")->fetch(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| DATA TRANSAKSI
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
SELECT
t.*,
u.nama as kasir
FROM transaksi t
LEFT JOIN users u
ON u.id = t.user_id
WHERE t.id_transaksi = ?
");

$stmt->execute([$id]);

$trx = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$trx) {
    die("Transaksi tidak ditemukan");
}

/*
|--------------------------------------------------------------------------
| DETAIL TRANSAKSI
|--------------------------------------------------------------------------
*/

$detail = $pdo->prepare("
SELECT
p.nama_produk,
d.qty,
d.harga,
d.subtotal
FROM detail_transaksi d
JOIN produk p
ON p.id_produk = d.id_produk
WHERE d.id_transaksi = ?
");

$detail->execute([$id]);

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>
        Struk <?= $trx['no_transaksi']; ?>
    </title>

    <style>
        body {
            font-family: monospace;
            font-size: 12px;
            width: 80mm;
            margin: auto;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo {
            max-width: 80px;
            margin-bottom: 5px;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 2px 0;
            vertical-align: top;
        }

        .btn {
            margin-top: 15px;
            padding: 8px 15px;
            border: none;
            background: #0d6efd;
            color: white;
            cursor: pointer;
        }

        @media print {

            .btn {
                display: none;
            }

            body {
                width: 58mm;
            }

        }
    </style>

</head>

<body>

    <div class="header">

        <?php if (!empty($toko['logo'])): ?>

            <img src="../assets/uploads/logo/<?= $toko['logo']; ?>" class="logo">

        <?php endif; ?>

        <h3 style="margin:0;">
            <?= strtoupper($toko['nama_toko'] ?? 'POS KASIR'); ?>
        </h3>

        <p style="margin:2px;">
            <?= $toko['alamat'] ?? ''; ?>
        </p>

        <p style="margin:2px;">
            Telp : <?= $toko['telepon'] ?? ''; ?>
        </p>

    </div>

    <div class="line"></div>

    <table>

        <tr>
            <td>No</td>
            <td>:</td>
            <td><?= $trx['no_transaksi']; ?></td>
        </tr>

        <tr>
            <td>Tgl</td>
            <td>:</td>
            <td>
                <?= date(
                    'd-m-Y H:i',
                    strtotime($trx['tanggal'])
                ); ?>
            </td>
        </tr>

        <tr>
            <td>Kasir</td>
            <td>:</td>
            <td><?= $trx['kasir']; ?></td>
        </tr>

    </table>

    <div class="line"></div>

    <?php while ($item = $detail->fetch(PDO::FETCH_ASSOC)): ?>

        <div>

            <b>
                <?= $item['nama_produk']; ?>
            </b>

            <table>

                <tr>

                    <td>
                        <?= $item['qty']; ?>
                        x
                        <?= number_format($item['harga'], 0, ',', '.'); ?>
                    </td>

                    <td class="text-right">

                        <?= number_format(
                            $item['subtotal'],
                            0,
                            ',',
                            '.'
                        ); ?>

                    </td>

                </tr>

            </table>

        </div>

    <?php endwhile; ?>

    <div class="line"></div>

    <table>

        <tr>

            <td>
                TOTAL
            </td>

            <td class="text-right">

                Rp <?= number_format(
                    $trx['total'],
                    0,
                    ',',
                    '.'
                ); ?>

            </td>

        </tr>

        <tr>

            <td>
                BAYAR
            </td>

            <td class="text-right">

                Rp <?= number_format(
                    $trx['bayar'],
                    0,
                    ',',
                    '.'
                ); ?>

            </td>

        </tr>

        <tr>

            <td>
                KEMBALIAN
            </td>

            <td class="text-right">

                Rp <?= number_format(
                    $trx['kembalian'],
                    0,
                    ',',
                    '.'
                ); ?>

            </td>

        </tr>

    </table>

    <div class="line"></div>

    <div class="text-center">

        Terima Kasih<br>

        Telah Berbelanja

        <br><br>

        <?= date('d/m/Y H:i'); ?>

    </div>

    <div class="text-center">

        <button onclick="window.print()" class="btn">

            🖨 Cetak Struk

        </button>

    </div>

    <script>

        window.onload = function () {

            setTimeout(function () {

                window.print();

            }, 500);

        }

    </script>

</body>

</html>