<?php
session_start();
include 'config/koneksi.php';

if (isset($_POST['register'])) {

    $nama = mysqli_real_escape_string($conn,$_POST['nama']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $password = md5($_POST['password']);

    $cek = mysqli_query($conn,
        "SELECT * FROM users WHERE email='$email'"
    );

    if(mysqli_num_rows($cek)>0){

        echo "<script>
                alert('Email sudah digunakan');
              </script>";

    }else{

        mysqli_query($conn,
            "INSERT INTO users
            (nama_lengkap,email,password,role)
            VALUES
            ('$nama','$email','$password','user')
        ");

        echo "<script>
                alert('Registrasi berhasil');
                window.location='login.php';
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Register</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
body{
    min-height:100vh;
    background:linear-gradient(135deg,#198754,#20c997);
    display:flex;
    align-items:center;
    justify-content:center;
}

.register-card{
    border:none;
    border-radius:25px;
    overflow:hidden;
    box-shadow:0 10px 35px rgba(0,0,0,.2);
}

.left-side{
    background:linear-gradient(180deg,#198754,#20c997);
    color:white;
    padding:50px;
}

.form-control{
    border-radius:12px;
    padding:12px;
}

.btn-register{
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

<div class="card register-card">

<div class="row g-0">

<div class="col-md-6 left-side d-flex align-items-center">

<div class="text-center">
<i class="bi bi-person-plus-fill logo-icon"></i>

<h2 class="fw-bold mt-3">
Buat Akun Baru
</h2>

<p>
Gabung untuk membantu melaporkan
parkir liar di lingkungan sekitar.
</p>
</div>

</div>

<div class="col-md-6 bg-white">

<div class="p-5">

<div class="text-center mb-4">
<h2 class="fw-bold">
Register
</h2>

<p class="text-muted">
Silakan isi data diri Anda
</p>
</div>

<form method="POST">

<div class="mb-3">
<label class="form-label">
Nama Lengkap
</label>

<div class="input-group">
<span class="input-group-text">
<i class="bi bi-person"></i>
</span>

<input
type="text"
name="nama"
class="form-control"
placeholder="Nama lengkap"
required>
</div>
</div>

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
name="register"
class="btn btn-success w-100 btn-register">

<i class="bi bi-person-check-fill"></i>
Daftar
</button>

</form>

<div class="text-center mt-4">

Sudah punya akun?

<a href="login.php"
class="text-decoration-none fw-semibold">

Login
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