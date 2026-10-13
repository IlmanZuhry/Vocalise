<?php
require_once 'core.php';
if (session_status() == PHP_SESSION_NONE) session_start();
$app = new manz();
$currentUser = $app->getCurrentUser();
$learningProgress = [
    'percent' => 0,
    'completed_videos' => 0,
    'total_videos' => 0,
    'completed_materials' => 0,
    'total_materials' => 0
];

// Function to get time ago in Indonesian
function getTimeAgoInIndonesian($dateString) {
    $date = new DateTime($dateString);
    $now = new DateTime();
    $interval = $now->diff($date);
    
    if ($interval->days == 0) {
        if ($interval->h == 0) {
            if ($interval->i == 0) return 'Baru saja';
            return $interval->i . ' menit lalu';
        }
        return $interval->h . ' jam lalu';
    } elseif ($interval->days < 7) {
        return $interval->days . ' hari lalu';
    } else {
        return $date->format('d M Y');
    }
}

// Get recent activities
$recentActivities = [];

if ($currentUser) {
        $learningProgress = $app->getOverallMaterialProgress($currentUser['Id_User']);

        // Get latest practice sessions
        $practiceHistory = $app->getPracticeHistory($currentUser['Id_User'], 5);
        foreach ($practiceHistory as $practice) {
            $recentActivities[] = [
                'type' => 'practice',
                'text' => 'Kamu melakukan latihan: "' . substr($practice['topic'], 0, 40) . '..."',
                'time' => $practice['created_at']
            ];
        }

        // Get latest challenge history
        $challengeHistory = $app->getChallengeHistory($currentUser['Id_User'], 5);
        foreach ($challengeHistory as $challenge) {
            $recentActivities[] = [
                'type' => 'challenge',
                'text' => 'Kamu menyelesaikan ' . $challenge['challenge_type'] . ' dengan skor ' . $challenge['score'],
                'time' => $challenge['created_at']
            ];
        }

        // Get latest material progress
        $materialHistory = $app->getMaterialActivityHistory($currentUser['Id_User'], 5);
        foreach ($materialHistory as $item) {
            $recentActivities[] = [
                'type' => 'material',
                'text' => 'Kamu menyelesaikan materi: "' . substr($item['material_title'], 0, 40) . ' - ' . substr($item['video_title'], 0, 40) . '..."',
                'time' => $item['created_at']
            ];
        }

        // Get latest ebook reads
        $ebookHistory = $app->getEbookActivityHistory($currentUser['Id_User'], 5);
        foreach ($ebookHistory as $ebook) {
            $recentActivities[] = [
                'type' => 'ebook',
                'text' => 'Kamu membaca e-book: "' . substr($ebook['ebook_title'], 0, 40) . '..."',
                'time' => $ebook['created_at']
            ];
        }

        // Get latest community posts by the user
        $userPosts = $app->getCommunityPostHistory($currentUser['Id_User'], 5);
        foreach ($userPosts as $post) {
            $recentActivities[] = [
                'type' => 'community_post',
                'text' => 'Kamu membuat posting komunitas: "' . substr($post['Isi'], 0, 40) . '..."',
                'time' => $post['Dibuat']
            ];
        }

        // Get latest comments by the user
        $userComments = $app->getUserCommentHistory($currentUser['Id_User'], 5);
        foreach ($userComments as $comment) {
            $recentActivities[] = [
                'type' => 'community_comment',
                'text' => 'Kamu mengomentari: "' . substr($comment['content'], 0, 40) . '..."',
                'time' => $comment['created_at']
            ];
        }

        // Get latest comment replies on user's posts
        $userPostComments = $app->getLatestCommentReplies($currentUser['Id_User'], 5);
        foreach ($userPostComments as $comment) {
            $recentActivities[] = [
                'type' => 'comment_reply',
            'text' => 'Kamu mendapat balasan komentar: "' . substr($comment['content'], 0, 40) . '..."',
            'time' => $comment['created_at']
        ];
    }
}

// Sort activities by time (newest first)
usort($recentActivities, function($a, $b) {
    return strtotime($b['time']) - strtotime($a['time']);
});

// Limit to 5 latest activities
$recentActivities = array_slice($recentActivities, 0, 5);

$progressPercent = (int) ($learningProgress['percent'] ?? 0);
$progressPercent = max(0, min($progressPercent, 100));
$completedVideos = (int) ($learningProgress['completed_videos'] ?? 0);
$totalVideos = (int) ($learningProgress['total_videos'] ?? 0);
$completedMaterials = (int) ($learningProgress['completed_materials'] ?? 0);
$totalMaterials = (int) ($learningProgress['total_materials'] ?? 0);

