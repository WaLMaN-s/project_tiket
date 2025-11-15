<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

requireAdmin();

if(isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Hapus tiket
    $query = mysqli_query($conn, "DELETE FROM tiket WHERE id = $id");
    
    if($query) {
        // Cek apakah masih ada data di tabel
        $check = mysqli_query($conn, "SELECT COUNT(*) as total FROM tiket");
        $result = mysqli_fetch_assoc($check);
        
        // Jika tabel sudah kosong, reset AUTO_INCREMENT ke 1
        if($result['total'] == 0) {
            mysqli_query($conn, "ALTER TABLE tiket AUTO_INCREMENT = 1");
        }
        
        $_SESSION['success'] = "Tiket berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus tiket!";
    }
} else {
    $_SESSION['error'] = "ID tiket tidak ditemukan!";
}

header("Location: data_tiket.php");
exit();
?>