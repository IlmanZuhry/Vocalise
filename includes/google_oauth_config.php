<?php
/*
 * Simpan kredensial hanya di google_oauth_config.local.php atau environment PHP.
 * Berkas lokal tersebut telah diabaikan oleh Git.
 */
$googleScheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$googleHost = $_SERVER['HTTP_HOST'] ?? 'localhost';
$googleBasePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
$googleLocalConfig = __DIR__ . '/google_oauth_config.local.php';
$googleLocalValues = is_file($googleLocalConfig) ? require $googleLocalConfig : [];

return [
    'client_id' => $googleLocalValues['client_id'] ?? (getenv('GOOGLE_CLIENT_ID') ?: ''),
    'client_secret' => $googleLocalValues['client_secret'] ?? (getenv('GOOGLE_CLIENT_SECRET') ?: ''),
    'redirect_uri' => $googleLocalValues['redirect_uri'] ?? (getenv('GOOGLE_REDIRECT_URI') ?: $googleScheme . '://' . $googleHost . $googleBasePath . '/google_callback.php'),
];
