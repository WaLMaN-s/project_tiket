<?php
session_start(); // WAJIB ADA
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

requireUser();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID tiket tidak valid";
    header('Location: dashboard.php');
    exit;
}

$order_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// Ambil data tiket lengkap
$stmt = $conn->prepare("
    SELECT p.*, t.jenis_tiket, t.deskripsi, t.tanggal_event, t.waktu_event, t.lokasi, 
           u.nama, u.email, t.gambar
    FROM pesanan p
    JOIN tiket t ON p.tiket_id = t.id
    JOIN users u ON p.user_id = u.id
    WHERE p.id = ? AND p.user_id = ? AND p.status_pesanan = 'dikonfirmasi'
");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error'] = "Tiket tidak ditemukan atau belum aktif";
    header('Location: dashboard.php');
    exit;
}

$ticket = $result->fetch_assoc();
$unique_code = 'PENTAS-' . strtoupper(substr(md5($order_id . $user_id . time()), 0, 8));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket #<?= $order_id ?> - PENTAS.HUB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f0022 0%, #1a0033 100%);
            min-height: 100vh;
            padding-top: 80px;
        }
        .ticket-container {
            max-width: 800px;
            margin: 2rem auto;
            background: linear-gradient(145deg, #1e1e2d, #2d2d44);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(139, 0, 255, 0.5);
            border: 2px solid #8b00ff;
            position: relative;
            color: white;
        }
        .watermark {
            position: absolute;
            opacity: 0.07;
            font-size: 12rem;
            transform: rotate(-30deg);
            top: 30%;
            left: -20%;
            font-weight: bold;
            color: white;
            pointer-events: none;
            z-index: 0;
        }
        .header {
            text-align: center;
            padding: 2rem 1.5rem;
            background: rgba(139, 0, 255, 0.2);
            border-bottom: 3px solid #8b00ff;
            position: relative;
            z-index: 1;
        }
        .header h1 {
            font-size: 2.8rem;
            margin: 0;
            background: linear-gradient(45deg, #8b00ff, #ff00ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .qr-container {
            display: flex;
            justify-content: center;
            margin: 1.5rem 0;
            z-index: 1;
        }
        .qr-code {
            width: 180px;
            height: 180px;
            background: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            border: 3px solid #8b00ff;
        }
        .ticket-body {
            padding: 2rem;
            position: relative;
            z-index: 1;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .info-card {
            background: rgba(30, 30, 45, 0.7);
            padding: 1.2rem;
            border-radius: 12px;
            border: 1px solid #8b5cf6;
        }
        .info-label {
            color: #c7a5ff;
            font-weight: 500;
            margin-bottom: 0.3rem;
        }
        .unique-code {
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid #10b981;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            font-size: 1.4rem;
            font-weight: bold;
            letter-spacing: 4px;
            margin: 1.5rem 0;
            color: #34d399;
        }
        .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(45deg, #10b981, #0ea5e9);
            padding: 0.3rem 1rem;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }
        .footer {
            text-align: center;
            padding: 1.2rem;
            background: rgba(0, 0, 0, 0.2);
            border-top: 2px solid #8b00ff;
            font-size: 0.9rem;
            color: #a78bfa;
        }
        @media print {
            body {
                background: white;
                padding-top: 0;
            }
            .no-print {
                display: none;
            }
            .ticket-container {
                box-shadow: none;
                border: none;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top no-print">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                PENTAS.<span>HUB</span>
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container no-print text-center mb-4">
        <div class="alert alert-info d-inline-block px-4 py-2">
            <i class="fas fa-print me-2"></i>
            <strong>Tiket akan otomatis terbuka untuk dicetak.</strong>
            <br>
            Jika tidak muncul dialog print, klik tombol di bawah:
        </div>
        <button onclick="window.print()" class="btn btn-danger px-4 py-2 fs-5">
            <i class="fas fa-print me-2"></i> CETAK TIKET
        </button>
    </div>

    <div class="ticket-container">
        <div class="watermark">PENTAS.HUB</div>
        <span class="status-badge">
            <i class="fas fa-check-circle me-1"></i> AKTIF
        </span>
        
        <div class="header">
            <h1>PENTAS.<span style="color:#ff6b6b">HUB</span></h1>
            <p class="fs-4 mb-0">MUSIC FESTIVAL 2025 • OFFICIAL TICKET</p>
        </div>
        
        <div class="qr-container">
            <div class="qr-code">
                <div class="text-center">
                    <i class="fas fa-qrcode" style="font-size: 5rem; color: #8b00ff;"></i>
                    <div class="mt-2 fw-bold fs-5">#<?= str_pad($order_id, 6, '0', STR_PAD_LEFT) ?></div>
                </div>
            </div>
        </div>
        
        <div class="ticket-body">
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-label">NAMA PEMILIK</div>
                    <div class="fs-4 fw-bold"><?= htmlspecialchars($ticket['nama']) ?></div>
                </div>
                <div class="info-card">
                    <div class="info-label">EMAIL</div>
                    <div><?= htmlspecialchars($ticket['email']) ?></div>
                </div>
                <div class="info-card">
                    <div class="info-label">TANGGAL PEMBELIAN</div>
                    <div><?= date('d M Y H:i', strtotime($ticket['tanggal_pesan'])) ?></div>
                </div>
                <div class="info-card">
                    <div class="info-label">ORDER ID</div>
                    <div class="text-warning">#000<?= $order_id ?></div>
                </div>
            </div>
            
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-label">JENIS TIKET</div>
                    <div class="fs-4" style="
                        <?php if($ticket['jenis_tiket'] == 'VVIP'): ?>
                            color: #ff6b6b; text-shadow: 0 0 10px rgba(255,107,107,0.5);
                        <?php elseif($ticket['jenis_tiket'] == 'VIP'): ?>
                            color: #ffd166; text-shadow: 0 0 10px rgba(255,209,102,0.5);
                        <?php else: ?>
                            color: #06d6a0; text-shadow: 0 0 10px rgba(6,214,160,0.5);
                        <?php endif; ?>
                    ">
                        <?= htmlspecialchars($ticket['jenis_tiket']) ?>
                    </div>
                </div>
                <div class="info-card">
                    <div class="info-label">TOTAL BAYAR</div>
                    <div class="fs-4 text-warning"><?= formatRupiah($ticket['total_harga']) ?></div>
                </div>
                <div class="info-card">
                    <div class="info-label">JUMLAH TIKET</div>
                    <div class="fs-4"><?= $ticket['jumlah_tiket'] ?> tiket</div>
                </div>
                <div class="info-card">
                    <div class="info-label">TANGGAL EVENT</div>
                    <div><?= formatTanggal($ticket['tanggal_event']) ?></div>
                </div>
            </div>
            
            <div class="unique-code">
                <?= $unique_code ?>
            </div>
            
            <div class="info-card">
                <div class="info-label mb-2">
                    <i class="fas fa-map-marker-alt me-2"></i> LOKASI EVENT
                </div>
                <p class="mb-0" style="line-height: 1.6;">
                    <?= nl2br(htmlspecialchars($ticket['lokasi'])) ?>
                </p>
            </div>
            
            <div class="info-card mt-3">
                <div class="info-label mb-2">
                    <i class="fas fa-info-circle me-2"></i> DESKRIPSI TIKET
                </div>
                <p class="mb-0" style="line-height: 1.6;">
                    <?= nl2br(htmlspecialchars($ticket['deskripsi'])) ?>
                </p>
            </div>
        </div>
        
        <div class="footer">
            • Tiket ini berlaku hanya untuk 1 (satu) orang •<br>
            • Tunjukkan QR code saat check-in di venue •<br>
            www.pentas-hub.com • Customer Service: +62 812-3456-7890
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto print setelah halaman selesai dimuat
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
        
        // Close parent modal jika dibuka dari modal
        window.addEventListener('afterprint', function() {
            const modalElement = document.querySelector('.modal.show');
            if (modalElement) {
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) modal.hide();
            }
        });
    </script>
</body>
</html>