<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}
if (!isset($_SESSION['id']) || !isset($_SESSION['user'])) {
    echo "ID atau username user tidak ditemukan di session!";
    exit;
}

$id_user = $_SESSION['id'];
$username = $_SESSION['user'];

include '../koneksi.php';
include '../assets/templates/navbar.php';

// Ambil email dari database dan simpan ke session
$query_user = $conn->query("SELECT email FROM users WHERE id = '$id_user' LIMIT 1");
if ($query_user && $query_user->num_rows > 0) {
    $data = $query_user->fetch_assoc();
    $_SESSION['email'] = $data['email'];
} else {
    $_SESSION['email'] = '-';
}


?>
<?php if (isset($_GET['msg']) && $_GET['msg'] == 'hapus'): ?>
    <div class="alert alert-warning">
        Pengajuan berhasil dibatalkan.
    </div>
<?php endif; ?>


<div class="container-fluid px-4 mt-4">
    <h3>Pinjam Barang</h3>
    <div class="card">
        <div class="card-header">
            Isi Form Barang
        </div>
        <div class="card-body mt-2">
            <form action="../proses/proses_pengajuan.php" method="post">
                <label for="item">Pilih Barang</label>
                <select class="form-control" name="item" required>
                    <?php
                    $query = $conn->query('SELECT * FROM master_barang');
                    while ($result = $query->fetch_array(MYSQLI_ASSOC)):
                    ?>
                        <option value="<?= $result['id'] ?>"><?= $result['nama'] ?></option>
                    <?php endwhile; ?>
                </select><br>

                <label for="">Jumlah Pengambilan</label>
                <input class="form-control" type="number" name="jumlah" min="1" required><br>

                <input type="hidden" name="email_pengambil" value="<?= $_SESSION['email'] ?>">

                <input type="hidden" name="pengambil" value="<?= $username ?>">
                <input class="btn btn-success" type="submit" value="Ajukan Pinjam">
            </form>

            <?php
            $q_pengajuan = $conn->query("
                SELECT p.kode_keluaran, b.nama, p.jumlah, p.status_pengajuan, p.tanggal_pengajuan 
                FROM barang_keluar p 
                JOIN master_barang b ON p.id_barang = b.id 
                WHERE p.nama_pengambil = '$username'
                ORDER BY p.tanggal_pengajuan DESC
            ");
            ?>
            <h5 class="mt-4">Status Pengajuan Barang</h5>
            <table class="table table-bordered">
                <thead class="text-center">
                    <tr >
                        <th>Kode</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Status</th>
                        <th>Aksi</th>

                    </tr>
                </thead>
                <tbody>
                    <?php while ($p = $q_pengajuan->fetch_assoc()): ?>
                        <tr class="text-center">
                            <td><?= $p['kode_keluaran'] ?></td>
                            <td><?= $p['nama'] ?></td>
                            <td><?= $p['jumlah'] ?></td>
                            <td><?= $p['tanggal_pengajuan'] ?></td>
                            <td>
                                <?php
                                if ($p['status_pengajuan'] == 'pending') echo '<span class="badge bg-warning">Pending</span>';
                                elseif ($p['status_pengajuan'] == 'disetujui') echo '<span class="badge bg-success">Disetujui</span>';
                                else echo '<span class="badge bg-danger">Ditolak</span>';
                                ?>
                            </td>
                            <td>
                                <?php if ($p['status_pengajuan'] === 'pending'): ?>
                                    <a href="../proses/batal_pengajuan.php?id=<?= $p['kode_keluaran'] ?>"
                                        onclick="return confirm('Yakin ingin membatalkan pengajuan ini?')"
                                        class="btn btn-danger btn-sm">Batal</a>
                                <?php elseif ($p['status_pengajuan'] === 'disetujui'): ?>
                                    <span class="text-success">Disetujui - tidak bisa dibatalkan</span>
                                <?php elseif ($p['status_pengajuan'] === 'ditolak'): ?>
                                    <span class="text-danger">Ditolak</span>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="container-fluid px-4 mt-4">
    <div class="card">
        <div class="card-header">Riwayat Barang Keluar</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="text-center">
                    <tr>
                        <th>Kode Pengambilan</th>
                        <th>Barang yang diambil</th>
                        <th>Jumlah Pengambilan</th>
                        <th>Nama Pengambil</th>
                        <th>Tanggal Pengambilan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query2 = $conn->query("
                        SELECT p.kode_keluaran, b.nama, p.jumlah, p.nama_pengambil, p.tanggal_pengambil 
                        FROM barang_keluar p 
                        JOIN master_barang b ON p.id_barang = b.id 
                        WHERE p.status_pengajuan = 'disetujui' AND p.tanggal_pengambil IS NOT NULL
                        ORDER BY p.tanggal_pengambil DESC
                    ");
                    while ($res = $query2->fetch_array(MYSQLI_ASSOC)):
                    ?>
                        <tr class="text-center">
                            <td><?= $res['kode_keluaran'] ?></td>
                            <td><?= $res['nama'] ?></td>
                            <td><?= $res['jumlah'] ?></td>
                            <td><?= $res['nama_pengambil'] ?></td>
                            <td><?= $res['tanggal_pengambil'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../assets/templates/footer.php'; ?>