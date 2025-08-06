<?php 
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include '../koneksi.php';

// Ambil data dari form
$id_barang = $_POST['item'];
$jumlah = $_POST['jumlah'];
$admin = $_POST['admin']; // Nama admin yang memasukkan barang
$tanggal_pemasukan = $_POST['tgl'];

// Ambil stok lama dari database
$sql_stok = $conn->query("SELECT stok FROM master_barang WHERE id = '$id_barang'");
$stok_lama = $sql_stok->fetch_array(MYSQLI_ASSOC);

// Cek apakah stok mencukupi
if ($stok_lama['stok'] < $jumlah) {
    echo "Stok tidak mencukupi! Stok tersedia: " . $stok_lama['stok'];
    exit;
}

// Hitung stok baru (stok dikurangi)
$stok_baru = $stok_lama['stok'] - $jumlah;

// Masukkan data ke tabel barang_masuk
$sql = "INSERT INTO barang_masuk VALUES (NULL, '$id_barang', '$jumlah', '$admin', '$tanggal_pemasukan')";
$result = $conn->query($sql);

// Update stok pada tabel master_barang
$sql2 = "UPDATE master_barang SET stok = '$stok_baru' WHERE id = '$id_barang'";
$result2 = $conn->query($sql2);

// Cek hasil query dan redirect
if ($result && $result2) {
    header("Location: http://localhost/DataBarang/admin/barang_masuk.php");
    exit;
} else {
    echo "Terjadi kesalahan: " . $conn->error;
    echo "<br>Query: $sql";
    echo "<br>Query2: $sql2";
}
?>
