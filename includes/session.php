<?php
// Mulai session jika belum
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fungsi cek login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Fungsi cek role admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

// Fungsi cek role user
function isUser() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'user';
}

// Proteksi halaman - harus login
function requireLogin() {
    if (!isLoggedIn()) {
        redirect(BASE_URL . 'login.php');
    }
}

// Proteksi halaman - khusus admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        redirect(BASE_URL . 'index.php');
    }
}

// Proteksi halaman - khusus user
function requireUser() {
    requireLogin();
    if (!isUser()) {
        redirect(BASE_URL . 'index.php');
    }
}

// Get data user yang sedang login
function getUserData() {
    global $conn;
    if (isLoggedIn()) {
        $user_id = $_SESSION['user_id'];
        $query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
        return mysqli_fetch_assoc($query);
    }
    return null;
}
?>