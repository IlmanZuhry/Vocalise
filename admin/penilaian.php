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
            header('Location: penilaian.php?submission=' . $submissionId . '&saved=1');
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

function penilaianStatusLabel($status) {
    if ($status === 'reviewed') return 'Sudah dinilai';
    if ($status === 'revision_requested') return 'Revisi';
    return 'Menunggu';
}

function penilaianScoreValue($submission, $key, $fallback = 75) {
    return isset($submission[$key]) && $submission[$key] !== null ? (int) $submission[$key] : $fallback;
}

function penilaianFeatureLabel($type) {
    $labels = ['voice' => '🎙 Suara', 'challenge' => '⏱ Tantangan', 'camera' => '📹 Kamera'];
    return $labels[$type] ?? $type;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TALKLAB - Penilaian Mentor</title>
    <?php include 'inc/layout_css.php'; ?>
    <style>
        main {
            flex: 1;
            margin-left: 260px;
            padding: 120px 36px 48px;
            color: #101828;
            background: #f4f6fa;
        }

        .topbar {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 28px;
        }

        .topbar h1 {
            font-size: 36px;
            color: #10204f;
            margin: 0 0 8px;
            background: linear-gradient(135deg, #10204f 0%, #d2a06b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .topbar p { color: #667085; margin: 0; font-size: 16px; }

        .date-pill {
            border-radius: 999px;
            display: inline-flex;
            padding: 8px 14px;
            white-space: nowrap;
            background: #fff;
            border: 1px solid #d8dee9;
            color: #344054;
            font-weight: 700;
            font-size: 14px;
        }

        .stats {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin-bottom: 28px;
        }

        .stat {
            background: #fff;
            border: 1px solid #e4e7ec;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgb(16 32 79 / 0.06);
            padding: 22px;
            position: relative;
            overflow: hidden;
        }

        .stat::after {
            content: "";
            position: absolute;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            right: -30px;
            top: -30px;
            background: rgb(210 160 107 / 0.1);
        }

        .stat span {
            color: #667085;
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 14px;
        }

        .stat strong {
            color: #10204f;
            font-size: 36px;
            position: relative;
            z-index: 1;
        }

        .layout {
            display: grid;
            gap: 20px;
            grid-template-columns: minmax(360px, 1.08fr) minmax(380px, 0.92fr);
            align-items: start;
        }

        .panel {
            background: #fff;
            border: 1px solid #e4e7ec;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgb(16 32 79 / 0.06);
            padding: 24px;
        }

        .panel-head {
            display: flex;
            align-items: center;
            gap: 14px;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .panel h2 {
            color: #10204f;
            font-size: 22px;
            margin: 0 0 6px;
        }

        .panel .muted { color: #667085; font-size: 14px; margin: 0; }

        .queue { display: grid; gap: 12px; }

        .queue-item {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 12px;
            align-items: start;
            background: #f8fafc;
            border: 1px solid #e4e7ec;
            border-radius: 12px;
            padding: 16px;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s;
        }

        .queue-item:hover,
        .queue-item.selected {
            border-color: #d2a06b;
            background: #fff8ef;
        }

        .queue-item strong { display: block; color: #10204f; margin-bottom: 4px; font-size: 15px; }
        .queue-item small { display: block; color: #667085; font-size: 13px; }
        .queue-item p { color: #667085; margin: 8px 0 0; line-height: 1.45; font-size: 14px; }

        .queue-feature {
            display: inline-block;
            font-size: 11px;
            font-weight: 800;
            background: #eff8ff;
            color: #175cd3;
            border-radius: 999px;
            padding: 3px 8px;
            margin-top: 6px;
        }

        .status {
            border-radius: 999px;
            display: inline-flex;
            padding: 6px 12px;
            white-space: nowrap;
            background: #fff1df;
            color: #9a5600;
            font-size: 13px;
            font-weight: 700;
        }

        .status.reviewed { background: #dcfae6; color: #067647; }

        .empty {
            border: 1px dashed #c7cfdb;
            border-radius: 12px;
            color: #667085;
            padding: 34px 20px;
            text-align: center;
            font-size: 15px;
        }

        .notice {
            border-radius: 12px;
            margin-bottom: 16px;
            padding: 14px 16px;
            font-weight: 700;
            font-size: 14px;
        }

        .notice.success { background: #dcfae6; color: #067647; }
        .notice.error { background: #fee4e2; color: #b42318; }

        .submission-meta {
            display: grid;
            gap: 10px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            margin-bottom: 18px;
        }

        .submission-meta div {
            background: #f8fafc;
            border: 1px solid #e4e7ec;
            border-radius: 10px;
            padding: 14px;
        }

        .submission-meta span {
            color: #667085;
            display: block;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .submission-meta strong { display: block; color: #10204f; font-size: 15px; }

        audio { margin-bottom: 18px; width: 100%; border-radius: 8px; }

        .score-grid {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        label {
            color: #667085;
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        input[type="number"],
        textarea {
            background: #fff;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            color: #142033;
            outline: none;
            padding: 12px;
            width: 100%;
            font: inherit;
        }

        input[type="number"]:focus,
        textarea:focus {
            border-color: #d2a06b;
            box-shadow: 0 0 0 3px rgb(210 160 107 / 0.18);
        }

        textarea { min-height: 86px; resize: vertical; }

        .field { margin-top: 14px; }

        .save {
            background: linear-gradient(135deg, #10204f 0%, #1c3a6e 100%);
            border: 0;
            border-radius: 12px;
            color: #fff;
            cursor: pointer;
            font-weight: 800;
            font-size: 15px;
            margin-top: 18px;
            padding: 15px 18px;
            width: 100%;
            transition: opacity 0.2s;
        }

        .save:hover { opacity: 0.9; }

        @media (max-width: 1100px) {
            main { margin-left: 0; padding: 112px 20px 36px; }
            .layout { grid-template-columns: 1fr; }
            .stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 640px) {
            .topbar { flex-direction: column; align-items: flex-start; }
            .stats, .score-grid, .submission-meta { grid-template-columns: 1fr; }
            .queue-item { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <?php include 'inc/header.php'; ?>
    <?php include 'inc/sidebar.php'; ?>

    <main>
        <header class="topbar">
            <div>
                <h1>Penilaian Latihan</h1>
                <p>Review dan nilai latihan public speaking dari peserta yang masuk ke antrean Anda.</p>
            </div>
            <span class="date-pill"><?= htmlspecialchars(date('d M Y')) ?></span>
        </header>

        <section class="stats" aria-label="Statistik penilaian">
            <div class="stat">
                <span>Menunggu Nilai</span>
                <strong><?= (int) $stats['pending'] ?></strong>
            </div>
            <div class="stat">
                <span>Sudah Dinilai</span>
                <strong><?= (int) $stats['reviewed'] ?></strong>
            </div>
            <div class="stat">
                <span>Peserta Aktif</span>
                <strong><?= (int) $stats['students'] ?></strong>
            </div>
            <div class="stat">
                <span>Rata-rata Nilai</span>
                <strong><?= (int) $stats['average_score'] ?></strong>
            </div>
        </section>

        <section class="layout">
            <!-- Antrean Latihan Masuk -->
            <article class="panel" id="review-queue">
                <div class="panel-head">
                    <div>
                        <h2>Latihan Masuk</h2>
                        <p class="muted"><?= count($queue) ?> latihan di antrean penilaian</p>
                    </div>
                </div>

                <div class="queue">
                    <?php if (empty($queue)): ?>
                        <div class="empty">Belum ada latihan yang dikirim ke mentor.<br>Submission peserta akan muncul di sini.</div>
                    <?php else: ?>
                        <?php foreach ($queue as $item): ?>
                            <a class="queue-item <?= $selectedSubmissionId === (int) $item['id'] ? 'selected' : '' ?>" href="penilaian.php?submission=<?= (int) $item['id'] ?>">
                                <div>
                                    <strong><?= htmlspecialchars($item['student_name']) ?> @<?= htmlspecialchars($item['student_username']) ?></strong>
                                    <small><?= htmlspecialchars(date('d M Y H:i', strtotime($item['submitted_at']))) ?> · <?= (int) $item['duration_seconds'] ?> detik</small>
                                    <p><?= htmlspecialchars($item['topic'] ?? 'Latihan') ?></p>
                                    <span class="queue-feature"><?= penilaianFeatureLabel($item['feature_type'] ?? 'voice') ?></span>
                                </div>
                                <span class="status <?= $item['status'] === 'reviewed' ? 'reviewed' : '' ?>">
                                    <?= htmlspecialchars(penilaianStatusLabel($item['status'])) ?>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </article>

            <!-- Panel Penilaian -->
            <article class="panel">
                <?php if ($reviewSaved): ?>
                    <div class="notice success">✅ Penilaian mentor berhasil tersimpan dan telah dikirim ke peserta.</div>
                <?php endif; ?>

                <?php if ($error !== ''): ?>
                    <div class="notice error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <?php if (!$selectedSubmission): ?>
                    <div>
                        <h2>Panel Penilaian</h2>
                        <p class="muted">Pilih latihan di panel kiri untuk mendengarkan rekaman dan mengisi nilai.</p>
                        <div class="empty">
                            Rubric mentor siap untuk:<br>
                            <strong>Artikulasi · Kelancaran · Percaya Diri · Struktur · Intonasi</strong>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="panel-head">
                        <div>
                            <h2>Nilai Latihan</h2>
                            <p class="muted"><?= htmlspecialchars($selectedSubmission['topic'] ?? 'Latihan') ?></p>
                        </div>
                        <span class="status <?= $selectedSubmission['status'] === 'reviewed' ? 'reviewed' : '' ?>">
                            <?= htmlspecialchars(penilaianStatusLabel($selectedSubmission['status'])) ?>
                        </span>
                    </div>

                    <div class="submission-meta">
                        <div>
                            <span>Peserta</span>
                            <strong><?= htmlspecialchars($selectedSubmission['student_name']) ?></strong>
                        </div>
                        <div>
                            <span>Username</span>
                            <strong>@<?= htmlspecialchars($selectedSubmission['student_username']) ?></strong>
                        </div>
                        <div>
                            <span>Tipe Latihan</span>
                            <strong><?= penilaianFeatureLabel($selectedSubmission['feature_type'] ?? 'voice') ?></strong>
                        </div>
                        <div>
                            <span>Durasi</span>
                            <strong><?= (int) $selectedSubmission['duration_seconds'] ?> detik</strong>
                        </div>
                        <div>
                            <span>Dikirim</span>
                            <strong><?= htmlspecialchars(date('d M Y H:i', strtotime($selectedSubmission['submitted_at']))) ?></strong>
                        </div>
                        <div>
                            <span>Latihan Dibuat</span>
                            <strong><?= htmlspecialchars(date('d M Y H:i', strtotime($selectedSubmission['practice_created_at'] ?? $selectedSubmission['submitted_at']))) ?></strong>
                        </div>
                    </div>

                    <?php if (!empty($selectedSubmission['video_path'])): ?>
                        <video controls style="width:100%; border-radius:8px; margin-bottom:18px;" src="../<?= htmlspecialchars($selectedSubmission['video_path']) ?>"></video>
                    <?php endif; ?>

                    <?php if (!empty($selectedSubmission['audio_path'])): ?>
                        <audio controls src="../<?= htmlspecialchars($selectedSubmission['audio_path']) ?>"></audio>
                    <?php endif; ?>

                    <form method="POST" action="penilaian.php?submission=<?= (int) $selectedSubmission['id'] ?>">
                        <input type="hidden" name="action" value="save_review">
                        <input type="hidden" name="submission_id" value="<?= (int) $selectedSubmission['id'] ?>">

                        <div class="score-grid">
                            <div>
                                <label for="articulation_score">Artikulasi</label>
                                <input id="articulation_score" type="number" name="articulation_score" min="0" max="100" value="<?= penilaianScoreValue($selectedSubmission, 'articulation_score') ?>" required>
                            </div>
                            <div>
                                <label for="fluency_score">Kelancaran</label>
                                <input id="fluency_score" type="number" name="fluency_score" min="0" max="100" value="<?= penilaianScoreValue($selectedSubmission, 'fluency_score') ?>" required>
                            </div>
                            <div>
                                <label for="confidence_score">Percaya Diri</label>
                                <input id="confidence_score" type="number" name="confidence_score" min="0" max="100" value="<?= penilaianScoreValue($selectedSubmission, 'confidence_score') ?>" required>
                            </div>
                            <div>
                                <label for="structure_score">Struktur</label>
                                <input id="structure_score" type="number" name="structure_score" min="0" max="100" value="<?= penilaianScoreValue($selectedSubmission, 'structure_score') ?>" required>
                            </div>
                            <div>
                                <label for="intonation_score">Intonasi</label>
                                <input id="intonation_score" type="number" name="intonation_score" min="0" max="100" value="<?= penilaianScoreValue($selectedSubmission, 'intonation_score') ?>" required>
                            </div>
                            <div>
                                <label>Nilai Akhir (otomatis)</label>
                                <input type="number" value="<?= penilaianScoreValue($selectedSubmission, 'final_score') ?>" disabled>
                            </div>
                        </div>

                        <div class="field">
                            <label for="strengths">✅ Kelebihan Peserta</label>
                            <textarea id="strengths" name="strengths" placeholder="Tuliskan kelebihan peserta..." required><?= htmlspecialchars($selectedSubmission['strengths'] ?? '') ?></textarea>
                        </div>

                        <div class="field">
                            <label for="improvements">📝 Perlu Diperbaiki</label>
                            <textarea id="improvements" name="improvements" placeholder="Tuliskan area yang perlu diperbaiki..." required><?= htmlspecialchars($selectedSubmission['improvements'] ?? '') ?></textarea>
                        </div>

                        <div class="field">
                            <label for="feedback">💬 Feedback & Catatan Mentor</label>
                            <textarea id="feedback" name="feedback" placeholder="Tuliskan feedback dan motivasi untuk peserta..." required><?= htmlspecialchars($selectedSubmission['feedback'] ?? '') ?></textarea>
                        </div>

                        <button class="save" type="submit">💾 Simpan Penilaian</button>
                    </form>
                <?php endif; ?>
            </article>
        </section>
    </main>
</body>
</html>
