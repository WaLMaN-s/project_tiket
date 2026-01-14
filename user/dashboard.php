<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

requireUser();

$user_id = $_SESSION['user_id'];

// Ambil data tiket yang tersedia
$stmt = $conn->prepare("SELECT * FROM tiket WHERE status='tersedia' AND stok > 0 ORDER BY harga ASC");
$stmt->execute();
$result_tiket = $stmt->get_result();

// Hitung total pesanan aktif (status: dikonfirmasi)
$stmt_count = $conn->prepare("SELECT COUNT(*) as total FROM pesanan WHERE user_id = ? AND status_pesanan = 'dikonfirmasi'");
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
    <style>
        .card-clickable {
            cursor: pointer;
            transition: transform 0.3s;
        }
        .card-clickable:hover {
            transform: translateY(-5px);
        }
        .ticket-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 3px 8px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 0.8rem;
        }
        .badge-vvip-custom { background: linear-gradient(45deg, #f43f5e, #ec4899); color: white; }
    </style>
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
                        <a class="nav-link" href="../index.php">Home</a>
                    </li>
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
                    <div class="card-custom card-clickable" data-bs-toggle="modal" data-bs-target="#ticketModal">
                        <h4><i class="fas fa-ticket-alt"></i> Total Tiket Aktif</h4>
                        <h2 style="color: #8b00ff;"><?= $total_pesanan ?></h2>
                        <small class="text-muted">Klik untuk lihat detail tiket</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-custom">
                        <h4><i class="fas fa-calendar"></i> Event Date</h4>
                        <h2 style="color: #8b00ff;">14 Februari 2025</h2>
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
                        
                        <a href="user/pesan_tiket.php?id=<?= $tiket['id'] ?>" class="btn btn-book mt-3 w-100">
                                <i class="fas fa-shopping-cart me-2"></i> PESAN SEKARANG
                            </a>
                        </a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal Tiket Aktif -->
    <div class="modal fade" id="ticketModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark border border-purple">
                <div class="modal-header border-bottom border-purple">
                    <h5 class="modal-title text-purple">
                        <i class="fas fa-ticket-alt me-2"></i> Tiket Aktif Saya
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    // Ambil tiket aktif dengan status 'dikonfirmasi'
                    $stmt_active = $conn->prepare("
                        SELECT p.*, t.jenis_tiket, t.tanggal_event, t.waktu_event, t.deskripsi
                        FROM pesanan p
                        JOIN tiket t ON p.tiket_id = t.id
                        WHERE p.user_id = ? AND p.status_pesanan = 'dikonfirmasi'
                        ORDER BY p.tanggal_pesan DESC
                    ");
                    $stmt_active->bind_param("i", $user_id);
                    $stmt_active->execute();
                    $result_active = $stmt_active->get_result();
                    
                    if ($result_active->num_rows == 0):
                    ?>
                        <div class="text-center py-5">
                            <i class="fas fa-ticket-alt" style="font-size: 4rem; color: #8b5cf6;"></i>
                            <h4 class="mt-3" style="color: white;">Belum Ada Tiket Aktif</h4>
                            <p class="text-muted">Beli tiket terlebih dahulu untuk melihat detail tiket Anda</p>
                        </div>
                    <?php else: ?>
                    <div class="row">
                        <?php while($order = $result_active->fetch_assoc()): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card bg-purple-dark border border-purple position-relative">
                                <span class="ticket-badge badge-vvip-custom">AKTIF</span>
                                <div class="card-body text-light">
                                    <h5 class="card-title text-warning"><?= htmlspecialchars($order['jenis_tiket']) ?></h5>
                                    
                                    <p class="card-text">
                                        <i class="fas fa-calendar me-2 text-purple-light"></i>
                                        <span class="text-light"><?= formatTanggal($order['tanggal_event']) ?></span><br>
                                        <i class="fas fa-clock me-2 text-purple-light"></i>
                                        <span class="text-light"><?= formatWaktu($order['waktu_event']) ?></span>
                                    </p>

                                    <p class="card-text">
                                        <strong class="text-warning">Deskripsi Tiket:</strong><br>

                                    </p>

                                    <p class="card-text">
                                        <strong class="text-warning">Jumlah:</strong> 
                                        <span class="text-light"><?= $order['jumlah_tiket'] ?> tiket</span><br>
                                        <strong class="text-warning">Total:</strong> 
                                        <span class="text-light"><?= formatRupiah($order['total_harga']) ?></span>
                                    </p>

                                    <div class="d-flex justify-content-between mt-3">
                                        <a href="detail_tiket.php?id=<?= $order['id'] ?>" 
                                        class="btn btn-sm btn-outline-light"
                                        target="_blank">
                                            <i class="fas fa-ticket-alt me-1"></i> Lihat Tiket
                                        </a>
                                       
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.lihat-detail').forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-order-id');
                    // Nanti bisa arahkan ke halaman detail jika diperlukan
                    alert('Detail tiket akan ditampilkan\nOrder ID: ' + orderId);
                });
            });
        });
    </script>
</body>
</html>