<?php
// Deteksi halaman aktif secara otomatis berdasarkan nama file (case-insensitive)
$current_page = strtolower(basename($_SERVER['PHP_SELF']));

// Tentukan menu mana yang aktif
// Materi & sub-halaman materi semuanya highlight menu "Materi"
$materi_pages = [
    'materi.php', 'vokal.php', 'gerak-tubuh.php', 'lainnya.php', 
    'submaterivokal.php', 'submateripostertubuh.php', 'submaterikontakmata.php', 
    'submateriinotasisuara.php', 'submaterimengatasigrogi.php', 'submaterigesturtangan.php', 
    'submateripenyusunanmateri.php', 'submaterimediapesentasi.php', 'materi_detail.php'
];

if ($current_page === 'beranda.php') {
    $active = 'beranda';
} elseif (in_array($current_page, $materi_pages)) {
    $active = 'materi';
} elseif ($current_page === 'latihan.php') {
    $active = 'latihan';
} elseif ($current_page === 'hasillatihan.php') {
    $active = 'hasillatihan';
} elseif ($current_page === 'ebook.php') {
    $active = 'ebook';
} elseif ($current_page === 'komunitas.php') {
    $active = 'komunitas';
} elseif ($current_page === 'profil.php') {
    $active = 'profil';
} else {
    $active = '';
}
?>

<aside class="sidebar">
    <ul>
        <li class="menu-item <?= $active === 'beranda' ? 'active' : '' ?>">
            <?php if ($active === 'beranda'): ?>
                <svg class="icon" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M12 3l9 8h-3v9h-5v-6H11v6H6v-9H3l9-8z" />
                </svg>
                <span>Beranda</span>
            <?php else: ?>
                <a href="Beranda.php">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M12 3l9 8h-3v9h-5v-6H11v6H6v-9H3l9-8z" />
                    </svg>
                    <span>Beranda</span>
                </a>
            <?php endif; ?>
        </li>

        <li class="menu-item <?= $active === 'materi' ? 'active' : '' ?>">
            <?php if ($active === 'materi'): ?>
                <svg class="icon" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M21 4H14C12.9 4 12 4.9 12 6V18C12 16.9 12.9 16 14 16H21V4M10 6C10 4.9 9.1 4 8 4H3V16H10C11.1 16 12 16.9 12 18V6C12 4.9 11.1 4 10 4M3 18V20H8C9.1 20 10 19.1 10 18H3M14 18C14 19.1 14.9 20 16 20H21V18H14Z" />
                </svg>
                <span>Materi</span>
            <?php else: ?>
                <a href="Materi.php">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M21 4H14C12.9 4 12 4.9 12 6V18C12 16.9 12.9 16 14 16H21V4M10 6C10 4.9 9.1 4 8 4H3V16H10C11.1 16 12 16.9 12 18V6C12 4.9 11.1 4 10 4M3 18V20H8C9.1 20 10 19.1 10 18H3M14 18C14 19.1 14.9 20 16 20H21V18H14Z" />
                    </svg>
                    <span>Materi</span>
                </a>
            <?php endif; ?>
        </li>

        <li class="menu-item <?= $active === 'latihan' ? 'active' : '' ?>">
            <?php if ($active === 'latihan'): ?>
                    <svg class="icon" viewBox="0 0 45 45" fill="none"
    xmlns="http://www.w3.org/2000/svg">

    <path
        d="M16.5 37.5V29.8126L20.0625 30.1876C21.0996 30.1299 22.0811 29.6999 22.8266 28.9765C23.572 28.2531 24.0313 27.285 24.12 26.2501V15.5626C24.1324 12.8613 23.0713 10.2658 21.17 8.34694C19.2688 6.42809 16.6831 5.34311 13.9819 5.33067C11.2806 5.31824 8.68511 6.37938 6.76626 8.28065C4.84741 10.1819 3.76243 12.7676 3.75 15.4688C3.75 20.7188 4.98 21.1951 5.625 24.0001C6.05868 25.6944 6.07734 27.4683 5.67938 29.1713L3.75 37.5M37.125 33.3751C39.7609 30.7388 41.2422 27.1639 41.2432 23.4359C41.2443 19.7079 39.765 16.1321 37.1306 13.4944M31.875 28.1251C32.4885 27.5117 32.9742 26.7829 33.3042 25.9806C33.6342 25.1784 33.8019 24.3187 33.7975 23.4512C33.7931 22.5838 33.6169 21.7258 33.2788 20.9269C32.9408 20.128 32.4477 19.4041 31.8281 18.7969"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round" />
