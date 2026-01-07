<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

requireUser();

if (!isset($_SESSION['temp_order'])) {
    redirect('dashboard.php');
}

$order = $_SESSION['temp_order'];
$error = '';
$upload_success = false;
$preview_url = '';

// Debug: cek direktori upload
$upload_dir = '../uploads/bukti/';
if (!is_dir($upload_dir)) {
    if (!mkdir($upload_dir, 0777, true)) {
        $error .= "Gagal membuat folder upload. ";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metode = $_POST['metode'] ?? '';
    if (!in_array($metode, ['qris', 'transfer'])) {
        $error .= 'Pilih metode pembayaran!<br>';
    }
    
    // Debug upload
    error_log("Upload status: " . print_r($_FILES, true));
    
    if (!isset($_FILES['bukti']) || $_FILES['bukti']['error'] !== UPLOAD_ERR_OK) {
        $error .= 'Upload bukti pembayaran!<br>';
        $error .= 'Error code: ' . ($_FILES['bukti']['error'] ?? 'no file') . '<br>';
    } else {
        $file = $_FILES['bukti'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($ext, $allowed)) {
            $error .= 'Format file tidak didukung. Gunakan: JPG, PNG, GIF.<br>';
        } elseif ($file['size'] > 5000000) { // 5MB
            $error .= 'Ukuran file maksimal 5 MB.<br>';
        } else {
            $filename = 'bukti_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
            $filepath = $upload_dir . $filename;
            
            // Debug path
            error_log("Upload path: " . realpath($upload_dir));
            error_log("Target file: " . $filepath);
            
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                $upload_success = true;
                $preview_url = 'uploads/bukti/' . $filename;
                
                $_SESSION['temp_order']['metode'] = $metode;
                $_SESSION['temp_order']['bukti'] = $filename;
                // Jangan redirect dulu, tampilkan preview
            } else {
                $error .= 'Gagal memindahkan file. Periksa permission folder.<br>';
                $error .= 'Path: ' . $filepath . '<br>';
                $error .= 'Error: ' . error_get_last()['message'] ?? 'unknown error';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metode Pembayaran - PENTAS.HUB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .preview-image {
            max-width: 300px;
            max-height: 300px;
            border: 3px solid #a855f7;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(168, 85, 247, 0.5);
            margin: 15px auto;
            display: block;
        }
        .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }
        .status-success {
            background: rgba(16, 185, 129, 0.3);
            color: #10b981;
            border: 1px solid #10b981;
        }
        .status-error {
            background: rgba(239, 68, 68, 0.3);
            color: #ef4444;
            border: 1px solid #ef4444;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="../index.php">PENTAS.<span class="text-warning">HUB</span></a>
        </div>
    </nav>

    <div class="dashboard-container" style="margin-top: 80px;">
        <div class="container">
            <a href="javascript:history.back()" class="btn btn-secondary mb-4">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong>Error:</strong><br>
                    <?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($upload_success): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <strong>Berhasil!</strong> Bukti pembayaran terupload.<br>
                    <small>Klik tombol di bawah untuk melanjutkan proses pembayaran</small>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                
                <div class="text-center mb-4">
                    <img src="<?= $preview_url ?>" alt="Bukti Pembayaran" class="preview-image">
                    <div class="mt-2">
                        <span class="status-badge status-success">
                            <i class="fas fa-check-circle me-1"></i> Berhasil Diupload
                        </span>
                    </div>
                </div>
                
                <form method="POST" action="proses-pembayaran.php">
                    <input type="hidden" name="confirm_upload" value="1">
                    <button type="submit" class="btn btn-success w-100 py-3">
                        <i class="fas fa-paper-plane me-2"></i> KONFIRMASI PEMBAYARAN & SELESAIKAN PESANAN
                    </button>
                </form>
                <?php exit; ?>
            <?php endif; ?>

            <div class="card-custom">
                <h2 class="mb-4 text-purple">
                    <i class="fas fa-credit-card me-2"></i> Pilih Metode Pembayaran
                </h2>

                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Metode Pembayaran</label>
                        <select name="metode" class="form-select form-select-lg bg-purple-dark border-purple text-white" id="metodeSelect" required>
                            <option value="" disabled selected>-- Pilih Metode Pembayaran --</option>
                            <option value="qris">QRIS (E-Wallet)</option>
                            <option value="transfer">Transfer Bank</option>
                        </select>
                    </div>

                    <!-- QRIS Content -->
                    <div id="qrisContent" class="payment-option active mb-4 p-3 rounded-3 bg-purple-dark border border-purple">
                        <div class="text-center mb-3">
                            <div class="bg-dark rounded-3 p-3 d-inline-block mb-3">
                                <img src="../assets/image/qris.jpg" alt="QRIS" class="img-fluid" style="max-width: 400px; border: 2px solid #a855f7; box-shadow: 0 0 15px rgba(168, 85, 247, 0.5);">
                            </div>
                            <h5 class="text-purple fw-bold mb-1">Scan QRIS untuk Bayar</h5>
                            <p class="text-muted small mb-2">Buka aplikasi e-wallet Anda (GoPay, OVO, DANA, dll)</p>
                            <div class="bg-dark rounded-2 p-2 d-inline-block">
                                <strong class="text-warning fs-3">Total: <?= formatRupiah($order['total_harga']) ?></strong>
                            </div>
                        </div>
                    </div>

                    <!-- Transfer Content -->
                    <div id="transferContent" class="payment-option mb-4 p-3 rounded-3 bg-purple-dark border border-purple">
                        <div class="card border-0 bg-transparent">
                            <div class="card-header bg-transparent border-bottom border-purple py-2">
                                <h5 class="text-purple fw-bold mb-0"><i class="fas fa-building me-2"></i>Detail Transfer Bank</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item bg-transparent border-purple py-2">
                                        <div class="d-flex justify-content-between">
                                            <span><i class="fas fa-university text-purple me-2"></i> Bank</span>
                                            <strong class="text-white">BCA</strong>
                                        </div>
                                    </li>
                                    <li class="list-group-item bg-transparent border-purple py-2">
                                        <div class="d-flex justify-content-between">
                                            <span><i class="fas fa-hashtag text-purple me-2"></i> No Rekening</span>
                                            <strong class="text-warning">1234567890</strong>
                                        </div>
                                    </li>
                                    <li class="list-group-item bg-transparent border-purple py-2">
                                        <div class="d-flex justify-content-between">
                                            <span><i class="fas fa-user text-purple me-2"></i> Atas Nama</span>
                                            <strong class="text-success">PENTAS HUB</strong>
                                        </div>
                                    </li>
                                    <li class="list-group-item bg-transparent border-purple py-2">
                                        <div class="d-flex justify-content-between">
                                            <span><i class="fas fa-money-bill-wave text-purple me-2"></i> Total Transfer</span>
                                            <strong class="text-warning fs-4"><?= formatRupiah($order['total_harga']) ?></strong>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="alert alert-info alert-dismissible fade show mt-3 mb-0 p-2 small" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Perhatikan!</strong> Lakukan transfer tepat sebelum batas waktu. Gunakan kode unik untuk memudahkan proses verifikasi.
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>

                   <!-- Upload Bukti -->
                    <div class="mt-4 p-3 bg-purple-dark rounded-3 border border-purple">
                        <label class="form-label fw-bold text-purple mb-3 d-block">
                            <i class="fas fa-file-image me-2"></i>Upload Bukti Pembayaran
                            <span class="text-muted small d-block mt-1">Pastikan gambar jelas dan terbaca</span>
                        </label>
                        <div class="upload-area border-2 border-dashed rounded-3 p-4 text-center bg-dark mb-3 position-relative">
                            <i class="fas fa-cloud-upload-alt text-purple" style="font-size: 2.5rem;"></i>
                            <h6 class="mt-2 mb-1 text-white">Tarik & lepas file atau klik untuk upload</h6>
                            <p class="text-muted small mb-0">Format: JPG, PNG, GIF (maks 5 MB)</p>
                            <input type="file" name="bukti" id="fileInput" class="form-control d-none" accept="image/*" required>
                            <div id="filePreview" class="mt-3"></div>
                        </div>
                        <div class="form-text text-center text-warning small fst-italic">
                            <i class="fas fa-exclamation-triangle me-1"></i> Pastikan bukti transfer jelas dan tidak diedit
                        </div>
                    </div>


                    <button type="submit" class="btn btn-custom w-100 py-3">
                        <i class="fas fa-paper-plane me-2"></i> UPLOAD BUKTI PEMBAYARAN
                    </button>
                    <div class="text-center mt-2 text-muted small">
                        <i class="fas fa-shield-alt me-1"></i> Pembayaran Anda akan diverifikasi oleh admin
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const metodeSelect = document.getElementById('metodeSelect');
    const qrisContent = document.getElementById('qrisContent');
    const transferContent = document.getElementById('transferContent');
    const uploadArea = document.querySelector('.upload-area');
    const fileInput = document.getElementById('fileInput');
    const filePreview = document.getElementById('filePreview');

    // Toggle metode pembayaran
    metodeSelect.addEventListener('change', function() {
        const isActive = this.value === 'qris';
        qrisContent.classList.toggle('active', isActive);
        transferContent.classList.toggle('active', !isActive);
    });

    // Klik area upload
    uploadArea.addEventListener('click', () => fileInput.click());

    // Validasi & preview file
    fileInput.addEventListener('change', function(e) {
        filePreview.innerHTML = '';
        if (!this.files[0]) return;

        const file = this.files[0];
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        const maxSize = 5 * 1024 * 1024; // 5MB

        if (!validTypes.includes(file.type)) {
            filePreview.innerHTML = '<div class="text-danger">❌ Format tidak didukung (harus JPG/PNG/GIF)</div>';
            this.value = '';
            return;
        }

        if (file.size > maxSize) {
            filePreview.innerHTML = '<div class="text-danger">❌ File terlalu besar (maks 5 MB)</div>';
            this.value = '';
            return;
        }

        // Tampilkan preview
        const reader = new FileReader();
        reader.onload = e => {
            filePreview.innerHTML = `
                <img src="${e.target.result}" class="preview-image" style="max-width: 100%; max-height: 200px; margin: 0 auto;">
                <div class="mt-2 text-success">✅ Siap diupload: ${file.name}</div>
            `;
        };
        reader.readAsDataURL(file);
    });

    // Set default ke QRIS
    metodeSelect.value = 'qris';
    metodeSelect.dispatchEvent(new Event('change'));
});
</script>
</body>
</html>