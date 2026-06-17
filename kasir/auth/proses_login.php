<?php
session_start();
include '../config/koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare(
"SELECT * FROM users
 WHERE username=?");

$stmt->bind_param(
"s",
$username
);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if($user){

if(password_verify(
$password,
$user['password']
)){

$_SESSION['login']=true;
$_SESSION['id']=$user['id'];
$_SESSION['nama']=$user['nama'];
$_SESSION['role']=$user['role'];

header("Location:../index.php");

}else{

header("Location:login.php?error");
}

}else{

header("Location:login.php?error");
}