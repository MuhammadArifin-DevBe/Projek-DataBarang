<?php
include '../koneksi.php';

// Tangkap data dari form
$nama = $_POST['nama'];
$stok = $_POST['stok'];
$satuan = $_POST['satuan'];
$kategori_barang = $_POST['kategori_barang'];
$jenis_barang = $_POST['jenis_barang'];

// Proses upload gambar
$gambar_name = $_FILES['gambar']['name'];
$gambar_tmp = $_FILES['gambar']['tmp_name'];
$ext = pathinfo($gambar_name, PATHINFO_EXTENSION);
$gambar_baru = uniqid() . '.' . $ext;
$upload_path = '../assets/img/' . $gambar_baru;

// Upload gambar dan simpan ke database
if (move_uploaded_file($gambar_tmp, $upload_path)) {
    $query = "INSERT INTO master_barang (nama, stok, satuan, gambar, kategori_barang, jenis_barang) 
              VALUES ('$nama', '$stok', '$satuan', '$gambar_baru', '$kategori_barang', '$jenis_barang')";

    if ($conn->query($query)) {
        echo "<script>alert('Berhasil Menambahkan!'); window.location.href = '../admin/master_barang.php';</script>";
        exit;
    } else {
        echo "Gagal menyimpan data: " . $conn->error;
    }
} else {
    echo "Upload gambar gagal!";
}
?>
