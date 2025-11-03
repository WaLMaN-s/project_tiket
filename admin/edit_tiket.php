<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

requireAdmin();

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM tiket WHERE id='$id'");
$tiket = mysqli_fetch_assoc($query);

if(!$tiket) {
    redirect('data_tiket.php');
}

$error = '';
$success = '';

if(isset($_POST['update'])) {
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
        $query_update = mysqli_query($conn, "UPDATE tiket SET 
            jenis_tiket='$jenis_tiket',
            harga='$harga',
            stok='$stok',
            deskripsi='$deskripsi',
            tanggal_event='$tanggal_event',
            waktu_event='$waktu_event',
            lokasi='$lokasi'
            WHERE id='$id'");
        
        if($query_update) {
            $success = 'Tiket berhasil diupdate!';
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'data_tiket.php';
                }, 2000);
            </script>";
        } else {
            $error = 'Gagal mengupdate tiket! ' . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tiket - Admin PENTAS.HUB</title>
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
                            <i class="fas fa-edit"></i> Edit Tiket
                        </h2>

                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jenis Tiket</label>
                                    <select name="jenis_tiket" class="form-control" required>
                                        <option value="">Pilih Jenis Tiket</option>
                                        <option value="VVIP" <?= $tiket['jenis_tiket'] == 'VVIP' ? 'selected' : '' ?>>VVIP</option>
                                        <option value="VIP" <?= $tiket['jenis_tiket'] == 'VIP' ? 'selected' : '' ?>>VIP</option>
                                        <option value="Festival" <?= $tiket['jenis_tiket'] == 'Festival' ? 'selected' : '' ?>>Festival</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Harga (Rp)</label>
                                    <input type="number" name="harga" class="form-control" value="<?= $tiket['harga'] ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Stok Tiket</label>
                                <input type="number" name="stok" class="form-control" value="<?= $tiket['stok'] ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="4" required><?= $tiket['deskripsi'] ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Event</label>
                                    <input type="date" name="tanggal_event" class="form-control" value="<?= $tiket['tanggal_event'] ?>" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Waktu Event</label>
                                    <input type="time" name="waktu_event" class="form-control" value="<?= $tiket['waktu_event'] ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Lokasi</label>
                                <input type="text" name="lokasi" class="form-control" value="<?= $tiket['lokasi'] ?>" required>
                            </div>

                            <button type="submit" name="update" class="btn btn-custom w-100">
                                <i class="fas fa-save"></i> UPDATE TIKET
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