<?php

session_start();

if(!isset($_SESSION['login'])){

header(
"Location:auth/login.php"
);

exit;
}

include 'config/koneksi.php';

include 'includes/header.php';

include 'includes/sidebar.php';

include 'pages/dashboard.php';

include 'includes/footer.php';