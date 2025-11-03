<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

requireAdmin();

$error = '';
$success = '';

if(isset($_POST['tambah'])) {
    $jenis_tiket = clean($_POST['jenis_tiket']);
    $harga = (int)$_POST['harga'];
    $stok = (int)$_POST['stok'];
    $deskripsi = clean($_POST['deskripsi']);
    $tanggal_event = clean($_POST['tanggal_event']);
    $waktu_event = clean($_POST['waktu_event']);
    $lokasi = clean($_POST['lokasi']);
    
    if(empty($jenis_tiket) || empty($harga) || empty($stok)) {
        $error = 'Semua field wajib diisi!';
    } else {
        $query = mysqli_query($conn, "INSERT INTO tiket 
            (jenis_tiket, harga, stok, deskripsi, tanggal_event, waktu_event, lokasi, status) 
            VALUES 
            ('$jenis_tiket', '$harga', '$stok', '$deskripsi', '$tanggal_event', '$waktu_event', '$lokasi', 'tersedia')");
        
        if($query) {
            $success = 'Tiket berhasil ditambahkan!';
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'data_tiket.php';
                }, 2000);
            </script>";
        } else {
            $error = 'Gagal menambahkan tiket! ' . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tiket - Admin PENTAS.HUB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                ADMIN - PENTAS.<span>HUB</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="data_tiket.php">Data Tiket</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="data_pesanan.php">Data Pesanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="data_user.php">Data User</a>
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
            <a href="data_tiket.php" class="btn btn-secondary mb-4">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            <?php if($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card-custom">
                        <h2 class="mb-4" style="color: #8b00ff;">
                            <i class="fas fa-plus-circle"></i> Tambah Tiket Baru
                        </h2>

                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jenis Tiket</label>
                                    <select name="jenis_tiket" class="form-control" required>
                                        <option value="">Pilih Jenis Tiket</option>
                                        <option value="VVIP">VVIP</option>
                                        <option value="VIP">VIP</option>
                                        <option value="Festival">Festival</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Harga (Rp)</label>
                                    <input type="number" name="harga" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Stok Tiket</label>
                                <input type="number" name="stok" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="4" required></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Event</label>
                                    <input type="date" name="tanggal_event" class="form-control" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Waktu Event</label>
                                    <input type="time" name="waktu_event" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Lokasi</label>
                                <input type="text" name="lokasi" class="form-control" required>
                            </div>

                            <button type="submit" name="tambah" class="btn btn-custom w-100">
                                <i class="fas fa-save"></i> SIMPAN TIKET
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>