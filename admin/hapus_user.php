<?php
include '../config/koneksi.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$id = $_GET['id'];

// Tidak boleh hapus diri sendiri
if ($id == $_SESSION['user_id']) {
    header('Location: data_user.php');
    exit;
}

// Hapus data peminjaman & unblock request terkait dulu
mysqli_query($koneksi, "DELETE FROM peminjaman WHERE user_id='$id'");
mysqli_query($koneksi, "DELETE FROM unblock_request WHERE user_id='$id'");

// Baru hapus usernya
mysqli_query($koneksi, "DELETE FROM users WHERE id='$id'");
header('Location: data_user.php');
exit;
?>