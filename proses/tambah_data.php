<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../koneksi.php';
include '../assets/templates/navbar.php';

// ENUM dari database
$kategori_options = ['elektronik', 'non elektronik'];
$jenis_options    = ['habis pakai', 'tidak habis pakai'];
$kondisi_options  = ['baik', 'rusak ringan', 'rusak berat'];
$status_options   = ['aktif', 'dipinjam', 'diperbaiki', 'rusak total'];
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

                <!-- Harga Barang -->
                <div class="mb-3">
                    <label for="harga_brg" class="form-label">Harga Barang</label>
                    <input type="text" name="harga_brg" id="harga_brg" class="form-control" placeholder="Contoh: 1500000" required>
                </div>

                <div class="mb-3">
                    <label for="tgl_beli" class="form-label">Tanggal Beli</label>
                    <input type="date" class="form-control" id="tgl_beli" name="tgl_beli" required>
                </div>

                <!-- Kondisi -->
                <div class="mb-3">
                    <label for="kondisi" class="form-label">Kondisi</label>
                    <select name="kondisi" id="kondisi" class="form-select" required>
                        <option value="">-- Pilih Kondisi --</option>
                        <?php foreach ($kondisi_options as $k): ?>
                            <option value="<?= $k ?>"><?= ucfirst($k) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Status -->
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="">-- Pilih Status --</option>
                        <?php foreach ($status_options as $s): ?>
                            <option value="<?= $s ?>"><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Diperoleh Dari -->
                <div class="mb-3">
                    <label for="diprlh_dri" class="form-label">Diperoleh Dari</label>
                    <input type="text" name="diprlh_dri" id="diprlh_dri" class="form-control" placeholder="Contoh: Hibah, Pembelian" required>
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