<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

requireUser();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect('dashboard.php');
}

$id_tiket = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM tiket WHERE id = ?");
$stmt->bind_param("i", $id_tiket);
$stmt->execute();
$tiket = $stmt->get_result()->fetch_assoc();

if (!$tiket) redirect('dashboard.php');

$user_data = getUserData();
$error = '';

if (isset($_POST['pesan'])) {
    $jumlah = (int)$_POST['jumlah_tiket'];
    if ($jumlah < 1) {
        $error = 'Jumlah minimal 1 tiket!';
    } elseif ($jumlah > $tiket['stok']) {
        $error = 'Stok tidak mencukupi!';
    } else {
        $_SESSION['temp_order'] = [
            'tiket_id' => $id_tiket,
            'jumlah_tiket' => $jumlah,
            'nama_pemesan' => clean($_POST['nama_pemesan']),
            'email_pemesan' => clean($_POST['email_pemesan']),
            'no_hp_pemesan' => clean($_POST['no_hp_pemesan']),
            'total_harga' => $jumlah * $tiket['harga'],
            'jenis_tiket' => $tiket['jenis_tiket']
        ];
        redirect('metode-pembayaran.php');
    }
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

            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8">
                    <div class="card-custom">
                        <h2 class="mb-4 text-purple">
                            <i class="fas fa-shopping-cart me-2"></i> Form Pemesanan Tiket
                        </h2>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Pemesan</label>
                                <input type="text" name="nama_pemesan" class="form-control bg-purple-dark border-purple text-white" 
                                       value="<?= htmlspecialchars($user_data['nama']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Email Pemesan</label>
                                <input type="email" name="email_pemesan" class="form-control bg-purple-dark border-purple text-white" 
                                       value="<?= htmlspecialchars($user_data['email']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">No. HP Pemesan</label>
                                <input type="text" name="no_hp_pemesan" class="form-control bg-purple-dark border-purple text-white" 
                                       value="<?= htmlspecialchars($user_data['no_hp']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Jumlah Tiket (Maks: <?= $tiket['stok'] ?>)</label>
                                <input type="number" name="jumlah_tiket" id="jumlah_tiket" 
                                       class="form-control bg-purple-dark border-purple text-white" min="1" max="<?= $tiket['stok'] ?>" 
                                       value="1" required onchange="hitungTotal()">
                            </div>

                            <button type="submit" name="pesan" class="btn btn-custom w-100 py-3">
                                <i class="fas fa-arrow-right me-2"></i> LANJUT KE PEMBAYARAN
                            </button>
                            <div class="text-center mt-2 text-muted small">
                                <i class="fas fa-info-circle me-1"></i> Setelah klik, Anda akan diarahkan ke halaman pembayaran
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card-custom">
                        <h4 class="mb-3 text-purple">
                            <i class="fas fa-receipt me-2"></i> Ringkasan Pesanan
                        </h4>

                        <div class="mb-3">
                            <strong class="d-block mb-2">Jenis Tiket:</strong>
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
                            <strong class="d-block mb-2">Harga per Tiket:</strong>
                            <span class="text-warning fs-4 fw-bold">
                                <?= formatRupiah($tiket['harga']) ?>
                            </span>
                        </div>

                        <div class="mb-3">
                            <strong class="d-block mb-2">Tanggal Event:</strong>
                            <?= formatTanggal($tiket['tanggal_event']) ?>
                        </div>

                        <div class="mb-3">
                            <strong class="d-block mb-2">Waktu:</strong>
                            <?= formatWaktu($tiket['waktu_event']) ?>
                        </div>

                        <div class="mb-3">
                            <strong class="d-block mb-2">Lokasi:</strong>
                            <?= htmlspecialchars($tiket['lokasi']) ?>
                        </div>

                        <hr class="border-purple my-4">

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-purple">Jumlah Tiket:</strong>
                                <span id="display_jumlah" class="text-white fw-bold fs-4">1</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-purple">Total Harga:</strong>
                                <h4 id="total_harga" class="text-warning mb-0 fw-bold fs-2">
                                    <?= formatRupiah($tiket['harga']) ?>
                                </h4>
                            </div>
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
        
        document.addEventListener('DOMContentLoaded', function() {
            hitungTotal();
        });
    </script>
</body>
</html>