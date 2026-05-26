<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$nama = $_SESSION['nama'];
$id_user = $_SESSION['id_user'];

$queryRiwayat = mysqli_query($conn,
    "SELECT *
    FROM laporan
    WHERE id_user='$id_user'
    ORDER BY created_at DESC
    LIMIT 5"
);

$totalLaporan = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT COUNT(*) as total
    FROM laporan
    WHERE id_user='$id_user'")
)['total'];

$diproses = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT COUNT(*) as total
    FROM laporan
    WHERE id_user='$id_user'
    AND status='Diproses'")
)['total'];

$selesai = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT COUNT(*) as total
    FROM laporan
    WHERE id_user='$id_user'
    AND status='Selesai'")
)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard User</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
body{
    background:#f4f6f9;
}

.sidebar{
    min-height:100vh;
    background:linear-gradient(180deg,#0d6efd,#2563eb);
    color:white;
}

.sidebar .nav-link{
    color:rgba(255,255,255,.85);
    padding:12px;
    border-radius:12px;
    margin-bottom:8px;
    transition:.3s;
}

.sidebar .nav-link:hover{
    background:rgba(255,255,255,.15);
    color:white;
}

.sidebar .nav-link.active{
    background:white;
    color:#0d6efd;
    font-weight:600;
}

.card-dashboard{
    border:none;
    border-radius:20px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}

.stat-icon{
    font-size:40px;
}

.quick-card{
    transition:.3s;
    cursor:pointer;
}

.quick-card:hover{
    transform:translateY(-5px);
}

.topbar{
    background:white;
    border-radius:20px;
    padding:18px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}

.stat-card{
    border-radius:24px;
    transition:.3s;
    background:#fff;
}

.icon-box{
    width:70px;
    height:70px;
    border-radius:20px;
    display:flex;
    align-items:center;
    justify-content:center;
}

.icon-box i{
    font-size:32px;
}

.content-wrapper{
    min-height:100vh;
    width:100%;
}

.content-area{
    padding:35px;
}

.stat-card{
    border-radius:24px;
    transition:.3s;
}

.stat-card:hover{
    transform:translateY(-4px);
}

.icon-box{
    width:70px;
    height:70px;
    border-radius:20px;
    display:flex;
    align-items:center;
    justify-content:center;
}

.icon-box i{
    font-size:32px;
}

@media(max-width:768px){

    .sidebar-user{
        width:100%;
        min-height:auto;
    }

    .content-area{
        padding:20px;
    }
}
</style>
</head>

<body style="background:#f4f6f9;">

<div class="container-fluid p-0">

    <div class="d-flex">

        <!-- SIDEBAR -->
        <?php
        include '../components/sidebar_user.php';
        ?>

        <!-- CONTENT -->
        <div class="content-wrapper flex-grow-1">

            <div class="content-area">

                <h2 class="fw-bold mb-4">
                    Dashboard User
                </h2>

                <!-- CARD STATISTIK -->
                <div class="row g-4 mb-4">

                    <!-- TOTAL -->
                    <div class="col-md-4">

                        <div class="card stat-card border-0 shadow-sm h-100">

                            <div class="card-body p-4">

                                <div class="d-flex
                                justify-content-between
                                align-items-center">

                                    <div>

                                        <p class="text-muted mb-2">
                                            Total Laporan
                                        </p>

                                        <h1 class="fw-bold mb-0">
                                            <?= $totalLaporan ?>
                                        </h1>

                                    </div>

                                    <div class="icon-box bg-primary-subtle">

                                        <i class="bi bi-file-earmark-text-fill
                                        text-primary"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- DIPROSES -->
                    <div class="col-md-4">

                        <div class="card stat-card border-0 shadow-sm h-100">

                            <div class="card-body p-4">

                                <div class="d-flex
                                justify-content-between
                                align-items-center">

                                    <div>

                                        <p class="text-muted mb-2">
                                            Diproses
                                        </p>

                                        <h1 class="fw-bold mb-0">
                                            <?= $diproses ?>
                                        </h1>

                                    </div>

                                    <div class="icon-box bg-warning-subtle">

                                        <i class="bi bi-arrow-repeat
                                        text-warning"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- SELESAI -->
                    <div class="col-md-4">

                        <div class="card stat-card border-0 shadow-sm h-100">

                            <div class="card-body p-4">

                                <div class="d-flex
                                justify-content-between
                                align-items-center">

                                    <div>

                                        <p class="text-muted mb-2">
                                            Selesai
                                        </p>

                                        <h1 class="fw-bold mb-0">
                                            <?= $selesai ?>
                                        </h1>

                                    </div>

                                    <div class="icon-box bg-success-subtle">

                                        <i class="bi bi-check-lg
                                        text-success"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- RIWAYAT -->
                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body p-4">

                        <h4 class="fw-bold mb-4">
                            Riwayat Laporan
                        </h4>

                        <div class="table-responsive">

                            <table
                            class="table align-middle">

                                <thead>

                                    <tr>
                                        <th>Lokasi</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>

                                </thead>

                                <tbody>

                                <?php while(
                                $row =
                                mysqli_fetch_assoc(
                                $queryRiwayat
                                )): ?>

                                    <tr>

                                        <td>
                                            <?= htmlspecialchars(
                                            $row['alamat_lokasi']
                                            ?: '-') ?>
                                        </td>

                                        <td>
                                            <?= $row['status'] ?>
                                        </td>

                                        <td>

                                            <?= date(
                                            'd M Y',
                                            strtotime(
                                            $row['created_at']
                                            )) ?>

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

</body>
</html>