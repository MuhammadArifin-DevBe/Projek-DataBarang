<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once '../koneksi.php'; // pastikan ini set $conn

// Pastikan form disubmit
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin/master_barang.php');
    exit;
}

// Ambil dan sanitasi input
$nama            = trim($_POST['nama'] ?? '');
$kategori_barang = trim($_POST['kategori_barang'] ?? '');
$jenis_barang    = trim($_POST['jenis_barang'] ?? '');
$stok            = (int) ($_POST['stok'] ?? 0);
$satuan          = trim($_POST['satuan'] ?? '');
$harga_brg       = trim($_POST['harga_brg'] ?? '');
$kondisi         = trim($_POST['kondisi'] ?? '');
$status          = trim($_POST['status'] ?? '');
$diprlh_dri      = trim($_POST['diprlh_dri'] ?? '');
$tgl_beli = $_POST['tgl_beli'];


// Validasi sederhana (tambahkan sesuai kebutuhan)
$errors = [];
if ($nama === '') $errors[] = 'Nama barang harus diisi.';
if ($kategori_barang === '') $errors[] = 'Kategori harus dipilih.';
if ($jenis_barang === '') $errors[] = 'Jenis harus dipilih.';

// handle gambar: bisa file upload atau URL (jika kamu ubah form ke text URL)
$gambar_baru = ''; // nama yang disimpan di DB (kosong jika tidak ada)
if (!empty($_FILES['gambar']) && isset($_FILES['gambar']['tmp_name']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
    // Direktori upload (absolute path)
    $uploadDir = __DIR__ . '/../assets/img/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $gambar_name = $_FILES['gambar']['name'];
    $gambar_tmp  = $_FILES['gambar']['tmp_name'];
    $gambar_err  = $_FILES['gambar']['error'];
    $ext = strtolower(pathinfo($gambar_name, PATHINFO_EXTENSION));

    // Validasi upload error
    if ($gambar_err !== UPLOAD_ERR_OK) {
        $errors[] = 'Terjadi masalah saat upload gambar (error code: ' . $gambar_err . ').';
    }

    // Validasi ekstensi
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($ext, $allowed)) {
        $errors[] = 'Format gambar tidak diperbolehkan. Gunakan: ' . implode(', ', $allowed);
    }

    // Buat nama file unik
    if (empty($errors)) {
        try {
            $uniq = bin2hex(random_bytes(6));
        } catch (Exception $e) {
            $uniq = uniqid();
        }
        $gambar_baru = $uniq . '.' . $ext;
        $targetPath = $uploadDir . $gambar_baru;

        if (!move_uploaded_file($gambar_tmp, $targetPath)) {
            $errors[] = 'Gagal memindahkan file gambar ke folder tujuan.';
        } else {
            // optional: set permission
            @chmod($targetPath, 0644);
        }
    }
} else {
    // Jika form diubah jadi menerima URL (text), tangkapnya di $_POST['gambar_url']
    if (!empty($_POST['gambar_url'])) {
        $gambar_baru = trim($_POST['gambar_url']);
    }
}

// Kalau ada error, redirect kembali dengan pesan (atau tampilkan)
if (!empty($errors)) {
    $msg = urlencode(implode(' | ', $errors));
    header("Location: ../admin/master_barang.php?status=error&msg={$msg}");
    exit;
}

// Insert ke database (prepared statement)
$sql = "INSERT INTO master_barang 
    (nama, stok, satuan, gambar, jenis_barang, kategori_barang, harga_brg, kondisi, status, diprlh_dri, tgl_beli)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    header("Location: ../admin/master_barang.php?status=error&msg=" . urlencode('Prepare statement gagal: ' . $conn->error));
    exit;
}

// Bind params: s = string, i = integer
// urutan: nama(s), stok(i), satuan(s), gambar(s), jenis(s), kategori(s), harga(s), kondisi(s), status(s), diprlh_dri(s)
$stmt->bind_param(
    'sisssssssss',
    $nama,
    $stok,
    $satuan,
    $gambar_baru,
    $jenis_barang,
    $kategori_barang,
    $harga_brg,
    $kondisi,
    $status,
    $diprlh_dri,
    $tgl_beli
);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: ../admin/master_barang.php");
    exit;
} else {
    $err = $stmt->error;
    $stmt->close();
    header("Location: ../admin/master_barang.php?status=error&msg=" . urlencode('Gagal menyimpan: ' . $err));
    exit;
}
