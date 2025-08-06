<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

include '../koneksi.php';

// Ambil data dari form
$id_barang = $_POST['item'];
$jumlah = $_POST['jumlah'];
$nama_pengambil = $_POST['pengambil'];
$tanggal_pengambilan = $_POST['tgl'];

// Ambil stok lama dari database
$sql_stok = $conn->query("SELECT stok FROM master_barang WHERE id=$id_barang");
$stok_lama = $sql_stok->fetch_array(MYSQLI_ASSOC);

// Periksa apakah stok cukup
if ($jumlah <= $stok_lama['stok']) {
    // Hitung stok baru setelah barang keluar
    $stok_baru = $stok_lama['stok'] - $jumlah;

    // Masukkan data ke tabel barang_keluar
    $sql = "INSERT INTO barang_keluar VALUES (NULL, '$id_barang', '$jumlah', '$nama_pengambil', '$tanggal_pengambilan')";
    $result = $conn->query($sql);

    // Update stok di tabel master_barang
    $sql2 = "UPDATE master_barang SET stok='$stok_baru' WHERE id='$id_barang'";
    $result2 = $conn->query($sql2);

    // Redirect jika proses berhasil
    if ($result && $result2) {
        header("Location: http://localhost/DataBarang/user/barang_keluar.php");
        exit;
    } else {
        // Tampilkan error jika query gagal
        echo "Terjadi kesalahan saat menyimpan data.";
        echo "<br>Query 1: $sql";
        echo "<br>Query 2: $sql2";
    }
} else {
    // Redirect kembali jika stok tidak mencukupi
    echo "<script>alert('Stok tidak mencukupi!'); window.location.href='http://localhost/DataBarang/user/barang_keluar.php';</script>";
}
?>
