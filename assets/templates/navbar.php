<?php
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Aplikasi Sistem Invetaris</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../assets/templates/css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <div class="sb-sidenav-menu-heading">Akun</div>
        <h2 class="navbar-brand ps-2" href="index.php">Invetaris</h2>
        <ul class="navbar-nav ms-auto me-3">
            <li class="nav-item">
                <a class="nav-link text-white" href="../logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </nav>

        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                                <?php if ($role === 'admin'): ?>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-house-user"></i></div>
                                Dashboard
                            </a>
                            <div class="sb-sidenav-menu-heading">Pilihan</div>
                            <a class="nav-link" href="master_barang.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-file"></i></div>
                                Data Barang
                            </a>
                            <a class="nav-link" href="pengajuan_masuk.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-file-circle-check"></i></div>
                                Pengajuan User
                            </a>
                            <a class="nav-link" href="barang_distribusi.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                                Riwayat Distribusi
                            </a>
                            <a class="nav-link" href="log_aktivitas.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-chart-line"></i></div>
                                Log Aktivitas
                            </a>
                            <a class="nav-link" href="manajemen_user.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-gear"></i></div>
                                Data Pengguna
                            </a>
                            <a class="nav-link" href="laporan.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-folder"></i></div>
                                Daftar Laporan
                            </a>
                        <?php elseif ($role === 'user') : ?>
                            <a class="nav-link" href="dashboard_user.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-house-user"></i></div>
                                Halaman Utama
                            </a>
                            <a class="nav-link" href="barang_keluar.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Pinjam Barang
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </nav>
            </div>

        


        <div id="layoutSidenav_content">
            <main>