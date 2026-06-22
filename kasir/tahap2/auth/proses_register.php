<?php

require '../config/koneksi.php';

$nama = trim($_POST['nama']);
$username = trim($_POST['username']);
$password = $_POST['password'];
$konfirmasi = $_POST['konfirmasi'];

if ($password != $konfirmasi) {

    echo "
    <script>
    alert('Konfirmasi password tidak cocok');
    window.location='register.php';
    </script>
    ";
    exit;
}

$cek = $pdo->prepare("
SELECT id
FROM users
WHERE username=?
");

$cek->execute([$username]);

if ($cek->rowCount() > 0) {

    echo "
    <script>
    alert('Username sudah digunakan');
    window.location='register.php';
    </script>
    ";
    exit;
}

$passwordHash = password_hash(
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
?,
?,
?,
'kasir'
)
");

$stmt->execute([
    $nama,
    $username,
    $passwordHash
]);

echo "
<script>
alert('Registrasi berhasil');
window.location='login.php';
</script>
";