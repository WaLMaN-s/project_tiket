<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

requireAdmin();

$id = $_GET['id'];

// Hapus tiket
$query = mysqli_query($conn, "DELETE FROM tiket WHERE id='$id'");

if($query) {
    echo "<script>
        alert('Tiket berhasil dihapus!');
        window.location.href = 'data_tiket.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal menghapus tiket!');
        window.location.href = 'data_tiket.php';
    </script>";
}
?>