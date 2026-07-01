<?php

session_start();

include "../config/koneksi.php";
include "../includes/header.php";
include "../includes/sidebar.php";

/*
|--------------------------------------------------------------------------
| SESSION KERANJANG
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/*
|--------------------------------------------------------------------------
| NOMOR TRANSAKSI OTOMATIS
|--------------------------------------------------------------------------
*/

$qtrx = $pdo->query("
SELECT MAX(id_transaksi)
FROM transaksi
");

$last = $qtrx->fetchColumn();

$last++;

$no_transaksi =
    "TRX" . str_pad(
        $last,
        5,
        "0",
        STR_PAD_LEFT
    );

/*
|--------------------------------------------------------------------------
| TAMBAH KE KERANJANG
|--------------------------------------------------------------------------
*/

if (isset($_POST['tambah_cart'])) {

    $id_produk = $_POST['id_produk'];
    $qty = $_POST['qty'];

    $stmt = $pdo->prepare("
    SELECT *
    FROM produk
    WHERE id_produk=?
    ");

    $stmt->execute([$id_produk]);

    $produk = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produk) {

        $_SESSION['cart'][] = [

            'id_produk' => $produk['id_produk'],
            'kode_produk' => $produk['kode_produk'],
            'nama_produk' => $produk['nama_produk'],
            'harga' => $produk['harga_jual'],
            'qty' => $qty,
            'subtotal' => $qty * $produk['harga_jual']

        ];

    }

    echo "
    <script>
    location='transaksi.php';
    </script>
    ";
}

/*
|--------------------------------------------------------------------------
| HAPUS ITEM KERANJANG
|--------------------------------------------------------------------------
*/

if (isset($_GET['hapus_cart'])) {

    $index = $_GET['hapus_cart'];

    unset($_SESSION['cart'][$index]);

    $_SESSION['cart'] =
        array_values($_SESSION['cart']);

    echo "
    <script>
    location='transaksi.php';
    </script>
    ";
}

/*
|--------------------------------------------------------------------------
| SIMPAN TRANSAKSI
|--------------------------------------------------------------------------
*/

if (isset($_POST['simpan_transaksi'])) {

    $total = $_POST['total'];
    $bayar = $_POST['bayar'];
    $kembalian = $_POST['kembalian'];

    $user_id = $_SESSION['id'];

    $stmt = $pdo->prepare("
    INSERT INTO transaksi
    (
        no_transaksi,
        total,
        bayar,
        kembalian,
        user_id
    )
    VALUES
    (?,?,?,?,?)
    ");

    $stmt->execute([

        $no_transaksi,
        $total,
        $bayar,
        $kembalian,
        $user_id

    ]);
    $idTransaksi = $pdo->lastInsertId();

    header(
        "Location: struk.php?id=" . $idTransaksi
    );
    exit;

    foreach ($_SESSION['cart'] as $item) {

        $detail = $pdo->prepare("
        INSERT INTO detail_transaksi
        (
            id_transaksi,
            id_produk,
            qty,
            harga,
            subtotal
        )
        VALUES
        (?,?,?,?,?)
        ");

        $detail->execute([

            $id_transaksi,
            $item['id_produk'],
            $item['qty'],
            $item['harga'],
            $item['subtotal']

        ]);


        /*
        |--------------------------------------------------------------------------
        | KURANGI STOK
        |--------------------------------------------------------------------------
        */

        $stok = $pdo->prepare("
        UPDATE produk
        SET stok = stok - ?
        WHERE id_produk=?
        ");

        $stok->execute([

            $item['qty'],
            $item['id_produk']

        ]);
    }

    unset($_SESSION['cart']);

    echo "
    <script>

    Swal.fire({

        icon:'success',
        title:'Transaksi Berhasil'

    }).then(()=>{

        location='transaksi.php';

    });

    </script>
    ";
}

?>

<div class="col-md-10 p-4">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">

                Transaksi Kasir

            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label>No Transaksi</label>

                    <input type="text" class="form-control" value="<?= $no_transaksi ?>" readonly>

                </div>

                <form method="POST">

                    <div class="row">

                        <div class="col-md-5">

                            <label>Produk</label>

                            <select name="id_produk" class="form-select" required>

                                <option value="">
                                    Pilih Produk
                                </option>

                                <?php

                                $produk = $pdo->query("
SELECT *
FROM produk
WHERE stok > 0
ORDER BY nama_produk ASC
");

                                while ($p = $produk->fetch()):

                                    ?>

                                    <option value="<?= $p['id_produk']; ?>">

                                        <?= $p['nama_produk']; ?>
                                        -
                                        Rp <?= number_format($p['harga_jual']); ?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>

                        <div class="col-md-3">

                            <label>Qty</label>

                            <input type="number" name="qty" class="form-control" value="1" required>

                        </div>

                        <div class="col-md-2 d-flex align-items-end">

                            <button type="submit" name="tambah_cart" class="btn btn-success">

                                Tambah

                            </button>

                        </div>

                    </div>

                </form>

                <hr>
                <?php

                $total = 0;

                ?>

                <table class="table table-bordered">

                    <thead class="table-dark">

                        <tr>

                            <th>No</th>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        $no = 1;

                        if (!empty($_SESSION['cart'])):

                            foreach ($_SESSION['cart'] as $index => $item):

                                $total += $item['subtotal'];

                                ?>

                                <tr>

                                    <td><?= $no++; ?></td>

                                    <td><?= $item['nama_produk']; ?></td>

                                    <td>
                                        Rp <?= number_format($item['harga'], 0, ',', '.'); ?>
                                    </td>

                                    <td><?= $item['qty']; ?></td>

                                    <td>
                                        Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?>
                                    </td>

                                    <td>

                                        <a href="?hapus_cart=<?= $index; ?>" class="btn btn-danger btn-sm">

                                            Hapus

                                        </a>

                                    </td>

                                </tr>

                                <?php

                            endforeach;

                        endif;

                        ?>

                    </tbody>

                    <tfoot>

                        <tr>

                            <th colspan="4" class="text-end">

                                TOTAL

                            </th>

                            <th colspan="2">

                                Rp <?= number_format($total, 0, ',', '.'); ?>

                            </th>

                        </tr>

                    </tfoot>

                </table>

                <form method="POST">

                    <input type="hidden" name="total" id="total" value="<?= $total; ?>">

                    <div class="row">

                        <div class="col-md-4">

                            <label>Total</label>

                            <input type="text" class="form-control" value="Rp <?= number_format($total, 0, ',', '.'); ?>"
                                readonly>

                        </div>

                        <div class="col-md-4">

                            <label>Bayar</label>

                            <input type="number" name="bayar" id="bayar" class="form-control" required>

                        </div>

                        <div class="col-md-4">

                            <label>Kembalian</label>

                            <input type="number" name="kembalian" id="kembalian" class="form-control" readonly>

                        </div>

                    </div>

                    <div class="mt-3">

                        <button type="submit" name="simpan_transaksi" class="btn btn-primary">

                            Simpan Transaksi

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <script>

        let bayar =
            document.getElementById('bayar');

        let total =
            document.getElementById('total');

        let kembalian =
            document.getElementById('kembalian');

        bayar.addEventListener(
            'keyup',
            function () {

                let hasil =

                    parseInt(this.value || 0)

                    -

                    parseInt(total.value || 0);

                kembalian.value = hasil;

            }
        );

    </script>

    <?php
    include "../includes/footer.php";
    ?>