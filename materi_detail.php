<?php
require_once 'core.php';
$app = new manz();
$app->ensureSession();
$currentUser = $app->getCurrentUser();

$materialId = $_GET['id'] ?? '';
$material = $app->getMaterialById($materialId);

if (!$material) {
    header("Location: Materi.php");
    exit;
}

$videos = $app->getMaterialVideos($materialId);
$completedVideoIds = $currentUser ? $app->getCompletedVideoIds($currentUser['Id_User'], $materialId) : [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TALKLAB - <?= htmlspecialchars($material['title']) ?></title>
    <?php include 'includes/layout_css.php'; ?>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: system-ui, sans-serif; }
        body { display: flex; background: #f5f5f5; }
        .back-link { display: inline-flex; align-items: center; gap: 8px; color: #1c407a; text-decoration: none; font-weight: 600; margin-bottom: 16px; font-size: 16px; transition: color 0.2s; }
        .back-link:hover { color: #15305c; text-decoration: underline; }
        .course-header { font-size: 28px; font-weight: bold; color: #1c407a; margin-bottom: 24px; }
        .course-container { display: flex; gap: 32px; background: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); padding: 32px; min-height: 600px; }
        
        .course-sidebar { width: 320px; border-right: 1px solid #eee; padding-right: 24px; }
        .course-sidebar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; font-weight: 600; font-size: 18px; color: #333; }
        .course-menu-list { list-style: none; padding: 0; margin: 0; }
        .course-menu-item { padding: 14px 12px; display: flex; justify-content: space-between; align-items: center; color: #555; cursor: pointer; font-size: 15px; border-radius: 8px; transition: background-color 0.2s; margin-bottom: 4px; }
        .course-menu-item:hover { background-color: #f8f9fa; }
        .course-menu-item.active { background-color: #eef3fc; color: #1c407a; font-weight: 600; }
        .course-menu-icon { display: flex; align-items: center; gap: 12px; }
        .course-check-icon { color: #28a745; font-weight: bold; display: none; }
        .course-menu-item.completed .course-check-icon { display: block; }
        
        .course-content { flex: 1; display: flex; flex-direction: column; }
        .video-player-placeholder { width: 100%; aspect-ratio: 16 / 9; background: #f0f0f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; margin-bottom: 24px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); }
        .script-section { flex: 1; }
        .script-title { font-weight: 700; color: #333; margin-bottom: 12px; font-size: 16px; }
        .script-text { color: #555; line-height: 1.7; font-size: 15px; margin-bottom: 32px; white-space: pre-wrap;}
        
        .course-navigation { display: flex; justify-content: center; gap: 16px; margin-top: auto; padding-top: 20px; border-top: 1px solid #eee; }
        .nav-btn { padding: 10px 24px; border: 1px solid #ddd; background: #fff; border-radius: 20px; cursor: pointer; font-size: 14px; font-weight: 500; color: #555; transition: all 0.2s; min-width: 120px; }
        .nav-btn:hover { background: #f8f9fa; border-color: #ccc; }
        .nav-btn.next { background: #1c407a; color: white; border: 1px solid #1c407a; }
        .nav-btn.next:hover { background: #15305c; border-color: #15305c; }
        
        .empty-course { text-align: center; padding: 40px; color: #666; font-size: 18px; }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>
    
    <main style="flex:1;">
        <div style="margin-left: 260px; padding: 120px 40px 40px; width: calc(100% - 260px); display: flex; flex-direction: column; box-sizing: border-box;">
            <div>
                <a href="Materi.php" class="back-link">
                    <svg viewBox="0 0 24 24" width="20" height="20">
                        <path fill="currentColor" d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z" />
                    </svg>
                    Kembali ke Materi
                </a>
            </div>
            <h1 class="course-header">Overview of <?= htmlspecialchars($material['title']) ?></h1>

            <div class="course-container">
                <?php if (empty($videos)): ?>
                    <div class="empty-course" style="width: 100%;">
                        Belum ada video untuk materi ini.
                    </div>
                <?php else: ?>
                <!-- Sidebar Menu -->
                <div class="course-sidebar">
                    <div class="course-sidebar-header">
                        <span>Daftar Video</span>
                    </div>
                    <ul class="course-menu-list">
                        <?php foreach ($videos as $idx => $vid): ?>
                            <li class="course-menu-item <?= $idx === 0 ? 'active' : '' ?>" data-index="<?= $idx ?>">
                                <div class="course-menu-icon">
                                    <span class="icon-text">▶</span> <?= htmlspecialchars($vid['title']) ?>
                                </div>
                                <span class="course-check-icon">✓</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Main Content -->
                <div class="course-content">
                    <div class="video-player-placeholder" id="video-container">
                        <!-- Video iframe goes here -->
                    </div>

                    <div class="script-section">
                        <div class="script-title">Penjelasan:</div>
                        <div class="script-text" id="script-text"></div>
                    </div>

                    <div class="course-navigation">
                        <button class="nav-btn" id="prev-btn" disabled style="opacity: 0.5; cursor: not-allowed;">« Previous</button>
                        <button class="nav-btn next" id="next-btn" disabled style="opacity: 0.5; cursor: not-allowed;">Next »</button>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php if (!empty($videos)): ?>
    <script src="https://www.youtube.com/iframe_api"></script>
    <script>
        let ytPlayer = null;
        let isYtApiReady = false;
        const MATERIAL_ID = <?= json_encode($materialId) ?>;
        const videosData = <?= json_encode($videos) ?>;
        
        let currentIndex = 0;
        let completedVideoIds = <?= json_encode($completedVideoIds) ?>;

        const nextBtn = document.getElementById('next-btn');
        const prevBtn = document.getElementById('prev-btn');
        const scriptText = document.getElementById('script-text');
        const menuItems = document.querySelectorAll('.course-menu-item');
        const videoContainer = document.getElementById('video-container');

        function onYouTubeIframeAPIReady() {
            isYtApiReady = true;
            let iframe = document.getElementById('course-video');
            if (iframe && iframe.tagName.toLowerCase() === 'iframe') {
                initYouTubePlayer('course-video');
            }
        }

        function initYouTubePlayer(iframeId) {
            if (ytPlayer) {
                ytPlayer.destroy();
            }
            ytPlayer = new YT.Player(iframeId, {
                events: {
                    'onStateChange': onPlayerStateChange
                }
            });
        }

        function saveProgressToDB(index) {
            let vidId = parseInt(videosData[index].id);
            if (completedVideoIds.includes(vidId)) return; // Only save if not already completed
            
            const formData = new FormData();
            formData.append('action', 'save_progress');
            formData.append('material_id', MATERIAL_ID);
            formData.append('video_id', vidId);

            fetch('api_progress.php', {
                method: 'POST',
                body: formData
            }).then(res => res.json()).then(data => {
                if(data.status) {
                    completedVideoIds.push(vidId);
                }
            }).catch(err => console.error(err));
        }

        function onPlayerStateChange(event) {
            if (event.data === YT.PlayerState.ENDED) {
                menuItems[currentIndex].classList.add('completed');
                saveProgressToDB(currentIndex);
                
                nextBtn.disabled = false;
                nextBtn.style.opacity = '1';
                nextBtn.style.cursor = 'pointer';
            }
        }

        function attachVideoEvent(vid) {
            vid.addEventListener('ended', () => {
                menuItems[currentIndex].classList.add('completed');
                saveProgressToDB(currentIndex);
                
                nextBtn.disabled = false;
                nextBtn.style.opacity = '1';
                nextBtn.style.cursor = 'pointer';
            });
        }

        function loadMaterial(index) {
            if (index < 0 || index >= videosData.length) return;
            
            menuItems.forEach((item, i) => {
                if (i === index) item.classList.add('active');
                else item.classList.remove('active');
            });

            const mat = videosData[index];
            scriptText.innerText = mat.script;

            if (mat.video_url) {
                let isYouTube = mat.video_url.includes('youtu.be') || mat.video_url.includes('youtube.com');
                
                if (isYouTube) {
                    let embedUrl = mat.video_url;
                    if (embedUrl.includes('youtu.be/')) {
                        let parts = embedUrl.split('youtu.be/');
                        let videoId = parts[1].split('?')[0];
                        let params = parts[1].includes('?') ? '&' + parts[1].split('?')[1] : '';
                        embedUrl = `https://www.youtube.com/embed/${videoId}?enablejsapi=1${params}`;
                    } else if (embedUrl.includes('youtube.com/watch')) {
                        let videoId = embedUrl.split('v=')[1].split('&')[0];
                        embedUrl = `https://www.youtube.com/embed/${videoId}?enablejsapi=1`;
                    }

                    videoContainer.innerHTML = `<iframe id="course-video" width="100%" height="100%" 
                                                    src="${embedUrl}" 
                                                    frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                    allowfullscreen style="border-radius: 12px;">
                                                </iframe>`;
                    
                    nextBtn.disabled = true;
                    nextBtn.style.opacity = '0.5';
                    nextBtn.style.cursor = 'not-allowed';

                    if (isYtApiReady) {
                        setTimeout(() => { initYouTubePlayer('course-video'); }, 100);
                    }
                } else {
                    videoContainer.innerHTML = `<video id="course-video" width="100%" height="100%" controls style="object-fit: cover;">
                                                    <source src="${mat.video_url}" type="video/mp4">
                                                    Your browser does not support HTML video.
                                                </video>`;
                    let video = document.getElementById('course-video');
                    attachVideoEvent(video);
                    
                    nextBtn.disabled = true;
                    nextBtn.style.opacity = '0.5';
                    nextBtn.style.cursor = 'not-allowed';
                }
            } else {
                videoContainer.innerHTML = `<div style="display:flex; justify-content:center; align-items:center; height:100%; width:100%; background:#f0f0f0; border-radius:12px; font-size:24px; font-weight:bold; color:#1c407a;">
                                                Video Tidak Tersedia
                                            </div>`;
                nextBtn.disabled = false;
                nextBtn.style.opacity = '1';
                nextBtn.style.cursor = 'pointer';
            }

            prevBtn.disabled = index === 0;
            prevBtn.style.opacity = index === 0 ? '0.5' : '1';
            prevBtn.style.cursor = index === 0 ? 'not-allowed' : 'pointer';

            if (index === videosData.length - 1) {
                nextBtn.innerText = "Selesai";
            } else {
                nextBtn.innerText = "Next »";
            }

            currentIndex = index;

            if (menuItems[index].classList.contains('completed')) {
                nextBtn.disabled = false;
                nextBtn.style.opacity = '1';
                nextBtn.style.cursor = 'pointer';
            }
        }

        nextBtn.addEventListener('click', () => {
            if (currentIndex < videosData.length - 1) {
                loadMaterial(currentIndex + 1);
            } else {
                alert("Selamat! Anda telah menyelesaikan materi ini.");
                window.location.href = 'Materi.php';
            }
        });

        prevBtn.addEventListener('click', () => {
            if (currentIndex > 0) {
                loadMaterial(currentIndex - 1);
            }
        });

        menuItems.forEach((item, i) => {
            item.addEventListener('click', () => {
                loadMaterial(i);
            });
        });

        window.addEventListener('DOMContentLoaded', () => {
            menuItems.forEach((item, i) => {
                let vidId = parseInt(videosData[i].id);
                if (completedVideoIds.includes(vidId)) {
                    item.classList.add('completed');
                }
            });
            loadMaterial(0);
        });
    </script>
    <?php endif; ?>
</body>
</html>
