<?php
require_once '../core.php';

$app = new manz();
$mentor = $app->getCurrentMentor();

if (!$mentor) {
    header('Location: loginad.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'save_review') {
    $submissionId = (int) ($_POST['submission_id'] ?? 0);
    $strengths = trim($_POST['strengths'] ?? '');
    $improvements = trim($_POST['improvements'] ?? '');
    $feedback = trim($_POST['feedback'] ?? '');

    if ($submissionId <= 0 || $strengths === '' || $improvements === '' || $feedback === '') {
        $error = 'Isi rubric dan catatan penilaian sebelum menyimpan.';
    } else {
        $saved = $app->saveMentorReview(
            $submissionId,
            $mentor['id'],
            [
                'articulation' => $_POST['articulation_score'] ?? 0,
                'fluency' => $_POST['fluency_score'] ?? 0,
                'confidence' => $_POST['confidence_score'] ?? 0,
                'structure' => $_POST['structure_score'] ?? 0,
                'intonation' => $_POST['intonation_score'] ?? 0
            ],
            $strengths,
            $improvements,
            $feedback
        );

        if ($saved) {
            header('Location: dashboard.php?submission=' . $submissionId . '&saved=1');
            exit;
        }

        $error = 'Penilaian belum tersimpan.';
    }
}

$stats = $app->getMentorDashboardStats($mentor['specialty'] ?? null);
$queue = $app->getMentorReviewQueue(12, $mentor['specialty'] ?? null);
$selectedSubmissionId = (int) ($_GET['submission'] ?? 0);
$selectedSubmission = $selectedSubmissionId > 0 ? $app->getMentorSubmissionById($selectedSubmissionId) : false;
$reviewSaved = isset($_GET['saved']);

function mentorStatusLabel($status) {
    if ($status === 'reviewed') return 'Sudah dinilai';
    if ($status === 'revision_requested') return 'Revisi';
    return 'Menunggu';
}

function mentorScoreValue($submission, $key, $fallback = 75) {
    return isset($submission[$key]) && $submission[$key] !== null ? (int) $submission[$key] : $fallback;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TALKLAB - Dashboard Mentor</title>
    <?php include 'inc/layout_css.php'; ?>
    <style>
        /* ===== HERO BANNER ===== */
        .dashboard-hero {
            background: linear-gradient(135deg, #10204f 0%, #1c3a6e 60%, #2a4e8e 100%);
            border-radius: 20px;
            padding: 40px 38px;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            overflow: hidden;
            margin-bottom: 32px;
            box-shadow: 0 12px 36px rgba(16, 32, 79, 0.18);
        }

        .dashboard-hero .ornamen-circle-1 {
            position: absolute;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: rgba(210, 160, 107, 0.12);
            left: -90px;
            bottom: -130px;
        }

        .dashboard-hero .ornamen-circle-2 {
            position: absolute;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            right: -60px;
            top: -100px;
        }

        .hero-text {
            position: relative;
            z-index: 2;
            max-width: 540px;
        }

        .hero-text h1 {
            font-size: 32px;
            font-weight: 800;
            margin: 0 0 10px;
            color: #fff;
            line-height: 1.25;
        }

        .hero-text p {
            font-size: 17px;
            color: #d2a06b;
            margin: 0 0 22px;
            line-height: 1.5;
        }

        .hero-stats {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
        }

        .hero-stat-box {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(6px);
            border-radius: 14px;
            padding: 18px 22px;
            min-width: 120px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .hero-stat-box .stat-number {
            display: block;
            font-size: 32px;
            font-weight: 800;
            color: #fff;
            line-height: 1;
        }

        .hero-stat-box .stat-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .hero-date {
            position: relative;
            z-index: 2;
            align-self: flex-start;
        }

        .hero-date .date-pill {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            border-radius: 999px;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 700;
            white-space: nowrap;
        }

        /* ===== QUICK ACCESS ===== */
        .section-title {
            font-size: 26px;
            font-weight: 800;
            color: #10204f;
            margin: 0 0 20px;
        }

        .quick-access-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 36px;
        }

        .quick-card {
            background: #fff;
            border-radius: 18px;
            padding: 30px 24px;
            text-align: center;
            box-shadow: 0 6px 24px rgba(16, 32, 79, 0.07);
            border: 1px solid #e4e7ec;
            text-decoration: none;
            color: inherit;
            transition: all 0.28s ease;
            position: relative;
            overflow: hidden;
        }

        .quick-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            border-radius: 18px 18px 0 0;
            transition: height 0.28s ease;
        }

        .quick-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 14px 36px rgba(16, 32, 79, 0.13);
        }

        .quick-card:hover::before {
            height: 6px;
        }

        .quick-card.card-penilaian::before { background: linear-gradient(135deg, #d2a06b, #e8c49a); }
        .quick-card.card-ebook::before { background: linear-gradient(135deg, #175cd3, #4a8aec); }
        .quick-card.card-materi::before { background: linear-gradient(135deg, #067647, #34c77b); }

        .quick-card-icon {
            width: 68px;
            height: 68px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .quick-card.card-penilaian .quick-card-icon { background: linear-gradient(135deg, #fdf0e0, #fce4c3); }
        .quick-card.card-ebook .quick-card-icon { background: linear-gradient(135deg, #e0edff, #c5d9f7); }
        .quick-card.card-materi .quick-card-icon { background: linear-gradient(135deg, #dcfae6, #b8f0cc); }

        .quick-card-icon svg {
            width: 30px;
            height: 30px;
        }

        .quick-card.card-penilaian .quick-card-icon svg { color: #9a5600; }
        .quick-card.card-ebook .quick-card-icon svg { color: #175cd3; }
        .quick-card.card-materi .quick-card-icon svg { color: #067647; }

        .quick-card h3 {
            font-size: 18px;
            font-weight: 700;
            color: #10204f;
            margin: 0 0 6px;
        }

        .quick-card p {
            font-size: 14px;
            color: #667085;
            margin: 0;
            line-height: 1.45;
        }

        /* ===== RECENT QUEUE ===== */
        .dashboard-panels {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
        }

        .dash-panel {
            background: #fff;
            border: 1px solid #e4e7ec;
            border-radius: 16px;
            box-shadow: 0 6px 24px rgba(16, 32, 79, 0.06);
            padding: 26px;
        }

        .dash-panel h2 {
            font-size: 22px;
            font-weight: 800;
            color: #10204f;
            margin: 0 0 6px;
        }

        .dash-panel .muted {
            color: #667085;
            font-size: 14px;
            margin: 0 0 18px;
        }

        .queue-mini {
            display: grid;
            gap: 10px;
        }

        .queue-mini-item {
            display: flex;
            align-items: center;
            gap: 14px;
            background: #f8fafc;
            border: 1px solid #e4e7ec;
            border-radius: 12px;
            padding: 14px;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s;
        }

        .queue-mini-item:hover {
            border-color: #d2a06b;
            background: #fffaf3;
        }

        .queue-mini-item .q-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #10204f;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            flex-shrink: 0;
        }

        .queue-mini-item .q-info {
            flex: 1;
            min-width: 0;
        }

        .queue-mini-item .q-name {
            font-weight: 700;
            color: #10204f;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .queue-mini-item .q-detail {
            font-size: 12px;
            color: #667085;
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .queue-mini-item .q-status {
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .q-status.pending { background: #fff1df; color: #9a5600; }
        .q-status.reviewed { background: #dcfae6; color: #067647; }

        .empty-mini {
            border: 1px dashed #c7cfdb;
            border-radius: 12px;
            color: #667085;
            padding: 28px 16px;
            text-align: center;
            font-size: 14px;
        }

        .view-all-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 14px;
            color: #d2a06b;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            transition: color 0.2s;
        }

        .view-all-link:hover { color: #b8864f; }

        /* ===== INFO PANEL ===== */
        .info-card {
            background: linear-gradient(135deg, #f8fafc, #f0f3f8);
            border: 1px solid #e4e7ec;
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 14px;
        }

        .info-card h3 {
            font-size: 16px;
            font-weight: 700;
            color: #10204f;
            margin: 0 0 8px;
        }

        .info-card p {
            font-size: 14px;
            color: #667085;
            margin: 0;
            line-height: 1.55;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1100px) {
            main { margin-left: 0; padding: 112px 20px 36px; }
            .quick-access-grid { grid-template-columns: 1fr; }
            .dashboard-panels { grid-template-columns: 1fr; }
            .hero-stats { flex-direction: column; }
        }

        @media (max-width: 640px) {
            .dashboard-hero { flex-direction: column; gap: 20px; padding: 28px 22px; }
            .hero-text h1 { font-size: 24px; }
            .quick-access-grid { gap: 14px; }
        }
    </style>
</head>
<body>
    <?php include 'inc/header.php'; ?>
    <?php include 'inc/sidebar.php'; ?>

    <main>
        <!-- Hero Banner -->
        <section class="dashboard-hero">
            <div class="ornamen-circle-1"></div>
            <div class="ornamen-circle-2"></div>

            <div class="hero-text">
                <h1>Selamat Datang, <?= htmlspecialchars($mentor['name']) ?>!</h1>
                <p>Kelola penilaian, materi, dan e-book untuk peserta TalkLab dari sini.</p>

                <div class="hero-stats">
                    <div class="hero-stat-box">
                        <span class="stat-number"><?= (int) $stats['pending'] ?></span>
                        <span class="stat-label">Menunggu Nilai</span>
                    </div>
                    <div class="hero-stat-box">
                        <span class="stat-number"><?= (int) $stats['reviewed'] ?></span>
                        <span class="stat-label">Sudah Dinilai</span>
                    </div>
                    <div class="hero-stat-box">
                        <span class="stat-number"><?= (int) $stats['students'] ?></span>
                        <span class="stat-label">Peserta Aktif</span>
                    </div>
                    <div class="hero-stat-box">
                        <span class="stat-number"><?= (int) $stats['average_score'] ?></span>
                        <span class="stat-label">Rata-rata Nilai</span>
                    </div>
                </div>
            </div>

            <div class="hero-date">
                <span class="date-pill"><?= htmlspecialchars(date('d M Y')) ?></span>
            </div>
        </section>

        <!-- Quick Access -->
        <h2 class="section-title">Akses Cepat</h2>
        <section class="quick-access-grid">
            <a href="penilaian.php" class="quick-card card-penilaian">
                <div class="quick-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 11l3 3L22 4"/>
                        <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                    </svg>
                </div>
                <h3>Penilaian</h3>
                <p>Review dan nilai latihan peserta</p>
            </a>

            <a href="ebook.php" class="quick-card card-ebook">
                <div class="quick-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 7v14"/>
                        <path d="M3 18a1 1 0 01-1-1V4a1 1 0 011-1h5a4 4 0 014 4 4 4 0 014-4h5a1 1 0 011 1v13a1 1 0 01-1 1h-6a3 3 0 00-3 3 3 3 0 00-3-3H3z"/>
                    </svg>
                </div>
                <h3>Tambah E-Book</h3>
                <p>Upload e-book baru untuk peserta</p>
            </a>

            <a href="materi.php" class="quick-card card-materi">
                <div class="quick-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="12" y1="18" x2="12" y2="12"/>
                        <line x1="9" y1="15" x2="15" y2="15"/>
                    </svg>
                </div>
                <h3>Tambah Materi</h3>
                <p>Kelola materi dan video pembelajaran</p>
            </a>
        </section>

        <!-- Panels Row -->
        <h2 class="section-title">Ringkasan Terbaru</h2>
        <section class="dashboard-panels">
            <!-- Recent Submissions -->
            <div class="dash-panel">
                <h2>Latihan Masuk</h2>
                <p class="muted"><?= (int) $stats['practice_audio'] ?> rekaman tersimpan</p>

                <div class="queue-mini">
                    <?php if (empty($queue)): ?>
                        <div class="empty-mini">Belum ada latihan yang dikirim ke mentor.</div>
                    <?php else: ?>
                        <?php foreach (array_slice($queue, 0, 5) as $item): ?>
                            <a class="queue-mini-item" href="penilaian.php?submission=<?= (int) $item['id'] ?>">
                                <div class="q-avatar"><?= strtoupper(substr($item['student_name'], 0, 1)) ?></div>
                                <div class="q-info">
                                    <div class="q-name"><?= htmlspecialchars($item['student_name']) ?></div>
                                    <div class="q-detail"><?= htmlspecialchars($item['topic']) ?> · <?= htmlspecialchars(date('d M H:i', strtotime($item['submitted_at']))) ?></div>
                                </div>
                                <span class="q-status <?= $item['status'] === 'reviewed' ? 'reviewed' : 'pending' ?>">
                                    <?= htmlspecialchars(mentorStatusLabel($item['status'])) ?>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <?php if (!empty($queue)): ?>
                    <a href="penilaian.php" class="view-all-link">
                        Lihat semua penilaian →
                    </a>
                <?php endif; ?>
            </div>

            <!-- Info & Tips -->
            <div class="dash-panel">
                <h2>Informasi Mentor</h2>
                <p class="muted">Tips dan panduan untuk penilaian</p>

                <div class="info-card">
                    <h3>📋 Rubrik Penilaian</h3>
                    <p>Setiap latihan dinilai berdasarkan 5 aspek: <strong>Artikulasi, Kelancaran, Percaya Diri, Struktur,</strong> dan <strong>Intonasi.</strong> Berikan skor 0–100 untuk setiap aspek.</p>
                </div>

                <div class="info-card">
                    <h3>💡 Tips Feedback</h3>
                    <p>Berikan feedback yang spesifik dan membangun. Sebutkan kelebihan peserta terlebih dahulu, lalu area yang perlu diperbaiki dengan contoh konkret.</p>
                </div>

                <div class="info-card">
                    <h3>📚 Konten Pembelajaran</h3>
                    <p>Anda dapat menambah materi video dan e-book kapan saja melalui menu <strong>Materi</strong> dan <strong>E-Book</strong> di sidebar.</p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
