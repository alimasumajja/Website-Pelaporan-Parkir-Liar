<?php
session_start();
include 'config/koneksi.php';

if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);

    $query = mysqli_query($conn,
        "SELECT * FROM users
        WHERE email='$email'
        AND password='$password'"
    );

    if (mysqli_num_rows($query) > 0) {

        $data = mysqli_fetch_assoc($query);

        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['nama'] = $data['nama_lengkap'];
        $_SESSION['role'] = $data['role'];

        if ($data['role'] == 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: user/dashboard.php");
        }

    } else {
        echo "<script>alert('Email atau Password salah!')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login - Sistem Pelaporan Parkir Liar</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
body{
    min-height:100vh;
    background: linear-gradient(135deg,#0d6efd,#4f46e5);
    display:flex;
    align-items:center;
    justify-content:center;
}

.login-card{
    border:none;
    border-radius:25px;
    overflow:hidden;
    box-shadow:0 10px 35px rgba(0,0,0,.2);
}

.left-side{
    background:linear-gradient(180deg,#0d6efd,#3b82f6);
    color:white;
    padding:50px;
}

.form-control{
    border-radius:12px;
    padding:12px;
}

.btn-login{
    border-radius:12px;
    padding:12px;
    font-weight:600;
}

.logo-icon{
    font-size:60px;
}

@media(max-width:768px){
    .left-side{
        display:none;
    }
}
</style>
</head>

<body>

<div class="container">
    <div class="row justify-content-center">

        <div class="col-lg-10">

            <div class="card login-card">

                <div class="row g-0">

                    <div class="col-md-6 left-side d-flex flex-column justify-content-center">

                        <div class="text-center">
                            <i class="bi bi-car-front-fill logo-icon"></i>

                            <h2 class="fw-bold mt-3">
                                Sistem Pelaporan Parkir Liar
                            </h2>

                            <p class="mt-3">
                                Laporkan parkir liar dengan mudah,
                                cepat, dan transparan.
                            </p>
                        </div>

                    </div>

                    <div class="col-md-6 bg-white">

                        <div class="p-5">

                            <div class="text-center mb-4">
                                <h2 class="fw-bold">
                                    Login
                                </h2>

                                <p class="text-muted">
                                    Masuk ke akun Anda
                                </p>
                            </div>

                            <form method="POST">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Email
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="bi bi-envelope"></i>
                                        </span>

                                        <input
                                            type="email"
                                            name="email"
                                            class="form-control"
                                            placeholder="Masukkan email"
                                            required>
                                    </div>
                                </div>

                                <div class="mb-4">

                                    <label class="form-label">
                                        Password
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="bi bi-lock"></i>
                                        </span>

                                        <input
                                            type="password"
                                            name="password"
                                            class="form-control"
                                            placeholder="Masukkan password"
                                            required>
                                    </div>
                                </div>

                                <button
                                    type="submit"
                                    name="login"
                                    class="btn btn-primary w-100 btn-login">

                                    <i class="bi bi-box-arrow-in-right"></i>
                                    Login
                                </button>

                            </form>

                            <div class="text-center mt-4">

                                Belum punya akun?

                                <a href="register.php"
                                   class="text-decoration-none fw-semibold">
                                    Register
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

</body>
</html>