<?php
require_once __DIR__ . '/../../core.php';
if (!isset($app) || !($app instanceof manz)) {
    $app = new manz();
}

$app->ensureSession();
$mentor = $mentor ?? $app->getCurrentMentor();

if (!$mentor) {
    header('Location: loginad.php');
    exit;
}
?>

<style>
    .profile-menu-wrap {
        position: relative;
    }

    .profile-menu-toggle {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 6px 8px;
        border: 0;
        border-radius: 14px;
        background: transparent;
        color: inherit;
        cursor: pointer;
        text-align: left;
        transition: background 0.2s ease;
    }

    .profile-menu-toggle:hover,
    .profile-menu-toggle.is-open {
        background: rgba(255, 255, 255, 0.12);
    }

    .profile-menu-toggle:focus-visible {
        outline: 3px solid rgba(210, 160, 107, 0.75);
        outline-offset: 3px;
    }

    .profile-dropdown {
        position: absolute;
        top: calc(100% + 12px);
        right: 0;
        width: 210px;
        padding: 8px;
        background: #ffffff;
        border: 1px solid rgba(14, 30, 77, 0.08);
        border-radius: 14px;
        box-shadow: 0 14px 34px rgba(0, 0, 0, 0.18);
        opacity: 0;
        transform: translateY(-6px);
        pointer-events: none;
        transition: opacity 0.18s ease, transform 0.18s ease;
        z-index: 1300;
    }

    .profile-dropdown.active {
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
    }

    .profile-dropdown::before {
        content: "";
        position: absolute;
        top: -7px;
        right: 28px;
        width: 14px;
        height: 14px;
        background: #ffffff;
        border-left: 1px solid rgba(14, 30, 77, 0.08);
        border-top: 1px solid rgba(14, 30, 77, 0.08);
        transform: rotate(45deg);
    }

    .profile-dropdown-item {
        position: relative;
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 12px 11px;
        border-radius: 10px;
        color: #1f2937;
        text-decoration: none;
        font-size: 15px;
        font-weight: 600;
        border: 0;
        background: transparent;
        cursor: pointer;
    }

    .profile-dropdown-item:hover {
        background: #f5f2ee;
        color: #0e1e4d;
    }

    .profile-dropdown-item.logout {
        color: #b42318;
    }

    .profile-dropdown-item svg {
        width: 20px;
        height: 20px;
        flex: 0 0 20px;
    }

    .logout-confirm-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 1500;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(0, 0, 0, 0.52);
    }

    .logout-confirm-overlay.active {
        display: flex;
    }

    .logout-confirm-box {
        width: min(360px, 100%);
        padding: 24px;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 18px 48px rgba(0, 0, 0, 0.24);
        color: #172033;
    }

    .logout-confirm-box h3 {
        margin: 0 0 8px;
        font-size: 22px;
        color: #0e1e4d;
    }

    .logout-confirm-box p {
        margin: 0;
        color: #667085;
        line-height: 1.45;
        font-size: 15px;
    }

    .logout-confirm-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 22px;
    }

    .logout-confirm-actions button {
        border: 0;
        border-radius: 10px;
        padding: 10px 18px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-cancel-logout {
        background: #eef0f4;
        color: #0e1e4d;
    }

    .btn-confirm-logout {
        background: #b42318;
        color: #ffffff;
    }
</style>

<header class="header">
    <div class="header-left">
        <img src="../assets/ayooo.png" class="logo-header" alt="Logo">
        <span class="brand-text">TALKLAB</span>
    </div>

    <div class="user-area">
        

        <div class="profile-menu-wrap">
            <button type="button" class="profile-menu-toggle" data-profile-menu-toggle aria-expanded="false" aria-haspopup="true">
                <div class="avatar" style="background:#d2a06b;display:flex;align-items:center;justify-content:center;">
                    <svg viewBox="0 0 24 24" width="30" height="30"><path fill="white" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                </div>

                <div class="user-info">
                    <div class="name"><?= htmlspecialchars($mentor['name']) ?></div>
                    <div class="username">@<?= htmlspecialchars($mentor['username']) ?></div>
                </div>
            </button>

            <div class="profile-dropdown" data-profile-menu>
                <a href="profil.php" class="profile-dropdown-item">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 12c2.67 0 8 1.34 8 4v3H4v-3c0-2.66 5.33-4 8-4zm0-2a4 4 0 110-8 4 4 0 010 8z"/></svg>
                    <span>Profil</span>
                </a>
                <a href="logoutad.php" class="profile-dropdown-item logout" data-logout-link>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M16 13v-2H8V8l-5 4 5 4v-3h8zm3-10H11a2 2 0 00-2 2v3h2V5h8v14h-8v-3H9v3a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2z"/></svg>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>
</header>

<div class="logout-confirm-overlay" data-logout-modal aria-hidden="true">
    <div class="logout-confirm-box" role="dialog" aria-modal="true" aria-labelledby="logoutConfirmTitle">
        <h3 id="logoutConfirmTitle">Yakin logout?</h3>
        <p>Sesi admin akan ditutup dan kamu perlu login lagi untuk mengakses dashboard mentor.</p>
        <div class="logout-confirm-actions">
            <button type="button" class="btn-cancel-logout" data-logout-cancel>Batal</button>
            <button type="button" class="btn-confirm-logout" data-logout-confirm>Logout</button>
        </div>
    </div>
</div>

<script>
    (() => {
        const profileToggle = document.querySelector('[data-profile-menu-toggle]');
        const profileMenu = document.querySelector('[data-profile-menu]');
        const logoutModal = document.querySelector('[data-logout-modal]');
        const cancelLogout = document.querySelector('[data-logout-cancel]');
        const confirmLogout = document.querySelector('[data-logout-confirm]');
        let logoutTarget = 'logoutad.php';

        const closeProfileMenu = () => {
            if (!profileToggle || !profileMenu) return;
            profileMenu.classList.remove('active');
            profileToggle.classList.remove('is-open');
            profileToggle.setAttribute('aria-expanded', 'false');
        };

        if (profileToggle && profileMenu) {
            profileToggle.addEventListener('click', (event) => {
                event.stopPropagation();
                const isOpen = profileMenu.classList.toggle('active');
                profileToggle.classList.toggle('is-open', isOpen);
                profileToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });

            document.addEventListener('click', (event) => {
                if (!profileMenu.contains(event.target) && !profileToggle.contains(event.target)) {
                    closeProfileMenu();
                }
            });
        }

        const openLogoutModal = () => {
            if (!logoutModal) return;
            logoutModal.classList.add('active');
            logoutModal.setAttribute('aria-hidden', 'false');
        };

        const closeLogoutModal = () => {
            if (!logoutModal) return;
            logoutModal.classList.remove('active');
            logoutModal.setAttribute('aria-hidden', 'true');
        };

        document.addEventListener('click', (event) => {
            const logoutLink = event.target.closest('[data-logout-link]');
            if (!logoutLink) return;

            event.preventDefault();
            logoutTarget = logoutLink.getAttribute('href') || 'logoutad.php';
            closeProfileMenu();
            openLogoutModal();
        });

        if (cancelLogout) {
            cancelLogout.addEventListener('click', closeLogoutModal);
        }

        if (logoutModal) {
            logoutModal.addEventListener('click', (event) => {
                if (event.target === logoutModal) closeLogoutModal();
            });
        }

        if (confirmLogout) {
            confirmLogout.addEventListener('click', () => {
                window.location.href = logoutTarget;
            });
        }

        document.addEventListener('keydown', (event) => {
            if (event.key !== 'Escape') return;
            closeProfileMenu();
            closeLogoutModal();
        });
    })();
</script>
