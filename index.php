<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vocalise - Ahlinya Public Speaking</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary-blue: #102A43;
            --primary-dark: #102A43;
            --accent-gold: #64C7D4;
            --accent-gold-light: #64C7D4;
            --white: #FFFFFF;
            --off-white: #EAF5F7;
            --text-dark: #1A1A1A;
            --text-light: #666666;
            --gradient-gold: linear-gradient(135deg, #64C7D4 0%, #64C7D4 100%);
            --gradient-blue: linear-gradient(135deg, #102A43 0%, #102A43 100%);
            --shadow-soft: 0 10px 30px rgba(0, 0, 0, 0.05);
            --shadow-gold: 0 10px 20px rgba(100, 199, 212, 0.2);
            --font-heading: 'Playfair Display', serif;
            --font-body: 'Inter', sans-serif;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--white);
            color: var(--text-dark);
            line-height: 1.6;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        ul {
            list-style: none;
        }


        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: var(--font-heading);
            color: var(--primary-blue);
            font-weight: 700;
            line-height: 1.2;
        }

        h1 {
            font-size: 4rem;
            margin-bottom: 1.5rem;
        }

        h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        p {
            margin-bottom: 1.5rem;
            color: var(--text-light);
            font-size: 1.1rem;
        }


        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .text-center {
            text-align: center;
        }

        .text-gold {
            color: var(--accent-gold);
        }

        .text-blue {
            color: var(--primary-blue);
        }

        .btn {
            display: inline-block;
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            font-size: 1rem;
            letter-spacing: 0.5px;
        }

        .btn-gold {
            background: var(--gradient-gold);
            color: var(--primary-blue);
            box-shadow: var(--shadow-gold);
        }

        .btn-gold:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(100, 199, 212, 0.3);
        }

        .btn-outline-blue {
            border: 2px solid var(--primary-blue);
            color: var(--primary-blue);
            background: transparent;
        }

        .btn-outline-blue:hover {
            background-color: var(--primary-blue);
            color: var(--white);
        }

        .section-padding {
            padding: 100px 0;
        }


        header {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 90px;
        }

        .logo {
            font-family: var(--font-heading);
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-blue);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logo-img {
            max-height: 80px;
            width: auto;
        }

        .nav-links {
            display: flex;
            gap: 40px;
        }

        .nav-links a {
            font-weight: 500;
            color: var(--primary-blue);
            transition: var(--transition);
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--accent-gold);
            transition: var(--transition);
        }

        .nav-links a:hover::after {
            width: 100%;
        }


        .hero {
            min-height: 90vh;
            display: flex;
            align-items: center;
            position: relative;
            background: radial-gradient(circle at top right, rgba(100, 199, 212, 0.1), transparent 40%),
                radial-gradient(circle at bottom left, rgba(16, 42, 67, 0.05), transparent 40%);
        }

        .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .hero-text h1 span {
            display: block;
            color: transparent;
            background: var(--gradient-gold);
            -webkit-background-clip: text;
            background-clip: text;
        }

        .hero-image {
            position: relative;
        }

        .hero-card {
            background: var(--white);
            padding: 40px;
            border-radius: 20px;
            box-shadow: var(--shadow-soft);
            position: relative;
            z-index: 2;
            border: 1px solid rgba(100, 199, 212, 0.2);
        }

        .hero-card::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 100px;
            height: 100px;
            background: var(--gradient-gold);
            border-radius: 50%;
            z-index: -1;
            opacity: 0.5;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: rgba(16, 42, 67, 0.05);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-blue);
            font-size: 1.5rem;
        }


        .features {
            background-color: var(--off-white);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin-top: 60px;
        }

        .feature-card {
            background: var(--white);
            padding: 40px;
            border-radius: 16px;
            transition: var(--transition);
            border: 1px solid transparent;
        }


        .features {
            background-color: var(--off-white);
            position: relative;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 40px;
            margin-top: 60px;
        }


        .cta {
            background: var(--primary-blue);
            color: var(--white);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 20px 20px;
            opacity: 0.3;
        }

        .cta-content {
            position: relative;
            z-index: 1;
            max-width: 700px;
            margin: 0 auto;
        }

        .cta h2 {
            color: var(--white);
        }

        .cta p {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 40px;
        }


        footer {
            background-color: var(--primary-dark);
            color: var(--white);
            padding: 60px 0 30px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 40px;
        }

        .footer-logo {
            font-family: var(--font-heading);
            font-size: 1.8rem;
            color: var(--white);
            margin-bottom: 15px;
            display: inline-block;
        }

        .footer-col h4 {
            color: var(--white);
            font-size: 1.1rem;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-col h4::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 30px;
            height: 2px;
            background-color: var(--accent-gold);
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col ul li {
            margin-bottom: 10px;
        }

        .footer-col ul li a {
            color: rgba(255, 255, 255, 0.7);
            transition: var(--transition);
            font-size: 0.95rem;
        }

        .footer-col ul li a:hover {
            color: var(--accent-gold);
            padding-left: 5px;
        }

        .footer-col p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }

        .social-links a {
            color: var(--white);
            background-color: rgba(255, 255, 255, 0.1);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 1.1rem;
            transition: var(--transition);
            font-weight: 600;
        }

        .social-links a:hover {
            color: var(--primary-dark);
            background-color: var(--accent-gold);
            transform: translateY(-3px);
        }

        .copyright {
            text-align: center;
            font-size: 0.9rem;
            opacity: 0.6;
        }


        @media (max-width: 768px) {
            h1 {
                font-size: 2.5rem;
            }

            .hero-content {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .hero-text {
                order: 2;
            }

            .hero-image {
                order: 1;
                margin-bottom: 40px;
            }

            .nav-links {
                display: none;
            }

            .footer-content {
                flex-direction: column;
                gap: 20px;
            }
        }


        .anim-element {
            opacity: 0;
            transition: all 0.8s ease-out;
            will-change: opacity, transform;
        }

        .anim-fade-up {
            transform: translateY(30px);
        }

        .anim-fade-down {
            transform: translateY(-30px);
        }

        .anim-fade-left {
            transform: translateX(30px);
        }

        .anim-fade-right {
            transform: translateX(-30px);
        }

        .anim-zoom-in {
            transform: scale(0.9);
        }

        .anim-element.visible {
            opacity: 1;
            transform: none;
        }


        .feature-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px 30px;
            border-radius: 24px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
            z-index: 1;
            text-align: center;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(100, 199, 212, 0.05) 0%, rgba(16, 42, 67, 0.05) 100%);
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: -1;
        }


        .feature-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: -150%;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, transparent 0%, rgba(255, 255, 255, 0.6) 50%, transparent 100%);
            transform: skewX(-25deg);
            transition: none;
            pointer-events: none;
            z-index: 2;
        }

        .feature-card:hover::after {
            left: 150%;
            transition: all 0.7s ease;
        }

        .feature-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 20px 40px rgba(16, 42, 67, 0.15);
            border-color: var(--accent-gold);
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient-blue);
            border-radius: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-gold);
            font-size: 2.5rem;
            margin-bottom: 25px;
            box-shadow: 0 10px 20px rgba(16, 42, 67, 0.3);
            transition: all 0.4s ease;
            position: relative;
            animation: floatIcon 3s ease-in-out infinite;
        }

        @keyframes floatIcon {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-5px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(6deg);
            box-shadow: 0 15px 30px rgba(100, 199, 212, 0.4);
            background: var(--gradient-gold);
            color: var(--primary-blue);
            animation: none;
        }

        .feature-card h3 {
            margin-bottom: 15px;
            font-size: 1.4rem;
            transition: color 0.3s ease;
        }

        .feature-card:hover h3 {
            color: var(--accent-gold);
        }

        .faq-item {
            background: var(--white);
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: var(--shadow-soft);
            overflow: hidden;
            transition: var(--transition);
            border: 1px solid transparent;
            cursor: pointer;
        }

        .faq-item:hover {
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .faq-item.active {
            border-color: var(--accent-gold);
            box-shadow: 0 15px 30px rgba(100, 199, 212, 0.15);
        }

        .faq-question {
            padding: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            color: var(--primary-blue);
            font-size: 1.15rem;
            user-select: none;
        }

        .faq-toggle {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(16, 42, 67, 0.05);
            border-radius: 50%;
            color: var(--accent-gold);
            transition: var(--transition);
            font-size: 1.2rem;
            font-weight: normal;
        }

        .faq-item.active .faq-toggle {
            background: var(--accent-gold);
            color: var(--white);
            transform: rotate(45deg);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease-out, opacity 0.3s ease;
            opacity: 0;
            padding: 0 24px;
        }

        .faq-item.active .faq-answer {
            opacity: 1;
            padding-bottom: 24px;
        }

        .mentor-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(16, 42, 67, 0.1) !important;
            border-bottom: 4px solid var(--accent-gold) !important;
        }
    </style>
    <link rel="stylesheet" href="brand-theme.css">
