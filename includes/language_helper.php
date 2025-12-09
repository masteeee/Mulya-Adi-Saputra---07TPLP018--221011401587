<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set default language to English if not set
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
}

// Change language if requested
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'id'])) {
    $_SESSION['lang'] = $_GET['lang'];
    setcookie('lang', $_SESSION['lang'], time() + (86400 * 30), "/");
    
    // Redirect to remove the lang parameter from URL
    $redirect = strtok($_SERVER['REQUEST_URI'], '?');
    header("Location: $redirect");
    exit();
} elseif (isset($_COOKIE['lang'])) {
    $_SESSION['lang'] = $_COOKIE['lang'];
}

// Load language file
$lang = [];
$langFile = __DIR__ . '/languages/' . $_SESSION['lang'] . '.php';
if (file_exists($langFile)) {
    $lang = include $langFile;
} else {
    // Fallback to English if language file doesn't exist
    $lang = include __DIR__ . '/languages/en.php';
}

/**
 * Get a translated string
 * 
 * @param string $key The language key
 * @return string The translated string or the key if not found
 */
function t($key) {
    global $lang;
    return $lang[$key] ?? $key;
}
?>
