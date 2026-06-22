<?php

session_start();

if (isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Register Kasir</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container">

        <div class="row justify-content-center mt-5">

            <div class="col-md-5">

                <div class="card shadow">

                    <div class="card-header text-center">

                        <h4>REGISTER USER</h4>

                    </div>

                    <div class="card-body">

                        <form action="proses_register.php" method="POST">

                            <div class="mb-3">

                                <label>Nama Lengkap</label>

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

                                <label>Konfirmasi Password</label>

                                <input type="password" name="konfirmasi" class="form-control" required>

                            </div>

                            <button type="submit" class="btn btn-success w-100">

                                Daftar

                            </button>

                            <a href="login.php" class="btn btn-secondary w-100 mt-2">

                                Kembali ke Login

                            </a>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>