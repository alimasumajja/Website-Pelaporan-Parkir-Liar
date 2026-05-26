<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

$id_user = $_POST['id_user'];
$nama = $_POST['nama_lengkap'];
$email = $_POST['email'];
$role = $_POST['role'];

mysqli_query($conn,
"UPDATE users
SET nama_lengkap='$nama',
email='$email',
role='$role'
WHERE id_user='$id_user'
");

header("Location: users.php");