<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION['role'] != 'admin') {
    header("Location: ../user/dashboard.php");
    exit;
}


// DATA STATUS
$statusList = [
    'Dikirim',
    'Diverifikasi',
    'Diproses',
    'Ditindak',
    'Selesai'
];

$statusData = [];

foreach($statusList as $status){

    $result = mysqli_fetch_assoc(
        mysqli_query($conn,
        "SELECT COUNT(*) AS total
        FROM laporan
        WHERE status='$status'")
    );

    $statusData[] =
    $result['total'];
}

// DATA BULANAN

$bulanLabel = [];
$jumlahLaporan = [];

for($i = 1; $i <= 12; $i++){

    $bulanLabel[] = date(
        'M',
        mktime(0,0,0,$i,1)
    );

    $result = mysqli_fetch_assoc(
        mysqli_query($conn,
        "SELECT COUNT(*) AS total
        FROM laporan
        WHERE MONTH(created_at)='$i'
        AND YEAR(created_at)=YEAR(CURDATE())")
    );

    $jumlahLaporan[] =
    $result['total'];
}

// TOTAL STATISTIK

$total = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT COUNT(*) as total
    FROM laporan")
)['total'];

$selesai = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT COUNT(*) as total
    FROM laporan
    WHERE status='Selesai'")
)['total'];

$diproses = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT COUNT(*) as total
    FROM laporan
    WHERE status='Diproses'")
)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Grafik Pelaporan</title>

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

.chart-card{
    border:none;
    border-radius:20px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}

.stat-card{
    border-radius:20px;
    color:white;
    padding:25px;
}
</style>
</head>

<body>

<div class="container-fluid py-4">

    <?php include '../components/header.php'; ?>

    <div class="card page-card p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="fw-bold">
                    <i class="bi bi-bar-chart-fill text-primary"></i>
                    Grafik Pelaporan
                </h2>

                <p class="text-muted mb-0">
                    Statistik laporan parkir liar masyarakat
                </p>

            </div>

            <a href="dashboard.php"
               class="btn btn-outline-primary rounded-pill">

                <i class="bi bi-arrow-left"></i>
                Dashboard
            </a>

        </div>

        <!-- STATISTIK -->
        <div class="row g-4 mb-4">

            <div class="col-md-4">

                <div class="stat-card bg-primary">
                    <small>Total Laporan</small>
                    <h1><?= $total ?></h1>
                </div>

            </div>

            <div class="col-md-4">

                <div class="stat-card bg-warning">
                    <small>Diproses</small>
                    <h1><?= $diproses ?></h1>
                </div>

            </div>

            <div class="col-md-4">

                <div class="stat-card bg-success">
                    <small>Selesai</small>
                    <h1><?= $selesai ?></h1>
                </div>

            </div>

        </div>

        <div class="row g-4">

            <!-- STATUS -->
            <div class="col-lg-5">

                <div class="card chart-card p-4">

                    <h5 class="fw-bold mb-4">
                        Status Laporan
                    </h5>

                    <canvas
                    id="statusChart">
                    </canvas>

                </div>

            </div>

            <!-- BULAN -->
            <div class="col-lg-7">

                <div class="card chart-card p-4">

                    <h5 class="fw-bold mb-4">
                        Laporan per Bulan
                    </h5>

                    <canvas
                    id="monthlyChart">
                    </canvas>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

// chart status
new Chart(
document.getElementById(
'statusChart'),
{
    type:'doughnut',

    data:{
        labels:
        <?= json_encode(
            $statusList
        ); ?>,

        datasets:[{
            data:
            <?= json_encode(
                $statusData
            ); ?>
        }]
    }
});

// chart bulanan
new Chart(
document.getElementById(
'monthlyChart'),
{
    type:'bar',

    data:{
        labels:
        <?= json_encode(
            $bulanLabel
        ); ?>,

        datasets:[{
            label:
            'Jumlah Laporan',

            data:
            <?= json_encode(
                $jumlahLaporan
            ); ?>
        }]
    }
});
</script>

</body>
</html>