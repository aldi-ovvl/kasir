<?php

session_start();

if (!isset($_SESSION['login'])) {

    header("Location: auth/login.php");

}

header("Location: pages/dashboard.php");