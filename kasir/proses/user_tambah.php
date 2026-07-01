<?php

include "../config/koneksi.php";

$nama = $_POST['nama'];
$username = $_POST['username'];

$password = password_hash(
    $_POST['password'],
    PASSWORD_DEFAULT
);

$role = $_POST['role'];

mysqli_query($koneksi, "
INSERT INTO users
(
nama,
username,
password,
role
)
VALUES
(
'$nama',
'$username',
'$password',
'$role'
)
");

header("Location: ../index.php?page=users");