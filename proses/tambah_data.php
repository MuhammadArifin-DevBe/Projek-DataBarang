<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../koneksi.php';
include '../assets/templates/navbar.php';

// Sesuaikan ENUM dengan database
$kategori_options = ['elektronik', 'non elektronik'];
$jenis_options = ['habis pakai', 'tidak habis pakai'];
?>

<div class="container-fluid px-4 mt-4">
    <h3>Tambah Data Barang</h3>

    <div class="card">
        <div class="card-body">
            <form action="insert.php" method="POST" enctype="multipart/form-data">
                <!-- Nama -->
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Barang</label>
                    <input type="text" name="nama" id="nama" class="form-control" required>
                </div>

                <!-- Kategori Barang -->
                <div class="mb-3">
                    <label for="kategori_barang" class="form-label">Kategori Barang</label>
                    <select name="kategori_barang" id="kategori_barang" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($kategori_options as $kategori): ?>
                            <option value="<?= $kategori ?>"><?= ucfirst($kategori) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Jenis Barang -->
                <div class="mb-3">
                    <label for="jenis_barang" class="form-label">Jenis Barang</label>
                    <select name="jenis_barang" id="jenis_barang" class="form-select" required>
                        <option value="">-- Pilih Jenis --</option>
                        <?php foreach ($jenis_options as $jenis): ?>
                            <option value="<?= $jenis ?>"><?= ucfirst($jenis) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Stok -->
                <div class="mb-3">
                    <label for="stok" class="form-label">Stok</label>
                    <input type="number" name="stok" id="stok" class="form-control" min="0" required>
                </div>

                <!-- Satuan -->
                <div class="mb-3">
                    <label for="satuan" class="form-label">Satuan</label>
                    <input type="text" name="satuan" id="satuan" class="form-control" placeholder="Contoh: pcs / box / unit" required>
                </div>

                <!-- Gambar -->
                <div class="mb-3">
                    <label for="gambar" class="form-label">Gambar</label>
                    <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*" required>
                </div>

                <!-- Tombol -->
                <button type="submit" class="btn btn-dark">Simpan</button>
                <a href="../admin/master_barang.php" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>

<?php include '../assets/templates/footer.php'; ?>
