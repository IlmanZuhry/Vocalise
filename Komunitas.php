<?php
require_once 'core.php';
if (session_status() == PHP_SESSION_NONE) session_start();
$app = new manz();
$app->ensureSession();
$currentUser = $app->getCurrentUser();

$feedback = '';
$feedbackType = 'info';
$editPost = null;

function redirectWith(array $params = []){
    $url = 'Komunitas.php';
    if (!empty($params)){
        $url .= '?' . http_build_query($params);
    }
    header('Location: ' . $url);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$app->isLoggedIn()) {
        redirectWith(['error' => 'Anda harus masuk untuk melakukan aksi ini.']);
    }

    $action = $_POST['action'] ?? '';
    $content = trim($_POST['content'] ?? '');

    if ($action === 'create') {
        if ($content === '') {
            redirectWith(['error' => 'Isi postingan tidak boleh kosong.']);
        }
        if ($app->createCommunityPost($currentUser['Id_User'],$content)) {
            redirectWith(['success' => 'Postingan berhasil dibuat.']);
        }
        redirectWith(['error' => 'Gagal membuat postingan. Silakan coba lagi.']);
    }

    if ($action === 'update') {
        $postId = $_POST['post_id'] ?? '';
        if ($postId === '' || $title === '' || $content === '') {
            redirectWith(['error' => 'Data tidak lengkap untuk memperbarui postingan.']);
        }
        if ($app->updateCommunityPost($postId, $currentUser['Id_User'],$content)) {
            redirectWith(['success' => 'Postingan berhasil diperbarui.']);
        }
        redirectWith(['error' => 'Gagal memperbarui postingan.']);
    }

    if ($action === 'delete') {
        $postId = $_POST['post_id'] ?? '';
        if ($postId === '') {
            redirectWith(['error' => 'Postingan tidak ditemukan.']);
        }
        if ($app->deleteCommunityPost($postId, $currentUser['Id_User'])) {
            redirectWith(['success' => 'Postingan berhasil dihapus.']);
        }
        redirectWith(['error' => 'Gagal menghapus postingan.']);
    }
}

if (!empty($_GET['success'])) {
    $feedback = htmlspecialchars($_GET['success']);
    $feedbackType = 'success';
} elseif (!empty($_GET['error'])) {
    $feedback = htmlspecialchars($_GET['error']);
    $feedbackType = 'error';
}

if (!empty($_GET['edit']) && $currentUser) {
    $post = $app->getCommunityPostById($_GET['edit']);
    if ($post && $post['Id_User'] === $currentUser['Id_User']) {
        $editPost = $post;
    }
}

