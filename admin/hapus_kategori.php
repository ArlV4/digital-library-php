<?php
include '../config/koneksi.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$id = $_GET['id'];
mysqli_query($koneksi, "DELETE FROM kategori WHERE id='$id'");
header('Location: tambah_kategori.php');
exit;
?>