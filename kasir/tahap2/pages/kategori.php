<?php

include "../config/koneksi.php";
include "../includes/header.php";
include "../includes/sidebar.php";

/*
|--------------------------------------------------------------------------
| TAMBAH KATEGORI
|--------------------------------------------------------------------------
*/
if(isset($_POST['tambah'])){

    $nama = trim($_POST['nama_kategori']);

    if(!empty($nama)){

        $cek = $pdo->prepare("
            SELECT COUNT(*)
            FROM kategori
            WHERE nama_kategori=?
        ");

        $cek->execute([$nama]);

        if($cek->fetchColumn() == 0){

            $stmt = $pdo->prepare("
                INSERT INTO kategori
                (nama_kategori)
                VALUES(?)
            ");

            $stmt->execute([$nama]);

            echo "
            <script>
            document.addEventListener('DOMContentLoaded',function(){
                Swal.fire({
                    icon:'success',
                    title:'Berhasil',
                    text:'Kategori berhasil ditambahkan'
                });
            });
            </script>";
        }
    }
}

/*
|--------------------------------------------------------------------------
| UPDATE KATEGORI
|--------------------------------------------------------------------------
*/
if(isset($_POST['update'])){

    $id   = $_POST['id_kategori'];
    $nama = trim($_POST['nama_kategori']);

    $stmt = $pdo->prepare("
        UPDATE kategori
        SET nama_kategori=?
        WHERE id_kategori=?
    ");

    $stmt->execute([$nama,$id]);

    echo "
    <script>
    document.addEventListener('DOMContentLoaded',function(){
        Swal.fire({
            icon:'success',
            title:'Berhasil',
            text:'Kategori berhasil diperbarui'
        });
    });
    </script>";
}

/*
|--------------------------------------------------------------------------
| HAPUS KATEGORI
|--------------------------------------------------------------------------
*/
if(isset($_GET['hapus'])){

    $id = $_GET['hapus'];

    $stmt = $pdo->prepare("
        DELETE FROM kategori
        WHERE id_kategori=?
    ");

    $stmt->execute([$id]);

    echo "
    <script>
    document.addEventListener('DOMContentLoaded',function(){
        Swal.fire({
            icon:'success',
            title:'Berhasil',
            text:'Kategori berhasil dihapus'
        });
    });
    </script>";
}

$data = $pdo->query("
    SELECT *
    FROM kategori
    ORDER BY id_kategori DESC
");

?>

<div class="col-md-10 p-4">

<div class="d-flex justify-content-between align-items-center mb-3">

<h3>Data Kategori</h3>

<button
class="btn btn-primary"
data-bs-toggle="modal"
data-bs-target="#modalTambah">

<i class="bi bi-plus-circle"></i>
Tambah Kategori

</button>

</div>

<div class="card shadow">

<div class="card-body">

<table class="table table-bordered table-striped datatable">

<thead>

<tr>

<th width="5%">No</th>
<th>Nama Kategori</th>
<th width="20%">Aksi</th>

</tr>

</thead>

<tbody>

<?php
$no=1;

while($row = $data->fetch(PDO::FETCH_ASSOC)):
?>

<tr>

<td><?= $no++; ?></td>

<td><?= htmlspecialchars($row['nama_kategori']); ?></td>

<td>

<button
class="btn btn-warning btn-sm"
data-bs-toggle="modal"
data-bs-target="#edit<?= $row['id_kategori']; ?>">

<i class="bi bi-pencil-square"></i>

</button>

<a
href="?hapus=<?= $row['id_kategori']; ?>"
class="btn btn-danger btn-sm btnHapus">

<i class="bi bi-trash"></i>

</a>

</td>

</tr>

<!-- MODAL EDIT -->

<div
class="modal fade"
id="edit<?= $row['id_kategori']; ?>">

<div class="modal-dialog">

<div class="modal-content">

<form method="POST">

<div class="modal-header bg-warning">

<h5 class="modal-title">

Edit Kategori

</h5>

</div>

<div class="modal-body">

<input
type="hidden"
name="id_kategori"
value="<?= $row['id_kategori']; ?>">

<label>Nama Kategori</label>

<input
type="text"
name="nama_kategori"
class="form-control"
value="<?= htmlspecialchars($row['nama_kategori']); ?>"
required>

</div>

<div class="modal-footer">

<button
type="submit"
name="update"
class="btn btn-success">

Simpan

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

<div class="modal-dialog">

<div class="modal-content">

<form method="POST">

<div class="modal-header bg-primary text-white">

<h5 class="modal-title">

Tambah Kategori

</h5>

</div>

<div class="modal-body">

<label>Nama Kategori</label>

<input
type="text"
name="nama_kategori"
class="form-control"
required>

</div>

<div class="modal-footer">

<button
type="submit"
name="tambah"
class="btn btn-primary">

Simpan

</button>

</div>

</form>

</div>

</div>

</div>

<script>

document.addEventListener(
'DOMContentLoaded',
function(){

document
.querySelectorAll('.btnHapus')
.forEach(function(btn){

btn.addEventListener(
'click',
function(e){

e.preventDefault();

let link = this.href;

Swal.fire({

title:'Hapus Data?',

text:'Data tidak dapat dikembalikan',

icon:'warning',

showCancelButton:true,

confirmButtonText:'Ya Hapus',

cancelButtonText:'Batal'

}).then((result)=>{

if(result.isConfirmed){

window.location = link;

}

});

});

});

});

</script>

<?php
include "../includes/footer.php";
?>