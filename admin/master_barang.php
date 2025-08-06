    <?php
    session_start();
    if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
        header("Location: login.php");
        exit;
    }

    include '../koneksi.php';
    include '../assets/templates/navbar.php';
    ?>

    <div class="container-fluid px-4 mt-4">
        <h3>Stok Barang</h3>

        <a href="../proses/tambah_data.php" class="btn btn-dark mb-3">Tambah Data Barang</a>

        <div class="card">
            <div class="card-header">
                Tambahkan barang untuk data masuk
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="text-center">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Stok</th>
                            <th>Satuan</th>
                            <th>Gambar</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = $conn->query("SELECT * FROM master_barang");
                        while ($row = $query->fetch_assoc()):
                        ?>
                            <tr class="text-center">
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['nama'] ?></td>
                                <td><?= $row['stok'] ?></td>
                                <td><?= $row['satuan'] ?></td>
                                <td>
                                    <img src="../assets/img/<?= $row['gambar'] ?>" style="width:90px; height:90px;" alt="Gambar Barang">
                                </td>
                                <td>
                                    <a href="../proses/delete.php?id=<?= $row['id'] ?>" class="btn btn-dark btn-sm" onclick="return confirm('Yakin ingin menghapus data?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include '../assets/templates/footer.php'; ?>
