<?php
session_start();
include __DIR__.'/../config/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: lapor.php");
    exit;
}

$id_user = $_SESSION['id_user'];

$deskripsi = mysqli_real_escape_string(
    $conn,
    $_POST['deskripsi']
);

$alamat_lokasi = mysqli_real_escape_string(
    $conn,
    $_POST['alamat_lokasi']
);

$latitude = mysqli_real_escape_string(
    $conn,
    $_POST['latitude']
);

$longitude = mysqli_real_escape_string(
    $conn,
    $_POST['longitude']
);

$status = "error";
$pesan  = "";

/*
|-------------------------------------------
| VALIDASI FOTO
|-------------------------------------------
*/

if (!isset($_FILES['foto']) ||
    $_FILES['foto']['error'] !== 0) {

    $pesan = "Foto laporan wajib diupload.";

} else {

    $namaFile = $_FILES['foto']['name'];
    $tmpFile  = $_FILES['foto']['tmp_name'];

    $ekstensiValid = ['jpg','jpeg','png','webp'];

    $ext = strtolower(
        pathinfo($namaFile, PATHINFO_EXTENSION)
    );

    if (!in_array($ext, $ekstensiValid)) {

        $pesan = "Format file harus JPG, JPEG, PNG, atau WEBP.";

    } else {

        /*
        |-------------------------------------------
        | UPLOAD FILE
        |-------------------------------------------
        */

        $folderUpload =
            "../assets/uploads/laporan/";

        if (!file_exists($folderUpload)) {
            mkdir(
                $folderUpload,
                0777,
                true
            );
        }

        $namaBaru =
            "laporan_" .
            time() .
            "_" .
            rand(1000,9999) .
            "." .
            $ext;

        $pathUpload =
            $folderUpload .
            $namaBaru;

        if (move_uploaded_file(
            $tmpFile,
            $pathUpload
        )) {

            /*
            |-------------------------------------------
            | INSERT LAPORAN
            |-------------------------------------------
            */

            $insert = mysqli_query($conn,
                "INSERT INTO laporan (
                    id_user,
                    foto,
                    deskripsi,
                    latitude,
                    longitude,
                    alamat_lokasi,
                    status
                )
                VALUES (
                    '$id_user',
                    '$namaBaru',
                    '$deskripsi',
                    '$latitude',
                    '$longitude',
                    '$alamat_lokasi',
                    'Dikirim'
                )"
            );

            if ($insert) {

                $id_laporan =
                    mysqli_insert_id($conn);

                /*
                |-------------------------------------------
                | INSERT STATUS AWAL
                |-------------------------------------------
                */

                mysqli_query($conn,
                    "INSERT INTO status_laporan (
                        id_laporan,
                        status,
                        catatan
                    )
                    VALUES (
                        '$id_laporan',
                        'Dikirim',
                        'Laporan berhasil dikirim'
                    )"
                );

                $status = "success";
                $pesan =
                    "Laporan berhasil dikirim.";

            } else {

                $pesan =
                    "Gagal menyimpan data ke database.";
            }

        } else {

            $pesan =
                "Gagal upload foto.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Status Laporan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:
    linear-gradient(135deg,#0d6efd,#4f46e5);
    padding:20px;
}

.result-card{
    border:none;
    border-radius:30px;
    box-shadow:
    0 20px 40px rgba(0,0,0,.15);
}

.icon-box{
    width:120px;
    height:120px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:auto;
    font-size:55px;
}

.success-box{
    background:#d1fae5;
    color:#198754;
}

.error-box{
    background:#ffe2e2;
    color:#dc3545;
}

.btn-custom{
    border-radius:15px;
    padding:14px;
    font-weight:600;
}
</style>
</head>

<body>

<div class="card result-card p-5 text-center col-lg-5">

    <?php if($status == 'success'): ?>

        <div class="icon-box success-box mb-4">
            <i class="bi bi-check-circle-fill"></i>
        </div>

        <h2 class="fw-bold text-success">
            Berhasil
        </h2>

        <p class="text-muted">
            <?= $pesan ?>
        </p>

        <div class="alert alert-success rounded-4">
            Laporan telah diterima dan
            menunggu verifikasi petugas.
        </div>

    <?php else: ?>

        <div class="icon-box error-box mb-4">
            <i class="bi bi-x-circle-fill"></i>
        </div>

        <h2 class="fw-bold text-danger">
            Gagal
        </h2>

        <p class="text-muted">
            <?= $pesan ?>
        </p>

        <div class="alert alert-danger rounded-4">
            Periksa kembali data laporan.
        </div>

    <?php endif; ?>

    <div class="d-grid gap-3 mt-4">

        <a href="dashboard.php"
           class="btn btn-primary btn-custom">

            <i class="bi bi-house-door-fill"></i>
            Dashboard
        </a>

        <a href="lapor.php"
           class="btn btn-outline-secondary btn-custom">

            <i class="bi bi-arrow-repeat"></i>
            Buat Laporan Lagi
        </a>

    </div>

</div>

</body>
</html>