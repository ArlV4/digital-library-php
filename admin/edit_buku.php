<?php
include '../config/koneksi.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$id   = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM buku WHERE id='$id'");
$buku  = mysqli_fetch_assoc($query);

if (!$buku) {
    header('Location: dashboard.php');
    exit;
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul        = $_POST['judul'];
    $pengarang    = $_POST['pengarang'];
    $kategori_id  = $_POST['kategori_id'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $deskripsi    = $_POST['deskripsi'];
    $cover        = $buku['cover'];
    $file_pdf     = $buku['file_pdf'];

    // Upload cover baru kalau ada
    if ($_FILES['cover']['name'] != '') {
        $cover = time() . '_' . $_FILES['cover']['name'];
        move_uploaded_file($_FILES['cover']['tmp_name'], '../assets/img/' . $cover);
    }

    // Upload PDF baru kalau ada
    if ($_FILES['file_pdf']['name'] != '') {
        $file_pdf = time() . '_' . $_FILES['file_pdf']['name'];
        move_uploaded_file($_FILES['file_pdf']['tmp_name'], '../assets/pdf/' . $file_pdf);
    }

    $update = mysqli_query($koneksi, "UPDATE buku SET 
        judul='$judul', pengarang='$pengarang', kategori_id='$kategori_id',
        tahun_terbit='$tahun_terbit', cover='$cover', file_pdf='$file_pdf',
        deskripsi='$deskripsi'
        WHERE id='$id'");

    if ($update) {
        $success = 'Buku berhasil diperbarui!';
        $buku = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM buku WHERE id='$id'"));
    } else {
        $error = 'Gagal memperbarui buku!';
    }
}

$kategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
?>

<div class="container">
    <h2 class="page-title">Edit Buku</h2>

    <div class="form-box" style="max-width:700px;">

        <?php if ($error): ?>
            <div class="alert-error"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Judul Buku</label>
                <input type="text" name="judul" value="<?= $buku['judul'] ?>" required>
            </div>
            <div class="form-group">
                <label>Pengarang</label>
                <input type="text" name="pengarang" value="<?= $buku['pengarang'] ?>" required>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php while ($row = mysqli_fetch_assoc($kategori)): ?>
                        <option value="<?= $row['id'] ?>" 
                            <?= $buku['kategori_id'] == $row['id'] ? 'selected' : '' ?>>
                            <?= $row['nama_kategori'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Tahun Terbit</label>
                <input type="number" name="tahun_terbit" value="<?= $buku['tahun_terbit'] ?>" min="1900" max="2099">
            </div>
            <div class="form-group">
                <label>Cover Buku (kosongkan jika tidak diganti)</label>
                <?php if ($buku['cover']): ?>
                    <img src="../assets/img/<?= $buku['cover'] ?>" style="height:80px; display:block; margin-bottom:8px; border-radius:4px;">
                <?php endif; ?>
                <input type="file" name="cover" accept="image/*">
            </div>
            <div class="form-group">
                <label>File PDF (kosongkan jika tidak diganti)</label>
                <?php if ($buku['file_pdf']): ?>
                    <p style="font-size:13px; color:#27ae60; margin-bottom:5px;">✅ PDF sudah ada: <?= $buku['file_pdf'] ?></p>
                <?php endif; ?>
                <input type="file" name="file_pdf" accept=".pdf">
            </div>
            <div class="form-group">
                <label>Deskripsi / Sinopsis</label>
                <textarea name="deskripsi" rows="4"><?= $buku['deskripsi'] ?></textarea>
            </div>
            <button type="submit" class="btn-full">Simpan Perubahan</button>
        </form>
    </div>

    <br>
    <a href="dashboard.php" class="btn-secondary">← Kembali ke Dashboard</a>
</div>

<?php include '../includes/footer.php'; ?>