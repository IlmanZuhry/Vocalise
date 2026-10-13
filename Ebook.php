<?php
require_once 'core.php';
if (session_status() == PHP_SESSION_NONE) session_start();
$app = new manz();
$search_query = $_GET['search'] ?? '';
$ebooks = $app->getEbooks($search_query);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TALKLAB - E-Book</title>
    <link rel="stylesheet" href="style.css" />
    <?php include 'includes/layout_css.php'; ?>
    <style>
        main {
            flex: 1;
            padding: 120px 40px 40px;
            margin-left: 260px;
            width: calc(100% - 260px);
            background-color: #f7f7fc;
            min-height: calc(100vh - 120px);
            position: relative;
            z-index: 1;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 38px;
            margin-bottom: 8px;
            color: #1f2b52;
            font-weight: 700;
        }

        .page-header p {
            color: #5d677a;
            font-size: 17px;
            line-height: 1.5;
        }

        .search-bar {
            max-width: 620px;
            width: 100%;
            display: flex;
            justify-content: flex-start;
            margin-bottom: 35px;
        }

        .search-bar input[type="search"] {
            width: 100%;
            border: none;
            padding: 16px 26px;
            border-radius: 25px;
            box-shadow: 0px 6px 18px rgb(0 0 0 / 0.08);
            font-size: 16px;
            color: #333;
            outline: none;
        }

        .search-bar input[type="search"]::placeholder {
            color: #9ea7b8;
        }

        .ebook-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
            max-width: 1200px;
        }

        .ebook-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            display: flex;
            flex-direction: column;
        }

        .ebook-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.12);
        }

        .ebook-card img {
            width: 100%;
            height: 240px;
            object-fit: cover;
            display: block;
        }

        .ebook-card-body {
            padding: 24px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .ebook-card-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #1f2b52;
        }

        .ebook-card-author {
            font-size: 14px;
            color: #6c7280;
            margin-bottom: 16px;
        }

        .ebook-card-pages {
            font-size: 14px;
            color: #5c6676;
        }

        .ebook-card-footer {
            margin-top: auto;
            padding-top: 18px;
        }

        .ebook-card-footer .btn-primary {
            width: 100%;
            border-radius: 20px;
            padding: 12px 0;
            font-size: 16px;
            background-color: #d2a06b;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .ebook-card-footer .btn-primary:hover {
            background-color: #c89555;
        }

        .ebook-card-footer .btn-primary:active {
            transform: scale(0.98);
        }

        .no-results {
            text-align: center;
            padding: 40px 20px;
            color: #6c7280;
            font-size: 18px;
            grid-column: 1 / -1;
        }

        /* Responsive design */
        @media (max-width: 1024px) {
            main {
                margin-left: 260px;
                padding: 100px 30px 40px;
            }

            .page-header h1 {
                font-size: 32px;
            }

            .ebook-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            main {
                margin-left: 0;
                padding: 100px 20px 40px;
            }

            .page-header h1 {
                font-size: 28px;
            }

            .page-header p {
                font-size: 15px;
            }

            .search-bar {
                margin-bottom: 25px;
            }

            .search-bar input[type="search"] {
                padding: 12px 20px;
                font-size: 14px;
            }

            .ebook-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 16px;
            }

            .ebook-card img {
                height: 180px;
            }

            .ebook-card-body {
                padding: 16px;
            }

            .ebook-card-title {
                font-size: 16px;
                margin-bottom: 8px;
            }

            .ebook-card-author {
                font-size: 12px;
                margin-bottom: 12px;
            }

            .ebook-card-pages {
                font-size: 12px;
            }

            .ebook-card-footer .btn-primary {
                padding: 10px 0;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            main {
                padding: 100px 15px 40px;
            }

            .page-header h1 {
                font-size: 24px;
                margin-bottom: 5px;
            }

            .page-header p {
                font-size: 14px;
            }

            .ebook-grid {
                grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
                gap: 12px;
            }

            .ebook-card img {
                height: 160px;
            }

            .ebook-card-body {
                padding: 12px;
            }

            .ebook-card-title {
                font-size: 14px;
            }

            .ebook-card-pages {
                font-size: 11px;
            }
        }
    </style>
</head>

