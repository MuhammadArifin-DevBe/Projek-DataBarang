<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit;
}

require '../koneksi.php';
require_once '../admin/function.php';

$id_barang = $_POST['item'];
$jumlah = (int) $_POST['jumlah'];
$pengambil = $_POST['pengambil'];
$email = $_POST['email_pengambil'];
$tanggal_pengajuan = date('Y-m-d H:i:s');

// Cek stok
$query_stok = $conn->query("SELECT stok, nama FROM master_barang WHERE id = '$id_barang' LIMIT 1");

if ($query_stok && $query_stok->num_rows > 0) {
    $data = $query_stok->fetch_assoc();
    $stok_tersedia = (int) $data['stok'];
    $nama_barang = $data['nama'];

    if ($stok_tersedia <= 0) {
        header("Location: ../user/barang_keluar.php?status=error&msg=stok_kosong&barang=" . urlencode($nama_barang));
        exit;
    }

    if ($jumlah > $stok_tersedia) {
        header("Location: ../user/barang_keluar.php?status=error&msg=melebihi_stok&barang=" . urlencode($nama_barang));
        exit;
    }

    // Simpan pengajuan
    $query = "INSERT INTO barang_keluar (id_barang, jumlah, nama_pengambil, email_pengambil, tanggal_pengajuan, status_pengajuan)
              VALUES ('$id_barang', '$jumlah', '$pengambil', '$email', '$tanggal_pengajuan', 'pending')";

    if ($conn->query($query)) {
        catat_log("Mengajukan peminjaman barang $nama_barang ($jumlah)");
        header("Location: ../user/barang_keluar.php?status=sukses&msg=berhasil_ajukan");
        exit;
    } else {
        header("Location: ../user/barang_keluar.php?status=error&msg=gagal_insert");
        exit;
    }

} else {
    header("Location: ../user/barang_keluar.php?status=error&msg=barang_tidak_ditemukan");
    exit;
}
