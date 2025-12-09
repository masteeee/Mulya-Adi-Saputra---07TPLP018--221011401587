<?php
// Language configuration
$lang = [
    'en' => [
        'welcome' => 'Welcome',
        'home' => 'Home',
        'about' => 'About Us',
        'services' => 'Services',
        'gallery' => 'Gallery',
        'contact' => 'Contact Us',
        'login' => 'Login',
        'logout' => 'Logout',
        'signin' => 'Sign In',
        'language' => 'Language',
        'english' => 'English',
        'indonesian' => 'Indonesian',
        'book_event' => 'Book Your Event',
        'admin_panel' => 'Admin Panel',
        'backup_restore' => 'Backup & Restore',
        'event_reports' => 'Event Reports',
        'system_logs' => 'System Logs'
    ],
    'id' => [
        'welcome' => 'Selamat Datang',
        'home' => 'Beranda',
        'about' => 'Tentang Kami',
        'services' => 'Layanan',
        'gallery' => 'Galeri',
        'contact' => 'Hubungi Kami',
        'login' => 'Masuk',
        'logout' => 'Keluar',
        'signin' => 'Daftar',
        'language' => 'Bahasa',
        'english' => 'Inggris',
        'indonesian' => 'Indonesia',
        'book_event' => 'Pesan Acara',
        'admin_panel' => 'Panel Admin',
        'backup_restore' => 'Cadangkan & Pulihkan',
        'event_reports' => 'Laporan Acara',
        'system_logs' => 'Catatan Sistem'
    ]
];

// Set default language to English if not set
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
}

// Change language if requested
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'id'])) {
    $_SESSION['lang'] = $_GET['lang'];
    setcookie('lang', $_SESSION['lang'], time() + (86400 * 30), "/"); // 30 days
} elseif (isset($_COOKIE['lang'])) {
    $_SESSION['lang'] = $_COOKIE['lang'];
}

// Function to get translation
function t($key) {
    global $lang;
    $current_lang = $_SESSION['lang'] ?? 'en';
    return $lang[$current_lang][$key] ?? $key;
}
?>
