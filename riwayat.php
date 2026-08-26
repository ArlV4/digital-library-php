<?php
include 'config/koneksi.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user    = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id='$user_id'"));
$hari_ini = date('Y-m-d');

$terlambat = mysqli_query($koneksi, "SELECT * FROM peminjaman 
    WHERE user_id='$user_id' AND status='dipinjam' AND tanggal_kembali < '$hari_ini'");

while ($row = mysqli_fetch_assoc($terlambat)) {
    $selisih = (strtotime($hari_ini) - strtotime($row['tanggal_kembali'])) / 86400;
    $denda = ($selisih <= 3) ? 5000 : (($selisih <= 7) ? 10000 : 20000);

    mysqli_query($koneksi, "UPDATE peminjaman SET denda='$denda', status_denda='belum_lunas' WHERE id='{$row['id']}'");
    mysqli_query($koneksi, "UPDATE users SET status='diblokir' WHERE id='$user_id'");
}

$riwayat = mysqli_query($koneksi, "SELECT p.*, b.judul, b.cover, b.pengarang 
    FROM peminjaman p 
    JOIN buku b ON p.buku_id = b.id 
    WHERE p.user_id='$user_id' 
    ORDER BY p.tanggal_pinjam DESC");
?>

<div class="container">
    <h2 class="page-title">Riwayat Peminjaman</h2>

    <?php if ($user['status'] == 'diblokir'): ?>
        <div class="alert-error" style="margin-bottom:20px;">
            ⛔ Akun kamu diblokir karena ada keterlambatan pengembalian buku!
        </div>
    <?php endif; ?>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Cover</th>
                    <th>Judul</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                    <th>Denda</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($riwayat)): ?>
                <tr>
                    <td>
                        <?php if ($row['cover']): ?>
                            <img src="assets/img/<?= htmlspecialchars($row['cover']) ?>" style="height:50px; border-radius:4px;">
                        <?php else: ?>
                            📚
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= date('d M Y', strtotime($row['tanggal_pinjam'])) ?></td>
                    <td><?= date('d M Y', strtotime($row['tanggal_kembali'])) ?></td>
                    <td>
                        <?php if ($row['status'] == 'dipinjam'): ?>
                            <?= ($row['tanggal_kembali'] < $hari_ini) ? '<span style="color:#e74c3c; font-weight:bold;">⚠️ Terlambat</span>' : '<span style="color:#27ae60; font-weight:bold;">✅ Dipinjam</span>' ?>
                        <?php else: ?>
                            <span style="color:#777;">Dikembalikan</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= ($row['denda'] > 0) ? '<span style="color:#e74c3c;">Rp ' . number_format($row['denda'], 0, ',', '.') . ($row['status_denda'] == 'lunas' ? ' ✅' : ' ❌') . '</span>' : '-' ?>
                    </td>
                    <td>
                        <?php if ($row['status'] == 'dipinjam' && $row['tanggal_kembali'] >= $hari_ini): ?>
                            <a href="kembalikan.php?id=<?= $row['id'] ?>&buku_id=<?= $row['buku_id'] ?>"
                               class="btn-secondary" style="font-size:12px; padding:5px 10px;"
                               onclick="konfirmasiAksi(event, this.href, 'Kembalikan buku ini sekarang?', 'Ya, Kembalikan')">Kembalikan</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <br>
    <a href="katalog.php" class="btn-secondary">← Kembali ke Katalog</a>
</div>

<?php include 'includes/footer.php'; ?>