<?php include 'includes/header.php'; ?>
<?php if (!isset($_SESSION['user_id'])): ?>
    <div class="info-bar">
        🔒 Silakan <a href="login.php">login</a> atau <a href="register.php">daftar</a> untuk bisa membaca dan mendownload buku!
    </div>
<?php endif; ?>

<div class="container">

    <!-- Hero Section -->
    <div class="hero">
        <h1>Selamat Datang di Perpustakaan Digital</h1>
        <p>Temukan dan baca buku secara online</p>
        <a href="katalog.php" class="btn-primary">Lihat Katalog</a>
    </div>

    <!-- Buku Terbaru -->
    <div class="section">
        <h2>Buku Terbaru</h2>
        <div class="buku-grid">

            <?php
            include 'config/koneksi.php';
            $query = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY created_at DESC LIMIT 8");

            while ($buku = mysqli_fetch_assoc($query)): ?>

                <div class="buku-card">
                    <?php if ($buku['cover']): ?>
                        <img src="assets/img/<?= $buku['cover'] ?>" alt="<?= $buku['judul'] ?>">
                    <?php else: ?>
                        <div class="no-cover">📚</div>
                    <?php endif; ?>
                    <h3><?= $buku['judul'] ?></h3>
                    <p><?= $buku['pengarang'] ?></p>
                    <a href="detail_buku.php?id=<?= $buku['id'] ?>" class="btn-secondary">Lihat Detail</a>
                </div>

            <?php endwhile; ?>

        </div>
    </div>

</div>

<?php include 'includes/footer.php'; ?>