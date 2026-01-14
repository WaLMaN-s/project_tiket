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
    <title>TIKET KONSER FEATS - Music Festival</title>
    
    <!-- Bootstrap CSS - FIXED URL (no extra spaces) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome - FIXED URL (no extra spaces) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Global Styles */
    :root {
    --purple-dark: #0f0c29;
    --purple-mid: #1e1b4b;
    --purple-light: #312e81;
    --gradient-primary: linear-gradient(135deg, #8b5cf6 0%, #a855f7 50%, #ec4899 100%);
    --gradient-vvip: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
    --gradient-vip: linear-gradient(135deg, #16e732 0%, #1774df 100%);
    --gradient-festival: linear-gradient(135deg, #e2b902 0%, #cc9d1a 100%);
    --text-primary: #e5e7eb;
    --text-secondary: #9ca3af;
    --border-color: rgba(168, 85, 247, 0.3);
    --shadow-primary: 0 4px 20px rgba(168, 85, 247, 0.3);
    --shadow-hover: 0 8px 30px rgba(168, 85, 247, 0.6);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--purple-dark) 0%, #160e6d 50%, #24243e 100%);
            color: #e5e7eb;
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Navbar */
        .navbar {
            background: rgba(15, 12, 41, 0.98) !important;
            box-shadow: var(--shadow-primary);
            padding: 1rem 0;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(139, 92, 246, 0.2);
            z-index: 1000;
        }
        
        .navbar-brand {
            font-size: 1.8rem;
            font-weight: bold;
            color: #f9fafb !important;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .navbar-brand span {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .nav-link {
            color: #d1d5db !important;
            margin: 0 10px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 1px;
            position: relative;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient-primary);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover {
            color: #c084fc !important;
            transform: translateY(-2px);
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        /* Hero Section */
        .hero-section {
            background-image: linear-gradient(rgba(15, 12, 41, 0.85), rgba(48, 43, 99, 0.85)), 
                  url('https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?w=1920&q=80');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .hero-title {
            font-size: 4rem;
            font-weight: 900;
            letter-spacing: 8px;
            background: linear-gradient(135deg, #ffffff 0%, #c084fc 50%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
            text-shadow: 0 0 20px rgba(139, 92, 246, 0.5);
        }
        
        .hero-subtitle {
            font-size: 2.2rem;
            letter-spacing: 6px;
            margin-bottom: 1rem;
            color: #e5e7eb;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.8);
        }
        
        .hero-date {
            font-size: 1.8rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }
        
        .hero-info {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            color: #e5e7eb;
            line-height: 1.8;
        }
        
        .hero-info i {
            color: #c084fc;
            margin-right: 8px;
        }
        
        /* Buttons */
        .btn-custom {
            background: var(--gradient-primary);
            border: none;
            color: #ffffff;
            font-weight: 700;
            padding: 12px 35px;
            font-size: 1.1rem;
            box-shadow: var(--shadow-primary);
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-radius: 50px;
        }
        
        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(139, 92, 246, 0.6);
        }
        
        .btn-book {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            color: white;
            font-weight: 700;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: none;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .btn-book:hover {
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(139, 92, 246, 0.4);
        }
        
        /* Sections */
        .section-title {
            position: relative;
            background: linear-gradient(135deg, #ffffff 0%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: none;
            margin-bottom: 3rem;
            text-align: center;
            font-size: 3rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
        
        /* Countdown */
        .countdown-container {
            max-width: 600px;
            margin: 0 auto;
        }
        
        .countdown-box {
            background: rgba(30, 27, 75, 0.7);
            border: 2px solid rgba(139, 92, 246, 0.5);
            border-radius: 12px;
            padding: 12px 8px;
            min-width: 70px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.2);
            transition: all 0.3s ease;
        }
        
        .countdown-box:hover {
            background: rgba(139, 92, 246, 0.15);
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(139, 92, 246, 0.3);
        }
        
        .countdown-number {
            font-size: 2.2rem;
            font-weight: bold;
            color: #ff6b6b;
            display: block;
            text-shadow: 0 0 10px rgba(255, 107, 107, 0.5);
        }
        
        .countdown-label {
            font-weight: bold;
            font-size: 0.9rem;
            color: #a78bfa;
            margin-top: 4px;
            display: block;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Ticket Section */
        .ticket-section {
            background: linear-gradient(135deg, var(--purple-mid) 0%, var(--purple-light) 50%, #1f2937 100%);
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }
        
        .ticket-card {
            background: rgba(30, 27, 75, 0.85);
            border: 2px solid var(--border-color);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            transition: all 0.4s ease;
            backdrop-filter: blur(5px);
        }
        
        .ticket-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 40px rgba(139, 92, 246, 0.4);
            border-color: rgba(139, 92, 246, 0.6);
        }
        
        .badge-vvip {
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
            color: #ffffff;
            font-weight: 800;
            padding: 6px 14px;
            border-radius: 10px;
            display: inline-block;
            font-size: 1rem;
        }
        
        .badge-vip {
            background: linear-gradient(135deg, #16e732 0%, #1774df 100%);
            color: #ffffff;
            font-weight: 800;
            padding: 6px 14px;
            border-radius: 10px;
            display: inline-block;
            font-size: 1rem;
        }
        
        .badge-festival {
            background: linear-gradient(135deg, #e2b902 0%, #cc9d1a 100%);
            color: #ffffff;
            font-weight: 800;
            padding: 6px 14px;
            border-radius: 10px;
            display: inline-block;
            font-size: 1rem;
        }
        
        .ticket-price {
            font-size: 2.2rem;
            font-weight: bold;
            background: linear-gradient(135deg, #ffcc00 0%, #ff9900 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 15px 0;
        }
        
        .ticket-info i {
            color: #c084fc;
            margin-right: 8px;
        }
        
        
#terms {
    background: linear-gradient(135deg, var(--purple-mid) 0%, var(--purple-light) 50%, #1f2937 100%);
    padding: 100px 0;
    position: relative;
    overflow: hidden;
}

.terms-header {
    text-align: center;
    margin-bottom: 50px;
}

.terms-header h2 {
    font-size: 3.2rem;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.terms-header p {
    color: #cbd5e1;
    max-width: 600px;
    margin: 15px auto 0;
    font-size: 1.2rem;
}

.terms-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-top: 40px;
}

.terms-card {
    background: rgba(255, 255, 255, 0.08);
    border-radius: 20px;
    padding: 35px;
    transition: all 0.4s ease;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(139, 92, 246, 0.2);
    position: relative;
    overflow: hidden;
}

.terms-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: var(--gradient-primary);
}

.terms-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3),
                0 0 30px rgba(139, 92, 246, 0.3);
    border-color: rgba(139, 92, 246, 0.5);
}

.terms-icon {
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(139, 92, 246, 0.15);
    border-radius: 18px;
    margin: 0 auto 25px;
    transition: all 0.3s ease;
}

.terms-icon i {
    font-size: 2.2rem;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.terms-card:hover .terms-icon {
    transform: scale(1.1);
    background: rgba(139, 92, 246, 0.25);
}

.terms-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #c084fc;
    margin-bottom: 20px;
    text-align: center;
}

.terms-list {
    list-style: none;
    padding-left: 0;
}

.terms-list li {
    padding: 12px 0 12px 25px;
    position: relative;
    line-height: 1.6;
    color: #cbd5e1;
    border-bottom: 1px dashed rgba(139, 92, 246, 0.2);
}

.terms-list li:last-child {
    border-bottom: none;
}

.terms-list li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 15px;
    width: 12px;
    height: 12px;
    background: var(--gradient-primary);
    border-radius: 50%;
    box-shadow: 0 0 8px rgba(139, 92, 246, 0.5);
}

.terms-list li:hover {
    color: white;
    transform: translateX(5px);
}

.terms-decoration {
    position: absolute;
    z-index: 0;
    pointer-events: none;
}

.terms-decoration-1 {
    top: 15%;
    right: 5%;
    width: 80px;
    height: 80px;
    border: 2px solid rgba(139, 92, 246, 0.3);
    border-radius: 50%;
}

.terms-decoration-2 {
    bottom: 20%;
    left: 5%;
    width: 60px;
    height: 60px;
    border: 2px solid rgba(236, 72, 153, 0.3);
    border-radius: 50%;
    opacity: 0.6;
}

/* Responsive */
@media (max-width: 768px) {
    .terms-header h2 {
        font-size: 2.5rem;
    }
    
    .terms-cards {
        grid-template-columns: 1fr;
    }
}
.footer {
    background: #0f172a;
    color: #ffffff;
    padding: 40px 20px;
    text-align: center;
}

.footer-content {
    max-width: 900px;
    margin: auto;
}

.footer-title {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 20px;
}

/* Menu */
.footer-menu {
    list-style: none;
    padding: 0;
    margin: 0 0 20px;
    display: flex;
    justify-content: center;
    gap: 25px;
    flex-wrap: wrap;
}

.footer-menu a {
    color: #cbd5e1;
    text-decoration: none;
    font-weight: 500;
}

.footer-menu a:hover {
    color: #38bdf8;
}

/* Kontak */
.footer-contact p {
    margin: 5px 0;
    font-size: 14px;
    color: #e5e7eb;
}

.footer-contact i {
    margin-right: 8px;
    color: #38bdf8;
}

/* Sosial Media */
.footer-social {
    margin: 20px 0;
}

.footer-social a {
    color: #ffffff;
    font-size: 20px;
    margin: 0 10px;
    transition: 0.3s;
}

.footer-social a:hover {
    color: #38bdf8;
}

/* Website & Copyright */
.footer-web {
    font-size: 14px;
    color: #cbd5e1;
    margin-bottom: 10px;
}

.footer-copy {
    font-size: 13px;
    color: #94a3b8;
}
.foto-section {
      height: 100vh; /* Full viewport height */
      overflow: hidden;
    }

.foto-section img {
      width: 100%;
      height: 100%;
      object-fit: cover; /* Menjaga rasio gambar tanpa distorsi */
      display: block;
    }
    .foto-section img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center 62%; /* default: 50% (tengah). Naikkan angka >50% untuk geser fokus ke bawah */
}

    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                TIKET KONSER <span>FEATS</span>
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
                    <li class="nav-item">
                        <a class="nav-link" href="#terms">Syarat & Ketentuan</a>
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
        <div class="hero-content text-center">
            <h1 class="hero-title">TIKET KONSER FEATS</h1>
            <p class="hero-subtitle">GRAND MUSIC FESTIVAL</p>
            <p class="hero-date">14 February 2026</p>
            
            <!-- Countdown Timer -->
            <div class="countdown-container my-4">
                <div class="countdown-label text-white mb-2">Sisa Waktu Menuju Konser</div>
                <div id="countdown" class="d-flex justify-content-center gap-3 flex-wrap">
                    <div class="countdown-box">
                        <span class="countdown-number" id="days">00</span>
                        <span class="countdown-label">Hari</span>
                    </div>
                    <div class="countdown-box">
                        <span class="countdown-number" id="hours">00</span>
                        <span class="countdown-label">Jam</span>
                    </div>
                    <div class="countdown-box">
                        <span class="countdown-number" id="minutes">00</span>
                        <span class="countdown-label">Menit</span>
                    </div>
                    <div class="countdown-box">
                        <span class="countdown-number" id="seconds">00</span>
                        <span class="countdown-label">Detik</span>
                    </div>
                </div>
            </div>

            <p class="hero-info">
                <i class="fas fa-map-marker-alt"></i> GBK SENAYAN, JAKARTA<br>
                <i class="fas fa-clock"></i> 19.30 WIB - SELESAI
            </p>
            <a href="#tickets" class="btn btn-custom">
                <i class="fas fa-ticket-alt me-2"></i> DAFTAR HARGA TIKET
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
                        
                        <div class="ticket-desc text-secondary">
                            <?= nl2br(htmlspecialchars($tiket['deskripsi'])) ?>
                        </div>
                        
                        <div class="ticket-info mt-3">
                            <div class="mb-2">
                                <i class="fas fa-calendar me-2"></i>
                                <?= formatTanggal($tiket['tanggal_event']) ?>
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-clock me-2"></i>
                                <?= formatWaktu($tiket['waktu_event']) ?>
                            </div>
                            <div>
                                <i class="fas fa-ticket-alt me-2"></i>
                                Stok: <span class="text-success fw-bold"><?= $tiket['stok'] ?></span> tiket
                            </div>
                        </div>
                        
                        <?php if(isLoggedIn() && isUser()): ?>
                            <a href="user/pesan_tiket.php?id=<?= $tiket['id'] ?>" class="btn btn-book mt-3 w-100">
                                <i class="fas fa-shopping-cart me-2"></i> PESAN SEKARANG
                            </a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-book mt-3 w-100">
                                <i class="fas fa-sign-in-alt me-2"></i> LOGIN UNTUK PESAN
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-ticket-alt" style="font-size: 4rem; color: #8b5cf6;"></i>
                    <h4 class="mt-3 text-white">Tiket Belum Tersedia</h4>
                    <p class="text-muted mt-2">Segera hadir! Stay tuned untuk pembelian tiket.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Terms & Conditions Section -->
     <!-- Section hanya berisi foto -->
  <section class="foto-section">
    <img src="assets/image/base.jpg" alt="Foto utama">
  </section>
    <!-- Terms & Conditions Section - MODERN 3-COLUMN LAYOUT -->
<section id="terms">
    <div class="container">
        <div class="terms-header">
            <h2>SYARAT & KETENTUAN</h2>
            <p>Harap baca syarat dan ketentuan berikut sebelum melakukan pemesanan</p>
        </div>
        
        <div class="terms-decorations">
            <div class="terms-decoration terms-decoration-1"></div>
            <div class="terms-decoration terms-decoration-2"></div>
        </div>
        
        <div class="terms-cards">
            <!-- Card 1 -->
            <div class="terms-card">
                <div class="terms-icon">
                    <i class="fas fa-umbrella"></i>
                </div>
                <h3 class="terms-title">Cuaca & Force Majeure</h3>
                <ul class="terms-list">
                    <li>Trip dapat ditunda/dibatalkan jika cuaca buruk, kapal tidak diizinkan beroperasi, atau terjadi force majeure</li>
                    <li>Penyelenggara tidak bertanggung jawab atas biaya tambahan di luar paket (hotel tambahan, tiket, dll)</li>
                    <li>Refund menyesuaikan kebijakan internal & kondisi</li>
                </ul>
            </div>
            
            <!-- Card 2 -->
            <div class="terms-card">
                <div class="terms-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h3 class="terms-title">Tanggung Jawab Peserta</h3>
                <ul class="terms-list">
                    <li>Wajib mengikuti instruksi guide demi keselamatan</li>
                    <li>Peserta bertanggung jawab atas barang pribadi masing-masing</li>
                    <li>Kerusakan fasilitas akibat kelalaian peserta akan dikenakan biaya penggantian</li>
                </ul>
            </div>
            
            <!-- Card 3 -->
            <div class="terms-card">
                <div class="terms-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <h3 class="terms-title">Kontak & Informasi</h3>
                <ul class="terms-list">
                    <li>Semua pemesanan dan pertanyaan dilakukan melalui kontak resmi penyelenggara</li>
                    <li>Harap membaca detail paket masing-masing sebelum melakukan DP (Down Payment)</li>
                    <li>Untuk pertanyaan lebih lanjut, hubungi customer service kami</li>
                </ul>
            </div>
        </div>
        
        <div class="text-center mt-5">
            <a href="https://wa.me/081266520389" class="btn btn-custom" target="_blank">
                <i class="fas fa-whatsapp me-2"></i> Chat WhatsApp
            </a>
        </div>
    </div>
</section>

    <!-- Footer -->
  <footer class="footer">
    <div class="container footer-content">

        <!-- Judul -->
        <h4 class="footer-title">🎵 TIKET KONSER FEATS</h4>

        <!-- Menu Footer -->
        <ul class="footer-menu">
            <li><a href="#home">Home</a></li>
            <li><a href="#tickets">Tickets</a></li>
            <li><a href="#terms">Syarat & Ketentuan</a></li>
        </ul>

        <!-- Kontak -->
        <div class="footer-contact">
            <p>
                <i class="fas fa-envelope"></i>
                support@tiketkonserfeats.com
            </p>
            <p>
                <i class="fas fa-phone"></i>
                +62 812-3456-7890
            </p>
        </div>

        <!-- Sosial Media -->
        <div class="footer-social">
            <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" title="TikTok"><i class="fab fa-tiktok"></i></a>
            <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
        </div>

        <!-- Website -->
        <p class="footer-web">
            <i class="fas fa-globe"></i> www.tiketkonserfeats.com
        </p>

        <!-- Copyright -->
        <p class="footer-copy">
            &copy; 2026 TIKET KONSER FEATS Music Festival. All Rights Reserved.
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
    
    <!-- Countdown Timer -->
    <script>
        const targetDate = new Date('2026-02-14T19:30:00+07:00');

        function updateCountdown() {
            const now = new Date();
            const diff = targetDate - now;

            if (diff <= 0) {
                document.getElementById('countdown').innerHTML = 
                    '<div class="text-center text-danger fw-bold fs-4">🎉 KONSER SEDANG BERLANGSUNG!</div>';
                return;
            }

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            document.getElementById('days').textContent = String(days).padStart(2, '0');
            document.getElementById('hours').textContent = String(hours).padStart(2, '0');
            document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
            document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    </script>
</body>
</html>