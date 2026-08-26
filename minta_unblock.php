<?php
include 'config/koneksi.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user    = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id='$user_id'"));

if ($user['status'] != 'diblokir') {
    header('Location: index.php');
    exit;
}

$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $alasan = $_POST['alasan'];
    mysqli_query($koneksi, "INSERT INTO unblock_request (user_id, alasan, status) 
        VALUES ('$user_id', '$alasan', 'pending')");
    $success = 'Permintaan berhasil dikirim! Tunggu persetujuan admin.';
}
?>

<div class="container">
    <h2 class="page-title">Minta Persetujuan Admin</h2>

    <div class="form-box" style="max-width:500px;">

        <?php if ($success): ?>
            <div class="alert-success"><?= $success ?></div>
        <?php else: ?>
            <p style="margin-bottom:20px; font-size:14px; color:#555;">
                Jelaskan alasan kamu terlambat mengembalikan buku. Admin akan mempertimbangkan permintaanmu.
            </p>

            <form method="POST">
                <div class="form-group">
                    <label>Alasan Keterlambatan</label>
                    <textarea name="alasan" rows="5" 
                        placeholder="Contoh: Saya lupa karena sedang ujian..." required></textarea>
                </div>
                <button type="submit" class="btn-full">📨 Kirim Permintaan</button>
            </form>

            <br>
            <a href="bayar_denda.php" class="btn-secondary" style="display:block; text-align:center;">
                💰 Bayar Denda Saja
            </a>
        <?php endif; ?>

    </div>
</div>

<?php include 'includes/footer.php'; ?>