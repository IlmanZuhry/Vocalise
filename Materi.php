<?php
require_once 'core.php';
if (session_status() == PHP_SESSION_NONE) session_start();
$app = new manz();

$currentUser = $app->getCurrentUser();
$categoryFilter = $_GET['category'] ?? null;

$materials = $app->getMaterials($categoryFilter);
$progressData = [];

if ($currentUser) {
    foreach ($materials as $mat) {
        $mId = $mat['id'];
        $videos = $app->getMaterialVideos($mId);
        $total = count($videos);
        
        if ($total > 0) {
            $completed = $app->getMaterialProgressCount($currentUser['Id_User'], $mId);
            if ($completed > $total) $completed = $total;
            if ($completed < 0) $completed = 0;
            $pct = round(($completed / $total) * 100);
            $progressData[$mId] = $pct;
        } else {
            $progressData[$mId] = 0;
        }
    }
} else {
    foreach ($materials as $mat) {
        $progressData[$mat['id']] = 0;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TALKLAB - Materi Pembelajaran</title>
  <?php include 'includes/layout_css.php'; ?>
  <style>
    main {
      flex: 1;
      padding: 120px 40px 40px;
      margin-left: 327px;
    }

    a {
      text-decoration: none;
    }

    .search-bar {
      max-width: 500px;
      width: 100%;
      margin-bottom: 20px;
      display: flex;
      justify-content: flex-start;
    }

    .search-bar input[type="search"] {
      width: 100%;
      border: none;
      padding: 14px 26px;
      border-radius: 20px;
      box-shadow: 0px 0px 15px rgb(0 0 0 / 0.1);
      font-size: 14px;
      color: #888;
    }

    .search-bar input[type="search"]::placeholder {
      color: #ccc;
    }

    .filter-group {
      display: flex;
      gap: 10px;
      margin-bottom: 25px;
      justify-content: flex-start;
    }

    .filter-group a button {
      border: none;
      padding: 14px 26px;
      border-radius: 20px;
      background-color: white;
      font-weight: 600;
      cursor: pointer;
      box-shadow: 0 0 5px rgb(0 0 0 / 0.1);
      color: #1f2b52;
      transition: all 0.3s;
    }

    .filter-group a.active button {
      background-color: #ba8e58;
      color: white;
      font-weight: 700;
      box-shadow: none;
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 25px;
      justify-content: center;
      width: 100%;
      max-width: 1200px;
    }

    .card {
      background: white;
      border-radius: 15px;
      box-shadow: 0px 4px 8px rgb(0 0 0 / 0.1);
      overflow: hidden;
      transition: transform 0.3s;
      cursor: pointer;
      height: 300px;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0px 6px 12px rgb(0 0 0 / 0.15);
    }

    .card-header {
      height: 120px;
      display: flex;
      justify-content: center;
      align-items: center;
      color: white;
      font-size: 50px;
      font-weight: 700;
    }

    .vokal { background-color: #5f73b6; }
    .gerak-tubuh { background-color: #bddbec; color: #a6c8ca; font-weight: 900; font-size: 48px; }
    .kontak-mata { background-color: #c9db28; color: white; font-weight: 700; font-size: 48px; }
    .intonasi { background-color: #f41968; color: white; font-weight: 700; font-size: 48px; }
    .mengatasi-grogi { background-color: #f8cae0; color: #bb8aa1; font-weight: 700; font-size: 48px; }
    .gestur-tangan { background-color: #f16722; color: white; font-weight: 700; font-size: 48px; }
    .penyusunan-materi { background-color: #f1e64c; color: white; font-weight: 700; font-size: 48px; }
    .media-presentasi { background-color: #f8a435; color: white; font-weight: 700; font-size: 48px; }
    .warna-ungu { background-color: #8b5cf6; color: white; font-weight: 700; font-size: 48px; }
    .warna-teal { background-color: #14b8a6; color: white; font-weight: 700; font-size: 48px; }
    .warna-merah { background-color: #ef4444; color: white; font-weight: 700; font-size: 48px; }
    .warna-indigo { background-color: #4f46e5; color: white; font-weight: 700; font-size: 48px; }
    .warna-coklat { background-color: #8B4513; color: white; font-weight: 700; font-size: 48px; }
    .warna-abuabu { background-color: #64748b; color: white; font-weight: 700; font-size: 48px; }
    .warna-hitam { background-color: #0f172a; color: white; font-weight: 700; font-size: 48px; }
    .warna-magenta { background-color: #d946ef; color: white; font-weight: 700; font-size: 48px; }
    .warna-cyan { background-color: #06b6d4; color: white; font-weight: 700; font-size: 48px; }
    .warna-lime { background-color: #84cc16; color: white; font-weight: 700; font-size: 48px; }
    .warna-emerald { background-color: #10b981; color: white; font-weight: 700; font-size: 48px; }
    .warna-rose { background-color: #f43f5e; color: white; font-weight: 700; font-size: 48px; }
    .warna-sky { background-color: #0ea5e9; color: white; font-weight: 700; font-size: 48px; }
    .warna-amber { background-color: #f59e0b; color: white; font-weight: 700; font-size: 48px; }
    .warna-pink-muda { background-color: #f472b6; color: white; font-weight: 700; font-size: 48px; }
    .warna-maroon { background-color: #9f1239; color: white; font-weight: 700; font-size: 48px; }
    .warna-navy { background-color: #1e3a8a; color: white; font-weight: 700; font-size: 48px; }
    .warna-peach { background-color: #fb923c; color: white; font-weight: 700; font-size: 48px; }
    .warna-olive { background-color: #65a30d; color: white; font-weight: 700; font-size: 48px; }
    .warna-lavender { background-color: #a78bfa; color: white; font-weight: 700; font-size: 48px; }

    .card-body {
      padding: 12px 15px 20px;
      color: #b4b4b4;
    }

    .card-title {
      font-weight: 700;
      font-size: 17px;
      margin-bottom: 4px;
      color: #2a2a34;
    }

    .card-desc {
      font-weight: 400;
      font-size: 15px;
      color: #b4b4b4;
      margin-bottom: 14px;
      min-height: 42px;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .card-info {
      font-size: 15px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-weight: 400;
    }

    .card-info .time {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .card-info .time svg {
      width: 14px;
      height: 14px;
    }

    .progress-text {
      display: flex;
      justify-content: space-between;
      font-size: 14px;
      color: #555;
      font-weight: 600;
      margin-bottom: -35px;
      margin-top: 10px;
    }

    .progress-text span {
      font-weight: 600;
      color: #ba8e58;
    }

    .progress-bar {
      height: 6px;
      background-color: #eee;
      border-radius: 4px;
      margin-top: 40px;
      overflow: hidden;
    }

    .progress {
      height: 6px;
      background-color: #ba8e58;
      border-radius: 4px;
    }
  </style>
</head>

<body>
  <?php include 'includes/header.php'; ?>
  <?php include 'includes/sidebar.php'; ?>

  <main>
    <h1 style="font-size:40px; font-weight:700; color:#000;">Materi Pembelajaran</h1>
    <p style="color:#777; font-size:20px; margin-bottom:30px;">Pilih materi yang ingin kamu pelajari</p>
    <div class="search-bar">
      <input type="search" placeholder="Cari materi..." />
    </div>

    <div class="filter-group">
      <a href="Materi.php" class="<?= !$categoryFilter ? 'active' : '' ?>"><button>Semua</button></a>
      <a href="Materi.php?category=vokal" class="<?= $categoryFilter === 'vokal' ? 'active' : '' ?>"><button>Vokal</button></a>
      <a href="Materi.php?category=gerak tubuh" class="<?= $categoryFilter === 'gerak tubuh' ? 'active' : '' ?>"><button>Gerak Tubuh</button></a>
      <a href="Materi.php?category=lainnya" class="<?= $categoryFilter === 'lainnya' ? 'active' : '' ?>"><button>Lainnya</button></a>
    </div>

    <div class="cards">
      <?php foreach ($materials as $mat): ?>
      <a href="materi_detail.php?id=<?= htmlspecialchars($mat['id']) ?>">
        <div class="card">
          <div class="card-header <?= htmlspecialchars($mat['color_class']) ?>" title="<?= htmlspecialchars($mat['title']) ?>">
            <img src="<?= htmlspecialchars($mat['icon_file']) ?>" width="88" height="88" alt="Icon" onerror="this.style.display='none'">
          </div>
          <div class="card-body">
            <h2 class="card-title"><?= htmlspecialchars($mat['title']) ?></h2>
            <p class="card-desc"><?= htmlspecialchars($mat['description']) ?></p>
            <div class="card-info">
              <div class="time">
                <svg viewBox="0 0 24 24"><path d="M12 22c5.52 0 10-4.48 10-10S17.52 2 12 2s-10 4.48-10 10 4.48 10 10 10zm-.5-15h1v6l5.25 3.15-.75 1.23L11.5 13V7z" /></svg>
                <?= (int)$mat['time_minutes'] ?> menit
              </div>
              <div><?= htmlspecialchars($mat['category']) ?></div>
            </div>
            <div class="progress-text"><span>Progress</span><span><?= $progressData[$mat['id']] ?>%</span></div>
            <div class="progress-bar"><div class="progress" style="width: <?= $progressData[$mat['id']] ?>%;"></div></div>
          </div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const searchInput = document.querySelector('.search-bar input[type="search"]');
      const cards = document.querySelectorAll('.cards > a');
      
      if (searchInput) {
        searchInput.addEventListener('input', function(e) {
          const searchTerm = e.target.value.toLowerCase();
          
          cards.forEach(cardLink => {
            const titleEl = cardLink.querySelector('.card-title');
            if (titleEl) {
              const title = titleEl.textContent.toLowerCase();
              if (title.includes(searchTerm)) {
                cardLink.style.display = 'block';
              } else {
                cardLink.style.display = 'none';
              }
            }
          });
        });
      }
    });
  </script>
</body>

</html>
