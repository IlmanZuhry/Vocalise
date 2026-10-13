<?php
require_once '../core.php';

$app = new manz();
$mentor = $app->getCurrentMentor();

if (!$mentor) {
    header('Location: loginad.php');
    exit;
}

$feedback = '';
$feedbackType = 'success';

function adminRedirectEbook($params = []) {
    $url = 'ebook.php';
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    header('Location: ' . $url);
    exit;
}

function saveAdminUpload($fieldName, $allowedMime, $allowedExt, $prefix, &$error) {
    if (empty($_FILES[$fieldName]['tmp_name']) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        $error = 'File ' . $fieldName . ' wajib diupload.';
        return false;
    }

    $originalName = $_FILES[$fieldName]['name'] ?? '';
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $mime = mime_content_type($_FILES[$fieldName]['tmp_name']);

    if (!in_array($extension, $allowedExt, true) || !in_array($mime, $allowedMime, true)) {
        $error = 'Format file ' . $fieldName . ' tidak didukung.';
        return false;
    }

    $uploadDir = dirname(__DIR__) . '/assets/ebook';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true)) {
        $error = 'Folder upload e-book tidak bisa dibuat.';
        return false;
    }

    $fileName = $prefix . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
    $targetPath = $uploadDir . '/' . $fileName;

    if (!move_uploaded_file($_FILES[$fieldName]['tmp_name'], $targetPath)) {
        $error = 'Gagal menyimpan file ' . $fieldName . '.';
        return false;
    }

    return 'assets/ebook/' . $fileName;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $title = trim($_POST['title'] ?? '');
        $author = trim($_POST['author'] ?? '');
        $pages = (int) ($_POST['pages'] ?? 0);

        if ($title === '' || $author === '') {
            adminRedirectEbook(['error' => 'Judul dan nama penulis wajib diisi.']);
        }

        $uploadError = '';
        $pdfPath = saveAdminUpload('pdf_file', ['application/pdf', 'application/x-pdf'], ['pdf'], 'ebook_pdf', $uploadError);
        if (!$pdfPath) {
            adminRedirectEbook(['error' => $uploadError]);
        }

        $thumbnailPath = saveAdminUpload(
            'thumbnail_file',
            ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
            ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'ebook_thumb',
            $uploadError
        );

        if (!$thumbnailPath) {
            adminRedirectEbook(['error' => $uploadError]);
        }

        if ($app->createEbook($title, $author, $pages, $thumbnailPath, $pdfPath)) {
            adminRedirectEbook(['success' => 'E-book berhasil ditambahkan dan sudah terhubung ke halaman user.']);
        }

        adminRedirectEbook(['error' => 'Data e-book gagal disimpan.']);
    }

    if ($action === 'delete') {
        $ebookId = (int) ($_POST['ebook_id'] ?? 0);
        if ($app->deleteEbook($ebookId)) {
            adminRedirectEbook(['success' => 'E-book berhasil dihapus dari daftar.']);
        }

        adminRedirectEbook(['error' => 'E-book gagal dihapus.']);
    }
}

if (!empty($_GET['success'])) {
    $feedback = $_GET['success'];
    $feedbackType = 'success';
} elseif (!empty($_GET['error'])) {
    $feedback = $_GET['error'];
    $feedbackType = 'error';
}

