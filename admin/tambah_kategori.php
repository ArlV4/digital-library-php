<?php
include '../config/koneksi.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kategori = $_POST['nama_kategori'];

    $cek = mysqli_query($koneksi, "SELECT * FROM kategori WHERE nama_kategori='$nama_kategori'");

    if (mysqli_num_rows($cek) > 0) {
        $error = 'Kategori sudah ada!';
    } else {
        $insert = mysqli_query($koneksi, "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')");
        if ($insert) {
            $success = 'Kategori berhasil ditambahkan!';
        } else {
            $error = 'Gagal menambahkan kategori!';
        }
    }
}

// Ambil semua kategori
$kategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
?>

<div class="container">
    <h2 class="page-title">Tambah Kategori</h2>

    <div class="two-column">

        <!-- Form Tambah -->
        <div class="form-box">
            <h3>Kategori Baru</h3>

            <?php if ($error): ?>
                <div class="alert-error"><?= $error ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert-success"><?= $success ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input type="text" name="nama_kategori" placeholder="Contoh: Fiksi, Sains, dll" required>
                </div>
                <button type="submit" class="btn-full">Tambah Kategori</button>
            </form>
        </div>

        <!-- Daftar Kategori -->
        <div class="form-box">
            <h3>Daftar Kategori</h3>
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($kategori)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['nama_kategori'] ?></td>
                            <td>
                               <a href="hapus_kategori.php?id=<?= $row['id'] ?>" class="btn-hapus"
   onclick="konfirmasiAksi(event, this.href, 'Hapus kategori <?= htmlspecialchars(addslashes($row['nama_kategori'])) ?>?', 'Ya, Hapus')">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <br>
    <a href="dashboard.php" class="btn-secondary">← Kembali ke Dashboard</a>
</div>