<?php
require_once 'core.php';
$app = new manz();
$app->ensureSession();
$error = $_SESSION['google_login_error'] ?? '';
unset($_SESSION['google_login_error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan kata sandi wajib diisi.';
    } else {
        
        // Cek apakah username ada
        $userRow = $app->getUserByUsername($username);
        if ($userRow === false) {
            $error = 'Username tidak ditemukan.';
        } else {
            // Username ada, periksa password
            if (password_verify($password, $userRow['Password'])) {
                $app->setSessionFromUser($userRow);
                header('Location: Beranda.php');
                exit;
            } else {
                $error = 'Kata sandi Anda salah.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vocalise Login</title>
    <link rel="stylesheet" href="style2.css">
    <style>
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #245B7A;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 24px;
            transition: color 0.2s;
        }
        .back-link:hover {
            color: #15305c;
            text-decoration: underline;
        }
    </style>
    <link rel="stylesheet" href="brand-theme.css">
</head>

<body>

    <div class="login-container">


        <div class="login-left">


            <div class="brand">
                <img src="assets/logo_withtext.png" class="brand-logo" alt="Logo">
                
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
            <a href="index.php" class="back-link">
                <svg viewBox="0 0 24 24" width="20" height="20"><path fill="currentColor" d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
                Kembali ke Beranda
            </a>
            <h1 style="font-size: 49px;">Selamat datang!</h1>
            <p style="font-size: 25px;">Silahkan masuk untuk melanjutkan.</p>

            <div class="form-wrapper">
                
            <?php if (!empty($error)): ?>
                    <div style="background:#fee2e2;color:#991b1b;padding:10px;border-radius:6px;margin-bottom:12px;">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="login.php">

                    <label>Username</label>
                    <input type="text" name="username" placeholder="Ketik username anda" required>

                    <label>Kata Sandi</label>
                    <div class="password-wrapper">
                        <input id="password-input" type="password" name="password" placeholder="Ketik kata sandi anda" required>

                        <img class="eye-toggle" id="toggle-eye" src="icon/pw.svg">
                    </div>

                    <a class="lost-password" href="forgot_password.php">Lupa kata sandi?</a>
                    <button type="submit" class="btn-login">Masuk</button>

                    <p class="register-text">Belum punya akun? <a href="regis.php">Daftar sekarang</a></p>
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


            toggleEye.src = isHidden
                ? "icon/buka.svg"
                : "icon/pw.svg";
        });
    </script>


</body>

</html>
