<?php
session_start();

if (isset($_SESSION['id_user'])) {

    if ($_SESSION['role'] == 'admin') {
        header("Location: admin/dashboard.php");
        exit;
    } else {
        header("Location: user/dashboard.php");
        exit;
    }

} else {

    header("Location: login.php");
    exit;
}
?>