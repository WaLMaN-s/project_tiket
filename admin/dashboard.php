<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

requireAdmin();

// Statistik dengan prepared statements
// Total Tiket
$stmt_tiket = $conn->prepare("SELECT COUNT(*) as total FROM tiket");
$stmt_tiket->execute();
$total_tiket = $stmt_tiket->get_result()->fetch_assoc()['total'];

// Total User
$stmt_user = $conn->prepare("SELECT COUNT(*) as total FROM users WHERE role='user'");
$stmt_user->execute();
$total_user = $stmt_user->get_result()->fetch_assoc()['total'];

// Total Pesanan AKTIF (tidak termasuk yang dibatalkan)
$stmt_pesanan = $conn->prepare("SELECT COUNT(*) as total FROM pesanan WHERE status_pesanan != 'dibatalkan'");
$stmt_pesanan->execute();
$total_pesanan = $stmt_pesanan->get_result()->fetch_assoc()['total'];

// Hitung juga total pesanan dibatalkan untuk informasi
$stmt_batal = $conn->prepare("SELECT COUNT(*) as total FROM pesanan WHERE status_pesanan = 'dibatalkan'");
$stmt_batal->execute();
$total_dibatalkan = $stmt_batal->get_result()->fetch_assoc()['total'];

// Total Pendapatan (hanya dari pesanan berhasil, tidak termasuk yang dibatalkan)
$stmt_pendapatan = $conn->prepare("SELECT SUM(total_harga) as total FROM pesanan WHERE status_pesanan='berhasil'");
$stmt_pendapatan->execute();
$total_pendapatan = $stmt_pendapatan->get_result()->fetch_assoc()['total'] ?? 0;

// Pesanan terbaru (10 terakhir)
$stmt_list = $conn->prepare("SELECT p.*, u.nama, u.email, t.jenis_tiket 
                              FROM pesanan p 
                              JOIN users u ON p.user_id = u.id 
                              JOIN tiket t ON p.tiket_id = t.id 
                              ORDER BY p.tanggal_pesan DESC 
                              LIMIT 10");
$stmt_list->execute();
$result_pesanan = $stmt_list->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PENTAS.HUB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
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
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="data_tiket.php">Data Tiket</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="konfirmasi_pesanan.php">konfirmasi pesanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="data_pesanan.php">Data Pesanan</a>
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

    <div class="dashboard-admin" style="margin-top: 80px;">
        <div class="container-fluid">
            <div class="dashboard-header">
                <h2>
                    <i class="fas fa-tachometer-alt"></i> Dashboard Admin
                </h2>
                <p class="mb-0">Selamat datang, <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong></p>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card-custom">
                        <h4><i class="fas fa-ticket-alt"></i> Total Tiket</h4>
                        <h2 style="color: #8b00ff;"><?= $total_tiket ?></h2>
                        <small class="text-muted">Semua jenis tiket</small>
                        <a href="data_tiket.php" class="btn btn-sm btn-custom mt-2">Lihat Detail</a>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card-custom">
                        <h4><i class="fas fa-users"></i> Total User</h4>
                        <h2 style="color: #00ff00;"><?= $total_user ?></h2>
                        <small class="text-muted">User terdaftar</small>
                        <a href="data_user.php" class="btn btn-sm btn-custom mt-2">Lihat Detail</a>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card-custom">
                        <h4><i class="fas fa-shopping-cart"></i> Pesanan Aktif</h4>
                        <h2 style="color: #ff00ff;"><?= $total_pesanan ?></h2>
                        <small class="text-muted">
                            Aktif: <?= $total_pesanan ?> | 
                            <span class="text-danger">Dibatalkan: <?= $total_dibatalkan ?></span>
                        </small>
                        <a href="data_pesanan.php" class="btn btn-sm btn-custom mt-2">Lihat Detail</a>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card-custom">
                        <h4><i class="fas fa-money-bill-wave"></i> Total Pendapatan</h4>
                        <h2 style="color: #ffd700;"><?= formatRupiah($total_pendapatan) ?></h2>
                        <small class="text-muted">Dari pesanan berhasil</small>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="card-custom">
                <h3 class="mb-4">
                    <i class="fas fa-list"></i> Pesanan Terbaru (10 Terakhir)
                </h3>

                <?php if($result_pesanan->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-custom table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Nama Pemesan</th>
                                <th>Email</th>
                                <th>Tiket</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($pesanan = $result_pesanan->fetch_assoc()): ?>
                            <tr>
                                <td>#<?= $pesanan['id'] ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($pesanan['tanggal_pesan'])) ?></td>
                                <td><?= htmlspecialchars($pesanan['nama']) ?></td>
                                <td><?= htmlspecialchars($pesanan['email']) ?></td>
                                <td>
                                    <?php if($pesanan['jenis_tiket'] == 'VVIP'): ?>
                                        <span class="badge-vvip"><?= htmlspecialchars($pesanan['jenis_tiket']) ?></span>
                                    <?php elseif($pesanan['jenis_tiket'] == 'VIP'): ?>
                                        <span class="badge-vip"><?= htmlspecialchars($pesanan['jenis_tiket']) ?></span>
                                    <?php else: ?>
                                        <span class="badge-festival"><?= htmlspecialchars($pesanan['jenis_tiket']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $pesanan['jumlah_tiket'] ?> tiket</td>
                                <td><?= formatRupiah($pesanan['total_harga']) ?></td>
                                <td>
                                    <?php if($pesanan['status_pesanan'] == 'berhasil'): ?>
                                        <span class="badge bg-success">Berhasil</span>
                                    <?php elseif($pesanan['status_pesanan'] == 'pending'): ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox" style="font-size: 4rem; color: #8b00ff;"></i>
                        <h4 class="mt-3">Belum ada pesanan</h4>
                        <p class="text-muted">Pesanan akan muncul di sini</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>