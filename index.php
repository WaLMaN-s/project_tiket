<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/session.php';

// Ambil data tiket dari database dengan prepared statement
$stmt = $conn->prepare("SELECT * FROM tiket WHERE status='tersedia' AND stok > 0 ORDER BY harga ASC");
$stmt->execute();
$result_tiket = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PENTAS.HUB - Music Festival</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Background Image CSS - Inline untuk kemudahan path -->
    <style>
        .hero-section {
            background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                  url('https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                PENTAS.<span>HUB</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tickets">Tickets</a>
                    </li>
                    <?php if(isLoggedIn()): ?>
                        <?php if(isAdmin()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin/dashboard.php">Dashboard Admin</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="user/dashboard.php">My Tickets</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">PENTAS.HUB</h1>
            <p class="hero-subtitle">MUSIC FESTIVAL</p>
            <p class="hero-date">15 Desember 2025</p>
            <p class="hero-info">
                <i class="fas fa-map-marker-alt"></i> GBK SENAYAN, JAKARTA<br>
                <i class="fas fa-clock"></i> 19.30 WIB - SELESAI
            </p>
            <a href="#tickets" class="btn btn-custom btn-lg">
                <i class="fas fa-ticket-alt"></i> DAFTAR HARGA TIKET
            </a>
        </div>
    </section>

    <!-- Tickets Section -->
    <section id="tickets" class="ticket-section">
        <div class="container">
            <h2 class="section-title">DAFTAR HARGA TIKET</h2>
            
            <?php if($result_tiket->num_rows > 0): ?>
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
                        
                        <?php if(isLoggedIn() && isUser()): ?>
                            <a href="user/pesan_tiket.php?id=<?= $tiket['id'] ?>" class="btn btn-book">
                                <i class="fas fa-shopping-cart"></i> PESAN SEKARANG
                            </a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-book">
                                <i class="fas fa-sign-in-alt"></i> LOGIN UNTUK PESAN
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-ticket-alt" style="font-size: 4rem; color: #8b00ff;"></i>
                    <h4 class="mt-3" style="color: white;">Tiket Belum Tersedia</h4>
                    <p class="text-muted">Segera hadir! Stay tuned untuk pembelian tiket.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 PENTAS.HUB Music Festival. All Rights Reserved.</p>
            <p>
                <i class="fas fa-globe"></i> www.feastkonser.com
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Smooth Scroll -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if(target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>