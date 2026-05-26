<?php
session_start();
include __DIR__.'/../config/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

$query = mysqli_query($conn,
    "SELECT * FROM laporan
    WHERE id_user='$id_user'
    ORDER BY created_at DESC"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Riwayat Laporan</title>

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
    box-shadow:0 8px 25px rgba(0,0,0,.08);
}

.report-card{
    border:none;
    border-radius:22px;
    overflow:hidden;
    transition:.3s;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}

.report-card:hover{
    transform:translateY(-5px);
}

.report-image{
    width:100%;
    height:220px;
    object-fit:cover;
}

.badge-status{
    padding:10px 15px;
    border-radius:999px;
    font-size:13px;
}

.search-box{
    border-radius:16px;
    padding:14px;
}
</style>
</head>

<body>

<div class="container py-4">

    <?php include '../components/header.php'; ?>

    <div class="card page-card p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-clock-history text-primary"></i>
                    Riwayat Laporan
                </h2>

                <p class="text-muted mb-0">
                    Daftar laporan parkir liar yang pernah dikirim
                </p>
            </div>

            <a href="dashboard.php"
               class="btn btn-outline-primary rounded-pill">

                <i class="bi bi-arrow-left"></i>
                Dashboard
            </a>

        </div>

        <!-- SEARCH -->
        <div class="row mb-4">

            <div class="col-md-5">

                <div class="input-group">

                    <span class="input-group-text bg-white">
                        <i class="bi bi-search"></i>
                    </span>

                    <input
                        type="text"
                        id="searchInput"
                        class="form-control search-box"
                        placeholder="Cari lokasi / deskripsi laporan...">

                </div>

            </div>

        </div>

        <!-- LIST LAPORAN -->
        <div class="row g-4" id="laporanContainer">

            <?php if(mysqli_num_rows($query) > 0): ?>

                <?php while($data = mysqli_fetch_assoc($query)): ?>

                    <?php
                    switch($data['status']){

                        case 'Dikirim':
                            $badge='secondary';
                            break;

                        case 'Diverifikasi':
                            $badge='info';
                            break;

                        case 'Diproses':
                            $badge='warning';
                            break;

                        case 'Ditindak':
                            $badge='primary';
                            break;

                        case 'Selesai':
                            $badge='success';
                            break;

                        default:
                            $badge='danger';
                    }
                    ?>

                    <div class="col-md-6 col-lg-4 laporan-item">

                        <div class="card report-card h-100">

                            <img
                                src="../assets/uploads/laporan/<?= $data['foto']; ?>"
                                class="report-image"
                                alt="Foto laporan">

                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-center mb-3">

                                    <h5 class="fw-bold mb-0">
                                        <?= htmlspecialchars(
                                            $data['alamat_lokasi']
                                            ?: 'Lokasi Tidak Diketahui'
                                        ); ?>
                                    </h5>

                                    <span class="badge bg-<?= $badge ?> badge-status">
                                        <?= $data['status']; ?>
                                    </span>

                                </div>

                                <p class="text-muted small mb-3">
                                    <?= htmlspecialchars(
                                        substr($data['deskripsi'],0,100)
                                    ); ?>...
                                </p>

                                <div class="small text-muted mb-2">

                                    <i class="bi bi-geo-alt-fill text-danger"></i>

                                    <?= $data['latitude']; ?>,
                                    <?= $data['longitude']; ?>

                                </div>

                                <div class="small text-muted mb-3">

                                    <i class="bi bi-calendar-event"></i>

                                    <?= date(
                                        'd M Y H:i',
                                        strtotime($data['created_at'])
                                    ); ?>

                                </div>

                                <div class="d-grid">

                                    <a href="tracking.php?id=<?= $data['id_laporan']; ?>"
                                       class="btn btn-primary rounded-pill">

                                        <i class="bi bi-eye-fill"></i>
                                        Lihat Progress
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php endwhile; ?>

            <?php else: ?>

                <div class="col-12">

                    <div class="alert alert-warning rounded-4 text-center p-5">

                        <i class="bi bi-folder-x fs-1"></i>

                        <h4 class="mt-3">
                            Belum Ada Laporan
                        </h4>

                        <p>
                            Anda belum pernah mengirim laporan parkir liar.
                        </p>

                        <a href="lapor.php"
                           class="btn btn-danger rounded-pill">

                            Buat Laporan
                        </a>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

<script>
// search realtime
document.getElementById("searchInput")
.addEventListener("keyup", function(){

    let filter =
    this.value.toLowerCase();

    let items =
    document.querySelectorAll(".laporan-item");

    items.forEach(item => {

        let text =
        item.innerText.toLowerCase();

        item.style.display =
        text.includes(filter)
        ? ""
        : "none";
    });
});
</script>

</body>
</html>