if (!$currentUser) {
    $progressMessage = 'Login untuk menyimpan progres belajarmu';
} elseif ($totalVideos === 0) {
    $progressMessage = 'Belum ada video materi yang tersedia';
} elseif ($completedVideos === 0) {
    $progressMessage = 'Kamu belum menyelesaikan materi apapun';
} elseif ($progressPercent >= 100) {
    $progressMessage = 'Hebat, semua materi sudah kamu selesaikan';
} else {
    $progressMessage = $completedVideos . ' dari ' . $totalVideos . ' video selesai';
    if ($totalMaterials > 0) {
        $progressMessage .= ' (' . $completedMaterials . ' dari ' . $totalMaterials . ' materi lengkap)';
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vocalise - Beranda</title>
    <link rel="stylesheet" href="style.css" />
    <?php include 'includes/layout_css.php'; ?>
</head>

<body>

    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>

    <main>
        <section class="hero">
            <div class="ornamen-left"></div>
            <div class="ornamen-right"></div>
            <div class="hero-content">
                <h1 class="mari">Mari Bicara Dengan Percaya Diri !</h1>
                <p class="karena">Karena percaya diri itu bisa dilatih, bukan di tunggu.</p>

                <div class="progress-box">
                    <span class="label">Progres Belajar</span>
                    <span class="percent"><?= $progressPercent ?>%</span>
                    <div class="progress-bar">
                        <div class="fill" style="width: <?= $progressPercent ?>%;"></div>
                    </div>
                    <p><?= htmlspecialchars($progressMessage) ?></p>
                </div>

                <button class="btn-primary" onclick="window.location.href='Materi.php'">Mulai Belajar</button>
            </div>

            <div class="hero-img">
                <div class="image-wrapper">
                    <img src="assets/jjjj.png" alt="character">
                </div>
            </div>

        </section>

        <h2 style="font-size: 35px;">Akses Cepat</h2>

        <div class="quick-cards">

            <a href="Materi.php" class="quick-card" style="text-decoration:none;">
                <div class="icon-box materi-icon">
                    <img src="icon/bukuuu.svg" alt="">
                </div>
                <h3>Materi</h3>
                <p>Klik untuk memulai</p>
            </a>

            <a href="Latihan.php" class="quick-card" style="text-decoration:none;">
                <div class="icon-box latihan-icon">
                    <img src="icon/ngomong.svg" alt="">
                </div>
                <h3>Latihan</h3>
                <p>Klik untuk memulai</p>
            </a>

            <a href="Ebook.php" class="quick-card" style="text-decoration:none;">
                <div class="icon-box ebook-icon">
                    <img src="icon/buk.svg" alt="">
                </div>
                <h3>E-Book</h3>
                <p>Klik untuk memulai</p>
            </a>

            <a href="Komunitas.php" class="quick-card" style="text-decoration:none;">
                <div class="icon-box komunitas-icon">
                    <img src="icon/dua.svg" alt="">
                </div>
                <h3>Komunitas</h3>
                <p>Klik untuk memulai</p>
            </a>

        </div>


        <h2 style="font-size: 33px;">Aktifitas Terbaru</h2>
        <div class="activity-list">
            <?php 
            if (!empty($recentActivities)):
                foreach ($recentActivities as $activity):
                    $timeAgo = getTimeAgoInIndonesian($activity['time']);
            ?>
                    <div class="activity-item">
                        <?php if ($activity['type'] === 'practice'): ?>
                            <div class="icon-box latihan-icon"><img src="icon/ngomong.svg" alt=""></div>
                        <?php elseif ($activity['type'] === 'challenge'): ?>
                            <div class="icon-box yellow"><img src="icon/cup.svg" alt=""></div>
                        <?php elseif ($activity['type'] === 'material'): ?>
                            <div class="icon-box materi-icon"><img src="icon/bukuuu.svg" alt=""></div>
                        <?php elseif ($activity['type'] === 'ebook'): ?>
                            <div class="icon-box ebook-icon"><img src="icon/buk.svg" alt=""></div>
                        <?php else: ?>
                            <div class="icon-box komunitas-icon"><img src="icon/dua.svg" alt=""></div>
                        <?php endif; ?>
                        <div class="text">
                            <p><?php echo htmlspecialchars($activity['text']); ?></p>
                            <small><?php echo $timeAgo; ?></small>
                        </div>
                    </div>
            <?php 
                endforeach;
            else:
            ?>
                    <div class="activity-item">
                        <img class="activity-icon" src="icon/panah.svg" alt="">
                        <div class="text">
                            <p>Mulai belajar untuk melihat aktivitas terbaru Anda</p>
                            <small>Belum ada aktivitas</small>
                        </div>
                    </div>
            <?php 
            endif;
            ?>
        </div>

    </main>

    <script>
    function getTimeAgoInIndonesian(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);
        
        if (diffMins < 1) return 'Baru saja';
        if (diffMins < 60) return diffMins + ' menit lalu';
        if (diffHours < 24) return diffHours + ' jam lalu';
        if (diffDays < 7) return diffDays + ' hari lalu';
        
        return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' });
    }
    </script>

</body>

</html>
