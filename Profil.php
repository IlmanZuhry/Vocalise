<?php
require_once 'core.php';
if (session_status() == PHP_SESSION_NONE) session_start();
$app = new manz();

// Handle Edit Profil form submission
$editMsg = '';
$editStatus = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_profil'])) {
    $currentUser = $app->getCurrentUser();
    if ($currentUser) {
        $newNama = trim($_POST['nama'] ?? '');
        $newUsername = trim($_POST['username'] ?? '');
        $newBio = trim($_POST['bio'] ?? '');

        if ($newNama === '' || $newUsername === '') {
            $editMsg = 'Nama dan username tidak boleh kosong.';
            $editStatus = 'error';
        } elseif (strlen($newBio) > 160) {
            $editMsg = 'Bio maksimal 160 karakter.';
            $editStatus = 'error';
        } else {
            $fotoPath = null;

            // Handle foto upload
            if (!empty($_FILES['foto']['tmp_name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $fileType = mime_content_type($_FILES['foto']['tmp_name']);

                if (in_array($fileType, $allowedTypes)) {
                    $uploadDir = __DIR__ . '/uploads/profile_photos';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0775, true);
                    }

                    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                    $fileName = $currentUser['Id_User'] . '_' . time() . '.' . $ext;
                    $targetPath = $uploadDir . '/' . $fileName;
                    $relativePath = 'uploads/profile_photos/' . $fileName;

                    if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetPath)) {
                        $fotoPath = $relativePath;
                    } else {
                        $editMsg = 'Gagal mengupload foto.';
                        $editStatus = 'error';
                    }
                } else {
                    $editMsg = 'Format foto tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP.';
                    $editStatus = 'error';
                }
            }

            if ($editStatus !== 'error') {
                $result = $app->updateProfile($currentUser['Id_User'], $newNama, $newUsername, $fotoPath, $newBio);
                $editMsg = $result['pesan'];
                $editStatus = $result['status'] ? 'success' : 'error';
            }
        }
    }
}

