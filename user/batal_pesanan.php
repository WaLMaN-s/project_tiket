<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

requireUser();

$id_pesanan = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Cek apakah pesanan milik user ini
$query = mysqli_query($conn, "SELECT * FROM pesanan WHERE id='$id_pesanan' AND user_id='$user_id'");

if(mysqli_num_rows($query) > 0) {
    $pesanan = mysqli_fetch_assoc($query);
    
    // Update status pesanan menjadi dibatalkan
    mysqli_query($conn, "UPDATE pesanan SET status_pesanan='dibatalkan' WHERE id='$id_pesanan'");
    
    // Kembalikan stok tiket
    updateStokTiket($pesanan['tiket_id'], $pesanan['jumlah_tiket'], 'tambah');
    
    echo "<script>
        alert('Pesanan berhasil dibatalkan!');
        window.location.href = 'riwayat.php';
    </script>";
} else {
    echo "<script>
        alert('Pesanan tidak ditemukan!');
        window.location.href = 'riwayat.php';
    </script>";
}
?>