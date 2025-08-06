<?php
session_start();
require '../koneksi.php';

$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");

include '../assets/templates/navbar.php';
?>

<div class="container mt-5">
    <h2 class="mb-4">Manajemen User</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($row = mysqli_fetch_assoc($users)) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><span class="badge bg-<?= $row['role'] === 'admin' ? 'primary' : 'secondary' ?>">
                                <?= ucfirst($row['role']) ?></span></td>
                        <td class="text-center">
                            <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="delete_user.php?id=<?= $row['id'] ?>"class="btn btn-sm btn-danger" onclick="return confirm('Yakin mau hapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../assets/templates/footer.php'; ?>