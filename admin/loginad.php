<?php
require_once '../core.php';
$app = new manz();
$app->ensureSession();
$error = '';
$availableSpecialties = $app->getAvailableSpecialties();
$isMentorSetup = count($availableSpecialties) > 0;

if ($app->getCurrentMentor() && !$isMentorSetup) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mode = $_POST['mode'] ?? 'login';
    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    $specialty = $_POST['specialty'] ?? '';

    if ($isMentorSetup && $mode === 'setup') {
        if ($name === '' || $username === '' || $password === '' || $specialty === '') {
            $error = 'Nama, username, kata sandi, dan spesialisasi mentor wajib diisi.';
        } elseif (!in_array($specialty, $availableSpecialties)) {
            $error = 'Spesialisasi tidak valid atau sudah ada mentor untuk bidang tersebut.';
        } elseif (strlen($password) < 8) {
            $error = 'Kata sandi mentor minimal 8 karakter.';
        } elseif ($password !== $passwordConfirm) {
            $error = 'Konfirmasi kata sandi belum sama.';
        } elseif (!$app->createMentorAccount($name, $username, $password, $specialty)) {
            $error = 'Akun mentor gagal dibuat. Coba username lain.';
        } else {
            $mentor = $app->authenticateMentor($username, $password);
            if ($mentor) {
                $app->setMentorSession($mentor);
                header('Location: dashboard.php');
                exit;
            }
        }
    } elseif ($username === '' || $password === '') {
        $error = 'Username dan kata sandi wajib diisi.';
    } else {
        $mentor = $app->authenticateMentor($username, $password);
        if ($mentor === false) {
            $error = 'Username atau kata sandi mentor tidak cocok.';
        } else {
            $app->setMentorSession($mentor);
            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TALKLAB Mentor Login</title>
    <link rel="stylesheet" href="../style2.css">
    <style>
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #1c407a;
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
</head>

<body>

    <div class="login-container">


        <div class="login-left">


            <div class="brand">
                <img src="../assets/ayooo.png" class="brand-logo" alt="Logo">
                <span class="brand-text">
                    <span class="talk">TALK</span><span class="lab">LAB</span>
                </span>
            </div>



            <div class="hero-illustration">
                <div class="circle-border">
                    <img src="../assets/log.png" class="character" alt="Character">
                </div>
            </div>

            <h2 class="hero-title">Siap Melihat Perkembangan Murid?</h2>
            


            <div class="ornament bottom-left"></div>
            <div class="ornament top-right"></div>

        </div>


        <div class="login-right">
            
            <h1 style="font-size: 49px;"><?= $isMentorSetup ? 'Setup mentor' : 'Selamat datang!' ?></h1>
            <p style="font-size: 25px;"><?= $isMentorSetup ? 'Buat akun mentor untuk spesialisasi yang tersedia.' : 'Masuk ke dashboard mentor.' ?></p>

            <div class="form-wrapper">
                
            <?php if (!empty($error)): ?>
                    <div style="background:#fee2e2;color:#991b1b;padding:10px;border-radius:6px;margin-bottom:12px;">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="loginad.php">
                    <input type="hidden" name="mode" value="<?= $isMentorSetup ? 'setup' : 'login' ?>">

                    <?php if ($isMentorSetup): ?>
                        <label>Nama Mentor</label>
                        <input type="text" name="name" placeholder="Ketik nama mentor" required>

                        <label>Spesialisasi</label>
                        <select name="specialty" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 16px; font-size: 15px;">
                            <option value="">Pilih Spesialisasi</option>
                            <?php foreach ($availableSpecialties as $spec): ?>
                                <option value="<?= htmlspecialchars($spec) ?>">
                                    <?= $spec === 'voice' ? 'Rekam Suara' : ($spec === 'challenge' ? 'Tantangan Bicara' : 'Camera Practice') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>

                    <label>Username</label>
                    <input type="text" name="username" placeholder="Ketik username anda" required>

                    <label>Kata Sandi</label>
                    <div class="password-wrapper">
                        <input id="password-input" type="password" name="password" placeholder="Ketik kata sandi anda" required>

                        <img class="eye-toggle" id="toggle-eye" src="../icon/pw.svg" style="width:32px; height:32px;">
                    </div>

                    <?php if ($isMentorSetup): ?>
                        <label>Konfirmasi Kata Sandi</label>
                        <input type="password" name="password_confirm" placeholder="Ulangi kata sandi" required>
                    <?php endif; ?>

                    <button type="submit" class="btn-login"><?= $isMentorSetup ? 'Buat Akun' : 'Masuk' ?></button>
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
                ? "../icon/buka.svg"
                : "../icon/pw.svg";
        });
    </script>


</body>

</html>
