<?php
include '../config/koneksi.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$query = mysqli_query($koneksi, "SELECT b.*, k.nama_kategori 
                                  FROM buku b 
                                  LEFT JOIN kategori k ON b.kategori_id = k.id 
                                  ORDER BY b.created_at DESC");
?>

<div class="container">
    <h2 class="page-title">Data Buku</h2>
    <a href="tambah_buku.php" class="btn-primary" style="display:inline-block; margin-bottom:20px;">+ Tambah Buku</a>

    <div class="table-wrapper">
        <table class="table">
            <thead>
            <tr>
                <th>No</th>
                <th>Cover</th>
                <th>Judul</th>
                <th>Pengarang</th>
                <th>Kategori</th>
                <th>Tahun</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td>
                    
                    <?php if ($row['cover']): ?>
                        <img src="../assets/img/<?= $row['cover'] ?>" style="height:50px; border-radius:4px;">
                    <?php else: ?>
                        📚
                    <?php endif; ?>
                </td>
                <td><?= $row['judul'] ?></td>
                <td><?= $row['pengarang'] ?></td>
                <td><?= $row['nama_kategori'] ?></td>
                <td><?= $row['tahun_terbit'] ?></td>
                <td>
                    <a href="edit_buku.php?id=<?= $row['id'] ?>" class="btn-secondary" style="font-size:12px; padding:5px 10px;">Edit</a>
                   <a href="hapus_buku.php?id=<?= $row['id'] ?>" class="btn-hapus"
   onclick="konfirmasiAksi(event, this.href, 'Yakin ingin menghapus buku <?= htmlspecialchars(addslashes($row['judul'])) ?>?', 'Ya, Hapus')">Hapus</a>
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