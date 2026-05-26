<?php
session_start();
include __DIR__.'/../config/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

$query = mysqli_query($conn,
    "SELECT *FROM laporan WHERE id_user='$id_user'ORDER BY created_at DESC");

$dataLaporan = [];

while($row = mysqli_fetch_assoc($query)){
    $dataLaporan[] = $row;
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
    box-shadow:0 8px 30px rgba(0,0,0,.08);
}

#map{
    width:100%;
    height:650px;
    border-radius:20px;
}

.info-card{
    border:none;
    border-radius:20px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}

.legend-item{
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:10px;
}

.dot{
    width:18px;
    height:18px;
    border-radius:50%;
}
</style>
</head>

<body>

<div class="container-fluid py-4">

    <?php include '../components/header.php'; ?>

    <div class="card page-card p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="fw-bold mb-1">
                    <i class="bi bi-map-fill text-success"></i>
                    Peta Pelanggaran
                </h2>

                <p class="text-muted mb-0">
                    Lokasi laporan parkir liar Anda
                </p>

            </div>

            <a href="dashboard.php"
               class="btn btn-outline-primary rounded-pill">

                <i class="bi bi-arrow-left"></i>
                Dashboard
            </a>

        </div>

        <div class="row g-4">

            <!-- PETA -->
            <div class="col-lg-9">

                <div class="card info-card p-3">
                    <div id="map"></div>
                </div>

            </div>

            <!-- SIDEBAR INFO -->
            <div class="col-lg-3">

                <div class="card info-card p-4">

                    <h5 class="fw-bold mb-4">
                        Informasi Marker
                    </h5>

                    <div class="legend-item">
                        <div class="dot bg-secondary"></div>
                        <span>Dikirim</span>
                    </div>

                    <div class="legend-item">
                        <div class="dot bg-info"></div>
                        <span>Diverifikasi</span>
                    </div>

                    <div class="legend-item">
                        <div class="dot bg-warning"></div>
                        <span>Diproses</span>
                    </div>

                    <div class="legend-item">
                        <div class="dot bg-primary"></div>
                        <span>Ditindak</span>
                    </div>

                    <div class="legend-item">
                        <div class="dot bg-success"></div>
                        <span>Selesai</span>
                    </div>

                    <hr>

                    <h6 class="fw-bold">
                        Total Laporan
                    </h6>

                    <h2 class="text-primary">
                        <?= count($dataLaporan); ?>
                    </h2>

                    <small class="text-muted">
                        Total titik laporan Anda
                    </small>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// default center (Cirebon)
let map = L.map('map')
.setView([-6.7320,108.5523], 13);

// OpenStreetMap
L.tileLayer(
'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
{
    attribution:
    '&copy; OpenStreetMap contributors'
}).addTo(map);

// data php → js
let laporan =
<?= json_encode($dataLaporan); ?>;

// icon warna
function markerColor(status){

    switch(status){

        case 'Dikirim':
            return 'gray';

        case 'Diverifikasi':
            return 'lightblue';

        case 'Diproses':
            return 'orange';

        case 'Ditindak':
            return 'blue';

        case 'Selesai':
            return 'green';

        default:
            return 'red';
    }
}

// marker
laporan.forEach(item => {

    let marker = L.circleMarker(
        [
            item.latitude,
            item.longitude
        ],
        {
            radius:10,
            fillColor:
            markerColor(item.status),

            color:'#fff',
            weight:2,
            opacity:1,
            fillOpacity:.9
        }
    ).addTo(map);

    marker.bindPopup(`
        <div style="width:230px">

            <img
            src="../assets/uploads/laporan/${item.foto}"
            style="
            width:100%;
            border-radius:10px;
            margin-bottom:10px;">

            <h6>${item.alamat_lokasi ?? 'Lokasi Tidak Diketahui'}</h6>

            <p style="font-size:13px">
            ${item.deskripsi}
            </p>

            <span class="badge bg-primary">
            ${item.status}
            </span>

        </div>
    `);

});

// auto fit bounds
if(laporan.length > 0){

    let bounds = laporan.map(x => [
        x.latitude,
        x.longitude
    ]);

    map.fitBounds(bounds);
}
</script>

</body>
</html>