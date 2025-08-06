<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include '../koneksi.php'; // Pindahkan ini ke atas sebelum mysqli_query
include '../assets/templates/navbar.php';

// Ambil data pengajuan yang statusnya pending
$query = mysqli_query($conn, "SELECT bk.*, mb.nama AS nama_barang 
    FROM barang_keluar bk 
    JOIN master_barang mb ON bk.id_barang = mb.id 
    WHERE bk.status_pengajuan = 'pending'
    ORDER BY bk.tanggal_pengajuan DESC");
?>


<div class="container-fluid px-4 mt-4">
    <h3>Pengajuan User</h3>
    <div class="card mt-4">
        <div class="card-header">
           Konfirmasi Pengajuan Barang
        </div>
        <div class="container-fluid px-4 mt-4">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Nama Pengambil</th>
                            <th>Email</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        while ($row = mysqli_fetch_assoc($query)): ?>
                            <tr class="text-center">
                                <td><?= $no++ ?></td>
                                <td><?= $row['nama_barang'] ?></td>
                                <td><?= $row['jumlah'] ?></td>
                                <td><?= $row['nama_pengambil'] ?: '-' ?></td>
                                <td><?= $row['email_pengambil'] ?: '-' ?></td>
                                <td><?= $row['tanggal_pengajuan'] ?></td>
                                <td>
                                    <a href="approve.php?id=<?= $row['kode_keluaran'] ?>&aksi=setujui" class="btn btn-success btn-sm">Setujui</a>
                                    <a href="approve.php?id=<?= $row['kode_keluaran'] ?>&aksi=tolak" class="btn btn-danger btn-sm" onclick="return confirm('Tolak pengajuan ini?')">Tolak</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include '../assets/templates/footer.php'; ?>