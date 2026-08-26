<?php
include '../config/koneksi.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$query = mysqli_query($koneksi, "SELECT * FROM users ORDER BY created_at ASC");
?>

<div class="container">
    <div class="page-header">
    <h2 class="page-title">Data User</h2>
    <a href="tambah_user.php" class="btn-primary">+ Tambah User</a>
</div>

    <div class="table-wrapper"> 
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Tanggal Daftar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['nama'] ?></td>
                <td><?= $row['email'] ?></td>
                <td>
                    <span class="badge <?= $row['role'] == 'admin' ? 'badge-admin' : 'badge-member' ?>">
                        <?= $row['role'] ?>
                    </span>
                </td>
                <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                <td>
                    <?php if ($row['id'] != $_SESSION['user_id']): ?>
                        <a href="hapus_user.php?id=<?= $row['id'] ?>" class="btn-hapus"
   onclick="konfirmasiAksi(event, this.href, 'Yakin ingin menghapus user <?= htmlspecialchars(addslashes($row['nama'])) ?>?', 'Ya, Hapus')">Hapus</a>
                    <?php else: ?>
                        <span style="color:#aaa; font-size:12px;">Akun kamu</span>
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