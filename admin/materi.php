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

function adminRedirectMateri($params = []) {
    $url = 'materi.php';
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    header('Location: ' . $url);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST)) {
        adminRedirectMateri(['error' => 'File video terlalu besar! Proses gagal karena melebihi batas maksimal upload server.']);
    }

    $action = $_POST['action'] ?? '';

    // Aksi CRUD Materi
    if ($action === 'create_material') {
        $id = trim($_POST['id'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $time_minutes = (int) ($_POST['time_minutes'] ?? 10);
        $icon_file = trim($_POST['icon_file'] ?? '');
        $color_class = trim($_POST['color_class'] ?? '');

        if ($color_class === '') $color_class = 'media-presentasi';

        if ($id === '' || $title === '') {
            adminRedirectMateri(['error' => 'ID dan Judul materi wajib diisi.']);
        }

        if ($app->createMaterial($id, $title, $description, $category, $time_minutes, $icon_file, $color_class)) {
            adminRedirectMateri(['success' => 'Materi berhasil ditambahkan.']);
        }

        adminRedirectMateri(['error' => 'Gagal menyimpan materi (Mungkin ID sudah ada).']);
    }

    if ($action === 'delete_material') {
        $id = trim($_POST['material_id'] ?? '');
        if ($app->deleteMaterial($id)) {
            adminRedirectMateri(['success' => 'Materi berhasil dihapus.']);
        }
        adminRedirectMateri(['error' => 'Materi gagal dihapus.']);
    }

    // Aksi CRUD Video
    if ($action === 'create_video') {
        $material_id = trim($_POST['material_id'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $video_url = trim($_POST['video_url'] ?? '');
        
        // Handle file upload
        if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/videos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = time() . '_' . preg_replace("/[^a-zA-Z0-9.-]/", "_", basename($_FILES['video_file']['name']));
            $targetFilePath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['video_file']['tmp_name'], $targetFilePath)) {
                $video_url = 'uploads/videos/' . $fileName;
            }
        }
        
        $script = trim($_POST['script'] ?? '');
        $order_index = (int) ($_POST['order_index'] ?? 1);

        if ($material_id === '' || $title === '') {
            adminRedirectMateri(['error' => 'Pilih materi dan isi judul video.']);
        }

        if ($app->addMaterialVideo($material_id, $title, $video_url, $script, $order_index)) {
            adminRedirectMateri(['success' => 'Video berhasil ditambahkan.']);
        }

        adminRedirectMateri(['error' => 'Gagal menyimpan video.']);
    }

    if ($action === 'delete_video') {
        $id = (int) ($_POST['video_id'] ?? 0);
        if ($app->deleteMaterialVideo($id)) {
            adminRedirectMateri(['success' => 'Video berhasil dihapus.']);
        }
        adminRedirectMateri(['error' => 'Video gagal dihapus.']);
    }

    if ($action === 'update_material') {
        $id = trim($_POST['id'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $time_minutes = (int) ($_POST['time_minutes'] ?? 10);
        $icon_file = trim($_POST['icon_file'] ?? '');
        $color_class = trim($_POST['color_class'] ?? '');
        
        // If hidden inputs were empty, fallback to defaults
        if ($icon_file === '') $icon_file = 'icon/mic.svg';
        if ($color_class === '') $color_class = 'vokal';

        if ($app->updateMaterial($id, $title, $description, $category, $time_minutes, $icon_file, $color_class)) {
            adminRedirectMateri(['success' => 'Materi berhasil diupdate.']);
        }
        adminRedirectMateri(['error' => 'Gagal mengupdate materi.']);
    }

    if ($action === 'update_video') {
        $id = (int) ($_POST['video_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $video_url = trim($_POST['video_url'] ?? '');
        
        // Handle file upload
        if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/videos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = time() . '_' . preg_replace("/[^a-zA-Z0-9.-]/", "_", basename($_FILES['video_file']['name']));
            $targetFilePath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['video_file']['tmp_name'], $targetFilePath)) {
                $video_url = 'uploads/videos/' . $fileName;
            }
        }
        
        $script = trim($_POST['script'] ?? '');
        $order_index = (int) ($_POST['order_index'] ?? 1);

        if ($app->updateMaterialVideo($id, $title, $video_url, $script, $order_index)) {
            adminRedirectMateri(['success' => 'Video berhasil diupdate.']);
        }
        adminRedirectMateri(['error' => 'Gagal mengupdate video.']);
    }
}

if (!empty($_GET['success'])) {
    $feedback = $_GET['success'];
    $feedbackType = 'success';
} elseif (!empty($_GET['error'])) {
    $feedback = $_GET['error'];
    $feedbackType = 'error';
}

$materials = $app->getMaterials();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TALKLAB - Manajemen Materi Mentor</title>
    <?php include 'inc/layout_css.php'; ?>
    <style>
        .materi-layout {
            align-items: start;
            display: grid;
            gap: 22px;
            grid-template-columns: minmax(320px, 420px) minmax(0, 1fr);
        }

        .materi-layout > div,
        .materi-layout > article,
        .admin-panel,
        .form-grid,
        .field {
            min-width: 0;
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

        .field input, .field select, .field textarea {
            background: #fff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            box-sizing: border-box;
            color: #142033;
            outline: none;
            padding: 12px;
            width: 100%;
            max-width: 100%;
        }

        .field input[type="file"] {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            padding-right: 0;
            min-width: 0;
            display: block;
            width: 100%;
        }

        .field input:focus, .field select:focus, .field textarea:focus {
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

        .materi-list {
            display: grid;
            gap: 14px;
        }

        .materi-item {
            display: grid;
            gap: 16px;
            grid-template-columns: 50px minmax(0, 1fr);
            align-items: start;
            padding: 16px;
        }

        .materi-icon {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Warna icon yang ada dari default */
        .vokal { background-color: #5f73b6; color: white; }
        .gerak-tubuh { background-color: #bddbec; color: #a6c8ca; }
        .kontak-mata { background-color: #c9db28; color: white; }
        .intonasi { background-color: #f41968; color: white; }
        .mengatasi-grogi { background-color: #f8cae0; color: #bb8aa1; }
        .gestur-tangan { background-color: #f16722; color: white; }
        .penyusunan-materi { background-color: #f1e64c; color: white; }
        .media-presentasi { background-color: #f8a435; color: white; }
        .warna-ungu { background-color: #8b5cf6; color: white; }
        .warna-teal { background-color: #14b8a6; color: white; }
        .warna-merah { background-color: #ef4444; color: white; }
        .warna-indigo { background-color: #4f46e5; color: white; }
        .warna-coklat { background-color: #8B4513; color: white; }
        .warna-abuabu { background-color: #64748b; color: white; }
        .warna-hitam { background-color: #0f172a; color: white; }
        .warna-magenta { background-color: #d946ef; color: white; }
        .warna-cyan { background-color: #06b6d4; color: white; }
        .warna-lime { background-color: #84cc16; color: white; }
        .warna-emerald { background-color: #10b981; color: white; }
        .warna-rose { background-color: #f43f5e; color: white; }
        .warna-sky { background-color: #0ea5e9; color: white; }
        .warna-amber { background-color: #f59e0b; color: white; }
        .warna-pink-muda { background-color: #f472b6; color: white; }
        .warna-maroon { background-color: #9f1239; color: white; }
        .warna-navy { background-color: #1e3a8a; color: white; }
        .warna-peach { background-color: #fb923c; color: white; }
        .warna-olive { background-color: #65a30d; color: white; }
        .warna-lavender { background-color: #a78bfa; color: white; }

        .materi-item h2 {
            color: #10204f;
            font-size: 20px;
            margin: 0 0 6px;
        }

        .materi-item p {
            color: #667085;
            margin: 0 0 4px;
            font-size: 14px;
        }

        .materi-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            flex-wrap: wrap;
            grid-column: 1 / -1;
            margin-top: 8px;
            padding-top: 16px;
            border-top: 1px dashed #e2e8f0;
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

        .video-list {
            margin-top: 10px;
            padding-left: 20px;
            border-left: 2px solid #e2e8f0;
        }

        .video-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px dashed #e2e8f0;
        }

        @media (max-width: 1080px) {
            .materi-layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'inc/header.php'; ?>
    <?php include 'inc/sidebar.php'; ?>

    <main>
        <h1 class="admin-page-title">Manajemen Materi</h1>
        <p class="admin-page-subtitle">Tambah dan hapus materi serta video pendukung untuk dipelajari peserta.</p>

        <?php if ($feedback !== ''): ?>
            <div class="notice <?= $feedbackType ?>"><?= htmlspecialchars($feedback) ?></div>
        <?php endif; ?>

        <section class="materi-layout">
            <div>
                <article class="admin-panel" style="margin-bottom:20px;">
                    <h2 id="form_material_title">Tambah Materi Utama</h2>
                    <form class="form-grid" method="POST" id="form_material">
                        <input type="hidden" name="action" id="action_material" value="create_material">
                        <input type="hidden" name="icon_file" id="icon_file" value="">

                        <div class="field">
                            <label for="id">ID (unik, tanpa spasi, ex: public_speaking)</label>
                            <input id="id" name="id" type="text" maxlength="50" required>
                            <small style="color:#666; font-size:12px;" id="id_help">Tidak bisa diubah saat edit.</small>
                        </div>
                        <div class="field">
                            <label for="title">Judul Materi</label>
                            <input id="title" name="title" type="text" maxlength="100" required>
                        </div>
                        <div class="field">
                            <label for="description">Deskripsi</label>
                            <input id="description" name="description" type="text" maxlength="255" required>
                        </div>
                        <div class="field">
                            <label for="category">Kategori</label>
                            <select id="category" name="category">
                                <option value="vokal">Vokal</option>
                                <option value="gerak tubuh">Gerak Tubuh</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="color_class">Tema Warna Latar</label>
                            <select id="color_class" name="color_class">
                                <option value="vokal">Biru Gelap</option>
                                <option value="gerak-tubuh">Biru Muda</option>
                                <option value="kontak-mata">Hijau</option>
                                <option value="intonasi">Merah Muda</option>
                                <option value="mengatasi-grogi">Pink</option>
                                <option value="gestur-tangan">Oranye</option>
                                <option value="penyusunan-materi">Kuning</option>
                                <option value="media-presentasi">Oranye Terang</option>
                                <option value="warna-ungu">Ungu</option>
                                <option value="warna-teal">Hijau Tosca</option>
                                <option value="warna-merah">Merah</option>
                                <option value="warna-indigo">Biru Nila / Indigo</option>
                                <option value="warna-coklat">Coklat</option>
                                <option value="warna-abuabu">Abu-abu</option>
                                <option value="warna-hitam">Hitam Elegan</option>
                                <option value="warna-magenta">Magenta</option>
                                <option value="warna-cyan">Cyan</option>
                                <option value="warna-lime">Hijau Lime</option>
                                <option value="warna-emerald">Hijau Emerald</option>
                                <option value="warna-rose">Merah Mawar</option>
                                <option value="warna-sky">Biru Langit</option>
                                <option value="warna-amber">Kuning Amber</option>
                                <option value="warna-pink-muda">Pink Muda</option>
                                <option value="warna-maroon">Merah Maroon</option>
                                <option value="warna-navy">Biru Navy</option>
                                <option value="warna-peach">Peach</option>
                                <option value="warna-olive">Hijau Zaitun</option>
                                <option value="warna-lavender">Lavender</option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="time_minutes">Estimasi Waktu (menit)</label>
                            <input id="time_minutes" name="time_minutes" type="number" value="10" required>
                        </div>

                        <button class="admin-button" type="submit" id="btn_material">Simpan Materi</button>
                        <button type="button" class="admin-button secondary" id="btn_cancel_material" style="display:none;" onclick="cancelEditMaterial()">Batal Edit</button>
                    </form>
                </article>

                <article class="admin-panel">
                    <h2 id="form_video_title">Tambah Video Pembelajaran</h2>
                    <form class="form-grid" method="POST" id="form_video" enctype="multipart/form-data">
                        <input type="hidden" name="action" id="action_video" value="create_video">
                        <input type="hidden" name="video_id" id="form_video_id" value="">
                        <div class="field">
                            <label for="material_id">Pilih Materi</label>
                            <select id="material_id" name="material_id" required>
                                <?php foreach ($materials as $m): ?>
                                    <option value="<?= htmlspecialchars($m['id']) ?>"><?= htmlspecialchars($m['title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="field">
                            <label for="video_title">Judul Video</label>
                            <input id="video_title" name="title" type="text" required>
                        </div>
                        <div class="field">
                            <label for="video_url">URL (YouTube / MP4)</label>
                            <input id="video_url" name="video_url" type="text" placeholder="https://youtu.be/...">
                        </div>
                        <div class="field">
                            <label for="video_file">Atau Upload File MP4 (Dari Laptop/HP)</label>
                            <input id="video_file" name="video_file" type="file" accept="video/mp4">
                            <small style="color:#666; font-size:12px;">Jika upload file, URL di atas akan diabaikan/ditimpa.</small>
                        </div>
                        <div class="field">
                            <label for="script">Teks Penjelasan / Skrip</label>
                            <textarea id="script" name="script" rows="4"></textarea>
                        </div>
                        <div class="field">
                            <label for="order_index">Urutan (1, 2, 3..)</label>
                            <input id="order_index" name="order_index" type="number" value="1" required>
                        </div>
                        <button class="admin-button" type="submit" id="btn_video">Tambah Video</button>
                        <button type="button" class="admin-button secondary" id="btn_cancel_video" style="display:none;" onclick="cancelEditVideo()">Batal Edit</button>
                    </form>
                </article>
            </div>

            <article class="admin-panel">
                <h2>Daftar Materi & Video</h2>
                <div class="materi-list">
                    <?php if (empty($materials)): ?>
                        <div class="empty">Belum ada materi.</div>
                    <?php endif; ?>

                    <?php foreach ($materials as $mat): ?>
                        <div class="admin-card materi-item">
                            <div class="materi-icon <?= htmlspecialchars($mat['color_class']) ?>">
                                <img src="../<?= htmlspecialchars($mat['icon_file']) ?>" width="30" height="30" onerror="this.style.display='none'">
                            </div>
                            <div style="min-width:0;">
                                <h2><?= htmlspecialchars($mat['title']) ?></h2>
                                <p><?= htmlspecialchars($mat['category']) ?> • <?= (int) $mat['time_minutes'] ?> menit</p>
                                <p><?= htmlspecialchars($mat['description']) ?></p>
                                
                                <?php 
                                $videos = $app->getMaterialVideos($mat['id']);
                                if (!empty($videos)): 
                                ?>
                                <div class="video-list">
                                    <?php foreach ($videos as $vid): ?>
                                    <div class="video-item">
                                        <div style="font-size:13px; color:#333;">
                                            <strong><?= htmlspecialchars($vid['title']) ?></strong>
                                        </div>
                                        <div style="display: flex; gap: 4px;">
                                            <button type="button" class="admin-button secondary" style="padding: 4px 8px; font-size: 11px;" onclick="editVideo(<?= htmlspecialchars(json_encode($vid)) ?>)">Edit</button>
                                            <form method="POST" style="margin:0;" onsubmit="return confirm('Hapus video ini?');">
                                                <input type="hidden" name="action" value="delete_video">
                                                <input type="hidden" name="video_id" value="<?= (int) $vid['id'] ?>">
                                                <button class="admin-button danger" style="padding: 4px 8px; font-size: 11px;" type="submit">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php else: ?>
                                    <p style="font-size:12px; color:#b42318; margin-top:10px;">Belum ada video.</p>
                                <?php endif; ?>
                            </div>
                            <div class="materi-actions">
                                <button type="button" class="admin-button secondary" onclick="editMaterial(<?= htmlspecialchars(json_encode($mat)) ?>)">Edit Materi</button>
                                <form method="POST" style="margin:0;" onsubmit="return confirm('Menghapus materi ini akan menghapus semua videonya juga. Lanjutkan?');">
                                    <input type="hidden" name="action" value="delete_material">
                                    <input type="hidden" name="material_id" value="<?= htmlspecialchars($mat['id']) ?>">
                                    <button class="admin-button danger" type="submit">Hapus Materi</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </article>
        </section>
    </main>
    <script>
        function editMaterial(mat) {
            document.getElementById('form_material_title').innerText = 'Edit Materi Utama';
            document.getElementById('action_material').value = 'update_material';
            document.getElementById('btn_material').innerText = 'Update Materi';
            document.getElementById('btn_cancel_material').style.display = 'inline-block';
            
            document.getElementById('id').value = mat.id;
            document.getElementById('id').readOnly = true;
            document.getElementById('id_help').style.display = 'inline-block';
            
            document.getElementById('title').value = mat.title;
            document.getElementById('description').value = mat.description;
            document.getElementById('category').value = mat.category;
            document.getElementById('time_minutes').value = mat.time_minutes;
            
            document.getElementById('icon_file').value = mat.icon_file || '';
            document.getElementById('color_class').value = mat.color_class || '';

            window.scrollTo({top: 0, behavior: 'smooth'});
        }

        function cancelEditMaterial() {
            document.getElementById('form_material').reset();
            document.getElementById('form_material_title').innerText = 'Tambah Materi Utama';
            document.getElementById('action_material').value = 'create_material';
            document.getElementById('btn_material').innerText = 'Simpan Materi';
            document.getElementById('btn_cancel_material').style.display = 'none';
            document.getElementById('id').readOnly = false;
            document.getElementById('icon_file').value = '';
            document.getElementById('color_class').value = 'vokal';
        }

        function editVideo(vid) {
            document.getElementById('form_video_title').innerText = 'Edit Video Pembelajaran';
            document.getElementById('action_video').value = 'update_video';
            document.getElementById('form_video_id').value = vid.id;
            document.getElementById('btn_video').innerText = 'Update Video';
            document.getElementById('btn_cancel_video').style.display = 'inline-block';
            
            document.getElementById('material_id').value = vid.material_id;
            document.getElementById('material_id').disabled = true; // prevent changing material
            
            // Add a hidden input to pass material_id since disabled selects don't post
            let hiddenMatId = document.getElementById('hidden_mat_id');
            if(!hiddenMatId) {
                hiddenMatId = document.createElement('input');
                hiddenMatId.type = 'hidden';
                hiddenMatId.name = 'material_id';
                hiddenMatId.id = 'hidden_mat_id';
                document.getElementById('form_video').appendChild(hiddenMatId);
            }
            hiddenMatId.value = vid.material_id;

            document.getElementById('video_title').value = vid.title;
            document.getElementById('video_url').value = vid.video_url;
            document.getElementById('script').value = vid.script;
            document.getElementById('order_index').value = vid.order_index;
            
            window.scrollTo({top: 0, behavior: 'smooth'});
        }

        function cancelEditVideo() {
            document.getElementById('form_video').reset();
            document.getElementById('form_video_title').innerText = 'Tambah Video Pembelajaran';
            document.getElementById('action_video').value = 'create_video';
            document.getElementById('form_video_id').value = '';
            document.getElementById('btn_video').innerText = 'Tambah Video';
            document.getElementById('btn_cancel_video').style.display = 'none';
            document.getElementById('material_id').disabled = false;
            
            let hiddenMatId = document.getElementById('hidden_mat_id');
            if(hiddenMatId) hiddenMatId.remove();
        }
    </script>
</body>
</html>
