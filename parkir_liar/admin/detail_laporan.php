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

if (!isset($_GET['id'])) {
    header("Location: laporan.php");
    exit;
}

$id_laporan = (int) $_GET['id'];


// UPDATE STATUS


if (isset($_POST['update_status'])) {

    $statusBaru = mysqli_real_escape_string(
        $conn,
        $_POST['status']
    );

    $catatan =
    "Status diperbarui menjadi $statusBaru";

    mysqli_query($conn,
        "UPDATE laporan
        SET status='$statusBaru'
        WHERE id_laporan='$id_laporan'"
    );

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

    header(
        "Location: detail_laporan.php?id=$id_laporan&success=1"
    );
    exit;
}

// GET DATA LAPORAN

$query = mysqli_query($conn,
    "SELECT laporan.*,
    users.nama_lengkap,
    users.email
    FROM laporan
    JOIN users
    ON users.id_user = laporan.id_user
    WHERE laporan.id_laporan='$id_laporan'"
);

if (mysqli_num_rows($query) == 0) {
    header("Location: laporan.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

$queryStatus = mysqli_query($conn,
    "SELECT *
    FROM status_laporan
    WHERE id_laporan='$id_laporan'
    ORDER BY created_at ASC"
);

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

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Detail Laporan</title>

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

.detail-card{
    border:none;
    border-radius:20px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}

.report-img{
    width:100%;
    height:450px;
    object-fit:cover;
    border-radius:20px;
}

.history-item{
    border-left:4px solid #0d6efd;
    padding-left:20px;
    margin-bottom:25px;
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
                    <i class="bi bi-file-earmark-text-fill text-primary"></i>
                    Detail Laporan
                </h2>

                <p class="text-muted mb-0">
                    Informasi lengkap laporan parkir liar
                </p>

            </div>

            <a href="laporan.php"
               class="btn btn-outline-primary rounded-pill">

                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>

        </div>

        <?php if(isset($_GET['success'])): ?>

            <div class="alert alert-success rounded-4">
                Status berhasil diperbarui.
            </div>

        <?php endif; ?>

        <div class="row g-4">

            <!-- FOTO -->
            <div class="col-lg-5">

                <div class="card detail-card p-3">

                    <img
                    src="../assets/uploads/laporan/<?= $data['foto']; ?>"
                    class="report-img">

                </div>

            </div>

            <!-- DETAIL -->
            <div class="col-lg-7">

                <div class="card detail-card p-4 h-100">

                    <div class="d-flex justify-content-between mb-4">

                        <h3 class="fw-bold">
                            <?= htmlspecialchars(
                                $data['alamat_lokasi']
                                ?: 'Lokasi Tidak Diketahui'
                            ); ?>
                        </h3>

                        <span class="badge bg-<?= $badge ?> p-3">
                            <?= $data['status']; ?>
                        </span>

                    </div>

                    <div class="row mb-4">

                        <div class="col-md-6">
                            <strong>Pelapor</strong>
                            <p>
                                <?= htmlspecialchars(
                                    $data['nama_lengkap']
                                ); ?>
                            </p>
                        </div>

                        <div class="col-md-6">
                            <strong>Email</strong>
                            <p>
                                <?= htmlspecialchars(
                                    $data['email']
                                ); ?>
                            </p>
                        </div>

                    </div>

                    <div class="mb-4">

                        <strong>Deskripsi</strong>

                        <p class="text-muted mt-2">
                            <?= htmlspecialchars(
                                $data['deskripsi']
                            ); ?>
                        </p>

                    </div>

                    <div class="row mb-4">

                        <div class="col-md-6">

                            <strong>Latitude</strong>
                            <p><?= $data['latitude']; ?></p>

                        </div>

                        <div class="col-md-6">

                            <strong>Longitude</strong>
                            <p><?= $data['longitude']; ?></p>

                        </div>

                    </div>

                    <div class="mb-4">

                        <a
                        href="https://www.google.com/maps?q=<?= $data['latitude']; ?>,<?= $data['longitude']; ?>"
                        target="_blank"
                        class="btn btn-success rounded-pill">

                            <i class="bi bi-map-fill"></i>
                            Buka di Google Maps
                        </a>

                    </div>

                    <hr>

                    <h5 class="fw-bold mb-3">
                        Update Status
                    </h5>

                    <form method="POST" action="update_status.php">

                        <div class="row">

                            <div class="col-md-9">

                                <select
                                name="status"
                                class="form-select">

                                    <option>Dikirim</option>
                                    <option>Diverifikasi</option>
                                    <option>Diproses</option>
                                    <option>Ditindak</option>
                                    <option>Selesai</option>

                                </select>

                            </div>
                            <input
                                type="hidden"
                                name="id_laporan"
                                value="<?= $id_laporan; ?>">

                            <input
                                type="hidden"
                                name="from_detail"
                                value="1">

                            <div class="col-md-3">

                                <button
                                type="submit"
                                name="update_status"
                                class="btn btn-primary w-100">

                                    Simpan
                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <!-- HISTORY -->
        <div class="card detail-card p-4 mt-4">

            <h4 class="fw-bold mb-4">
                Riwayat Progress
            </h4>

            <?php while($status =
            mysqli_fetch_assoc($queryStatus)): ?>

                <div class="history-item">

                    <h5 class="fw-bold">
                        <?= $status['status']; ?>
                    </h5>

                    <p class="text-muted">
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