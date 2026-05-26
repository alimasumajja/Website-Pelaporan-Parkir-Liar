<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

$nama = $_POST['nama_lengkap'];
$email = $_POST['email'];
$password = md5($_POST['password']);
$role = $_POST['role'];

mysqli_query($conn,
"INSERT INTO users(
nama_lengkap,email,password,role
)
VALUES(
'$nama',
'$email',
'$password',
'$role'
)");

header("Location: users.php");