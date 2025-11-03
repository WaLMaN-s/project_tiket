<?php
// Format Rupiah
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// Format Tanggal Indonesia
function formatTanggal($tanggal) {
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    
    $split = explode('-', $tanggal);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

// Format Waktu
function formatWaktu($waktu) {
    return date('H:i', strtotime($waktu)) . ' WIB';
}

// Sanitasi input
function clean($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

// Alert Bootstrap
function alert($type, $message) {
    return '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
                ' . $message . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
}

// Redirect
function redirect($url) {
    echo "<script>window.location.href='" . $url . "';</script>";
    exit();
}

// Generate kode booking unik
function generateKodeBooking() {
    return 'PH' . date('Ymd') . rand(1000, 9999);
}

// Cek stok tiket
function cekStokTiket($tiket_id) {
    global $conn;
    $query = mysqli_query($conn, "SELECT stok FROM tiket WHERE id='$tiket_id'");
    $data = mysqli_fetch_assoc($query);
    return $data['stok'];
}

// Update stok tiket
function updateStokTiket($tiket_id, $jumlah, $operasi = 'kurang') {
    global $conn;
    
    if ($operasi == 'kurang') {
        $query = "UPDATE tiket SET stok = stok - $jumlah WHERE id = '$tiket_id'";
    } else {
        $query = "UPDATE tiket SET stok = stok + $jumlah WHERE id = '$tiket_id'";
    }
    
    mysqli_query($conn, $query);
    
    // Update status tiket jika stok habis
    $stok_sekarang = cekStokTiket($tiket_id);
    if ($stok_sekarang <= 0) {
        mysqli_query($conn, "UPDATE tiket SET status='habis' WHERE id='$tiket_id'");
    } else {
        mysqli_query($conn, "UPDATE tiket SET status='tersedia' WHERE id='$tiket_id'");
    }
}

// Upload gambar
function uploadGambar($file) {
    $target_dir = "../assets/images/tiket/";
    $file_name = time() . '_' . basename($file["name"]);
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Cek apakah file adalah gambar
    $check = getimagesize($file["tmp_name"]);
    if($check === false) {
        return false;
    }
    
    // Cek ukuran file (max 5MB)
    if ($file["size"] > 5000000) {
        return false;
    }
    
    // Hanya izinkan format tertentu
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        return false;
    }
    
    // Upload file
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $file_name;
    }
    
    return false;
}
?>