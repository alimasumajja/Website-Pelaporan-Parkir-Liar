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
    "SELECT * FROM users
    ORDER BY id_user DESC"
);

$totalUser = mysqli_num_rows(
    mysqli_query($conn,
    "SELECT * FROM users
    WHERE role='user'")
);

$totalAdmin = mysqli_num_rows(
    mysqli_query($conn,
    "SELECT * FROM users
    WHERE role='admin'")
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Data User</title>

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
    border-radius:24px;
    box-shadow:0 8px 25px rgba(0,0,0,.08);
}

.stat-card{
    border-radius:20px;
    color:white;
    padding:25px;
}

.search-box{
    border-radius:15px;
}

.badge-role{
    padding:10px 16px;
    border-radius:999px;
    color:black;
    font-weight:600;
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
            <i class="bi bi-people-fill text-primary"></i>
            Data Users
        </h2>

        <p class="text-muted mb-0">
            Kelola pengguna sistem
        </p>

    </div>

    <div class="d-flex gap-2">

        <!-- tombol kembali -->
        <a
        href="dashboard.php"
        class="btn btn-outline-secondary rounded-pill">

            <i class="bi bi-arrow-left"></i>
            Kembali

        </a>

        <!-- tombol tambah -->
        <button
        class="btn btn-primary rounded-pill"
        data-bs-toggle="modal"
        data-bs-target="#modalTambah">

            <i class="bi bi-plus-circle"></i>
            Tambah User

        </button>

    </div>

</div>

<div class="row g-4 mb-4">

    <div class="col-md-6">

        <div class="stat-card bg-primary">
            <small>Total User</small>
            <h1><?= $totalUser ?></h1>
        </div>

    </div>

    <div class="col-md-6">

        <div class="stat-card bg-success">
            <small>Total Admin</small>
            <h1><?= $totalAdmin ?></h1>
        </div>

    </div>

</div>

<div class="mb-4">

    <input
    type="text"
    id="searchInput"
    class="form-control search-box"
    placeholder="Cari nama atau email">

</div>

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-primary">
<tr>
<th>Nama</th>
<th>Email</th>
<th>Role</th>
<th>Aksi</th>
</tr>
</thead>

<tbody id="tableUser">

<?php while($row = mysqli_fetch_assoc($query)): ?>

<tr class="user-row">

<td><?= htmlspecialchars($row['nama_lengkap']) ?></td>

<td><?= htmlspecialchars($row['email']) ?></td>

<td>

    <span class="badge
    bg-<?= $row['role']=='admin'
    ? 'success':'primary' ?>
    badge-role">

        <?= ucfirst($row['role']) ?>

    </span>

</td>

<td>

<div class="d-flex gap-2">

<button
class="btn btn-warning"
data-bs-toggle="modal"
data-bs-target="#edit<?= $row['id_user'] ?>">

<i class="bi bi-pencil-fill"></i>

</button>

<a
href="hapus_user.php?id=<?= $row['id_user'] ?>"
class="btn btn-danger"
onclick="return confirm(
'Hapus user ini?')">

<i class="bi bi-trash-fill"></i>
</a>

</div>

</td>

</tr>

<!-- MODAL EDIT -->
<div
class="modal fade"
id="edit<?= $row['id_user'] ?>">

<div class="modal-dialog">

<div class="modal-content">

<form
method="POST"
action="update_user.php">

<div class="modal-header">
<h5>Edit User</h5>
</div>

<div class="modal-body">

<input
type="hidden"
name="id_user"
value="<?= $row['id_user'] ?>">

<label>Nama</label>
<input
type="text"
name="nama_lengkap"
class="form-control mb-3"
value="<?= $row['nama_lengkap'] ?>"
required>

<label>Email</label>
<input
type="email"
name="email"
class="form-control mb-3"
value="<?= $row['email'] ?>"
required>

<label>Role</label>
<select
name="role"
class="form-select">

<option
value="user"
<?= $row['role']=='user'
?'selected':'' ?>>
User
</option>

<option
value="admin"
<?= $row['role']=='admin'
?'selected':'' ?>>
Admin
</option>

</select>

</div>

<div class="modal-footer">

<button
class="btn btn-secondary"
data-bs-dismiss="modal">

Batal

</button>

<button
class="btn btn-primary">

Update

</button>

</div>

</form>

</div>
</div>
</div>

<?php endwhile; ?>

</tbody>

</table>

</div>

</div>

</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah">

<div class="modal-dialog">

<div class="modal-content">

<form
method="POST"
action="tambah_user.php">

<div class="modal-header">
<h5>Tambah User</h5>
</div>

<div class="modal-body">

<input
type="text"
name="nama_lengkap"
class="form-control mb-3"
placeholder="Nama Lengkap"
required>

<input
type="email"
name="email"
class="form-control mb-3"
placeholder="Email"
required>

<input
type="password"
name="password"
class="form-control mb-3"
placeholder="Password"
required>

<select
name="role"
class="form-select">

<option value="user">
User
</option>

<option value="admin">
Admin
</option>

</select>

</div>

<div class="modal-footer">

<button
class="btn btn-secondary"
data-bs-dismiss="modal">

Batal

</button>

<button
class="btn btn-primary">

Simpan

</button>

</div>

</form>

</div>
</div>
</div>

<script>
document.getElementById(
'searchInput'
).addEventListener(
'keyup',
function(){

let val =
this.value.toLowerCase();

document
.querySelectorAll('.user-row')
.forEach(row=>{

row.style.display =
row.innerText
.toLowerCase()
.includes(val)
? ''
: 'none';

});
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>