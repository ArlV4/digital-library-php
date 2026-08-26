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
    $judul       = $_POST['judul'];
    $pengarang   = $_POST['pengarang'];
    $kategori_id = $_POST['kategori_id'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $deskripsi   = $_POST['deskripsi'];
    $cover       = '';
    $file_pdf    = '';

    // Upload cover
    if ($_FILES['cover']['name'] != '') {
        $cover = time() . '_' . $_FILES['cover']['name'];
        move_uploaded_file($_FILES['cover']['tmp_name'], '../assets/img/' . $cover);
    }

    // Upload PDF
    if ($_FILES['file_pdf']['name'] != '') {
        $file_pdf = time() . '_' . $_FILES['file_pdf']['name'];
        move_uploaded_file($_FILES['file_pdf']['tmp_name'], '../assets/pdf/' . $file_pdf);
    }

    $insert = mysqli_query($koneksi, "INSERT INTO buku (judul, pengarang, kategori_id, tahun_terbit, cover, file_pdf, deskripsi) 
                                      VALUES ('$judul', '$pengarang', '$kategori_id', '$tahun_terbit', '$cover', '$file_pdf', '$deskripsi')");
    if ($insert) {
        $success = 'Buku berhasil ditambahkan!';
    } else {
        $error = 'Gagal menambahkan buku!';
    }
}

$kategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
?>

<div class="container">
    <h2 class="page-title">Tambah Buku</h2>

    <div class="form-box" style="max-width: 700px;">

        <?php if ($error): ?>
            <div class="alert-error"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Judul Buku</label>
                <input type="text" name="judul" placeholder="Masukkan judul buku" required>
            </div>
            <div class="form-group">
                <label>Pengarang</label>
                <input type="text" name="pengarang" placeholder="Nama pengarang" required>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php while ($row = mysqli_fetch_assoc($kategori)): ?>
                        <option value="<?= $row['id'] ?>"><?= $row['nama_kategori'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Tahun Terbit</label>
                <input type="number" name="tahun_terbit" placeholder="Contoh: 2023" min="1900" max="2099">
            </div>
            <div class="form-group">
                <label>Cover Buku (JPG/PNG)</label>
                <input type="file" name="cover" accept="image/*">
            </div>
            <div class="form-group">
                <label>File PDF Buku</label>
                <input type="file" name="file_pdf" accept=".pdf">
            </div>
            <div class="form-group">
                <label>Deskripsi / Sinopsis</label>
                <textarea name="deskripsi" rows="4" placeholder="Tulis sinopsis buku..."></textarea>
            </div>
            <button type="submit" class="btn-full">Simpan Buku</button>
        </form>
    </div>

    <br>
    <a href="dashboard.php" class="btn-secondary">← Kembali ke Dashboard</a>
</div>

<?php include '../includes/footer.php'; ?>