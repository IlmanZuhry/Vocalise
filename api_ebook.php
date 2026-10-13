<?php
require_once 'core.php';
if (session_status() == PHP_SESSION_NONE) session_start();

header('Content-Type: application/json');

$app = new manz();
$app->ensureSession();
$currentUser = $app->getCurrentUser();

if (!$currentUser) {
    http_response_code(401);
    echo json_encode(['status' => false, 'message' => 'Anda harus login untuk mencatat aktivitas e-book.']);
    exit;
}

$action = $_POST['action'] ?? '';
$ebookId = $_POST['ebook_id'] ?? '';

if ($action !== 'save_read') {
    http_response_code(400);
    echo json_encode(['status' => false, 'message' => 'Action tidak dikenali']);
    exit;
}

if (empty($ebookId)) {
    http_response_code(400);
    echo json_encode(['status' => false, 'message' => 'E-book tidak ditemukan']);
    exit;
}

$ebook = $app->getEbookById($ebookId);
if (!$ebook) {
    http_response_code(404);
    echo json_encode(['status' => false, 'message' => 'E-book tidak ditemukan']);
    exit;
}

$result = $app->saveEbookActivity($currentUser['Id_User'], $ebook['id'], $ebook['title']);
if ($result) {
    echo json_encode(['status' => true, 'message' => 'Aktivitas e-book tercatat']);
    exit;
}

http_response_code(500);
echo json_encode(['status' => false, 'message' => 'Gagal mencatat aktivitas e-book']);
