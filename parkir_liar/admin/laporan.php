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
| UPDATE STATUS
|--------------------------------------------------------------------------
*/

if (isset($_POST['update_status'])) {

    $id_laporan = (int) $_POST['id_laporan'];

    $statusBaru = mysqli_real_escape_string(
        $conn,
        $_POST['status']
    );

    $catatan = mysqli_real_escape_string(
        $conn,
        "Status diubah menjadi $statusBaru"
    );

    mysqli_query($conn,
        "UPDATE laporan
        SET status='$statusBaru'
        WHERE id_laporan='$id_laporan'"
    );

    mysqli_query($conn,
        "INSERT INTO status_laporan(
            id_laporan,
            status,
            catatan
        )
        VALUES(
            '$id_laporan',
            '$statusBaru',
            '$catatan'
        )"
    );

    header("Location: laporan.php?success=1");
    exit;
}



// GET LAPORAN


$query = mysqli_query($conn,
    "SELECT laporan.*,
    users.nama_lengkap
    FROM laporan
    JOIN users
    ON users.id_user = laporan.id_user
    ORDER BY laporan.created_at DESC"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Data Laporan</title>

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
    width:75px;
    height:75px;
    object-fit:cover;
    border-radius:14px;
}

.search-box{
    border-radius:15px;
    padding:12px;
}

.table td{
    vertical-align:middle;
}

.badge-status{
    border-radius:999px;
    padding:10px 15px;
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
                    Data Laporan
                </h2>

                <p class="text-muted mb-0">
                    Verifikasi dan update status laporan masyarakat
                </p>

            </div>

            <a href="dashboard.php"
               class="btn btn-outline-primary rounded-pill">

                <i class="bi bi-arrow-left"></i>
                Dashboard
            </a>

        </div>

        <?php if(isset($_GET['success'])): ?>

            <div class="alert alert-success rounded-4">
                Status laporan berhasil diperbarui.
            </div>

        <?php endif; ?>

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
                        placeholder="Cari pelapor, lokasi, status...">

                </div>

            </div>

        </div>

        <!-- TABLE -->
        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-primary">

                    <tr>
                        <th>Foto</th>
                        <th>Pelapor</th>
                        <th>Lokasi</th>
                        <th>GPS</th>
                        <th>Status</th>
                        <th>Update</th>
                    </tr>

                </thead>

                <tbody id="tableLaporan">

                <?php while($row =
                mysqli_fetch_assoc($query)): ?>

                    <?php

                    switch($row['status']){

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

                    <tr class="laporan-row">

                        <td>
                            <img
                            src="../assets/uploads/laporan/<?= $row['foto']; ?>"
                            class="report-img">
                        </td>

                        <td>
                            <strong>
                                <?= htmlspecialchars($row['nama_lengkap']); ?>
                            </strong>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $row['alamat_lokasi']
                                ?: '-'
                            ); ?>
                        </td>

                        <td>
                            <small>
                                <?= $row['latitude']; ?>,
                                <?= $row['longitude']; ?>
                            </small>
                        </td>

                        <td>

                            <span class="badge bg-<?= $badge ?>
                            badge-status">

                                <?= $row['status']; ?>

                            </span>

                        </td>

                        <td>
                            <a href="detail_laporan.php?id=<?= $row['id_laporan']; ?>"
                            class="btn btn-info">

                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <form method="POST" action="update_status.php">

                                <input
                                type="hidden"
                                name="id_laporan"
                                value="<?= $row['id_laporan']; ?>">

                                <div class="d-flex gap-2">

                                    <select
                                    name="status"
                                    class="form-select">

                                        <option
                                        value="Dikirim"
                                        <?= $row['status']=='Dikirim'
                                        ? 'selected':'' ?>>
                                            Dikirim
                                        </option>

                                        <option
                                        value="Diverifikasi"
                                        <?= $row['status']=='Diverifikasi'
                                        ? 'selected':'' ?>>
                                            Diverifikasi
                                        </option>

                                        <option
                                        value="Diproses"
                                        <?= $row['status']=='Diproses'
                                        ? 'selected':'' ?>>
                                            Diproses
                                        </option>

                                        <option
                                        value="Ditindak"
                                        <?= $row['status']=='Ditindak'
                                        ? 'selected':'' ?>>
                                            Ditindak
                                        </option>

                                        <option
                                        value="Selesai"
                                        <?= $row['status']=='Selesai'
                                        ? 'selected':'' ?>>
                                            Selesai
                                        </option>

                                    </select>

                                    <button
                                    type="submit"
                                    name="update_status"
                                    class="btn btn-primary">

                                        <i class="bi bi-check-lg"></i>
                                    </button>

                                </div>

                            </form>

                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>
// realtime search
document
.getElementById('searchInput')
.addEventListener('keyup', function(){

    let value =
    this.value.toLowerCase();

    let rows =
    document.querySelectorAll(
        '.laporan-row'
    );

    rows.forEach(row => {

        row.style.display =
        row.innerText
        .toLowerCase()
        .includes(value)
        ? ''
        : 'none';
    });
});
</script>

</body>
</html>