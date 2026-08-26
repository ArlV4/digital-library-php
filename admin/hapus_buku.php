<?php
include '../config/koneksi.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$id   = $_GET['id'];
$buku = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM buku WHERE id='$id'"));

// Hapus data peminjaman terkait dulu
mysqli_query($koneksi, "DELETE FROM peminjaman WHERE buku_id='$id'");

// Hapus file cover & pdf dari folder
if ($buku['cover']) {
    @unlink('../assets/img/' . $buku['cover']);
}
if ($buku['file_pdf']) {
    @unlink('../assets/pdf/' . $buku['file_pdf']);
}

// Baru hapus bukunya
mysqli_query($koneksi, "DELETE FROM buku WHERE id='$id'");
header('Location: data_buku.php');
exit;
?>