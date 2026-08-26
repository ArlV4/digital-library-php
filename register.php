<?php
include 'config/koneksi.php';
include 'includes/header.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama     = $_POST['nama'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    // Cek password sama
    if ($password !== $konfirmasi) {
        $error = 'Password dan konfirmasi password tidak sama!';
    } else {
        // Cek email sudah ada atau belum
        $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");

        if (mysqli_num_rows($cek) > 0) {
            $error = 'Email sudah terdaftar, gunakan email lain!';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = mysqli_query($koneksi, "INSERT INTO users (nama, email, password, role) 
                                             VALUES ('$nama', '$email', '$hash', 'member')");
            if ($insert) {
                $success = 'Akun berhasil dibuat! Silakan login.';
            } else {
                $error = 'Gagal membuat akun, coba lagi!';
            }
        }
    }
}
?>

<div class="form-center">
    <div class="form-box">
        <h2>Daftar Akun</h2>

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
                <label>Konfirmasi Password</label>
                <input type="password" name="konfirmasi" placeholder="Ulangi password" required>
            </div>
            <button type="submit" class="btn-full">Daftar</button>
        </form>

        <p class="form-link">Sudah punya akun? <a href="login.php">Login disini</a></p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>