<?php
include 'config/koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id     = $_SESSION['user_id'];
$pinjaman_id = $_GET['id'];
$buku_id     = $_GET['buku_id'];

// Cek pinjaman milik user ini
$pinjaman = mysqli_fetch_assoc(mysqli_query($koneksi, 
    "SELECT * FROM peminjaman WHERE id='$pinjaman_id' AND user_id='$user_id'"));

if (!$pinjaman) {
    header('Location: riwayat.php');
    exit;
}

$tanggal_dikembalikan = date('Y-m-d');

mysqli_query($koneksi, "UPDATE peminjaman SET 
    status='dikembalikan',
    tanggal_dikembalikan='$tanggal_dikembalikan'
    WHERE id='$pinjaman_id'");

header('Location: detail_buku.php?id=' . $buku_id . '&kembali=success');
exit;
?>