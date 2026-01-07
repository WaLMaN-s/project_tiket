<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

requireUser();

if (!isset($_SESSION['temp_order']) || 
    !isset($_SESSION['temp_order']['bukti']) || 
    !isset($_SESSION['temp_order']['metode'])) {
    redirect('metode-pembayaran.php');
}

$order = $_SESSION['temp_order'];
$user_id = $_SESSION['user_id'];

// Cek stok ulang
$stmt = $conn->prepare("SELECT stok FROM tiket WHERE id = ?");
$stmt->bind_param("i", $order['tiket_id']);
$stmt->execute();
$tiket = $stmt->get_result()->fetch_assoc();

if (!$tiket || $tiket['stok'] < $order['jumlah_tiket']) {
    unset($_SESSION['temp_order']);
    $_SESSION['error'] = 'Maaf, tiket telah habis.';
    redirect('dashboard.php');
}

$conn->begin_transaction();

try {
    // Simpan pesanan
    $stmt_ins = $conn->prepare("INSERT INTO pesanan 
        (user_id, tiket_id, jumlah_tiket, total_harga, nama_pemesan, email_pemesan, no_hp_pemesan, metode_pembayaran, bukti_pembayaran, status_pesanan)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'menunggu_konfirmasi')");
    
    $stmt_ins->bind_param(
        "iiidsssss",
        $user_id,
        $order['tiket_id'],
        $order['jumlah_tiket'],
        $order['total_harga'],
        $order['nama_pemesan'],
        $order['email_pemesan'],
        $order['no_hp_pemesan'],
        $order['metode'],
        $order['bukti']
    );

    if (!$stmt_ins->execute()) throw new Exception('Gagal menyimpan pesanan.');

    // Kurangi stok
    $new_stok = $tiket['stok'] - $order['jumlah_tiket'];
    $status_tiket = ($new_stok > 0) ? 'tersedia' : 'habis';
    
    $stmt_upd = $conn->prepare("UPDATE tiket SET stok = ?, status = ? WHERE id = ?");
    $stmt_upd->bind_param("isi", $new_stok, $status_tiket, $order['tiket_id']);
    if (!$stmt_upd->execute()) throw new Exception('Gagal update stok.');

    $conn->commit();
    unset($_SESSION['temp_order']);
    $_SESSION['success'] = 'Bukti pembayaran berhasil dikirim! Menunggu konfirmasi admin.';
    redirect('riwayat.php');

} catch (Exception $e) {
    $conn->rollback();
    unset($_SESSION['temp_order']);
    $_SESSION['error'] = 'Terjadi kesalahan: ' . $e->getMessage();
    redirect('dashboard.php');
}
?>