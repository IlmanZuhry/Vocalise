<?php
$current_page = strtolower(basename($_SERVER['PHP_SELF']));
$current_hash = $_SERVER['QUERY_STRING'] ?? '';

$active = '';
if ($current_page === 'dashboard.php') {
    $active = 'beranda';
} elseif ($current_page === 'penilaian.php') {
    $active = 'penilaian';
} elseif ($current_page === 'ebook.php') {
    $active = 'ebook';
} elseif ($current_page === 'materi.php') {
    $active = 'materi';
} elseif ($current_page === 'profil.php') {
    $active = 'profil';
}

$menus = [
    [
        'key' => 'beranda',
        'label' => 'Beranda',
        'href' => 'dashboard.php',
        'icon' => '<path fill="currentColor" d="M12 3l9 8h-3v9h-5v-6H11v6H6v-9H3l9-8z" />'
    ],
    [
        'key' => 'penilaian',
        'label' => 'Penilaian',
        'href' => 'penilaian.php',
        'icon' => '<path fill="currentColor" d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-8 14H7v-2h4v2zm6-4H7v-2h10v2zm0-4H7V7h10v2z" />'
    ],
    [
        'key' => 'ebook',
        'label' => 'E-Book',
        'href' => 'ebook.php',
        'icon' => '<path fill="currentColor" d="M21 4H14C12.9 4 12 4.9 12 6V18C12 16.9 12.9 16 14 16H21V4M10 6C10 4.9 9.1 4 8 4H3V16H10C11.1 16 12 16.9 12 18V6C12 4.9 11.1 4 10 4M3 18V20H8C9.1 20 10 19.1 10 18H3M14 18C14 19.1 14.9 20 16 20H21V18H14Z" />'
    ],
    [
        'key' => 'materi',
        'label' => 'Materi',
        'href' => 'materi.php',
        'icon' => '<path fill="currentColor" d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9H9V9h10v2zm-4 4H9v-2h6v2zm4-8H9V5h10v2z"/>'
    ],
    [
        'key' => 'profil',
        'label' => 'Profil',
        'href' => 'profil.php',
        'icon' => '<path fill="currentColor" d="M12 12c2.67 0 8 1.34 8 4v3H4v-3c0-2.66 5.33-4 8-4zm0-2a4 4 0 110-8 4 4 0 010 8z" />'
    ],
];
?>

<?php
$mentorLabel = 'Mentor';
if (isset($mentor) && $mentor) {
    if ($mentor['specialty'] === 'voice') $mentorLabel = 'Mentor Suara';
    elseif ($mentor['specialty'] === 'challenge') $mentorLabel = 'Mentor Tantangan';
    elseif ($mentor['specialty'] === 'camera') $mentorLabel = 'Mentor Kamera';
}
?>

<aside class="sidebar">
    <?php if (isset($mentor) && $mentor): ?>
    <div style="padding: 20px; text-align: center; border-bottom: 1px solid #eef2f7; margin-bottom: 10px;">
        <div style="width: 50px; height: 50px; background: #eef2f7; color: #10204f; font-weight: bold; font-size: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">
            <?= strtoupper(substr($mentor['name'], 0, 1)) ?>
        </div>
        <div style="color: #10204f; font-weight: 800; font-size: 15px; margin-bottom: 4px;"><?= htmlspecialchars($mentor['name']) ?></div>
        <div style="display: inline-block; background: #d2a06b; color: #fff; font-size: 11px; font-weight: bold; padding: 3px 8px; border-radius: 999px; letter-spacing: 0.5px; text-transform: uppercase;">
            <?= $mentorLabel ?>
        </div>
    </div>
    <?php endif; ?>
    <ul>
        <?php foreach ($menus as $menu): ?>
            <li class="menu-item <?= $active === $menu['key'] ? 'active' : '' ?>">
                <?php if ($active === $menu['key']): ?>
                    <svg class="icon" viewBox="0 0 24 24">
                        <?= $menu['icon'] ?>
                    </svg>
                    <span><?= $menu['label'] ?></span>
                <?php else: ?>
                    <a href="<?= $menu['href'] ?>">
                        <svg class="icon" viewBox="0 0 24 24">
                            <?= $menu['icon'] ?>
                        </svg>
                        <span><?= $menu['label'] ?></span>
                    </a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</aside>
