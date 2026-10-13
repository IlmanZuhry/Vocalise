<?php
require_once '../core.php';

$app = new manz();
$mentor = $app->getCurrentMentor();

if (!$mentor) {
    header('Location: loginad.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TALKLAB - Profil Mentor</title>
    <?php include 'inc/layout_css.php'; ?>
    <style>
        .profile-page {
            padding-bottom: 48px;
        }

        .profile-hero {
            width: 100%;
            background: #0e1e4d;
            padding: 72px 40px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #fff;
            min-height: 240px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 12px 32px rgb(16 32 79 / 0.14);
        }

        .profile-ornament-left,
        .profile-ornament-right {
            position: absolute;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: rgba(80, 64, 64, 0.45);
        }

        .profile-ornament-left {
            left: -80px;
            bottom: -120px;
        }

        .profile-ornament-right {
            right: -80px;
            top: -120px;
        }

        .profile-identity {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .profile-avatar {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 5px solid #d2a06b;
            background: #d2a06b;
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
        }

        .profile-name {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 6px;
            color: #fff;
        }

        .profile-username {
            font-size: 22px;
            color: #d2a06b;
            margin-bottom: 10px;
        }

        .profile-role {
            font-size: 20px;
            color: rgba(255, 255, 255, 0.9);
        }

        .profile-menu-box {
            background: #fff;
            margin-top: 35px;
            padding: 25px;
            border-radius: 18px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            font-size: 22px;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .profile-menu-item {
            color: #111827;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .profile-menu-item img {
            width: 36px;
            height: 36px;
            flex: 0 0 auto;
        }

        .profile-menu-item.logout {
            color: #b42318;
        }

        @media (max-width: 900px) {
            .profile-hero {
                padding: 48px 28px;
            }

            .profile-avatar {
                width: 150px;
                height: 150px;
            }

            .profile-avatar svg {
                width: 72px;
                height: 72px;
            }
        }

        @media (max-width: 720px) {
            .profile-hero {
                align-items: flex-start;
            }

            .profile-identity {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <?php include 'inc/header.php'; ?>
    <?php include 'inc/sidebar.php'; ?>

    <main class="profile-page">
        <h1 class="admin-page-title">Profil</h1>
        <p class="admin-page-subtitle">Kelola akun mentor yang sedang aktif.</p>

        <section class="profile-hero">
            <div class="profile-ornament-left"></div>
            <div class="profile-ornament-right"></div>

            <div class="profile-identity">
                <div class="profile-avatar">
                    <svg viewBox="0 0 24 24" width="90" height="90" aria-hidden="true">
                        <path fill="white" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="profile-name"><?= htmlspecialchars($mentor['name']) ?></h2>
                    <p class="profile-username">@<?= htmlspecialchars($mentor['username']) ?></p>
                    <p class="profile-role">Mentor Admin</p>
                </div>
            </div>
        </section>

        <section class="profile-menu-box" aria-label="Menu profil admin">
            <div class="profile-menu-item">
                <img src="../icon/help.svg" alt="">
                <span>Bantuan</span>
            </div>
            <div class="profile-menu-item">
                <img src="../icon/info.svg" alt="">
                <span>Informasi Versi</span>
            </div>
            <a class="profile-menu-item logout" href="logoutad.php" data-logout-link>
                <img src="../icon/logout.svg" alt="">
                <span>Logout</span>
            </a>
        </section>
    </main>
</body>
</html>
