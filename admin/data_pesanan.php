<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

requireAdmin();

$query = mysqli_query($conn, "SELECT p.*, u.nama, u.email, t.jenis_tiket 
                               FROM pesanan p 
                               JOIN users u ON p.user_id = u.id 
                               JOIN tiket t ON p.tiket_id = t.id 
                               ORDER BY p.tanggal_pesan DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pesanan - Admin PENTAS.HUB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css"
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
                        <a class="nav-link" href="data_tiket.php">Data Tiket</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="data_pesanan.php">Data Pesanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="data_user.php">Data User</a>
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
                    <i class="fas fa-shopping-cart"></i> Data Pesanan
                </h2>
            </div>

            <div class="card-custom">
                <div class="table-responsive">
                    <table class="table table-custom table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal Pesan</th>
                                <th>Nama User</th>
                                <th>Email</th>
                                <th>Nama Pemesan</th>
                                <th>No. HP</th>
                                <th>Jenis Tiket</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($pesanan = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td>#<?= $pesanan['id'] ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($pesanan['tanggal_pesan'])) ?></td>
                                <td><?= $pesanan['nama'] ?></td>
                                <td><?= $pesanan['email'] ?></td>
                                <td><?= $pesanan['nama_pemesan'] ?></td>
                                <td><?= $pesanan['no_hp_pemesan'] ?></td>
                                <td>
                                    <?php if($pesanan['jenis_tiket'] == 'VVIP'): ?>
                                        <span class="badge-vvip"><?= $pesanan['jenis_tiket'] ?></span>
                                    <?php elseif($pesanan['jenis_tiket'] == 'VIP'): ?>
                                        <span class="badge-vip"><?= $pesanan['jenis_tiket'] ?></span>
                                    <?php else: ?>
                                        <span class="badge-festival"><?= $pesanan['jenis_tiket'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $pesanan['jumlah_tiket'] ?></td>
                                <td><?= formatRupiah($pesanan['total_harga']) ?></td>
                                <td>
                                    <?php if($pesanan['status_pesanan'] == 'berhasil'): ?>
                                        <span class="badge bg-success">Berhasil</span>
                                    <?php elseif($pesanan['status_pesanan'] == 'pending'): ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    <?php endif; ?>
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