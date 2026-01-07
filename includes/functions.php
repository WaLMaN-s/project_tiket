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
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Redirect
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Upload gambar (hanya untuk admin)
function uploadGambar($file) {
    $target_dir = "../assets/images/tiket/";
    $file_name = time() . '_' . basename($file["name"]);
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    $check = getimagesize($file["tmp_name"]);
    if($check === false) return false;
    if ($file["size"] > 5000000) return false;
    if(!in_array($imageFileType, ["jpg", "jpeg", "png"])) return false;
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $file_name;
    }
    return false;
}
?>