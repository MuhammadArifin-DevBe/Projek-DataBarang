<?php
session_start();

require_once '../admin/function.php'; // Untuk mencatat log
require_once '../koneksi.php';         // Untuk koneksi database

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit;
}

// Catat log sebelum aksi
catat_log("Membatalkan pengajuan peminjaman");

$id = $_GET['id'];
$query = "DELETE FROM barang_keluar WHERE kode_keluaran = '$id' AND status_pengajuan = 'pending'";
$result = mysqli_query($conn, $query);

if ($result) {
    header("Location: ../user/barang_keluar.php?msg=hapus");
} else {
    echo "Gagal membatalkan pengajuan.";
}
?>
