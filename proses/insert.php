<?php
include '../koneksi.php';

$id = $_POST['id'];
$nama = $_POST['nama'];
$stok = $_POST['stok'];
$satuan = $_POST['satuan'];

// Proses Upload Gambar
$gambar_name = $_FILES['gambar']['name'];
$gambar_tmp = $_FILES['gambar']['tmp_name'];

// Bikin nama file unik biar gak tabrakan
$ext = pathinfo($gambar_name, PATHINFO_EXTENSION);
$gambar_baru = uniqid() . '.' . $ext;
$upload_path = '../assets/img/' . $gambar_baru;

// Upload file ke folder
if (move_uploaded_file($gambar_tmp, $upload_path)) {
    // Simpan ke database
    $query = "INSERT INTO master_barang (id, nama, stok, satuan, gambar) 
              VALUES ('$id', '$nama', '$stok', '$satuan', '$gambar_baru')";

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
