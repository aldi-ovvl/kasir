<?php

include "../config/koneksi.php";
include "../includes/header.php";
include "../includes/sidebar.php";

/*
|--------------------------------------------------------------------------
| TAMBAH USER
|--------------------------------------------------------------------------
*/

if (isset($_POST['tambah'])) {

    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    $cek = $pdo->prepare("
        SELECT id
        FROM users
        WHERE username=?
    ");

    $cek->execute([$username]);

    if ($cek->rowCount() > 0) {

        echo "
        <script>
        Swal.fire({
            icon:'error',
            title:'Gagal',
            text:'Username sudah digunakan'
        });
        </script>
        ";

    } else {

        $hash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $stmt = $pdo->prepare("
            INSERT INTO users
            (
                nama,
                username,
                password,
                role
            )
            VALUES
            (
                ?,?,?,?
            )
        ");

        $stmt->execute([
            $nama,
            $username,
            $hash,
            $role
        ]);

        echo "
        <script>
        Swal.fire({
            icon:'success',
            title:'Berhasil',
            text:'User berhasil ditambahkan'
        }).then(()=>{
            location='users.php';
        });
        </script>
        ";
    }
}

/*
|--------------------------------------------------------------------------
| UPDATE USER
|--------------------------------------------------------------------------
*/

if (isset($_POST['update'])) {

    $id = $_POST['id'];
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $role = $_POST['role'];

    if (!empty($_POST['password'])) {

        $hash = password_hash(
            $_POST['password'],
            PASSWORD_DEFAULT
        );

        $stmt = $pdo->prepare("
            UPDATE users
            SET
                nama=?,
                username=?,
                password=?,
                role=?
            WHERE id=?
        ");

        $stmt->execute([
            $nama,
            $username,
            $hash,
            $role,
            $id
        ]);

    } else {

        $stmt = $pdo->prepare("
            UPDATE users
            SET
                nama=?,
                username=?,
                role=?
            WHERE id=?
        ");

        $stmt->execute([
            $nama,
            $username,
            $role,
            $id
        ]);
    }

    echo "
    <script>
    Swal.fire({
        icon:'success',
        title:'Berhasil',
        text:'User berhasil diupdate'
    }).then(()=>{
        location='users.php';
    });
    </script>
    ";
}

/*
|--------------------------------------------------------------------------
| HAPUS USER
|--------------------------------------------------------------------------
*/

if (isset($_GET['hapus'])) {

    $id = $_GET['hapus'];

    if ($id == $_SESSION['id']) {

        echo "
        <script>
        Swal.fire({
            icon:'warning',
            title:'Peringatan',
            text:'Tidak bisa menghapus akun sendiri'
        }).then(()=>{
            location='users.php';
        });
        </script>
        ";

    } else {

        $stmt = $pdo->prepare("
            DELETE FROM users
            WHERE id=?
        ");

        $stmt->execute([$id]);

        echo "
        <script>
        Swal.fire({
            icon:'success',
            title:'Berhasil',
            text:'User berhasil dihapus'
        }).then(()=>{
            location='users.php';
        });
        </script>
        ";
    }
}

?>

<div class="col-md-10 p-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        ```
        <h3>Data Users</h3>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">

            <i class="bi bi-plus-circle"></i>
            Tambah User

        </button>
        ```

    </div>

    <div class="card shadow">

        <div class="card-body">

            <table class="table table-bordered table-striped datatable">

                <thead>

                    <tr>

                        ```
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Tanggal Dibuat</th>
                        <th width="130">Aksi</th>
                        ```

                    </tr>

                </thead>

                <tbody>

                    <?php

                    $no = 1;

                    $query = $pdo->query("
    SELECT *
    FROM users
    ORDER BY id DESC
");

                    while ($row = $query->fetch(PDO::FETCH_ASSOC)):

                        ?>

                        <tr>

                            <td><?= $no++; ?></td>

                            <td><?= htmlspecialchars($row['nama']); ?></td>

                            <td><?= htmlspecialchars($row['username']); ?></td>

                            <td>

                                <?php if ($row['role'] == 'admin'): ?>

                                    <span class="badge bg-danger">
                                        Admin
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-success">
                                        Kasir
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <?= date(
                                    'd-m-Y H:i',
                                    strtotime($row['created_at'])
                                ); ?>

                            </td>

                            <td>

                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#edit<?= $row['id']; ?>">

                                    <i class="bi bi-pencil-square"></i>

                                </button>

                                <a href="?hapus=<?= $row['id']; ?>" class="btn btn-danger btn-sm btnHapus">

                                    <i class="bi bi-trash"></i>

                                </a>

                            </td>

                        </tr>

                        <!-- MODAL EDIT -->

                        <div class="modal fade" id="edit<?= $row['id']; ?>">

                            <div class="modal-dialog">

                                <div class="modal-content">

                                    <form method="POST">

                                        <div class="modal-header bg-warning">

                                            <h5 class="modal-title">
                                                Edit User
                                            </h5>

                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                        </div>

                                        <div class="modal-body">

                                            <input type="hidden" name="id" value="<?= $row['id']; ?>">

                                            <div class="mb-3">

                                                <label>Nama</label>

                                                <input type="text" name="nama" class="form-control"
                                                    value="<?= $row['nama']; ?>" required>

                                            </div>

                                            <div class="mb-3">

                                                <label>Username</label>

                                                <input type="text" name="username" class="form-control"
                                                    value="<?= $row['username']; ?>" required>

                                            </div>

                                            <div class="mb-3">

                                                <label>Password Baru</label>

                                                <input type="password" name="password" class="form-control">

                                                <small class="text-muted">
                                                    Kosongkan jika tidak ingin mengganti password
                                                </small>

                                            </div>

                                            <div class="mb-3">

                                                <label>Role</label>

                                                <select name="role" class="form-select">

                                                    <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : ''; ?>>

                                                        Admin

                                                    </option>

                                                    <option value="kasir" <?= $row['role'] == 'kasir' ? 'selected' : ''; ?>>

                                                        Kasir

                                                    </option>

                                                </select>

                                            </div>

                                        </div>

                                        <div class="modal-footer">

                                            <button type="submit" name="update" class="btn btn-success">

                                                Update User

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

<div class="modal fade" id="modalTambah">

    <div class="modal-dialog">

        <div class="modal-content">

            <form method="POST">

                <div class="modal-header bg-primary text-white">

                    <h5 class="modal-title">

                        Tambah User

                    </h5>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label>Nama</label>

                        <input type="text" name="nama" class="form-control" required>

                    </div>

                    <div class="mb-3">

                        <label>Username</label>

                        <input type="text" name="username" class="form-control" required>

                    </div>

                    <div class="mb-3">

                        <label>Password</label>

                        <input type="password" name="password" class="form-control" required>

                    </div>

                    <div class="mb-3">

                        <label>Role</label>

                        <select name="role" class="form-select">

                            <option value="admin">
                                Admin
                            </option>

                            <option value="kasir">
                                Kasir
                            </option>

                        </select>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="submit" name="tambah" class="btn btn-primary">

                        Simpan User

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>

    document
        .querySelectorAll('.btnHapus')
        .forEach(function (btn) {

            btn.addEventListener(
                'click',
                function (e) {

                    e.preventDefault();

                    let url = this.href;

                    Swal.fire({

                        title: 'Hapus User?',

                        text: 'Data tidak dapat dikembalikan',

                        icon: 'warning',

                        showCancelButton: true,

                        confirmButtonText: 'Ya, Hapus',

                        cancelButtonText: 'Batal'

                    }).then((result) => {

                        if (result.isConfirmed) {

                            window.location = url;

                        }

                    });

                });

        });

</script>

<?php
include "../includes/footer.php";
?>