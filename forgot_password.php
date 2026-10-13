<?php
require_once 'core.php';

$app = new manz();
$app->ensureSession();

// Jangan tampilkan formulir pemulihan lama dari cache saat pengguna menekan tombol Back browser.
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// Pengguna dapat membatalkan verifikasi untuk memeriksa atau mengisi ulang data akun.
if (isset($_GET['ulang'])) {
    unset($_SESSION['password_reset_user_id']);
    header('Location: forgot_password.php');
    exit;
}

if (empty($_SESSION['password_reset_csrf'])) {
    $_SESSION['password_reset_csrf'] = bin2hex(random_bytes(32));
}

$error = '';
$success = '';
$username = '';
$tempatLahir = '';
$tanggalLahir = '';
$resetUserId = $_SESSION['password_reset_user_id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['password_reset_csrf'], $csrf)) {
        $error = 'Permintaan tidak valid. Silakan coba lagi.';
    } elseif (($_POST['action'] ?? '') === 'verify') {
        $username = trim($_POST['username'] ?? '');
        $tempatLahir = trim($_POST['tempat_lahir'] ?? '');
        $tanggalLahir = $_POST['tanggal_lahir'] ?? '';

        if ($username === '' || $tempatLahir === '' || $tanggalLahir === '') {
            $error = 'Username, tempat lahir, dan tanggal lahir wajib diisi.';
        } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalLahir)) {
            $error = 'Tanggal lahir tidak valid.';
        } else {
            $userId = $app->getUserIdByIdentity($username, $tempatLahir, $tanggalLahir);
            if (!$userId) {
                $error = 'Data akun tidak cocok. Periksa kembali data yang digunakan saat mendaftar.';
            } else {
                $_SESSION['password_reset_user_id'] = $userId;
                $_SESSION['password_reset_csrf'] = bin2hex(random_bytes(32));
                header('Location: forgot_password.php');
                exit;
            }
        }
    } elseif (($_POST['action'] ?? '') === 'reset' && $resetUserId !== '') {
        $password = $_POST['password'] ?? '';
        $konfirmasi = $_POST['konfirmasi_password'] ?? '';

        if ($password === '' || $konfirmasi === '') {
            $error = 'Kata sandi baru dan konfirmasinya wajib diisi.';
        } elseif (strlen($password) < 6) {
            $error = 'Kata sandi baru minimal 6 karakter.';
        } elseif ($password !== $konfirmasi) {
            $error = 'Konfirmasi kata sandi tidak sama.';
        } elseif (!$app->updatePasswordByUserId($resetUserId, $password)) {
            $error = 'Kata sandi belum dapat diperbarui. Silakan coba lagi.';
        } else {
            unset($_SESSION['password_reset_user_id']);
            $_SESSION['password_reset_csrf'] = bin2hex(random_bytes(32));
            $resetUserId = '';
            $success = 'Kata sandi berhasil diperbarui. Silakan masuk dengan kata sandi baru.';
        }
    } else {
        $error = 'Sesi pemulihan tidak ditemukan. Silakan verifikasi data akun kembali.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Kata Sandi - TALKLAB</title>
    <link rel="stylesheet" href="style3.css">
    <style>
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; font-weight: 600; }
        .alert-error { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .alert-success { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
        .form-wrapper input { box-sizing: border-box; }
        .back-button { display: block; width: 70%; box-sizing: border-box; padding: 11px; margin: 12px auto 0; border: 1px solid #102A43; border-radius: 25px; color: #102A43; font-size: 16px; font-weight: 600; text-align: center; text-decoration: none; }
        .back-button:hover { background: #f1f4fa; }
    </style>
    <link rel="stylesheet" href="brand-theme.css">
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <div class="brand">
                <img src="assets/ayooo.png" class="brand-logo" alt="Logo">
                <span class="brand-text"><span class="talk">TALK</span><span class="lab">LAB</span></span>
            </div>
            <div class="hero-illustration"><div class="circle-border"><img src="assets/log.png" class="character" alt="Character"></div></div>
            <h2 class="hero-title">Siap bicara dengan percaya diri?</h2>
            <p class="hero-subtitle">Lakukan perjalanan belajarmu hari ini</p>
            <div class="ornament bottom-left"></div>
            <div class="ornament top-right"></div>
        </div>

        <div class="login-right">
            <h1 style="font-size: 49px;">Atur ulang kata sandi</h1>
            <p style="font-size: 25px;"><?= $resetUserId ? 'Buat kata sandi baru untuk akun anda.' : 'Verifikasi data akun terlebih dahulu.' ?></p>

            <div class="form-wrapper">
                <?php if ($error): ?><div class="alert alert-error" role="alert"><?= htmlspecialchars($error) ?></div><?php endif; ?>
                <?php if ($success): ?><div class="alert alert-success" role="status"><?= htmlspecialchars($success) ?></div><?php endif; ?>

                <?php if ($resetUserId): ?>
                    <form method="POST" action="forgot_password.php">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['password_reset_csrf']) ?>">
                        <input type="hidden" name="action" value="reset">

                        <label for="password">Kata Sandi Baru</label>
                        <input id="password" type="password" name="password" placeholder="Minimal 6 karakter" minlength="6" autocomplete="new-password" required>

                        <label for="konfirmasi_password">Ketik ulang Kata Sandi Baru</label>
                        <input id="konfirmasi_password" type="password" name="konfirmasi_password" placeholder="Ulangi kata sandi baru anda" minlength="6" autocomplete="new-password" required>

                        <button type="submit" class="btn-login">Simpan Kata Sandi</button>
                    </form>
                    <a class="back-button" href="forgot_password.php?ulang=1">&larr; Kembali periksa data</a>
                <?php else: ?>
                    <form method="POST" action="forgot_password.php">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['password_reset_csrf']) ?>">
                        <input type="hidden" name="action" value="verify">

                        <label for="username">Username</label>
                        <input id="username" type="text" name="username" placeholder="Ketik username anda" value="<?= htmlspecialchars($username) ?>" autocomplete="username" required>

                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input id="tempat_lahir" type="text" name="tempat_lahir" placeholder="Ketik kota tempat lahir anda" value="<?= htmlspecialchars($tempatLahir) ?>" required>

                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input id="tanggal_lahir" type="date" name="tanggal_lahir" value="<?= htmlspecialchars($tanggalLahir) ?>" required>

                        <button type="submit" class="btn-login">Verifikasi Data</button>
                    </form>
                    <a class="back-button" href="login.php">&larr; Kembali ke halaman masuk</a>
                <?php endif; ?>
                <p class="register-text">Sudah ingat kata sandi? <a href="login.php">Masuk sekarang</a></p>
            </div>
        </div>
    </div>
</body>
</html>
