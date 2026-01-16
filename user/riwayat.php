<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

requireUser();

$user_id = $_SESSION['user_id'];

// Query pesanan dengan status baru
$stmt = $conn->prepare("SELECT p.*, t.jenis_tiket, t.tanggal_event, t.waktu_event, t.lokasi 
                        FROM pesanan p 
                        JOIN tiket t ON p.tiket_id = t.id 
                        WHERE p.user_id = ?
                        ORDER BY p.tanggal_pesan DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Tampilkan pesan sukses/error dari session
$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - KONSER .FEATS</title>
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
                        <a class="nav-link" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">My Tickets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="riwayat.php">Riwayat Pesanan</a>
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
            <a href="dashboard.php" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>

            <?php if($success): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= htmlspecialchars($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if($error): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="dashboard-header mb-4">
                <h2>
                    <i class="fas fa-history"></i> Riwayat Pesanan
                </h2>
                <p class="mb-0 text-muted">Daftar semua pesanan tiket Anda</p>
            </div>

            <div class="card-custom">
                <?php if($result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-custom table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Pesan</th>
                                <th>Jenis Tiket</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while($pesanan = $result->fetch_assoc()): 
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($pesanan['tanggal_pesan'])) ?></td>
                                <td>
                                    <?php if($pesanan['jenis_tiket'] == 'VVIP'): ?>
                                        <span class="badge-vvip"><?= htmlspecialchars($pesanan['jenis_tiket']) ?></span>
                                    <?php elseif($pesanan['jenis_tiket'] == 'VIP'): ?>
                                        <span class="badge-vip"><?= htmlspecialchars($pesanan['jenis_tiket']) ?></span>
                                    <?php else: ?>
                                        <span class="badge-festival"><?= htmlspecialchars($pesanan['jenis_tiket']) ?></span>
                                    <?php endif; ?>
                                    <div class="mt-1">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i><?= formatTanggal($pesanan['tanggal_event']) ?> |
                                            <i class="fas fa-clock me-1"></i><?= formatWaktu($pesanan['waktu_event']) ?>
                                        </small>
                                    </div>
                                </td>
                                <td><?= $pesanan['jumlah_tiket'] ?> tiket</td>
                                <td class="fw-bold text-warning"><?= formatRupiah($pesanan['total_harga']) ?></td>
                                <td>
                                    <?php if($pesanan['status_pesanan'] == 'dikonfirmasi'): ?>
                                        <span class="status-badge status-dikonfirmasi">
                                            <i class="fas fa-check-circle me-1"></i> Berhasil
                                        </span>
                                    <?php elseif($pesanan['status_pesanan'] == 'menunggu_konfirmasi'): ?>
                                        <span class="status-badge status-menunggu_konfirmasi">
                                            <i class="fas fa-clock me-1"></i> Menunggu Konfirmasi
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge status-dibatalkan">
                                            <i class="fas fa-times-circle me-1"></i> Dibatalkan
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($pesanan['bukti_pembayaran'] && $pesanan['status_pesanan'] != 'dibatalkan'): ?>
                                        <a href="../uploads/bukti/<?= htmlspecialchars($pesanan['bukti_pembayaran']) ?>" 
                                           target="_blank" class="btn btn-sm btn-info mb-1"
                                           title="Lihat Bukti Pembayaran">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if($pesanan['status_pesanan'] == 'menunggu_konfirmasi'): ?>
                                        <a href="batal_pesanan.php?id=<?= $pesanan['id'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Yakin ingin membatalkan pesanan ini? Stok tiket akan dikembalikan.')">
                                            <i class="fas fa-times"></i> Batalkan
                                        </a>
                                    <?php elseif($pesanan['status_pesanan'] == 'dikonfirmasi'): ?>
                                        <a href="#" class="btn btn-sm btn-success disabled">
                                            <i class="fas fa-check"></i> Tiket Aktif
                                        </a>
                                    <?php elseif($pesanan['status_pesanan'] == 'dibatalkan'): ?>
                                        <a href="pesan_ulang.php?id_tiket=<?= $pesanan['tiket_id'] ?>&jumlah=<?= $pesanan['jumlah_tiket'] ?>" 
                                           class="btn btn-sm btn-warning"
                                           title="Pesan ulang tiket ini">
                                            <i class="fas fa-redo"></i> Pesan Ulang
                                        </a>
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
                        <h4 class="mt-3">Belum ada riwayat pesanan</h4>
                        <p class="text-muted mb-4">Anda belum pernah melakukan pemesanan tiket</p>
                        <a href="dashboard.php" class="btn btn-custom px-4 py-2">
                            <i class="fas fa-ticket-alt me-2"></i> LIHAT TIKET TERSEDIA
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="card-custom mt-4">
                <h4 class="text-purple mb-3"><i class="fas fa-info-circle me-2"></i>Panduan Status Pesanan</h4>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-start">
                            <div class="status-badge status-menunggu_konfirmasi p-2 me-3">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h6>Menunggu Konfirmasi</h6>
                                <p class="text-muted small mb-0">Admin sedang memverifikasi bukti pembayaran Anda</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-start">
                            <div class="status-badge status-dikonfirmasi p-2 me-3">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <h6>Berhasil</h6>
                                <p class="text-muted small mb-0">Pembayaran telah dikonfirmasi, tiket Anda aktif</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-start">
                            <div class="status-badge status-dibatalkan p-2 me-3">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div>
                                <h6>Dibatalkan</h6>
                                <p class="text-muted small mb-0">Pesanan dibatalkan, stok tiket telah dikembalikan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>