<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

requireAdmin();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = 'ID pesanan tidak valid.';
    redirect('konfirmasi-pesanan.php');
}

$id = (int)$_GET['id'];

// Ambil data pesanan untuk kembalikan stok
$stmt = $conn->prepare("SELECT tiket_id, jumlah_tiket FROM pesanan WHERE id = ? AND status_pesanan = 'menunggu_konfirmasi'");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = 'Pesanan tidak ditemukan atau sudah diproses.';
    redirect('konfirmasi-pesanan.php');
}

$pesanan = $result->fetch_assoc();
$tiket_id = $pesanan['tiket_id'];
$jumlah = $pesanan['jumlah_tiket'];

// Mulai transaksi
$conn->autocommit(FALSE);

try {
    // Kembalikan stok tiket
    $stmt_update = $conn->prepare("UPDATE tiket SET stok = stok + ?, status = 'tersedia' WHERE id = ?");
    $stmt_update->bind_param("ii", $jumlah, $tiket_id);
    if (!$stmt_update->execute()) {
        throw new Exception("Gagal update stok: " . $stmt_update->error);
    }

    // Update status pesanan ke "dibatalkan"
    $stmt_cancel = $conn->prepare("UPDATE pesanan SET status_pesanan = 'dibatalkan' WHERE id = ?");
    $stmt_cancel->bind_param("i", $id);
    if (!$stmt_cancel->execute()) {
        throw new Exception("Gagal update status: " . $stmt_cancel->error);
    }

    $conn->commit();
    $_SESSION['success'] = 'Pesanan berhasil ditolak dan stok telah dikembalikan.';
    redirect('konfirmasi-pesanan.php');
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = 'Gagal menolak pesanan: ' . $e->getMessage();
    redirect('konfirmasi-pesanan.php');
} finally {
    $conn->autocommit(TRUE);
}
?>