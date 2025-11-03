<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Jika sudah login, redirect
if(isset($_SESSION['user_id'])) {
    if($_SESSION['role'] == 'admin') {
        redirect('admin/dashboard.php');
    } else {
        redirect('user/dashboard.php');
    }
}

$error = '';

if(isset($_POST['login'])) {
    $email = clean($_POST['email']);
    $password = md5($_POST['password']);
    
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    
    if(mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        
        if($user['role'] == 'admin') {
            redirect('admin/dashboard.php');
        } else {
            redirect('user/dashboard.php');
        }
    } else {
        $error = 'Email atau password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PENTAS.HUB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h2 class="auth-title">
                <i class="fas fa-sign-in-alt"></i> LOGIN
            </h2>
            
            <?php if($error): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
                
                <button type="submit" name="login" class="btn btn-custom w-100 mb-3">
                    <i class="fas fa-sign-in-alt"></i> LOGIN
                </button>
                
                <div class="text-center">
                    <p>Belum punya akun? <a href="register.php" style="color: #8b00ff;">Daftar disini</a></p>
                    <a href="index.php" style="color: #ccc;">
                        <i class="fas fa-arrow-left"></i> Kembali ke Home
                    </a>
                </div>
            </form>
            
            <hr style="border-color: #8b00ff; margin: 30px 0;">
            
            <div class="text-center" style="color: #ccc; font-size: 0.9rem;">
                <p><strong>Demo Account:</strong></p>
                <p>Admin: admin@pentashub.com / admin123</p>
                <p>User: user@example.com / user123</p>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>