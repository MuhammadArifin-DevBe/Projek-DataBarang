<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit;
}

require '../koneksi.php';
require_once '../admin/function.php'; // pastikan catat_log bisa diakses

$id_barang = $_POST['item'];
$jumlah = $_POST['jumlah'];
$pengambil = $_POST['pengambil'];
$email = $_POST['email_pengambil'];
$tanggal_pengajuan = date('Y-m-d H:i:s');

// Simpan ke database
$query = "INSERT INTO barang_keluar (id_barang, jumlah, nama_pengambil, email_pengambil, tanggal_pengajuan)
          VALUES ('$id_barang', '$jumlah', '$pengambil', '$email', '$tanggal_pengajuan')";

if ($conn->query($query)) {
    catat_log("Mengajukan peminjaman barang");
    header("Location: ../user/barang_keluar.php?msg=sukses");
    exit;
} else {
    echo "Gagal menyimpan pengajuan: " . $conn->error;
}
?>
