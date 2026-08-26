<?php
include 'config/koneksi.php';
include 'includes/header.php';

// Harus sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$buku_id = $_GET['id'];

// Cek status akun
$user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id='$user_id'"));
if ($user['status'] == 'diblokir') {
    header('Location: detail_buku.php?id=' . $buku_id);
    exit;
}

// Cek apakah sudah pinjam buku ini
$cek = mysqli_query($koneksi, "SELECT * FROM peminjaman 
    WHERE user_id='$user_id' AND buku_id='$buku_id' AND status='dipinjam'");
if (mysqli_num_rows($cek) > 0) {
    header('Location: detail_buku.php?id=' . $buku_id);
    exit;
}

// Proses pinjam
$tanggal_pinjam  = date('Y-m-d');
$tanggal_kembali = date('Y-m-d', strtotime('+7 days'));

mysqli_query($koneksi, "INSERT INTO peminjaman (user_id, buku_id, tanggal_pinjam, tanggal_kembali, status, denda, status_denda) 
    VALUES ('$user_id', '$buku_id', '$tanggal_pinjam', '$tanggal_kembali', 'dipinjam', 0, 'lunas')");

header('Location: detail_buku.php?id=' . $buku_id . '&pinjam=success');
exit;
?>