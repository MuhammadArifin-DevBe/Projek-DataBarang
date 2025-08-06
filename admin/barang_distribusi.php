    <?php
    session_start();
    if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
        header("Location: login.php");
        exit;
    }

    include '../koneksi.php';
    include '../assets/templates/navbar.php';

    // Ambil data dari tabel barang_keluar + join ke master_barang
    $query2 = $conn->query("SELECT 
        bk.kode_keluaran, 
        mb.nama AS nama_barang, 
        bk.jumlah, 
        bk.nama_pengambil, 
        bk.email_pengambil, 
        bk.tanggal_pengajuan, 
        bk.status_pengajuan 
    FROM barang_keluar bk
    JOIN master_barang mb ON bk.id_barang = mb.id
    ORDER BY bk.kode_keluaran DESC");
    ?>

    <div class="container-fluid px-4 mt-4">
        <h3>Riwayat Distribusi Peminjaman Barang</h3>
        <div class="card mt-4">
            <div class="card-header">Daftar Pengajuan</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="text-center">
                        <tr>
                            <th>Kode Keluar</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Nama Peminjam</th>
                            <th>Email</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($res = $query2->fetch_array(MYSQLI_ASSOC)) : ?>
                            <tr class="text-center">
                                <td><?= htmlspecialchars($res['kode_keluaran']) ?></td>
                                <td><?= htmlspecialchars($res['nama_barang']) ?></td>
                                <td><?= htmlspecialchars($res['jumlah']) ?></td>
                                <td><?= htmlspecialchars($res['nama_pengambil']) ?></td>
                                <td><?= htmlspecialchars($res['email_pengambil']) ?></td>
                                <td><?= htmlspecialchars($res['tanggal_pengajuan']) ?></td>
                                <td>
                                    <?php
                                    if ($res['status_pengajuan'] === 'disetujui') {
                                        echo "<span class='badge bg-success'>Disetujui</span>";
                                    } elseif ($res['status_pengajuan'] === 'ditolak') {
                                        echo "<span class='badge bg-danger'>Ditolak</span>";
                                    } else {
                                        echo "<span class='badge bg-warning text-dark'>Menunggu</span>";
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include '../assets/templates/footer.php'; ?>
