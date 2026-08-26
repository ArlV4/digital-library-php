<?php
include '../config/koneksi.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$hari_ini = date('Y-m-d');

// Update denda otomatis untuk semua yang terlambat
$terlambat = mysqli_query($koneksi, "SELECT * FROM peminjaman 
    WHERE status='dipinjam' AND tanggal_kembali < '$hari_ini'");

while ($row = mysqli_fetch_assoc($terlambat)) {
    $selisih = (strtotime($hari_ini) - strtotime($row['tanggal_kembali'])) / 86400;

    if ($selisih <= 3) $denda = 5000;
    elseif ($selisih <= 7) $denda = 10000;
    else $denda = 20000;

    mysqli_query($koneksi, "UPDATE peminjaman SET denda='$denda', status_denda='belum_lunas' 
        WHERE id='{$row['id']}'");
    mysqli_query($koneksi, "UPDATE users SET status='diblokir' WHERE id='{$row['user_id']}'");
}

// Filter
$filter  = isset($_GET['filter']) ? $_GET['filter'] : 'semua';
$where   = "WHERE 1=1";
if ($filter == 'aktif')     $where .= " AND p.status='dipinjam' AND p.tanggal_kembali >= '$hari_ini'";
if ($filter == 'terlambat') $where .= " AND p.status='dipinjam' AND p.tanggal_kembali < '$hari_ini'";
if ($filter == 'selesai')   $where .= " AND p.status='dikembalikan'";

$peminjaman = mysqli_query($koneksi, "SELECT p.*, u.nama, u.email, b.judul 
    FROM peminjaman p 
    JOIN users u ON p.user_id = u.id 
    JOIN buku b ON p.buku_id = b.id 
    $where
    ORDER BY p.tanggal_pinjam DESC");
?>

<div class="container">
    <h2 class="page-title">Kelola Peminjaman</h2>

    <!-- Filter -->
    <div class="filter-form" style="margin-bottom:20px;">
        <a href="?filter=semua" 
           class="<?= $filter == 'semua' ? 'btn-primary' : 'btn-secondary' ?>">Semua</a>
        <a href="?filter=aktif" 
           class="<?= $filter == 'aktif' ? 'btn-primary' : 'btn-secondary' ?>">Aktif</a>
        <a href="?filter=terlambat" 
           class="<?= $filter == 'terlambat' ? 'btn-primary' : 'btn-secondary' ?>">Terlambat</a>
        <a href="?filter=selesai" 
           class="<?= $filter == 'selesai' ? 'btn-primary' : 'btn-secondary' ?>">Selesai</a>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                <th>No</th>
                <th>Nama User</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($peminjaman)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td>
                    <?= $row['nama'] ?>
                    <br>
                    <small style="color:#777;"><?= $row['email'] ?></small>
                </td>
                <td><?= $row['judul'] ?></td>
                <td><?= date('d M Y', strtotime($row['tanggal_pinjam'])) ?></td>
                <td><?= date('d M Y', strtotime($row['tanggal_kembali'])) ?></td>
                <td>
                    <?php if ($row['status'] == 'dikembalikan'): ?>
                        <span style="color:#777;">✅ Selesai</span>
                    <?php elseif ($row['tanggal_kembali'] < $hari_ini): ?>
                        <span style="color:#e74c3c; font-weight:bold;">⚠️ Terlambat</span>
                    <?php else: ?>
                        <span style="color:#27ae60; font-weight:bold;">📖 Dipinjam</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($row['denda'] > 0): ?>
                        <span style="color:#e74c3c;">
                            Rp <?= number_format($row['denda'], 0, ',', '.') ?>
                            <?= $row['status_denda'] == 'lunas' ? '✅' : '❌' ?>
                        </span>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    </div>

    <br>
    <a href="dashboard.php" class="btn-secondary">← Kembali ke Dashboard</a>
</div>

<?php include '../includes/footer.php'; ?>