<?php
// Sidebar admin bersama - di-include dari tiap halaman admin.
$current = basename($_SERVER['SCRIPT_NAME']);
$nav_items = [
    'dashboard.php'            => ['icon' => 'fa-tachometer-alt', 'label' => 'Dashboard'],
    'data_tiket.php'           => ['icon' => 'fa-ticket-alt', 'label' => 'Data Tiket'],
    'data_pesanan.php'         => ['icon' => 'fa-receipt', 'label' => 'Data Pesanan'],
    'konfirmasi-pesanan.php'   => ['icon' => 'fa-check-circle', 'label' => 'Konfirmasi Pesanan'],
    'data_user.php'            => ['icon' => 'fa-users', 'label' => 'Data User'],
];
// Halaman form/edit ikut menyorot menu induknya di sidebar.
$active_map = [
    'edit_tiket.php' => 'data_tiket.php',
];
$active = $active_map[$current] ?? $current;
?>
<button class="menu-toggle" type="button" aria-label="Buka menu" onclick="document.querySelector('.admin-sidebar').classList.toggle('show')">
    <i class="fas fa-bars"></i>
</button>

<aside class="admin-sidebar">
    <div class="p-3 border-bottom" style="border-color: rgba(168,85,247,0.3) !important;">
        <a href="dashboard.php" class="navbar-brand" style="font-size: 1.3rem;">
            ADMIN - PENTAS<span>.HUB</span>
        </a>
    </div>
    <nav class="mt-3">
        <?php foreach ($nav_items as $href => $item): ?>
            <a href="<?= $href ?>" class="admin-nav-link<?= $active === $href ? ' active' : '' ?>">
                <i class="fas <?= $item['icon'] ?>"></i> <?= $item['label'] ?>
            </a>
        <?php endforeach; ?>
        <a href="../logout.php" class="admin-nav-link">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </nav>
</aside>
