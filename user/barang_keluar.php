<?php
session_start();
require '../koneksi.php';
include '../assets/templates/navbar.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'user') {
    header('Location: ../login.php');
    exit;
}

$username = $_SESSION['user'];
$email = $_SESSION['email'] ?? '';
?>

<div class="container-fluid px-4 mt-4">

    <!-- ALERT NOTIFIKASI -->
    <?php
    if (isset($_GET['status'], $_GET['msg'])) {
        $status = $_GET['status'];
        $msg = $_GET['msg'];
        $barang = htmlspecialchars($_GET['barang'] ?? '');

        $alertClass = match ($status) {
            'sukses' => 'alert-success',
            'error' => 'alert-danger',
            default => 'alert-warning'
        };

        $message = match ($msg) {
            'stok_kosong' => "Stok barang <strong>$barang</strong> kosong. Tidak bisa melakukan pengajuan.",
            'melebihi_stok' => "Jumlah pengajuan melebihi stok tersedia untuk <strong>$barang</strong>.",
            'barang_tidak_ditemukan' => "Barang tidak ditemukan.",
            'gagal_insert' => "Gagal menyimpan pengajuan ke database.",
            'berhasil_ajukan' => "Pengajuan barang berhasil diajukan.",
            'hapus' => "Pengajuan berhasil dibatalkan.",
            default => ''
        };

        if (!empty($message)) {
            echo "<div id='alertBox' class='alert $alertClass alert-dismissible fade show' role='alert'>
                    $message
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }
    }
    ?>

    <!-- JAVASCRIPT ALERT AUTO HIDE -->
    <script>
        setTimeout(() => {
            const alertBox = document.getElementById('alertBox');
            if (alertBox) {
                alertBox.classList.remove('show');
                alertBox.classList.add('fade');
                setTimeout(() => alertBox.remove(), 500);
            }
        }, 3000);
    </script>

    <h3>Pinjam Barang</h3>
    <div class="card mb-4">
        <div class="card-header">Isi Form Barang</div>
        <div class="card-body">
            <form action="../proses/proses_pengajuan.php" method="post">
                <div class="mb-3">
                    <label for="item" class="form-label">Pilih Barang</label>
                    <select class="form-control" name="item" id="item" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php
                        $query = $conn->query("SELECT * FROM master_barang");
                        while ($result = $query->fetch_assoc()) {
                            echo "<option value='{$result['id']}'>
                                    {$result['nama']} ({$result['jenis_barang']} | {$result['kategori_barang']})
                                  </option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="jumlah" class="form-label">Jumlah Pengambilan</label>
                    <input class="form-control" type="number" name="jumlah" id="jumlah" min="1" required>
                </div>

                <input type="hidden" name="email_pengambil" value="<?= htmlspecialchars($email) ?>">
                <input type="hidden" name="pengambil" value="<?= htmlspecialchars($username) ?>">

                <button class="btn btn-success" type="submit">Ajukan Pinjam</button>
            </form>
        </div>
    </div>

    <!-- STATUS PENGAJUAN -->
    <h5>Status Pengajuan Barang</h5>
    <?php
    $q_pengajuan = $conn->query("
        SELECT p.kode_keluaran, b.nama, b.jenis_barang, b.kategori_barang, p.jumlah, p.status_pengajuan, p.tanggal_pengajuan 
        FROM barang_keluar p 
        JOIN master_barang b ON p.id_barang = b.id 
        WHERE p.nama_pengambil = '$username'
        ORDER BY p.tanggal_pengajuan DESC
    ");
    ?>
    <table class="table table-bordered table-striped">
        <thead class="text-center">
            <tr>
                <th>Kode</th>
                <th>Barang</th>
                <th>Jenis</th>
                <th>Kategori</th>
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
                    <td><?= $p['jenis_barang'] ?></td>
                    <td><?= $p['kategori_barang'] ?></td>
                    <td><?= $p['jumlah'] ?></td>
                    <td><?= $p['tanggal_pengajuan'] ?></td>
                    <td>
                        <?= match ($p['status_pengajuan']) {
                            'pending' => '<span class="badge bg-warning">Pending</span>',
                            'disetujui' => '<span class="badge bg-success">Disetujui</span>',
                            default => '<span class="badge bg-danger">Ditolak</span>'
                        } ?>
                    </td>
                    <td>
                        <?php if ($p['status_pengajuan'] === 'pending'): ?>
                            <a href="../proses/batal_pengajuan.php?id=<?= $p['kode_keluaran'] ?>"
                               onclick="return confirm('Yakin ingin membatalkan pengajuan ini?')"
                               class="btn btn-danger btn-sm">Batal</a>
                        <?php elseif ($p['status_pengajuan'] === 'disetujui'): ?>
                            <span class="text-success">Disetujui - tidak bisa dibatalkan</span>
                        <?php else: ?>
                            <span class="text-danger">Ditolak</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- RIWAYAT BARANG KELUAR -->
    <div class="card mt-4">
        <div class="card-header">Riwayat Barang Keluar</div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="text-center">
                    <tr>
                        <th>Kode</th>
                        <th>Barang</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Nama Pengambil</th>
                        <th>Tanggal Pengambilan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query2 = $conn->query("
                        SELECT p.kode_keluaran, b.nama, b.jenis_barang, b.kategori_barang, p.jumlah, p.nama_pengambil, p.tanggal_pengambil 
                        FROM barang_keluar p 
                        JOIN master_barang b ON p.id_barang = b.id 
                        WHERE p.status_pengajuan = 'disetujui' AND p.tanggal_pengambil IS NOT NULL
                        ORDER BY p.tanggal_pengambil DESC
                    ");
                    while ($res = $query2->fetch_assoc()):
                    ?>
                        <tr class="text-center">
                            <td><?= $res['kode_keluaran'] ?></td>
                            <td><?= $res['nama'] ?></td>
                            <td><?= $res['jenis_barang'] ?></td>
                            <td><?= $res['kategori_barang'] ?></td>
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
