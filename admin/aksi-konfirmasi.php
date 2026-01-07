<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

requireAdmin();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = 'ID pesanan tidak valid.';
    header('Location: konfirmasi-pesanan.php');
    exit;
}

$id = (int)$_GET['id'];

// Update status ke "dikonfirmasi"
$stmt = $conn->prepare("UPDATE pesanan SET status_pesanan = 'dikonfirmasi' WHERE id = ? AND status_pesanan = 'menunggu_konfirmasi'");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $_SESSION['success'] = 'Pesanan berhasil dikonfirmasi.';
} else {
    $_SESSION['error'] = 'Gagal mengonfirmasi pesanan (mungkin sudah diproses).';
}

header('Location: konfirmasi-pesanan.php');
exit;
?>