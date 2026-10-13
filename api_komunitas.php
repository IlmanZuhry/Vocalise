<?php
require_once 'core.php';
if (session_status() == PHP_SESSION_NONE) session_start();

header('Content-Type: application/json');

$app = new manz();
$app->ensureSession();
$currentUser = $app->getCurrentUser();

if (!$currentUser) {
    http_response_code(401);
    echo json_encode(['status' => false, 'message' => 'Anda harus login']);
    exit;
}

$action = $_POST['action'] ?? '';
$postId = $_POST['post_id'] ?? '';

if (empty($postId)) {
    http_response_code(400);
    echo json_encode(['status' => false, 'message' => 'Post ID tidak ditemukan']);
    exit;
}

if ($action === 'toggle_like') {
    $result = $app->toggleLike($postId, $currentUser['Id_User']);
    $likeCount = $app->getLikeCount($postId);
    $isLiked = $app->getUserLikeStatus($postId, $currentUser['Id_User']);
    
    if ($result) {
        echo json_encode([
            'status' => true,
            'message' => $isLiked ? 'Disukai' : 'Tidak disukai',
            'like_count' => $likeCount,
            'is_liked' => $isLiked
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['status' => false, 'message' => 'Gagal mengubah like']);
    }
    exit;
}

if ($action === 'add_comment') {
    $content = trim($_POST['content'] ?? '');

    if (empty($content)) {
        http_response_code(400);
        echo json_encode(['status' => false, 'message' => 'Komentar tidak boleh kosong']);
        exit;
    }

    $result = $app->addComment($postId, $currentUser['Id_User'], $content);
    $commentCount = $app->getCommentCount($postId);

    if ($result) {
        echo json_encode([
            'status' => true,
            'message' => 'Komentar berhasil ditambahkan',
            'comment_count' => $commentCount
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['status' => false, 'message' => 'Gagal menambahkan komentar']);
    }
    exit;
}

if ($action === 'get_comments') {
    $comments = $app->getComments($postId);
    echo json_encode([
        'status' => true,
        'comments' => $comments
    ]);
    exit;
}

http_response_code(400);
echo json_encode(['status' => false, 'message' => 'Action tidak dikenali']);
