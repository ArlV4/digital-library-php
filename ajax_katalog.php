<?php
include 'config/koneksi.php';

$search      = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, trim($_GET['search'])) : '';
$kategori_id = isset($_GET['kategori_id']) ? mysqli_real_escape_string($koneksi, trim($_GET['kategori_id'])) : '';

$per_halaman = 8;
$halaman     = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$mulai_dari  = ($halaman - 1) * $per_halaman;

$where = "WHERE 1=1";
if ($search != '') {
    $kata = explode(' ', $search);
    foreach ($kata as $k) {
        if ($k != '') {
            $where .= " AND (b.judul LIKE '%$k%' OR b.pengarang LIKE '%$k%')";
        }
    }
}
if ($kategori_id != '') {
    $where .= " AND b.kategori_id = '$kategori_id'";
}

$total_query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku b $where");
$total_buku  = mysqli_fetch_assoc($total_query)['total'];
$total_halaman = ceil($total_buku / $per_halaman);

$query = mysqli_query($koneksi, "SELECT b.*, k.nama_kategori 
                                  FROM buku b 
                                  LEFT JOIN kategori k ON b.kategori_id = k.id 
                                  $where 
                                  ORDER BY b.created_at DESC
                                  LIMIT $mulai_dari, $per_halaman");
?>

<p style="font-size:13px; color:var(--text-muted); margin-bottom:15px;" id="info-total">
    Menampilkan <?= $total_buku ?> buku
    <?= $search ? "untuk pencarian <strong>'" . htmlspecialchars($search) . "'</strong>" : '' ?>
</p>

<div class="buku-grid">
    <?php if ($total_buku == 0): ?>
        <p style="grid-column: 1/-1; text-align:center; color:var(--text-muted); padding: 30px 0;">
            Tidak ada buku yang ditemukan.
        </p>
    <?php endif; ?>

    <?php while ($buku = mysqli_fetch_assoc($query)): ?>
        <div class="buku-card">
            <?php if ($buku['cover']): ?>
                <img src="assets/img/<?= htmlspecialchars($buku['cover']) ?>" alt="<?= htmlspecialchars($buku['judul']) ?>">
            <?php else: ?>
                <div class="no-cover">📚</div>
            <?php endif; ?>
            <span class="badge"><?= htmlspecialchars($buku['nama_kategori']) ?></span>
            <h3><?= htmlspecialchars($buku['judul']) ?></h3>
            <p><?= htmlspecialchars($buku['pengarang']) ?></p>
            <a href="detail_buku.php?id=<?= $buku['id'] ?>" class="btn-secondary">Lihat Detail</a>
        </div>
    <?php endwhile; ?>
</div>

<?php if ($total_halaman > 1): ?>
<div class="pagination">
    <?php if ($halaman > 1): ?>
        <a href="javascript:void(0)" onclick="muatData(<?= $halaman - 1 ?>)">← Sebelumnya</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
        <a href="javascript:void(0)" 
           onclick="muatData(<?= $i ?>)"
           class="<?= $halaman == $i ? 'active' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($halaman < $total_halaman): ?>
        <a href="javascript:void(0)" onclick="muatData(<?= $halaman + 1 ?>)">Selanjutnya →</a>
    <?php endif; ?>
</div>
<?php endif; ?>