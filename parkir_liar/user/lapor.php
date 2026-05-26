<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Lapor Parkir Liar</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
body{
    background:#f4f6f9;
}

.page-card{
    border:none;
    border-radius:24px;
    box-shadow:0 8px 30px rgba(0,0,0,.08);
}

.form-card{
    border:none;
    border-radius:20px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}

.form-control,
.form-select{
    border-radius:14px;
    padding:14px;
}

.preview-box{
    border:2px dashed #d1d5db;
    border-radius:20px;
    min-height:250px;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
    background:#fafafa;
}

.preview-box img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.location-box{
    background:#eef4ff;
    border-radius:16px;
    padding:20px;
}

.btn-submit{
    border-radius:14px;
    padding:14px;
    font-weight:600;
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
                    <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                    Lapor Parkir Liar
                </h2>

                <p class="text-muted mb-0">
                    Laporkan area parkir ilegal dengan bukti foto dan lokasi GPS
                </p>
            </div>

            <a href="dashboard.php"
               class="btn btn-outline-primary rounded-pill">

                <i class="bi bi-arrow-left"></i>
                Dashboard
            </a>

        </div>

        <div class="row g-4">

            <!-- PREVIEW FOTO -->
            <div class="col-lg-5">

                <div class="card form-card p-4 h-100">

                    <h5 class="fw-bold mb-3">
                        Foto Lokasi/Kendaraan
                    </h5>

                    <div class="preview-box mb-3">

                        <img id="previewImage"
                             src="https://placehold.co/600x400?text=Preview+Foto"
                             alt="Preview">

                    </div>

                    <input
                        type="file"
                        class="form-control"
                        id="foto"
                        accept="image/*"
                        required>

                    <small class="text-muted">
                        Upload foto kendaraan atau lokasi parkir liar
                    </small>

                </div>

            </div>

            <!-- FORM -->
            <div class="col-lg-7">

                <div class="card form-card p-4">

                    <form action="simpan_laporan.php"
                          method="POST"
                          enctype="multipart/form-data">

                        <h5 class="fw-bold mb-4">
                            Detail Laporan
                        </h5>

                        <!-- FOTO -->
                        <input
                            type="file"
                            hidden
                            id="fotoHidden"
                            name="foto">

                        <!-- DESKRIPSI -->
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Deskripsi Laporan
                            </label>

                            <textarea
                                name="deskripsi"
                                class="form-control"
                                rows="5"
                                placeholder="Contoh: Parkir liar memenuhi trotoar dan mengganggu pejalan kaki..."
                                required></textarea>
                        </div>

                        <!-- ALAMAT -->
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Nama Lokasi / Jalan
                            </label>

                            <input
                                type="text"
                                name="alamat_lokasi"
                                class="form-control"
                                placeholder="Contoh: Jl. Kartini, Cirebon">
                        </div>

                        <!-- GPS -->
                        <div class="location-box mb-4">

                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <h6 class="fw-bold mb-0">
                                    <i class="bi bi-geo-alt-fill text-primary"></i>
                                    Lokasi GPS Otomatis
                                </h6>

                                <button
                                    type="button"
                                    class="btn btn-primary btn-sm rounded-pill"
                                    onclick="getLocation()">

                                    Ambil Lokasi
                                </button>
                            </div>

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Latitude
                                    </label>

                                    <input
                                        type="text"
                                        id="latitude"
                                        name="latitude"
                                        class="form-control"
                                        readonly
                                        required>
                                </div>

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Longitude
                                    </label>

                                    <input
                                        type="text"
                                        id="longitude"
                                        name="longitude"
                                        class="form-control"
                                        readonly
                                        required>
                                </div>

                            </div>

                        </div>

                        <button
                            type="submit"
                            class="btn btn-danger w-100 btn-submit">

                            <i class="bi bi-send-fill"></i>
                            Kirim Laporan
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// preview image
document.getElementById('foto')
.addEventListener('change', function(e){

    const file = e.target.files[0];

    if(file){

        const reader = new FileReader();

        reader.onload = function(event){
            document.getElementById('previewImage')
            .src = event.target.result;
        }

        reader.readAsDataURL(file);

        document.getElementById('fotoHidden')
        .files = e.target.files;
    }
});

// GPS otomatis
function getLocation(){

    if(navigator.geolocation){

        navigator.geolocation.getCurrentPosition(
            function(position){

                document.getElementById('latitude').value =
                position.coords.latitude;

                document.getElementById('longitude').value =
                position.coords.longitude;
            },

            function(){
                alert("Lokasi gagal diambil.");
            }
        );

    }else{
        alert("Browser tidak mendukung GPS.");
    }
}

// auto load GPS
window.onload = getLocation;
</script>

</body>
</html>