<?php
include '../config/koneksi.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama     = $_POST['nama'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $role     = $_POST['role'];

    // Cek email sudah ada
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        $error = 'Email sudah terdaftar!';
    } else {
        $hash   = password_hash($password, PASSWORD_DEFAULT);
        $insert = mysqli_query($koneksi, "INSERT INTO users (nama, email, password, role) 
                                          VALUES ('$nama', '$email', '$hash', '$role')");
        if ($insert) {
            $success = 'User berhasil ditambahkan!';
        } else {
            $error = 'Gagal menambahkan user!';
        }
    }
}
?>

<div class="container">
    <div class="page-header">
        <h2 class="page-title">Tambah User</h2>
        <a href="data_user.php" class="btn-secondary">← Kembali ke Data User</a>
    </div>

    <div class="form-box" style="max-width:500px;">

        <?php if ($error): ?>
            <div class="alert-error"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Masukkan email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" required>
                    <option value="member">Member</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn-full">Tambah User</button>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>