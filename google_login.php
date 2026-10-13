<?php
session_start();
$googleConfig = require __DIR__ . '/includes/google_oauth_config.php';

if ($googleConfig['client_id'] === '' || $googleConfig['client_secret'] === '') {
    $_SESSION['google_login_error'] = 'Login Google belum dikonfigurasi. Hubungi administrator aplikasi.';
    header('Location: login.php');
    exit;
}

$state = bin2hex(random_bytes(32));
$_SESSION['google_oauth_state'] = $state;

$parameters = [
    'client_id' => $googleConfig['client_id'],
    'redirect_uri' => $googleConfig['redirect_uri'],
    'response_type' => 'code',
    'scope' => 'openid email profile',
    'state' => $state,
    'prompt' => 'select_account',
];

header('Location: https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($parameters, '', '&', PHP_QUERY_RFC3986));
exit;
