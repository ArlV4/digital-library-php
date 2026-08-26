<?php
include '../config/koneksi.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

// Proses approve/reject
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id     = $_GET['id'];
    $action = $_GET['action'];

    $request = mysqli_fetch_assoc(mysqli_query($koneksi, 
        "SELECT * FROM unblock_request WHERE id='$id'"));

    if ($action == 'approve') {
        // Lunasin semua denda & unblock
        mysqli_query($koneksi, "UPDATE peminjaman SET 
            status_denda='lunas', status='dikembalikan',
            tanggal_dikembalikan='" . date('Y-m-d') . "'
            WHERE user_id='{$request['user_id']}' AND status_denda='belum_lunas'");
        mysqli_query($koneksi, "UPDATE users SET status='aktif' WHERE id='{$request['user_id']}'");
        mysqli_query($koneksi, "UPDATE unblock_request SET status='approved' WHERE id='$id'");
    } else {
        mysqli_query($koneksi, "UPDATE unblock_request SET status='rejected' WHERE id='$id'");
    }

    header('Location: unblock_user.php');
    exit;
}

// Ambil semua request
$requests = mysqli_query($koneksi, "SELECT ur.*, u.nama, u.email 
    FROM unblock_request ur 
    JOIN users u ON ur.user_id = u.id 
    ORDER BY ur.created_at DESC");
?>

<div class="container">
    <h2 class="page-title">Permintaan Unblock User</h2>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Alasan</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($requests)): ?>
            <tr>
                <td><?= $row['nama'] ?></td>
                <td><?= $row['email'] ?></td>
                <td style="font-size:13px;"><?= $row['alasan'] ?></td>
                <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                <td>
                    <?php if ($row['status'] == 'pending'): ?>
                        <span style="color:#f39c12; font-weight:bold;">⏳ Pending</span>
                    <?php elseif ($row['status'] == 'approved'): ?>
                        <span style="color:#27ae60; font-weight:bold;">✅ Approved</span>
                    <?php else: ?>
                        <span style="color:#e74c3c; font-weight:bold;">❌ Rejected</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($row['status'] == 'pending'): ?>
                        <a href="?action=approve&id=<?= $row['id'] ?>" class="btn-secondary" style="font-size:12px; padding:5px 10px; background-color:#27ae60;"
   onclick="konfirmasiAksi(event, this.href, 'Setujui unblock user ini?', 'Ya, Setujui')">✅ Approve</a>
<a href="?action=reject&id=<?= $row['id'] ?>" class="btn-hapus"
   onclick="konfirmasiAksi(event, this.href, 'Tolak unblock user ini?', 'Ya, Tolak')">❌ Reject</a>
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