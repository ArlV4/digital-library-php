<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/project_perpustakaan/assets/css/style.css">
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<nav class="navbar">
    <div class="navbar-brand">
        <a href="/project_perpustakaan/index.php">
            <img src="/project_perpustakaan/assets/img/logo.png" alt="Perpustakaan Digital" class="logo-img">
            <span class="logo-text">Perpustakaan Digital</span>
        </a>
    </div>
    <div class="navbar-menu">
        <a href="/project_perpustakaan/index.php">Beranda</a>
        <a href="/project_perpustakaan/katalog.php">Katalog</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="/project_perpustakaan/admin/dashboard.php">Admin Panel</a>
            <?php else: ?>
                <a href="/project_perpustakaan/riwayat.php">Riwayat</a>
            <?php endif; ?>
            <a href="/project_perpustakaan/logout.php" onclick="konfirmasiLogout(event)">Logout</a>
        <?php else: ?>
            <a href="/project_perpustakaan/login.php">Login</a>
            <a href="/project_perpustakaan/register.php">Register</a>
        <?php endif; ?>
        <button class="dark-toggle" id="darkToggle" title="Toggle Dark Mode">🌙</button>
    </div>
</nav>

<?php if (isset($_SESSION['user_id'])): ?>
    <div class="login-bar">
        👋 Halo, <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong>!
        <?php if ($_SESSION['role'] == 'admin'): ?>
            <span class="badge-admin">Admin</span>
        <?php else: ?>
            <span class="badge-member">Member</span>
        <?php endif; ?>
    </div>
<?php endif; ?>

<div class="wrapper">