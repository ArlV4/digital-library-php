<?php
include 'config/koneksi.php';
include 'includes/header.php';

$id    = mysqli_real_escape_string($koneksi, $_GET['id']);
$query = mysqli_query($koneksi, "SELECT b.*, k.nama_kategori 
                                  FROM buku b 
                                  LEFT JOIN kategori k ON b.kategori_id = k.id 
                                  WHERE b.id = '$id'");
$buku  = mysqli_fetch_assoc($query);

if (!$buku) {
    echo "<div class='container'><p>Buku tidak ditemukan.</p></div>";
    include 'includes/footer.php';
    exit;
}

$hari_ini = date('Y-m-d');
$pinjaman_aktif = null;

if (isset($_SESSION['user_id'])) {
    $user_id   = $_SESSION['user_id'];
    $user_data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT status FROM users WHERE id='$user_id'"));
    $cek_pinjam = mysqli_query($koneksi, "SELECT * FROM peminjaman 
        WHERE user_id='$user_id' AND buku_id='{$buku['id']}' AND status='dipinjam'");
    $pinjaman_aktif = mysqli_fetch_assoc($cek_pinjam);
}
?>

<div class="container">
    <div class="detail-box">
        <div class="detail-cover">
            <?php if ($buku['cover']): ?>
                <img src="assets/img/<?= htmlspecialchars($buku['cover']) ?>" alt="<?= htmlspecialchars($buku['judul']) ?>">
            <?php else: ?>
                <div class="no-cover" style="height:350px; font-size:100px;">📚</div>
            <?php endif; ?>
        </div>

        <div class="detail-info">
            <span class="badge"><?= htmlspecialchars($buku['nama_kategori']) ?></span>
            <h1><?= htmlspecialchars($buku['judul']) ?></h1>
            <table class="detail-table">
                <tr>
                    <td>Pengarang</td>
                    <td>: <?= htmlspecialchars($buku['pengarang']) ?></td>
                </tr>
                <tr>
                    <td>Kategori</td>
                    <td>: <?= htmlspecialchars($buku['nama_kategori']) ?></td>
                </tr>
                <tr>
                    <td>Tahun Terbit</td>
                    <td>: <?= $buku['tahun_terbit'] ?></td>
                </tr>
            </table>

            <div class="detail-deskripsi">
                <h3>Sinopsis</h3>
                <p><?= $buku['deskripsi'] ? nl2br(htmlspecialchars($buku['deskripsi'])) : 'Tidak ada sinopsis.' ?></p>
            </div>

            <div class="detail-actions" style="flex-direction:column; gap:15px;">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="login.php" class="btn-primary">🔒 Login untuk Meminjam</a>
                <?php elseif ($user_data['status'] == 'diblokir'): ?>
                    <div class="alert-error">⛔ Akun kamu diblokir! Selesaikan denda terlebih dahulu.</div>
                    <div style="display:flex; gap:10px;">
                        <a href="bayar_denda.php" class="btn-primary">💰 Bayar Denda</a>
                        <a href="minta_unblock.php" class="btn-secondary">📨 Minta Persetujuan Admin</a>
                    </div>
                <?php elseif ($pinjaman_aktif): ?>
                    <div class="info-pinjaman">
                        <p>📅 Batas kembali: <strong><?= date('d M Y', strtotime($pinjaman_aktif['tanggal_kembali'])) ?></strong></p>
                    </div>
                    <?php if ($buku['file_pdf']): ?>
                        <div style="display:flex; gap:10px;">
                            <a href="assets/pdf/<?= htmlspecialchars($buku['file_pdf']) ?>" target="_blank" class="btn-primary">📖 Baca Sekarang</a>
                            <a href="assets/pdf/<?= htmlspecialchars($buku['file_pdf']) ?>" download class="btn-secondary">⬇️ Download PDF</a>
                        </div>
                    <?php endif; ?>
                    <a href="kembalikan.php?id=<?= $pinjaman_aktif['id'] ?>&buku_id=<?= $buku['id'] ?>"
                       class="btn-hapus" style="display:inline-block; padding:10px 20px; text-align:center;"
                       onclick="konfirmasiAksi(event, this.href, 'Apakah kamu ingin mengembalikan buku ini sekarang?', 'Ya, Kembalikan')">
                       🔄 Kembalikan Buku
                    </a>
                <?php else: ?>
                    <?php if ($buku['file_pdf']): ?>
                        <a href="pinjam.php?id=<?= $buku['id'] ?>" class="btn-primary"
                           onclick="konfirmasiAksi(event, this.href, 'Pinjam buku ini selama 7 hari?', 'Ya, Pinjam')">
                           📚 Pinjam Buku (7 Hari)
                        </a>
                    <?php else: ?>
                        <p style="color:#e74c3c;">PDF belum tersedia.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <br>
            <a href="katalog.php" class="btn-secondary">← Kembali ke Katalog</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>