// Get current user data for display
$displayName = $app->getDisplayName();
$displayUsername = $app->getDisplayUsername();
$displayFoto = $app->getDisplayFoto();
$displayBio = $app->getDisplayBio();
$currentUser = $app->getCurrentUser();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TALKLAB - Profil</title>
    <?php include 'includes/layout_css.php'; ?>
    <style>
        .ornamen-left {
            position: absolute;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: rgba(80, 64, 64, 0.45);
            left: -80px;
            bottom: -120px;
        }

        .ornamen-right {
            position: absolute;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: rgba(80, 64, 64, 0.45);
            right: -80px;
            top: -120px;
        }

        /* ===== MODAL EDIT PROFIL ===== */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.55);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.active {
            display: flex;
        }
        .modal-content {
            background: #fff;
            border-radius: 20px;
            padding: 36px 32px;
            width: 420px;
            max-width: 92vw;
            box-shadow: 0 16px 48px rgba(0,0,0,0.25);
            position: relative;
            animation: modalFadeIn 0.3s ease;
        }
        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-30px) scale(0.95); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .modal-close {
            position: absolute;
            top: 16px; right: 18px;
            background: none;
            border: none;
            font-size: 26px;
            cursor: pointer;
            color: #666;
            transition: color 0.2s;
        }
        .modal-close:hover { color: #000; }
        .modal-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 24px;
            color: #0e1e4d;
        }

        /* Avatar upload area */
        .avatar-upload-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 22px;
        }
        .avatar-preview {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            border: 4px solid #d2a06b;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e8e8e8;
            cursor: pointer;
            position: relative;
            transition: box-shadow 0.2s;
        }
        .avatar-preview:hover {
            box-shadow: 0 0 0 4px rgba(210, 160, 107, 0.3);
        }
        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .avatar-preview .avatar-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.2s;
            border-radius: 50%;
        }
        .avatar-preview:hover .avatar-overlay {
            opacity: 1;
        }
        .avatar-overlay svg {
            width: 32px;
            height: 32px;
        }
        .avatar-upload-hint {
            font-size: 13px;
            color: #999;
            margin-top: 8px;
        }

        /* Form fields */
        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            font-size: 15px;
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
        }
        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.2s;
            resize: vertical;
        }
        .form-group textarea {
            min-height: 92px;
            line-height: 1.45;
        }
        .form-group input[type="text"]:focus,
        .form-group textarea:focus {
            border-color: #d2a06b;
        }
        .bio-counter {
            display: block;
            text-align: right;
            color: #999;
            font-size: 12px;
            margin-top: 5px;
        }

        /* Buttons */
        .btn-save-profil {
            width: 100%;
            padding: 14px;
            background: #0e1e4d;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 6px;
        }
        .btn-save-profil:hover {
            background: #1c407a;
        }

        /* Alert messages */
        .alert-msg {
            padding: 10px 14px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 14px;
            font-weight: 500;
        }
        .alert-msg.success {
            background: #d1fae5;
            color: #065f46;
        }
        .alert-msg.error {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Default avatar SVG in banner */
        .banner-avatar-default {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 5px solid #d2a06b;
            background: #d2a06b;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>

    <div style="margin-left:260px; padding-top:120px; width:100%; padding-left:40px; padding-right:40px;">

        <h1 style="font-size:40px; font-weight:700; color:#000;">Profil</h1>
        <p style="color:#777; font-size:20px; margin-bottom:30px;">Kelola akun dan lihat progres kamu</p>

        <!-- Banner Profil -->
        <div class="banner-profil" style="
            width:100%;
            background:#0e1e4d;
            padding:95px 40px;
            border-radius:20px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            color:white;
            min-height:240px;
            position:relative;
            overflow:hidden;
        ">
            <div class="ornamen-left"></div>
            <div class="ornamen-right"></div>
            <div style="display:flex; align-items:center; gap:25px;">
                <?php if (!empty($displayFoto)): ?>
                    <div style="width:200px; height:200px; border-radius:50%; border:5px solid #d2a06b; overflow:hidden;">
                        <img src="<?= $displayFoto ?>" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                <?php else: ?>
                    <div class="banner-avatar-default">
                        <svg viewBox="0 0 24 24" width="90" height="90"><path fill="white" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    </div>
                <?php endif; ?>
                <div>
                    <h2 style="font-size:32px; font-weight:700;"><?= $displayName ?></h2>
                    <p style="font-size:22px; color:#d2a06b;">@<?= $displayUsername ?></p>
                    <p style="font-size:20px; margin-top:10px;">"<?= $displayBio ?>"</p>
                </div>
            </div>
            <button id="btnEditProfil" style="background:#d2a06b; padding:10px 26px; color:white; font-size:20px; border:none; border-radius:12px; cursor:pointer; margin-right: 40px; z-index:2;">
                Edit Profil
            </button>
        </div>

        <!-- Box Statistik -->
        <div style="display:flex; gap:35px; margin-top:40px; flex-wrap:wrap; justify-content:space-between;">

            <div style="width:30%; min-width:260px; background:white; padding:30px; border-radius:18px; box-shadow:0 3px 6px rgba(0,0,0,0.1);">
                <div style="width:55px; height:55px; background:#EC1763; border-radius:12px; display:flex; align-items:center; justify-content:center; margin-bottom:15px;">
                    <img src="icon/award.svg" style="width:32px; height:32px;">
                </div>
                <h3 style="font-size:22px; font-weight:600;">Materi Selesai</h3>
                <p style="font-size:32px; margin-top:10px;">3</p>
            </div>

            <div style="width:30%; min-width:260px; background:white; padding:30px; border-radius:18px; box-shadow:0 3px 6px rgba(0,0,0,0.1);">
                <div style="width:55px; height:55px; background:#E59600; border-radius:12px; display:flex; align-items:center; justify-content:center; margin-bottom:15px;">
                    <img src="icon/date.svg" style="width:32px; height:32px;">
                </div>
                <h3 style="font-size:22px; font-weight:600;">Hari Belajar</h3>
                <p style="font-size:32px; margin-top:10px;">10</p>
            </div>

            <div style="width:30%; min-width:260px; background:white; padding:30px; border-radius:18px; box-shadow:0 3px 6px rgba(0,0,0,0.1);">
                <div style="width:55px; height:55px; background:#1355B2; border-radius:12px; display:flex; align-items:center; justify-content:center; margin-bottom:15px;">
                    <img src="icon/target.svg" style="width:32px; height:32px;">
                </div>
                <h3 style="font-size:22px; font-weight:600;">Target Bulan Ini</h3>
                <p style="font-size:32px; margin-top:10px;">65%</p>
            </div>

        </div>

        <!-- Menu Bantuan -->
        <div style="background:white; margin-top:35px; padding:25px; border-radius:18px; box-shadow:0 3px 6px rgba(0,0,0,0.1); font-size:22px; display:flex; flex-direction:column; gap:18px; margin-bottom: 45px;">
            <p style="display:flex; align-items:center; gap:12px;">
                <img src="icon/help.svg" style="width:36px; height:36px;"> Bantuan
            </p>
            <p style="display:flex; align-items:center; gap:12px;">
                <img src="icon/info.svg" style="width:36px; height:36px;"> Informasi Versi
            </p>
            <p style="display:flex; align-items:center; gap:12px;">
                <img src="icon/logout.svg" style="width:36px; height:36px;">
                <a href="logout.php" data-logout-link>Logout</a>
            </p>
        </div>

        <!-- Pencapaian -->
        <h2 style="margin-top:45px; font-size:34px;">Pencapaian</h2>

        <div style="display:flex; gap:25px; margin-top:20px; flex-wrap:wrap; padding-bottom: 40px; justify-content:space-between;">
            <div style="width:22%; min-width:200px; background:white; padding:25px; border-radius:18px; box-shadow:0 3px 6px rgba(0,0,0,0.1); text-align:center;">
                <img src="icon/tropy.svg" style="width:60px;">
                <p style="margin-top:10px; font-size:20px;">Pemula Berbicara</p>
            </div>
            <div style="width:22%; min-width:200px; background:white; padding:25px; border-radius:18px; box-shadow:0 3px 6px rgba(0,0,0,0.1); text-align:center;">
                <img src="icon/planet.svg" style="width:60px;">
                <p style="margin-top:10px; font-size:20px;">Fokus Belajar</p>
            </div>
            <div style="width:22%; min-width:200px; background:white; padding:25px; border-radius:18px; box-shadow:0 3px 6px rgba(0,0,0,0.1); text-align:center;">
                <img src="icon/api.svg" style="width:60px;">
                <p style="margin-top:10px; font-size:20px;">Streak 5 Hari</p>
            </div>
            <div style="width:22%; min-width:200px; background:white; padding:25px; border-radius:18px; box-shadow:0 3px 6px rgba(0,0,0,0.1); text-align:center;">
                <img src="icon/star.svg" style="width:60px;">
                <p style="margin-top:10px; font-size:20px;">Bintang Panggung</p>
            </div>
        </div>

    </div>

    <!-- ===== MODAL EDIT PROFIL ===== -->
    <div class="modal-overlay" id="modalEditProfil">
        <div class="modal-content">
            <button class="modal-close" id="btnCloseModal">&times;</button>
            <h3 class="modal-title">Edit Profil</h3>

            <?php if (!empty($editMsg)): ?>
                <div class="alert-msg <?= $editStatus ?>"><?= htmlspecialchars($editMsg) ?></div>
            <?php endif; ?>

            <form method="POST" action="Profil.php" enctype="multipart/form-data">
                <input type="hidden" name="edit_profil" value="1">

                <!-- Avatar Upload -->
                <div class="avatar-upload-area">
                    <label for="inputFoto" class="avatar-preview" id="avatarPreviewLabel">
                        <?php if (!empty($displayFoto)): ?>
                            <img src="<?= $displayFoto ?>" id="avatarPreviewImg">
                        <?php else: ?>
                            <svg viewBox="0 0 24 24" width="50" height="50" id="avatarPreviewSvg"><path fill="#999" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            <img src="" id="avatarPreviewImg" style="display:none;">
                        <?php endif; ?>
                        <div class="avatar-overlay">
                            <svg viewBox="0 0 24 24" fill="white"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 000-1.41l-2.34-2.34a1 1 0 00-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                        </div>
                    </label>
                    <input type="file" name="foto" id="inputFoto" accept="image/*" style="display:none;">
                    <span class="avatar-upload-hint">Klik untuk ganti foto profil</span>
                </div>

                <!-- Nama -->
                <div class="form-group">
                    <label for="inputNama">Nama Lengkap</label>
                    <input type="text" name="nama" id="inputNama" value="<?= $displayName ?>" required>
                </div>

                <!-- Username -->
                <div class="form-group">
                    <label for="inputUsername">Username</label>
                    <input type="text" name="username" id="inputUsername" value="<?= $displayUsername ?>" required>
                </div>

                <!-- Bio -->
                <div class="form-group">
                    <label for="inputBio">Bio</label>
                    <textarea name="bio" id="inputBio" maxlength="160" placeholder="Tulis bio singkat kamu"><?= $displayBio ?></textarea>
                    <span class="bio-counter"><span id="bioCount">0</span>/160</span>
                </div>

                <button type="submit" class="btn-save-profil">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <script>
        // Modal open/close
        const btnEdit = document.getElementById('btnEditProfil');
        const modal = document.getElementById('modalEditProfil');
        const btnClose = document.getElementById('btnCloseModal');

        btnEdit.addEventListener('click', () => {
            modal.classList.add('active');
        });
        btnClose.addEventListener('click', () => {
            modal.classList.remove('active');
        });
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.classList.remove('active');
        });

        // Foto preview
        const inputFoto = document.getElementById('inputFoto');
        const avatarPreviewImg = document.getElementById('avatarPreviewImg');
        const avatarPreviewSvg = document.getElementById('avatarPreviewSvg');

        inputFoto.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (ev) => {
                    avatarPreviewImg.src = ev.target.result;
                    avatarPreviewImg.style.display = 'block';
                    if (avatarPreviewSvg) avatarPreviewSvg.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });

        // Bio counter
        const inputBio = document.getElementById('inputBio');
        const bioCount = document.getElementById('bioCount');
        const updateBioCount = () => {
            bioCount.textContent = inputBio.value.length;
        };
        inputBio.addEventListener('input', updateBioCount);
        updateBioCount();

        // Auto-open modal if there was a form submission with error
        <?php if (!empty($editMsg)): ?>
            modal.classList.add('active');
        <?php endif; ?>
    </script>

</body>

</html>
