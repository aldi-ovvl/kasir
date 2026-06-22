<?php

include "../config/koneksi.php";
include "../includes/header.php";
include "../includes/sidebar.php";

/*
|--------------------------------------------------------------------------
| AUTO KODE PRODUK
|--------------------------------------------------------------------------
*/

$qKode = $pdo->query("SELECT MAX(id_produk) as terakhir FROM produk");
$dataKode = $qKode->fetch(PDO::FETCH_ASSOC);

$nomor = (int)$dataKode['terakhir'] + 1;

$kodeOtomatis =
"PRD".str_pad(
    $nomor,
    4,
    "0",
    STR_PAD_LEFT
);

/*
|--------------------------------------------------------------------------
| TAMBAH PRODUK
|--------------------------------------------------------------------------
*/

if(isset($_POST['tambah'])){

    $kode = $_POST['kode_produk'];
    $nama = $_POST['nama_produk'];
    $kategori = $_POST['id_kategori'];
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];
    $stok = $_POST['stok'];

    $foto = "";

    if(!empty($_FILES['foto']['name'])){

        $foto =
        time().'_'.
        $_FILES['foto']['name'];

        move_uploaded_file(

            $_FILES['foto']['tmp_name'],

            "../assets/img/produk/".$foto

        );

    }

    $stmt = $pdo->prepare("
        INSERT INTO produk
        (
            kode_produk,
            nama_produk,
            id_kategori,
            harga_beli,
            harga_jual,
            stok,
            foto
        )
        VALUES
        (
            ?,?,?,?,?,?,?
        )
    ");

    $stmt->execute([

        $kode,
        $nama,
        $kategori,
        $harga_beli,
        $harga_jual,
        $stok,
        $foto

    ]);

    echo "
    <script>

    Swal.fire({

        icon:'success',
        title:'Berhasil',

        text:'Produk berhasil ditambahkan'

    }).then(()=>{

        location='produk.php';

    });

    </script>
    ";
}

/*
|--------------------------------------------------------------------------
| UPDATE PRODUK
|--------------------------------------------------------------------------
*/

if(isset($_POST['update'])){

    $id = $_POST['id_produk'];

    $kode = $_POST['kode_produk'];
    $nama = $_POST['nama_produk'];
    $kategori = $_POST['id_kategori'];

    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];
    $stok = $_POST['stok'];

    $fotoLama = $_POST['foto_lama'];

    $foto = $fotoLama;

    if(!empty($_FILES['foto']['name'])){

        if(
            !empty($fotoLama)
            &&
            file_exists(
                "../assets/img/produk/".$fotoLama
            )
        ){

            unlink(
                "../assets/img/produk/".$fotoLama
            );

        }

        $foto =
        time().'_'.
        $_FILES['foto']['name'];

        move_uploaded_file(

            $_FILES['foto']['tmp_name'],

            "../assets/img/produk/".$foto

        );

    }

    $stmt = $pdo->prepare("
        UPDATE produk
        SET

        kode_produk=?,
        nama_produk=?,
        id_kategori=?,
        harga_beli=?,
        harga_jual=?,
        stok=?,
        foto=?

        WHERE id_produk=?
    ");

    $stmt->execute([

        $kode,
        $nama,
        $kategori,
        $harga_beli,
        $harga_jual,
        $stok,
        $foto,
        $id

    ]);

    echo "
    <script>

    Swal.fire({

        icon:'success',
        title:'Berhasil',

        text:'Produk berhasil diupdate'

    }).then(()=>{

        location='produk.php';

    });

    </script>
    ";
}

/*
|--------------------------------------------------------------------------
| HAPUS PRODUK
|--------------------------------------------------------------------------
*/

