<?php
require __DIR__ . '/../koneksi.php';

// Tidak perlu session_start() di sini jika sudah dipanggil dari file lain

function catat_log($aktivitas) {
    global $conn;   

    $id_user = $_SESSION['id'] ?? null;
    if (!$id_user) return;

    $stmt = $conn->prepare("INSERT INTO log_aktivitas (user_id, aktivitas, waktu) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $id_user, $aktivitas);
    $stmt->execute();
}
