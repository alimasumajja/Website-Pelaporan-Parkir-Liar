<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

$id = (int) $_GET['id'];

mysqli_query($conn,
"DELETE FROM users
WHERE id_user='$id'");

header("Location: users.php");