$posts = $app->getCommunityPosts();
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TALKLAB - Komunitas</title>
  <?php include 'includes/layout_css.php'; ?>
  <style>
    main {
      margin-left: 250px;
      margin-top: 64px;
      padding: 40px;
      flex-grow: 1;
      background-color: #f7f7fc;
      min-height: calc(90vh - 64px);
      overflow-y: auto;
    }

    main h1 {
      font-weight: 700;
      font-size: 28px;
      color: #101828;
      margin-bottom: 6px;
      margin-top: 20px;
    }

    main p.subheading {
      color: #8f95a6;
      font-weight: 400;
      font-size: 15px;
      margin-bottom: 24px;
    }

    .heart-icon{
    width:20px;
    height:20px;

    stroke:#6b7280;
    fill:transparent;

    transition:all .2s ease;

    flex-shrink:0;
}

    .btn-posting {
      float: right;
      background-color: #bca451;
      color: white;
      padding: 10px 25px;
      border: none;
      border-radius: 12px;
      font-size: 18px;
      cursor: pointer;
      transition: background-color 0.3s;
      user-select: none;
      margin-bottom: 24px;
      margin-top: -55px;
    }

    .btn-submit{
  background-color: #bca451;
  color: white;
  padding: 10px 25px;
  border: none;
  border-radius: 12px;
  font-size: 18px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.btn-submit:hover{
  background-color: #a5924a;
}

    .btn-posting:hover { background-color: #a5924a; }

    .clearfix::after { content: ""; display: table; clear: both; }

    .post-list { display: flex; flex-direction: column; gap: 20px; }

    article.post {
      background-color: white;
      padding: 20px 24px;
      border-radius: 16px;
      box-shadow: 0 2px 6px rgb(0 0 0 / 0.08);
      display: flex;
      flex-direction: column;
      gap: 12px;
      position: relative;
    }

    .post-header { display: flex; align-items: center; gap: 14px; }

    .post-avatar {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #bca451;
      flex-shrink: 0;
    }

    .post-avatar-default {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: #d2a06b;
    }

    .post-avatar-default svg {
      width: 28px;
      height: 28px;
    }

    .post-author-info { display: flex; flex-direction: column; gap: 4px; user-select: text; }
    .post-author { font-weight: 700; font-size: 16px; color: #101828; line-height: 1.1; }
    .post-username-time { font-size: 13px; color: #8f95a6; user-select: none; }

    .post-menu {
      position: absolute;
      top: 20px;
      right: 20px;
      background: none;
      border: none;
      font-size: 22px;
      color: #c4c4c4;
      cursor: pointer;
      user-select: none;
    }

    .post-menu:hover { color: #bca451; }

    .post-text {
      font-size: 15px;
      line-height: 1.5;
      color: #434445;
      background-color: #f5f5f5;
      border-radius: 12px;
      padding: 20px;
      white-space: pre-wrap;
      user-select: text;
      text-align: left;
      
    }

    .post-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 13px;
      color: #8f95a6;
      gap: 16px;
      flex-wrap: wrap;
    }

    .post-footer .reactions { display: flex; gap: 35px; user-select: none; align-items: center; }

    .reaction-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 12px;
      border-radius: 999px;
      border: 1px solid #e5e7eb;
      background: #f8fafc;
      color: #4b5563;
      font-weight: 700;
      cursor: pointer;
      transition: background-color 0.2s, border-color 0.2s, color 0.2s;
    }

    .reaction-btn:hover {
      background-color: #f3f4f6;
      border-color: #d1d5db;
      color: #bca451;
    }
    .heart-icon{
  width:20px;
  height:20px;

  stroke:#6b7280;
  fill:transparent;

  transition:all .2s ease;
}

.like-btn.liked{
  background:#fee2e2;
  border-color:#fecaca;
}

.like-btn.liked .heart-icon{
  fill:#ef4444;
  stroke:#ef4444;
}

.like-btn.liked .like-count{
  color:#ef4444;
}

    .reaction-btn span {
      min-width: 20px;
      text-align: center;
    }

    .post-share {
      cursor: pointer;
      font-weight: 600;
      color: #8f95a6;
      transition: color 0.3s;
      user-select: none;
      background: none;
      border: none;
      padding: 0;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .post-share:hover { color: #bca451; }

    .alert {
      padding: 16px 20px;
      border-radius: 14px;
      margin-bottom: 24px;
      font-size: 15px;
      max-width: 900px;
    }

    .alert.success { background-color: #e6f4ea; color: #175c3f; }
    .alert.error { background-color: #fce8e6; color: #9a2a22; }

    .post-form {
      background: white;
      padding: 24px;
      border-radius: 16px;
      box-shadow: 0 2px 10px rgb(0 0 0 / 0.08);
      margin-bottom: 30px;
      max-width: 900px;
    }

    .post-form h2 {
      margin-top: 0;
      margin-bottom: 18px;
      font-size: 22px;
      color: #101828;
    }

    .form-group { margin-bottom: 18px; }
    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: #4b5563;
      font-weight: 600;
    }

    .form-group input,
    .form-group textarea {
      width: 100%;
      padding: 14px 16px;
      border: 1px solid #d1d5db;
      border-radius: 12px;
      font-size: 15px;
      color: #111827;
      background: #f8fafc;
    }

    .form-group textarea { min-height: 140px; resize: vertical; }

    .form-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      align-items: center;
    }

    .btn-cancel {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 10px 24px;
      color: #374151;
      background: #f3f4f6;
      border-radius: 12px;
      text-decoration: none;
      font-size: 15px;
    }

    .info-box {
      background: white;
      max-width: 900px;
      padding: 18px 22px;
      border-radius: 16px;
      box-shadow: 0 2px 10px rgb(0 0 0 / 0.05);
      margin-bottom: 30px;
      color: #334155;
      font-size: 15px;
    }

    .post-list { display: flex; flex-direction: column; gap: 20px; }

    .post-actions {
      margin-left: auto;
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .post-action,
    .post-action.delete {
      font-size: 13px;
      font-weight: 700;
      color: #6b7280;
      background: none;
      border: none;
      cursor: pointer;
      text-decoration: none;
      padding: 6px 10px;
      border-radius: 10px;
    }

    .post-action.delete { color: #b91c1c; }

    .post-title {
      margin: 0;
      font-size: 18px;
      font-weight: 700;
      color: #111827;
    }

    .empty-state {
      padding: 24px;
      border-radius: 16px;
      background: white;
      border: 1px dashed #cbd5e1;
      color: #334155;
      max-width: 900px;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    .modal.active { display: flex; align-items: center; justify-content: center; }

    .modal-content {
      background-color: white;
      border-radius: 16px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.16);
      width: 90%;
      max-width: 600px;
      max-height: 80vh;
      display: flex;
      flex-direction: column;
      animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
      from { transform: translateY(30px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    .modal-header {
      padding: 24px;
      border-bottom: 1px solid #e5e7eb;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .modal-header h2 {
      margin: 0;
      font-size: 20px;
      font-weight: 700;
      color: #101828;
    }

    .modal-close {
      background: none;
      border: none;
      font-size: 28px;
      color: #6b7280;
      cursor: pointer;
      padding: 0;
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .modal-close:hover { color: #111827; }

    .modal-body {
      flex: 1;
      overflow-y: auto;
      padding: 24px;
    }

    .comments-list {
      display: flex;
      flex-direction: column;
      gap: 16px;
      margin-bottom: 24px;
    }

    .comment-item {
      background-color: #f8fafc;
      border-radius: 12px;
      padding: 16px;
    }

    .comment-header {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 8px;
    }

    .comment-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #bca451;
    }

    .comment-avatar-default {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: #d2a06b;
      flex-shrink: 0;
    }

    .comment-avatar-default svg {
      width: 20px;
      height: 20px;
    }

    .comment-author {
      font-weight: 700;
      font-size: 14px;
      color: #101828;
    }

    .comment-time {
      font-size: 12px;
      color: #8f95a6;
    }

    .comment-content {
      font-size: 14px;
      color: #434445;
      line-height: 1.5;
      white-space: pre-wrap;
    }

    .empty-comments {
      text-align: center;
      padding: 32px 16px;
      color: #8f95a6;
      font-size: 14px;
    }

    .modal-footer {
      border-top: 1px solid #e5e7eb;
      padding: 20px 24px;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .comment-form {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .comment-form textarea {
      width: 100%;
      padding: 12px 16px;
      border: 1px solid #d1d5db;
      border-radius: 12px;
      font-size: 14px;
      color: #111827;
      background: #f8fafc;
      resize: vertical;
      min-height: 80px;
      font-family: inherit;
    }

    .comment-form textarea:focus {
      outline: none;
      border-color: #bca451;
      background: white;
    }

    .comment-form-actions {
      display: flex;
      gap: 8px;
      justify-content: flex-end;
    }

    .btn-comment-cancel {
      padding: 8px 16px;
      background: #f3f4f6;
      border: none;
      border-radius: 8px;
      color: #374151;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
    }

    .btn-comment-submit {
      padding: 8px 16px;
      background: #bca451;
      border: none;
      border-radius: 8px;
      color: white;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
    }

    .btn-comment-submit:hover { background: #a5924a; }
    .btn-comment-cancel:hover { background: #e5e7eb; }

    .comment-loading {
      text-align: center;
      padding: 24px;
      color: #8f95a6;
    }

    @media (max-width: 1024px) {
      main { padding: 24px; margin-left: 0; }
      .btn-posting { float: none; display: block; width: 100%; margin-top: 12px; }
      .modal-content { width: 95%; max-height: 90vh; }
    }
  </style>
</head>

<body>
  <?php include 'includes/header.php'; ?>
  <?php include 'includes/sidebar.php'; ?>

  <main role="main" tabindex="-1">
    <div class="clearfix">
      <h1 style="font-size:40px; font-weight:700; color:#000;">Komunitas</h1>
      <p style="color:#777; font-size:20px; margin-bottom:30px;">Berbagi pengalaman dan belajar bersama</p>
      <?php if ($currentUser): ?>
        <button class="btn-posting" id="toggle-post-form" type="button" aria-label="Buat Postingan">Buat Postingan</button>
      <?php endif; ?>
    </div>

    <?php if ($feedback): ?>
      <div class="alert <?= $feedbackType ?>"><?= $feedback ?></div>
    <?php endif; ?>

    <?php if ($currentUser): ?>
      <section class="post-form" id="post-form" style="<?= $editPost ? 'display:block;' : 'display:none;' ?>">
        <h2><?= $editPost ? 'Sunting Postingan' : 'Tulis Postingan Baru' ?></h2>
        <form action="Komunitas.php" method="post">
          <input type="hidden" name="action" value="<?= $editPost ? 'update' : 'create' ?>">
          <?php if ($editPost): ?>
            <input type="hidden" name="post_id" value="<?= htmlspecialchars($editPost['Id']) ?>">
          <?php endif; ?>

          <div class="form-group">
            <label for="content">Isi</label>
            <textarea id="content" name="content" required><?= htmlspecialchars($editPost['Isi'] ?? '') ?></textarea>
          </div>

          <div class="form-actions">
            <button class="btn-submit" type="submit"><?= $editPost ? 'Simpan Perubahan' : 'Posting' ?></button>
            <?php if ($editPost): ?>
              <a href="Komunitas.php" class="btn-cancel">Batal</a>
            <?php endif; ?>
          </div>
        </form>
      </section>
    <?php else: ?>
      <div class="info-box">Silakan <a href="login.php">masuk</a> atau <a href="regis.php">daftar</a> untuk membuat postingan komunitas.</div>
    <?php endif; ?>

    <section class="post-list" aria-label="Daftar posting komunitas">
      <?php if (empty($posts)): ?>
        <div class="empty-state">Belum ada postingan. Jadilah yang pertama untuk berbagi!</div>
      <?php endif; ?>

      <?php foreach ($posts as $post): ?>
        <?php
$isLiked = false;

if($currentUser){
    $isLiked = $app->getUserLikeStatus(
        $post['Id'],
        $currentUser['Id_User']
    );
}
?>
        <article class="post" tabindex="0">
          <header class="post-header">
            <?php if (!empty($post['foto'])): ?>
              <img src="<?= htmlspecialchars($post['foto']) ?>" alt="Foto profil <?= htmlspecialchars($post['name'] ?: 'Pengguna') ?>" class="post-avatar" width="44" height="44">
            <?php else: ?>
              <div class="post-avatar post-avatar-default" aria-label="Foto profil default <?= htmlspecialchars($post['name'] ?: 'Pengguna') ?>">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="white" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
              </div>
            <?php endif; ?>
            <div class="post-author-info">
              <div class="post-author"><?= htmlspecialchars($post['name'] ?: 'Pengguna') ?></div>
              <div class="post-username-time">@<?= htmlspecialchars($post['username'] ?: 'guest') ?> • <?= htmlspecialchars(date('d M Y H:i', strtotime($post['Dibuat']))) ?></div>
            </div>
            <?php if ($currentUser && $post['Id_User'] === $currentUser['Id_User']): ?>
              <div class="post-actions">
                <a class="post-action" href="Komunitas.php?edit=<?= urlencode($post['Id']) ?>">Edit</a>
                <form action="Komunitas.php" method="post" onsubmit="return confirm('Hapus postingan ini?');">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['Id']) ?>">
                  <button type="submit" class="post-action delete">Hapus</button>
                </form>
              </div>
            <?php endif; ?>
          </header>

          
          <p class="post-text"><?= nl2br(htmlspecialchars($post['Isi'])) ?></p>
          <footer class="post-footer">
            <div class="reactions" aria-label="Reaksi postingan">
              <button
    type="button"
    class="reaction-btn like-btn <?= $isLiked ? 'liked' : '' ?>" data-post-id="<?= $post['Id'] ?>" data-action="like" aria-label="Suka">
                <svg
    class="heart-icon"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    stroke-width="2"
    stroke-linecap="round"
    stroke-linejoin="round"
>

    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>

</svg>
                <span class="like-count"><?= $app->getLikeCount($post['Id']) ?></span>
              </button>
              <button type="button" class="reaction-btn comment-btn" data-post-id="<?= $post['Id'] ?>" data-action="comment" aria-label="Komentar">
                <img src="icon/komen.svg" style="width:20px; height:20px;" alt="Komentar">
                <span class="comment-count"><?= $app->getCommentCount($post['Id']) ?></span>
              </button>
            </div>
          </footer>
        </article>
      <?php endforeach; ?>
    </section>
  </main>

  <!-- Modal Komentar -->
  <div id="commentModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Komentar</h2>
        <button class="modal-close" id="closeCommentModal" aria-label="Tutup">&times;</button>
      </div>
      <div class="modal-body">
        <div class="comments-list" id="commentsList"></div>
      </div>
      <div class="modal-footer">
        <form class="comment-form" id="commentForm">
          <textarea id="commentInput" placeholder="Tulis komentar Anda..." required></textarea>
          <div class="comment-form-actions">
            <button type="button" class="btn-comment-cancel" id="cancelComment">Batal</button>
            <button type="submit" class="btn-comment-submit">Posting</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('toggle-post-form')?.addEventListener('click', function () {
      const form = document.getElementById('post-form');
      if (!form) return;
      const isHidden = form.style.display === 'none' || form.style.display === '';
      form.style.display = isHidden ? 'block' : 'none';
      this.textContent = isHidden ? 'Batalkan' : 'Buat Postingan';
      this.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
    });

    // Handle like button
    document.querySelectorAll('.like-btn').forEach(btn => {
      btn.addEventListener('click', async function() {
        const postId = this.dataset.postId;
        const formData = new FormData();
        formData.append('action', 'toggle_like');
        formData.append('post_id', postId);

        try {
          const response = await fetch('api_komunitas.php', {
            method: 'POST',
            body: formData
          });

          const data = await response.json();

         if (data.status) {

    const countSpan = this.querySelector('.like-count');

    countSpan.textContent = data.like_count;

    if(data.is_liked){
        this.classList.add('liked');
    }else{
        this.classList.remove('liked');
    }

}else {
            if (response.status === 401) {
              window.location.href = 'login.php';
            } else {
              alert(data.message || 'Gagal mengubah like');
            }
          }
        } catch (error) {
          console.error('Error:', error);
          alert('Terjadi kesalahan');
        }
      });
    });

    // Handle comment button
    document.querySelectorAll('.comment-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const postId = this.dataset.postId;
        openCommentModal(postId, this);
      });
    });

    let currentPostId = null;
    let currentCommentBtn = null;

    function openCommentModal(postId, btn) {
      currentPostId = postId;
      currentCommentBtn = btn;
      const modal = document.getElementById('commentModal');
      const commentsList = document.getElementById('commentsList');

      commentsList.innerHTML = '<div class="comment-loading">Memuat komentar...</div>';
      modal.classList.add('active');

      // Ambil daftar komentar
      const formData = new FormData();
      formData.append('action', 'get_comments');
      formData.append('post_id', postId);

      fetch('api_komunitas.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status && data.comments) {
          renderComments(data.comments);
        } else {
          commentsList.innerHTML = '<div class="empty-comments">Belum ada komentar</div>';
        }
      })
      .catch(error => {
        console.error('Error:', error);
        commentsList.innerHTML = '<div class="empty-comments">Gagal memuat komentar</div>';
      });
    }

    function renderComments(comments) {
      const commentsList = document.getElementById('commentsList');

      if (comments.length === 0) {
        commentsList.innerHTML = '<div class="empty-comments">Belum ada komentar</div>';
        return;
      }

      commentsList.innerHTML = comments.map(comment => `
        <div class="comment-item">
          <div class="comment-header">
            ${renderCommentAvatar(comment)}
            <div style="flex-grow: 1;">
              <div class="comment-author">${escapeHtml(comment.Nama || 'Pengguna')}</div>
              <div style="font-size: 12px; color: #8f95a6;">@${escapeHtml(comment.Username || 'guest')} • ${formatDate(comment.created_at)}</div>
            </div>
          </div>
          <div class="comment-content">${escapeHtml(comment.content)}</div>
        </div>
      `).join('');
    }

    function renderCommentAvatar(comment) {
      if (comment.Foto) {
        return `<img src="${escapeAttribute(comment.Foto)}" alt="Foto profil ${escapeAttribute(comment.Nama || 'Pengguna')}" class="comment-avatar">`;
      }

      return `
        <div class="comment-avatar comment-avatar-default" aria-label="Foto profil default ${escapeAttribute(comment.Nama || 'Pengguna')}">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="white" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
        </div>
      `;
    }

    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    function escapeAttribute(text) {
      return escapeHtml(text).replace(/"/g, '&quot;');
    }

    function formatDate(dateStr) {
      const date = new Date(dateStr);
      const now = new Date();
      const diff = now - date;
      const minutes = Math.floor(diff / 60000);
      const hours = Math.floor(diff / 3600000);
      const days = Math.floor(diff / 86400000);

      if (minutes < 1) return 'Baru saja';
      if (minutes < 60) return `${minutes}m lalu`;
      if (hours < 24) return `${hours}j lalu`;
      if (days < 7) return `${days}h lalu`;

      return date.toLocaleDateString('id-ID', { day: 'short', month: 'short', year: 'numeric' });
    }

    const commentModal = document.getElementById('commentModal');
    const closeCommentModal = document.getElementById('closeCommentModal');
    const cancelCommentBtn = document.getElementById('cancelComment');
    const commentForm = document.getElementById('commentForm');
    const commentInput = document.getElementById('commentInput');

    closeCommentModal.addEventListener('click', () => {
      commentModal.classList.remove('active');
      commentForm.reset();
    });

    cancelCommentBtn.addEventListener('click', () => {
      commentModal.classList.remove('active');
      commentForm.reset();
    });

    commentModal.addEventListener('click', (e) => {
      if (e.target === commentModal) {
        commentModal.classList.remove('active');
        commentForm.reset();
      }
    });

    commentForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      const content = commentInput.value.trim();
      if (!content) {
        alert('Komentar tidak boleh kosong');
        return;
      }

      const formData = new FormData();
      formData.append('action', 'add_comment');
      formData.append('post_id', currentPostId);
      formData.append('content', content);

      try {
        const response = await fetch('api_komunitas.php', {
          method: 'POST',
          body: formData
        });

        const data = await response.json();

        if (data.status) {
          commentInput.value = '';

          // Update count di button
          if (currentCommentBtn) {
            const countSpan = currentCommentBtn.querySelector('.comment-count');
            countSpan.textContent = data.comment_count;
          }

          // Reload comments list
          const commentsList = document.getElementById('commentsList');
          commentsList.innerHTML = '<div class="comment-loading">Memuat komentar...</div>';

          const reloadData = new FormData();
          reloadData.append('action', 'get_comments');
          reloadData.append('post_id', currentPostId);

          const reloadRes = await fetch('api_komunitas.php', {
            method: 'POST',
            body: reloadData
          });

          const reloadJson = await reloadRes.json();
          if (reloadJson.status && reloadJson.comments) {
            renderComments(reloadJson.comments);
          }
        } else {
          if (response.status === 401) {
            window.location.href = 'login.php';
          } else {
            alert(data.message || 'Gagal menambahkan komentar');
          }
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
      }
    });
  </script>
</body>

</html>
