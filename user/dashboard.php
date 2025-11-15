<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

requireUser();

$user_id = $_SESSION['user_id'];

// Ambil data tiket yang tersedia dengan prepared statement
$stmt = $conn->prepare("SELECT * FROM tiket WHERE status='tersedia' AND stok > 0 ORDER BY harga ASC");
$stmt->execute();
$result_tiket = $stmt->get_result();

// Hitung total pesanan user YANG TIDAK DIBATALKAN
$stmt_count = $conn->prepare("SELECT COUNT(*) as total FROM pesanan WHERE user_id=? AND status_pesanan != 'dibatalkan'");
$stmt_count->bind_param("i", $user_id);
$stmt_count->execute();
$count = $stmt_count->get_result()->fetch_assoc();
$total_pesanan = $count['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User - PENTAS.HUB</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                PENTAS.<span>HUB</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                   
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">My Tickets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="riwayat.php">Riwayat Pesanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="dashboard-container" style="margin-top: 80px;">
        <div class="container">
            <!-- Header -->
            <div class="dashboard-header">
                <h2>
                    <i class="fas fa-user"></i> User
                </h2>
                <p class="mb-0">Selamat datang, <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong></p>
            </div>

            <!-- Stats -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card-custom">
                        <h4><i class="fas fa-ticket-alt"></i> Total Tiket Aktif</h4>
                        <h2 style="color: #8b00ff;"><?= $total_pesanan ?></h2>
                        <small class="text-muted">Pesanan yang berhasil</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-custom">
                        <h4><i class="fas fa-calendar"></i> Event Date</h4>
                        <h2 style="color: #8b00ff;">29 Nov 2025</h2>
                    </div>
                </div>
            </div>

            <!-- Daftar Tiket -->
            <h3 class="mb-4">
                <i class="fas fa-list"></i> Tiket Tersedia
            </h3>

            <?php if($result_tiket->num_rows == 0): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> 
                    Tidak ada tiket yang tersedia saat ini.
                </div>
            <?php else: ?>
            <div class="row">
                <?php while($tiket = $result_tiket->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="ticket-card">
                        <div class="ticket-type">
                            <?php if($tiket['jenis_tiket'] == 'VVIP'): ?>
                                <span class="badge-vvip"><?= htmlspecialchars($tiket['jenis_tiket']) ?></span>
                            <?php elseif($tiket['jenis_tiket'] == 'VIP'): ?>
                                <span class="badge-vip"><?= htmlspecialchars($tiket['jenis_tiket']) ?></span>
                            <?php else: ?>
                                <span class="badge-festival"><?= htmlspecialchars($tiket['jenis_tiket']) ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="ticket-price">
                            <?= formatRupiah($tiket['harga']) ?>
                        </div>
                        
                        <div class="ticket-desc">
                            <?= nl2br(htmlspecialchars($tiket['deskripsi'])) ?>
                        </div>
                        
                        <div class="ticket-info">
                            <div>
                                <i class="fas fa-calendar"></i>
                                <?= formatTanggal($tiket['tanggal_event']) ?>
                            </div>
                            <div>
                                <i class="fas fa-clock"></i>
                                <?= formatWaktu($tiket['waktu_event']) ?>
                            </div>
                        </div>
                        
                        <div class="ticket-info">
                            <div>
                                <i class="fas fa-ticket-alt"></i>
                                Stok: <?= $tiket['stok'] ?> tiket
                            </div>
                        </div>
                        
                        <a href="pesan_tiket.php?id=<?= $tiket['id'] ?>" class="btn btn-book">
                            <i class="fas fa-shopping-cart"></i> PESAN SEKARANG
                        </a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>