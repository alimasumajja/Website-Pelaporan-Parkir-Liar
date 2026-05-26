<?php
session_start();
include __DIR__.'/../config/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION['role'] != 'admin') {
    header("Location: ../user/dashboard.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| STATISTIK
|--------------------------------------------------------------------------
*/

$totalLaporan = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as total
    FROM laporan"
))['total'];

$dikirim = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as total
    FROM laporan
    WHERE status='Dikirim'"
))['total'];

$diproses = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as total
    FROM laporan
    WHERE status='Diproses'"
))['total'];

$selesai = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as total
    FROM laporan
    WHERE status='Selesai'"
))['total'];

$laporanTerbaru = mysqli_query($conn,
    "SELECT laporan.*,
    users.nama_lengkap
    FROM laporan
    JOIN users
    ON users.id_user = laporan.id_user
    ORDER BY laporan.created_at DESC
    LIMIT 10"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Dashboard Admin</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
body{
    background:#f4f6f9;
}

.sidebar{
    min-height:100vh;
    background:
    linear-gradient(180deg,#0d6efd,#2563eb);
    padding:30px 20px;
}

.sidebar a{
    color:white;
    text-decoration:none;
    padding:14px 16px;
    display:block;
    border-radius:14px;
    margin-bottom:10px;
    transition:.3s;
}

.sidebar a:hover,
.sidebar .active{
    background:rgba(255,255,255,.15);
}

.page-card{
    border:none;
    border-radius:24px;
    box-shadow:
    0 8px 25px rgba(0,0,0,.08);
}

.stat-card{
    border:none;
    border-radius:24px;
    color:white;
    overflow:hidden;
}

.table img{
    width:65px;
    height:65px;
    object-fit:cover;
    border-radius:12px;
}
</style>
</head>

<body>

<div class="container-fluid">

<div class="row">

    <!-- SIDEBAR -->
    <div class="col-lg-2 sidebar">

        <h3 class="text-white fw-bold mb-4">
            <i class="bi bi-shield-check"></i>
            Admin
        </h3>

        <a href="dashboard.php" class="active">
            <i class="bi bi-house-door-fill me-2"></i>
            Dashboard
        </a>

        <a href="laporan.php">
            <i class="bi bi-file-earmark-text-fill me-2"></i>
            Data Laporan
        </a>

        <a href="peta.php">
            <i class="bi bi-map-fill me-2"></i>
            Peta Pelanggaran
        </a>

        <a href="grafik.php">
            <i class="bi bi-bar-chart-fill me-2"></i>
            Grafik
        </a>
        <a href="users.php">
            <i class="bi bi-person-fill-gear me-2"></i>
            Users
        </a>

        <a href="../logout.php">
            <i class="bi bi-box-arrow-right me-2"></i>
            Logout
        </a>

    </div>

    <!-- CONTENT -->
    <div class="col-lg-10 p-4">

        <?php include '../components/header.php'; ?>

        <div class="mb-4">

            <h2 class="fw-bold">
                Dashboard Admin
            </h2>

            <p class="text-muted">
                Monitoring laporan parkir liar masyarakat
            </p>

        </div>

        <!-- STATISTIK -->
        <div class="row g-4 mb-4">

            <div class="col-md-3">

                <div class="card stat-card bg-primary p-4">

                    <h5>Total Laporan</h5>

                    <h1 class="fw-bold">
                        <?= $totalLaporan ?>
                    </h1>

                    <i class="bi bi-file-earmark-text fs-2"></i>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card stat-card bg-secondary p-4">

                    <h5>Dikirim</h5>

                    <h1 class="fw-bold">
                        <?= $dikirim ?>
                    </h1>

                    <i class="bi bi-send-fill fs-2"></i>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card stat-card bg-warning p-4">

                    <h5>Diproses</h5>

                    <h1 class="fw-bold">
                        <?= $diproses ?>
                    </h1>

                    <i class="bi bi-gear-fill fs-2"></i>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card stat-card bg-success p-4">

                    <h5>Selesai</h5>

                    <h1 class="fw-bold">
                        <?= $selesai ?>
                    </h1>

                    <i class="bi bi-check-circle-fill fs-2"></i>

                </div>

            </div>

        </div>

        <div class="row g-4">

            <!-- GRAFIK -->
            <div class="col-lg-5">

                <div class="card page-card p-4">

                    <h5 class="fw-bold mb-3">
                        Grafik Status Laporan
                    </h5>

                    <canvas id="chartLaporan"></canvas>

                </div>

            </div>

            <!-- LAPORAN TERBARU -->
            <div class="col-lg-7">

                <div class="card page-card p-4">

                    <div class="d-flex justify-content-between">

                        <h5 class="fw-bold">
                            Laporan Terbaru
                        </h5>

                        <a href="laporan.php"
                           class="btn btn-primary rounded-pill">

                            Lihat Semua
                        </a>

                    </div>

                    <div class="table-responsive mt-3">

                        <table class="table align-middle">

                            <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Pelapor</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                            </tr>
                            </thead>

                            <tbody>

                            <?php while($row =
                            mysqli_fetch_assoc($laporanTerbaru)): ?>

                                <tr>

                                    <td>
                                        <img src="../assets/uploads/laporan/<?= $row['foto']; ?>">
                                    </td>

                                    <td>
                                        <?= $row['nama_lengkap']; ?>
                                    </td>

                                    <td>
                                        <?= $row['alamat_lokasi']; ?>
                                    </td>

                                    <td>
                                        <span class="badge bg-primary">
                                            <?= $row['status']; ?>
                                        </span>
                                    </td>

                                </tr>

                            <?php endwhile; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(
document.getElementById('chartLaporan'),
{
    type:'doughnut',

    data:{
        labels:[
            'Dikirim',
            'Diproses',
            'Selesai'
        ],

        datasets:[{
            data:[
                <?= $dikirim ?>,
                <?= $diproses ?>,
                <?= $selesai ?>
            ]
        }]
    }
});
</script>

</body>
</html>