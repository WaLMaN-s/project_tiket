<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/session.php';

// Ambil data tiket dari database
$query_tiket = mysqli_query($conn, "SELECT * FROM tiket WHERE status='tersedia' ORDER BY harga ASC");
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
            <p class="hero-date">29 NOVEMBER 2025</p>
            <p style="font-size: 1.2rem; margin-bottom: 2rem;">
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
                        
                        <?php if(isLoggedIn() && isUser()): ?>
                            <a href="user/detail_tiket.php?id=<?= $tiket['id'] ?>" class="btn btn-book">
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