<?php
require_once 'core.php';

$pesan   = '';
$sukses  = false;
$oldData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaDepan   = trim($_POST['nama_depan']   ?? '');
    $namaBelakang= trim($_POST['nama_belakang'] ?? '');
    $tempatLahir = trim($_POST['tempat_lahir']  ?? '');
    $tglLahir    = trim($_POST['tanggal_lahir'] ?? '');
    $username    = trim($_POST['username']      ?? '');
    $password    = $_POST['password']           ?? '';
    $konfirmasi  = $_POST['konfirmasi']         ?? '';

    // Simpan data lama agar form tidak kosong jika error
    $oldData = compact('namaDepan','namaBelakang','tempatLahir','tglLahir','username');

    if (empty($namaDepan) || empty($tempatLahir) || empty($tglLahir) || empty($username) || empty($password)) {
        $pesan = 'Semua field wajib diisi.';
    } elseif ($password !== $konfirmasi) {
        $pesan = 'Kata sandi dan konfirmasi tidak cocok.';
    } elseif (strlen($password) < 6) {
        $pesan = 'Kata sandi minimal 6 karakter.';
    } else {
        $nama = trim($namaDepan . ' ' . $namaBelakang);
        $db   = new manz();
        $hasil = $db->register($nama, $tempatLahir, $tglLahir, $username, $password);

        if ($hasil['status']) {
            $sukses = true;
            $pesan  = $hasil['pesan'];
            $oldData = []; // Reset form setelah sukses
        } else {
            $pesan = $hasil['pesan'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TALKLAB - Daftar Akun</title>
    <link rel="stylesheet" href="style3.css">
    <style>
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
            font-weight: 600;
        }
        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }
    </style>
</head>

<body>

    <div class="login-container">

        <div class="login-left">

            <div class="brand">
                <img src="assets/ayooo.png" class="brand-logo" alt="Logo">
                <span class="brand-text">
                    <span class="talk">TALK</span><span class="lab">LAB</span>
                </span>
            </div>

            <div class="hero-illustration">
                <div class="circle-border">
                    <img src="assets/log.png" class="character" alt="Character">
                </div>
            </div>

            <h2 class="hero-title">Siap bicara dengan percaya diri?</h2>
            <p class="hero-subtitle">Lakukan perjalanan belajarmu hari ini</p>

            <div class="ornament bottom-left"></div>
            <div class="ornament top-right"></div>

        </div>

        <div class="login-right">

            <h1 style="font-size: 49px;">Halo, Salam Kenal</h1>
            <p style="font-size: 25px;">Silahkan isi data anda.</p>

            <div class="form-wrapper">

                <?php if (!empty($pesan)): ?>
                    <div class="alert <?= $sukses ? 'alert-success' : 'alert-error' ?>">
                        <?= htmlspecialchars($pesan) ?>
                        <?php if ($sukses): ?>
                            &nbsp;<a href="login.php" style="color:#15803d;">Klik di sini untuk login</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="regis.php">

                    <label>Nama Depan</label>
                    <input type="text" name="nama_depan" placeholder="Ketik nama depan anda"
                           value="<?= htmlspecialchars($oldData['namaDepan'] ?? '') ?>" required>

                    <label>Nama Belakang</label>
                    <input type="text" name="nama_belakang" placeholder="Ketik nama belakang anda"
                           value="<?= htmlspecialchars($oldData['namaBelakang'] ?? '') ?>">

                    <label>Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" placeholder="Ketik kota tempat lahir anda"
                           value="<?= htmlspecialchars($oldData['tempatLahir'] ?? '') ?>" required>

                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir"
                           value="<?= htmlspecialchars($oldData['tglLahir'] ?? '') ?>" required>

                    <label>Username</label>
                    <input type="text" name="username" placeholder="Ketik username anda"
                           value="<?= htmlspecialchars($oldData['username'] ?? '') ?>" required>

                    <label>Kata Sandi</label>
                    <div class="password-wrapper">
                        <input id="password-input" type="password" name="password"
                               placeholder="Minimal 6 karakter" required>
                        <img class="eye-toggle" id="toggle-eye" src="icon/pw.svg" style="width:32px; height:32px;">
                    </div>

                    <label>Ketik ulang Kata Sandi</label>
                    <div class="password-wrapper">
                        <input id="password-input2" type="password" name="konfirmasi"
                               placeholder="Ulangi kata sandi anda" required>
                        <img class="eye-toggle" id="toggle-eye2" src="icon/pw.svg" style="width:32px; height:32px;">
                    </div>

                    <button type="submit" class="btn-login">Daftar</button>
                    <p class="register-text">Sudah punya akun? <a href="login.php">Masuk sekarang</a></p>

                </form>
            </div>
        </div>

    </div>

    <script>
        const passwordInput = document.getElementById("password-input");
        const toggleEye = document.getElementById("toggle-eye");
        toggleEye.addEventListener("click", () => {
            const isHidden = passwordInput.type === "password";
            passwordInput.type = isHidden ? "text" : "password";
            toggleEye.src = isHidden ? "icon/buka.svg" : "icon/pw.svg";
        });

        const passwordInput2 = document.getElementById("password-input2");
        const toggleEye2 = document.getElementById("toggle-eye2");
        toggleEye2.addEventListener("click", () => {
            const isHidden = passwordInput2.type === "password";
            passwordInput2.type = isHidden ? "text" : "password";
            toggleEye2.src = isHidden ? "icon/buka.svg" : "icon/pw.svg";
        });
    </script>

</body>

</html>

