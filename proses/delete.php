<?php
include '../koneksi.php';

$id = $_GET['id'];
$sql = "delete from master_barang where id='$id'";

$result = $conn->query($sql);
if($result === true)
{
    echo "<script>alert('berhasil menghapus data!')</script>";
    header('location:http://localhost/DataBarang/admin/master_barang.php');
}
else
{
    echo "Error : " . $conn->error;
}

?>