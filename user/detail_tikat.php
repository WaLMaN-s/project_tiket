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
    <title>Pesan Tiket - PENTAS.HUB</title>
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

            <?php if($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8">
                    <div class="card-custom">
                        <h2 class="mb-4" style="color: #8b00ff;">
                            <i class="fas fa-shopping-cart"></i> Form Pemesanan Tiket
                        </h2>

                        <form method="POST" action="" id="formPesan">
                            <div class="mb-3">
                                <label class="form-label">Nama Pemesan</label>
                                <input type="text" name="nama_pemesan" class="form-control" 
                                       value="<?= htmlspecialchars($user_data['nama']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Pemesan</label>
                                <input type="email" name="email_pemesan" class="form-control" 
                                       value="<?= htmlspecialchars($user_data['email']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">No. HP Pemesan</label>
                                <input type="text" name="no_hp_pemesan" class="form-control" 
                                       value="<?= htmlspecialchars($user_data['no_hp']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jumlah Tiket (Maks: <?= $tiket['stok'] ?>)</label>
                                <input type="number" name="jumlah_tiket" id="jumlah_tiket" 
                                       class="form-control" min="1" max="<?= $tiket['stok'] ?>" 
                                       value="1" required onchange="hitungTotal()">
                            </div>

                            <button type="submit" name="pesan" class="btn btn-custom w-100">
                                <i class="fas fa-check"></i> KONFIRMASI PEMESANAN
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card-custom">
                        <h4 class="mb-3" style="color: #8b00ff;">
                            <i class="fas fa-receipt"></i> Ringkasan Pesanan
                        </h4>

                        <div class="mb-3">
                            <strong>Jenis Tiket:</strong><br>
                            <span class="ticket-type">
                                <?php if($tiket['jenis_tiket'] == 'VVIP'): ?>
                                    <span class="badge-vvip"><?= htmlspecialchars($tiket['jenis_tiket']) ?></span>
                                <?php elseif($tiket['jenis_tiket'] == 'VIP'): ?>
                                    <span class="badge-vip"><?= htmlspecialchars($tiket['jenis_tiket']) ?></span>
                                <?php else: ?>
                                    <span class="badge-festival"><?= htmlspecialchars($tiket['jenis_tiket']) ?></span>
                                <?php endif; ?>
                            </span>
                        </div>

                        <div class="mb-3">
                            <strong>Harga per Tiket:</strong><br>
                            <span style="color: #8b00ff; font-size: 1.2rem;">
                                <?= formatRupiah($tiket['harga']) ?>
                            </span>
                        </div>

                        <div class="mb-3">
                            <strong>Tanggal Event:</strong><br>
                            <?= formatTanggal($tiket['tanggal_event']) ?>
                        </div>

                        <div class="mb-3">
                            <strong>Waktu:</strong><br>
                            <?= formatWaktu($tiket['waktu_event']) ?>
                        </div>

                        <div class="mb-3">
                            <strong>Lokasi:</strong><br>
                            <?= htmlspecialchars($tiket['lokasi']) ?>
                        </div>

                        <hr style="border-color: #8b00ff;">

                        <div class="mb-2">
                            <strong>Jumlah Tiket:</strong>
                            <span id="display_jumlah" class="float-end">1</span>
                        </div>

                        <div class="mb-3">
                            <strong>Total Harga:</strong>
                            <h4 id="total_harga" class="float-end" style="color: #00ff00;">
                                <?= formatRupiah($tiket['harga']) ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const hargaSatuan = <?= $tiket['harga'] ?>;
        
        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        
        function hitungTotal() {
            const jumlah = parseInt(document.getElementById('jumlah_tiket').value) || 0;
            const total = jumlah * hargaSatuan;
            
            document.getElementById('display_jumlah').textContent = jumlah;
            document.getElementById('total_harga').textContent = formatRupiah(total);
        }
    </script>
</body>
</html>