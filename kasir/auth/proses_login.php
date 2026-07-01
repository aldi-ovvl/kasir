<?php

session_start();

require '../config/koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $pdo->prepare(
    "SELECT * FROM users WHERE username=?"
);

$stmt->execute([$username]);

$user = $stmt->fetch();

if ($user) {

    if (
        password_verify(
            $password,
            $user['password']
        )
    ) {

        $_SESSION['login'] = true;
        $_SESSION['id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];

        header("Location: ../index.php");
        exit;

    } else {

        echo "Password Salah";

    }

} else {

    echo "User Tidak Ditemukan";

}