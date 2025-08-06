<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$id = $_GET['id'];
$aksi = $_GET['aksi'];

if ($aksi === 'setujui') {
    // Ambil data pengajuan
    $data = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT * FROM barang_keluar WHERE kode_keluaran = '$id'
    "));

    if ($data) {
        $id_barang = $data['id_barang'];
        $jumlah = $data['jumlah'];
        $admin = $_SESSION['user']; // Atau nama admin

        // Update status dan tanggal_pengambil
        mysqli_query($conn, "
            UPDATE barang_keluar 
            SET status_pengajuan = 'disetujui',
                tanggal_pengambil = NOW()
            WHERE kode_keluaran = '$id'
        ");

        // Insert ke barang_distribusi
        mysqli_query($conn, "
            INSERT INTO barang_distribusi (id_barang, jumlah_pemasukan, admin, tanggal_distribusi)
            VALUES ('$id_barang', '$jumlah', '$admin', NOW())
        ");

        // Kurangi stok master_barang
        mysqli_query($conn, "
            UPDATE master_barang 
            SET stok = stok - $jumlah 
            WHERE id = '$id_barang'
        ");
    }
} elseif ($aksi === 'tolak') {
    // Tolak pengajuan
    mysqli_query($conn, "
        UPDATE barang_keluar 
        SET status_pengajuan = 'ditolak' 
        WHERE kode_keluaran = '$id'
    ");
}

header('Location: pengajuan_masuk.php');
exit;
