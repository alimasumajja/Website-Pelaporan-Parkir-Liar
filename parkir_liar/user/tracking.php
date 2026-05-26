<?php
session_start();
include __DIR__.'/../config/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

if (!isset($_GET['id'])) {
    header("Location: riwayat.php");
    exit;
}

$id_laporan = (int) $_GET['id'];

$queryLaporan = mysqli_query($conn,
    "SELECT *
    FROM laporan
    WHERE id_laporan='$id_laporan'
    AND id_user='$id_user'"
);

if (mysqli_num_rows($queryLaporan) == 0) {
    header("Location: riwayat.php");
    exit;
}

$data = mysqli_fetch_assoc($queryLaporan);

$queryStatus = mysqli_query($conn,
    "SELECT *
    FROM status_laporan
    WHERE id_laporan='$id_laporan'
    ORDER BY created_at ASC"
);

$statusList = [
    'Dikirim',
    'Diverifikasi',
    'Diproses',
    'Ditindak',
    'Selesai'
];

$currentIndex =
array_search($data['status'], $statusList);

switch ($data['status']) {
    case 'Dikirim':
        $badge = 'secondary';
        break;
    case 'Diverifikasi':
        $badge = 'info';
        break;
    case 'Diproses':
        $badge = 'warning';
        break;
    case 'Ditindak':
        $badge = 'primary';
        break;
    case 'Selesai':
        $badge = 'success';
        break;
    default:
        $badge = 'danger';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Tracking Laporan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
body{
    background:#f4f6f9;
}

.page-card{
    border:none;
    border-radius:25px;
    box-shadow:0 8px 30px rgba(0,0,0,.08);
}

.report-img{
    width:100%;
    height:350px;
    object-fit:cover;
    border-radius:20px;
}

.step-wrapper{
    display:flex;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:15px;
}

.step{
    flex:1;
    min-width:120px;
    text-align:center;
    position:relative;
}

.step-circle{
    width:60px;
    height:60px;
    border-radius:50%;
    background:#dee2e6;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:auto;
    font-size:24px;
    color:#6c757d;
}

.step.active .step-circle{
    background:#0d6efd;
    color:white;
}

.timeline-card{
    border:none;
    border-radius:20px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}

.history-item{
    border-left:4px solid #0d6efd;
    padding-left:20px;
    margin-bottom:25px;
}
</style>
</head>

<body>

<div class="container py-4">

    <?php include '../components/header.php'; ?>

    <div class="card page-card p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2 class="fw-bold">
                    <i class="bi bi-geo-alt-fill text-primary"></i>
                    Tracking Progress
                </h2>

                <p class="text-muted mb-0">
                    Pantau perkembangan laporan Anda
                </p>
            </div>

            <a href="riwayat.php"
               class="btn btn-outline-primary rounded-pill">

                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>

        </div>

        <div class="row g-4">

            <!-- FOTO -->
            <div class="col-lg-5">

                <img
                src="../assets/uploads/laporan/<?= $data['foto']; ?>"
                class="report-img">

            </div>

            <!-- DETAIL -->
            <div class="col-lg-7">

                <div class="card timeline-card p-4 h-100">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <h4 class="fw-bold mb-0">
                            <?= htmlspecialchars(
                                $data['alamat_lokasi']
                                ?: 'Lokasi Tidak Diketahui'
                            ); ?>
                        </h4>

                        <span class="badge bg-<?= $badge ?> p-3">
                            <?= $data['status']; ?>
                        </span>
                    </div>

                    <p class="text-muted">
                        <?= htmlspecialchars(
                            $data['deskripsi']
                        ); ?>
                    </p>

                    <div class="row mb-4">

                        <div class="col-md-6">
                            <strong>Latitude</strong>
                            <p class="text-muted">
                                <?= $data['latitude']; ?>
                            </p>
                        </div>

                        <div class="col-md-6">
                            <strong>Longitude</strong>
                            <p class="text-muted">
                                <?= $data['longitude']; ?>
                            </p>
                        </div>

                    </div>

                    <hr>

                    <h5 class="fw-bold mb-4">
                        Progress Status
                    </h5>

                    <div class="step-wrapper">

                        <?php foreach($statusList as $index => $status): ?>

                            <div class="step
                                <?= $index <= $currentIndex
                                ? 'active' : '' ?>">

                                <div class="step-circle">

                                    <?php if(
                                        $index <= $currentIndex
                                    ): ?>

                                        <i class="bi bi-check-lg"></i>

                                    <?php else: ?>

                                        <i class="bi bi-circle"></i>

                                    <?php endif; ?>

                                </div>

                                <div class="mt-2 fw-semibold">
                                    <?= $status ?>
                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>

            </div>

        </div>

        <!-- HISTORY -->
        <div class="card timeline-card p-4 mt-4">

            <h4 class="fw-bold mb-4">
                Riwayat Progress
            </h4>

            <?php while($status = mysqli_fetch_assoc($queryStatus)): ?>

                <div class="history-item">

                    <h5 class="fw-bold mb-1">
                        <?= $status['status']; ?>
                    </h5>

                    <p class="text-muted mb-1">
                        <?= htmlspecialchars(
                            $status['catatan']
                        ); ?>
                    </p>

                    <small class="text-secondary">
                        <i class="bi bi-clock"></i>

                        <?= date(
                            'd M Y H:i',
                            strtotime(
                                $status['created_at']
                            )
                        ); ?>
                    </small>

                </div>

            <?php endwhile; ?>

        </div>

    </div>

</div>

</body>
</html>