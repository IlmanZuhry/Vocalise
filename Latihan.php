<?php
require_once 'core.php';

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

$app = new manz();
$app->ensureSession();
$currentUser = $app->getCurrentUser();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	header('Content-Type: application/json');

	$action = $_POST['action'] ?? '';

	if ($action === 'save_practice') {
		echo json_encode($app->handleSavePractice($currentUser));
		exit;
	}

	if ($action === 'save_challenge') {
		echo json_encode($app->handleSaveChallenge($currentUser));
		exit;
	}

	if ($action === 'save_camera_practice') {
		echo json_encode($app->handleSaveCameraPractice($currentUser));
		exit;
	}

	if ($action === 'submit_to_mentor') {
		echo json_encode($app->handleSubmitToMentor($currentUser));
		exit;
	}
}

$practiceHistory = $currentUser ? $app->getPracticeHistory($currentUser['Id_User']) : [];
$challengeHistory = $currentUser ? $app->getChallengeHistory($currentUser['Id_User']) : [];
$cameraHistory = $currentUser ? $app->getCameraPracticeHistory($currentUser['Id_User']) : [];
$mentorInfo = $app->getMentorInfoForFeatures();

$practiceScripts = [
  [
    'category' => 'Pidato',
    'scripts' => [
      [
        'level' => 'Beginner',
        'duration' => 30,
        'title' => 'Pidato Singkat Tentang Disiplin',
        'text' => "Selamat pagi semuanya.\n\n[JEDA]\n\nHari ini saya ingin menyampaikan pesan singkat tentang disiplin.\n\nDisiplin membantu kita menggunakan waktu dengan lebih baik.\n\n[TEKANAN]\n\nJika kita konsisten, tujuan kecil setiap hari akan membawa perubahan besar."
      ],
      [
        'level' => 'Intermediate',
        'duration' => 60,
        'title' => 'Pentingnya Berani Berbicara',
        'text' => "Selamat pagi dan salam sejahtera untuk kita semua.\n\n[JEDA]\n\nKemampuan berbicara di depan umum bukan hanya bakat, tetapi keterampilan yang dapat dilatih.\n\nSaat kita berani menyampaikan pendapat, kita belajar menyusun pikiran dengan lebih jelas.\n\n[TEKANAN]\n\nKeberanian berbicara membuka kesempatan untuk memimpin, bekerja sama, dan memberi pengaruh positif.\n\nMari mulai dari langkah kecil: berbicara jelas, mendengar dengan baik, dan menyampaikan pesan dengan percaya diri."
      ],
      [
        'level' => 'Advanced',
        'duration' => 180,
        'title' => 'Pidato Tentang Generasi Pembelajar',
        'text' => "Assalamualaikum warahmatullahi wabarakatuh.\n\n[JEDA]\n\nHadirin yang saya hormati, hari ini kita hidup di masa ketika perubahan terjadi sangat cepat. Teknologi berkembang, cara belajar berubah, dan tantangan masa depan semakin beragam.\n\nNamun ada satu hal yang tetap penting, yaitu kemauan untuk terus belajar.\n\n[TEKANAN]\n\nGenerasi pembelajar bukan generasi yang selalu tahu semua jawaban. Generasi pembelajar adalah generasi yang berani bertanya, mau mencoba, dan tidak berhenti ketika gagal.\n\n[JEDA]\n\nDalam kehidupan sehari-hari, sikap belajar dapat dimulai dari hal sederhana. Kita membaca lebih teliti, mendengarkan pendapat orang lain, melatih kemampuan komunikasi, dan mengevaluasi diri setelah melakukan sesuatu.\n\nKemampuan public speaking juga bagian dari proses itu. Saat berbicara di depan orang lain, kita belajar mengatur pikiran, menjaga emosi, dan menyampaikan pesan secara bertanggung jawab.\n\n[TEKANAN]\n\nKarena itu, marilah kita menjadi generasi yang tidak hanya mengejar nilai, tetapi juga membangun karakter, keberanian, dan kemampuan untuk memberi manfaat.\n\nTerima kasih."
      ]
    ]
  ],
  [
    'category' => 'Presentasi',
    'scripts' => [
      [
        'level' => 'Beginner',
        'duration' => 30,
        'title' => 'Pembuka Presentasi Tugas',
        'text' => "Halo semuanya.\n\n[JEDA]\n\nPada kesempatan ini, saya akan mempresentasikan hasil tugas kelompok kami.\n\nMateri ini akan saya sampaikan dalam tiga bagian: latar belakang, pembahasan, dan kesimpulan.\n\n[TEKANAN]\n\nSemoga penjelasan ini mudah dipahami."
      ],
      [
        'level' => 'Intermediate',
        'duration' => 60,
        'title' => 'Presentasi Ide Aplikasi Belajar',
        'text' => "Selamat siang semuanya.\n\n[JEDA]\n\nHari ini saya akan memperkenalkan ide aplikasi belajar yang membantu siswa melatih public speaking secara mandiri.\n\nMasalah yang sering terjadi adalah siswa ingin berlatih, tetapi tidak tahu harus mulai dari topik apa.\n\n[TEKANAN]\n\nSolusi kami adalah menyediakan naskah, timer, rekaman, dan riwayat latihan agar proses belajar lebih terarah.\n\nDengan fitur ini, pengguna dapat membaca naskah, merekam suara, lalu mengevaluasi cara berbicara mereka secara bertahap."
      ],
      [
        'level' => 'Advanced',
        'duration' => 180,
        'title' => 'Presentasi Rencana Program Kelas',
        'text' => "Selamat pagi Bapak, Ibu, dan teman-teman.\n\n[JEDA]\n\nPada presentasi ini, saya akan menyampaikan rencana program kelas yang bertujuan meningkatkan kemampuan komunikasi siswa.\n\nProgram ini berangkat dari kebutuhan sederhana: banyak siswa memiliki ide yang baik, tetapi belum terbiasa menyampaikannya dengan jelas di depan umum.\n\n[TEKANAN]\n\nKarena itu, program ini dirancang dalam tiga tahap. Tahap pertama adalah latihan membaca naskah pendek untuk melatih artikulasi dan ritme. Tahap kedua adalah tantangan bicara singkat untuk melatih spontanitas. Tahap ketiga adalah praktik menggunakan kamera untuk melatih ekspresi dan bahasa tubuh.\n\n[JEDA]\n\nPelaksanaan program dapat dilakukan dua kali seminggu. Setiap sesi berlangsung singkat agar tidak membebani jadwal belajar. Siswa akan mendapat tema latihan, melakukan rekaman, lalu mencatat evaluasi sederhana.\n\nManfaat yang diharapkan bukan hanya kemampuan berbicara yang lebih baik, tetapi juga peningkatan rasa percaya diri, kemampuan menyusun pesan, dan keberanian menerima masukan.\n\n[TEKANAN]\n\nDengan program yang konsisten, kelas dapat menjadi ruang aman untuk belajar berbicara, mencoba, dan berkembang bersama.\n\nTerima kasih."
      ]
    ]
  ],
  [
    'category' => 'Seminar',
    'scripts' => [
      [
        'level' => 'Beginner',
        'duration' => 30,
        'title' => 'Pembuka Seminar Singkat',
        'text' => "Selamat pagi para peserta.\n\n[JEDA]\n\nTerima kasih sudah hadir dalam seminar hari ini.\n\nKita akan membahas cara membangun komunikasi yang lebih percaya diri.\n\n[TEKANAN]\n\nSemoga sesi ini memberi manfaat dan bisa langsung dipraktikkan."
      ],
      [
        'level' => 'Intermediate',
        'duration' => 60,
        'title' => 'Pengantar Seminar Komunikasi',
        'text' => "Selamat pagi dan salam sejahtera.\n\n[JEDA]\n\nPada seminar ini, kita akan membahas pentingnya komunikasi yang jelas dalam kehidupan akademik maupun profesional.\n\nKomunikasi bukan hanya tentang berbicara, tetapi juga tentang menyusun pesan, memilih kata, dan memahami audiens.\n\n[TEKANAN]\n\nKetika pesan disampaikan dengan baik, audiens lebih mudah memahami tujuan kita.\n\nMari ikuti sesi ini dengan aktif, terbuka, dan siap berlatih."
      ],
      [
        'level' => 'Advanced',
        'duration' => 180,
        'title' => 'Seminar Membangun Kepercayaan Diri',
        'text' => "Selamat pagi para peserta seminar yang saya hormati.\n\n[JEDA]\n\nKepercayaan diri sering dianggap sebagai sesuatu yang muncul secara alami. Padahal, dalam banyak situasi, kepercayaan diri tumbuh karena latihan yang konsisten dan pengalaman yang berulang.\n\nDalam public speaking, rasa percaya diri bukan berarti tidak pernah gugup. Rasa percaya diri berarti kita tetap mampu menyampaikan pesan meskipun ada rasa gugup.\n\n[TEKANAN]\n\nAda tiga hal yang dapat membantu kita membangun kepercayaan diri. Pertama, kuasai pesan utama yang ingin disampaikan. Kedua, latih suara agar terdengar jelas dan stabil. Ketiga, biasakan memberi jeda agar pikiran dan napas tetap terkontrol.\n\n[JEDA]\n\nSelain itu, evaluasi setelah latihan sangat penting. Dengarkan rekaman suara, perhatikan bagian yang terlalu cepat, lalu ulangi dengan ritme yang lebih baik. Proses sederhana ini dapat menghasilkan perkembangan nyata.\n\nPublic speaking bukan perlombaan untuk terlihat sempurna. Public speaking adalah proses menyampaikan pesan dengan jujur, terarah, dan mudah dipahami.\n\n[TEKANAN]\n\nSemoga setelah seminar ini, kita tidak hanya memahami teori, tetapi juga berani memulai latihan kecil secara rutin.\n\nTerima kasih."
      ]
    ]
  ],
  [
    'category' => 'MC',
    'scripts' => [
      [
        'level' => 'Beginner',
        'duration' => 30,
        'title' => 'Pembukaan Acara Kelas',
        'text' => "Selamat pagi semuanya.\n\n[JEDA]\n\nSaya sebagai pembawa acara mengucapkan selamat datang dalam kegiatan hari ini.\n\nSebelum acara dimulai, marilah kita berdoa agar kegiatan berjalan lancar.\n\n[TEKANAN]\n\nSemoga acara ini memberi manfaat bagi kita semua."
      ],
      [
        'level' => 'Intermediate',
        'duration' => 60,
        'title' => 'MC Seminar Sekolah',
        'text' => "Assalamualaikum warahmatullahi wabarakatuh.\n\n[JEDA]\n\nSelamat pagi Bapak, Ibu, narasumber, dan teman-teman yang berbahagia.\n\nSaya akan memandu jalannya seminar pada hari ini dengan tema komunikasi percaya diri.\n\n[TEKANAN]\n\nSebelum memasuki acara inti, mari kita siapkan perhatian dan semangat belajar.\n\nSemoga kegiatan ini berjalan lancar dari awal hingga akhir."
      ],
      [
        'level' => 'Advanced',
        'duration' => 180,
        'title' => 'MC Acara Formal',
        'text' => "Assalamualaikum warahmatullahi wabarakatuh.\n\n[JEDA]\n\nYang terhormat Bapak dan Ibu guru, yang kami hormati para tamu undangan, serta teman-teman yang saya banggakan.\n\nPuji syukur kita panjatkan ke hadirat Tuhan Yang Maha Esa karena pada hari ini kita dapat berkumpul dalam keadaan sehat untuk mengikuti acara yang telah kita nantikan bersama.\n\n[TEKANAN]\n\nSaya selaku pembawa acara akan memandu rangkaian kegiatan dari awal hingga selesai. Adapun susunan acara pada hari ini meliputi pembukaan, sambutan, penyampaian materi utama, sesi tanya jawab, dan penutup.\n\n[JEDA]\n\nSebelum memasuki acara pertama, kami mengajak seluruh hadirin untuk menjaga suasana tetap tertib dan memberikan perhatian penuh kepada setiap pengisi acara.\n\nSemoga kegiatan hari ini dapat berjalan lancar, memberi wawasan baru, dan menjadi pengalaman yang bermanfaat bagi seluruh peserta.\n\n[TEKANAN]\n\nBaik, marilah kita mulai acara ini dengan membaca doa menurut agama dan kepercayaan masing-masing. Berdoa dimulai.\n\nTerima kasih."
      ]
    ]
  ],
  [
    'category' => 'Storytelling',
    'scripts' => [
      [
        'level' => 'Beginner',
        'duration' => 30,
        'title' => 'Cerita Tentang Hari Pertama',
        'text' => "Hari pertama saya masuk kelas baru terasa menegangkan.\n\n[JEDA]\n\nSaya duduk diam dan belum berani menyapa siapa pun.\n\nNamun, satu teman tersenyum dan mengajak berbicara.\n\n[TEKANAN]\n\nDari situ saya belajar bahwa keberanian kecil bisa membuka pertemanan baru."
      ],
      [
        'level' => 'Intermediate',
        'duration' => 60,
        'title' => 'Cerita Belajar dari Kegagalan',
        'text' => "Beberapa waktu lalu, saya pernah gagal saat presentasi.\n\n[JEDA]\n\nSaya lupa urutan materi, berbicara terlalu cepat, dan merasa sangat gugup.\n\nSetelah itu, saya mendengarkan masukan dari teman dan mulai berlatih dengan naskah pendek.\n\n[TEKANAN]\n\nSaya belajar bahwa kegagalan bukan akhir, tetapi tanda bahwa kita perlu memperbaiki cara berlatih.\n\nSekarang, saya lebih siap karena tahu apa yang harus diperbaiki."
      ],
      [
        'level' => 'Advanced',
        'duration' => 180,
        'title' => 'Cerita Tentang Keberanian Mencoba',
        'text' => "Dulu saya selalu menghindar ketika diminta berbicara di depan kelas.\n\n[JEDA]\n\nSetiap kali guru meminta sukarelawan, saya menunduk dan berharap nama saya tidak dipanggil. Saya merasa suara saya tidak cukup bagus, kalimat saya tidak rapi, dan semua orang akan memperhatikan kesalahan saya.\n\nSuatu hari, kelompok saya harus mempresentasikan hasil diskusi. Tidak ada yang siap membuka presentasi, lalu saya mencoba mengambil bagian itu.\n\n[TEKANAN]\n\nAwalnya suara saya bergetar. Saya berhenti sebentar, menarik napas, lalu membaca kalimat pembuka yang sudah saya siapkan.\n\n[JEDA]\n\nTernyata, setelah kalimat pertama selesai, rasa takut itu mulai berkurang. Teman-teman mendengarkan, dan saya mulai memahami bahwa audiens tidak selalu menunggu kesalahan. Banyak dari mereka justru ingin memahami pesan yang kita sampaikan.\n\nSejak hari itu, saya tidak langsung menjadi pembicara yang hebat. Tetapi saya mulai berani berlatih. Saya merekam suara, memperbaiki tempo, dan belajar memberi jeda.\n\n[TEKANAN]\n\nKeberanian tidak selalu datang sebelum kita mencoba. Kadang keberanian muncul setelah kita mengambil langkah pertama.\n\nItulah pelajaran yang saya ingat sampai sekarang."
      ]
    ]
  ],
  [
    'category' => 'Perkenalan Diri',
    'scripts' => [
      [
        'level' => 'Beginner',
        'duration' => 30,
        'title' => 'Perkenalan Diri Singkat',
        'text' => "Halo semuanya.\n\n[JEDA]\n\nPerkenalkan nama saya Ahmad.\n\nSaya adalah mahasiswa yang tertarik pada teknologi dan komunikasi.\n\n[TEKANAN]\n\nSaya ingin melatih public speaking agar dapat menyampaikan ide dengan lebih percaya diri."
      ],
      [
        'level' => 'Intermediate',
        'duration' => 60,
        'title' => 'Perkenalan Diri yang Percaya Diri',
        'text' => "Halo semuanya.\n\n[JEDA]\n\nPerkenalkan nama saya Ahmad.\n\nSaya merupakan mahasiswa yang memiliki minat pada bidang teknologi dan komunikasi.\n\n[TEKANAN]\n\nSaya percaya bahwa kemampuan public speaking merupakan salah satu keterampilan penting untuk masa depan.\n\nMelalui latihan yang konsisten, saya ingin belajar berbicara dengan lebih jelas, terstruktur, dan percaya diri.\n\nTerima kasih."
      ],
      [
        'level' => 'Advanced',
        'duration' => 180,
        'title' => 'Perkenalan Diri Profesional',
        'text' => "Halo semuanya, perkenalkan nama saya Ahmad.\n\n[JEDA]\n\nSaya adalah mahasiswa yang memiliki ketertarikan besar pada bidang teknologi, komunikasi, dan pengembangan diri. Selama ini saya belajar bahwa kemampuan teknis saja tidak cukup. Kita juga perlu mampu menjelaskan ide, bekerja sama, dan menyampaikan pendapat dengan jelas.\n\n[TEKANAN]\n\nKarena itu, saya terus melatih public speaking sebagai bagian dari proses membangun kepercayaan diri.\n\nDalam kegiatan sehari-hari, saya senang mempelajari hal baru, terutama yang berhubungan dengan cara teknologi membantu manusia berkomunikasi dan belajar lebih efektif.\n\n[JEDA]\n\nSaya juga percaya bahwa setiap orang dapat berkembang jika memiliki kemauan untuk mencoba, menerima masukan, dan memperbaiki diri secara bertahap.\n\nMelalui kesempatan ini, saya ingin memperkenalkan diri bukan hanya sebagai seseorang yang sedang belajar, tetapi juga sebagai pribadi yang ingin terus tumbuh dan memberi kontribusi positif.\n\n[TEKANAN]\n\nHarapan saya, latihan ini membantu saya berbicara lebih runtut, mengatur intonasi, dan menyampaikan pesan dengan lebih meyakinkan.\n\nTerima kasih atas perhatiannya."
      ]
    ]
  ],
  [
    'category' => 'Motivasi',
    'scripts' => [
      [
        'level' => 'Beginner',
        'duration' => 30,
        'title' => 'Motivasi Memulai Latihan',
        'text' => "Setiap kemampuan besar dimulai dari latihan kecil.\n\n[JEDA]\n\nKita tidak harus langsung sempurna saat pertama mencoba.\n\nYang penting adalah berani memulai dan mau memperbaiki diri.\n\n[TEKANAN]\n\nSatu latihan hari ini bisa menjadi langkah penting untuk masa depan."
      ],
      [
        'level' => 'Intermediate',
        'duration' => 60,
        'title' => 'Motivasi Menghadapi Rasa Gugup',
        'text' => "Rasa gugup saat berbicara di depan umum adalah hal yang wajar.\n\n[JEDA]\n\nBahkan pembicara berpengalaman pun pernah mengalaminya.\n\nYang membedakan adalah cara kita mengelola rasa gugup tersebut.\n\n[TEKANAN]\n\nTarik napas, atur tempo, dan fokus pada pesan yang ingin disampaikan.\n\nSemakin sering berlatih, semakin kuat kepercayaan diri kita."
      ],
      [
        'level' => 'Advanced',
        'duration' => 180,
        'title' => 'Motivasi Untuk Terus Bertumbuh',
        'text' => "Dalam perjalanan belajar, sering kali kita ingin melihat hasil yang cepat.\n\n[JEDA]\n\nKita ingin langsung lancar berbicara, langsung percaya diri, dan langsung mampu memukau audiens. Namun, kemampuan yang kuat biasanya dibangun melalui proses yang tidak selalu terlihat.\n\nLatihan kecil yang dilakukan berulang kali akan membentuk kebiasaan. Kebiasaan akan membentuk kepercayaan diri. Dan kepercayaan diri akan membantu kita tampil lebih baik saat kesempatan datang.\n\n[TEKANAN]\n\nJangan menunggu sampai rasa takut hilang sepenuhnya untuk mulai berbicara. Mulailah berbicara, lalu biarkan latihan membantu mengurangi rasa takut itu.\n\n[JEDA]\n\nSetiap rekaman yang kita buat adalah bahan evaluasi. Setiap kesalahan adalah petunjuk. Setiap pengulangan adalah investasi untuk kemampuan diri.\n\nMungkin hari ini suara kita masih terdengar ragu. Mungkin tempo kita belum stabil. Tetapi selama kita mau memperbaiki, kita sedang bergerak maju.\n\n[TEKANAN]\n\nTeruslah bertumbuh, karena pembicara yang baik bukan lahir dari satu penampilan sempurna, melainkan dari keberanian untuk terus berlatih.\n\nTerima kasih."
      ]
    ]
  ]
];
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vocalise - Latihan</title>
  <?php include 'includes/layout_css.php'; ?>
  <script>
    window.tailwind = window.tailwind || {};
    window.tailwind.config = { corePlugins: { preflight: false } };
  </script>
  <script src="https://cdn.tailwindcss.com"></script>
  <script crossorigin src="https://unpkg.com/react@18/umd/react.development.js"></script>
  <script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
  <script crossorigin src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
  <style>
    body { 
      background: #f7f7fc; 
      width: 100%; 
      display: flex; 
      margin: 0; 
      padding: 0; 
      overflow-x: hidden;
    }

    main {
      margin-left: 260px;
      padding: 120px 40px 48px;
      color: #101828;
      width: calc(100% - 260px) !important;
      min-height: 100vh;
      box-sizing: border-box;
      max-width: calc(100% - 260px) !important;
      display: block !important;
      flex-grow: 1;
      overflow-x: hidden;
    }

    .page-head {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      gap: 20px;
      margin-bottom: 28px;
      width: 100% !important;
    }

    .page-head h1 {
      font-size: 40px;
      line-height: 1.1;
      margin-bottom: 8px;
    }

    .page-head p {
      color: #777;
      font-size: 20px;
    }

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

    .btn-primary { background: #64C7D4; color: #fff; }
    .btn-dark { background: #102A43; color: #fff; }
    .btn-muted { background: #EAF5F7; color: #344054; }
    .btn-danger { background: #b42318; color: #fff; }
    .btn:disabled { cursor: not-allowed; opacity: 0.55; }

    .camera-card {
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 8px 24px rgb(0 0 0 / 0.07);
      border: 1px solid #e5e7eb;
      transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
    }

    .camera-card.interactive:hover {
      transform: translateY(-4px);
      box-shadow: 0 16px 34px rgb(16 32 79 / 0.12);
      border-color: #64C7D4;
    }

    .camera-btn {
      border: 0;
      border-radius: 14px;
      padding: 12px 20px;
      font-weight: 800;
      font-size: 15px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: 0.2s;
    }

    .camera-btn:disabled {
      cursor: not-allowed;
      opacity: 0.55;
    }

    .btn-primary-camera { background: #64C7D4; color: #fff; }
    .btn-muted-camera { background: #EAF5F7; color: #344054; }
    .btn-danger-camera { background: #b42318; color: #fff; }

    .start-recording-cta {
      box-shadow: 0 14px 30px rgb(210 160 107 / 0.28);
    }

    .start-recording-cta:not(:disabled):hover {
      transform: translateY(-2px);
      box-shadow: 0 18px 38px rgb(210 160 107 / 0.36);
    }

    .start-recording-cta:not(:disabled) {
      animation: ctaBreath 2.8s ease-in-out infinite;
    }

    .recording-glow {
      box-shadow: 0 0 0 2px rgb(239 68 68 / 0.45), 0 20px 60px rgb(210 160 107 / 0.28);
    }

    .recording-dot {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: #ef4444;
      box-shadow: 0 0 0 0 rgb(239 68 68 / 0.7);
      animation: recordPulse 1.2s infinite;
    }

    .camera-status-pill {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      border-radius: 999px;
      background: #EAF5F7;
      color: #344054;
      padding: 8px 12px;
      font-size: 12px;
      font-weight: 900;
    }

    .camera-status-pill.live {
      background: #ecfdf3;
      color: #027a48;
    }

    .audio-wave {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      height: 24px;
    }

    .audio-wave span {
      width: 4px;
      height: 8px;
      border-radius: 999px;
      background: #64C7D4;
      opacity: 0.45;
    }

    .audio-wave.active span {
      animation: waveBounce 0.9s ease-in-out infinite;
      opacity: 1;
    }

    .audio-wave span:nth-child(2) { animation-delay: 0.12s; }
    .audio-wave span:nth-child(3) { animation-delay: 0.24s; }
    .audio-wave span:nth-child(4) { animation-delay: 0.36s; }
    .audio-wave span:nth-child(5) { animation-delay: 0.48s; }

    @keyframes waveBounce {
      0%, 100% { height: 8px; }
      50% { height: 22px; }
    }

    .history-video-card .play-overlay {
      opacity: 0;
      transition: opacity 0.22s ease;
    }

    .history-video-card:hover .play-overlay {
      opacity: 1;
    }

    @keyframes recordPulse {
      0% { box-shadow: 0 0 0 0 rgb(239 68 68 / 0.7); }
      70% { box-shadow: 0 0 0 12px rgb(239 68 68 / 0); }
      100% { box-shadow: 0 0 0 0 rgb(239 68 68 / 0); }
    }

    @keyframes ctaBreath {
      0%, 100% { box-shadow: 0 14px 30px rgb(210 160 107 / 0.25); }
      50% { box-shadow: 0 18px 42px rgb(210 160 107 / 0.4); }
    }

    .feature-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 18px;
      margin-bottom: 28px;
      width: 100% !important;
    }

    .feature-card {
      background: #fff;
      border: 2px solid transparent;
      border-radius: 18px;
      padding: 24px;
      box-shadow: 0 8px 24px rgb(0 0 0 / 0.07);
      min-height: 175px;
      cursor: pointer;
      text-align: left;
      width: 100%;
      overflow: hidden;
      position: relative;
      transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
    }

    .feature-card.active {
      border-color: #64C7D4;
      background: #EAF5F7;
    }

    .feature-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 14px 34px rgb(16 32 79 / 0.14);
    }

    .feature-card::after {
      content: "";
      position: absolute;
      width: 120px;
      height: 120px;
      border-radius: 50%;
      right: -46px;
      top: -46px;
      background: rgb(210 160 107 / 0.14);
    }

    .feature-icon {
      width: 58px;
      height: 58px;
      border-radius: 18px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 18px;
      background: #102A43;
      color: #fff;
      font-size: 30px;
      box-shadow: 0 10px 22px rgb(16 32 79 / 0.18);
      position: relative;
      z-index: 1;
    }

    .feature-card[data-feature="challenge"] .feature-icon { background: #6d2d59; }
    .feature-card[data-feature="ai"] .feature-icon { background: #b8752b; }

    .feature-card h2 {
      color: #102A43;
      font-size: 24px;
      margin-bottom: 10px;
      position: relative;
      z-index: 1;
    }

    .feature-card p {
      color: #667085;
      font-size: 15px;
      line-height: 1.5;
      margin-bottom: 18px;
      position: relative;
      z-index: 1;
    }

    .badge {
      display: inline-flex;
      border-radius: 999px;
      padding: 7px 12px;
      font-size: 12px;
      font-weight: 800;
      background: #EAF5F7;
      color: #344054;
    }

    .badge-ready { background: #ecfdf3; color: #027a48; }

    .workspace {
      display: grid;
      grid-template-columns: 1fr 340px;
      gap: 24px;
      align-items: start;
      width: 100% !important;
      max-width: none !important;
    }

    .feature-section {
      display: none;
    }

    .feature-section.active {
      display: grid;
    }

    .panel,
    .history-card {
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 8px 24px rgb(0 0 0 / 0.07);
    }

    .panel {
      padding: 28px;
      overflow: hidden;
    }

    .panel-title {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 20px;
      margin-bottom: 24px;
      padding-right: 4px;
    }

    .panel-title h2 {
      color: #102A43;
      font-size: 26px;
      margin-bottom: 8px;
    }

    .panel-title p {
      color: #667085;
      font-size: 15px;
      line-height: 1.5;
    }

    .coach-strip {
      display: flex;
      align-items: center;
      gap: 16px;
      background: #EAF5F7;
      border: 1px solid #CDE5EA;
      border-radius: 18px;
      padding: 14px 16px;
      margin-bottom: 22px;
    }

    .coach-strip img {
      width: 74px;
      height: 74px;
      object-fit: contain;
      flex-shrink: 0;
    }

    .coach-strip strong {
      display: block;
      color: #102A43;
      font-size: 18px;
      margin-bottom: 3px;
    }

    .coach-strip span {
      color: #667085;
      font-size: 14px;
      line-height: 1.45;
    }

    .topic-box {
      background: #102A43;
      border-radius: 18px;
      color: #fff;
      padding: 26px;
      position: relative;
      overflow: hidden;
      margin-bottom: 22px;
    }

    .topic-box::after {
      content: "";
      position: absolute;
      width: 190px;
      height: 190px;
      border-radius: 50%;
      right: -64px;
      top: -78px;
      background: rgb(210 160 107 / 0.32);
    }

    .topic-label {
      color: #64C7D4;
      font-size: 13px;
      font-weight: 900;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      margin-bottom: 10px;
    }

    .topic-text {
      position: relative;
      z-index: 1;
      font-size: 28px;
      font-weight: 800;
      line-height: 1.25;
    }

    .control-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 18px;
      margin-bottom: 22px;
    }

    .challenge-layout {
      display: grid;
      grid-template-columns: 280px 1fr 340px;
      gap: 20px;
      align-items: stretch;
      margin-bottom: 22px;
    }

    .challenge-layout > .control-box {
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .challenge-layout .option-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 10px;
      flex: 1;
    }

    .challenge-layout .option-card {
      padding: 12px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .challenge-layout .option-card strong {
      font-size: 14px;
    }

    .challenge-layout .option-card span {
      font-size: 11px;
    }

    .briefing-card {
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .stat-row {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
    }

    .stat-item {
      background: #fff;
      padding: 12px;
      border-radius: 16px;
      border: 1px solid #CDE5EA;
      text-align: center;
    }

    .fokus-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 8px;
    }

    .fokus-tag {
      background: #102A43;
      color: #fff;
      font-size: 10px;
      font-weight: 900;
      padding: 6px 10px;
      border-radius: 8px;
      text-align: center;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .simulation-sidebar {
      background: #fff;
      border-radius: 22px;
      padding: 24px;
      border: 1px solid #e5e7eb;
      box-shadow: 0 4px 14px rgb(0 0 0 / 0.04);
    }

    .simulation-sidebar h4 {
      color: #102A43;
      font-size: 14px;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .tip-item {
      display: flex;
      gap: 10px;
      margin-bottom: 12px;
      font-size: 13px;
      font-weight: 700;
      color: #667085;
      line-height: 1.4;
    }

    .tip-item span {
      color: #027a48;
    }

    .xp-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #EAF5F7;
      border: 1px solid #CDE5EA;
      color: #64C7D4;
      padding: 8px 16px;
      border-radius: 12px;
      font-weight: 900;
      font-size: 14px;
    }

    .streak-card {
      background: #102A43;
      color: #fff;
      padding: 16px;
      border-radius: 18px;
      text-align: center;
    }

    .streak-card strong {
      display: block;
      font-size: 24px;
      margin-bottom: 2px;
    }

    .streak-card span {
      font-size: 11px;
      font-weight: 800;
      text-transform: uppercase;
      opacity: 0.6;
    }

    .option-card {
      border: 1px solid #e5e7eb;
      background: #fff;
      border-radius: 14px;
      padding: 15px;
      text-align: left;
      cursor: pointer;
    }

    .option-card.active {
      border-color: #64C7D4;
      background: #EAF5F7;
    }

    .option-card strong {
      display: block;
      color: #102A43;
      font-size: 16px;
      margin-bottom: 5px;
    }

    .option-card span {
      color: #667085;
      font-size: 14px;
      line-height: 1.45;
    }

    .control-box {
      border: 1px solid #e5e7eb;
      border-radius: 16px;
      background: #fbfbfd;
      padding: 18px;
    }

    .control-box h3 {
      color: #102A43;
      font-size: 17px;
      margin-bottom: 12px;
    }

    .duration-options,
    .action-row {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
    }

    .duration-btn {
      border: 1px solid #d0d5dd;
      background: #fff;
      color: #344054;
      border-radius: 12px;
      padding: 10px 15px;
      font-weight: 800;
      cursor: pointer;
    }

    .duration-btn.active {
      border-color: #64C7D4;
      background: #64C7D4;
      color: #fff;
    }

    .category-grid {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 12px;
      margin-bottom: 22px;
    }

    .category-btn {
      border: 1px solid #e5e7eb;
      background: #fff;
      color: #344054;
      border-radius: 14px;
      padding: 14px 15px;
      font-weight: 900;
      cursor: pointer;
      text-align: left;
      transition: border-color 0.2s, background 0.2s, color 0.2s;
    }

    .category-btn.active {
      border-color: #64C7D4;
      background: #EAF5F7;
      color: #102A43;
    }

    .script-layout {
      display: grid;
      grid-template-columns: minmax(0, 1.1fr) minmax(260px, 0.55fr);
      gap: 18px;
      margin-bottom: 22px;
    }

    .script-card,
    .guide-card {
      border: 1px solid #e5e7eb;
      border-radius: 18px;
      background: #fff;
      padding: 22px;
    }

    .script-card {
      box-shadow: 0 8px 22px rgb(16 32 79 / 0.06);
    }

    .script-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin: 12px 0 18px;
    }

    .script-title {
      color: #102A43;
      font-size: 28px;
      line-height: 1.25;
      margin: 0;
    }

    .script-text {
      color: #344054;
      font-size: 18px;
      line-height: 1.8;
      white-space: pre-line;
    }

    .script-text .script-cue {
      display: inline-flex;
      margin: 4px 0;
      border-radius: 999px;
      padding: 3px 10px;
      background: #EAF5F7;
      color: #b8752b;
      font-size: 13px;
      font-weight: 900;
      letter-spacing: 0.3px;
    }

    .guide-card h3 {
      color: #102A43;
      font-size: 18px;
      margin-bottom: 12px;
    }

    .guide-list {
      list-style: none;
      padding: 0;
      margin: 0;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .guide-list li {
      color: #344054;
      font-weight: 700;
      line-height: 1.45;
    }

    .guide-list li::before {
      content: "✓";
      color: #027a48;
      font-weight: 900;
      margin-right: 8px;
    }

    .recording-status-line {
      display: flex;
      align-items: center;
      gap: 10px;
      color: #667085;
      font-size: 13px;
      font-weight: 800;
      margin-bottom: 12px;
    }

    .recording-status-line .recording-dot {
      display: none;
    }

    .recording-status-line.active .recording-dot {
      display: inline-flex;
      flex-shrink: 0;
    }

    .voice-wave {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      height: 28px;
      margin-top: 8px;
    }

    .voice-wave span {
      width: 5px;
      height: 9px;
      border-radius: 999px;
      background: #64C7D4;
      opacity: 0.42;
    }

    .voice-wave.active span {
      animation: waveBounce 0.9s ease-in-out infinite;
      opacity: 1;
    }

    .voice-wave span:nth-child(2) { animation-delay: 0.1s; }
    .voice-wave span:nth-child(3) { animation-delay: 0.2s; }
    .voice-wave span:nth-child(4) { animation-delay: 0.3s; }
    .voice-wave span:nth-child(5) { animation-delay: 0.4s; }

    .recorder-box {
      border: 1px solid #e5e7eb;
      border-radius: 18px;
      background: #fbfbfd;
      padding: 22px;
    }

    .practice-stage {
      display: grid;
      grid-template-columns: 190px minmax(0, 1fr);
      gap: 22px;
      align-items: center;
      margin-bottom: 18px;
    }

    .mic-orb {
      width: 172px;
      height: 172px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: radial-gradient(circle at 35% 25%, #EAF5F7, #64C7D4 48%, #102A43 100%);
      color: #fff;
      font-size: 58px;
      box-shadow: 0 18px 40px rgb(16 32 79 / 0.2);
      position: relative;
      isolation: isolate;
    }

    .mic-orb::before,
    .mic-orb::after {
      content: "";
      position: absolute;
      inset: -12px;
      border-radius: 50%;
      border: 2px solid rgb(210 160 107 / 0.28);
      opacity: 0;
      z-index: -1;
    }

    .mic-orb.recording::before { animation: pulseRing 1.4s ease-out infinite; }
    .mic-orb.recording::after { animation: pulseRing 1.4s ease-out 0.45s infinite; }

    @keyframes pulseRing {
      0% { transform: scale(0.82); opacity: 0.85; }
      100% { transform: scale(1.24); opacity: 0; }
    }

    .timer-wrap {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 18px;
      margin-bottom: 16px;
    }

    .timer {
      color: #102A43;
      font-size: 48px;
      font-weight: 900;
      line-height: 1;
    }

    .challenge-timer {
      font-size: 64px;
    }

    .countdown-card {
      background: #102A43;
      color: #fff;
      border-radius: 22px;
      padding: 24px;
      min-width: 220px;
      text-align: center;
      box-shadow: 0 16px 34px rgb(16 32 79 / 0.18);
    }

    .countdown-card .timer,
    .countdown-card .timer-caption {
      color: #fff;
    }

    .countdown-card .timer-caption {
      color: #dbe4ff;
    }

    .timer-caption {
      color: #667085;
      font-weight: 700;
      margin-top: 6px;
    }

    .status-pill {
      border-radius: 999px;
      padding: 9px 14px;
      background: #EAF5F7;
      color: #344054;
      font-weight: 900;
      font-size: 13px;
    }

    .status-recording {
      background: #fee4e2;
      color: #b42318;
    }

    .meter {
      height: 10px;
      background: #e5e7eb;
      border-radius: 999px;
      overflow: hidden;
      margin-bottom: 18px;
    }

    .meter-fill {
      width: 0;
      height: 100%;
      background: #64C7D4;
      border-radius: 999px;
      transition: width 0.2s;
    }

    audio {
      width: 100%;
      margin-top: 18px;
    }

    .result-box {
      display: none;
      margin-top: 18px;
      padding: 18px;
      border-radius: 16px;
      background: #f8fafc;
      border: 1px solid #e5e7eb;
    }

    .result-box h3 {
      color: #102A43;
      margin-bottom: 12px;
    }

    .result-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 12px;
    }

    .score-card {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 12px;
      margin-top: 14px;
    }

    .score-card.four {
      grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    .score-item {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 14px;
      padding: 14px;
    }

    .score-item span {
      display: block;
      color: #667085;
      font-size: 12px;
      font-weight: 800;
      margin-bottom: 6px;
    }

    .score-item strong {
      color: #102A43;
      font-size: 22px;
    }

    .score-ring {
      width: 116px;
      height: 116px;
      border-radius: 50%;
      margin: 0 auto 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: conic-gradient(#64C7D4 var(--score, 0%), #EAF5F7 0);
      position: relative;
    }

    .score-ring::after {
      content: "";
      position: absolute;
      width: 84px;
      height: 84px;
      border-radius: 50%;
      background: #fff;
    }

    .score-ring strong {
      position: relative;
      z-index: 1;
      color: #102A43;
      font-size: 24px;
    }

    .analysis-input {
      width: 100%;
      min-height: 130px;
      border: 1px solid #d0d5dd;
      border-radius: 14px;
      padding: 14px;
      font-size: 15px;
      line-height: 1.5;
      color: #344054;
      resize: vertical;
    }

    .achievement-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 12px;
      margin-top: 14px;
    }

    .achievement {
      border-radius: 14px;
      border: 1px solid #e5e7eb;
      background: #f8fafc;
      padding: 15px;
    }

    .achievement.unlocked {
      background: #ecfdf3;
      border-color: #abefc6;
    }

    .achievement strong {
      display: block;
      color: #102A43;
      margin-bottom: 6px;
    }

    .achievement span {
      color: #667085;
      font-size: 13px;
      line-height: 1.4;
    }

    .toast {
      display: none;
      margin-top: 16px;
      border-radius: 14px;
      padding: 14px 16px;
      font-weight: 700;
      line-height: 1.5;
    }

    .toast.success { display: block; background: #ecfdf3; color: #027a48; }
    .toast.error { display: block; background: #fef3f2; color: #b42318; }
    .toast.info { display: block; background: #eff8ff; color: #175cd3; }

    .history-panel {
      padding: 24px;
    }

    .history-panel h2 {
      color: #102A43;
      font-size: 24px;
      margin-bottom: 8px;
    }

    .history-panel p {
      color: #667085;
      font-size: 15px;
      line-height: 1.5;
      margin-bottom: 18px;
    }

    .history-list {
      display: flex;
      flex-direction: column;
      gap: 14px;
      max-height: 620px;
      overflow-y: auto;
      padding-right: 4px;
    }

    .history-card {
      box-shadow: 0 4px 14px rgb(0 0 0 / 0.06);
      padding: 16px;
    }

    .history-card strong {
      display: block;
      color: #102A43;
      line-height: 1.35;
      margin-bottom: 8px;
    }

    .history-card small {
      display: block;
      color: #667085;
      margin-bottom: 10px;
    }

    .history-card audio {
      margin-top: 8px;
    }

    /* Hide scrollbars but keep functionality */
    .no-scrollbar {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
    .no-scrollbar::-webkit-scrollbar {
      display: none;
    }

    .teleprompter-container {
      mask-image: linear-gradient(to bottom, transparent, black 15%, black 85%, transparent);
    }

    .cue-toast {
      animation: cueFade 2s ease-in-out forwards;
    }

    @keyframes cueFade {
      0% { opacity: 0; transform: translateY(10px); }
      15% { opacity: 1; transform: translateY(0); }
      85% { opacity: 1; transform: translateY(0); }
      100% { opacity: 0; transform: translateY(-10px); }
    }

    .empty-state {
      color: #667085;
      background: #f8fafc;
      border: 1px dashed #cbd5e1;
      border-radius: 16px;
      padding: 18px;
      line-height: 1.5;
    }

    @media (max-width: 1100px) {
      main {
        margin-left: 0;
        padding: 112px 24px 36px;
        width: 100% !important;
      }

      .feature-grid,
      .workspace,
      .challenge-layout,
      .script-layout,
      .practice-stage,
      .score-card,
      .score-card.four,
      .achievement-grid,
      .control-grid {
        grid-template-columns: 1fr;
      }

      .category-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

      .page-head,
      .panel-title,
      .timer-wrap {
        align-items: flex-start;
        flex-direction: column;
      }
    }

    @media (max-width: 560px) {
      .category-grid {
        grid-template-columns: 1fr;
      }

      .script-title {
        font-size: 23px;
      }

      .script-text {
        font-size: 16px;
      }
    }
  </style>
</head>

<body>
  <?php include 'includes/header.php'; ?>
  <?php include 'includes/sidebar.php'; ?>

  <main>
    <section class="page-head">
      <div>
        <h1>Latihan</h1>
        <p>Pilih fitur latihan public speaking yang ingin kamu gunakan.</p>
      </div>
      <a href="Beranda.php" class="btn btn-muted">Kembali</a>
    </section>

    <section class="feature-grid" aria-label="Pilihan fitur latihan">
      <button class="feature-card active" type="button" data-feature="voice">
        <div class="feature-icon">🎙</div>
        <h2>Rekam Suara</h2>
        <p>Pilih topik, bicara, dengarkan ulang.</p>
        <?php if ($mentorInfo['voice']): ?>
          <span class="badge badge-ready">Mentor: <?= htmlspecialchars($mentorInfo['voice']['name']) ?></span>
        <?php endif; ?>
      </button>
      <button class="feature-card" type="button" data-feature="challenge">
        <div class="feature-icon">⏱</div>
        <h2>Tantangan Bicara</h2>
        <p>Mode cepat dengan level dan skor.</p>
        <?php if ($mentorInfo['challenge']): ?>
          <span class="badge badge-ready">Mentor: <?= htmlspecialchars($mentorInfo['challenge']['name']) ?></span>
        <?php endif; ?>
      </button>
      <button class="feature-card" type="button" data-feature="ai">
        <div class="feature-icon">📹</div>
        <h2>Camera Practice</h2>
        <p>Latih ekspresi, eye contact, dan gestur.</p>
        <?php if ($mentorInfo['camera']): ?>
          <span class="badge badge-ready">Mentor: <?= htmlspecialchars($mentorInfo['camera']['name']) ?></span>
        <?php endif; ?>
      </button>
    </section>

    <section class="workspace feature-section active" id="voiceSection">
      <div id="guided-speaking-root"></div>

      <aside class="panel history-panel">
        <h2>Practice History</h2>
        <p>Riwayat menampilkan judul latihan, kategori, tanggal, durasi, dan audio playback yang tersimpan di akun pengguna.</p>

        <div class="history-list" id="historyList">
          <?php if (!$currentUser): ?>
            <div class="empty-state">Silakan login terlebih dahulu agar hasil latihan bisa disimpan ke akun.</div>
          <?php elseif (empty($practiceHistory)): ?>
            <div class="empty-state">Belum ada riwayat latihan. Selesaikan satu rekaman lalu simpan riwayat.</div>
          <?php else: ?>
            <?php foreach ($practiceHistory as $item): ?>
              <div class="history-card">
                <strong><?= htmlspecialchars($item['script_title'] ?? $item['topic']) ?></strong>
                <small>Kategori: <?= htmlspecialchars($item['category'] ?? 'Latihan Suara') ?></small>
                <small><?= htmlspecialchars(date('d M Y H:i', strtotime($item['created_at']))) ?> - <?= (int) $item['duration_seconds'] ?> detik<?= !empty($item['level_name']) ? ' - ' . htmlspecialchars($item['level_name']) : '' ?></small>
                <audio controls src="<?= htmlspecialchars($item['audio_path']) ?>"></audio>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </aside>
    </section>

    <section class="workspace feature-section" id="challengeSection">
      <div id="conversation-challenge-root"></div>

      <aside class="panel history-panel">
        <div class="flex items-center justify-between mb-4">
          <h2 class="m-0">Riwayat Tantangan</h2>
          <?php if (count($challengeHistory) > 3): ?>
            <a href="HasilLatihan.php" class="text-xs font-black text-[#64C7D4] uppercase tracking-wider hover:underline">Lihat Semua →</a>
          <?php endif; ?>
        </div>
        <p>3 simulasi terakhir Anda. Selesaikan tantangan untuk melihat progres terbaru.</p>

        <div class="history-list" id="challengeHistoryList">
          <?php if (!$currentUser): ?>
            <div class="empty-state">Silakan login terlebih dahulu agar hasil tantangan bisa disimpan ke akun.</div>
          <?php elseif (empty($challengeHistory)): ?>
            <div class="empty-state">Belum ada riwayat tantangan. Selesaikan satu simulasi lalu simpan hasilnya.</div>
          <?php else: ?>
            <?php 
              $limitedHistory = array_slice($challengeHistory, 0, 3);
              foreach ($limitedHistory as $item): 
            ?>
              <div class="history-card">
                <strong><?= htmlspecialchars($item['challenge_type']) ?></strong>
                <small><?= htmlspecialchars($item['level_name']) ?></small>
                <small><?= (int) ($item['question_count'] ?? 1) ?> Pertanyaan</small>
                <small><?= htmlspecialchars(date('d M Y H:i', strtotime($item['created_at']))) ?> - <?= (int) $item['actual_seconds'] ?> detik</small>
                <div class="result-meta">
                  <span class="badge"><?= (int) $item['completed'] === 1 ? 'Selesai' : 'Belum selesai' ?></span>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </aside>
    </section>

    <section class="feature-section" id="aiSection">
      <div id="camera-practice-root"></div>
    </section>
  </main>

  <script>
    const practiceScripts = <?= json_encode($practiceScripts, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    const isLoggedIn = <?= $currentUser ? 'true' : 'false' ?>;
    const featureCards = document.querySelectorAll(".feature-card");
    const voiceSection = document.getElementById("voiceSection");
    const challengeSection = document.getElementById("challengeSection");
    const aiSection = document.getElementById("aiSection");
    const historyList = document.getElementById("historyList");
    const challengeHistoryList = document.getElementById("challengeHistoryList");

    function switchFeature(feature) {
      featureCards.forEach(card => {
        card.classList.toggle("active", card.dataset.feature === feature);
      });

      voiceSection.classList.toggle("active", feature === "voice");
      challengeSection.classList.toggle("active", feature === "challenge");
      aiSection.classList.toggle("active", feature === "ai");
    }

    function prependHistory(item) {
      if (!historyList) return;
      const empty = historyList.querySelector(".empty-state");
      if (empty) empty.remove();

      const card = document.createElement("div");
      card.className = "history-card";

      const title = document.createElement("strong");
      title.textContent = item.script_title || item.topic;

      const category = document.createElement("small");
      category.textContent = `Kategori: ${item.category || "Latihan Suara"}`;

      const meta = document.createElement("small");
      const level = item.level_name ? ` - ${item.level_name}` : "";
      meta.textContent = `${new Date(item.created_at.replace(" ", "T")).toLocaleString("id-ID")} - ${item.duration_seconds} detik${level}`;

      const audio = document.createElement("audio");
      audio.controls = true;
      audio.src = item.audio_path;

      card.append(title, category, meta, audio);
      historyList.prepend(card);
    }

    function prependChallengeHistory(item) {
      if (!challengeHistoryList) return;
      const empty = challengeHistoryList.querySelector(".empty-state");
      if (empty) empty.remove();

      const card = document.createElement("div");
      card.className = "history-card";

      const title = document.createElement("strong");
      title.textContent = item.challenge_type;

      const meta = document.createElement("small");
      meta.textContent = item.level_name;

      const count = document.createElement("small");
      count.textContent = `${item.question_count || 1} Pertanyaan`;

      const date = document.createElement("small");
      date.textContent = `${new Date(item.created_at.replace(" ", "T")).toLocaleString("id-ID")} - ${item.actual_seconds} detik`;

      const stats = document.createElement("div");
      stats.className = "result-meta";

      const completed = document.createElement("span");
      completed.className = "badge";
      completed.textContent = Number(item.completed) === 1 ? "Selesai" : "Belum selesai";

      stats.append(completed);
      card.append(title, meta, count, date, stats);
      challengeHistoryList.prepend(card);
    }

    window.prependHistory = prependHistory;
    window.prependChallengeHistory = prependChallengeHistory;

    featureCards.forEach(card => {
      card.addEventListener("click", () => {
        switchFeature(card.dataset.feature);
      });
    });

    switchFeature("voice");
  </script>

  <script type="text/babel">
    const { useEffect, useMemo, useRef, useState } = React;

    (function () {
      const simulasiPercakapan = [
        {
          id: "wawancara",
          nama: "Simulasi Wawancara",
          persona: "Pewawancara Virtual",
          personaIcon: "👤",
          pesanPersona: "Selamat datang. Saya akan mengajukan beberapa pertanyaan rekrutmen. Jawablah seolah-olah Anda sedang dalam sesi interview nyata.",
          deskripsi: "Latihan menjawab pertanyaan wawancara kerja dengan jelas, percaya diri, dan terstruktur.",
          fokus: ["Struktur Jawaban", "Kejelasan Penyampaian", "Kepercayaan Diri", "Respons Spontan"],
          tips: ["Gunakan metode STAR (Situation, Task, Action, Result)", "Jaga kontak mata dengan kamera", "Hindari jawaban 'ya' atau 'tidak' saja"],
          pertanyaan: [
            "Ceritakan tentang diri Anda.",
            "Apa kelebihan terbesar Anda?",
            "Apa kelemahan terbesar Anda?",
            "Mengapa kami harus memilih Anda?",
            "Di mana Anda melihat diri Anda dalam lima tahun ke depan?",
            "Ceritakan pengalaman saat Anda menyelesaikan masalah.",
            "Bagaimana cara Anda bekerja dalam tekanan?",
            "Apa kontribusi yang bisa Anda berikan?"
          ]
        },
        {
          id: "seminar",
          nama: "Tanya Jawab Seminar",
          persona: "Moderator Seminar",
          personaIcon: "🎤",
          pesanPersona: "Terima kasih atas pemaparannya. Sekarang, saya akan menyampaikan beberapa pertanyaan dari audiens. Jawablah secara profesional.",
          deskripsi: "Latihan menjawab pertanyaan audiens setelah menyampaikan materi seminar.",
          fokus: ["Kedalaman Materi", "Etika Menjawab", "Kejelasan Argumen", "Manajemen Waktu"],
          tips: ["Apresiasi pertanyaan audiens", "Berikan jawaban yang edukatif", "Jika tidak tahu, sampaikan akan didiskusikan lanjut"],
          pertanyaan: [
            "Apa inti dari presentasi Anda?",
            "Mengapa topik ini penting?",
            "Apa solusi yang Anda tawarkan?",
            "Apa bukti bahwa solusi tersebut dapat diterapkan?",
            "Bagaimana cara mengukur keberhasilannya?",
            "Apa tantangan terbesar dari gagasan Anda?",
            "Bagaimana jika audiens tidak setuju dengan pendapat Anda?",
            "Apa langkah pertama yang perlu dilakukan?"
          ]
        },
        {
          id: "presentasi",
          nama: "Simulasi Presentasi",
          persona: "Audiens Virtual",
          personaIcon: "👥",
          pesanPersona: "Ide yang menarik. Namun kami memiliki beberapa pertanyaan kritis mengenai ide yang Anda sampaikan.",
          deskripsi: "Latihan mempertahankan ide presentasi melalui pertanyaan lanjutan.",
          fokus: ["Penguasaan Ide", "Ketangguhan Argumen", "Ketenangan", "Persuasi"],
          tips: ["Gunakan data untuk mendukung argumen", "Tetap tenang saat ditanya hal kritis", "Arahkan kembali ke manfaat utama ide Anda"],
          pertanyaan: [
            "Jelaskan ide utama presentasi Anda.",
            "Apa manfaat dari solusi yang Anda tawarkan?",
            "Bagaimana cara penerapannya?",
            "Siapa yang paling membutuhkan solusi ini?",
            "Apa risiko yang perlu diperhatikan?",
            "Mengapa pendekatan Anda lebih efektif?",
            "Apa data atau alasan yang mendukung ide Anda?",
            "Bagaimana Anda menutup presentasi dengan kuat?"
          ]
        },
        {
          id: "mc",
          nama: "Pembawa Acara",
          persona: "Koordinator Acara",
          personaIcon: "🎙️",
          pesanPersona: "Acara akan segera dimulai. Anda akan menghadapi situasi panggung yang dinamis. Jaga energi penonton tetap tinggi.",
          deskripsi: "Latihan merespons situasi acara sebagai MC secara ramah and profesional.",
          fokus: ["Energi & Vokal", "Kelikatan Bicara", "Keramahan", "Improvisasi"],
          tips: ["Mulai dengan senyum yang tulus", "Gunakan intonasi yang variatif", "Siap dengan kalimat cadangan jika ada kendala teknis"],
          pertanyaan: [
            "Buka acara seminar ini.",
            "Perkenalkan narasumber.",
            "Tutup acara secara profesional.",
            "Arahkan peserta menuju sesi tanya jawab.",
            "Isi jeda ketika narasumber belum siap.",
            "Sampaikan perubahan susunan acara dengan tenang.",
            "Ajak peserta memberi apresiasi kepada narasumber.",
            "Berikan pengumuman singkat sebelum acara selesai."
          ]
        },
        {
          id: "perkenalan",
          nama: "Networking",
          persona: "Rekan Profesional",
          personaIcon: "🤝",
          pesanPersona: "Senang bertemu Anda. Mari berkenalan lebih jauh mengenai latar belakang dan keahlian Anda.",
          deskripsi: "Latihan memperkenalkan diri dalam kegiatan formal, komunitas, atau dunia kerja.",
          fokus: ["Personal Branding", "Artikulasi Nama", "Kesan Pertama", "Relevansi Pengalaman"],
          tips: ["Sampaikan 'Elevator Pitch' Anda", "Hubungkan keahlian dengan kebutuhan lawan bicara", "Tanyakan balik untuk membangun koneksi"],
          pertanyaan: [
            "Perkenalkan diri Anda.",
            "Ceritakan latar belakang Anda.",
            "Apa tujuan Anda mengikuti kegiatan ini?",
            "Apa pengalaman yang paling relevan dengan bidang Anda?",
            "Apa kemampuan utama yang ingin Anda tunjukkan?",
            "Bagaimana Anda ingin dikenal oleh orang lain?",
            "Apa nilai yang Anda bawa dalam kerja sama?",
            "Apa target pengembangan diri Anda saat ini?"
          ]
        },
        {
          id: "diskusi",
          nama: "Diskusi Kelompok",
          persona: "Fasilitator Diskusi",
          personaIcon: "👨‍🏫",
          pesanPersona: "Kita perlu mencapai kesepakatan. Saya ingin mendengar kontribusi dan tanggapan Anda atas poin berikut.",
          deskripsi: "Latihan memberi respons dalam diskusi, menyanggah dengan sopan, dan merangkum pendapat.",
          fokus: ["Kerja Sama Tim", "Kesopanan", "Analisis Masalah", "Gaya Diplomatis"],
          tips: ["Gunakan kalimat 'Saya setuju, namun...' untuk menyanggah", "Berikan solusi, bukan hanya kritik", "Pastikan semua poin tersampaikan ringkas"],
          pertanyaan: [
            "Apa pendapat Anda tentang ide kelompok ini?",
            "Bagaimana Anda menanggapi pendapat yang berbeda?",
            "Apa usulan Anda agar diskusi lebih terarah?",
            "Bagaimana cara Anda menyampaikan ketidaksetujuan dengan sopan?",
            "Apa keputusan terbaik menurut Anda?",
            "Bagaimana Anda merangkum hasil diskusi?",
            "Apa peran yang bisa Anda ambil dalam kelompok?",
            "Bagaimana cara menjaga semua anggota tetap terlibat?"
          ]
        }
      ];

      const tingkatKesulitan = [
        { nama: "Pemula", jumlah: 3, persiapan: 30, jawab: 45, deskripsi: "3 pertanyaan, persiapan 30 detik, menjawab 45 detik." },
        { nama: "Menengah", jumlah: 5, persiapan: 15, jawab: 60, deskripsi: "5 pertanyaan, persiapan 15 detik, menjawab 60 detik." },
        { nama: "Lanjutan", jumlah: 8, persiapan: 8, jawab: 90, deskripsi: "8 pertanyaan, persiapan 8 detik, menjawab 90 detik." }
      ];

      function TantanganPercakapan() {
        const [simulasi, setSimulasi] = useState(simulasiPercakapan[0]);
        const [level, setLevel] = useState(tingkatKesulitan[0]);
        const [modeCepat, setModeCepat] = useState(false);
        const [view, setView] = useState("PREP"); // "PREP", "TRANSITION", "ACTIVE", "RESULT"
        const [pertanyaan, setPertanyaan] = useState([]);
        const [nomor, setNomor] = useState(0);
        const [fase, setFase] = useState("persiapan"); // "persiapan", "menjawab", "jawaban"
        const [sisaWaktu, setSisaWaktu] = useState(0);
        const [durasiJawaban, setDurasiJawaban] = useState([]);
        const [isSaving, setIsSaving] = useState(false);
        const [isSaved, setIsSaved] = useState(false);
        const [isSubmitted, setIsSubmitted] = useState(false);
        const [practiceHistoryId, setPracticeHistoryId] = useState(null);
        const [transitionCountdown, setTransitionCountdown] = useState(3);
        const [stepCountdown, setStepCountdown] = useState(0);
        const [lastDuration, setLastDuration] = useState(0);
        const [setupMessage, setSetupMessage] = useState("");
        const [showExitDialog, setShowExitDialog] = useState(false);

        const recorderRef = useRef(null);
        const streamRef = useRef(null);
        const chunksRef = useRef([]);
        const allAudioChunksRef = useRef([]);
        const fullRecorderRef = useRef(null);
        const fullStreamRef = useRef(null);

        const currentRules = modeCepat
          ? { nama: "Mode Respon Cepat", jumlah: 5, persiapan: 5, jawab: 30, deskripsi: "Pertanyaan acak, persiapan 5 detik, menjawab 30 detik." }
          : level;

        const progres = pertanyaan.length ? Math.min((durasiJawaban.length / pertanyaan.length) * 100, 100) : 0;
        const waktuTotal = durasiJawaban.reduce((t, v) => t + v, 0);

        const formatTime = (s) => {
          const mins = String(Math.floor(s / 60)).padStart(2, "0");
          const secs = String(s % 60).padStart(2, "0");
          return `${mins}:${secs}`;
        };

        const cancelSimulation = () => setShowExitDialog(true);

        const confirmExitSimulation = () => {
          setShowExitDialog(false);
          if (fullRecorderRef.current?.state === "recording") fullRecorderRef.current.stop();
          if (recorderRef.current?.state === "recording") recorderRef.current.stop();
          fullStreamRef.current?.getTracks().forEach(track => track.stop());
          streamRef.current?.getTracks().forEach(track => track.stop());
          setView("PREP");
        };

        const exitConfirmationModal = showExitDialog && (
          <div className="fixed inset-0 z-[10000] flex items-center justify-center bg-[#102A43]/55 p-5 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="exit-dialog-title">
            <div className="w-full max-w-md rounded-[28px] bg-white p-6 text-center shadow-2xl md:p-8">
              <div className="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#EAF5F7] text-2xl text-[#64C7D4]">?</div>
              <h2 id="exit-dialog-title" className="mt-5 text-2xl font-extrabold text-[#102A43]">Kembali ke menu?</h2>
              <p className="mt-2 text-sm font-semibold leading-6 text-[#667085]">Sesi yang sedang berjalan akan dibatalkan dan progresnya tidak disimpan.</p>
              <div className="mt-6 grid gap-3 sm:grid-cols-2">
                <button type="button" className="rounded-xl border border-[#d0d5dd] px-4 py-3 text-sm font-black text-[#344054] transition hover:bg-slate-50" onClick={() => setShowExitDialog(false)}>Lanjut Latihan</button>
                <button type="button" className="rounded-xl bg-[#102A43] px-4 py-3 text-sm font-black text-white transition hover:bg-[#1c316c]" onClick={confirmExitSimulation}>Ya, Kembali</button>
              </div>
            </div>
          </div>
        );

        const startSimulationFlow = async () => {
          const daftar = modeCepat
            ? [...simulasiPercakapan.flatMap(s => s.pertanyaan.map(t => ({ teks: t, asal: s.nama })))].sort(() => Math.random() - 0.5).slice(0, 5)
            : simulasi.pertanyaan.slice(0, level.jumlah).map(t => ({ teks: t, asal: simulasi.nama }));

          setSetupMessage("");
          allAudioChunksRef.current = [];
          try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            fullStreamRef.current = stream;
            const preferredMime = MediaRecorder.isTypeSupported("audio/webm") ? "audio/webm" : "";
            const recorder = new MediaRecorder(stream, preferredMime ? { mimeType: preferredMime } : {});
            recorder.ondataavailable = e => { if (e.data.size > 0) allAudioChunksRef.current.push(e.data); };
            fullRecorderRef.current = recorder;
            recorder.start();
          } catch (error) {
            setSetupMessage("Mikrofon harus diizinkan sebelum simulasi dimulai. Periksa izin browser lalu coba lagi.");
            return;
          }
          
          setPertanyaan(daftar);
          setNomor(0);
          setDurasiJawaban([]);
          setIsSaved(false);
          setIsSubmitted(false);
          setPracticeHistoryId(null);
          setTransitionCountdown(3);
          setView("TRANSITION");
          
          const interval = setInterval(() => {
            setTransitionCountdown(prev => {
              if (prev <= 1) {
                clearInterval(interval);
                mulaiTantangan();
                return 0;
              }
              return prev - 1;
            });
          }, 1000);
        };

        function mulaiTantangan() {
          setView("ACTIVE");
          setFase("persiapan");
          setSisaWaktu(modeCepat ? 5 : level.persiapan);
        }

        useEffect(() => {
          if (view !== "ACTIVE") return;
          if (fase === "jawaban") return;

          if (sisaWaktu <= 0) {
            if (fase === "persiapan") mulaiMenjawab();
            else if (fase === "menjawab") selesaiMenjawab(true);
            return;
          }

          const id = setTimeout(() => setSisaWaktu(s => Math.max(s - 1, 0)), 1000);
          return () => clearTimeout(id);
        }, [view, fase, sisaWaktu]);

        function mulaiMenjawab() {
          setFase("menjawab");
          setSisaWaktu(modeCepat ? 30 : level.jawab);
        }

        function selesaiMenjawab(otomatis = false) {
          const target = modeCepat ? 30 : level.jawab;
          const durasi = otomatis ? target : Math.max(target - sisaWaktu, 1);
          setDurasiJawaban(curr => [...curr, durasi]);
          setLastDuration(durasi);
          
          if (nomor + 1 >= pertanyaan.length) {
            if (fullRecorderRef.current && fullRecorderRef.current.state !== "inactive") fullRecorderRef.current.stop();
            setView("RESULT");
          } else {
            setFase("jawaban");
            setStepCountdown(1);
            const interval = setInterval(() => {
              setStepCountdown(prev => {
                if (prev <= 1) {
                  clearInterval(interval);
                  lanjutPertanyaan();
                  return 0;
                }
                return prev - 1;
              });
            }, 1000);
          }
        }

        function lanjutPertanyaan() {
          setNomor(n => n + 1);
          setFase("persiapan");
          setSisaWaktu(modeCepat ? 5 : level.persiapan);
        }

        async function simpanRiwayat() {
          if (!isLoggedIn) return;
          setIsSaving(true);
          const formData = new FormData();
          formData.append("action", "save_challenge");
          formData.append("challenge_type", modeCepat ? "Mode Respon Cepat" : simulasi.nama);
          formData.append("level_name", currentRules.nama);
          formData.append("question_count", pertanyaan.length);
          formData.append("prompt", pertanyaan.map((item, index) => `${index + 1}. ${item.teks}`).join("\n"));
          formData.append("prep_seconds", currentRules.persiapan);
          formData.append("speak_seconds", currentRules.jawab);
          formData.append("actual_seconds", waktuTotal);
          formData.append("score", 0);
          formData.append("completed", 1);
          if (allAudioChunksRef.current.length > 0) {
            formData.append("audio", new Blob(allAudioChunksRef.current, { type: "audio/webm" }), "challenge.webm");
          }

          try {
            const res = await fetch("Latihan.php", { method: "POST", body: formData });
            const data = await res.json();
            if (data.status) {
              setIsSaved(true);
              setPracticeHistoryId(data.practice_history_id);
              window.prependChallengeHistory?.(data.item);
            }
          } catch (e) {} finally { setIsSaving(false); }
        }

        async function submitToMentor() {
          if (!practiceHistoryId || !isLoggedIn) return;
          setIsSubmitted(false);
          const formData = new FormData();
          formData.append("action", "submit_to_mentor");
          formData.append("practice_history_id", practiceHistoryId);
          formData.append("feature_type", "challenge");
          try {
            const res = await fetch("Latihan.php", { method: "POST", body: formData });
            const data = await res.json();
            if (data.status) setIsSubmitted(true);
          } catch (e) {}
        }

        if (view === "TRANSITION") {
          return (
            <div className="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-[#102A43]/95 p-6 text-white backdrop-blur-xl">
              <button type="button" className="absolute left-6 top-6 rounded-xl border border-white/20 px-4 py-2 text-sm font-black text-white transition hover:bg-white/10" onClick={cancelSimulation}>← Kembali</button>
              <div className="text-center animate-in fade-in zoom-in duration-500">
                <div className="mb-4 text-sm font-black uppercase tracking-[0.3em] text-[#64C7D4]">Simulasi Akan Dimulai</div>
                <div className="text-[180px] font-black leading-none">{transitionCountdown > 0 ? transitionCountdown : 'MULAI'}</div>
                <div className="mt-8 space-y-2">
                  <div className="text-2xl font-black">{modeCepat ? "Mode Respon Cepat" : simulasi.nama}</div>
                  <div className="text-sm font-bold opacity-60">Level: {currentRules.nama} • {pertanyaan.length} Pertanyaan</div>
                </div>
              </div>
              {exitConfirmationModal}
            </div>
          );
        }

        if (view === "ACTIVE") {
          const aktif = pertanyaan[nomor];
          return (
            <div className="fixed inset-0 z-[9999] flex flex-col bg-[#f7f7fc]">
              <div className="border-b border-slate-100 bg-white px-4 py-4 md:px-8">
                <div className="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4">
                  <div className="flex items-center gap-3">
                    <button type="button" className="rounded-xl border border-slate-200 px-3 py-2 text-sm font-black text-[#102A43] transition hover:bg-slate-50" onClick={cancelSimulation}>← Kembali</button>
                    <div className="h-11 w-11 rounded-2xl bg-[#102A43] flex items-center justify-center text-white text-xl">{modeCepat ? '⚡' : simulasi.personaIcon}</div>
                    <div>
                      <div className="text-[10px] font-black uppercase tracking-widest text-[#64C7D4]">{modeCepat ? "Mode Respon Cepat" : simulasi.nama}</div>
                      <div className="text-base font-black text-[#102A43]">{simulasi.persona}</div>
                    </div>
                  </div>
                  <div className="flex items-center gap-5">
                  <div className="text-right">
                    <div className="text-[10px] font-black text-[#667085] uppercase tracking-wider">Progres</div>
                    <div className="text-base font-black text-[#102A43]">{nomor + 1} <span className="text-xs opacity-40">/ {pertanyaan.length}</span></div>
                  </div>
                  <div className="h-9 w-px bg-slate-100"></div>
                  <div className="text-right">
                    <div className="text-[10px] font-black text-[#667085] uppercase">{fase === "persiapan" ? "Persiapan" : "Bicara"}</div>
                    <div className={`text-3xl font-black tabular-nums ${fase === "menjawab" ? "text-red-500" : "text-[#102A43]"}`}>{formatTime(sisaWaktu)}</div>
                  </div>
                </div>
                </div>
              </div>

              <div className="h-1.5 w-full bg-slate-100">
                <div className="h-full bg-[#64C7D4] transition-all duration-500" style={{ width: `${progres}%` }}></div>
              </div>

              <main className="flex-1 overflow-y-auto p-5 md:p-8" style={{ width: '100%', marginLeft: 0 }}>
                <div className="mx-auto flex min-h-full max-w-5xl flex-col justify-center">
                  {fase === "jawaban" ? (
                    <div className="text-center animate-in fade-in zoom-in duration-300">
                      <div className="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-green-100 text-5xl text-green-600">✓</div>
                      <h2 className="text-3xl font-black text-[#102A43]">Jawaban Berhasil Disimpan</h2>
                      <p className="text-lg font-bold text-[#667085] mb-8">Durasi: {lastDuration} Detik</p>
                      <div className="text-sm font-black uppercase tracking-widest text-[#64C7D4]">Bersiap ke pertanyaan berikutnya</div>
                      <div className="text-6xl font-black text-[#102A43] mt-4">{stepCountdown}</div>
                    </div>
                  ) : (
                    <div className="w-full">
                      <div className="mb-8">
                        <div className="flex items-center gap-3 mb-4">
                          <div className="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-lg">{simulasi.personaIcon}</div>
                          <span className="text-sm font-black text-[#667085] uppercase tracking-widest">{simulasi.persona}</span>
                        </div>
                        <div className="bg-white p-6 md:p-10 rounded-[28px] rounded-tl-none shadow-sm border border-slate-100 relative">
                          <div className="text-2xl md:text-3xl font-bold text-[#102A43] leading-relaxed">{aktif.teks}</div>
                          <div className="absolute -left-3 top-0 w-6 h-6 bg-white border-l border-t border-slate-100 transform -rotate-45"></div>
                        </div>
                      </div>
                      
                      <div className="text-center">
                        <div className={`inline-flex items-center gap-3 rounded-full px-6 py-3 text-sm font-black transition-all ${fase === "menjawab" ? "bg-red-50 text-red-600 animate-pulse border border-red-100 shadow-lg shadow-red-500/10" : "bg-[#EAF5F7] text-[#64C7D4] border border-[#CDE5EA]"}`}>
                          {fase === "menjawab" ? <><span className="h-3 w-3 rounded-full bg-red-600"></span> SEDANG MEREKAM JAWABAN</> : "TAHAP PERSIAPAN"}
                        </div>
                        <p className="mt-5 text-base md:text-lg font-bold text-[#667085]">
                          {fase === "persiapan" ? "Gunakan waktu ini untuk menyusun poin jawaban Anda..." : "Bicaralah dengan tenang, jelas, and penuh percaya diri."}
                        </p>
                      </div>
                    </div>
                  )}

                  {fase === "persiapan" && (
                <aside className="mt-6 grid gap-4 md:grid-cols-2">
                  <div className="simulation-sidebar">
                    <h4><span>💡</span> Tips Menjawab</h4>
                    <div className="space-y-4">
                      {(simulasi.tips || ["Jawab dengan jelas", "Gunakan contoh nyata", "Jaga kontak mata"]).map((tip, i) => (
                        <div key={i} className="tip-item">
                          <span>✓</span> {tip}
                        </div>
                      ))}
                    </div>
                  </div>

                  <div className="simulation-sidebar">
                    <h4><span>🎯</span> Fokus Penilaian</h4>
                    <div className="flex flex-wrap gap-2">
                      {simulasi.fokus.map(f => (
                        <span key={f} className="bg-[#102A43] text-white text-[10px] font-black px-3 py-1.5 rounded-lg">✓ {f}</span>
                      ))}
                    </div>
                  </div>
                </aside>
                  )}
                </div>
              </main>

              <div className="bg-white border-t border-slate-100 px-5 py-4 md:px-8 flex justify-center gap-4">
                <button className="btn btn-muted px-7 font-black text-sm" onClick={cancelSimulation}>Kembali</button>
                {fase === "menjawab" && <button className="btn btn-danger px-16 py-5 text-xl font-black shadow-xl shadow-red-500/20" onClick={() => selesaiMenjawab(false)}>Selesai Menjawab</button>}
              </div>
              {exitConfirmationModal}
            </div>
          );
        }

        if (view === "RESULT") {
          return (
            <div className="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-[#102A43]/95 p-6 text-white backdrop-blur-xl">
              <div className="w-full max-w-2xl max-h-[90vh] overflow-y-auto no-scrollbar rounded-[40px] bg-white text-[#102A43] shadow-2xl">
                <div className="p-8 md:p-12 text-center">
                  <div className="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-green-100 text-5xl text-green-600 animate-bounce">✓</div>
                  <h2 className="text-4xl font-black tracking-tight mb-2">🎉 Simulasi Selesai!</h2>
                  <p className="text-lg font-bold text-[#667085] mb-8">Luar biasa! Kamu telah menyelesaikan tantangan {simulasi.nama}.</p>
                  
                  <div className="hidden flex items-center justify-center gap-4 mb-10">
                    <div className="xp-badge"><span>✨</span> +120 XP</div>
                    <div className="xp-badge" style={{ background: '#ecfdf3', borderColor: '#abefc6', color: '#027a48' }}><span>🔥</span> Streak 7 Hari</div>
                  </div>

                  <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                    <div className="rounded-[32px] bg-[#f8fafc] border border-[#EAF5F7] p-8 text-left">
                      <div className="topic-label mb-6 text-center">Statistik Sesi</div>
                      <div className="grid grid-cols-2 gap-4">
                        <div className="score-item border-none bg-white p-4 rounded-2xl shadow-sm text-center">
                          <span className="text-[10px] uppercase font-black text-[#667085]">Pertanyaan</span>
                          <strong className="text-xl">{pertanyaan.length}</strong>
                        </div>
                        <div className="score-item border-none bg-white p-4 rounded-2xl shadow-sm text-center">
                          <span className="text-[10px] uppercase font-black text-[#667085]">Durasi</span>
                          <strong className="text-xl">{waktuTotal}s</strong>
                        </div>
                        <div className="score-item border-none bg-white p-4 rounded-2xl shadow-sm text-center">
                          <span className="text-[10px] uppercase font-black text-[#667085]">Tingkat</span>
                          <strong className="text-lg">{currentRules.nama}</strong>
                        </div>
                        <div className="score-item border-none bg-white p-4 rounded-2xl shadow-sm text-center">
                          <span className="text-[10px] uppercase font-black text-[#667085]">Status</span>
                          <strong className="text-xl text-[#027a48]">Selesai</strong>
                        </div>
                      </div>
                    </div>
                    
                    <div className="rounded-[32px] bg-[#102A43] p-8 text-left text-white shadow-xl shadow-[#102A43]/20">
                      <div className="text-sm font-black text-[#64C7D4] mb-4 uppercase tracking-widest">Evaluasi Mandiri</div>
                      <p className="text-sm font-semibold leading-6 text-white/75">Dengarkan ulang rekamanmu untuk menilai struktur jawaban, kejelasan suara, dan ketenangan saat menjawab.</p>
                    </div>
                  </div>

                  <div className="flex flex-col gap-4">
                    <button className="btn btn-primary py-5 text-xl font-black shadow-xl shadow-[#64C7D4]/20" onClick={simpanRiwayat} disabled={isSaving || isSaved}>
                      {isSaving ? 'Menyimpan...' : isSaved ? '✓ Tersimpan di Riwayat' : '💾 Simpan & Selesaikan'}
                    </button>
                    {isSaved && (
                      <button className="btn py-5 text-xl font-black text-white shadow-xl shadow-green-500/20 transition-all hover:scale-[1.02]" style={{ background: '#027a48' }} onClick={submitToMentor} disabled={isSubmitted}>
                        {isSubmitted ? '✓ Terkirim ke Mentor' : '📤 Kirim ke Mentor'}
                      </button>
                    )}
                    <button className="btn btn-muted py-4 font-black text-sm" onClick={() => setView("PREP")}>Kembali ke Menu Utama</button>
                  </div>
                </div>
              </div>
            </div>
          );
        }

        return (
          <div className="space-y-6">
            <section className="camera-card p-6">
              <div className="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                  <div className="text-sm font-black uppercase tracking-wide text-[#64C7D4]">Tantangan Bicara</div>
                  <h2 className="mt-2 text-3xl font-extrabold text-[#102A43]">Latihan menjawab spontan</h2>
                  <p className="mt-2 max-w-2xl text-sm font-semibold leading-6 text-[#667085]">Pilih situasi, tentukan level, lalu jawab pertanyaan dengan mikrofon. Rekamanmu akan disimpan setelah tantangan selesai.</p>
                </div>
                <div className="shrink-0 whitespace-nowrap rounded-full bg-[#EAF5F7] px-4 py-2 text-xs font-black uppercase tracking-wide text-[#245B7A] ring-1 ring-[#CDE5EA]">3 langkah mudah</div>
              </div>
            </section>

            <section>
              <div className="mb-3 flex items-center justify-between gap-4">
                <div>
                  <div className="text-sm font-black uppercase tracking-wide text-[#64C7D4]">Pilih simulasi</div>
                  <p className="mt-1 text-sm font-semibold text-[#667085]">Pilih situasi yang ingin kamu latih.</p>
                </div>
                {modeCepat && <span className="rounded-full bg-[#64C7D4] px-3 py-1.5 text-[10px] font-black text-white">RESPON CEPAT AKTIF</span>}
              </div>
              <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                {simulasiPercakapan.map((item) => (
                  <button
                    key={item.id}
                    type="button"
                    className={`camera-card interactive p-5 text-left transition ${!modeCepat && simulasi.id === item.id ? "border-[#64C7D4] bg-[#EAF5F7]" : ""}`}
                    onClick={() => { setSimulasi(item); setModeCepat(false); }}
                  >
                    <div className="text-2xl">{item.personaIcon}</div>
                    <h3 className="mt-3 text-xl font-extrabold text-[#102A43]">{item.nama}</h3>
                    <p className="mt-2 text-sm font-semibold leading-6 text-[#667085]">{item.deskripsi}</p>
                  </button>
                ))}
                <button type="button" className={`camera-card interactive p-5 text-left transition ${modeCepat ? "border-[#64C7D4] bg-[#EAF5F7]" : ""}`} onClick={() => setModeCepat(true)}>
                  <div className="text-2xl">⚡</div>
                  <h3 className="mt-3 text-xl font-extrabold text-[#102A43]">Respon Cepat</h3>
                  <p className="mt-2 text-sm font-semibold leading-6 text-[#667085]">Jawab pertanyaan acak untuk melatih spontanitas dalam waktu singkat.</p>
                </button>
              </div>
            </section>

            <section className="camera-card overflow-hidden">
              <div className="space-y-6 p-6">
                <div>
                  <div className="text-sm font-black uppercase tracking-wide text-[#64C7D4]">Tantangan yang dipilih</div>
                  <div className="mt-3 flex items-center gap-4">
                    <div className="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#102A43] text-2xl text-white">{modeCepat ? '⚡' : simulasi.personaIcon}</div>
                    <div>
                      <h3 className="text-2xl font-extrabold text-[#102A43]">{modeCepat ? "Tantangan Respon Cepat" : simulasi.nama}</h3>
                      <p className="mt-1 text-sm font-semibold leading-6 text-[#667085]">{modeCepat ? "Pertanyaan akan dipilih secara acak saat latihan dimulai." : simulasi.pesanPersona}</p>
                    </div>
                  </div>
                  <div className="mt-5 grid grid-cols-3 gap-3">
                    <div className="min-w-0 rounded-2xl bg-[#f8fafc] px-3 py-3 text-center ring-1 ring-[#e5e7eb]"><span className="block text-[10px] font-black uppercase tracking-wide text-[#667085]">Soal</span><strong className="mt-1 block text-lg text-[#102A43]">{currentRules.jumlah}</strong></div>
                    <div className="min-w-0 rounded-2xl bg-[#f8fafc] px-3 py-3 text-center ring-1 ring-[#e5e7eb]"><span className="block text-[10px] font-black uppercase tracking-wide text-[#667085]">Siapkan</span><strong className="mt-1 block text-lg text-[#102A43]">{currentRules.persiapan} dtk</strong></div>
                    <div className="min-w-0 rounded-2xl bg-[#f8fafc] px-3 py-3 text-center ring-1 ring-[#e5e7eb]"><span className="block text-[10px] font-black uppercase tracking-wide text-[#667085]">Jawab</span><strong className="mt-1 block text-lg text-[#102A43]">{currentRules.jawab} dtk</strong></div>
                  </div>
                </div>
                <div className="rounded-[18px] border border-[#e5e7eb] bg-[#fbfbfd] p-5">
                  <div className="text-sm font-extrabold text-[#667085]">Tingkat Kesulitan</div>
                  <div className="mt-3 grid gap-2 md:grid-cols-3">
                    {tingkatKesulitan.map((item) => (
                      <button key={item.nama} type="button" className={`rounded-xl px-4 py-3 text-left text-sm font-extrabold transition ${level.nama === item.nama ? "bg-[#102A43] text-white shadow-lg shadow-[#102A43]/20" : "bg-white text-[#344054] ring-1 ring-[#d0d5dd]"}`} onClick={() => setLevel(item)}>
                        <span className="block">{item.nama}</span>
                        <span className={`mt-1 block text-xs font-semibold ${level.nama === item.nama ? "text-white/70" : "text-[#667085]"}`}>{item.deskripsi}</span>
                      </button>
                    ))}
                  </div>
                </div>
              </div>
              <div className="flex flex-col gap-4 border-t border-[#e5e7eb] p-6 lg:flex-row lg:items-center lg:justify-between">
                <p className="text-sm font-semibold leading-6 text-[#667085]">Pastikan mikrofon aktif. Kamu akan diberi waktu untuk berpikir sebelum mulai menjawab.</p>
                <button className="btn btn-primary shrink-0 px-8 py-4 text-lg font-black shadow-xl shadow-[#64C7D4]/20" onClick={startSimulationFlow}>Mulai Tantangan</button>
              </div>
            </section>
          </div>
        );
      }

      ReactDOM.createRoot(document.getElementById("conversation-challenge-root")).render(<TantanganPercakapan />);
    })();
  </script>

  <script type="text/babel">
    const { useEffect: useEff, useMemo: useMem, useRef: useRefC, useState: useSt } = React;

    const cameraTopics = [
      "Ceritakan pengalaman paling berkesan saat berbicara di depan orang.",
      "Jelaskan mengapa eye contact penting dalam public speaking.",
      "Presentasikan ide kegiatan kelas dalam satu menit.",
      "Ceritakan cara kamu membangun rasa percaya diri.",
      "Berikan opini singkat tentang pentingnya bahasa tubuh.",
      "Simulasikan pembukaan sebagai MC acara sekolah."
    ];

    const cameraDurations = [
      { label: "1 menit", seconds: 60 },
      { label: "3 menit", seconds: 180 },
      { label: "5 menit", seconds: 300 }
    ];

    const cameraHistoryFromDB = <?= json_encode($cameraHistory, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    const cameraMentorInfo = <?= json_encode($mentorInfo['camera'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

    const simulationModes = [
      {
        name: "Presentasi",
        objective: "Sampaikan ide utama dengan pembuka, tiga poin, dan penutup yang jelas.",
        tip: "Gunakan gestur tangan saat berpindah poin."
      },
      {
        name: "Wawancara",
        objective: "Jawab dengan struktur singkat: situasi, tindakan, dan hasil.",
        tip: "Tatap kamera seperti sedang melihat pewawancara."
      },
      {
        name: "Pembukaan MC",
        objective: "Buka acara dengan salam, perkenalan, dan energi yang ramah.",
        tip: "Mulai dengan senyum dan intonasi naik di kalimat pembuka."
      }
    ];

    const focusItems = ["Kontak mata", "Ekspresi wajah", "Bahasa tubuh", "Kepercayaan diri"];

    function formatCameraTime(seconds) {
      const mins = String(Math.floor(seconds / 60)).padStart(2, "0");
      const secs = String(seconds % 60).padStart(2, "0");
      return `${mins}:${secs}`;
    }

    function CameraPracticeDashboard() {
      const liveVideoRef = useRefC(null);
      const replayVideoRef = useRefC(null);
      const mediaRecorderRef = useRefC(null);
      const streamRef = useRefC(null);
      const chunksRef = useRefC([]);
      const timerRef = useRefC(null);
      const elapsedRef = useRefC(0);
      const recordedBlobRef = useRefC(null);
      const processedStreamRef = useRefC(null);
      const frameRequestRef = useRefC(null);

      const [selectedDuration, setSelectedDuration] = useSt(60);
      const [topic, setTopic] = useSt(cameraTopics[0]);
      const [isRecording, setIsRecording] = useSt(false);
      const [shouldFlipCamera, setShouldFlipCamera] = useSt(true);
      const [elapsed, setElapsed] = useSt(0);
      const [recordedUrl, setRecordedUrl] = useSt("");
      const [statusMessage, setStatusMessage] = useSt("Camera dan microphone akan aktif saat latihan dimulai.");
      // Build initial history from DB records
      const dbHistory = cameraHistoryFromDB.map((item) => ({
        id: `db-${item.id}`,
        dbId: item.id,
        topic: item.topic,
        date: new Date(item.created_at.replace(" ", "T")).toLocaleString("id-ID", { dateStyle: "medium", timeStyle: "short" }),
        duration: item.duration_seconds,
        videoUrl: item.video_path || "",
        confidence: item.simulation_mode || "Practice"
      }));
      const [history, setHistory] = useSt(dbHistory);
      const [activeMode, setActiveMode] = useSt(simulationModes[0]);
      const [focusProgress, setFocusProgress] = useSt({
        "Eye contact": false,
        "Facial expression": false,
        "Body language": false,
        "Confidence": false
      });
      const [cameraReady, setCameraReady] = useSt(false);
      const [micReady, setMicReady] = useSt(false);
      const [isSaving, setIsSaving] = useSt(false);
      const [isSaved, setIsSaved] = useSt(false);
      const [practiceHistoryId, setPracticeHistoryId] = useSt(null);
      const [isSubmitted, setIsSubmitted] = useSt(false);

      const remainingTime = Math.max(selectedDuration - elapsed, 0);
      const progress = Math.min((elapsed / selectedDuration) * 100, 100);
      const recordingStatus = isRecording ? "SEDANG MEREKAM" : recordedUrl ? "SELESAI" : cameraReady ? "KAMERA SIAP" : "SIAP";
      const practiceStep = recordedUrl ? 3 : (cameraReady || isRecording ? 2 : 1);
      const practiceStepLabel = recordedUrl ? "Tinjau hasil" : (cameraReady || isRecording ? "Rekam latihan" : "Siapkan latihan");
      const speakingTip = isRecording
        ? activeMode.tip
        : "Siapkan posisi kamera sejajar mata dan pastikan bahu terlihat natural.";

      const randomTopic = () => {
        const nextTopics = cameraTopics.filter((item) => item !== topic);
        const nextTopic = nextTopics[Math.floor(Math.random() * nextTopics.length)] || cameraTopics[0];
        setTopic(nextTopic);
      };

      const attachStream = (stream) => {
        streamRef.current = stream;
        setCameraReady(stream.getVideoTracks().length > 0);
        setMicReady(stream.getAudioTracks().length > 0);
        if (liveVideoRef.current) {
          liveVideoRef.current.srcObject = stream;
        }
      };

      const cameraConstraints = {
        video: {
          facingMode: "user",
          width: { ideal: 1280 },
          height: { ideal: 720 },
          aspectRatio: { ideal: 16 / 9 }
        },
        audio: true
      };

      const startPreview = async () => {
        if (streamRef.current) return;

        try {
          const stream = await navigator.mediaDevices.getUserMedia(cameraConstraints);
          attachStream(stream);
          const portraitScreen = window.innerHeight > window.innerWidth;
          setStatusMessage(portraitScreen
            ? "Kamera siap. Putar perangkat ke posisi horizontal agar wajah dan gestur terlihat lebih jelas."
            : "Kamera siap. Periksa posisi wajah, bahu, dan pencahayaan sebelum mulai merekam.");
        } catch (error) {
          setStatusMessage("Camera atau microphone tidak bisa diakses. Periksa izin browser.");
        }
      };

      const createUnmirroredRecordingStream = async (stream) => {
        const preview = liveVideoRef.current;
        const videoTrack = stream.getVideoTracks()[0];
        if (!preview || !videoTrack || !HTMLCanvasElement.prototype.captureStream) return stream;

        try {
          await preview.play();
          const settings = videoTrack.getSettings();
          const width = preview.videoWidth || settings.width || 1280;
          const height = preview.videoHeight || settings.height || 720;
          const canvas = document.createElement("canvas");
          canvas.width = width;
          canvas.height = height;
          const context = canvas.getContext("2d");
          if (!context) return stream;

          const drawFrame = () => {
            context.save();
            if (shouldFlipCamera) {
              context.setTransform(-1, 0, 0, 1, width, 0);
            } else {
              context.setTransform(1, 0, 0, 1, 0, 0);
            }
            context.drawImage(preview, 0, 0, width, height);
            context.restore();
            frameRequestRef.current = requestAnimationFrame(drawFrame);
          };
          drawFrame();

          const canvasStream = canvas.captureStream(30);
          const recordingStream = new MediaStream([
            ...canvasStream.getVideoTracks(),
            ...stream.getAudioTracks()
          ]);
          processedStreamRef.current = canvasStream;
          return recordingStream;
        } catch (error) {
          return stream;
        }
      };

      const stopProcessedStream = () => {
        if (frameRequestRef.current) {
          cancelAnimationFrame(frameRequestRef.current);
          frameRequestRef.current = null;
        }
        if (processedStreamRef.current) {
          processedStreamRef.current.getTracks().forEach((track) => track.stop());
          processedStreamRef.current = null;
        }
      };

      const stopTracks = () => {
        stopProcessedStream();
        if (streamRef.current) {
          streamRef.current.getTracks().forEach((track) => track.stop());
          streamRef.current = null;
        }
        if (liveVideoRef.current) liveVideoRef.current.srcObject = null;
        setCameraReady(false);
        setMicReady(false);
      };

      const stopRecording = () => {
        clearInterval(timerRef.current);
        timerRef.current = null;
        setIsRecording(false);

        if (mediaRecorderRef.current && mediaRecorderRef.current.state !== "inactive") {
          mediaRecorderRef.current.stop();
        } else {
          stopTracks();
        }
      };

      const startRecording = async () => {
        try {
          let stream = streamRef.current;
          if (!stream) {
            stream = await navigator.mediaDevices.getUserMedia(cameraConstraints);
            attachStream(stream);
          }
          chunksRef.current = [];
          recordedBlobRef.current = null;
          setRecordedUrl("");
          setElapsed(0);
          elapsedRef.current = 0;
          setIsSaved(false);
          setIsSubmitted(false);
          setPracticeHistoryId(null);

          const preferredMime = MediaRecorder.isTypeSupported("video/webm;codecs=vp9,opus")
            ? "video/webm;codecs=vp9,opus"
            : MediaRecorder.isTypeSupported("video/webm")
              ? "video/webm"
              : "";
          const recordingStream = await createUnmirroredRecordingStream(stream);
          const recorder = preferredMime ? new MediaRecorder(recordingStream, { mimeType: preferredMime }) : new MediaRecorder(recordingStream);
          mediaRecorderRef.current = recorder;

          recorder.ondataavailable = (event) => {
            if (event.data.size > 0) chunksRef.current.push(event.data);
          };

          recorder.onstop = () => {
            const blob = new Blob(chunksRef.current, { type: preferredMime || "video/webm" });
            recordedBlobRef.current = blob;
            const videoUrl = URL.createObjectURL(blob);
            setRecordedUrl(videoUrl);
            stopProcessedStream();
            stopTracks();
            setStatusMessage("Recording selesai. Simpan riwayat untuk menyimpan video ke server.");
          };

          recorder.start();
          setIsRecording(true);
          setStatusMessage("Recording berjalan. Jaga eye contact dan bahasa tubuh.");

          timerRef.current = setInterval(() => {
            setElapsed((current) => {
              const next = current + 1;
              elapsedRef.current = Math.min(next, selectedDuration);
              if (next >= selectedDuration) setTimeout(stopRecording, 0);
              return Math.min(next, selectedDuration);
            });
          }, 1000);
        } catch (error) {
          setStatusMessage("Camera atau microphone tidak bisa diakses. Periksa izin browser.");
        }
      };

      const saveCameraPractice = async (sendToMentor = false) => {
        if (!recordedBlobRef.current) {
          setStatusMessage("Belum ada rekaman video yang bisa disimpan.");
          return;
        }
        if (!isLoggedIn) {
          setStatusMessage("Silakan login terlebih dahulu agar riwayat tersimpan ke akun.");
          return;
        }

        setIsSaving(true);
        setStatusMessage("Menyimpan video ke server...");

        try {
          const formData = new FormData();
          formData.append("action", "save_camera_practice");
          formData.append("topic", topic);
          formData.append("simulation_mode", activeMode.name);
          formData.append("duration", elapsedRef.current || selectedDuration);
          formData.append("video", recordedBlobRef.current, "camera_practice.webm");

          const response = await fetch("Latihan.php", { method: "POST", body: formData });
          const data = await response.json();

          if (!data.status) {
            setStatusMessage(data.message || "Gagal menyimpan riwayat camera practice.");
            setIsSaving(false);
            return;
          }

          setIsSaved(true);
          setPracticeHistoryId(data.practice_history_id || null);

          // Add to history list with server path
          const newItem = {
            id: `db-${data.practice_history_id}`,
            dbId: data.practice_history_id,
            topic: data.item.topic,
            date: new Date().toLocaleString("id-ID", { dateStyle: "medium", timeStyle: "short" }),
            duration: data.item.duration_seconds,
            videoUrl: data.item.video_path,
            confidence: data.item.simulation_mode || "Practice"
          };
          setHistory((current) => [newItem, ...current]);
          if (!sendToMentor) {
            setStatusMessage(data.message);
            return;
          }

          setStatusMessage("Menyimpan berhasil. Mengirim latihan ke mentor...");
          const submitForm = new FormData();
          submitForm.append("action", "submit_to_mentor");
          submitForm.append("practice_history_id", data.practice_history_id);
          submitForm.append("feature_type", "camera");
          const submitResponse = await fetch("Latihan.php", { method: "POST", body: submitForm });
          const submitData = await submitResponse.json();
          if (!submitData.status) {
            setStatusMessage(submitData.message || "Rekaman tersimpan, tetapi gagal dikirim ke mentor.");
            return;
          }

          setIsSubmitted(true);
          setStatusMessage(submitData.message);
        } catch (error) {
          setStatusMessage(sendToMentor ? "Rekaman mungkin sudah tersimpan, tetapi pengiriman ke mentor gagal." : "Terjadi kesalahan saat menyimpan riwayat camera practice.");
        } finally {
          setIsSaving(false);
        }
      };

      const submitToMentor = async () => {
        if (!practiceHistoryId) {
          setStatusMessage("Simpan riwayat camera practice terlebih dahulu.");
          return;
        }
        if (!isLoggedIn) {
          setStatusMessage("Silakan login terlebih dahulu.");
          return;
        }

        setStatusMessage("Mengirim latihan ke mentor...");

        try {
          const formData = new FormData();
          formData.append("action", "submit_to_mentor");
          formData.append("practice_history_id", practiceHistoryId);
          formData.append("feature_type", "camera");

          const response = await fetch("Latihan.php", { method: "POST", body: formData });
          const data = await response.json();

          if (!data.status) {
            setStatusMessage(data.message || "Gagal mengirim ke mentor.");
            return;
          }

          setIsSubmitted(true);
          setStatusMessage(data.message);
        } catch (error) {
          setStatusMessage("Terjadi kesalahan saat mengirim ke mentor.");
        }
      };

      const saveAndSubmitCameraPractice = async () => {
        if (isSaved && practiceHistoryId) {
          await submitToMentor();
          return;
        }
        await saveCameraPractice(true);
      };

      const replayVideo = (videoUrl) => {
        if (!videoUrl) return;
        setRecordedUrl(videoUrl);
        setTimeout(() => replayVideoRef.current?.play(), 100);
      };

      useEff(() => {
        return () => {
          clearInterval(timerRef.current);
          stopTracks();
        };
      }, []);

      const instruction = useMem(() => {
        if (isRecording) return "Bicara menghadap kamera, jaga gestur tetap natural, dan gunakan jeda yang jelas.";
        if (cameraReady) return "Periksa posisi wajah dan bahu pada preview, lalu mulai rekam saat sudah siap.";
        return "Pilih mode, topik, dan durasi. Aktifkan kamera untuk mengecek posisi sebelum merekam.";
      }, [isRecording, cameraReady]);

      const toggleFocus = (item) => {
        setFocusProgress((current) => ({ ...current, [item]: !current[item] }));
      };

      return (
        <div className="space-y-6">
          <section className="camera-card p-6">
            <div className="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
              <div>
                <div className="text-sm font-black uppercase tracking-wide text-[#64C7D4]">Langkah {practiceStep} · {practiceStepLabel}</div>
                <h2 className="mt-2 text-3xl font-extrabold text-[#102A43]">Latihan dengan webcam</h2>
                <p className="mt-2 text-sm font-semibold leading-6 text-[#667085]">Rekam latihan horizontal untuk mengecek ekspresi, kontak mata, dan bahasa tubuhmu.</p>
              </div>
              <div className="flex flex-wrap gap-3">
                {cameraMentorInfo && <div className="camera-status-pill live">Mentor: {cameraMentorInfo.name}</div>}
                <button type="button" className="camera-btn btn-muted-camera" onClick={randomTopic}>
                  Topik Acak
                </button>
              </div>
            </div>
          </section>

          <section className="grid gap-4 md:grid-cols-3">
            {simulationModes.map((mode) => (
              <button
                key={mode.name}
                type="button"
                onClick={() => setActiveMode(mode)}
                className={`camera-card interactive p-5 text-left transition ${activeMode.name === mode.name ? "border-[#64C7D4] bg-[#EAF5F7]" : ""}`}
              >
                <div className="text-sm font-black uppercase tracking-wide text-[#64C7D4]">Mode latihan</div>
                <h3 className="mt-2 text-xl font-extrabold text-[#102A43]">{mode.name}</h3>
                <p className="mt-2 text-sm font-semibold leading-6 text-[#667085]">{mode.objective}</p>
              </button>
            ))}
          </section>

          <section className={`grid gap-6 ${recordedUrl ? "xl:grid-cols-[minmax(0,1.55fr)_minmax(300px,0.55fr)]" : ""}`}>
            <div className="camera-card overflow-hidden">
              <div className="border-b border-[#e5e7eb] p-6">
                <div className="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                  <div>
                    <div className="text-sm font-black uppercase tracking-wide text-[#64C7D4]">Topik latihan</div>
                    <h3 className="mt-2 text-2xl font-extrabold leading-snug text-[#102A43]">{topic}</h3>
                  </div>
                  <div className="flex flex-wrap items-center gap-2">
                    <div className={`camera-status-pill ${cameraReady ? "live" : ""}`}>
                      <span className={cameraReady ? "recording-dot" : ""}></span>
                      Kamera
                    </div>
                    <div className={`camera-status-pill ${micReady ? "live" : ""}`}>
                      Mikrofon {micReady ? "siap" : "standby"}
                    </div>
                    <div className={`camera-status-pill ${isRecording ? "live" : ""}`}>
                      {isRecording && <span className="recording-dot"></span>}
                      {recordingStatus}
                    </div>
                  </div>
                </div>
              </div>

              <div className="grid gap-6 p-6 lg:grid-cols-[minmax(0,1fr)_240px]">
                <div className={`relative overflow-hidden rounded-[18px] bg-[#102A43] transition duration-300 ${isRecording ? "recording-glow" : ""}`}>
                  <video ref={liveVideoRef} className="aspect-video w-full bg-[#102A43] object-contain" style={{ transform: shouldFlipCamera ? 'scaleX(-1)' : 'none' }} autoPlay muted playsInline></video>
                  <div className="pointer-events-none absolute left-4 top-4 flex items-center gap-2 rounded-full bg-black/35 px-3 py-2 text-xs font-black uppercase tracking-wide text-white">
                    {isRecording && <span className="recording-dot"></span>}
                    {recordingStatus}
                  </div>
                  <div className="pointer-events-none absolute right-4 top-4 hidden rounded-full bg-black/35 px-3 py-2 text-xs font-black uppercase tracking-wide text-white sm:block">
                    {activeMode.name} Mode
                  </div>
                  <div className={`audio-wave ${isRecording ? "active" : ""} pointer-events-none absolute bottom-4 left-4 rounded-full bg-black/35 px-3 py-2`}>
                    <span></span><span></span><span></span><span></span><span></span>
                  </div>
                  <div className="pointer-events-none absolute bottom-4 right-4 hidden rounded-full bg-black/35 px-3 py-2 text-xs font-black uppercase tracking-wide text-white sm:block">
                    Format horizontal 16:9
                  </div>
                </div>

                <div className="space-y-4">
                  <div className="rounded-[18px] border border-[#e5e7eb] bg-[#fbfbfd] p-5">
                    <div className="text-sm font-extrabold text-[#667085]">Timer latihan</div>
                    <div className="mt-2 text-6xl font-black tracking-tight text-[#102A43]">{formatCameraTime(remainingTime)}</div>
                    <div className="mt-4 h-3 overflow-hidden rounded-full bg-[#e5e7eb]">
                      <div className="h-full rounded-full bg-[#64C7D4] transition-all" style={{ width: `${progress}%` }}></div>
                    </div>
                    <div className="mt-3 text-xs font-black uppercase tracking-wide text-[#64C7D4]">{recordingStatus}</div>
                  </div>

                  <div className="rounded-[18px] border border-[#e5e7eb] bg-[#fbfbfd] p-5">
                    <div className="mb-3 text-sm font-extrabold text-[#667085]">Durasi</div>
                    <div className="flex flex-wrap gap-2">
                      {cameraDurations.map((duration) => (
                        <button
                          key={duration.seconds}
                          type="button"
                          disabled={isRecording}
                          onClick={() => {
                            setSelectedDuration(duration.seconds);
                            setElapsed(0);
                          }}
                          className={`rounded-xl px-4 py-2 text-sm font-extrabold transition ${
                            selectedDuration === duration.seconds
                              ? "bg-[#64C7D4] text-white"
                              : "bg-white text-[#344054] ring-1 ring-[#d0d5dd]"
                          }`}
                        >
                          {duration.label}
                        </button>
                      ))}
                    </div>
                  </div>
                </div>
              </div>

              <div className="flex flex-col gap-4 border-t border-[#e5e7eb] p-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                  <div className="text-sm font-black uppercase tracking-wide text-[#64C7D4]">Langkah latihan</div>
                  <p className="mt-1 max-w-2xl text-sm font-semibold leading-6 text-[#667085]">{instruction}</p>
                  <div className="mt-3 grid gap-2 md:grid-cols-2">
                    <div className="rounded-2xl bg-[#f8fafc] px-4 py-3 text-sm font-bold text-[#344054] ring-1 ring-[#e5e7eb]">
                      Tujuan: {activeMode.objective}
                    </div>
                    <div className="rounded-2xl bg-[#EAF5F7] px-4 py-3 text-sm font-bold text-[#245B7A] ring-1 ring-[#CDE5EA]">
                      Tips: {speakingTip}
                    </div>
                  </div>
                </div>
                <div className="hidden flex-wrap gap-3">
                  <button type="button" className="camera-btn btn-primary-camera start-recording-cta" onClick={startRecording} disabled={isRecording}>Start Recording</button>
                  <button type="button" className="camera-btn btn-danger-camera" onClick={stopRecording} disabled={!isRecording}>Stop Recording</button>
                  <button type="button" className="camera-btn btn-primary-camera" onClick={saveCameraPractice} disabled={!recordedUrl || isRecording || isSaving || isSaved} style={{ background: '#102A43' }}>💾 Simpan Riwayat</button>
                  <button type="button" className="camera-btn btn-primary-camera" onClick={submitToMentor} disabled={!isSaved || isSubmitted} style={{ background: '#027a48' }}>📤 Kirim ke Mentor</button>
                </div>
                <div className="flex flex-wrap gap-3">
                  {!cameraReady && !recordedUrl && (
                    <button type="button" className="camera-btn btn-primary-camera start-recording-cta" onClick={startPreview}>Aktifkan Kamera</button>
                  )}
                  {cameraReady && !isRecording && !recordedUrl && (
                    <>
                      <button type="button" className="camera-btn btn-muted-camera" onClick={() => setShouldFlipCamera((current) => !current)}>
                        {shouldFlipCamera ? 'Gunakan Tampilan Asli' : 'Balik Tampilan'}
                      </button>
                      <button type="button" className="camera-btn btn-primary-camera start-recording-cta" onClick={startRecording}>Mulai Rekam</button>
                    </>
                  )}
                  {isRecording && (
                    <button type="button" className="camera-btn btn-danger-camera" onClick={stopRecording}>Selesai Rekam</button>
                  )}
                  {recordedUrl && !isRecording && (
                    <>
                      <button type="button" className="camera-btn btn-muted-camera" onClick={startRecording}>Rekam Ulang</button>
                      <button type="button" className="camera-btn btn-primary-camera" onClick={saveAndSubmitCameraPractice} disabled={isSaving || isSubmitted} style={{ background: '#027a48' }}>
                        {isSaving ? 'Menyimpan...' : isSubmitted ? '✓ Terkirim ke Mentor' : 'Simpan & Kirim ke Mentor'}
                      </button>
                    </>
                  )}
                </div>
              </div>

              {/* Status toast */}
              <div className="px-6 pb-6">
                <div className={`rounded-2xl px-4 py-3 text-sm font-bold ${statusMessage.includes("berhasil") || statusMessage.includes("dikirim") ? "bg-[#ecfdf3] text-[#027a48]" : statusMessage.includes("Gagal") || statusMessage.includes("kesalahan") || statusMessage.includes("tidak bisa") ? "bg-[#fef3f2] text-[#b42318]" : "bg-[#eff8ff] text-[#175cd3]"}`}>
                  {statusMessage}
                </div>
              </div>
            </div>

            <aside className="space-y-6">
              {recordedUrl && (
                <>
              <div className="camera-card p-6">
                <h2 className="text-2xl font-extrabold text-[#102A43]">Replay Result</h2>
                <p className="mt-2 text-sm font-semibold leading-6 text-[#667085]">{recordedUrl ? "Putar ulang hasil rekaman terakhir." : "Hasil recording akan muncul di sini."}</p>
                <div className="mt-5 overflow-hidden rounded-[18px] bg-[#102A43]">
                  {recordedUrl ? (
                    <video ref={replayVideoRef} className="aspect-video w-full object-contain" src={recordedUrl} controls></video>
                  ) : (
                    <div className="flex aspect-video items-center justify-center text-sm font-bold text-white/75">Hasil recording akan muncul di sini</div>
                  )}
                </div>
              </div>

              <div className="camera-card p-6">
                <h2 className="text-2xl font-extrabold text-[#102A43]">Checklist Evaluasi Mandiri</h2>
                <p className="mt-2 text-sm font-semibold leading-6 text-[#667085]">Tandai setelah melihat ulang rekamanmu. Checklist ini bukan penilaian otomatis.</p>
                <div className="mt-4 grid gap-3">
                  {focusItems.map((item) => (
                    <button
                      key={item}
                      type="button"
                      onClick={() => toggleFocus(item)}
                      className={`flex items-center justify-between rounded-2xl px-4 py-3 text-left text-sm font-extrabold ring-1 transition ${
                        focusProgress[item]
                          ? "bg-[#ecfdf3] text-[#027a48] ring-[#abefc6]"
                          : isRecording
                            ? "bg-[#EAF5F7] text-[#245B7A] ring-[#CDE5EA]"
                            : "bg-[#f8fafc] text-[#344054] ring-[#e5e7eb]"
                      }`}
                    >
                      <span>{item}</span>
                      <span>{focusProgress[item] ? "Ditandai" : "Belum dicek"}</span>
                    </button>
                  ))}
                </div>
              </div>
                </>
              )}
            </aside>
          </section>

          <section className="camera-card p-6">
            <div className="mb-5">
              <h2 className="text-2xl font-extrabold text-[#102A43]">History Camera Practice</h2>
              <p className="mt-1 text-sm font-semibold text-[#667085]">Riwayat latihan camera practice yang tersimpan di akun.</p>
            </div>

            {history.length === 0 ? (
              <div className="rounded-2xl border border-dashed border-[#cbd5e1] bg-[#f8fafc] px-6 py-10 text-center text-sm font-semibold text-[#667085]">
                Belum ada riwayat camera practice. Mulai rekaman lalu simpan riwayat.
              </div>
            ) : (
              <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                {history.map((item) => (
                  <article key={item.id} className="camera-card interactive rounded-[18px] border border-[#e5e7eb] bg-[#fbfbfd] p-4">
                    <div className="history-video-card relative overflow-hidden rounded-2xl bg-[#102A43]">
                      {item.videoUrl ? (
                        <video className="aspect-video w-full object-cover" src={item.videoUrl} controls></video>
                      ) : (
                        <div className="flex aspect-video items-center justify-center px-4 text-center text-sm font-bold text-white/75">Video tidak tersedia</div>
                      )}
                    </div>
                    <h3 className="mt-4 text-base font-extrabold leading-snug text-[#102A43]">{item.topic}</h3>
                    <div className="mt-3 flex flex-wrap gap-2">
                      <span className="rounded-full bg-[#EAF5F7] px-3 py-1 text-xs font-black text-[#344054]">{item.date}</span>
                      <span className="rounded-full bg-[#EAF5F7] px-3 py-1 text-xs font-black text-[#245B7A]">{formatCameraTime(item.duration)}</span>
                      <span className="rounded-full bg-[#ecfdf3] px-3 py-1 text-xs font-black text-[#027a48]">{item.confidence}</span>
                    </div>
                  </article>
                ))}
              </div>
            )}
          </section>
        </div>
      );
    }

    ReactDOM.createRoot(document.getElementById("camera-practice-root")).render(<CameraPracticeDashboard />);

    const voiceMentorInfo = <?= json_encode($mentorInfo['voice'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

    const GuidedSpeakingPractice = () => {
      const { useEffect, useMemo, useRef, useState } = React;
      const [view, setView] = useState('PREP'); // 'PREP', 'OVERLAY'
      const [overlayState, setOverlayState] = useState('COUNTDOWN'); // 'COUNTDOWN', 'RECORDING', 'RESULT'
      const [activeCategory, setActiveCategory] = useState(practiceScripts[0].category);
      const [activeLevel, setActiveLevel] = useState('Beginner');
      const [scriptIndex, setScriptIndex] = useState(0);
      const [countdown, setCountdown] = useState(3);
      const [elapsed, setElapsed] = useState(0);
      const [recordedBlob, setRecordedBlob] = useState(null);
      const [recordedUrl, setRecordedUrl] = useState('');
      const [statusMsg, setStatusMsg] = useState('');
      const [isSaving, setIsSaving] = useState(false);
      const [isSaved, setIsSaved] = useState(false);
      const [isSubmitted, setIsSubmitted] = useState(false);
      const [practiceHistoryId, setPracticeHistoryId] = useState(null);
      const [currentStep, setCurrentStep] = useState(0);
      const [cue, setCue] = useState(null); // { type: 'JEDA' | 'TEKANAN', text: string }

      const levelLabels = { Beginner: 'Pemula', Intermediate: 'Menengah', Advanced: 'Lanjutan' };
      const levelLabel = (levelName) => levelLabels[levelName] || levelName;

      const mediaRecorderRef = useRef(null);
      const streamRef = useRef(null);
      const chunksRef = useRef([]);
      const timerIntervalRef = useRef(null);
      const countdownIntervalRef = useRef(null);
      const teleprompterRef = useRef(null);
      const stepRefs = useRef([]);

      const scripts = useMemo(() => {
        const group = practiceScripts.find(g => g.category === activeCategory);
        return group ? group.scripts.filter(s => s.level === activeLevel) : [];
      }, [activeCategory, activeLevel]);

      const activeScript = scripts[scriptIndex] || scripts[0] || { title: 'No Script', text: '', duration: 30 };
      const paragraphs = useMemo(() => activeScript.text.split('\n\n'), [activeScript]);
      const targetDuration = Number(activeScript.duration);
      const progressPercent = Math.min((elapsed / targetDuration) * 100, 100);
      
      const stepDuration = targetDuration / paragraphs.length;

      const formatTime = (s) => {
        const mins = String(Math.floor(s / 60)).padStart(2, '0');
        const secs = String(s % 60).padStart(2, '0');
        return `${mins}:${secs}`;
      };

      const startPractice = () => {
        setView('OVERLAY');
        setOverlayState('COUNTDOWN');
        setCountdown(3);
        setRecordedBlob(null);
        setRecordedUrl('');
        setElapsed(0);
        setCurrentStep(0);
        setCue(null);

        countdownIntervalRef.current = setInterval(() => {
          setCountdown(prev => {
            if (prev <= 1) {
              clearInterval(countdownIntervalRef.current);
              startRecording();
              return 0;
            }
            return prev - 1;
          });
        }, 1000);
      };

      const startRecording = async () => {
        try {
          const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
          streamRef.current = stream;
          chunksRef.current = [];
          
          const preferredMime = MediaRecorder.isTypeSupported("audio/webm") ? "audio/webm" : "";
          const recorder = preferredMime ? new MediaRecorder(stream, { mimeType: preferredMime }) : new MediaRecorder(stream);
          mediaRecorderRef.current = recorder;

          recorder.ondataavailable = e => { if (e.data.size > 0) chunksRef.current.push(e.data); };
          recorder.onstop = () => {
            const blob = new Blob(chunksRef.current, { type: preferredMime || "audio/webm" });
            setRecordedBlob(blob);
            setRecordedUrl(URL.createObjectURL(blob));
            setOverlayState('RESULT');
            stopStream();
          };

          recorder.start();
          setOverlayState('RECORDING');
          
          timerIntervalRef.current = setInterval(() => {
            setElapsed(prev => prev + 1);
          }, 1000);
        } catch (err) {
          console.error(err);
          alert("Microphone tidak dapat diakses.");
          setView('PREP');
        }
      };

      const stopRecording = () => {
        clearInterval(timerIntervalRef.current);
        if (mediaRecorderRef.current && mediaRecorderRef.current.state !== 'inactive') {
          mediaRecorderRef.current.stop();
        }
      };

      const stopStream = () => {
        if (streamRef.current) {
          streamRef.current.getTracks().forEach(t => t.stop());
          streamRef.current = null;
        }
      };

      const saveResult = async () => {
        if (!recordedBlob || !isLoggedIn) return;
        setIsSaving(true);
        const formData = new FormData();
        formData.append("action", "save_practice");
        formData.append("topic", activeScript.title);
        formData.append("script_title", activeScript.title);
        formData.append("category", activeCategory);
        formData.append("level_name", levelLabel(activeLevel));
        formData.append("duration", elapsed);
        formData.append("audio", recordedBlob, "practice.webm");

        try {
          const res = await fetch("Latihan.php", { method: "POST", body: formData });
          const data = await res.json();
          if (data.status) {
            setIsSaved(true);
            setPracticeHistoryId(data.practice_history_id || null);
            window.prependHistory?.(data.item);
          } else {
            alert(data.message);
          }
        } catch (err) {
          alert("Gagal menyimpan riwayat.");
        } finally {
          setIsSaving(false);
        }
      };

      const submitToMentor = async () => {
        if (!practiceHistoryId) {
          alert("Simpan riwayat latihan terlebih dahulu.");
          return;
        }
        if (!isLoggedIn) {
          alert("Silakan login terlebih dahulu.");
          return;
        }

        setStatusMsg("Mengirim latihan ke mentor...");

        try {
          const formData = new FormData();
          formData.append("action", "submit_to_mentor");
          formData.append("practice_history_id", practiceHistoryId);
          formData.append("feature_type", "voice");

          const response = await fetch("Latihan.php", { method: "POST", body: formData });
          const data = await response.json();

          if (!data.status) {
            alert(data.message || "Gagal mengirim ke mentor.");
            return;
          }

          setIsSubmitted(true);
          alert(data.message);
        } catch (error) {
          alert("Terjadi kesalahan saat mengirim ke mentor.");
        }
      };

      useEffect(() => {
        if (overlayState === 'RECORDING') {
          const step = Math.min(Math.floor(elapsed / stepDuration), paragraphs.length - 1);
          if (step !== currentStep) {
            setCurrentStep(step);
          }

          // Detect Cues
          const currentP = paragraphs[step] || '';
          if (currentP.includes('[JEDA]')) {
            setCue({ type: 'JEDA', text: '⏸ Jeda Sejenak. Tarik napas dan lanjutkan.' });
          } else if (currentP.includes('[TEKANAN]')) {
            setCue({ type: 'TEKANAN', text: '🔊 Tekankan Kalimat Berikut!' });
          } else {
            setCue(null);
          }
        }
      }, [elapsed, overlayState, paragraphs, stepDuration]);

      useEffect(() => {
        if (overlayState === 'RECORDING' && stepRefs.current[currentStep]) {
          stepRefs.current[currentStep].scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      }, [currentStep, overlayState]);

      useEffect(() => {
        return () => {
          clearInterval(timerIntervalRef.current);
          clearInterval(countdownIntervalRef.current);
          stopStream();
        };
      }, []);

      if (view === 'OVERLAY') {
        return (
          <div className="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-slate-900/95 p-6 text-white backdrop-blur-xl">
            {overlayState === 'COUNTDOWN' && (
              <div className="text-center">
                <div className="mb-4 text-sm font-black uppercase tracking-widest text-[#64C7D4]">Bersiap dalam</div>
                <div className="animate-pulse text-[180px] font-black leading-none">{countdown > 0 ? countdown : 'MULAI'}</div>
                <div className="mt-8 text-xl font-bold opacity-75">{activeScript.title}</div>
              </div>
            )}

            {overlayState === 'RECORDING' && (
              <div className="flex h-full w-full max-w-4xl flex-col overflow-hidden">
                <div className="flex items-center justify-between border-b border-white/10 pb-6">
                  <div>
                    <div className="flex items-center gap-3">
                      <span className="flex items-center gap-2 rounded-full bg-red-500/20 px-3 py-1 text-xs font-black text-red-400">
                        <span className="recording-dot"></span> SEDANG MEREKAM
                      </span>
                      <span className="text-sm font-bold opacity-60">{activeCategory} • {levelLabel(activeLevel)}</span>
                    </div>
                    <h2 className="mt-2 text-2xl font-black">{activeScript.title}</h2>
                  </div>
                  <div className="text-right">
                    <div className="text-sm font-bold opacity-50 mb-1">PROGRES LATIHAN</div>
                    <div className="text-lg font-black text-[#64C7D4]">Paragraf {currentStep + 1} dari {paragraphs.length}</div>
                    <div className="text-xs font-bold opacity-40">{Math.round((currentStep + 1) / paragraphs.length * 100)}% Selesai</div>
                  </div>
                  <div className="text-right ml-8">
                    <div className="text-5xl font-black tabular-nums">{formatTime(elapsed)}</div>
                    <div className="mt-1 text-xs font-bold opacity-50">Target: {formatTime(targetDuration)}</div>
                  </div>
                </div>

                <div className="mt-6 h-2 w-full overflow-hidden rounded-full bg-white/10">
                  <div className="h-full bg-[#64C7D4] transition-all duration-1000" style={{ width: `${progressPercent}%` }}></div>
                </div>

                <div className="relative flex-1 py-12 teleprompter-container overflow-hidden">
                  {cue && (
                    <div key={cue.text} className="cue-toast absolute top-0 left-1/2 -translate-x-1/2 z-10 flex items-center gap-3 rounded-2xl bg-[#64C7D4] px-6 py-3 font-black text-[#102A43] shadow-2xl">
                      <span className="text-2xl">{cue.type === 'JEDA' ? '⏸' : '🔊'}</span>
                      {cue.text}
                    </div>
                  )}
                  
                  <div ref={teleprompterRef} className="no-scrollbar h-full overflow-y-auto px-4">
                    <div className="flex flex-col gap-24 py-[20vh] text-center">
                      {paragraphs.map((p, i) => (
                        <div 
                          key={i} 
                          ref={el => stepRefs.current[i] = el}
                          className={`transition-all duration-700 ${currentStep === i ? 'scale-110 text-5xl font-black text-white' : 'scale-90 text-3xl font-bold text-white/10 blur-[1px]'}`}
                        >
                          {p.split('\n').map((line, li) => {
                            const isCue = line.includes('[JEDA]') || line.includes('[TEKANAN]');
                            if (isCue) {
                              return <div key={li} className="my-6"><span className="rounded-full bg-[#64C7D4]/20 px-6 py-2 text-base font-black text-[#64C7D4] tracking-widest">{line}</span></div>;
                            }
                            return <p key={li} className="mb-4 leading-tight">{line}</p>;
                          })}
                        </div>
                      ))}
                    </div>
                  </div>
                </div>

                <div className="flex items-center justify-center gap-6 border-t border-white/10 pt-8 pb-4">
                  <button className="btn btn-muted bg-white/10 text-white hover:bg-white/20 px-8" onClick={() => { stopRecording(); setView('PREP'); }}>Batal</button>
                  <button className="btn btn-muted bg-white/10 text-white hover:bg-white/20 px-8" onClick={() => { stopRecording(); startPractice(); }}>Ulangi</button>
                  <button className="btn btn-danger px-16 py-4 text-xl shadow-2xl shadow-red-500/20" onClick={stopRecording}>Selesai</button>
                </div>
              </div>
            )}

            {overlayState === 'RESULT' && (
              <div className="w-full max-w-2xl max-h-[90vh] overflow-y-auto no-scrollbar rounded-[40px] bg-white text-[#102A43] shadow-2xl">
                <div className="p-8 md:p-12">
                  <div className="text-center mb-10">
                    <div className="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-green-100 text-5xl text-green-600 animate-bounce">✓</div>
                    <h2 className="text-4xl font-black tracking-tight mb-2">🎉 Sesi Selesai!</h2>
                    <p className="text-lg font-bold text-[#667085]">Kerja bagus! Kamu baru saja menyelesaikan sesi pelatihan public speaking profesional.</p>
                  </div>

                  {/* Main Audio Playback - Focal Point */}
                  <div className="mb-10 p-8 rounded-[32px] bg-[#102A43] text-white shadow-xl relative overflow-hidden">
                    <div className="relative z-10">
                      <div className="text-xs font-black uppercase tracking-[0.2em] text-[#64C7D4] mb-4">Dengarkan Rekamanmu</div>
                      <audio src={recordedUrl} controls className="w-full h-14 accent-[#64C7D4]"></audio>
                      <div className="mt-4 flex items-center gap-4 text-xs font-bold opacity-60">
                        <span className="flex items-center gap-1">⏱ {elapsed} Detik</span>
                        <span className="flex items-center gap-1">🎙 High Quality Audio</span>
                      </div>
                    </div>
                    <div className="absolute right-[-20px] bottom-[-20px] text-[120px] font-black opacity-10 pointer-events-none">🎙</div>
                  </div>

                  <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                    {/* Session recap: quality assessment is provided by a mentor after submission. */}
                    <div className="p-6 rounded-3xl bg-[#f8fafc] border border-[#EAF5F7]">
                      <div className="topic-label mb-4">Evaluasi Mandiri</div>
                      <div className="space-y-4">
                        <div className="flex justify-between items-center">
                          <span className="text-sm font-bold text-[#667085]">Kelancaran</span>
                          <span className="text-sm font-black text-[#102A43]">Dengarkan ulang</span>
                        </div>
                        <div className="flex justify-between items-center">
                          <span className="text-sm font-bold text-[#667085]">Jeda &amp; tekanan</span>
                          <span className="text-sm font-black text-[#102A43]">Cek dari rekaman</span>
                        </div>
                        <div className="flex justify-between items-center">
                          <span className="text-sm font-bold text-[#667085]">Intonasi</span>
                          <span className="text-sm font-black text-[#102A43]">Catat perbaikanmu</span>
                        </div>
                      </div>
                      <p className="mt-5 text-xs font-bold leading-5 text-[#667085]">Penilaian detail tersedia setelah rekaman dikirim dan ditinjau mentor.</p>
                    </div>

                    {/* Practice Details */}
                    <div className="p-6 rounded-3xl bg-[#f8fafc] border border-[#EAF5F7]">
                      <div className="topic-label mb-4">Detail Sesi</div>
                      <div className="space-y-3">
                        <div className="score-item border-none bg-white p-3 rounded-2xl">
                          <span className="text-[10px] uppercase font-black text-[#667085]">Naskah</span>
                          <strong className="text-sm block truncate">{activeScript.title}</strong>
                        </div>
                        <div className="grid grid-cols-2 gap-3">
                          <div className="score-item border-none bg-white p-3 rounded-2xl">
                            <span className="text-[10px] uppercase font-black text-[#667085]">Level</span>
                            <strong className="text-sm block">{levelLabel(activeLevel)}</strong>
                          </div>
                          <div className="score-item border-none bg-white p-3 rounded-2xl">
                            <span className="text-[10px] uppercase font-black text-[#667085]">Status</span>
                            <strong className="text-sm block text-[#027a48]">Siap disimpan</strong>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  {/* Recommendations */}
                  <div className="mb-10">
                    <div className="topic-label mb-4">Rekomendasi Latihan</div>
                    <div className="grid grid-cols-1 gap-3">
                      <div className="flex items-center gap-4 p-4 rounded-2xl bg-[#EAF5F7] border border-[#CDE5EA]">
                        <div className="h-10 w-10 flex-shrink-0 flex items-center justify-center rounded-xl bg-[#64C7D4] text-white">📈</div>
                        <div>
                          <div className="text-sm font-black text-[#102A43]">Tantangan Bicara Lanjutan</div>
                          <p className="text-xs font-bold text-[#667085]">Latih spontanitasmu dengan topik acak.</p>
                        </div>
                      </div>
                      <div className="flex items-center gap-4 p-4 rounded-2xl bg-[#eff8ff] border border-[#d1e9ff]">
                        <div className="h-10 w-10 flex-shrink-0 flex items-center justify-center rounded-xl bg-[#175cd3] text-white">📹</div>
                        <div>
                          <div className="text-sm font-black text-[#102A43]">Camera Practice</div>
                          <p className="text-xs font-bold text-[#667085]">Evaluasi ekspresi wajah dan bahasa tubuh.</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div className="flex flex-col gap-4">
                    <button className="btn btn-primary py-5 text-xl shadow-xl shadow-[#64C7D4]/20" onClick={saveResult} disabled={isSaving || isSaved}>
                      {isSaving ? 'Menyimpan...' : isSaved ? '✓ Tersimpan di Riwayat' : '💾 Simpan & Selesaikan'}
                    </button>
                    
                    {isSaved && (
                      <button 
                        className="btn py-5 text-xl font-black text-white shadow-xl shadow-green-500/20 transition-all hover:scale-[1.02]" 
                        style={{ background: isSubmitted ? '#027a48' : '#027a48', opacity: isSubmitted ? 0.7 : 1 }}
                        onClick={submitToMentor} 
                        disabled={isSubmitted}
                      >
                        {isSubmitted ? '✓ Terkirim ke Mentor' : '📤 Kirim ke Mentor'}
                      </button>
                    )}

                    {statusMsg && (
                      <div className="rounded-2xl bg-[#eff8ff] px-4 py-3 text-center text-sm font-bold text-[#175cd3]">
                        {statusMsg}
                      </div>
                    )}

                    <div className="grid grid-cols-2 gap-4">
                      <button className="btn btn-muted py-4 font-black text-sm" onClick={() => { setScriptIndex(prev => prev + 1); setView('PREP'); setIsSaved(false); setIsSubmitted(false); setPracticeHistoryId(null); setStatusMsg(''); }}>📄 Naskah Berikutnya</button>
                      <button className="btn btn-muted py-4 font-black text-sm" onClick={() => { startPractice(); setIsSaved(false); setIsSubmitted(false); setPracticeHistoryId(null); setStatusMsg(''); }}>🔁 Latih Lagi</button>
                    </div>
                    <button className="mt-4 text-xs font-black text-[#667085] hover:text-[#102A43] uppercase tracking-widest transition-colors" onClick={() => setView('PREP')}>Kembali ke Menu Utama</button>
                  </div>
                </div>
              </div>
            )}
          </div>
        );
      }

      return (
        <div className="space-y-6">
          <div className="camera-card p-6">
            <div>
              <div className="text-sm font-black uppercase tracking-wide text-[#64C7D4]">Rekam Suara</div>
              <h2 className="mt-2 text-3xl font-extrabold text-[#102A43]">Latihan membaca dengan panduan</h2>
              <p className="mt-2 max-w-2xl text-sm font-semibold leading-6 text-[#667085]">Pilih kategori dan naskah, lalu rekam suaramu sambil mengikuti panduan jeda serta penekanan kalimat.</p>
            </div>
          </div>

          <div className="camera-card p-5">
            <div>
              <strong>{voiceMentorInfo ? `Mentor: ${voiceMentorInfo.name}` : 'Siap latihan suara?'}</strong>
              <span>Gunakan mode teleprompter untuk membantumu fokus pada artikulasi dan intonasi.</span>
            </div>
          </div>

          <div>
            <div className="mb-3 text-sm font-black uppercase tracking-wide text-[#64C7D4]">Pilih kategori</div>
            <div className="grid gap-3 md:grid-cols-2">
            {practiceScripts.map((group) => (
              <button
                key={group.category}
                className={`camera-card interactive p-4 text-left transition ${activeCategory === group.category ? 'border-[#64C7D4] bg-[#EAF5F7]' : ''}`}
                onClick={() => { setActiveCategory(group.category); setScriptIndex(0); }}
              >
                {group.category}
              </button>
            ))}
            </div>
          </div>

          <div className="camera-card p-6">
            <article className="script-card">
              <div className="topic-label">Naskah Terpilih</div>
              <h3 className="script-title">{activeScript.title}</h3>
              <div className="script-meta">
                <span className="badge">Kategori: {activeCategory}</span>
                <span className="badge">Durasi: {activeScript.duration} Detik</span>
                <span className="badge">Level: {levelLabel(activeLevel)}</span>
              </div>
              <div className="script-text line-clamp-6 opacity-60">
                {activeScript.text}
              </div>
              <div className="mt-4 flex justify-end">
                <button className="text-sm font-bold text-[#64C7D4]" onClick={() => setScriptIndex(prev => prev + 1)}>Lihat Naskah Lainnya →</button>
              </div>
            </article>

            <aside className="guide-card mt-4">
              <h3>Panduan Berbicara</h3>
              <ul className="guide-list">
                <li>Baca dengan suara jelas</li>
                <li>Berikan jeda pada tanda [JEDA]</li>
                <li>Tekankan kata pada tanda [TEKANAN]</li>
                <li>Gunakan mode teleprompter</li>
              </ul>
            </aside>
          </div>

          <div className="camera-card p-6">
            <div className="control-box">
              <h3>Tingkat Latihan</h3>
              <div className="duration-options">
                {['Beginner', 'Intermediate', 'Advanced'].map(l => (
                  <button
                    key={l}
                    className={`duration-btn ${activeLevel === l ? 'active' : ''}`}
                    onClick={() => { setActiveLevel(l); setScriptIndex(0); }}
                  >
                    {levelLabel(l)}
                  </button>
                ))}
              </div>
            </div>
            <div className="flex items-end">
              <button 
                className="btn btn-primary w-full py-4 text-lg shadow-xl"
                onClick={startPractice}
              >
                🟡 Mulai Latihan
              </button>
            </div>
          </div>
        </div>
      );
    };

    ReactDOM.createRoot(document.getElementById("guided-speaking-root")).render(<GuidedSpeakingPractice />);
  </script>

  <script type="text/plain" data-disabled-duplicate="conversation-challenge">
    const { useEffect, useMemo, useRef, useState } = React;

    (function () {
      const simulasiPercakapan = [
        {
          id: "wawancara",
          nama: "Simulasi Wawancara",
          persona: "Pewawancara Virtual",
          personaIcon: "👤",
          pesanPersona: "Selamat datang. Saya akan mengajukan beberapa pertanyaan rekrutmen. Jawablah seolah-olah Anda sedang dalam sesi interview nyata.",
          deskripsi: "Latihan menjawab pertanyaan wawancara kerja dengan jelas, percaya diri, dan terstruktur.",
          fokus: ["Struktur Jawaban", "Kejelasan Penyampaian", "Kepercayaan Diri", "Respons Spontan"],
          tips: ["Gunakan metode STAR (Situation, Task, Action, Result)", "Jaga kontak mata dengan kamera", "Hindari jawaban 'ya' atau 'tidak' saja"],
          pertanyaan: [
            "Ceritakan tentang diri Anda.",
            "Apa kelebihan terbesar Anda?",
            "Apa kelemahan terbesar Anda?",
            "Mengapa kami harus memilih Anda?",
            "Di mana Anda melihat diri Anda dalam lima tahun ke depan?",
            "Ceritakan pengalaman saat Anda menyelesaikan masalah.",
            "Bagaimana cara Anda bekerja dalam tekanan?",
            "Apa kontribusi yang bisa Anda berikan?"
          ]
        },
        {
          id: "seminar",
          nama: "Tanya Jawab Seminar",
          persona: "Moderator Seminar",
          personaIcon: "🎤",
          pesanPersona: "Terima kasih atas pemaparannya. Sekarang, saya akan menyampaikan beberapa pertanyaan dari audiens. Jawablah secara profesional.",
          deskripsi: "Latihan menjawab pertanyaan audiens setelah menyampaikan materi seminar.",
          fokus: ["Kedalaman Materi", "Etika Menjawab", "Kejelasan Argumen", "Manajemen Waktu"],
          tips: ["Apresiasi pertanyaan audiens", "Berikan jawaban yang edukatif", "Jika tidak tahu, sampaikan akan didiskusikan lanjut"],
          pertanyaan: [
            "Apa inti dari presentasi Anda?",
            "Mengapa topik ini penting?",
            "Apa solusi yang Anda tawarkan?",
            "Apa bukti bahwa solusi tersebut dapat diterapkan?",
            "Bagaimana cara mengukur keberhasilannya?",
            "Apa tantangan terbesar dari gagasan Anda?",
            "Bagaimana jika audiens tidak setuju dengan pendapat Anda?",
            "Apa langkah pertama yang perlu dilakukan?"
          ]
        },
        {
          id: "presentasi",
          nama: "Simulasi Presentasi",
          persona: "Audiens Virtual",
          personaIcon: "👥",
          pesanPersona: "Ide yang menarik. Namun kami memiliki beberapa pertanyaan kritis mengenai ide yang Anda sampaikan.",
          deskripsi: "Latihan mempertahankan ide presentasi melalui pertanyaan lanjutan.",
          fokus: ["Penguasaan Ide", "Ketangguhan Argumen", "Ketenangan", "Persuasi"],
          tips: ["Gunakan data untuk mendukung argumen", "Tetap tenang saat ditanya hal kritis", "Arahkan kembali ke manfaat utama ide Anda"],
          pertanyaan: [
            "Jelaskan ide utama presentasi Anda.",
            "Apa manfaat dari solusi yang Anda tawarkan?",
            "Bagaimana cara penerapannya?",
            "Siapa yang paling membutuhkan solusi ini?",
            "Apa risiko yang perlu diperhatikan?",
            "Mengapa pendekatan Anda lebih efektif?",
            "Apa data atau alasan yang mendukung ide Anda?",
            "Bagaimana Anda menutup presentasi dengan kuat?"
          ]
        },
        {
          id: "mc",
          nama: "Pembawa Acara",
          persona: "Koordinator Acara",
          personaIcon: "🎙️",
          pesanPersona: "Acara akan segera dimulai. Anda akan menghadapi situasi panggung yang dinamis. Jaga energi penonton tetap tinggi.",
          deskripsi: "Latihan merespons situasi acara sebagai MC secara ramah and profesional.",
          fokus: ["Energi & Vokal", "Kelikatan Bicara", "Keramahan", "Improvisasi"],
          tips: ["Mulai dengan senyum yang tulus", "Gunakan intonasi yang variatif", "Siap dengan kalimat cadangan jika ada kendala teknis"],
          pertanyaan: [
            "Buka acara seminar ini.",
            "Perkenalkan narasumber.",
            "Tutup acara secara profesional.",
            "Arahkan peserta menuju sesi tanya jawab.",
            "Isi jeda ketika narasumber belum siap.",
            "Sampaikan perubahan susunan acara dengan tenang.",
            "Ajak peserta memberi apresiasi kepada narasumber.",
            "Berikan pengumuman singkat sebelum acara selesai."
          ]
        },
        {
          id: "perkenalan",
          nama: "Networking",
          persona: "Rekan Profesional",
          personaIcon: "🤝",
          pesanPersona: "Senang bertemu Anda. Mari berkenalan lebih jauh mengenai latar belakang dan keahlian Anda.",
          deskripsi: "Latihan memperkenalkan diri dalam kegiatan formal, komunitas, atau dunia kerja.",
          fokus: ["Personal Branding", "Artikulasi Nama", "Kesan Pertama", "Relevansi Pengalaman"],
          tips: ["Sampaikan 'Elevator Pitch' Anda", "Hubungkan keahlian dengan kebutuhan lawan bicara", "Tanyakan balik untuk membangun koneksi"],
          pertanyaan: [
            "Perkenalkan diri Anda.",
            "Ceritakan latar belakang Anda.",
            "Apa tujuan Anda mengikuti kegiatan ini?",
            "Apa pengalaman yang paling relevan dengan bidang Anda?",
            "Apa kemampuan utama yang ingin Anda tunjukkan?",
            "Bagaimana Anda ingin dikenal oleh orang lain?",
            "Apa nilai yang Anda bawa dalam kerja sama?",
            "Apa target pengembangan diri Anda saat ini?"
          ]
        },
        {
          id: "diskusi",
          nama: "Diskusi Kelompok",
          persona: "Fasilitator Diskusi",
          personaIcon: "👨‍🏫",
          pesanPersona: "Kita perlu mencapai kesepakatan. Saya ingin mendengar kontribusi dan tanggapan Anda atas poin berikut.",
          deskripsi: "Latihan memberi respons dalam diskusi, menyanggah dengan sopan, dan merangkum pendapat.",
          fokus: ["Kerja Sama Tim", "Kesopanan", "Analisis Masalah", "Gaya Diplomatis"],
          tips: ["Gunakan kalimat 'Saya setuju, namun...' untuk menyanggah", "Berikan solusi, bukan hanya kritik", "Pastikan semua poin tersampaikan ringkas"],
          pertanyaan: [
            "Apa pendapat Anda tentang ide kelompok ini?",
            "Bagaimana Anda menanggapi pendapat yang berbeda?",
            "Apa usulan Anda agar diskusi lebih terarah?",
            "Bagaimana cara Anda menyampaikan ketidaksetujuan dengan sopan?",
            "Apa keputusan terbaik menurut Anda?",
            "Bagaimana Anda merangkum hasil diskusi?",
            "Apa peran yang bisa Anda ambil dalam kelompok?",
            "Bagaimana cara menjaga semua anggota tetap terlibat?"
          ]
        }
      ];

      const tingkatKesulitan = [
        { nama: "Pemula", jumlah: 3, persiapan: 20, jawab: 45, deskripsi: "3 pertanyaan, persiapan 20 detik, menjawab 45 detik." },
        { nama: "Menengah", jumlah: 5, persiapan: 15, jawab: 60, deskripsi: "5 pertanyaan, persiapan 15 detik, menjawab 60 detik." },
        { nama: "Lanjutan", jumlah: 8, persiapan: 10, jawab: 90, deskripsi: "8 pertanyaan, persiapan 10 detik, menjawab 90 detik." }
      ];

      function TantanganPercakapan() {
        const [simulasi, setSimulasi] = useState(simulasiPercakapan[0]);
        const [level, setLevel] = useState(tingkatKesulitan[0]);
        const [modeCepat, setModeCepat] = useState(false);
        const [view, setView] = useState("PREP"); // "PREP", "TRANSITION", "ACTIVE", "RESULT"
        const [pertanyaan, setPertanyaan] = useState([]);
        const [nomor, setNomor] = useState(0);
        const [fase, setFase] = useState("persiapan"); // "persiapan", "menjawab", "jawaban"
        const [sisaWaktu, setSisaWaktu] = useState(0);
        const [durasiJawaban, setDurasiJawaban] = useState([]);
        const [isSaving, setIsSaving] = useState(false);
        const [isSaved, setIsSaved] = useState(false);
        const [isSubmitted, setIsSubmitted] = useState(false);
        const [practiceHistoryId, setPracticeHistoryId] = useState(null);
        const [transitionCountdown, setTransitionCountdown] = useState(3);
        const [stepCountdown, setStepCountdown] = useState(0);
        const [lastDuration, setLastDuration] = useState(0);

        const recorderRef = useRef(null);
        const streamRef = useRef(null);
        const chunksRef = useRef([]);
        const allAudioChunksRef = useRef([]);
        const fullRecorderRef = useRef(null);
        const fullStreamRef = useRef(null);

        const currentRules = modeCepat
          ? { nama: "Mode Respon Cepat", jumlah: 5, persiapan: 5, jawab: 30, deskripsi: "Pertanyaan acak, persiapan 5 detik, menjawab 30 detik." }
          : level;

        const progres = pertanyaan.length ? Math.min((durasiJawaban.length / pertanyaan.length) * 100, 100) : 0;
        const waktuTotal = durasiJawaban.reduce((t, v) => t + v, 0);

        const formatTime = (s) => {
          const mins = String(Math.floor(s / 60)).padStart(2, "0");
          const secs = String(s % 60).padStart(2, "0");
          return `${mins}:${secs}`;
        };

        const startSimulationFlow = () => {
          const daftar = modeCepat
            ? [...simulasiPercakapan.flatMap(s => s.pertanyaan.map(t => ({ teks: t, asal: s.nama })))].sort(() => Math.random() - 0.5).slice(0, 5)
            : simulasi.pertanyaan.map(t => ({ teks: t, asal: simulasi.nama }));
          
          setPertanyaan(daftar);
          setNomor(0);
          setDurasiJawaban([]);
          setIsSaved(false);
          setIsSubmitted(false);
          setPracticeHistoryId(null);
          setTransitionCountdown(3);
          setView("TRANSITION");
          
          const interval = setInterval(() => {
            setTransitionCountdown(prev => {
              if (prev <= 1) {
                clearInterval(interval);
                mulaiTantangan(daftar);
                return 0;
              }
              return prev - 1;
            });
          }, 1000);
        };

        async function mulaiTantangan(daftar) {
          allAudioChunksRef.current = [];
          try {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
              const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
              fullStreamRef.current = stream;
              const preferredMime = MediaRecorder.isTypeSupported("audio/webm") ? "audio/webm" : "";
              const recorder = new MediaRecorder(stream, preferredMime ? { mimeType: preferredMime } : {});
              recorder.ondataavailable = e => { if (e.data.size > 0) allAudioChunksRef.current.push(e.data); };
              fullRecorderRef.current = recorder;
              recorder.start();
            }
          } catch (e) {}

          setView("ACTIVE");
          setFase("persiapan");
          setSisaWaktu(modeCepat ? 5 : level.persiapan);
        }

        useEffect(() => {
          if (view !== "ACTIVE") return;
          if (fase === "jawaban") return;

          if (sisaWaktu <= 0) {
            if (fase === "persiapan") mulaiMenjawab();
            else if (fase === "menjawab") selesaiMenjawab(true);
            return;
          }

          const id = setTimeout(() => setSisaWaktu(s => Math.max(s - 1, 0)), 1000);
          return () => clearTimeout(id);
        }, [view, fase, sisaWaktu]);

        async function mulaiMenjawab() {
          try {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
              const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
              streamRef.current = stream;
              const recorder = new MediaRecorder(stream);
              recorder.onstop = () => { if (streamRef.current) streamRef.current.getTracks().forEach(t => t.stop()); };
              recorderRef.current = recorder;
              recorder.start();
            }
            setFase("menjawab");
            setSisaWaktu(modeCepat ? 30 : level.jawab);
          } catch (e) {
            setFase("menjawab");
            setSisaWaktu(modeCepat ? 30 : level.jawab);
          }
        }

        function selesaiMenjawab(otomatis = false) {
          if (recorderRef.current && recorderRef.current.state !== "inactive") recorderRef.current.stop();
          
          const target = modeCepat ? 30 : level.jawab;
          const durasi = otomatis ? target : Math.max(target - sisaWaktu, 1);
          setDurasiJawaban(curr => [...curr, durasi]);
          setLastDuration(durasi);
          
          if (nomor + 1 >= pertanyaan.length) {
            if (fullRecorderRef.current && fullRecorderRef.current.state !== "inactive") fullRecorderRef.current.stop();
            setView("RESULT");
          } else {
            setFase("jawaban");
            setStepCountdown(3);
            const interval = setInterval(() => {
              setStepCountdown(prev => {
                if (prev <= 1) {
                  clearInterval(interval);
                  lanjutPertanyaan();
                  return 0;
                }
                return prev - 1;
              });
            }, 1000);
          }
        }

        function lanjutPertanyaan() {
          setNomor(n => n + 1);
          setFase("persiapan");
          setSisaWaktu(modeCepat ? 5 : level.persiapan);
        }

        async function simpanRiwayat() {
          if (!isLoggedIn) return;
          setIsSaving(true);
          const formData = new FormData();
          formData.append("action", "save_challenge");
          formData.append("challenge_type", modeCepat ? "Mode Respon Cepat" : simulasi.nama);
          formData.append("level_name", currentRules.nama);
          formData.append("question_count", pertanyaan.length);
          formData.append("prompt", pertanyaan.map((item, index) => `${index + 1}. ${item.teks}`).join("\n"));
          formData.append("prep_seconds", currentRules.persiapan);
          formData.append("speak_seconds", currentRules.jawab);
          formData.append("actual_seconds", waktuTotal);
          formData.append("score", Math.round(progres));
          formData.append("completed", 1);
          if (allAudioChunksRef.current.length > 0) {
            formData.append("audio", new Blob(allAudioChunksRef.current, { type: "audio/webm" }), "challenge.webm");
          }

          try {
            const res = await fetch("Latihan.php", { method: "POST", body: formData });
            const data = await res.json();
            if (data.status) {
              setIsSaved(true);
              setPracticeHistoryId(data.practice_history_id);
              window.prependChallengeHistory?.(data.item);
            }
          } catch (e) {} finally { setIsSaving(false); }
        }

        async function submitToMentor() {
          if (!practiceHistoryId || !isLoggedIn) return;
          setIsSubmitted(false);
          const formData = new FormData();
          formData.append("action", "submit_to_mentor");
          formData.append("practice_history_id", practiceHistoryId);
          formData.append("feature_type", "challenge");
          try {
            const res = await fetch("Latihan.php", { method: "POST", body: formData });
            const data = await res.json();
            if (data.status) setIsSubmitted(true);
          } catch (e) {}
        }

        if (view === "TRANSITION") {
          return (
            <div className="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-[#102A43]/95 text-white backdrop-blur-xl">
              <div className="text-center animate-in fade-in zoom-in duration-500">
                <div className="mb-4 text-sm font-black uppercase tracking-[0.3em] text-[#64C7D4]">Simulasi Akan Dimulai</div>
                <div className="text-[180px] font-black leading-none">{transitionCountdown > 0 ? transitionCountdown : 'MULAI'}</div>
                <div className="mt-8 space-y-2">
                  <div className="text-2xl font-black">{modeCepat ? "Mode Respon Cepat" : simulasi.nama}</div>
                  <div className="text-sm font-bold opacity-60">Level: {currentRules.nama} • {pertanyaan.length} Pertanyaan</div>
                </div>
              </div>
            </div>
          );
        }

        if (view === "ACTIVE") {
          const aktif = pertanyaan[nomor];
          return (
            <div className="fixed inset-0 z-[9999] flex flex-col bg-[#f7f7fc]">
              <div className="flex items-center justify-between bg-white px-8 py-4 shadow-sm">
                <div className="flex items-center gap-6">
                  <div className="h-12 w-12 rounded-2xl bg-[#102A43] flex items-center justify-center text-white text-xl">{simulasi.personaIcon}</div>
                  <div>
                    <div className="text-[10px] font-black uppercase tracking-widest text-[#64C7D4]">{modeCepat ? "MODE RESPON CEPAT" : simulasi.nama}</div>
                    <div className="text-lg font-black text-[#102A43]">{simulasi.persona}</div>
                  </div>
                </div>
                <div className="flex items-center gap-8">
                  <div className="text-right">
                    <div className="text-[10px] font-black text-[#667085] uppercase tracking-wider">Progres Simulasi</div>
                    <div className="text-lg font-black text-[#102A43]">Pertanyaan {nomor + 1} <span className="text-xs opacity-40">dari {pertanyaan.length}</span></div>
                    <div className="text-[10px] font-bold text-[#64C7D4]">{Math.round((nomor / pertanyaan.length) * 100)}% Selesai</div>
                  </div>
                  <div className="h-10 w-[2px] bg-slate-100"></div>
                  <div className="text-right">
                    <div className="text-[10px] font-black text-[#667085] uppercase">{fase === "persiapan" ? "Persiapan" : "Bicara"}</div>
                    <div className={`text-4xl font-black tabular-nums ${fase === "menjawab" ? "text-red-500" : "text-[#102A43]"}`}>{formatTime(sisaWaktu)}</div>
                  </div>
                </div>
              </div>

              <div className="h-1.5 w-full bg-slate-100">
                <div className="h-full bg-[#64C7D4] transition-all duration-500" style={{ width: `${progres}%` }}></div>
              </div>

              <main className="flex-1 flex gap-8 p-12 overflow-hidden" style={{ width: '100%', marginLeft: 0 }}>
                <div className="flex-1 flex flex-col items-center justify-center">
                  {fase === "jawaban" ? (
                    <div className="text-center animate-in fade-in zoom-in duration-300">
                      <div className="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-green-100 text-5xl text-green-600">✓</div>
                      <h2 className="text-3xl font-black text-[#102A43]">Jawaban Berhasil Disimpan</h2>
                      <p className="text-lg font-bold text-[#667085] mb-8">Durasi: {lastDuration} Detik</p>
                      <div className="text-sm font-black uppercase tracking-widest text-[#64C7D4]">Bersiap ke pertanyaan berikutnya</div>
                      <div className="text-6xl font-black text-[#102A43] mt-4">{stepCountdown}</div>
                    </div>
                  ) : (
                    <div className="w-full max-w-4xl">
                      <div className="mb-12">
                        <div className="flex items-center gap-3 mb-4">
                          <div className="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-lg">{simulasi.personaIcon}</div>
                          <span className="text-sm font-black text-[#667085] uppercase tracking-widest">{simulasi.persona}</span>
                        </div>
                        <div className="bg-white p-10 rounded-[40px] rounded-tl-none shadow-sm border border-slate-100 relative">
                          <div className="text-3xl font-bold text-[#102A43] leading-relaxed italic">"{aktif.teks}"</div>
                          <div className="absolute -left-3 top-0 w-6 h-6 bg-white border-l border-t border-slate-100 transform -rotate-45"></div>
                        </div>
                      </div>
                      
                      <div className="text-center">
                        <div className={`inline-flex items-center gap-3 rounded-full px-8 py-4 text-sm font-black transition-all ${fase === "menjawab" ? "bg-red-50 text-red-600 animate-pulse border border-red-100 shadow-lg shadow-red-500/10" : "bg-[#EAF5F7] text-[#64C7D4] border border-[#CDE5EA]"}`}>
                          {fase === "menjawab" ? <><span className="h-3 w-3 rounded-full bg-red-600"></span> SEDANG MEREKAM JAWABAN</> : "TAHAP PERSIAPAN"}
                        </div>
                        <p className="mt-8 text-xl font-bold text-[#667085]">
                          {fase === "persiapan" ? "Gunakan waktu ini untuk menyusun poin jawaban Anda..." : "Bicaralah dengan tenang, jelas, and penuh percaya diri."}
                        </p>
                      </div>
                    </div>
                  )}
                </div>

                <aside className="w-80 space-y-6">
                  <div className="simulation-sidebar">
                    <h4><span>💡</span> Tips Menjawab</h4>
                    <div className="space-y-4">
                      {(simulasi.tips || ["Jawab dengan jelas", "Gunakan contoh nyata", "Jaga kontak mata"]).map((tip, i) => (
                        <div key={i} className="tip-item">
                          <span>✓</span> {tip}
                        </div>
                      ))}
                    </div>
                  </div>

                  <div className="simulation-sidebar">
                    <h4><span>🎯</span> Fokus Penilaian</h4>
                    <div className="flex flex-wrap gap-2">
                      {simulasi.fokus.map(f => (
                        <span key={f} className="bg-[#102A43] text-white text-[10px] font-black px-3 py-1.5 rounded-lg">✓ {f}</span>
                      ))}
                    </div>
                  </div>
                </aside>
              </main>

              <div className="bg-white border-t border-slate-100 p-8 flex justify-center gap-4">
                <button className="btn btn-muted px-10 font-black text-sm" onClick={() => { if(confirm("Batalkan simulasi? Progres tidak akan disimpan.")) { setView("PREP"); if (fullStreamRef.current) fullStreamRef.current.getTracks().forEach(t => t.stop()); } }}>Batalkan Latihan</button>
                {fase === "menjawab" && <button className="btn btn-danger px-16 py-5 text-xl font-black shadow-xl shadow-red-500/20" onClick={() => selesaiMenjawab(false)}>Selesai Menjawab</button>}
              </div>
            </div>
          );
        }

        if (view === "RESULT") {
          return (
            <div className="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-[#102A43]/95 p-6 text-white backdrop-blur-xl">
              <div className="w-full max-w-2xl max-h-[90vh] overflow-y-auto no-scrollbar rounded-[40px] bg-white text-[#102A43] shadow-2xl">
                <div className="p-8 md:p-12 text-center">
                  <div className="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-green-100 text-5xl text-green-600 animate-bounce">✓</div>
                  <h2 className="text-4xl font-black tracking-tight mb-2">🎉 Simulasi Selesai!</h2>
                  <p className="text-lg font-bold text-[#667085] mb-8">Luar biasa! Kamu telah menyelesaikan tantangan {simulasi.nama}.</p>
                  
                  <div className="flex items-center justify-center gap-4 mb-10">
                    <div className="xp-badge"><span>✨</span> +120 XP</div>
                    <div className="xp-badge" style={{ background: '#ecfdf3', borderColor: '#abefc6', color: '#027a48' }}><span>🔥</span> Streak 7 Hari</div>
                  </div>

                  <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                    <div className="rounded-[32px] bg-[#f8fafc] border border-[#EAF5F7] p-8 text-left">
                      <div className="topic-label mb-6 text-center">Statistik Sesi</div>
                      <div className="grid grid-cols-2 gap-4">
                        <div className="score-item border-none bg-white p-4 rounded-2xl shadow-sm text-center">
                          <span className="text-[10px] uppercase font-black text-[#667085]">Pertanyaan</span>
                          <strong className="text-xl">{pertanyaan.length}</strong>
                        </div>
                        <div className="score-item border-none bg-white p-4 rounded-2xl shadow-sm text-center">
                          <span className="text-[10px] uppercase font-black text-[#667085]">Durasi</span>
                          <strong className="text-xl">{waktuTotal}s</strong>
                        </div>
                        <div className="score-item border-none bg-white p-4 rounded-2xl shadow-sm text-center">
                          <span className="text-[10px] uppercase font-black text-[#667085]">Tingkat</span>
                          <strong className="text-lg">{currentRules.nama}</strong>
                        </div>
                        <div className="score-item border-none bg-white p-4 rounded-2xl shadow-sm text-center">
                          <span className="text-[10px] uppercase font-black text-[#667085]">Status</span>
                          <strong className="text-xl text-[#027a48]">Lulus</strong>
                        </div>
                      </div>
                    </div>
                    
                    <div className="streak-card flex flex-col justify-center items-center shadow-xl shadow-[#102A43]/20">
                      <div className="text-sm font-black text-[#64C7D4] mb-4 uppercase tracking-widest">Level Speaking</div>
                      <div className="relative h-24 w-24 flex items-center justify-center mb-4">
                        <svg className="absolute inset-0 h-full w-full -rotate-90">
                          <circle cx="48" cy="48" r="40" stroke="rgba(255,255,255,0.1)" strokeWidth="8" fill="none" />
                          <circle cx="48" cy="48" r="40" stroke="#64C7D4" strokeWidth="8" fill="none" strokeDasharray="251.2" strokeDashoffset={251.2 - (251.2 * 0.68)} strokeLinecap="round" />
                        </svg>
                        <span className="text-2xl font-black">68%</span>
                      </div>
                      <div className="bg-[#64C7D4] px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">+8% Naik</div>
                    </div>
                  </div>

                  <div className="flex flex-col gap-4">
                    <button className="btn btn-primary py-5 text-xl font-black shadow-xl shadow-[#64C7D4]/20" onClick={simpanRiwayat} disabled={isSaving || isSaved}>
                      {isSaving ? 'Menyimpan...' : isSaved ? '✓ Tersimpan di Riwayat' : '💾 Simpan & Selesaikan'}
                    </button>
                    {isSaved && (
                      <button className="btn py-5 text-xl font-black text-white shadow-xl shadow-green-500/20 transition-all hover:scale-[1.02]" style={{ background: '#027a48' }} onClick={submitToMentor} disabled={isSubmitted}>
                        {isSubmitted ? '✓ Terkirim ke Mentor' : '📤 Kirim ke Mentor'}
                      </button>
                    )}
                    <button className="btn btn-muted py-4 font-black text-sm" onClick={() => setView("PREP")}>Kembali ke Menu Utama</button>
                  </div>
                </div>
              </div>
            </div>
          );
        }

        return (
          <div className="panel">
            <div className="panel-title">
              <div>
                <h2 className="text-3xl font-black">Tantangan Bicara</h2>
                <p>Uji kemampuan bicara spontan Anda melalui simulasi percakapan interaktif.</p>
              </div>
              <span className="status-pill border border-slate-200">MODE SIMULASI AKTIF</span>
            </div>

            <div className="challenge-layout mb-8">
              <div className="control-box">
                <div className="flex items-center justify-between mb-5">
                  <h3 className="m-0 text-sm font-black uppercase tracking-wider text-[#667085]">Pilih Jenis Simulasi</h3>
                  {modeCepat && <span className="rounded-full bg-[#64C7D4] px-3 py-1 text-[10px] font-black text-white">RESPON CEPAT AKTIF</span>}
                </div>
                <div className="option-grid">
                  {simulasiPercakapan.map((item) => (
                    <button
                      key={item.id}
                      type="button"
                      className={`option-card transition-all duration-300 ${!modeCepat && simulasi.id === item.id ? "active scale-[1.02] border-[#64C7D4] shadow-lg shadow-[#64C7D4]/10" : "hover:border-slate-300"}`}
                      onClick={() => { setSimulasi(item); setModeCepat(false); }}
                    >
                      <div className="text-2xl mb-2">{item.personaIcon}</div>
                      <strong className="text-sm font-black">{item.nama}</strong>
                    </button>
                  ))}
                  <button type="button" className={`option-card transition-all duration-300 ${modeCepat ? "active scale-[1.02] border-[#64C7D4] shadow-lg shadow-[#64C7D4]/10" : "hover:border-slate-300"}`} onClick={() => setModeCepat(true)}>
                    <div className="text-2xl mb-2">⚡</div>
                    <strong className="text-sm font-black">Respon Cepat</strong>
                  </button>
                </div>
              </div>

              <div className="space-y-6">
                <div className="control-box">
                  <h3 className="m-0 text-sm font-black uppercase tracking-wider text-[#667085] mb-4">Tingkat Kesulitan</h3>
                  <div className="flex gap-3">
                    {tingkatKesulitan.map((item) => (
                      <button
                        key={item.nama}
                        type="button"
                        className={`flex-1 rounded-xl py-3 text-sm font-black transition-all ${level.nama === item.nama ? "bg-[#102A43] text-white shadow-lg shadow-[#102A43]/20" : "bg-white text-slate-500 ring-1 ring-slate-200 hover:ring-slate-300"}`}
                        onClick={() => setLevel(item)}
                      >
                        {item.nama}
                      </button>
                    ))}
                  </div>
                </div>

                <div className="rounded-[40px] border border-[#CDE5EA] bg-[#EAF5F7] p-10 shadow-sm relative overflow-hidden">
                  <div className="relative z-10">
                    <div className="flex items-center gap-5 mb-8">
                      <div className="h-16 w-16 rounded-3xl bg-[#64C7D4] flex items-center justify-center text-white text-3xl shadow-xl shadow-[#64C7D4]/20">{modeCepat ? '⚡' : simulasi.personaIcon}</div>
                      <div>
                        <div className="text-[10px] font-black uppercase tracking-[0.2em] text-[#64C7D4] mb-1">Simulasi Briefing</div>
                        <h3 className="text-2xl font-black text-[#102A43] uppercase tracking-tight m-0">{modeCepat ? "Tantangan Respon Cepat" : simulasi.nama}</h3>
                      </div>
                    </div>
                    
                    <div className="mb-8">
                      <div className="text-[10px] font-black text-[#667085] uppercase tracking-widest mb-3">Tujuan Latihan</div>
                      <p className="text-base font-bold leading-relaxed text-[#102A43]">
                        {modeCepat 
                          ? "Latih spontanitas maksimal dengan menjawab pertanyaan acak dalam waktu terbatas."
                          : `Anda akan mengikuti simulasi ${simulasi.nama.toLowerCase()}. Jawablah setiap pertanyaan dari ${simulasi.persona} secara terstruktur and profesional.`}
                      </p>
                    </div>

                    <div className="mb-8">
                      <div className="text-[10px] font-black text-[#667085] uppercase tracking-widest mb-4">Target Kompetensi</div>
                      <div className="flex flex-wrap gap-2">
                        {(modeCepat ? ["Spontanitas", "Kecepatan", "Improvisasi", "Ketajaman"] : simulasi.fokus).map(f => (
                          <span key={f} className="rounded-xl bg-white border border-[#CDE5EA] px-4 py-2 text-xs font-black text-[#102A43]">✓ {f}</span>
                        ))}
                      </div>
                    </div>

                    <div className="mb-10 grid grid-cols-3 gap-4">
                      <div className="text-center">
                        <span className="text-[10px] font-black text-[#667085] uppercase block mb-1">Pertanyaan</span>
                        <strong className="text-xl text-[#102A43]">{currentRules.jumlah}</strong>
                      </div>
                      <div className="text-center border-x border-[#CDE5EA]">
                        <span className="text-[10px] font-black text-[#667085] uppercase block mb-1">Persiapan</span>
                        <strong className="text-xl text-[#102A43]">{currentRules.persiapan}s</strong>
                      </div>
                      <div className="text-center">
                        <span className="text-[10px] font-black text-[#667085] uppercase block mb-1">Menjawab</span>
                        <strong className="text-xl text-[#102A43]">{currentRules.jawab}s</strong>
                      </div>
                    </div>

                    <div className="space-y-4">
                      <button 
                        className="btn btn-primary w-full py-5 text-2xl font-black shadow-2xl shadow-[#64C7D4]/40 hover:scale-[1.02] active:scale-[0.98] transition-all"
                        onClick={startSimulationFlow}
                      >
                        🎯 MULAI SIMULASI
                      </button>
                      <p className="text-center text-[11px] font-black text-[#667085] uppercase tracking-wider">Siapkan mikrofon Anda sebelum memulai</p>
                    </div>
                  </div>
                  <div className="absolute -right-10 -bottom-10 text-[200px] font-black opacity-[0.03] pointer-events-none">{simulasi.personaIcon}</div>
                </div>
              </div>
            </div>
          </div>
        );
      }

      ReactDOM.createRoot(document.getElementById("conversation-challenge-root")).render(<TantanganPercakapan />);
    })();
  </script>

  <script type="text/plain" data-disabled-duplicate="camera-practice">
    const { useEffect: useEff, useMemo: useMem, useRef: useRefC, useState: useSt } = React;

    const cameraTopics = [
      "Ceritakan pengalaman paling berkesan saat berbicara di depan orang.",
      "Jelaskan mengapa eye contact penting dalam public speaking.",
      "Presentasikan ide kegiatan kelas dalam satu menit.",
      "Ceritakan cara kamu membangun rasa percaya diri.",
      "Berikan opini singkat tentang pentingnya bahasa tubuh.",
      "Simulasikan pembukaan sebagai MC acara sekolah."
    ];

    const cameraDurations = [
      { label: "1 minute", seconds: 60 },
      { label: "3 minutes", seconds: 180 },
      { label: "5 minutes", seconds: 300 }
    ];

    const cameraHistoryFromDB = <?= json_encode($cameraHistory, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    const cameraMentorInfo = <?= json_encode($mentorInfo['camera'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

    const simulationModes = [
      {
        name: "Presentation",
        objective: "Sampaikan ide utama dengan pembuka, tiga poin, dan penutup yang jelas.",
        tip: "Gunakan gesture tangan saat berpindah poin."
      },
      {
        name: "Interview",
        objective: "Jawab dengan struktur singkat: situasi, tindakan, dan hasil.",
        tip: "Tatap kamera seperti sedang melihat pewawancara."
      },
      {
        name: "MC Opening",
        objective: "Buka acara dengan salam, perkenalan, and energi yang ramah.",
        tip: "Mulai dengan senyum dan intonasi naik di kalimat pembuka."
      }
    ];

    const focusItems = ["Eye contact", "Facial expression", "Body language", "Confidence"];

    function formatCameraTime(seconds) {
      const mins = String(Math.floor(seconds / 60)).padStart(2, "0");
      const secs = String(seconds % 60).padStart(2, "0");
      return `${mins}:${secs}`;
    }

    function CameraPracticeDashboard() {
      const liveVideoRef = useRefC(null);
      const replayVideoRef = useRefC(null);
      const mediaRecorderRef = useRefC(null);
      const streamRef = useRefC(null);
      const chunksRef = useRefC([]);
      const timerRef = useRefC(null);
      const elapsedRef = useRefC(0);
      const recordedBlobRef = useRefC(null);

      const [selectedDuration, setSelectedDuration] = useSt(60);
      const [topic, setTopic] = useSt(cameraTopics[0]);
      const [isRecording, setIsRecording] = useSt(false);
      const [elapsed, setElapsed] = useSt(0);
      const [recordedUrl, setRecordedUrl] = useSt("");
      const [statusMessage, setStatusMessage] = useSt("Camera dan microphone akan aktif saat latihan dimulai.");
      // Build initial history from DB records
      const dbHistory = cameraHistoryFromDB.map((item) => ({
        id: `db-${item.id}`,
        dbId: item.id,
        topic: item.topic,
        date: new Date(item.created_at.replace(" ", "T")).toLocaleString("id-ID", { dateStyle: "medium", timeStyle: "short" }),
        duration: item.duration_seconds,
        videoUrl: item.video_path || "",
        confidence: item.simulation_mode || "Practice"
      }));
      const [history, setHistory] = useSt(dbHistory);
      const [activeMode, setActiveMode] = useSt(simulationModes[0]);
      const [focusProgress, setFocusProgress] = useSt({
        "Eye contact": false,
        "Facial expression": false,
        "Body language": false,
        "Confidence": false
      });
      const [cameraReady, setCameraReady] = useSt(false);
      const [micReady, setMicReady] = useSt(false);
      const [isSaving, setIsSaving] = useSt(false);
      const [isSaved, setIsSaved] = useSt(false);
      const [practiceHistoryId, setPracticeHistoryId] = useSt(null);
      const [isSubmitted, setIsSubmitted] = useSt(false);

      const remainingTime = Math.max(selectedDuration - elapsed, 0);
      const progress = Math.min((elapsed / selectedDuration) * 100, 100);
      const recordingStatus = isRecording ? "RECORDING" : recordedUrl ? "FINISHED" : "READY";
      const speakingTip = isRecording
        ? activeMode.tip
        : "Siapkan posisi kamera sejajar mata dan pastikan bahu terlihat natural.";

      const randomTopic = () => {
        const nextTopics = cameraTopics.filter((item) => item !== topic);
        const nextTopic = nextTopics[Math.floor(Math.random() * nextTopics.length)] || cameraTopics[0];
        setTopic(nextTopic);
      };

      const attachStream = (stream) => {
        streamRef.current = stream;
        setCameraReady(stream.getVideoTracks().length > 0);
        setMicReady(stream.getAudioTracks().length > 0);
        if (liveVideoRef.current) {
          liveVideoRef.current.srcObject = stream;
        }
      };

      const stopTracks = () => {
        if (streamRef.current) {
          streamRef.current.getTracks().forEach((track) => track.stop());
          streamRef.current = null;
        }
        setCameraReady(false);
        setMicReady(false);
      };

      const stopRecording = () => {
        clearInterval(timerRef.current);
        timerRef.current = null;
        setIsRecording(false);

        if (mediaRecorderRef.current && mediaRecorderRef.current.state !== "inactive") {
          mediaRecorderRef.current.stop();
        } else {
          stopTracks();
        }
      };

      const startRecording = async () => {
        try {
          const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
          attachStream(stream);
          chunksRef.current = [];
          recordedBlobRef.current = null;
          setRecordedUrl("");
          setElapsed(0);
          elapsedRef.current = 0;
          setIsSaved(false);
          setIsSubmitted(false);
          setPracticeHistoryId(null);

          const preferredMime = MediaRecorder.isTypeSupported("video/webm;codecs=vp9,opus")
            ? "video/webm;codecs=vp9,opus"
            : MediaRecorder.isTypeSupported("video/webm")
              ? "video/webm"
              : "";
          const recorder = preferredMime ? new MediaRecorder(stream, { mimeType: preferredMime }) : new MediaRecorder(stream);
          mediaRecorderRef.current = recorder;

          recorder.ondataavailable = (event) => {
            if (event.data.size > 0) chunksRef.current.push(event.data);
          };

          recorder.onstop = () => {
            const blob = new Blob(chunksRef.current, { type: preferredMime || "video/webm" });
            recordedBlobRef.current = blob;
            const videoUrl = URL.createObjectURL(blob);
            setRecordedUrl(videoUrl);
            stopTracks();
            setStatusMessage("Recording selesai. Simpan riwayat untuk menyimpan video ke server.");
          };

          recorder.start();
          setIsRecording(true);
          setStatusMessage("Recording berjalan. Jaga eye contact dan bahasa tubuh.");

          timerRef.current = setInterval(() => {
            setElapsed((current) => {
              const next = current + 1;
              elapsedRef.current = Math.min(next, selectedDuration);
              if (next >= selectedDuration) setTimeout(stopRecording, 0);
              return Math.min(next, selectedDuration);
            });
          }, 1000);
        } catch (error) {
          setStatusMessage("Camera atau microphone tidak bisa diakses. Periksa izin browser.");
        }
      };

      const saveCameraPractice = async () => {
        if (!recordedBlobRef.current) {
          setStatusMessage("Belum ada rekaman video yang bisa disimpan.");
          return;
        }
        if (!isLoggedIn) {
          setStatusMessage("Silakan login terlebih dahulu agar riwayat tersimpan ke akun.");
          return;
        }

        setIsSaving(true);
        setStatusMessage("Menyimpan video ke server...");

        try {
          const formData = new FormData();
          formData.append("action", "save_camera_practice");
          formData.append("topic", topic);
          formData.append("simulation_mode", activeMode.name);
          formData.append("duration", elapsedRef.current || selectedDuration);
          formData.append("video", recordedBlobRef.current, "camera_practice.webm");

          const response = await fetch("Latihan.php", { method: "POST", body: formData });
          const data = await response.json();

          if (!data.status) {
            setStatusMessage(data.message || "Gagal menyimpan riwayat camera practice.");
            setIsSaving(false);
            return;
          }

          setIsSaved(true);
          setPracticeHistoryId(data.practice_history_id || null);

          // Add to history list with server path
          const newItem = {
            id: `db-${data.practice_history_id}`,
            dbId: data.practice_history_id,
            topic: data.item.topic,
            date: new Date().toLocaleString("id-ID", { dateStyle: "medium", timeStyle: "short" }),
            duration: data.item.duration_seconds,
            videoUrl: data.item.video_path,
            confidence: data.item.simulation_mode || "Practice"
          };
          setHistory((current) => [newItem, ...current]);
          setStatusMessage(data.message + " Klik 'Kirim ke Mentor' untuk penilaian.");
        } catch (error) {
          setStatusMessage("Terjadi kesalahan saat menyimpan riwayat camera practice.");
        }
        setIsSaving(false);
      };

      const submitToMentor = async () => {
        if (!practiceHistoryId) {
          setStatusMessage("Simpan riwayat camera practice terlebih dahulu.");
          return;
        }
        if (!isLoggedIn) {
          setStatusMessage("Silakan login terlebih dahulu.");
          return;
        }

        setStatusMessage("Mengirim latihan ke mentor...");

        try {
          const formData = new FormData();
          formData.append("action", "submit_to_mentor");
          formData.append("practice_history_id", practiceHistoryId);
          formData.append("feature_type", "camera");

          const response = await fetch("Latihan.php", { method: "POST", body: formData });
          const data = await response.json();

          if (!data.status) {
            setStatusMessage(data.message || "Gagal mengirim ke mentor.");
            return;
          }

          setIsSubmitted(true);
          setStatusMessage(data.message);
        } catch (error) {
          setStatusMessage("Terjadi kesalahan saat mengirim ke mentor.");
        }
      };

      const replayVideo = (videoUrl) => {
        if (!videoUrl) return;
        setRecordedUrl(videoUrl);
        setTimeout(() => replayVideoRef.current?.play(), 100);
      };

      useEff(() => {
        return () => {
          clearInterval(timerRef.current);
          stopTracks();
        };
      }, []);

      const instruction = useMem(() => {
        if (isRecording) return "Bicara menghadap kamera, jaga gestur tetap natural, and gunakan jeda yang jelas.";
        return "Pilih durasi dan topik, lalu mulai recording untuk melatih ekspresi, eye contact, and body language.";
      }, [isRecording]);

      const toggleFocus = (item) => {
        setFocusProgress((current) => ({ ...current, [item]: !current[item] }));
      };

      return (
        <div className="space-y-6">
          <section className="camera-card p-6">
            <div className="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
              <div>
                <div className="text-sm font-black uppercase tracking-wide text-[#64C7D4]">Camera Speaking Practice</div>
                <h2 className="mt-2 text-3xl font-extrabold text-[#102A43]">Latihan dengan webcam</h2>
                <p className="mt-2 text-sm font-semibold leading-6 text-[#667085]">Latih ekspresi, eye contact, body language, and confidence langsung dari browser.</p>
              </div>
              <div className="flex flex-wrap gap-3">
                {cameraMentorInfo && <div className="camera-status-pill live">Mentor: {cameraMentorInfo.name}</div>}
                <button type="button" className="camera-btn btn-muted-camera" onClick={randomTopic}>
                  Topik Acak
                </button>
              </div>
            </div>
          </section>

          <section className="grid gap-4 md:grid-cols-3">
            {simulationModes.map((mode) => (
              <button
                key={mode.name}
                type="button"
                onClick={() => setActiveMode(mode)}
                className={`camera-card interactive p-5 text-left transition ${activeMode.name === mode.name ? "border-[#64C7D4] bg-[#EAF5F7]" : ""}`}
              >
                <div className="text-sm font-black uppercase tracking-wide text-[#64C7D4]">Simulation</div>
                <h3 className="mt-2 text-xl font-extrabold text-[#102A43]">{mode.name}</h3>
                <p className="mt-2 text-sm font-semibold leading-6 text-[#667085]">{mode.objective}</p>
              </button>
            ))}
          </section>

          <section className="grid gap-6 xl:grid-cols-[minmax(0,1.55fr)_minmax(300px,0.55fr)]">
            <div className="camera-card overflow-hidden">
              <div className="border-b border-[#e5e7eb] p-6">
                <div className="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                  <div>
                    <div className="text-sm font-black uppercase tracking-wide text-[#64C7D4]">Topic Prompt</div>
                    <h3 className="mt-2 text-2xl font-extrabold leading-snug text-[#102A43]">{topic}</h3>
                  </div>
                  <div className="flex flex-wrap items-center gap-2">
                    <div className={`camera-status-pill ${cameraReady ? "live" : ""}`}>
                      <span className={cameraReady ? "recording-dot" : ""}></span>
                      LIVE Camera
                    </div>
                    <div className={`camera-status-pill ${micReady ? "live" : ""}`}>
                      Mic {micReady ? "On" : "Standby"}
                    </div>
                    <div className={`camera-status-pill ${isRecording ? "live" : ""}`}>
                      {isRecording && <span className="recording-dot"></span>}
                      {recordingStatus}
                    </div>
                  </div>
                </div>
              </div>

              <div className="grid gap-6 p-6 lg:grid-cols-[minmax(0,1fr)_240px]">
                <div className={`relative overflow-hidden rounded-[18px] bg-[#102A43] transition duration-300 ${isRecording ? "recording-glow" : ""}`}>
                  <video ref={liveVideoRef} className="aspect-video w-full bg-[#102A43] object-cover" autoPlay muted playsInline></video>
                  <div className="pointer-events-none absolute left-4 top-4 flex items-center gap-2 rounded-full bg-black/35 px-3 py-2 text-xs font-black uppercase tracking-wide text-white">
                    {isRecording && <span className="recording-dot"></span>}
                    {recordingStatus}
                  </div>
                  <div className="pointer-events-none absolute right-4 top-4 hidden rounded-full bg-black/35 px-3 py-2 text-xs font-black uppercase tracking-wide text-white sm:block">
                    {activeMode.name} Mode
                  </div>
                  <div className={`audio-wave ${isRecording ? "active" : ""} pointer-events-none absolute bottom-4 left-4 rounded-full bg-black/35 px-3 py-2`}>
                    <span></span><span></span><span></span><span></span><span></span>
                  </div>
                  <div className="pointer-events-none absolute bottom-4 right-4 hidden rounded-full bg-black/35 px-3 py-2 text-xs font-black uppercase tracking-wide text-white sm:block">
                    Eye Contact Practice
                  </div>
                </div>

                <div className="space-y-4">
                  <div className="rounded-[18px] border border-[#e5e7eb] bg-[#fbfbfd] p-5">
                    <div className="text-sm font-extrabold text-[#667085]">Speaking Timer</div>
                    <div className="mt-2 text-6xl font-black tracking-tight text-[#102A43]">{formatCameraTime(remainingTime)}</div>
                    <div className="mt-4 h-3 overflow-hidden rounded-full bg-[#e5e7eb]">
                      <div className="h-full rounded-full bg-[#64C7D4] transition-all" style={{ width: `${progress}%` }}></div>
                    </div>
                    <div className="mt-3 text-xs font-black uppercase tracking-wide text-[#64C7D4]">{recordingStatus}</div>
                  </div>

                  <div className="rounded-[18px] border border-[#e5e7eb] bg-[#fbfbfd] p-5">
                    <div className="mb-3 text-sm font-extrabold text-[#667085]">Duration</div>
                    <div className="flex flex-wrap gap-2">
                      {cameraDurations.map((duration) => (
                        <button
                          key={duration.seconds}
                          type="button"
                          disabled={isRecording}
                          onClick={() => {
                            setSelectedDuration(duration.seconds);
                            setElapsed(0);
                          }}
                          className={`rounded-xl px-4 py-2 text-sm font-extrabold transition ${
                            selectedDuration === duration.seconds
                              ? "bg-[#64C7D4] text-white"
                              : "bg-white text-[#344054] ring-1 ring-[#d0d5dd]"
                          }`}
                        >
                          {duration.label}
                        </button>
                      ))}
                    </div>
                  </div>
                </div>
              </div>

              <div className="flex flex-col gap-4 border-t border-[#e5e7eb] p-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                  <div className="text-sm font-black uppercase tracking-wide text-[#64C7D4]">Instruction</div>
                  <p className="mt-1 max-w-2xl text-sm font-semibold leading-6 text-[#667085]">{instruction}</p>
                  <div className="mt-3 grid gap-2 md:grid-cols-2">
                    <div className="rounded-2xl bg-[#f8fafc] px-4 py-3 text-sm font-bold text-[#344054] ring-1 ring-[#e5e7eb]">
                      Objective: {activeMode.objective}
                    </div>
                    <div className="rounded-2xl bg-[#EAF5F7] px-4 py-3 text-sm font-bold text-[#245B7A] ring-1 ring-[#CDE5EA]">
                      Tip: {speakingTip}
                    </div>
                  </div>
                </div>
                <div className="flex flex-wrap gap-3">
                  <button type="button" className="camera-btn btn-primary-camera start-recording-cta" onClick={startRecording} disabled={isRecording}>Start Recording</button>
                  <button type="button" className="camera-btn btn-danger-camera" onClick={stopRecording} disabled={!isRecording}>Stop Recording</button>
                  <button type="button" className="camera-btn btn-primary-camera" onClick={saveCameraPractice} disabled={!recordedUrl || isRecording || isSaving || isSaved} style={{ background: '#102A43' }}>💾 Simpan Riwayat</button>
                  <button type="button" className="camera-btn btn-primary-camera" onClick={submitToMentor} disabled={!isSaved || isSubmitted} style={{ background: '#027a48' }}>📤 Kirim ke Mentor</button>
                </div>
              </div>

              {/* Status toast */}
              <div className="px-6 pb-6">
                <div className={`rounded-2xl px-4 py-3 text-sm font-bold ${statusMessage.includes("berhasil") || statusMessage.includes("dikirim") ? "bg-[#ecfdf3] text-[#027a48]" : statusMessage.includes("Gagal") || statusMessage.includes("kesalahan") || statusMessage.includes("tidak bisa") ? "bg-[#fef3f2] text-[#b42318]" : "bg-[#eff8ff] text-[#175cd3]"}`}>
                  {statusMessage}
                </div>
              </div>
            </div>

            <aside className="space-y-6">
              <div className="camera-card p-6">
                <h2 className="text-2xl font-extrabold text-[#102A43]">Replay Result</h2>
                <p className="mt-2 text-sm font-semibold leading-6 text-[#667085]">{recordedUrl ? "Putar ulang hasil rekaman terakhir." : "Hasil recording akan muncul di sini."}</p>
                <div className="mt-5 overflow-hidden rounded-[18px] bg-[#102A43]">
                  {recordedUrl ? (
                    <video ref={replayVideoRef} className="aspect-video w-full object-cover" src={recordedUrl} controls></video>
                  ) : (
                    <div className="flex aspect-video items-center justify-center text-sm font-bold text-white/75">Hasil recording akan muncul di sini</div>
                  )}
                </div>
              </div>

              <div className="camera-card p-6">
                <h2 className="text-2xl font-extrabold text-[#102A43]">Practice Focus</h2>
                <div className="mt-4 grid gap-3">
                  {focusItems.map((item) => (
                    <button
                      key={item}
                      type="button"
                      onClick={() => toggleFocus(item)}
                      className={`flex items-center justify-between rounded-2xl px-4 py-3 text-left text-sm font-extrabold ring-1 transition ${
                        focusProgress[item]
                          ? "bg-[#ecfdf3] text-[#027a48] ring-[#abefc6]"
                          : isRecording
                            ? "bg-[#EAF5F7] text-[#245B7A] ring-[#CDE5EA]"
                            : "bg-[#f8fafc] text-[#344054] ring-[#e5e7eb]"
                      }`}
                    >
                      <span>{item}</span>
                      <span>{focusProgress[item] ? "Completed" : isRecording ? "Active" : "Ready"}</span>
                    </button>
                  ))}
                </div>
              </div>

              <div className="camera-card p-6">
                <h2 className="text-2xl font-extrabold text-[#102A43]">Daily Challenge</h2>
                <p className="mt-2 text-sm font-semibold leading-6 text-[#667085]">Selesaikan 1 rekaman camera practice hari ini untuk mendapatkan 120 XP.</p>
                <div className="mt-4 h-3 overflow-hidden rounded-full bg-[#e5e7eb]">
                  <div className="h-full w-[68%] rounded-full bg-[#64C7D4]"></div>
                </div>
                <div className="mt-3 text-sm font-black text-[#64C7D4]">68% menuju target harian</div>
              </div>
            </aside>
          </section>

          <section className="camera-card p-6">
            <div className="mb-5">
              <h2 className="text-2xl font-extrabold text-[#102A43]">History Camera Practice</h2>
              <p className="mt-1 text-sm font-semibold text-[#667085]">Riwayat latihan camera practice yang tersimpan di akun.</p>
            </div>

            {history.length === 0 ? (
              <div className="rounded-2xl border border-dashed border-[#cbd5e1] bg-[#f8fafc] px-6 py-10 text-center text-sm font-semibold text-[#667085]">
                Belum ada riwayat camera practice. Mulai rekaman lalu simpan riwayat.
              </div>
            ) : (
              <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                {history.map((item) => (
                  <article key={item.id} className="camera-card interactive rounded-[18px] border border-[#e5e7eb] bg-[#fbfbfd] p-4">
                    <div className="history-video-card relative overflow-hidden rounded-2xl bg-[#102A43]">
                      {item.videoUrl ? (
                        <video className="aspect-video w-full object-cover" src={item.videoUrl} controls></video>
                      ) : (
                        <div className="flex aspect-video items-center justify-center px-4 text-center text-sm font-bold text-white/75">Video tidak tersedia</div>
                      )}
                    </div>
                    <h3 className="mt-4 text-base font-extrabold leading-snug text-[#102A43]">{item.topic}</h3>
                    <div className="mt-3 flex flex-wrap gap-2">
                      <span className="rounded-full bg-[#EAF5F7] px-3 py-1 text-xs font-black text-[#344054]">{item.date}</span>
                      <span className="rounded-full bg-[#EAF5F7] px-3 py-1 text-xs font-black text-[#245B7A]">{formatCameraTime(item.duration)}</span>
                      <span className="rounded-full bg-[#ecfdf3] px-3 py-1 text-xs font-black text-[#027a48]">{item.confidence}</span>
                    </div>
                  </article>
                ))}
              </div>
            )}
          </section>
        </div>
      );
    }

    ReactDOM.createRoot(document.getElementById("camera-practice-root")).render(<CameraPracticeDashboard />);
  </script>
</body>

</html>