</svg>
                <span>Latihan</span>
            <?php else: ?>
                <a href="Latihan.php">
                    <svg class="icon" viewBox="0 0 45 45" fill="none"
    xmlns="http://www.w3.org/2000/svg">

    <path
        d="M16.5 37.5V29.8126L20.0625 30.1876C21.0996 30.1299 22.0811 29.6999 22.8266 28.9765C23.572 28.2531 24.0313 27.285 24.12 26.2501V15.5626C24.1324 12.8613 23.0713 10.2658 21.17 8.34694C19.2688 6.42809 16.6831 5.34311 13.9819 5.33067C11.2806 5.31824 8.68511 6.37938 6.76626 8.28065C4.84741 10.1819 3.76243 12.7676 3.75 15.4688C3.75 20.7188 4.98 21.1951 5.625 24.0001C6.05868 25.6944 6.07734 27.4683 5.67938 29.1713L3.75 37.5M37.125 33.3751C39.7609 30.7388 41.2422 27.1639 41.2432 23.4359C41.2443 19.7079 39.765 16.1321 37.1306 13.4944M31.875 28.1251C32.4885 27.5117 32.9742 26.7829 33.3042 25.9806C33.6342 25.1784 33.8019 24.3187 33.7975 23.4512C33.7931 22.5838 33.6169 21.7258 33.2788 20.9269C32.9408 20.128 32.4477 19.4041 31.8281 18.7969"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round" />
</svg>
                    <span>Latihan</span>
                </a>
            <?php endif; ?>
        </li>

        <li class="menu-item <?= $active === 'hasillatihan' ? 'active' : '' ?>">
            <?php if ($active === 'hasillatihan'): ?>
                <svg class="icon" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
                </svg>
                <span>Hasil Latihan</span>
            <?php else: ?>
                <a href="HasilLatihan.php">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
                    </svg>
                    <span>Hasil Latihan</span>
                </a>
            <?php endif; ?>
        </li>

        <li class="menu-item <?= $active === 'ebook' ? 'active' : '' ?>">
            <?php if ($active === 'ebook'): ?>
                <svg class="icon" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M21 4H14C12.9 4 12 4.9 12 6V18C12 16.9 12.9 16 14 16H21V4M10 6C10 4.9 9.1 4 8 4H3V16H10C11.1 16 12 16.9 12 18V6C12 4.9 11.1 4 10 4M3 18V20H8C9.1 20 10 19.1 10 18H3M14 18C14 19.1 14.9 20 16 20H21V18H14Z" />
                </svg>
                <span>E-Book</span>
            <?php else: ?>
                <a href="Ebook.php">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M21 4H14C12.9 4 12 4.9 12 6V18C12 16.9 12.9 16 14 16H21V4M10 6C10 4.9 9.1 4 8 4H3V16H10C11.1 16 12 16.9 12 18V6C12 4.9 11.1 4 10 4M3 18V20H8C9.1 20 10 19.1 10 18H3M14 18C14 19.1 14.9 20 16 20H21V18H14Z" />
                    </svg>
                    <span>E-Book</span>
                </a>
            <?php endif; ?>
        </li>

        <li class="menu-item <?= $active === 'komunitas' ? 'active' : '' ?>">
            <?php if ($active === 'komunitas'): ?>
                <svg class="icon" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                </svg>
                <span>Komunitas</span>
            <?php else: ?>
                <a href="Komunitas.php">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                    </svg>
                    <span>Komunitas</span>
                </a>
            <?php endif; ?>
        </li>

        <li class="menu-item <?= $active === 'profil' ? 'active' : '' ?>">
            <?php if ($active === 'profil'): ?>
                <svg class="icon" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M12 12c2.67 0 8 1.34 8 4v3H4v-3c0-2.66 5.33-4 8-4zm0-2a4 4 0 110-8 4 4 0 010 8z" />
                </svg>
                <span>Profil</span>
            <?php else: ?>
                <a href="Profil.php">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M12 12c2.67 0 8 1.34 8 4v3H4v-3c0-2.66 5.33-4 8-4zm0-2a4 4 0 110-8 4 4 0 010 8z" />
                    </svg>
                    <span>Profil</span>
                </a>
            <?php endif; ?>
        </li>
    </ul>
</aside>
