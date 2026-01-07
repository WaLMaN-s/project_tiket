<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

function isUser() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'user';
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect('../login.php');
    }
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        redirect('../index.php');
    }
}

function requireUser() {
    requireLogin();
    if (!isUser()) {
        redirect('../index.php');
    }
}

function getUserData() {
    global $conn;
    if (isLoggedIn()) {
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    return null;
}
?>