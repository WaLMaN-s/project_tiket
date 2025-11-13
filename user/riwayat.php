<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

requireUser();

$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT p.*, t.jenis_tiket, t.tanggal_event, t.waktu_event, t.lokasi 
                               FROM pesanan p 
                               JOIN tiket t ON p.tiket_id = t.id 
                               WHERE p.user_id='$user_id' 
                               ORDER BY p.tanggal_pesan DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - PENTAS.HUB</title>
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
            <div class="dashboard-header">
                <h2>
                    <i class="fas fa-history"></i> Riwayat Pesanan
                </h2>
            </div>

            <div class="card-custom">
                <?php if(mysqli_num_rows($query) > 0): ?>
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
                            while($pesanan = mysqli_fetch_assoc($query)): 
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($pesanan['tanggal_pesan'])) ?></td>
                                <td>
                                    <?php if($pesanan['jenis_tiket'] == 'VVIP'): ?>
                                        <span class="badge-vvip"><?= $pesanan['jenis_tiket'] ?></span>
                                    <?php elseif($pesanan['jenis_tiket'] == 'VIP'): ?>
                                        <span class="badge-vip"><?= $pesanan['jenis_tiket'] ?></span>
                                    <?php else: ?>
                                        <span class="badge-festival"><?= $pesanan['jenis_tiket'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $pesanan['jumlah_tiket'] ?> tiket</td>
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
                                <td>
                                    <?php if($pesanan['status_pesanan'] == 'berhasil'): ?>
                                        <a href="batal_pesanan.php?id=<?= $pesanan['id'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                            <i class="fas fa-times"></i> Batal
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
                        <p class="text-muted">Mulai pesan tiket sekarang!</p>
                        <a href="dashboard.php" class="btn btn-custom">
                            <i class="fas fa-shopping-cart"></i> Pesan Tiket
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>