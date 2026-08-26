<?php
include 'config/koneksi.php';
include 'includes/header.php';

$kategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
?>

<div class="container">
    <h2 class="page-title">Katalog Buku</h2>

    <div class="filter-form">
        <input type="text" id="searchInput" placeholder="Ketik untuk mencari judul atau pengarang...">
        <select id="kategoriSelect">
            <option value="">-- Semua Kategori --</option>
            <?php while ($kat = mysqli_fetch_assoc($kategori)): ?>
                <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['nama_kategori']) ?></option>
            <?php endwhile; ?>
        </select>
        <button type="button" onclick="muatData(1)">🔍 Cari</button>
        <button type="button" class="btn-secondary" onclick="resetFilter()">Reset</button>
    </div>

    <div id="katalogContent">
        <p style="text-align:center; color:var(--text-muted); padding:30px;">Memuat katalog buku...</p>
    </div>
</div>

<script>
let timerPencarian;

function muatData(halaman = 1) {
    const search = encodeURIComponent(document.getElementById('searchInput').value);
    const kategori_id = encodeURIComponent(document.getElementById('kategoriSelect').value);
    const kontainer = document.getElementById('katalogContent');

    kontainer.style.opacity = '0.5';

    fetch(`ajax_katalog.php?search=${search}&kategori_id=${kategori_id}&halaman=${halaman}`)
        .then(response => response.text())
        .then(html => {
            kontainer.innerHTML = html;
            kontainer.style.opacity = '1';
        })
        .catch(err => {
            kontainer.innerHTML = '<p style="color:red; text-align:center;">Gagal memuat data buku.</p>';
            kontainer.style.opacity = '1';
        });
}

function resetFilter() {
    document.getElementById('searchInput').value = '';
    document.getElementById('kategoriSelect').value = '';
    muatData(1);
}

document.getElementById('searchInput').addEventListener('input', () => {
    clearTimeout(timerPencarian);
    timerPencarian = setTimeout(() => muatData(1), 300);
});

document.getElementById('kategoriSelect').addEventListener('change', () => muatData(1));
document.addEventListener('DOMContentLoaded', () => muatData(1));
</script>

<?php include 'includes/footer.php'; ?>