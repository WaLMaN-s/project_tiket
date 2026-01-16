<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

requireAdmin();

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

$stmt = $conn->prepare("
    SELECT p.*, u.nama AS nama_user, u.email, t.jenis_tiket
    FROM pesanan p
    JOIN users u ON p.user_id = u.id
    JOIN tiket t ON p.tiket_id = t.id
    WHERE p.status_pesanan = 'menunggu_konfirmasi'
    ORDER BY p.tanggal_pesan DESC
");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
      <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                ADMIN - KONSER<span>.FEATS</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="data_tiket.php">Data Tiket</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="data_pesanan.php">Data Pesanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="konfirmasi-pesanan.php">Konfirmasi Pesanan</a>
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

    <div class="dashboard-admin" style="margin-top: 80px;">
        <div class="container">
            <div class="dashboard-header mb-4">
                <h2><i class="fas fa-check-circle"></i> Konfirmasi Pembayaran</h2>
                <p class="text-muted">Kelola pesanan yang menunggu verifikasi bukti pembayaran</p>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($result->num_rows > 0): ?>
                <div class="table-container">
                    <table class="table table-custom table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>User</th>
                                <th>Tiket</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                                <th>Metode</th>
                                <th>Bukti</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?= $row['id'] ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($row['tanggal_pesan'])) ?></td>
                                <td>
                                    <?= htmlspecialchars($row['nama_user']) ?><br>
                                    <small class="text-muted"><?= htmlspecialchars($row['email']) ?></small>
                                </td>
                                <td>
                                    <?= htmlspecialchars($row['jenis_tiket']) ?><br>
                                    <span class="badge <?= 
                                        $row['jenis_tiket'] == 'VVIP' ? 'bg-danger' : 
                                        ($row['jenis_tiket'] == 'VIP' ? 'bg-warning' : 'bg-primary') 
                                    ?>"><?= htmlspecialchars($row['jenis_tiket']) ?></span>
                                </td>
                                <td><?= $row['jumlah_tiket'] ?></td>
                                <td><?= formatRupiah($row['total_harga']) ?></td>
                                <td><?= ucfirst($row['metode_pembayaran']) ?></td>
                                <td>
                                    <?php if (!empty($row['bukti_pembayaran'])): ?>
                                        <a href="../uploads/bukti/<?= htmlspecialchars($row['bukti_pembayaran']) ?>" 
                                           target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">–</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="aksi-konfirmasi.php?id=<?= $row['id'] ?>" 
                                       class="btn btn-success btn-sm mb-1" 
                                       onclick="return confirm('Konfirmasi pembayaran ini?')">
                                        <i class="fas fa-check"></i> Konfirmasi
                                    </a>
                                    <a href="aksi-ditolak.php?id=<?= $row['id'] ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Tolak pesanan ini? Stok tiket akan dikembalikan.')">
                                        <i class="fas fa-times"></i> Tolak
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-check" style="font-size: 4rem; color: #28a745; opacity: 0.6;"></i>
                    <h4 class="mt-3">Tidak Ada Pesanan Menunggu Konfirmasi</h4>
                    <p class="text-muted">Semua pembayaran telah diverifikasi.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>