if(isset($_GET['hapus'])){

    $id = $_GET['hapus'];

    $cek = $pdo->prepare("
    SELECT foto
    FROM produk
    WHERE id_produk=?
    ");

    $cek->execute([$id]);

    $fotoData = $cek->fetch();

    if(
        !empty($fotoData['foto'])
        &&
        file_exists(
            "../assets/img/produk/".
            $fotoData['foto']
        )
    ){

        unlink(
            "../assets/img/produk/".
            $fotoData['foto']
        );

    }

    $stmt = $pdo->prepare("
    DELETE FROM produk
    WHERE id_produk=?
    ");

    $stmt->execute([$id]);

    echo "
    <script>

    Swal.fire({

        icon:'success',
        title:'Berhasil',

        text:'Produk berhasil dihapus'

    }).then(()=>{

        location='produk.php';

    });

    </script>
    ";
}

?>

<div class="col-md-10 p-4">

<div class="d-flex justify-content-between align-items-center mb-3">

<h3>Data Produk</h3>

<button
class="btn btn-primary"
data-bs-toggle="modal"
data-bs-target="#modalTambah">

<i class="bi bi-plus-circle"></i>
Tambah Produk

</button>

</div>

<div class="card shadow">

<div class="card-body">

<table class="table table-bordered table-striped datatable">

<thead>

<tr>

<th>No</th>
<th>Foto</th>
<th>Kode</th>
<th>Nama Produk</th>
<th>Kategori</th>
<th>Harga Beli</th>
<th>Harga Jual</th>
<th>Stok</th>
<th width="120">Aksi</th>

</tr>

</thead>

<tbody>

<?php

$no = 1;

$query = $pdo->query("

SELECT
produk.*,
kategori.nama_kategori

FROM produk

LEFT JOIN kategori
ON produk.id_kategori =
kategori.id_kategori

ORDER BY id_produk DESC

");

while(
$row =
$query->fetch(PDO::FETCH_ASSOC)
):

?>

<tr>

<td><?= $no++; ?></td>

<td>

<?php if(!empty($row['foto'])): ?>

<img
src="../assets/img/produk/<?= $row['foto']; ?>"
style="
width:70px;
height:70px;
object-fit:cover;
border-radius:8px;
">

<?php endif; ?>

</td>

<td><?= $row['kode_produk']; ?></td>

<td><?= $row['nama_produk']; ?></td>

<td><?= $row['nama_kategori']; ?></td>

<td>
Rp <?= number_format($row['harga_beli'],0,',','.'); ?>
</td>

<td>
Rp <?= number_format($row['harga_jual'],0,',','.'); ?>
</td>

<td><?= $row['stok']; ?></td>

<td>

<button
class="btn btn-warning btn-sm"
data-bs-toggle="modal"
data-bs-target="#edit<?= $row['id_produk']; ?>">

<i class="bi bi-pencil-square"></i>

</button>

<a
href="?hapus=<?= $row['id_produk']; ?>"
class="btn btn-danger btn-sm btnHapus">

<i class="bi bi-trash"></i>

</a>

</td>

</tr><!-- MODAL EDIT -->

<div
class="modal fade"
id="edit<?= $row['id_produk']; ?>">

<div class="modal-dialog modal-lg">

<div class="modal-content">

<form
method="POST"
enctype="multipart/form-data">

<div class="modal-header bg-warning">

<h5 class="modal-title">
Edit Produk
</h5>

<button
type="button"
class="btn-close"
data-bs-dismiss="modal"> </button>

</div>

<div class="modal-body">

<input
type="hidden"
name="id_produk"
value="<?= $row['id_produk']; ?>">

<input
type="hidden"
name="foto_lama"
value="<?= $row['foto']; ?>">

<div class="row">

<div class="col-md-6 mb-3">

<label>Kode Produk</label>

<input
type="text"
name="kode_produk"
class="form-control"
value="<?= $row['kode_produk']; ?>"
readonly>

</div>

<div class="col-md-6 mb-3">

<label>Nama Produk</label>

<input
type="text"
name="nama_produk"
class="form-control"
value="<?= $row['nama_produk']; ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label>Kategori</label>

<select
name="id_kategori"
class="form-select"
required>

<?php

$kategoriEdit =
$pdo->query("
SELECT *
FROM kategori
ORDER BY nama_kategori ASC
");

while($kt = $kategoriEdit->fetch()):

?>

<option
value="<?= $kt['id_kategori']; ?>"
<?= ($kt['id_kategori']==$row['id_kategori']) ? 'selected' : ''; ?>>

<?= $kt['nama_kategori']; ?>

</option>

<?php endwhile; ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label>Stok</label>

<input
type="number"
name="stok"
class="form-control"
value="<?= $row['stok']; ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label>Harga Beli</label>

<input
type="number"
name="harga_beli"
class="form-control"
value="<?= $row['harga_beli']; ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label>Harga Jual</label>

<input
type="number"
name="harga_jual"
class="form-control"
value="<?= $row['harga_jual']; ?>"
required>

</div>

<div class="col-md-12">

<label>Foto Produk</label>

<input
type="file"
name="foto"
class="form-control"
accept="image/*"
onchange="previewImage(this,'preview<?= $row['id_produk']; ?>')">

<br>

<img
id="preview<?= $row['id_produk']; ?>"
src="../assets/img/produk/<?= $row['foto']; ?>"
style="
width:120px;
height:120px;
object-fit:cover;
border:1px solid #ddd;
border-radius:10px;
">

</div>

</div>

</div>

<div class="modal-footer">

<button
type="submit"
name="update"
class="btn btn-success">

Update Produk

</button>

</div>

</form>

</div>

</div>

</div>

<?php endwhile; ?>

</tbody>

</table>

</div>

</div>

</div>

<!-- MODAL TAMBAH -->

<div
class="modal fade"
id="modalTambah">

<div class="modal-dialog modal-lg">

<div class="modal-content">

<form
method="POST"
enctype="multipart/form-data">

<div class="modal-header bg-primary text-white">

<h5 class="modal-title">
Tambah Produk
</h5>

<button
type="button"
class="btn-close btn-close-white"
data-bs-dismiss="modal"> </button>

</div>

<div class="modal-body">

<div class="row">

<div class="col-md-6 mb-3">

<label>Kode Produk</label>

<input
type="text"
name="kode_produk"
class="form-control"
value="<?= $kodeOtomatis ?>"
readonly>

</div>

<div class="col-md-6 mb-3">

<label>Nama Produk</label>

<input
type="text"
name="nama_produk"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label>Kategori</label>

<select
name="id_kategori"
class="form-select"
required>

<option value="">
Pilih Kategori
</option>

<?php

$kategori =
$pdo->query("
SELECT *
FROM kategori
ORDER BY nama_kategori ASC
");

while($k = $kategori->fetch()):

?>

<option
value="<?= $k['id_kategori']; ?>">

<?= $k['nama_kategori']; ?>

</option>

<?php endwhile; ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label>Stok</label>

<input
type="number"
name="stok"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label>Harga Beli</label>

<input
type="number"
name="harga_beli"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label>Harga Jual</label>

<input
type="number"
name="harga_jual"
class="form-control"
required>

</div>

<div class="col-md-12">

<label>Foto Produk</label>

<input
type="file"
name="foto"
class="form-control"
accept="image/*"
onchange="previewImage(this,'previewTambah')">

<br>

<img
id="previewTambah"
src="https://via.placeholder.com/120"
style="
width:120px;
height:120px;
object-fit:cover;
border:1px solid #ddd;
border-radius:10px;
">

</div>

</div>

</div>

<div class="modal-footer">

<button
type="submit"
name="tambah"
class="btn btn-primary">

Simpan Produk

</button>

</div>

</form>

</div>

</div>

</div>

<script>

function previewImage(input,id){

let preview =
document.getElementById(id);

if(input.files && input.files[0]){

let reader =
new FileReader();

reader.onload = function(e){

preview.src =
e.target.result;

};

reader.readAsDataURL(
input.files[0]
);

}

}

document
.querySelectorAll('.btnHapus')
.forEach(function(btn){

btn.addEventListener(
'click',
function(e){

e.preventDefault();

let url = this.href;

Swal.fire({

title:'Hapus Produk?',

text:'Data tidak dapat dikembalikan',

icon:'warning',

showCancelButton:true,

confirmButtonText:'Ya, Hapus',

cancelButtonText:'Batal'

}).then((result)=>{

if(result.isConfirmed){

window.location = url;

}

});

});

});

</script>

<?php
include "../includes/footer.php";
?>
