<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Jika sudah login, redirect
if(isset($_SESSION['user_id'])) {
    redirect('index.php');
}

$error = '';

if(isset($_POST['register'])) {
    $nama = clean($_POST['nama']);
    $email = clean($_POST['email']);
    $no_hp = clean($_POST['no_hp']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validasi
    if(empty($nama) || empty($email) || empty($password)) {
        $error = 'Semua field harus diisi!';
    } elseif($password != $confirm_password) {
        $error = 'Password dan konfirmasi password tidak cocok!';
    } elseif(strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } else {
        // Cek email sudah terdaftar
        $cek_email = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if(mysqli_num_rows($cek_email) > 0) {
            $error = 'Email sudah terdaftar!';
        } else {
            $password_hash = md5($password);
            $query = mysqli_query($conn, "INSERT INTO users (nama, email, no_hp, password, role) 
                                          VALUES ('$nama', '$email', '$no_hp', '$password_hash', 'user')");
            
            if($query) {
                // Auto login setelah registrasi berhasil
                $user_id = mysqli_insert_id($conn);
                
                // Set session
                $_SESSION['user_id'] = $user_id;
                $_SESSION['nama'] = $nama;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = 'user';
                
                // Redirect ke halaman utama
                redirect('index.php');
            } else {
                $error = 'Registrasi gagal! ' . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - PENTAS.HUB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h2 class="auth-title">
                <i class="fas fa-user-plus"></i> REGISTER
            </h2>
            
            <?php if($error): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control" placeholder="Masukkan nomor HP">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password" required>
                </div>
                
                <button type="submit" name="register" class="btn btn-custom w-100 mb-3">
                    <i class="fas fa-user-plus"></i> DAFTAR
                </button>
                
                <div class="text-center">
                    <p>Sudah punya akun? <a href="login.php" style="color: #8b00ff;">Login disini</a></p>
                    <a href="index.php" style="color: #ccc;">
                        <i class="fas fa-arrow-left"></i> Kembali ke Home
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>