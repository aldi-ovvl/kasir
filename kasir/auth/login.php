<?php
session_start();

if (isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>POS Kasir - Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg,
                    #0d6efd,
                    #4f46e5);
        }

        .login-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, .2);
        }

        .login-left {
            background: white;
            padding: 40px;
        }

        .login-right {
            background: #0d6efd;
            color: white;
            padding: 40px;
        }

        .logo-circle {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: #0d6efd;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
            color: white;
            font-size: 40px;
        }

        .form-control {
            border-radius: 12px;
        }

        .btn-login {
            border-radius: 12px;
            padding: 10px;
        }

        @media(max-width:768px) {

            .login-right {
                display: none;
            }

        }
    </style>

</head>

<body>

    <div class="container">

        <div class="row justify-content-center align-items-center min-vh-100">

            <div class="col-lg-9">

                <div class="card login-card">

                    <div class="row g-0">

                        <div class="col-md-6">

                            <div class="login-left">

                                <div class="text-center mb-4">

                                    <div class="logo-circle">

                                        <i class="bi bi-shop"></i>

                                    </div>

                                    <h3 class="mt-3 fw-bold">

                                        POS KASIR

                                    </h3>

                                    <p class="text-muted">

                                        Sistem Point Of Sale Modern

                                    </p>

                                </div>

                                <form action="proses_login.php" method="POST">

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Username

                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text">

                                                <i class="bi bi-person"></i>

                                            </span>

                                            <input type="text" name="username" class="form-control" required>

                                        </div>

                                    </div>

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Password

                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text">

                                                <i class="bi bi-lock"></i>

                                            </span>

                                            <input type="password" name="password" id="password" class="form-control"
                                                required>

                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="togglePassword()">

                                                <i class="bi bi-eye"></i>

                                            </button>

                                        </div>

                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 btn-login">

                                        <i class="bi bi-box-arrow-in-right"></i>
                                        Login

                                    </button>

                                    

                                </form>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="login-right h-100 d-flex flex-column justify-content-center">

                                <h2 class="fw-bold">

                                    Selamat Datang 👋

                                </h2>

                                <p>

                                    Kelola transaksi, produk, kategori,
                                    laporan, dan pengguna dalam satu sistem.

                                </p>

                                <hr>

                                <div class="mt-3">

                                    <p>

                                        <i class="bi bi-check-circle-fill"></i>
                                        Manajemen Produk

                                    </p>

                                    <p>

                                        <i class="bi bi-check-circle-fill"></i>
                                        Transaksi Kasir

                                    </p>

                                    <p>

                                        <i class="bi bi-check-circle-fill"></i>
                                        Laporan Penjualan

                                    </p>

                                    <p>

                                        <i class="bi bi-check-circle-fill"></i>
                                        Multi User Admin & Kasir

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script>

        function togglePassword() {

            const pass =
                document.getElementById('password');

            if (pass.type === 'password') {
                pass.type = 'text';
            } else {
                pass.type = 'password';
            }

        }

    </script>

</body>

</html>