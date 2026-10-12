<?php


$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443);

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => '',
    'secure'   => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax',
]);

session_start();

date_default_timezone_set('Africa/Accra');

require_once __DIR__ . '/db_class.php';

// Base path of the project on the server, e.g. /Shoppn
// This makes redirects work no matter how deep the current file is.
if (!defined('BASE_URL')) {
    define('BASE_URL', 'nana.maman/ecommerce/Shoppn/');
}

function get_ip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    return $_SERVER['REMOTE_ADDR'] ?? '';
}

function redirect($url)
{
    // If it's already an absolute path (starts with / or http), use as-is.
    // Otherwise, treat it as relative to BASE_URL.
    if (!preg_match('#^(https?://|/)#', $url)) {
        // Strip leading ./ and ../ so older relative-style paths
        // (e.g. '../views/home.php') still resolve from the project root.
        $url = preg_replace('#^(\.\.?/)+#', '', $url);
        $url = BASE_URL . '/' . ltrim($url, '/');
    }

    header("Location: $url");
    exit();
}

function is_logged_in()
{
    return isset($_SESSION['customer_id']);
}

function is_admin()
{
    return isset($_SESSION['user_role']) &&
           (int) $_SESSION['user_role'] === 1;
}

function require_login()
{
    if (!is_logged_in()) {
        $_SESSION['error'] = 'Please login to continue.';
        redirect(BASE_URL . '/views/login.php');
    }
}

function require_admin()
{
    if (!is_admin()) {
        $_SESSION['error'] = 'Access denied.';
        redirect(BASE_URL . '/index.php');
    }
}