<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

requireAdmin();

$query = mysqli_query($conn, "SELECT * FROM tiket ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Tiket - Admin PENTAS.HUB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                ADMIN - PENTAS.<span>HUB</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="data_tiket.php">Data Tiket</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="data_pesanan.php">Data Pesanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="data_user.php">Data User</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Lihat Website</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="dashboard-container" style="margin-top: 80px;">
        <div class="container-fluid">
            <div class="dashboard-header">
                <h2>
                    <i class="fas fa-ticket-alt"></i> Data Tiket
                </h2>
                <a href="tambah_tiket.php" class="btn btn-custom">
                    <i class="fas fa-plus"></i> Tambah Tiket Baru
                </a>
            </div>

            <div class="card-custom">
                <div class="table-responsive">
                    <table class="table table-custom table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Jenis Tiket</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Tanggal Event</th>
                                <th>Waktu</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($tiket = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td>#<?= $tiket['id'] ?></td>
                                <td>
                                    <?php if($tiket['jenis_tiket'] == 'VVIP'): ?>
                                        <span class="badge-vvip"><?= $tiket['jenis_tiket'] ?></span>
                                    <?php elseif($tiket['jenis_tiket'] == 'VIP'): ?>
                                        <span class="badge-vip"><?= $tiket['jenis_tiket'] ?></span>
                                    <?php else: ?>
                                        <span class="badge-festival"><?= $tiket['jenis_tiket'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= formatRupiah($tiket['harga']) ?></td>
                                <td>
                                    <strong style="color: <?= $tiket['stok'] > 50 ? '#00ff00' : ($tiket['stok'] > 0 ? '#ff9900' : '#ff0000') ?>">
                                        <?= $tiket['stok'] ?>
                                    </strong>
                                </td>
                                <td><?= formatTanggal($tiket['tanggal_event']) ?></td>
                                <td><?= formatWaktu($tiket['waktu_event']) ?></td>
                                <td><?= $tiket['lokasi'] ?></td>
                                <td>
                                    <?php if($tiket['status'] == 'tersedia'): ?>
                                        <span class="badge bg-success">Tersedia</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Habis</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="edit_tiket.php?id=<?= $tiket['id'] ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="hapus_tiket.php?id=<?= $tiket['id'] ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Yakin ingin menghapus tiket ini?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>