<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Ambil data
$data_pengajuan = mysqli_query($conn, "SELECT bk.*, mb.nama FROM barang_keluar bk JOIN master_barang mb ON bk.id_barang = mb.id ORDER BY bk.tanggal_pengajuan DESC");
$data_distribusi = mysqli_query($conn, "SELECT bd.*, mb.nama FROM barang_distribusi bd JOIN master_barang mb ON bd.id_barang = mb.id ORDER BY bd.tanggal_distribusi DESC");
$data_log = mysqli_query($conn, "SELECT la.*, u.username FROM log_aktivitas la JOIN users u ON la.user_id = u.id ORDER BY la.waktu DESC");
$data_barang = mysqli_query($conn, "SELECT * FROM master_barang ORDER BY id DESC");
$data_user = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");

include '../assets/templates/navbar.php';
?>

<div class="container mt-4">
    <h3 class="mb-4">Daftar Laporan</h3>
    <ul class="nav nav-tabs" id="laporanTabs" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pengajuan">Pengajuan</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#distribusi">Distribusi</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#log">Log Aktivitas</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#barang">Data Barang</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#user">Data Pengguna</button></li>
    </ul>

    <div class="tab-content border border-top-0 p-3">
        <!-- Tab Pengajuan -->
        <div class="tab-pane fade show active" id="pengajuan">
            <h5>Pengajuan Barang</h5>
            <a href="cetak_pengajuan.php" class="btn btn-primary mb-3" target="_blank">Cetak PDF</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Pengambil</th>
                        <th>Email</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    while ($row = mysqli_fetch_assoc($data_pengajuan)) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['nama'] ?></td>
                            <td><?= $row['jumlah'] ?></td>
                            <td><?= $row['nama_pengambil'] ?></td>
                            <td><?= $row['email_pengambil'] ?></td>
                            <td><?= $row['tanggal_pengajuan'] ?></td>
                            <td><?= $row['status_pengajuan'] ?></td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        </div>

        <!-- Tab Distribusi -->
        <div class="tab-pane fade" id="distribusi">
            <h5>Distribusi Barang</h5>
            <a href="cetak_pengajuan.php" class="btn btn-primary mb-3" target="_blank">Cetak PDF</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    while ($row = mysqli_fetch_assoc($data_distribusi)) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['nama'] ?></td>
                            <td><?= $row['jumlah'] ?></td>
                            <td><?= $row['tanggal_distribusi'] ?></td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        </div>

        <!-- Tab Log -->
        <div class="tab-pane fade" id="log">
            <h5>Log Aktivitas</h5>
            <a href="cetak_pengajuan.php" class="btn btn-primary mb-3" target="_blank">Cetak PDF</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Aktivitas</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    while ($row = mysqli_fetch_assoc($data_log)) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['username'] ?></td>
                            <td><?= $row['aktivitas'] ?></td>
                            <td><?= $row['waktu'] ?></td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        </div>

        <!-- Tab Barang -->
        <div class="tab-pane fade" id="barang">
            <h5>Data Barang</h5>
            <a href="cetak_data.php" class="btn btn-primary mb-3" target="_blank">Cetak PDF</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Jenis</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th>Harga Barang</th>
                        <th>Kondisi Barang</th>
                        <th>Status Barang</th>
                        <th>Barang Diperoleh Dari</th>
                        <th>Tanggal Beli</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    while ($row = mysqli_fetch_assoc($data_barang)) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['nama'] ?></td>
                            <td><?= $row['kategori_barang'] ?></td>
                            <td><?= $row['jenis_barang'] ?></td>
                            <td><?= $row['stok'] ?></td>
                            <td><?= $row['satuan'] ?></td>
                            <td>Rp.<?= number_format($row['harga_brg'], 0, ',', '.') ?></td>
                            <td><?= $row['kondisi'] ?></td>
                            <td><?= $row['status'] ?></td>
                            <td><?= $row['diprlh_dri'] ?></td>
                            <td><?= $row['tgl_beli'] ?></td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        </div>

        <!-- Tab User -->
        <div class="tab-pane fade" id="user">
            <h5>Data Pengguna</h5>
            <a href="cetak_pengajuan.php" class="btn btn-primary mb-3" target="_blank">Cetak PDF</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    while ($row = mysqli_fetch_assoc($data_user)) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['username'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= $row['role'] ?></td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../assets/templates/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>