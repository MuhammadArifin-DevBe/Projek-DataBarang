<?php
include '../koneksi.php';

$id = $_POST['id'];
$nama = $_POST['nama'];
$stok = $_POST['stok'];
$satuan = $_POST['satuan'];
$kategori_barang = $_POST['kategori_barang'];
$jenis_barang = $_POST['jenis_barang'];

$gambar_baru = '';

// Cek apakah ada gambar baru diupload
if (!empty($_FILES['gambar']['name'])) {
    $gambar_name = $_FILES['gambar']['name'];
    $gambar_tmp = $_FILES['gambar']['tmp_name'];
    $ext = pathinfo($gambar_name, PATHINFO_EXTENSION);
    $gambar_baru = uniqid() . '.' . $ext;
    $upload_path = '../assets/img/' . $gambar_baru;

    move_uploaded_file($gambar_tmp, $upload_path);

    // Update dengan gambar baru
    $sql = "UPDATE master_barang 
            SET nama='$nama', stok='$stok', satuan='$satuan', gambar='$gambar_baru',
                kategori_barang='$kategori_barang', jenis_barang='$jenis_barang' 
            WHERE id='$id'";
} else {
    // Update tanpa ubah gambar
    $sql = "UPDATE master_barang 
            SET nama='$nama', stok='$stok', satuan='$satuan',
                kategori_barang='$kategori_barang', jenis_barang='$jenis_barang' 
            WHERE id='$id'";
}

$result = $conn->query($sql);

if ($result === true) {
    echo "<script>alert('Berhasil mengedit data!'); window.location.href = '../admin/master_barang.php';</script>";
} else {
    echo "Error: " . $conn->error;
}
?>
