<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

requireUser();

$user_id = $_SESSION['user_id'];

// Ambil data tiket yang tersedia
$query_tiket = mysqli_query($conn, "SELECT * FROM tiket WHERE status='tersedia' ORDER BY harga ASC");

// Hitung total pesanan user
$query_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM pesanan WHERE user_id='$user_id'");
$count = mysqli_fetch_assoc($query_count);
$total_pesanan = $count['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User - PENTAS.HUB</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css"
    
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
                <p class="mb-0">Selamat datang, <strong><?= $_SESSION['nama'] ?></strong></p>
            </div>

            <!-- Stats -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card-custom">
                        <h4><i class="fas fa-ticket-alt"></i> Total Tiket</h4>
                        <h2 style="color: #8b00ff;"><?= $total_pesanan ?></h2>
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

            <div class="row">
                <?php while($tiket = mysqli_fetch_assoc($query_tiket)): ?>
                <div class="col-md-4">
                    <div class="ticket-card">
                        <div class="ticket-type">
                            <?php if($tiket['jenis_tiket'] == 'VVIP'): ?>
                                <span class="badge-vvip"><?= $tiket['jenis_tiket'] ?></span>
                            <?php elseif($tiket['jenis_tiket'] == 'VIP'): ?>
                                <span class="badge-vip"><?= $tiket['jenis_tiket'] ?></span>
                            <?php else: ?>
                                <span class="badge-festival"><?= $tiket['jenis_tiket'] ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="ticket-price">
                            <?= formatRupiah($tiket['harga']) ?>
                        </div>
                        
                        <div class="ticket-desc">
                            <?= nl2br($tiket['deskripsi']) ?>
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
                        
                        <a href="detail_tiket.php?id=<?= $tiket['id'] ?>" class="btn btn-book">
                            <i class="fas fa-shopping-cart"></i> PESAN SEKARANG
                        </a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>