<?php
include '../koneksi.php';

    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $stok = $_POST['stok'];
    $satuan = $_POST['satuan'];
    $gambar = $_POST['gambar'];
    
    $sql = "UPDATE master_barang SET nama='$nama', stok='$stok', satuan='$satuan', gambar='$gambar' WHERE id='$id';";

    $result = $conn->query($sql);
    
    if ($result === true) {
        echo "<script>alert('Berhasil mengedit data!');</script>";
        header('Location: http://localhost/DataBarang/admin/master_barang.php');
    } else {
        echo "Error: " . $conn->error;
    }
?>