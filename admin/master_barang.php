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

        <!-- Filter -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <!-- Dropdown Kategori -->
                    <div class="col-md-4">
                        <label for="kategori" class="form-label">Kategori Barang</label>
                        <select class="form-select" name="kategori" id="kategori">
                            <option value="">-- Semua Kategori --</option>
                            <?php
                            $kategori_q = $conn->query("SELECT DISTINCT kategori_barang FROM master_barang WHERE kategori_barang IS NOT NULL ORDER BY kategori_barang ASC");
                            while ($k = $kategori_q->fetch_assoc()):
                                $selected = (isset($_GET['kategori']) && $_GET['kategori'] == $k['kategori_barang']) ? 'selected' : '';
                            ?>
                                <option value="<?= htmlspecialchars($k['kategori_barang']) ?>" <?= $selected ?>>
                                    <?= ucwords($k['kategori_barang']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- Dropdown Jenis -->
                    <div class="col-md-4">
                        <label for="jenis" class="form-label">Jenis Barang</label>
                        <select class="form-select" name="jenis" id="jenis">
                            <option value="">-- Semua Jenis --</option>
                            <option value="habis pakai" <?= (isset($_GET['jenis']) && $_GET['jenis'] == 'habis pakai') ? 'selected' : '' ?>>Habis pakai</option>
                            <option value="tidak habis pakai" <?= (isset($_GET['jenis']) && $_GET['jenis'] == 'tidak habis pakai') ? 'selected' : '' ?>>Tidak habis pakai</option>
                        </select>
                    </div>

                    <!-- Dropdown Kondisi -->
                    <div class="col-md-4">
                        <label for="kondisi" class="form-label">Kondisi Barang</label>
                        <select class="form-select" name="kondisi" id="kondisi">
                            <option value="">-- Semua Kondisi --</option>
                            <option value="baik" <?= (isset($_GET['kondisi']) && $_GET['kondisi'] == 'baik') ? 'selected' : '' ?>>Baik</option>
                            <option value="rusak ringan" <?= (isset($_GET['kondisi']) && $_GET['kondisi'] == 'rusak ringan') ? 'selected' : '' ?>>Rusak Ringan</option>
                            <option value="rusak berat" <?= (isset($_GET['kondisi']) && $_GET['kondisi'] == 'rusak berat') ? 'selected' : '' ?>>Rusak Berat</option>
                        </select>
                    </div>

                    <!-- Dropdown Status -->
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status Barang</label>
                        <select class="form-select" name="status" id="status">
                            <option value="">-- Semua Status --</option>
                            <option value="aktif" <?= (isset($_GET['status']) && $_GET['status'] == 'aktif') ? 'selected' : '' ?>>Aktif</option>
                            <option value="dipinjam" <?= (isset($_GET['status']) && $_GET['status'] == 'dipinjam') ? 'selected' : '' ?>>Dipinjam</option>
                            <option value="diperbaiki" <?= (isset($_GET['status']) && $_GET['status'] == 'diperbaiki') ? 'selected' : '' ?>>Diperbaiki</option>
                            <option value="rusak total" <?= (isset($_GET['status']) && $_GET['status'] == 'rusak total') ? 'selected' : '' ?>>Rusak Total</option>
                        </select>
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="master_barang.php" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel -->
        <div class="card">
            <div class="card-header">
                List Data Barang
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="text-center">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Jenis Barang</th>
                            <th>Stok</th>
                            <th>Satuan</th>
                            <th>Harga Barang</th>
                            <th>Tanggal Beli</th>
                            <th>Diperoleh</th>
                            <th>Kondisi</th>
                            <th>Status</th>
                            <th>Gambar</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $where = [];
                        if (!empty($_GET['kategori'])) {
                            $kategori = mysqli_real_escape_string($conn, $_GET['kategori']);
                            $where[] = "kategori_barang = '$kategori'";
                        }
                        if (!empty($_GET['jenis'])) {
                            $jenis = mysqli_real_escape_string($conn, $_GET['jenis']);
                            $where[] = "jenis_barang = '$jenis'";
                        }
                        if (!empty($_GET['kondisi'])) {
                            $kondisi = mysqli_real_escape_string($conn, $_GET['kondisi']);
                            $where[] = "kondisi = '$kondisi'";
                        }
                        if (!empty($_GET['status'])) {
                            $status = mysqli_real_escape_string($conn, $_GET['status']);
                            $where[] = "status = '$status'";
                        }

                        $sql = "SELECT * FROM master_barang";
                        if ($where) {
                            $sql .= " WHERE " . implode(" AND ", $where);
                        }
                        $sql .= " ORDER BY nama ASC";

                        $query = $conn->query($sql);
                        if ($query->num_rows > 0):
                            while ($row = $query->fetch_assoc()):
                        ?>
                                <tr class="text-center">
                                    <td><?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['nama']) ?></td>
                                    <td><?= ucwords($row['kategori_barang']) ?></td>
                                    <td><?= ucwords($row['jenis_barang']) ?></td>
                                    <td><?= $row['stok'] ?></td>
                                    <td><?= htmlspecialchars($row['satuan']) ?></td>
                                    <td>Rp.<?= number_format($row['harga_brg'], 0, ',', '.') ?></td>
                                    <td><?= $row['tgl_beli'] ?></td>
                                    <td><?= htmlspecialchars($row['diprlh_dri']) ?></td>
                                    <td><?= ucwords($row['kondisi']) ?></td>
                                    <td><?= ucwords($row['status']) ?></td>
                                    <td>
                                        <?php if (!empty($row['gambar'])): ?>
                                            <img src="../assets/img/<?= htmlspecialchars($row['gambar']) ?>" style="width:90px; height:90px;" alt="Gambar Barang">
                                        <?php else: ?>
                                            <span>Tidak ada gambar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="display: flex; gap: 5px;">
                                        <a href="../proses/edit_data.php?id=<?= $row['id'] ?>" class="btn btn-dark btn-sm">Edit</a>
                                        <a href="../proses/delete.php?id=<?= $row['id'] ?>" class="btn btn-dark btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                    </td>
                                </tr>
                        <?php
                            endwhile;
                        else:
                            echo "<tr><td colspan='13' class='text-center'>Data tidak ditemukan.</td></tr>";
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include '../assets/templates/footer.php'; ?>