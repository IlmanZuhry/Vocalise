<?php
require_once 'core.php';

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

$app = new manz();
$app->ensureSession();
$currentUser = $app->getCurrentUser();

if (!$currentUser) {
	header('Location: login.php');
	exit;
}

$reviewResults = $app->getUserReviewResults($currentUser['Id_User']);
$reviewStats = $app->getUserReviewStats($currentUser['Id_User']);
$mentorInfo = $app->getMentorInfoForFeatures();

function featureLabel($type) {
	$labels = ['voice' => 'Rekam Suara', 'challenge' => 'Tantangan Bicara', 'camera' => 'Camera Practice'];
	return $labels[$type] ?? $type;
}

function featureIcon($type) {
	$icons = ['voice' => '🎙', 'challenge' => '⏱', 'camera' => '📹'];
	return $icons[$type] ?? '📋';
}

function statusLabel($status) {
	if ($status === 'reviewed') return 'Sudah Dinilai';
	if ($status === 'revision_requested') return 'Revisi';
	return 'Menunggu Penilaian';
}

function scoreColor($score) {
	if ($score >= 80) return '#027a48';
	if ($score >= 60) return '#b8752b';
	return '#b42318';
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TALKLAB - Hasil Latihan</title>
  <?php include 'includes/layout_css.php'; ?>
  <style>
    body { background: #f7f7fc; }

    main {
      flex: 1;
      margin-left: 260px;
      padding: 120px 40px 48px;
      color: #101828;
    }

    .page-head {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      gap: 20px;
      margin-bottom: 28px;
    }

    .page-head h1 {
      font-size: 40px;
      line-height: 1.1;
      margin-bottom: 8px;
      background: linear-gradient(135deg, #10204f 0%, #d2a06b 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .page-head p { color: #777; font-size: 20px; }

    .btn {
      border: 0;
      border-radius: 14px;
      padding: 12px 20px;
      font-weight: 800;
      font-size: 15px;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .btn-muted { background: #eef2f7; color: #344054; }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 16px;
      margin-bottom: 28px;
    }

    .stat-card {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 18px;
      padding: 24px;
      box-shadow: 0 8px 24px rgb(0 0 0 / 0.07);
      position: relative;
      overflow: hidden;
    }

    .stat-card::after {
      content: "";
      position: absolute;
      width: 100px;
      height: 100px;
      border-radius: 50%;
      right: -36px;
      top: -36px;
      background: rgb(210 160 107 / 0.12);
    }

    .stat-card span {
      display: block;
      color: #667085;
      font-size: 13px;
      font-weight: 800;
      margin-bottom: 12px;
    }

    .stat-card strong {
      color: #10204f;
      font-size: 36px;
      position: relative;
      z-index: 1;
    }

    .stat-card.accent strong { color: #d2a06b; }

    .filter-tabs {
      display: flex;
      gap: 10px;
      margin-bottom: 24px;
      flex-wrap: wrap;
    }

    .filter-tab {
      border: 1px solid #e5e7eb;
      background: #fff;
      color: #344054;
      border-radius: 14px;
      padding: 12px 18px;
      font-weight: 900;
      font-size: 14px;
      cursor: pointer;
      transition: all 0.2s;
    }

    .filter-tab.active {
      border-color: #d2a06b;
      background: #fffaf3;
      color: #10204f;
    }

    .filter-tab:hover {
      border-color: #d2a06b;
    }

    .results-list {
      display: flex;
      flex-direction: column;
      gap: 18px;
    }

    .result-card {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 18px;
      box-shadow: 0 8px 24px rgb(0 0 0 / 0.07);
      overflow: hidden;
      transition: transform 0.22s ease, box-shadow 0.22s ease;
    }

    .result-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 14px 34px rgb(16 32 79 / 0.12);
    }

    .result-card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 22px 24px 14px;
      gap: 14px;
      flex-wrap: wrap;
    }

    .result-card-header h3 {
      color: #10204f;
      font-size: 20px;
      margin: 0 0 6px;
    }

    .result-card-meta {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      align-items: center;
    }

    .badge {
      display: inline-flex;
      border-radius: 999px;
      padding: 7px 12px;
      font-size: 12px;
      font-weight: 800;
      background: #eef2f7;
      color: #344054;
    }

    .badge-pending { background: #fff1df; color: #9a5600; }
    .badge-reviewed { background: #dcfae6; color: #067647; }
    .badge-feature { background: #eff8ff; color: #175cd3; }
    .badge-mentor { background: #f4f3ff; color: #5925dc; }

    .status-reviewed { border-left: 4px solid #027a48; }
    .status-pending { border-left: 4px solid #d2a06b; }

    .result-card-body {
      padding: 0 24px 22px;
    }

    .score-grid {
      display: grid;
      grid-template-columns: repeat(5, minmax(0, 1fr));
      gap: 12px;
      margin-bottom: 18px;
    }

    .score-item {
      background: #f8fafc;
      border: 1px solid #e5e7eb;
      border-radius: 14px;
      padding: 16px;
      text-align: center;
    }

    .score-item span {
      display: block;
      color: #667085;
      font-size: 11px;
      font-weight: 800;
      margin-bottom: 8px;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }

    .score-item strong {
      font-size: 28px;
      line-height: 1;
    }

    .final-score {
      display: flex;
      align-items: center;
      gap: 18px;
      background: #10204f;
      border-radius: 18px;
      padding: 22px 26px;
      margin-bottom: 18px;
      position: relative;
      overflow: hidden;
    }

    .final-score::after {
      content: "";
      position: absolute;
      width: 160px;
      height: 160px;
      border-radius: 50%;
      right: -50px;
      top: -60px;
      background: rgb(210 160 107 / 0.25);
    }

    .score-ring {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: conic-gradient(#d2a06b var(--score, 0%), #1c3068 0);
      position: relative;
      flex-shrink: 0;
      z-index: 1;
    }

    .score-ring::after {
      content: "";
      position: absolute;
      width: 68px;
      height: 68px;
      border-radius: 50%;
      background: #10204f;
    }

    .score-ring strong {
      position: relative;
      z-index: 1;
      color: #fff;
      font-size: 26px;
    }

    .final-score-text {
      position: relative;
      z-index: 1;
    }

    .final-score-text strong {
      display: block;
      color: #d2a06b;
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 6px;
    }

    .final-score-text span {
      color: #dbe4ff;
      font-size: 14px;
      line-height: 1.5;
    }

    .feedback-section {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
      margin-bottom: 14px;
    }

    .feedback-box {
      border: 1px solid #e5e7eb;
      border-radius: 14px;
      padding: 16px;
      background: #fbfbfd;
    }

    .feedback-box h4 {
      color: #10204f;
      font-size: 14px;
      margin: 0 0 10px;
    }

    .feedback-box p {
      color: #344054;
      font-size: 14px;
      line-height: 1.6;
      margin: 0;
      white-space: pre-line;
    }

    .feedback-full {
      border: 1px solid #f2d7b8;
      border-radius: 14px;
      padding: 16px;
      background: #fffaf3;
    }

    .feedback-full h4 {
      color: #b8752b;
      font-size: 14px;
      margin: 0 0 10px;
    }

    .feedback-full p {
      color: #344054;
      font-size: 14px;
      line-height: 1.6;
      margin: 0;
      white-space: pre-line;
    }

    .pending-notice {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 20px 24px;
      background: #fffaf3;
      border: 1px solid #f2d7b8;
      border-radius: 14px;
      color: #9a5600;
      font-weight: 700;
      font-size: 15px;
    }

    .pending-notice .pending-icon {
      font-size: 28px;
      flex-shrink: 0;
    }

    .empty-state {
      color: #667085;
      background: #f8fafc;
      border: 1px dashed #cbd5e1;
      border-radius: 16px;
      padding: 40px 24px;
      line-height: 1.5;
      text-align: center;
      font-size: 16px;
    }

    audio { width: 100%; margin-top: 10px; }

    @media (max-width: 1100px) {
      main { margin-left: 0; padding: 112px 24px 36px; }
      .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
      .score-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
      .feedback-section { grid-template-columns: 1fr; }
      .page-head { flex-direction: column; align-items: flex-start; }
    }

    @media (max-width: 560px) {
      .stats-grid { grid-template-columns: 1fr; }
      .score-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
  </style>
</head>

<body>
  <?php include 'includes/header.php'; ?>
  <?php include 'includes/sidebar.php'; ?>

  <main>
    <section class="page-head">
      <div>
        <h1>Hasil Latihan</h1>
        <p>Lihat penilaian dan feedback dari mentor untuk setiap latihan yang kamu kirimkan.</p>
      </div>
      <a href="Latihan.php" class="btn btn-muted">Kembali ke Latihan</a>
    </section>

    <section class="stats-grid" aria-label="Statistik penilaian">
      <div class="stat-card">
        <span>Total Dikirim</span>
        <strong><?= (int) $reviewStats['total'] ?></strong>
      </div>
      <div class="stat-card">
        <span>Menunggu Nilai</span>
        <strong><?= (int) $reviewStats['pending'] ?></strong>
      </div>
      <div class="stat-card">
        <span>Sudah Dinilai</span>
        <strong><?= (int) $reviewStats['reviewed'] ?></strong>
      </div>
      <div class="stat-card accent">
        <span>Rata-rata Nilai</span>
        <strong><?= (int) $reviewStats['average_score'] ?></strong>
      </div>
    </section>

    <div class="filter-tabs" id="filterTabs">
      <button class="filter-tab active" type="button" data-filter="all">Semua</button>
      <button class="filter-tab" type="button" data-filter="voice">🎙 Rekam Suara</button>
      <button class="filter-tab" type="button" data-filter="challenge">⏱ Tantangan Bicara</button>
      <button class="filter-tab" type="button" data-filter="camera">📹 Camera Practice</button>
    </div>

    <div class="results-list" id="resultsList">
      <?php if (empty($reviewResults)): ?>
        <div class="empty-state">
          Belum ada latihan yang dikirim ke mentor.<br>
          Mulai latihan di halaman Latihan, simpan, lalu klik "Kirim ke Mentor" untuk mendapatkan penilaian.
        </div>
      <?php else: ?>
        <?php foreach ($reviewResults as $result): ?>
          <div class="result-card <?= $result['status'] === 'reviewed' ? 'status-reviewed' : 'status-pending' ?>" data-feature="<?= htmlspecialchars($result['feature_type']) ?>">
            <div class="result-card-header">
              <div>
                <h3><?= htmlspecialchars($result['script_title'] ?? $result['topic']) ?></h3>
                <div class="result-card-meta">
                  <span class="badge badge-feature"><?= featureIcon($result['feature_type']) ?> <?= featureLabel($result['feature_type']) ?></span>
                  <?php if ($result['category']): ?>
                    <span class="badge"><?= htmlspecialchars($result['category']) ?></span>
                  <?php endif; ?>
                  <?php if ($result['level_name']): ?>
                    <span class="badge"><?= htmlspecialchars($result['level_name']) ?></span>
                  <?php endif; ?>
                  <span class="badge"><?= (int) $result['duration_seconds'] ?> detik</span>
                  <?php if ($result['mentor_name']): ?>
                    <span class="badge badge-mentor">👨‍🏫 <?= htmlspecialchars($result['mentor_name']) ?></span>
                  <?php endif; ?>
                </div>
              </div>
              <div style="text-align: right;">
                <span class="badge <?= $result['status'] === 'reviewed' ? 'badge-reviewed' : 'badge-pending' ?>">
                  <?= statusLabel($result['status']) ?>
                </span>
                <div style="color:#667085; font-size:12px; font-weight:700; margin-top:6px;">
                  <?= htmlspecialchars(date('d M Y H:i', strtotime($result['submitted_at']))) ?>
                </div>
              </div>
            </div>

            <div class="result-card-body">
              <?php if ($result['feature_type'] === 'camera' && !empty($result['video_path'])): ?>
                <video controls src="<?= htmlspecialchars($result['video_path']) ?>" style="width:100%; border-radius:8px; margin-bottom:10px;"></video>
              <?php endif; ?>
              <?php if ($result['audio_path']): ?>
                <audio controls src="<?= htmlspecialchars($result['audio_path']) ?>"></audio>
              <?php endif; ?>

              <?php if ($result['status'] === 'reviewed' && $result['final_score'] !== null): ?>
                <div class="final-score">
                  <div class="score-ring" style="--score: <?= (int) $result['final_score'] ?>%;">
                    <strong><?= (int) $result['final_score'] ?></strong>
                  </div>
                  <div class="final-score-text">
                    <strong>Nilai Akhir</strong>
                    <span>
                      <?php if ($result['final_score'] >= 80): ?>
                        Luar biasa! Terus pertahankan kualitas public speaking-mu.
                      <?php elseif ($result['final_score'] >= 60): ?>
                        Bagus! Ada beberapa area yang bisa ditingkatkan.
                      <?php else: ?>
                        Terus berlatih, kamu pasti bisa lebih baik lagi!
                      <?php endif; ?>
                    </span>
                  </div>
                </div>

                <div class="score-grid">
                  <div class="score-item">
                    <span>Artikulasi</span>
                    <strong style="color: <?= scoreColor($result['articulation_score']) ?>"><?= (int) $result['articulation_score'] ?></strong>
                  </div>
                  <div class="score-item">
                    <span>Kelancaran</span>
                    <strong style="color: <?= scoreColor($result['fluency_score']) ?>"><?= (int) $result['fluency_score'] ?></strong>
                  </div>
                  <div class="score-item">
                    <span>Percaya Diri</span>
                    <strong style="color: <?= scoreColor($result['confidence_score']) ?>"><?= (int) $result['confidence_score'] ?></strong>
                  </div>
                  <div class="score-item">
                    <span>Struktur</span>
                    <strong style="color: <?= scoreColor($result['structure_score']) ?>"><?= (int) $result['structure_score'] ?></strong>
                  </div>
                  <div class="score-item">
                    <span>Intonasi</span>
                    <strong style="color: <?= scoreColor($result['intonation_score']) ?>"><?= (int) $result['intonation_score'] ?></strong>
                  </div>
                </div>

                <div class="feedback-section">
                  <div class="feedback-box">
                    <h4>✅ Kelebihan</h4>
                    <p><?= nl2br(htmlspecialchars($result['strengths'])) ?></p>
                  </div>
                  <div class="feedback-box">
                    <h4>📝 Perlu Diperbaiki</h4>
                    <p><?= nl2br(htmlspecialchars($result['improvements'])) ?></p>
                  </div>
                </div>

                <div class="feedback-full">
                  <h4>💬 Feedback Mentor</h4>
                  <p><?= nl2br(htmlspecialchars($result['feedback'])) ?></p>
                </div>
              <?php else: ?>
                <div class="pending-notice">
                  <span class="pending-icon">⏳</span>
                  <span>Latihan ini sedang dalam proses penilaian oleh <?= htmlspecialchars($result['mentor_name'] ?? 'Mentor') ?>. Hasil akan muncul setelah mentor selesai menilai.</span>
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </main>

  <script>
    const filterTabs = document.querySelectorAll(".filter-tab");
    const resultCards = document.querySelectorAll(".result-card");

    filterTabs.forEach(tab => {
      tab.addEventListener("click", () => {
        filterTabs.forEach(t => t.classList.remove("active"));
        tab.classList.add("active");

        const filter = tab.dataset.filter;
        resultCards.forEach(card => {
          if (filter === "all" || card.dataset.feature === filter) {
            card.style.display = "";
          } else {
            card.style.display = "none";
          }
        });
      });
    });
  </script>
</body>

</html>