<body>

    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>

    <main>
        <section class="page-header">
            <h1>E-Book</h1>
            <p>Pilih buku yang ingin kamu baca</p>
        </section>

        <div class="search-bar">
            <input type="search" id="searchInput" placeholder="Cari Judul E-Book..." aria-label="Cari Judul E-Book">
        </div>

        <section class="ebook-grid" id="ebookGrid">
            <?php
            if (count($ebooks) > 0) {
                foreach ($ebooks as $ebook) {
                    echo '<article class="ebook-card" data-title="' . htmlspecialchars(strtolower($ebook['title'])) . '">';
                    echo '<img src="' . htmlspecialchars($ebook['thumbnail_path']) . '" alt="Cover ' . htmlspecialchars($ebook['title']) . '">';
                    echo '<div class="ebook-card-body">';
                    echo '<div class="ebook-card-title">' . htmlspecialchars($ebook['title']) . '</div>';
                    echo '<div class="ebook-card-author">' . htmlspecialchars($ebook['author']) . '</div>';
                    if ((int) $ebook['pages'] > 0) {
                        echo '<div class="ebook-card-pages">' . (int) $ebook['pages'] . ' Halaman</div>';
                    }
                    echo '<div class="ebook-card-footer">';
                    echo '<a href="' . htmlspecialchars($ebook['pdf_path']) . '" target="_blank" class="btn-primary ebook-read-btn" data-ebook-id="' . (int) $ebook['id'] . '" data-ebook-title="' . htmlspecialchars($ebook['title'], ENT_QUOTES) . '" style="display: block; text-align: center; text-decoration: none;">Baca</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</article>';
                }
            } else {
                echo '<div class="no-results" style="grid-column: 1 / -1;">Tidak ada e-book yang sesuai dengan pencarian Anda</div>';
            }
            ?>
        </section>
    </main>

    <script>
        // Fitur pencarian real-time
        const searchInput = document.getElementById('searchInput');
        const ebookGrid = document.getElementById('ebookGrid');
        const ebookCards = document.querySelectorAll('.ebook-card');

        // Data ebook dari PHP
        const ebooksData = <?php echo json_encode($app->getEbooks()); ?>;

        // Fungsi untuk melakukan pencarian
        function performSearch(query) {
            const searchQuery = query.toLowerCase().trim();

            if (searchQuery === '') {
                renderEbooks(ebooksData);
                return;
            }

            // Filter ebook berdasarkan query
            const filteredEbooks = ebooksData.filter(ebook => 
                ebook.title.toLowerCase().includes(searchQuery)
            );

            renderEbooks(filteredEbooks);
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text ?? '';
            return div.innerHTML;
        }

        function renderEbooks(items) {
            ebookGrid.innerHTML = '';

            if (items.length > 0) {
                items.forEach(ebook => {
                    const card = document.createElement('article');
                    card.className = 'ebook-card';
                    const pages = parseInt(ebook.pages, 10);
                    card.innerHTML = `
                        <img src="${escapeHtml(ebook.thumbnail_path)}" alt="Cover ${escapeHtml(ebook.title)}">
                        <div class="ebook-card-body">
                            <div class="ebook-card-title">${escapeHtml(ebook.title)}</div>
                            <div class="ebook-card-author">${escapeHtml(ebook.author)}</div>
                            ${pages > 0 ? `<div class="ebook-card-pages">${pages} Halaman</div>` : ''}
                            <div class="ebook-card-footer">
                                <a href="${escapeHtml(ebook.pdf_path)}" target="_blank" class="btn-primary ebook-read-btn" data-ebook-id="${escapeHtml(ebook.id)}" data-ebook-title="${escapeHtml(ebook.title)}" style="display: block; text-align: center; text-decoration: none;">Baca</a>
                            </div>
                        </div>
                    `;
                    ebookGrid.appendChild(card);
                });
            } else {
                const noResults = document.createElement('div');
                noResults.className = 'no-results';
                noResults.style.gridColumn = '1 / -1';
                noResults.textContent = 'Tidak ada e-book yang sesuai dengan pencarian Anda';
                ebookGrid.appendChild(noResults);
            }
            setupEbookLoggers();
        }

        // Add event listener to ebook read buttons
        function logEbookRead(ebookId, ebookTitle) {
            if (!ebookId) return;
            const formData = new FormData();
            formData.append('action', 'save_read');
            formData.append('ebook_id', ebookId);
            formData.append('ebook_title', ebookTitle);

            fetch('api_ebook.php', {
                method: 'POST',
                body: formData
            }).catch(() => {});
        }

        function setupEbookLoggers() {
            document.querySelectorAll('.ebook-read-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const ebookId = btn.dataset.ebookId;
                    const ebookTitle = btn.dataset.ebookTitle || '';
                    logEbookRead(ebookId, ebookTitle);
                });
            });
        }

        // Event listener untuk search input
        searchInput.addEventListener('input', (e) => {
            performSearch(e.target.value);
        });

        // Event listener untuk Enter key
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                performSearch(e.target.value);
            }
        });

        setupEbookLoggers();
    </script>

</body>

</html>