$ebooks = $app->getEbooks();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TALKLAB - E-Book Mentor</title>
    <?php include 'inc/layout_css.php'; ?>
    <style>
        .ebook-layout {
            align-items: start;
            display: grid;
            gap: 22px;
            grid-template-columns: minmax(320px, 420px) minmax(0, 1fr);
        }

        .form-grid {
            display: grid;
            gap: 16px;
        }

        .field label {
            color: #344054;
            display: block;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 7px;
        }

        .field input {
            background: #fff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            color: #142033;
            outline: none;
            padding: 12px;
            width: 100%;
        }

        .field input:focus {
            border-color: #1c407a;
            box-shadow: 0 0 0 3px rgb(28 64 122 / 0.14);
        }

        .notice {
            border-radius: 8px;
            margin-bottom: 18px;
            padding: 13px 15px;
        }

        .notice.success {
            background: #dcfae6;
            color: #067647;
        }

        .notice.error {
            background: #fee4e2;
            color: #b42318;
        }

        .ebook-list {
            display: grid;
            gap: 14px;
        }

        .ebook-item {
            align-items: center;
            display: grid;
            gap: 16px;
            grid-template-columns: 82px minmax(0, 1fr) auto;
        }

        .ebook-thumb {
            aspect-ratio: 3 / 4;
            border-radius: 8px;
            height: 110px;
            object-fit: cover;
            width: 82px;
        }

        .ebook-item h2 {
            color: #10204f;
            font-size: 20px;
            margin: 0 0 6px;
        }

        .ebook-item p {
            color: #667085;
            margin: 0 0 4px;
        }

        .ebook-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        .admin-button.secondary {
            background: #eef2f7;
            color: #10204f;
        }

        .admin-button.danger {
            background: #b42318;
            color: #fff;
        }

        .empty {
            border: 1px dashed #c7cfdb;
            border-radius: 8px;
            color: #667085;
            padding: 34px 20px;
            text-align: center;
        }

        @media (max-width: 1080px) {
            .ebook-layout {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 720px) {
            .ebook-item {
                grid-template-columns: 70px minmax(0, 1fr);
            }

            .ebook-actions {
                grid-column: 1 / -1;
                justify-content: flex-start;
            }
        }
    </style>
</head>
<body>
    <?php include 'inc/header.php'; ?>
    <?php include 'inc/sidebar.php'; ?>

    <main>
        <h1 class="admin-page-title">E-Book</h1>
        <p class="admin-page-subtitle">Upload PDF, judul, penulis, dan thumbnail untuk ditampilkan di halaman e-book user.</p>

        <?php if ($feedback !== ''): ?>
            <div class="notice <?= $feedbackType ?>"><?= htmlspecialchars($feedback) ?></div>
        <?php endif; ?>

        <section class="ebook-layout">
            <article class="admin-panel">
                <h2>Tambah E-Book</h2>
                <form class="form-grid" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="create">

                    <div class="field">
                        <label for="title">Judul</label>
                        <input id="title" name="title" type="text" maxlength="180" required>
                    </div>

                    <div class="field">
                        <label for="author">Nama Penulis</label>
                        <input id="author" name="author" type="text" maxlength="120" required>
                    </div>

                    <div class="field">
                        <label for="pages">Jumlah Halaman</label>
                        <input id="pages" name="pages" type="number" min="0" value="0">
                    </div>

                    <div class="field">
                        <label for="pdf_file">File PDF</label>
                        <input id="pdf_file" name="pdf_file" type="file" accept="application/pdf,.pdf" required>
                    </div>

                    <div class="field">
                        <label for="thumbnail_file">Gambar Thumbnail</label>
                        <input id="thumbnail_file" name="thumbnail_file" type="file" accept="image/*" required>
                    </div>

                    <button class="admin-button" type="submit">Simpan E-Book</button>
                </form>
            </article>

            <article class="admin-panel">
                <h2>Daftar E-Book User</h2>
                <div class="ebook-list">
                    <?php if (empty($ebooks)): ?>
                        <div class="empty">Belum ada e-book.</div>
                    <?php endif; ?>

                    <?php foreach ($ebooks as $ebook): ?>
                        <div class="admin-card ebook-item">
                            <img class="ebook-thumb" src="../<?= htmlspecialchars($ebook['thumbnail_path']) ?>" alt="Thumbnail <?= htmlspecialchars($ebook['title']) ?>">
                            <div>
                                <h2><?= htmlspecialchars($ebook['title']) ?></h2>
                                <p><?= htmlspecialchars($ebook['author']) ?></p>
                                <?php if ((int) $ebook['pages'] > 0): ?>
                                    <p><?= (int) $ebook['pages'] ?> halaman</p>
                                <?php endif; ?>
                            </div>
                            <div class="ebook-actions">
                                <a class="admin-button secondary" href="../<?= htmlspecialchars($ebook['pdf_path']) ?>" target="_blank">Buka PDF</a>
                                <form method="POST" onsubmit="return confirm('Hapus e-book ini dari daftar user?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="ebook_id" value="<?= (int) $ebook['id'] ?>">
                                    <button class="admin-button danger" type="submit">Hapus</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </article>
        </section>
    </main>
</body>
</html>
