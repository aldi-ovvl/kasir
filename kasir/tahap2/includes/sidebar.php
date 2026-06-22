<div class="col-md-2 sidebar">

    <ul class="nav flex-column mt-3">

        <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="transaksi.php">
                <i class="bi bi-cart"></i>
                Transaksi
            </a>
        </li>

        <?php if ($_SESSION['role'] == 'admin'): ?>

        <li class="nav-item">
            <a class="nav-link" href="kategori.php">
                <i class="bi bi-tags"></i>
                Kategori
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="produk.php">
                <i class="bi bi-box-seam"></i>
                Produk
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="laporan.php">
                <i class="bi bi-file-earmark-bar-graph"></i>
                Laporan
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="users.php">
                <i class="bi bi-people"></i>
                Users
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="pengaturan.php">
                <i class="bi bi-gear"></i>
                Pengaturan
            </a>
        </li>

        <?php endif; ?>

    </ul>

</div>