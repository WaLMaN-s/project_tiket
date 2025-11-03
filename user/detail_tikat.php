<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

requireUser();

$id_tiket = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM tiket WHERE id='$id_tiket'");
$tiket = mysqli_fetch_assoc($query);

if(!$tiket) {
    redirect('dashboard.php');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tiket - PENTAS.HUB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
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
            <a href="dashboard.php" class="btn btn-secondary mb-4">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card-custom">
                        <h2 class="mb-4" style="color: #8b00ff;">
                            <i class="fas fa-ticket-alt"></i> Detail Tiket
                        </h2>

                        <div class="ticket-type mb-4">
                            <?php if($tiket['jenis_tiket'] == 'VVIP'): ?>
                                <span class="badge-vvip" style="font-size: 1.5rem;"><?= $tiket['jenis_tiket'] ?></span>
                            <?php elseif($tiket['jenis_tiket'] == 'VIP'): ?>
                                <span class="badge-vip" style="font-size: 1.5rem;"><?= $tiket['jenis_tiket'] ?></span>
                            <?php else: ?>
                                <span class="badge-festival" style="font-size: 1.5rem;"><?= $tiket['jenis_tiket'] ?></span>
                            <?php endif; ?>
                        </div>

                        <h3 class="ticket-price mb-4"><?= formatRupiah($tiket['harga']) ?></h3>

                        <div class="mb-4">
                            <h5><i class="fas fa-info-circle"></i> Deskripsi:</h5>
                            <p style="color: #ccc; line-height: 1.8;">
                                <?= nl2br($tiket['deskripsi']) ?>
                            </p>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="ticket-info mb-3">
                                    <div>
                                        <i class="fas fa-calendar"></i> Tanggal Event
                                    </div>
                                    <div>
                                        <strong><?= formatTanggal($tiket['tanggal_event']) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="ticket-info mb-3">
                                    <div>
                                        <i class="fas fa-clock"></i> Waktu Event
                                    </div>
                                    <div>
                                        <strong><?= formatWaktu($tiket['waktu_event']) ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ticket-info mb-4">
                            <div>
                                <i class="fas fa-map-marker-alt"></i> Lokasi
                            </div>
                            <div>
                                <strong><?= $tiket['lokasi'] ?></strong>
                            </div>
                        </div>

                        <div class="ticket-info mb-4">
                            <div>
                                <i class="fas fa-ticket-alt"></i> Stok Tersedia
                            </div>
                            <div>
                                <strong style="color: #00ff00;"><?= $tiket['stok'] ?> tiket</strong>
                            </div>
                        </div>

                        <a href="pesan_tiket.php?id=<?= $tiket['id'] ?>" class="btn btn-custom w-100 btn-lg">
                            <i class="fas fa-shopping-cart"></i> LANJUT PESAN
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>