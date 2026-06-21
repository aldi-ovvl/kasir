<?php

include "../config/koneksi.php";
include "../includes/header.php";
include "../includes/sidebar.php";

/*
|--------------------------------------------------------------------------
| AMBIL DATA PENGATURAN
|--------------------------------------------------------------------------
*/

$cek = $pdo->query("
    SELECT *
    FROM pengaturan_toko
    LIMIT 1
");

$data = $cek->fetch(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| JIKA BELUM ADA DATA
|--------------------------------------------------------------------------
*/

if(!$data){

    $pdo->exec("
        INSERT INTO pengaturan_toko
        (
            nama_toko,
            alamat,
            telepon
        )
        VALUES
        (
            'POS KASIR',
            '',
            ''
        )
    ");

    $cek = $pdo->query("
        SELECT *
        FROM pengaturan_toko
        LIMIT 1
    ");

    $data = $cek->fetch(PDO::FETCH_ASSOC);
}

/*
|--------------------------------------------------------------------------
| UPDATE
|--------------------------------------------------------------------------
*/

if(isset($_POST['simpan'])){

    $nama_toko = trim($_POST['nama_toko']);
    $alamat    = trim($_POST['alamat']);
    $telepon   = trim($_POST['telepon']);

    $logoLama = $data['logo'];

    if(!empty($_FILES['logo']['name'])){

        $ext = pathinfo(
            $_FILES['logo']['name'],
            PATHINFO_EXTENSION
        );

        $namaLogo =
            time().'_logo.'.$ext;

        $folder =
            "../assets/uploads/logo/";

        if(!is_dir($folder)){
            mkdir($folder,0777,true);
        }

        move_uploaded_file(
            $_FILES['logo']['tmp_name'],
            $folder.$namaLogo
        );

        if(
            !empty($logoLama) &&
            file_exists($folder.$logoLama)
        ){
            unlink($folder.$logoLama);
        }

    }else{

        $namaLogo = $logoLama;
    }

    $stmt = $pdo->prepare("
        UPDATE pengaturan_toko
        SET
            nama_toko=?,
            alamat=?,
            telepon=?,
            logo=?
        WHERE id=?
    ");

    $stmt->execute([
        $nama_toko,
        $alamat,
        $telepon,
        $namaLogo,
        $data['id']
    ]);

    echo "
    <script>
    Swal.fire({
        icon:'success',
        title:'Berhasil',
        text:'Pengaturan berhasil disimpan'
    }).then(()=>{
        location='pengaturan.php';
    });
    </script>
    ";
}

?>

<div class="col-md-10 p-4">

    <h3 class="mb-4">
        Pengaturan Toko
    </h3>

    <div class="card shadow">

        <div class="card-body">

            <form method="POST"
                  enctype="multipart/form-data">

                <div class="mb-3">

                    <label>
                        Nama Toko
                    </label>

                    <input
                        type="text"
                        name="nama_toko"
                        class="form-control"
                        value="<?= htmlspecialchars($data['nama_toko']) ?>"
                        required>

                </div>

                <div class="mb-3">

                    <label>
                        Alamat
                    </label>

                    <textarea
                        name="alamat"
                        rows="4"
                        class="form-control"><?= htmlspecialchars($data['alamat']) ?></textarea>

                </div>

                <div class="mb-3">

                    <label>
                        Telepon
                    </label>

                    <input
                        type="text"
                        name="telepon"
                        class="form-control"
                        value="<?= htmlspecialchars($data['telepon']) ?>">

                </div>

                <div class="mb-3">

                    <label>
                        Logo Toko
                    </label>

                    <input
                        type="file"
                        name="logo"
                        class="form-control">

                </div>

                <?php if(!empty($data['logo'])): ?>

                <div class="mb-3">

                    <img
                        src="../assets/uploads/logo/<?= $data['logo'] ?>"
                        width="150"
                        class="img-thumbnail">

                </div>

                <?php endif; ?>

                <button
                    type="submit"
                    name="simpan"
                    class="btn btn-primary">

                    <i class="bi bi-save"></i>
                    Simpan Pengaturan

                </button>

            </form>

        </div>

    </div>

</div>

<?php
include "../includes/footer.php";
?>