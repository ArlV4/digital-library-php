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

// Ambil semua denda belum lunas
$denda_query = mysqli_query($koneksi, "SELECT p.*, b.judul FROM peminjaman p 
    JOIN buku b ON p.buku_id = b.id 
    WHERE p.user_id='$user_id' AND p.status_denda='belum_lunas'");

$total_denda = 0;
$denda_list  = [];
while ($row = mysqli_fetch_assoc($denda_query)) {
    $total_denda += $row['denda'];
    $denda_list[] = $row;
}

// Proses bayar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($denda_list as $d) {
        mysqli_query($koneksi, "UPDATE peminjaman SET 
            status_denda='lunas', status='dikembalikan',
            tanggal_dikembalikan='" . date('Y-m-d') . "'
            WHERE id='{$d['id']}'");
    }
    // Unblock user
    mysqli_query($koneksi, "UPDATE users SET status='aktif' WHERE id='$user_id'");
    
    header('Location: index.php?unblock=success');
    exit;
}
?>

<div class="container">
    <h2 class="page-title">Bayar Denda</h2>

    <div class="form-box" style="max-width:500px;">
        <div class="alert-error" style="margin-bottom:20px;">
            ⛔ Akun kamu diblokir karena keterlambatan pengembalian buku!
        </div>

        <h3 style="margin-bottom:15px;">Detail Denda</h3>
        <table class="table" style="margin-bottom:20px;">
            <thead>
                <tr>
                    <th>Judul Buku</th>
                    <th>Denda</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($denda_list as $d): ?>
                <tr>
                    <td><?= $d['judul'] ?></td>
                    <td style="color:#e74c3c;">
                        Rp <?= number_format($d['denda'], 0, ',', '.') ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td><strong>Total</strong></td>
                    <td><strong style="color:#e74c3c;">
                        Rp <?= number_format($total_denda, 0, ',', '.') ?>
                    </strong></td>
                </tr>
            </tbody>
        </table>

        <p style="font-size:13px; color:#777; margin-bottom:20px;">
            * Pembayaran bersifat simulasi. Klik tombol di bawah untuk melunasi denda dan membuka blokir akun.
        </p>

        <form method="POST">
            <button type="submit" class="btn-full" style="background-color:#27ae60;">
                💰 Bayar Denda Rp <?= number_format($total_denda, 0, ',', '.') ?>
            </button>
        </form>

        <br>
        <a href="minta_unblock.php" class="btn-secondary" style="display:block; text-align:center;">
            📨 Minta Persetujuan Admin (Gratis)
        </a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>