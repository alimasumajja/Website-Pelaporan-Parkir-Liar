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

$query = mysqli_query($conn,
    "SELECT laporan.*,
    users.nama_lengkap
    FROM laporan
    JOIN users
    ON users.id_user = laporan.id_user
    ORDER BY laporan.created_at DESC"
);

$dataLaporan = [];

while($row = mysqli_fetch_assoc($query)){
    $dataLaporan[] = $row;
}

/*
|--------------------------------------------------------------------------
| STATISTIK
|--------------------------------------------------------------------------
*/

$total = count($dataLaporan);

$dikirim = 0;
$diproses = 0;
$selesai = 0;

foreach($dataLaporan as $row){

    if($row['status'] == 'Dikirim'){
        $dikirim++;
    }

    if($row['status'] == 'Diproses'){
        $diproses++;
    }

    if($row['status'] == 'Selesai'){
        $selesai++;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Peta Pelanggaran</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link rel="stylesheet"
href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>
body{
    background:#f4f6f9;
}

.page-card{
    border:none;
    border-radius:25px;
    box-shadow:0 8px 25px rgba(0,0,0,.08);
}

.info-card{
    border:none;
    border-radius:20px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}

#map{
    height:700px;
    border-radius:20px;
}

.legend-dot{
    width:16px;
    height:16px;
    border-radius:50%;
}

.legend-item{
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:12px;
}

.stat-box{
    border-radius:18px;
    padding:20px;
    color:white;
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
                    <i class="bi bi-map-fill text-success"></i>
                    Peta Pelanggaran
                </h2>

                <p class="text-muted mb-0">
                    Monitoring lokasi parkir liar masyarakat
                </p>

            </div>

            <a href="dashboard.php"
               class="btn btn-outline-primary rounded-pill">

                <i class="bi bi-arrow-left"></i>
                Dashboard
            </a>

        </div>

        <div class="row g-4">

            <!-- MAP -->
            <div class="col-lg-9">

                <div class="card info-card p-3">

                    <div id="map"></div>

                </div>

            </div>

            <!-- SIDEBAR -->
            <div class="col-lg-3">

                <div class="card info-card p-4 mb-4">

                    <h5 class="fw-bold mb-4">
                        Keterangan Status
                    </h5>

                    <div class="legend-item">
                        <div class="legend-dot bg-secondary"></div>
                        Dikirim
                    </div>

                    <div class="legend-item">
                        <div class="legend-dot bg-info"></div>
                        Diverifikasi
                    </div>

                    <div class="legend-item">
                        <div class="legend-dot bg-warning"></div>
                        Diproses
                    </div>

                    <div class="legend-item">
                        <div class="legend-dot bg-primary"></div>
                        Ditindak
                    </div>

                    <div class="legend-item">
                        <div class="legend-dot bg-success"></div>
                        Selesai
                    </div>

                </div>

                <div class="card info-card p-4">

                    <h5 class="fw-bold mb-4">
                        Statistik
                    </h5>

                    <div class="stat-box bg-primary mb-3">
                        <small>Total Laporan</small>
                        <h2><?= $total ?></h2>
                    </div>

                    <div class="stat-box bg-secondary mb-3">
                        <small>Dikirim</small>
                        <h2><?= $dikirim ?></h2>
                    </div>

                    <div class="stat-box bg-warning mb-3">
                        <small>Diproses</small>
                        <h2><?= $diproses ?></h2>
                    </div>

                    <div class="stat-box bg-success">
                        <small>Selesai</small>
                        <h2><?= $selesai ?></h2>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

let map = L.map('map')
.setView([-6.7320,108.5523],13);

// tile
L.tileLayer(
'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
{
    attribution:
    '&copy; OpenStreetMap'
}).addTo(map);

// data dari php
let laporan =
<?= json_encode($dataLaporan); ?>;

// warna marker
function getColor(status){

    switch(status){

        case 'Dikirim':
            return 'gray';

        case 'Diverifikasi':
            return '#0dcaf0';

        case 'Diproses':
            return '#ffc107';

        case 'Ditindak':
            return '#0d6efd';

        case 'Selesai':
            return '#198754';

        default:
            return 'red';
    }
}

// generate marker
laporan.forEach(item => {

    let marker = L.circleMarker(
        [
            item.latitude,
            item.longitude
        ],
        {
            radius:10,
            fillColor:getColor(item.status),
            color:'#fff',
            weight:2,
            opacity:1,
            fillOpacity:.9
        }
    ).addTo(map);

    marker.bindPopup(`
        <div style="width:250px">

            <img
            src="../assets/uploads/laporan/${item.foto}"
            style="
            width:100%;
            border-radius:12px;
            margin-bottom:10px">

            <h6>${item.alamat_lokasi ?? '-'}</h6>

            <p style="font-size:13px">
            ${item.deskripsi}
            </p>

            <small>
            Pelapor:
            <strong>${item.nama}</strong>
            </small>

            <br><br>

            <span class="badge bg-primary">
            ${item.status}
            </span>

            <a
            href="detail_laporan.php?id=${item.id_laporan}"
            class="btn btn-sm btn-primary w-100 mt-2">

                Detail
            </a>

        </div>
    `);

});

// auto fit marker
if(laporan.length > 0){

    let bounds = laporan.map(item => [
        item.latitude,
        item.longitude
    ]);

    map.fitBounds(bounds);
}
</script>

</body>
</html>