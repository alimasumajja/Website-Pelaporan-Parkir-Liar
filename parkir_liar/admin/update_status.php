<?php
session_start();
include __DIR__ . '/../config/koneksi.php';


// PROTEKSI LOGIN ADMIN

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION['role'] != 'admin') {
    header("Location: ../user/dashboard.php");
    exit;
}


// VALIDASI REQUEST


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: laporan.php");
    exit;
}

$id_laporan = (int) $_POST['id_laporan'];

$statusBaru = mysqli_real_escape_string(
    $conn,
    $_POST['status']
);


// VALIDASI STATUS


$statusValid = [
    'Dikirim',
    'Diverifikasi',
    'Diproses',
    'Ditindak',
    'Selesai'
];

if (!in_array($statusBaru, $statusValid)) {

    header(
        "Location: laporan.php?error=status"
    );
    exit;
}


//UPDATE STATUS LAPORAN


$update = mysqli_query($conn,
    "UPDATE laporan
    SET status='$statusBaru'
    WHERE id_laporan='$id_laporan'"
);



// INSERT HISTORY STATUS


if ($update) {

    $catatan =
    "Status laporan diperbarui menjadi $statusBaru";

    mysqli_query($conn,
        "INSERT INTO status_laporan (
            id_laporan,
            status,
            catatan
        )
        VALUES (
            '$id_laporan',
            '$statusBaru',
            '$catatan'
        )"
    );

    //REDIRECT KEMBALI
    

    if (isset($_POST['from_detail'])) {

        header(
            "Location: detail_laporan.php?id=$id_laporan&success=1"
        );

    } else {

        header(
            "Location: laporan.php?success=1"
        );
    }

    exit;

} else {

    header(
        "Location: laporan.php?error=db"
    );

    exit;
}
?>