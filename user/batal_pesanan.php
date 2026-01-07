<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

requireUser();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = 'ID pesanan tidak valid.';
    redirect('riwayat.php');
}

$id_pesanan = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// Cek apakah pesanan milik user ini dan masih bisa dibatalkan
$stmt = $conn->prepare("SELECT * FROM pesanan WHERE id = ? AND user_id = ? AND status_pesanan = 'menunggu_konfirmasi'");
$stmt->bind_param("ii", $id_pesanan, $user_id);
$stmt->execute();
$pesanan = $stmt->get_result()->fetch_assoc();

if (!$pesanan) {
    $_SESSION['error'] = 'Pesanan tidak ditemukan atau sudah tidak bisa dibatalkan.';
    redirect('riwayat.php');
}

$conn->begin_transaction();

try {
    // Update status ke dibatalkan
    $stmt1 = $conn->prepare("UPDATE pesanan SET status_pesanan = 'dibatalkan' WHERE id = ?");
    $stmt1->bind_param("i", $id_pesanan);
    $stmt1->execute();

    // Kembalikan stok tiket
    $stmt2 = $conn->prepare("UPDATE tiket SET stok = stok + ? WHERE id = ?");
    $stmt2->bind_param("ii", $pesanan['jumlah_tiket'], $pesanan['tiket_id']);
    $stmt2->execute();

    $conn->commit();
    $_SESSION['success'] = 'Pesanan berhasil dibatalkan dan stok tiket telah dikembalikan.';
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = 'Gagal membatalkan pesanan: ' . $e->getMessage();
}

redirect('riwayat.php');
?>