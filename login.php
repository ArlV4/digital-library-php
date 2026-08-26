<?php
include 'config/koneksi.php';
include 'includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $query  = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");
    $user   = mysqli_fetch_assoc($query);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama']    = $user['nama'];
        $_SESSION['role']    = $user['role'];

        if ($user['role'] == 'admin') {
            header('Location: admin/dashboard.php');
        } else {
            header('Location: index.php');
        }
        exit;
    } else {
        $error = 'Email atau password salah!';
    }
}
?>

<div class="form-center">
    <div class="form-box">
        <h2>Login</h2>

        <?php if ($error): ?>
            <div class="alert-error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Masukkan email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn-full">Login</button>
        </form>

        <p class="form-link">Belum punya akun? <a href="register.php">Daftar disini</a></p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>