<?php
session_start();
require '../koneksi.php';

// Pastikan hanya admin yang bisa hapus
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Validasi ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo "<div class='alert alert-danger text-center mt-5'>ID tidak valid.</div>";
    exit;
}

// Cek apakah user dengan ID itu ada
$check = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
if (mysqli_num_rows($check) === 0) {
    echo "<div class='alert alert-warning text-center mt-5'>User tidak ditemukan.</div>";
    exit;
}

// Lakukan penghapusan
mysqli_query($conn, "DELETE FROM users WHERE id = $id");

// Redirect kembali ke halaman manajemen
header("Location: manajemen_user.php?msg=deleted");
exit;
