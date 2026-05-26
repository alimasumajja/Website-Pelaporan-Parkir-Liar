<?php
$currentPage =
basename($_SERVER['PHP_SELF']);
?>

<style>
.sidebar-user{
    width:280px;
    min-height:100vh;

    background:
    linear-gradient(
    180deg,
    #0d6efd,
    #2563eb
    );

    padding:25px 18px;

    position:sticky;
    top:0;
}

.sidebar-brand{
    color:white;
    font-size:24px;
    font-weight:700;
    margin-bottom:35px;
}

.sidebar-menu{
    text-decoration:none;
    color:white;
    padding:14px 18px;
    display:flex;
    align-items:center;
    gap:12px;
    border-radius:18px;
    margin-bottom:12px;
    transition:.3s;
    font-weight:500;
}

.sidebar-menu:hover{
    background:
    rgba(255,255,255,.15);

    color:white;
}

.sidebar-menu.active{
    background:white;
    color:#0d6efd;
    font-weight:700;
}

.sidebar-menu i{
    font-size:18px;
}

.user-box{
    background:
    rgba(255,255,255,.15);

    border-radius:20px;
    padding:15px;
    margin-bottom:30px;
    color:white;
}

.user-box h6{
    margin:0;
    font-weight:600;
}

.user-box small{
    opacity:.8;
}
</style>

<div class="sidebar-user">

    <!-- BRAND -->
    <div class="sidebar-brand">

        <i class="bi bi-car-front-fill"></i>
        ParkirLiar

    </div>

    <!-- USER INFO -->
    <div class="user-box">

        <small>Login sebagai</small>

        <h6>
            <?= $_SESSION['nama']; ?>
        </h6>

    </div>

    <!-- MENU -->

    <a
    href="dashboard.php"
    class="sidebar-menu
    <?= $currentPage ==
    'dashboard.php'
    ? 'active' : '' ?>">

        <i class="bi bi-house-door-fill"></i>
        Dashboard

    </a>

    <a
    href="lapor.php"
    class="sidebar-menu
    <?= $currentPage ==
    'lapor.php'
    ? 'active' : '' ?>">

        <i class="bi bi-plus-circle-fill"></i>
        Buat Laporan

    </a>

    <a
    href="riwayat.php"
    class="sidebar-menu
    <?= $currentPage ==
    'riwayat.php'
    ? 'active' : '' ?>">

        <i class="bi bi-clock-history"></i>
        Riwayat Laporan

    </a>

    <a
    href="peta.php"
    class="sidebar-menu
    <?= $currentPage ==
    'tracking.php'
    ? 'active' : '' ?>">

        <i class="bi bi-geo-alt-fill"></i>
        Tracking Progress

    </a>

    <hr class="text-white">

    <a
    href="../logout.php"
    class="sidebar-menu">

        <i class="bi bi-box-arrow-right"></i>
        Logout

    </a>

</div>