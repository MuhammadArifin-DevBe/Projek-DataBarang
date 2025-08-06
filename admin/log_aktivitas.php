<?php
session_start();
require '../koneksi.php';

$result = mysqli_query($conn, "
    SELECT la.*, u.username 
    FROM log_aktivitas la 
    JOIN users u ON la.user_id = u.id 
    ORDER BY la.waktu DESC
");

include '../koneksi.php';
include '../assets/templates/navbar.php';
?>

    <div class="container mt-5">
        <h2>Log Aktivitas</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Aksi</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['aktivitas']) ?></td>
                        <td><?= $row['waktu'] ?></td>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>
    <?php include '../assets/templates/footer.php'; ?>
