<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nama = $_SESSION['nama'] ?? 'User';
$role = $_SESSION['role'] ?? 'user';
?>

<style>
.navbar-custom{
    background: rgba(255,255,255,.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 14px 22px;
    box-shadow: 0 4px 20px rgba(0,0,0,.08);
}

.brand-logo{
    width:45px;
    height:45px;
    border-radius:12px;
    background:linear-gradient(135deg,#0d6efd,#2563eb);
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:20px;
}

.user-avatar{
    width:45px;
    height:45px;
    border-radius:50%;
    background:linear-gradient(135deg,#0d6efd,#4f46e5);
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
    font-size:18px;
}

.dropdown-menu{
    border:none;
    border-radius:18px;
    box-shadow:0 10px 25px rgba(0,0,0,.1);
    overflow:hidden;
}

.dropdown-item{
    padding:12px 18px;
}

.dropdown-item:hover{
    background:#f3f6ff;
}
</style>

<nav class="navbar navbar-expand-lg navbar-custom mb-4">

    <div class="container-fluid">

        <!-- LOGO -->
        <a class="navbar-brand d-flex align-items-center gap-3"
           href="#">

            <div class="brand-logo">
                <i class="bi bi-car-front-fill"></i>
            </div>

            <div>
                <h5 class="mb-0 fw-bold">
                    Sistem Pelaporan Parkir Liar
                </h5>

                <small class="text-muted">
                    Monitoring Laporan Masyarakat
                </small>
            </div>
        </a>

        <!-- TOGGLE MOBILE -->
        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarContent">

            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- MENU -->
        <div class="collapse navbar-collapse justify-content-end"
             id="navbarContent">

            <div class="dropdown">

                <button class="btn border-0 d-flex align-items-center gap-3"
                        data-bs-toggle="dropdown">

                    <div class="text-end d-none d-md-block">

                        <h6 class="mb-0 fw-bold">
                            <?= htmlspecialchars($nama) ?>
                        </h6>

                        <small class="text-muted text-capitalize">
                            <?= htmlspecialchars($role) ?>
                        </small>
                    </div>

                    <div class="user-avatar">
                        <?= strtoupper(substr($nama,0,1)); ?>
                    </div>

                </button>

                <ul class="dropdown-menu dropdown-menu-end p-2">

                    <li>
                        <a class="dropdown-item rounded-3"
                           href="profil.php">

                            <i class="bi bi-person me-2"></i>
                            Profil Saya
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item rounded-3"
                           href="#">

                            <i class="bi bi-gear me-2"></i>
                            Pengaturan
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item rounded-3 text-danger"
                           href="../logout.php">

                            <i class="bi bi-box-arrow-right me-2"></i>
                            Logout
                        </a>
                    </li>

                </ul>

            </div>

        </div>
    </div>
</nav>