</head>

<body>


    <header>
        <div class="container">
            <nav>
                <a href="#" class="logo">
                    <img src="assets/logo_voca.png" alt="Vocalise Logo" class="logo-img">
                </a>
                <ul class="nav-links">
                    <li><a href="#curriculum">Kurikulum</a></li>
                    <li><a href="#mentors">Mentor</a></li>
                    <li><a href="#testimonials">Testimoni</a></li>
                    <li><a href="#faq">FAQ</a></li>
                </ul>
                <a href="login.php" class="btn btn-gold">Login</a>
            </nav>
        </div>
    </header>


    <section class="hero landing-hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Buka Potensi Suara Anda <br><span>Bicara dengan Percaya Diri</span></h1>
                    <p>Bergabunglah dengan platform utama untuk menguasai seni berbicara di depan umum. Atasi kecemasan,
                        pikat audiens Anda, dan memimpin dengan karisma yang tak terbantahkan.</p>
                    <div
                        style="display: flex; gap: 15px; flex-wrap: wrap; justify-content: center; @media(min-width: 769px) { justify-content: flex-start; }">
                        <a href="regis.php" class="btn btn-gold">Daftar Gratis Sekarang</a>
                        <a href="#" class="btn btn-outline-blue">Lihat Silabus</a>
                    </div>
                    <div style="margin-top: 40px; display: flex; align-items: center; gap: 15px;">
                        <div style="display: flex; color: var(--accent-gold); font-size: 1.2rem;">
                            ★ ★ ★ ★ ★
                        </div>
                        <span style="font-weight: 500; color: var(--text-light);">Dipercaya oleh 10.000+
                            Pembicara</span>
                    </div>
                </div>
                <div class="hero-image">
                    <div class="hero-card">
                        <div class="stat-item">
                            <div class="stat-icon">
                                👥
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 1.1rem;">Latihan Langsung</h4>
                                <span style="font-size: 0.9rem; color: var(--text-light);">Sesi grup harian</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                📈
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 1.1rem;">Analisis AI</h4>
                                <span style="font-size: 0.9rem; color: var(--text-light);">Umpan balik nada suara
                                    realtime</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                📜
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 1.1rem;">Sertifikasi</h4>
                                <span style="font-size: 0.9rem; color: var(--text-light);">Program terakreditasi
                                    A</span>
                            </div>
                        </div>
                        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                            <p style="margin: 0; font-style: italic; font-size: 0.95rem;">"Vocalise mengubah karier
                                saya. Saya berubah dari orang yang takut bicara menjadi pembicara TEDx dalam 6 bulan."
                            </p>
                            <div style="margin-top: 10px; font-weight: 600; color: var(--primary-blue);">- Syaiful
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="section-padding" style="background-color: var(--white);">
        <div class="container">
            <div class="text-center" style="max-width: 800px; margin: 0 auto 60px;">
                <h2 class="text-blue">Mengapa Memilih Vocalise?</h2>
                <p>Kami tidak hanya mengajarkan teori. Kami memberikan pengalaman transformasi total yang akan mengubah
                    cara Anda berkomunikasi selamanya.</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
                <div style="padding: 20px; border-left: 4px solid var(--accent-gold);">
                    <h3 style="font-size: 1.2rem;">Metode Terbukti</h3>
                    <p style="font-size: 1rem;">Kurikulum kami dikembangkan oleh pakar komunikasi kelas dunia dan
                        psikolog perilaku.</p>
                </div>
                <div style="padding: 20px; border-left: 4px solid var(--accent-gold);">
                    <h3 style="font-size: 1.2rem;">Komunitas Pendukung</h3>
                    <p style="font-size: 1rem;">Bergabunglah dengan ribuan pembelajar yang saling mendukung dan
                        memberikan umpan balik positif.</p>
                </div>
                <div style="padding: 20px; border-left: 4px solid var(--accent-gold);">
                    <h3 style="font-size: 1.2rem;">Akses Seumur Hidup</h3>
                    <p style="font-size: 1rem;">Sekali mendaftar, Anda mendapatkan akses seumur hidup ke semua materi
                        dan pembaruan di masa depan.</p>
                </div>
                <div style="padding: 20px; border-left: 4px solid var(--accent-gold);">
                    <h3 style="font-size: 1.2rem;">Mentoring Eksklusif</h3>
                    <p style="font-size: 1rem;">Dapatkan kesempatan untuk dibimbing langsung oleh para profesional
                        industri terkemuka.</p>
                </div>
            </div>
        </div>
    </section>


    <section id="curriculum" class="features section-padding">
        <div class="container">
            <div class="text-center" style="max-width: 700px; margin: 0 auto 60px;">
                <h2 class="text-blue">Seni Komunikasi</h2>
                <p>Kurikulum komprehensif kami mencakup setiap aspek berbicara yang efektif, mulai dari kontrol vokal
                    hingga isyarat non-verbal.</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        🎤
                    </div>
                    <h3>Penguasaan Vokal</h3>
                    <p>Pelajari cara mengontrol nada, intonasi, dan kecepatan bicara Anda untuk menarik perhatian dan
                        menyampaikan otoritas.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        👨‍🏫
                    </div>
                    <h3>Bahasa Tubuh</h3>
                    <p>Kuasai sinyal non-verbal yang membangun kepercayaan dan keterlibatan dengan audiens Anda secara
                        instan.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        📖
                    </div>
                    <h3>Storytelling</h3>
                    <p>Buat narasi menarik yang beresonansi secara emosional dan membuat pesan Anda tak terlupakan.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        🧠
                    </div>
                    <h3>Psikologi Audiens</h3>
                    <p>Pahami cara kerja pikiran audiens Anda untuk menyusun pesan yang persuasif dan berdampak.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        📹
                    </div>
                    <h3>Presentasi Virtual</h3>
                    <p>Teknik khusus untuk tampil memukau di depan kamera dan dalam pertemuan online.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        🤝
                    </div>
                    <h3>Negosiasi</h3>
                    <p>Gunakan keterampilan bicara Anda untuk memenangkan negosiasi dan mencapai kesepakatan terbaik.
                    </p>
                </div>
            </div>
        </div>
    </section>


    <section id="mentors" class="section-padding" style="background-color: var(--white);">
        <div class="container">
            <div class="text-center" style="margin-bottom: 60px;">
                <h2 class="text-blue">Mentor Ahli Kami</h2>
                <p>Belajar langsung dari para praktisi dan ahli komunikasi terbaik di bidangnya.</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px;">
                <div style="background: var(--off-white); border-radius: 16px; overflow: hidden; box-shadow: var(--shadow-soft); text-align: center; transition: var(--transition); border-bottom: 4px solid transparent;"
                    class="mentor-card">
                    <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80"
                        alt="Budi Santoso" style="width: 100%; height: 250px; object-fit: cover;">
                    <div style="padding: 24px;">
                        <h3 style="margin-bottom: 5px; font-size: 1.3rem;">Budi Santoso</h3>
                        <p style="color: var(--accent-gold); font-weight: 600; margin-bottom: 15px; font-size: 0.9rem;">
                            Pakar Public Speaking</p>
                        <p style="font-size: 0.95rem; color: var(--text-light);">Pengalaman lebih dari 15 tahun membantu
                            ribuan profesional tampil percaya diri di panggung.</p>
                    </div>
                </div>
                <div style="background: var(--off-white); border-radius: 16px; overflow: hidden; box-shadow: var(--shadow-soft); text-align: center; transition: var(--transition); border-bottom: 4px solid transparent;"
                    class="mentor-card">
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80"
                        alt="Siti Aminah" style="width: 100%; height: 250px; object-fit: cover;">
                    <div style="padding: 24px;">
                        <h3 style="margin-bottom: 5px; font-size: 1.3rem;">Siti Aminah</h3>
                        <p style="color: var(--accent-gold); font-weight: 600; margin-bottom: 15px; font-size: 0.9rem;">
                            Pelatih Vokal & Artikulasi</p>
                        <p style="font-size: 0.95rem; color: var(--text-light);">Fokus pada teknik pernapasan, intonasi,
                            dan kejelasan suara untuk penyampaian pesan yang maksimal.</p>
                    </div>
                </div>
                <div style="background: var(--off-white); border-radius: 16px; overflow: hidden; box-shadow: var(--shadow-soft); text-align: center; transition: var(--transition); border-bottom: 4px solid transparent;"
                    class="mentor-card">
                    <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80"
                        alt="Andi Wijaya" style="width: 100%; height: 250px; object-fit: cover;">
                    <div style="padding: 24px;">
                        <h3 style="margin-bottom: 5px; font-size: 1.3rem;">Andi Wijaya</h3>
                        <p style="color: var(--accent-gold); font-weight: 600; margin-bottom: 15px; font-size: 0.9rem;">
                            Master Storyteller</p>
                        <p style="font-size: 0.95rem; color: var(--text-light);">Ahli dalam merangkai cerita yang
                            menyentuh emosi audiens dan persuasif.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section id="testimonials" class="section-padding" style="background-color: var(--off-white);">
        <div class="container">
            <div class="text-center" style="margin-bottom: 60px;">
                <h2 class="text-blue">Apa Kata Alumni Kami</h2>
                <p>Kisah sukses nyata dari orang-orang yang telah mengubah hidup mereka.</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px;">
                <div
                    style="background: var(--off-white); padding: 30px; border-radius: 10px; box-shadow: var(--shadow-soft);">
                    <div style="color: var(--accent-gold); margin-bottom: 15px; font-size: 2rem;">❝</div>
                    <p style="font-style: italic;">"Saya dulu gemetar setiap kali harus presentasi. Vocalise memberi
                        saya alat dan kepercayaan diri untuk tidak hanya berbicara, tetapi juga menikmati momen di
                        panggung."</p>
                    <div style="margin-top: 20px; font-weight: 600;">- Ilman Ethanol, Mahasiswa</div>
                </div>
                <div
                    style="background: var(--off-white); padding: 30px; border-radius: 10px; box-shadow: var(--shadow-soft);">
                    <div style="color: var(--accent-gold); margin-bottom: 15px; font-size: 2rem;">❝</div>
                    <p style="font-style: italic;">"Investasi terbaik untuk karier saya. Teknik storytelling yang
                        diajarkan sangat brilian dan mudah diterapkan dalam pekerjaan sehari-hari."</p>
                    <div style="margin-top: 20px; font-weight: 600;">- Riski Galon, Mahasiswa</div>
                </div>
                <div
                    style="background: var(--off-white); padding: 30px; border-radius: 10px; box-shadow: var(--shadow-soft);">
                    <div style="color: var(--accent-gold); margin-bottom: 15px; font-size: 2rem;">❝</div>
                    <p style="font-style: italic;">"Mentornya sangat suportif dan materi yang diberikan sangat relevan
                        dengan tantangan komunikasi modern. Sangat direkomendasikan!"</p>
                    <div style="margin-top: 20px; font-weight: 600;">- Fadhillah, Mahasiswa</div>
                </div>
            </div>
        </div>
    </section>


    <section id="faq" class="section-padding" style="background-color: var(--white);">
        <div class="container">
            <div class="text-center" style="margin-bottom: 60px;">
                <h2 class="text-blue">Pertanyaan yang Sering Diajukan</h2>
                <p>Jawaban untuk pertanyaan umum tentang program kami.</p>
            </div>
            <div style="max-width: 800px; margin: 0 auto;">
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Apakah program ini cocok untuk pemula?</span>
                        <div class="faq-toggle">+</div>
                    </div>
                    <div class="faq-answer">
                        <p style="margin-top: 10px; margin-bottom: 0;">Tentu saja! Program kami dirancang untuk semua
                            tingkatan, mulai dari pemula yang gugup hingga pembicara berpengalaman yang ingin memoles
                            keterampilan mereka.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Berapa lama waktu yang dibutuhkan untuk melihat hasil?</span>
                        <div class="faq-toggle">+</div>
                    </div>
                    <div class="faq-answer">
                        <p style="margin-top: 10px; margin-bottom: 0;">Banyak siswa melaporkan peningkatan kepercayaan
                            diri
                            dalam 2-3 minggu pertama. Namun, penguasaan penuh adalah perjalanan berkelanjutan.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Apakah ada jaminan uang kembali?</span>
                        <div class="faq-toggle">+</div>
                    </div>
                    <div class="faq-answer">
                        <p style="margin-top: 10px; margin-bottom: 0;">Ya, kami menawarkan jaminan uang kembali 30 hari
                            jika
                            Anda merasa program ini tidak sesuai dengan kebutuhan Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="cta section-padding">
        <div class="container">
            <div class="cta-content">
                <h2>Siap Menemukan Suara Anda?</h2>
                <p>Bergabunglah dengan ribuan profesional yang telah meningkatkan karier mereka melalui kekuatan bicara.
                </p>
                <a href="login.php" class="btn btn-gold">Gabung Vocalise Sekarang</a>
            </div>
        </div>
    </section>


    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-col" style="grid-column: span 2;">
                    <div class="footer-logo">Vocalise</div>
                    <p style="max-width: 350px;">Platform e-learning terdepan untuk mengembangkan kemampuan Public
                        Speaking, Komunikasi, dan Kepercayaan Diri Anda dalam hitungan minggu.</p>
                </div>

                <div class="footer-col">
                    <h4>Tautan Cepat</h4>
                    <ul>
                        <li><a href="#curriculum">Kurikulum</a></li>
                        <li><a href="#mentors">Mentor</a></li>
                        <li><a href="#testimonials">Testimoni</a></li>
                        <li><a href="#faq">FAQ</a></li>
                        <li><a href="login.php">Login Member</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Kontak Kami</h4>
                    <p>📞 +62 853-5901-3034</p>
                    <p>✉️ Vocalise@gmail.com</p>

                    <div class="social-links">
                        <a href="#">IG</a>
                        <a href="#">TW</a>
                        <a href="#">LI</a>
                        <a href="#">YT</a>
                    </div>
                </div>
            </div>
            <div class="copyright">
                &copy; 2024 Vocalise Academy. Hak Cipta Dilindungi Undang-Undang.
            </div>
        </div>
    </footer>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: "0px 0px -50px 0px"
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    } else {
                        entry.target.classList.remove('visible');
                    }
                });
            }, observerOptions);

            const animate = (selector, animClass, delay = 0) => {
                const elements = document.querySelectorAll(selector);
                elements.forEach((el, index) => {
                    el.classList.add('anim-element', animClass);

                    if (delay === 'stagger') {
                        el.style.transitionDelay = `${index * 100}ms`;
                    } else if (typeof delay === 'number' && delay > 0) {
                        el.style.transitionDelay = `${delay}ms`;
                    }
                    observer.observe(el);
                });
            };


            animate('header', 'anim-fade-down');


            animate('.hero-text h1', 'anim-fade-right');
            animate('.hero-text p', 'anim-fade-right', 200);
            animate('.hero-text > div:nth-of-type(1)', 'anim-fade-right', 400);
            animate('.hero-text > div:nth-of-type(2)', 'anim-fade-right', 600);
            animate('.hero-card', 'anim-fade-left', 200);


            const whyChooseSection = document.querySelector('section:nth-of-type(2)');
            if (whyChooseSection) {
                const cards = whyChooseSection.querySelectorAll('.container > div:nth-child(2) > div');
                cards.forEach((card, index) => {
                    card.classList.add('anim-element', 'anim-fade-up');
                    card.style.transitionDelay = `${index * 100}ms`;
                    observer.observe(card);
                });

                animate('section:nth-of-type(2) h2', 'anim-fade-up');
                animate('section:nth-of-type(2) .text-center > p', 'anim-fade-up', 100);
            }


            animate('#curriculum h2', 'anim-fade-up');
            animate('#curriculum .text-center > p', 'anim-fade-up', 100);
            animate('.feature-card', 'anim-fade-up', 'stagger');


            animate('#mentors h2', 'anim-fade-up');
            animate('#mentors .text-center > p', 'anim-fade-up', 100);
            const mentorCards = document.querySelectorAll('.mentor-card');
            mentorCards.forEach((card, index) => {
                card.classList.add('anim-element', 'anim-fade-up');
                card.style.transitionDelay = `${index * 150}ms`;
                observer.observe(card);
            });


            animate('#testimonials h2', 'anim-fade-up');
            animate('#testimonials .text-center > p', 'anim-fade-up', 100);
            const testimonialCards = document.querySelectorAll('#testimonials .container > div:nth-child(2) > div');
            testimonialCards.forEach((card, index) => {
                card.classList.add('anim-element', 'anim-zoom-in');
                card.style.transitionDelay = `${index * 150}ms`;
                observer.observe(card);
            });


            animate('#faq h2', 'anim-fade-up');
            animate('#faq .text-center > p', 'anim-fade-up', 100);
            animate('.faq-item', 'anim-fade-up', 'stagger');


            const faqItems = document.querySelectorAll('.faq-item');
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question');
                question.addEventListener('click', () => {
                    const isActive = item.classList.contains('active');


                    faqItems.forEach(otherItem => {
                        otherItem.classList.remove('active');
                        otherItem.querySelector('.faq-answer').style.maxHeight = null;
                    });


                    if (!isActive) {
                        item.classList.add('active');
                        const answer = item.querySelector('.faq-answer');
                        answer.style.maxHeight = answer.scrollHeight + "px";
                    }
                });
            });


            animate('.cta-content', 'anim-zoom-in');
        });
    </script>
</body>

</html>
