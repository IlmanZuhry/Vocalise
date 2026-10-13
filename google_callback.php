<?php
require_once 'core.php';
session_start();

function googleLoginFail($message) {
    $_SESSION['google_login_error'] = $message;
    header('Location: login.php');
    exit;
}

function googlePostForm($url, array $data) {
    $payload = http_build_query($data, '', '&', PHP_QUERY_RFC3986);
    if (function_exists('curl_init')) {
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_TIMEOUT => 15,
        ]);
        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return [$status, $response];
    }
    return [0, false];
}

function googleGetJson($url, $accessToken) {
    if (!function_exists('curl_init')) return [0, false];
    $curl = curl_init($url);
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $accessToken],
        CURLOPT_TIMEOUT => 15,
    ]);
    $response = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    return [$status, $response];
}

if (!empty($_GET['error'])) googleLoginFail('Login Google dibatalkan atau ditolak.');
if (empty($_GET['code']) || empty($_GET['state']) || empty($_SESSION['google_oauth_state']) || !hash_equals($_SESSION['google_oauth_state'], $_GET['state'])) {
    googleLoginFail('Sesi login Google tidak valid. Silakan coba lagi.');
}
unset($_SESSION['google_oauth_state']);

$googleConfig = require __DIR__ . '/includes/google_oauth_config.php';
if ($googleConfig['client_id'] === '' || $googleConfig['client_secret'] === '') googleLoginFail('Login Google belum dikonfigurasi.');

[$tokenStatus, $tokenResponse] = googlePostForm('https://oauth2.googleapis.com/token', [
    'code' => $_GET['code'],
    'client_id' => $googleConfig['client_id'],
    'client_secret' => $googleConfig['client_secret'],
    'redirect_uri' => $googleConfig['redirect_uri'],
    'grant_type' => 'authorization_code',
]);
$token = json_decode($tokenResponse ?: '', true);
if ($tokenStatus !== 200 || empty($token['access_token'])) googleLoginFail('Token Google tidak dapat diverifikasi. Silakan coba lagi.');

[$profileStatus, $profileResponse] = googleGetJson('https://openidconnect.googleapis.com/v1/userinfo', $token['access_token']);
$profile = json_decode($profileResponse ?: '', true);
if ($profileStatus !== 200 || empty($profile['sub']) || empty($profile['email']) || empty($profile['email_verified'])) {
    googleLoginFail('Akun Google harus memiliki alamat e-mail yang sudah terverifikasi.');
}

$app = new manz();
$user = $app->getUserByGoogleSub($profile['sub']);
if (!$user) {
    $user = $app->createGoogleUser($profile['sub'], $profile['email'], $profile['name'] ?? '', $profile['picture'] ?? '');
}
if (!$user) googleLoginFail('Akun Google tidak dapat dibuat. Silakan coba lagi.');

$app->setSessionFromUser($user);
header('Location: Beranda.php');
exit;
