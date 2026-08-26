<?php
include '../config/koneksi.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$total_buku      = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku"))['total'];
$total_user      = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users"))['total'];
$total_kategori  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kategori"))['total'];
$total_pinjam    = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman WHERE status='dipinjam'"))['total'];
$total_terlambat = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman WHERE status='dipinjam' AND tanggal_kembali < '" . date('Y-m-d') . "'"))['total'];
$total_diblokir  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE status='diblokir'"))['total'];
$total_selesai   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman WHERE status='dikembalikan'"))['total'];

$kategori_chart_query = mysqli_query($koneksi, "SELECT k.nama_kategori, COUNT(b.id) as jumlah 
                                                FROM kategori k 
                                                LEFT JOIN buku b ON k.id = b.kategori_id 
                                                GROUP BY k.id");
$label_kategori = [];
$data_kategori  = [];
while ($row = mysqli_fetch_assoc($kategori_chart_query)) {
    $label_kategori[] = $row['nama_kategori'];
    $data_kategori[]  = (int)$row['jumlah'];
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container">
    <h2 class="page-title">Dashboard Admin</h2>
    <p style="margin-bottom:20px; color:var(--text-muted);">Selamat datang, <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong>!</p>

    <div class="stats-grid" style="grid-template-columns: repeat(3, 1fr);">
        <div class="stat-card">
            <div class="stat-icon">📚</div>
            <div class="stat-info">
                <h3><?= $total_buku ?></h3>
                <p>Total Buku</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-info">
                <h3><?= $total_user ?></h3>
                <p>Total User</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🏷️</div>
            <div class="stat-info">
                <h3><?= $total_kategori ?></h3>
                <p>Total Kategori</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📖</div>
            <div class="stat-info">
                <h3><?= $total_pinjam ?></h3>
                <p>Sedang Dipinjam</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">⚠️</div>
            <div class="stat-info">
                <h3><?= $total_terlambat ?></h3>
                <p>Terlambat</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">⛔</div>
            <div class="stat-info">
                <h3><?= $total_diblokir ?></h3>
                <p>User Diblokir</p>
            </div>
        </div>
    </div>

    <div class="two-column" style="margin-top: 25px;">
        <div class="form-box" style="max-width: 100%; margin: 0; padding: 25px;">
            <h3 style="font-size: 16px; margin-bottom: 15px; text-align: center;">Statistik Status Peminjaman</h3>
            <div style="position: relative; height: 260px; width: 100%;">
                <canvas id="chartStatus"></canvas>
            </div>
        </div>
        <div class="form-box" style="max-width: 100%; margin: 0; padding: 25px;">
            <h3 style="font-size: 16px; margin-bottom: 15px; text-align: center;">Jumlah Buku per Kategori</h3>
            <div style="position: relative; height: 260px; width: 100%;">
                <canvas id="chartKategori"></canvas>
            </div>
        </div>
    </div>

    <div class="admin-menu" style="margin-top:35px;">
        <h3>Kelola Data</h3>
        <div class="menu-grid">
            <a href="tambah_buku.php" class="menu-card"><span>➕</span><p>Tambah Buku</p></a>
            <a href="data_buku.php" class="menu-card"><span>📚</span><p>Data Buku</p></a>
            <a href="tambah_kategori.php" class="menu-card"><span>🏷️</span><p>Kelola Kategori</p></a>
            <a href="data_user.php" class="menu-card"><span>👥</span><p>Data User</p></a>
            <a href="kelola_peminjaman.php" class="menu-card"><span>📋</span><p>Kelola Peminjaman</p></a>
            <a href="unblock_user.php" class="menu-card"><span>🔓</span><p>Unblock User</p></a>
            <a href="../katalog.php" class="menu-card"><span>🔍</span><p>Lihat Katalog</p></a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctxStatus = document.getElementById('chartStatus').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Aktif Dipinjam', 'Terlambat', 'Selesai Dikembalikan'],
            datasets: [{
                data: [<?= max(0, $total_pinjam - $total_terlambat) ?>, <?= $total_terlambat ?>, <?= $total_selesai ?>],
                backgroundColor: ['#27ae60', '#e74c3c', '#2980b9'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    const ctxKategori = document.getElementById('chartKategori').getContext('2d');
    new Chart(ctxKategori, {
        type: 'bar',
        data: {
            labels: <?= json_encode($label_kategori) ?>,
            datasets: [{
                label: 'Jumlah Buku',
                data: <?= json_encode($data_kategori) ?>,
                backgroundColor: '#c9922a',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
            plugins: { legend: { display: false } }
        }
    });
});
</script>

<?php include '../includes/footer.php'; ?>