<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hierarchix - Sistem Pendukung Keputusan AHP 3D</title>

    <!-- Google Fonts: Outfit, Plus Jakarta Sans, & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Three.js (r128) Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

    <!-- Script Deteksi Dark Mode Otomatis Mengikuti OS Pengguna (Tanpa Switch Button) -->
    <script>
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
            if (event.matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });
    </script>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        mint: {
                            DEFAULT: '#98FBCB',
                            light: '#C6FDE4',
                            dark: '#0F766E',
                            deep: '#042F2E',
                        },
                    }
                }
            }
        }
    </script>

    <!-- Custom Styling: Clean Flat & Subtle Glassmorphism -->
    <style>
        body {
            transition: background-color 0.3s ease, color 0.3s ease;
            overflow-x: hidden;
        }

        /* Clean Glassmorphic & Flat Surfaces */
        .glass-panel {
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.85);
            box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.05);
            border-radius: 20px;
        }

        .dark .glass-panel {
            background-color: rgba(30, 41, 59, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(51, 65, 85, 0.75);
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.3);
        }

        .glass-card {
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.85);
            box-shadow: 0 4px 12px -2px rgba(15, 23, 42, 0.04);
            border-radius: 20px;
            transition: all 0.25s ease;
        }

        .dark .glass-card {
            background-color: rgba(30, 41, 59, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(51, 65, 85, 0.75);
            box-shadow: 0 4px 16px -2px rgba(0, 0, 0, 0.25);
        }

        .glass-card:hover {
            border-color: #98FBCB;
            transform: translateY(-2px);
        }

        /* Clean Flat Buttons dengan Kontras Teks Gelap Terjamin */
        .btn-primary {
            background-color: #98FBCB;
            color: #042F2E !important;
            font-weight: 700;
            border-radius: 9999px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px -1px rgba(152, 251, 203, 0.5);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .dark .btn-primary {
            background-color: #98FBCB;
            color: #042F2E !important;
        }

        .btn-primary:hover {
            background-color: #7ce7b4;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px -1px rgba(152, 251, 203, 0.6);
            color: #042F2E !important;
        }

        .btn-secondary {
            background-color: rgba(255, 255, 255, 0.9);
            color: #1E293B;
            border: 1px solid #E2E8F0;
            border-radius: 9999px;
            font-weight: 600;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .dark .btn-secondary {
            background-color: rgba(30, 41, 59, 0.9);
            color: #F1F5F9;
            border-color: #334155;
        }

        .btn-secondary:hover {
            border-color: #98FBCB;
            color: #0F766E;
        }

        .dark .btn-secondary:hover {
            color: #98FBCB;
        }

        /* Kompatibilitas Class neu-cta-btn & neu-btn */
        .neu-cta-btn, .neu-btn {
            background-color: #98FBCB;
            color: #042F2E !important;
            font-weight: 700;
            border-radius: 9999px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px -1px rgba(152, 251, 203, 0.5);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .neu-cta-btn:hover, .neu-btn:hover {
            background-color: #7ce7b4;
            color: #042F2E !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px -1px rgba(152, 251, 203, 0.6);
        }

        /* Math Terminal Box (Menyala & Kontras Ekstrem) */
        .math-terminal-box {
            background-color: #0B0F19;
            border: 1px solid #1E293B;
            border-radius: 16px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.45);
            overflow: hidden;
        }

        .math-terminal-header {
            background-color: #0F172A;
            padding: 8px 14px;
            border-bottom: 1px solid #1E293B;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .math-glowing-text {
            color: #98FBCB;
            text-shadow: 0 0 14px rgba(152, 251, 203, 0.6), 0 0 2px rgba(152, 251, 203, 0.85);
        }

        /* Ambient Glow Background */
        .ambient-soft {
            position: absolute;
            border-radius: 9999px;
            filter: blur(100px);
            pointer-events: none;
            opacity: 0.15;
            z-index: 0;
        }

        /* Sembunyikan scrollbar pada Carousel Mobile */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="min-h-full font-sans antialiased bg-gray-50 text-slate-800 dark:bg-slate-900 dark:text-slate-100 relative">

    <!-- Ambient Subtle Background Glow -->
    <div class="ambient-soft w-96 h-96 bg-[#98FBCB] top-10 left-10"></div>
    <div class="ambient-soft w-96 h-96 bg-teal-300 dark:bg-teal-800 top-72 right-10"></div>
    <div class="ambient-soft w-96 h-96 bg-emerald-300 dark:bg-emerald-900 top-[60rem] left-1/4"></div>

    <!-- Sticky Navigation Bar dengan Dynamic Scrollspy & Adaptive Glassmorphism -->
    <header id="mainHeader" class="sticky top-0 z-50 w-full transition-all duration-300 backdrop-blur-sm bg-white/40 dark:bg-[#090D16]/40 border-b border-transparent">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
            <!-- 1. KIRI: Logo Freestanding Hierarchix & Brand Name -->
            <div class="flex items-center space-x-3 flex-shrink-0">
                <a href="#hero" class="flex items-center space-x-2.5 group">
                    <svg class="w-8 h-8 sm:w-9 sm:h-9 text-[#98FBCB] drop-shadow-[0_0_8px_rgba(152,251,203,0.5)] group-hover:scale-105 transition-transform flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <!-- Top Node -->
                        <circle cx="12" cy="4.5" r="2.2"/>

                        <!-- Branches from Top Node -->
                        <path d="M10.2 5.8 L6.8 9.2"/>
                        <path d="M12 6.7 L12 9.7"/>
                        <path d="M13.8 5.8 L17.2 9.2"/>

                        <!-- Mid Nodes -->
                        <circle cx="5" cy="11" r="1.8"/>
                        <circle cx="12" cy="11.5" r="1.3"/>
                        <circle cx="19" cy="11" r="1.8"/>

                        <!-- Left Sub-branches -->
                        <path d="M5 12.8 L5 16"/>
                        <path d="M2 16 L8 16"/>
                        <path d="M2 16 L2 18"/>
                        <path d="M5 16 L5 18"/>
                        <path d="M8 16 L8 18"/>

                        <!-- Left Leaf Nodes -->
                        <circle cx="2" cy="19.2" r="1"/>
                        <circle cx="5" cy="19.2" r="1"/>
                        <circle cx="8" cy="19.2" r="1"/>

                        <!-- Right Sub-branches -->
                        <path d="M19 12.8 L19 16"/>
                        <path d="M16 16 L22 16"/>
                        <path d="M16 16 L16 18"/>
                        <path d="M19 16 L19 18"/>
                        <path d="M22 16 L22 18"/>

                        <!-- Right Leaf Nodes -->
                        <circle cx="16" cy="19.2" r="1"/>
                        <circle cx="19" cy="19.2" r="1"/>
                        <circle cx="22" cy="19.2" r="1"/>
                    </svg>
                    <span class="font-display font-extrabold text-slate-900 dark:text-white text-lg sm:text-xl tracking-tight">Hierarchix</span>
                </a>
            </div>

            <!-- 2. TENGAH: Tautan Navigasi Seragam dengan Kontras Pekat Light Mode & Scrollspy Dot Indicator -->
            <div class="hidden md:flex items-center space-x-8 text-xs sm:text-sm">
                <a href="#hero" class="nav-link relative group py-1 flex flex-col items-center text-slate-900 dark:text-slate-100 font-bold hover:text-teal-700 dark:hover:text-[#98FBCB] transition-colors" data-section="hero">
                    <span class="nav-text">Beranda</span>
                    <!-- Dot Indicator Menu Aktif -->
                    <span class="nav-dot w-1.5 h-1.5 rounded-full bg-[#98FBCB] shadow-[0_0_8px_#98FBCB] mt-0.5 opacity-100 scale-100 transition-all duration-300"></span>
                </a>
                <a href="#pengenalan" class="nav-link relative group py-1 flex flex-col items-center text-slate-900 dark:text-slate-100 font-bold hover:text-teal-700 dark:hover:text-[#98FBCB] transition-colors" data-section="pengenalan">
                    <span class="nav-text">Pengenalan</span>
                    <!-- Dot Indicator -->
                    <span class="nav-dot w-1.5 h-1.5 rounded-full bg-[#98FBCB] shadow-[0_0_8px_#98FBCB] mt-0.5 opacity-0 scale-0 transition-all duration-300"></span>
                </a>
                <a href="#formula" class="nav-link relative group py-1 flex flex-col items-center text-slate-900 dark:text-slate-100 font-bold hover:text-teal-700 dark:hover:text-[#98FBCB] transition-colors" data-section="formula">
                    <span class="nav-text">Rumus</span>
                    <!-- Dot Indicator -->
                    <span class="nav-dot w-1.5 h-1.5 rounded-full bg-[#98FBCB] shadow-[0_0_8px_#98FBCB] mt-0.5 opacity-0 scale-0 transition-all duration-300"></span>
                </a>
                <a href="#cta-bottom" class="nav-link relative group py-1 flex flex-col items-center text-slate-900 dark:text-slate-100 font-bold hover:text-teal-700 dark:hover:text-[#98FBCB] transition-colors" data-section="cta-bottom">
                    <span class="nav-text">Simulasi</span>
                    <!-- Dot Indicator -->
                    <span class="nav-dot w-1.5 h-1.5 rounded-full bg-[#98FBCB] shadow-[0_0_8px_#98FBCB] mt-0.5 opacity-0 scale-0 transition-all duration-300"></span>
                </a>
            </div>

            <!-- 3. KANAN: CTA Primer 'Mulai Simulasi' (Pill - Tanpa Menu Login) -->
            <div class="flex items-center space-x-3 sm:space-x-4 flex-shrink-0">
                <a href="/ahp" class="btn-primary neu-cta-btn px-5 py-2 text-xs sm:text-sm rounded-full font-bold">
                    <span>Mulai Simulasi</span>
                </a>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT CONTAINER -->
    <main class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16 sm:space-y-24 lg:space-y-28 pt-4 sm:pt-8 pb-16 sm:pb-20">

        <!-- ==========================================
             1. HERO SECTION (Floating Abstract Platonic 3D Solids & Dua Kolom)
             ========================================== -->
        <section id="hero" class="pt-2 sm:pt-6 lg:pt-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 lg:gap-8 items-center">

                <!-- Kolom Kiri: Tajuk, Penjelasan Singkat & CTA Mulai Simulasi -->
                <div class="lg:col-span-7 space-y-4 sm:space-y-6 text-left">
                    <!-- Badge Pengantar -->
                    <div class="inline-flex items-center space-x-2 px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-full bg-[#98FBCB]/25 border border-[#98FBCB]/50 text-[11px] sm:text-xs font-semibold text-teal-900 dark:text-[#98FBCB]">
                        <span class="w-2 h-2 rounded-full bg-[#98FBCB] animate-pulse"></span>
                        <span>Analytic Hierarchy Process &bull; Thomas L. Saaty</span>
                    </div>

                    <!-- Tajuk Utama -->
                    <h1 class="font-display font-extrabold text-3xl sm:text-4xl lg:text-[3.3rem] text-slate-900 dark:text-white tracking-tight leading-[1.15] sm:leading-[1.12]">
                        Sistem Pendukung Keputusan <span class="bg-gradient-to-r from-[#0F766E] via-teal-600 to-[#98FBCB] dark:from-[#98FBCB] dark:to-teal-300 bg-clip-text text-transparent">AHP 3D</span>
                    </h1>

                    <!-- Penjelasan Singkat Ramah Pengguna Awam -->
                    <p class="text-sm sm:text-base lg:text-lg text-slate-600 dark:text-slate-300 font-normal leading-relaxed max-w-xl">
                        Aplikasi pendukung keputusan yang menguraikan persoalan multikriteria secara ilmiah. Menentukan alternatif terbaik dengan membandingkan nilai kriteria secara terstruktur, objektif, dan akurat melalui pembobotan matematis ilmiah.
                    </p>

                    <!-- Tombol CTA 'Mulai Simulasi' -->
                    <div class="flex flex-wrap items-center gap-3 sm:gap-4 pt-1 sm:pt-2">
                        <a href="/ahp" class="btn-primary neu-cta-btn px-6 sm:px-8 py-3 sm:py-3.5 text-xs sm:text-sm md:text-base tracking-wide group">
                            <span>Mulai Simulasi</span>
                            <span class="ml-2.5 w-6 h-6 rounded-full bg-slate-950/10 flex items-center justify-center text-slate-950 group-hover:translate-x-1 transition-transform">
                                &rarr;
                            </span>
                        </a>

                        <a href="#formula" class="btn-secondary px-5 sm:px-6 py-3 sm:py-3.5 text-xs sm:text-sm">
                            <span>Pelajari Rumus AHP</span>
                            <span class="ml-1.5">&darr;</span>
                        </a>
                    </div>

                    <!-- Feature Checks -->
                    <div class="pt-1 sm:pt-2 flex flex-wrap items-center gap-x-4 gap-y-2 sm:space-x-6 text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">
                        <div class="flex items-center space-x-1.5">
                            <span class="text-[#0F766E] dark:text-[#98FBCB] font-bold">&#10003;</span>
                            <span>Skala Saaty 9-1-9</span>
                        </div>
                        <div class="flex items-center space-x-1.5">
                            <span class="text-[#0F766E] dark:text-[#98FBCB] font-bold">&#10003;</span>
                            <span>Uji Konsistensi (CR &le; 0,1)</span>
                        </div>
                        <div class="flex items-center space-x-1.5">
                            <span class="text-[#0F766E] dark:text-[#98FBCB] font-bold">&#10003;</span>
                            <span>Visualisasi 3D Interaktif</span>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: 3D Platonic Solids Melayang Megah (Tanpa Frame Pembatas / Seamless) -->
                <div class="lg:col-span-5 relative flex items-center justify-center">
                    <!-- Wadah Canvas 3D Transparan Melebur Langsung dengan Background Halaman -->
                    <div id="hero3DContainer" class="w-full h-[300px] sm:h-[380px] lg:h-[540px] relative flex items-center justify-center pointer-events-auto">
                        <canvas id="hero3DCanvas" class="w-full h-full block"></canvas>
                    </div>
                </div>

            </div>
        </section>


        <!-- ==========================================
             2. PENGENALAN AHP (Thomas L. Saaty & Struktur Hierarki)
             ========================================== -->
        <section id="pengenalan" class="space-y-10 reveal-on-scroll">
            <div class="text-center space-y-3 max-w-2xl mx-auto">
                <span class="px-3.5 py-1 rounded-full bg-[#98FBCB]/25 text-[#0F766E] dark:text-[#98FBCB] border border-[#98FBCB]/40 text-xs font-bold tracking-wider uppercase">
                    Konsep Fundamental
                </span>
                <h2 class="font-display font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white tracking-tight">
                    Pengenalan Metode AHP
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                    Metode <em>Analytic Hierarchy Process</em> (AHP) digagas oleh <strong>Thomas L. Saaty</strong> pada tahun 1970-an untuk menguraikan permasalahan multikriteria yang rumit ke dalam struktur hierarki berjenjang: <strong>Tujuan (Goal)</strong>, <strong>Kriteria</strong>, dan <strong>Alternatif</strong>.
                </p>
            </div>

            <!-- Dekomposisi Struktur Tiga Tingkat (Goal -> Kriteria -> Alternatif) -->
            <div class="glass-card p-6 sm:p-8">
                <div class="text-center space-y-1 mb-6">
                    <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white">
                        Struktur Hierarki Keputusan Tiga Tingkat
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Alur hierarkis sistematis dalam pemecahan persoalan keputusan:
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <!-- Level 1: Tujuan (Goal) -->
                    <div class="p-5 rounded-2xl bg-white/70 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80 text-center space-y-2 border-t-4 border-t-[#98FBCB]">
                        <div class="w-8 h-8 mx-auto rounded-lg bg-[#98FBCB]/30 text-slate-900 dark:text-[#98FBCB] font-bold text-xs flex items-center justify-center">
                            L-1
                        </div>
                        <h4 class="font-display font-bold text-base text-slate-900 dark:text-white">Tujuan (Goal)</h4>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            Sasaran utama yang ingin dicapai pada puncak hierarki keputusan (misal: Pemilihan Mahasiswa Berprestasi).
                        </p>
                    </div>

                    <!-- Level 2: Kriteria -->
                    <div class="p-5 rounded-2xl bg-white/70 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80 text-center space-y-2 border-t-4 border-t-teal-500">
                        <div class="w-8 h-8 mx-auto rounded-lg bg-teal-100 dark:bg-teal-900/40 text-teal-800 dark:text-teal-200 font-bold text-xs flex items-center justify-center">
                            L-2
                        </div>
                        <h4 class="font-display font-bold text-base text-slate-900 dark:text-white">Kriteria</h4>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            Parameter atau tolok ukur penilaian independen yang dijadikan acuan komparasi (IPK, Prestasi, Sikap, dll).
                        </p>
                    </div>

                    <!-- Level 3: Alternatif -->
                    <div class="p-5 rounded-2xl bg-white/70 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80 text-center space-y-2 border-t-4 border-t-emerald-500">
                        <div class="w-8 h-8 mx-auto rounded-lg bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-200 font-bold text-xs flex items-center justify-center">
                            L-3
                        </div>
                        <h4 class="font-display font-bold text-base text-slate-900 dark:text-white">Alternatif</h4>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            Kandidat atau opsi pilihan pada tingkat terbawah yang diperingkatkan berdasarkan prioritas global.
                        </p>
                    </div>
                </div>
            </div>

            <!-- 4 Langkah Inti AHP -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="glass-card p-5 flex flex-col justify-between">
                    <div class="space-y-2.5">
                        <div class="w-9 h-9 rounded-lg bg-[#98FBCB]/25 text-[#0F766E] dark:text-[#98FBCB] font-display font-extrabold text-sm flex items-center justify-center">
                            01
                        </div>
                        <h4 class="font-display font-bold text-base text-slate-900 dark:text-white">Dekomposisi Hierarki</h4>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            Membagi persoalan kompleks ke dalam Tujuan, Kriteria hingga Alternatif.
                        </p>
                    </div>
                    <span class="mt-3 pt-2 border-t border-slate-200/60 dark:border-slate-700/60 text-[11px] font-semibold text-[#0F766E] dark:text-[#98FBCB]">
                        Penyusunan Hierarki &rarr;
                    </span>
                </div>

                <div class="glass-card p-5 flex flex-col justify-between">
                    <div class="space-y-2.5">
                        <div class="w-9 h-9 rounded-lg bg-teal-100 dark:bg-teal-900/40 text-teal-800 dark:text-teal-200 font-display font-extrabold text-sm flex items-center justify-center">
                            02
                        </div>
                        <h4 class="font-display font-bold text-base text-slate-900 dark:text-white">Perbandingan Berpasangan</h4>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            Menilai preferensi relatif antar-elemen dengan Skala Saaty 1-9 dan sifat resiprokal.
                        </p>
                    </div>
                    <span class="mt-3 pt-2 border-t border-slate-200/60 dark:border-slate-700/60 text-[11px] font-semibold text-teal-600 dark:text-teal-300">
                        Skala Saaty 9-1-9 &rarr;
                    </span>
                </div>

                <div class="glass-card p-5 flex flex-col justify-between">
                    <div class="space-y-2.5">
                        <div class="w-9 h-9 rounded-lg bg-sky-100 dark:bg-sky-900/40 text-sky-800 dark:text-sky-200 font-display font-extrabold text-sm flex items-center justify-center">
                            03
                        </div>
                        <h4 class="font-display font-bold text-base text-slate-900 dark:text-white">Uji Konsistensi</h4>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            Memverifikasi rasio logis penilaian pengambil keputusan ($CR \le 0,1$).
                        </p>
                    </div>
                    <span class="mt-3 pt-2 border-t border-slate-200/60 dark:border-slate-700/60 text-[11px] font-semibold text-sky-600 dark:text-sky-300">
                        Toleransi &le; 10% &rarr;
                    </span>
                </div>

                <div class="glass-card p-5 flex flex-col justify-between">
                    <div class="space-y-2.5">
                        <div class="w-9 h-9 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-200 font-display font-extrabold text-sm flex items-center justify-center">
                            04
                        </div>
                        <h4 class="font-display font-bold text-base text-slate-900 dark:text-white">Visualisasi</h4>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            Menyajikan peringkat alternatif dalam ruang 3D interaktif real-time.
                        </p>
                    </div>
                    <span class="mt-3 pt-2 border-t border-slate-200/60 dark:border-slate-700/60 text-[11px] font-semibold text-emerald-600 dark:text-emerald-300">
                        Batang 3D Interaktif &rarr;
                    </span>
                </div>
            </div>
        </section>


        <!-- ==========================================
             3. RUMUS MATEMATIKA AHP
             Desain layar terminal matematika gelap kontras menyala (glowing math terminal)
             Mobile Carousel Slider (snap-x scrollbar-hide) & Desktop 2-Kolom Grid
             ========================================== -->
        <section id="formula" class="space-y-8 sm:space-y-10 reveal-on-scroll">
            <div class="text-center space-y-3 max-w-2xl mx-auto px-2">
                <span class="px-3.5 py-1 rounded-full bg-[#98FBCB]/25 text-[#0F766E] dark:text-[#98FBCB] border border-[#98FBCB]/40 text-xs font-bold tracking-wider uppercase">
                    Komputasi Presisi Tinggi
                </span>
                <h2 class="font-display font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white tracking-tight">
                    Rumus Matematika AHP
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                    Setiap tahapan kalkulasi AHP didasari oleh formulasi matematika ilmiah yang ketat, transparan, dan dapat diaudit secara terbuka.
                </p>
            </div>

            <!-- Slider / Carousel Rumus: Horizontal Scroll di Mobile (< md), Grid 2-Kolom di Desktop (md:) -->
            <div id="formulaCardsContainer" class="flex md:grid overflow-x-auto md:overflow-visible snap-x snap-mandatory md:snap-none md:grid-cols-2 gap-4 sm:gap-6 pb-2 md:pb-0 scrollbar-hide">

                <!-- 1. Matriks & Normalisasi Kolom + Contoh Numerik Nyata -->
                <div class="glass-card p-5 sm:p-7 flex flex-col justify-between space-y-4 w-full flex-shrink-0 md:flex-shrink snap-center min-w-full md:min-w-0">
                    <div class="space-y-3.5">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full bg-[#98FBCB]/25 text-teal-900 dark:text-[#98FBCB] text-xs font-bold">
                                Tahap 1: Normalisasi Kolom
                            </span>
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold">Skala 1 - 9</span>
                        </div>

                        <h3 class="font-display font-bold text-xl text-slate-900 dark:text-white">
                            Matriks &amp; Normalisasi Kolom
                        </h3>

                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            Setiap elemen perbandingan $a_{ij}$ dinilai dengan Skala Fundamental Saaty (1-9). Nilai ternormalisasi $r_{ij}$ didapat dengan membagi nilai sel dengan total jumlah kolom yang bersesuaian:
                        </p>

                        <!-- Box Rumus Terminal Gelap Menyala (Ukuran Font Besar) -->
                        <div class="math-terminal-box my-2">
                            <div class="math-terminal-header">
                                <span class="font-mono text-[10px] text-slate-400">RUMUS TAHAP 1 &bull; NORMALISASI</span>
                                <div class="flex space-x-1.5">
                                    <span class="w-2 h-2 rounded-full bg-rose-500/80"></span>
                                    <span class="w-2 h-2 rounded-full bg-amber-500/80"></span>
                                    <span class="w-2 h-2 rounded-full bg-[#98FBCB]"></span>
                                </div>
                            </div>
                            <div class="p-5 sm:p-6 text-center bg-slate-950">
                                <div class="font-mono text-xl sm:text-2xl md:text-3xl font-black math-glowing-text tracking-wider">
                                    r<sub>ij</sub> = a<sub>ij</sub> / &sum;<sub>k=1</sub><sup>n</sup> a<sub>kj</sub>
                                </div>
                                <div class="text-[11px] text-slate-400 mt-2 font-mono">
                                    Elemen sel dibagi dengan total jumlah nilai kolom
                                </div>
                            </div>
                        </div>

                        <!-- Contoh Numerik Nyata -->
                        <div class="p-3.5 rounded-xl bg-[#98FBCB]/15 border border-[#98FBCB]/40 text-xs space-y-1">
                            <span class="font-bold text-teal-900 dark:text-[#98FBCB] flex items-center space-x-1.5">
                                <span>Contoh Numerik Nyata:</span>
                            </span>
                            <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed">
                                Misal pada kolom K1 total jumlah kolom adalah <strong>3,833</strong>. Jika nilai sel diagonal $a_{11} = 1$, maka nilai normalisasinya adalah <strong>1 / 3,833 = 0,261</strong>.
                            </p>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-200/70 dark:border-slate-700/70 text-[11px] text-slate-500 dark:text-slate-400">
                        Diagonal selalu bernilai 1 ($a_{ii}=1$), dengan prinsip resiprokal: $a_{ji} = 1 / a_{ij}$.
                    </div>
                </div>

                <!-- 2. Bobot Prioritas (Eigen Vector) + Contoh Numerik Nyata -->
                <div class="glass-card p-5 sm:p-7 flex flex-col justify-between space-y-4 w-full flex-shrink-0 md:flex-shrink snap-center min-w-full md:min-w-0">
                    <div class="space-y-3.5">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full bg-teal-100 dark:bg-teal-900/40 text-teal-800 dark:text-teal-200 text-xs font-bold">
                                Tahap 2: Bobot Prioritas
                            </span>
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold">Eigen Vector</span>
                        </div>

                        <h3 class="font-display font-bold text-xl text-slate-900 dark:text-white">
                            Bobot Prioritas Rata-Rata
                        </h3>

                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            Bobot prioritas kriteria ($w_i$) dihitung dengan menjumlahkan setiap elemen pada baris hasil normalisasi, kemudian dibagi dengan jumlah elemen ($n$):
                        </p>

                        <!-- Box Rumus Terminal Gelap Menyala (Ukuran Font Besar) -->
                        <div class="math-terminal-box my-2">
                            <div class="math-terminal-header">
                                <span class="font-mono text-[10px] text-slate-400">RUMUS TAHAP 2 &bull; EIGEN VECTOR</span>
                                <div class="flex space-x-1.5">
                                    <span class="w-2 h-2 rounded-full bg-rose-500/80"></span>
                                    <span class="w-2 h-2 rounded-full bg-amber-500/80"></span>
                                    <span class="w-2 h-2 rounded-full bg-[#98FBCB]"></span>
                                </div>
                            </div>
                            <div class="p-5 sm:p-6 text-center bg-slate-950">
                                <div class="font-mono text-xl sm:text-2xl md:text-3xl font-black math-glowing-text tracking-wider">
                                    w<sub>i</sub> = (1 / n) &times; &sum;<sub>j=1</sub><sup>n</sup> r<sub>ij</sub>
                                </div>
                                <div class="text-[11px] text-slate-400 mt-2 font-mono">
                                    Rata-rata dari hasil penjumlahan baris normalisasi
                                </div>
                            </div>
                        </div>

                        <!-- Contoh Numerik Nyata -->
                        <div class="p-3.5 rounded-xl bg-teal-50 dark:bg-teal-950/40 border border-teal-200 dark:border-teal-800 text-xs space-y-1">
                            <span class="font-bold text-teal-800 dark:text-teal-200 flex items-center space-x-1.5">
                                <span>Contoh Numerik Nyata:</span>
                            </span>
                            <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed">
                                Jika baris K1 memiliki total normalisasi 0,261 + 0,245 + 0,280 = <strong>0,786</strong>, maka dengan $n=3$, bobot prioritasnya adalah <strong>0,786 / 3 = 0,262 (26,2%)</strong>.
                            </p>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-200/70 dark:border-slate-700/70 text-[11px] text-slate-500 dark:text-slate-400">
                        Vektor prioritas memenuhi syarat normalisasi &sum; w<sub>i</sub> = 1,0 (100%).
                    </div>
                </div>

                <!-- 3. RUMUS LAMBDA MAX (λmax) SECARA EKSPLISIT -->
                <div class="glass-card p-5 sm:p-7 flex flex-col justify-between space-y-4 w-full flex-shrink-0 md:flex-shrink snap-center min-w-full md:min-w-0">
                    <div class="space-y-3.5">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full bg-sky-100 dark:bg-sky-900/40 text-sky-800 dark:text-sky-200 text-xs font-bold">
                                Tahap 3: Nilai Eigen Maksimum
                            </span>
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold">&lambda;<sub>max</sub></span>
                        </div>

                        <h3 class="font-display font-bold text-xl text-slate-900 dark:text-white">
                            Pencarian Lambda Maksimum (&lambda;<sub>max</sub>)
                        </h3>

                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            Nilai <em>Eigen Value</em> maksimal (&lambda;<sub>max</sub>) dihitung dari rata-rata hasil perkalian matriks perbandingan awal ($A$) dengan vektor bobot prioritas ($w$), dibagi bobot baris ($w_i$):
                        </p>

                        <!-- Box Rumus Terminal Gelap Menyala (Eksplisit & Font Besar) -->
                        <div class="math-terminal-box my-2">
                            <div class="math-terminal-header">
                                <span class="font-mono text-[10px] text-slate-400">RUMUS TAHAP 3 &bull; LAMBDA MAX</span>
                                <div class="flex space-x-1.5">
                                    <span class="w-2 h-2 rounded-full bg-rose-500/80"></span>
                                    <span class="w-2 h-2 rounded-full bg-amber-500/80"></span>
                                    <span class="w-2 h-2 rounded-full bg-[#98FBCB]"></span>
                                </div>
                            </div>
                            <div class="p-5 sm:p-6 text-center bg-slate-950">
                                <div class="font-mono text-lg sm:text-2xl md:text-3xl font-black math-glowing-text tracking-wider">
                                    &lambda;<sub>max</sub> = (1 / n) &times; &sum;<sub>i=1</sub><sup>n</sup> [ (A &times; w)<sub>i</sub> / w<sub>i</sub> ]
                                </div>
                                <div class="text-[11px] sm:text-xs text-slate-400 mt-2.5 font-mono">
                                    Rata-rata dari perkalian matriks perbandingan dengan bobot prioritas dibagi bobot baris
                                </div>
                            </div>
                        </div>

                        <!-- Penjelasan Komputasi Eksplisit -->
                        <div class="p-3.5 rounded-xl bg-sky-50 dark:bg-sky-950/40 border border-sky-200 dark:border-sky-800 text-xs space-y-1">
                            <span class="font-bold text-sky-800 dark:text-sky-200">Prinsip Komputasi Eksplisit:</span>
                            <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed">
                                Pada matriks konsisten sempurna, nilai &lambda;<sub>max</sub> sama persis dengan $n$. Nilai selisih (&lambda;<sub>max</sub> - $n$) menunjukkan tingkat penyimpangan konsistensi logis.
                            </p>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-200/70 dark:border-slate-700/70 text-[11px] text-slate-500 dark:text-slate-400">
                        &lambda;<sub>max</sub> menjadi basis mutlak perhitungan <em>Consistency Index</em> (CI).
                    </div>
                </div>

                <!-- 4. Uji Konsistensi (CI & CR) -->
                <div class="glass-card p-5 sm:p-7 flex flex-col justify-between space-y-4 w-full flex-shrink-0 md:flex-shrink snap-center min-w-full md:min-w-0">
                    <div class="space-y-3.5">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-200 text-xs font-bold">
                                Tahap 4: Uji Konsistensi
                            </span>
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold">CI &bull; CR</span>
                        </div>

                        <h3 class="font-display font-bold text-xl text-slate-900 dark:text-white">
                            Uji Konsistensi (CI &amp; CR)
                        </h3>

                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            Mengukur indeks inkonsistensi rasional melalui <em>Consistency Index</em> (CI) dan membandingkannya terhadap indeks acak dengan <em>Consistency Ratio</em> (CR):
                        </p>

                        <!-- Box Rumus Terminal Gelap Menyala (Font Besar) -->
                        <div class="math-terminal-box my-2">
                            <div class="math-terminal-header">
                                <span class="font-mono text-[10px] text-slate-400">RUMUS TAHAP 4 &bull; CI &amp; CR</span>
                                <div class="flex space-x-1.5">
                                    <span class="w-2 h-2 rounded-full bg-rose-500/80"></span>
                                    <span class="w-2 h-2 rounded-full bg-amber-500/80"></span>
                                    <span class="w-2 h-2 rounded-full bg-[#98FBCB]"></span>
                                </div>
                            </div>
                            <div class="p-5 sm:p-6 space-y-3 text-center bg-slate-950">
                                <div class="font-mono text-xl sm:text-2xl md:text-3xl font-black math-glowing-text tracking-wider">
                                    CI = (&lambda;<sub>max</sub> - n) / (n - 1)
                                </div>
                                <div class="font-mono text-xl sm:text-2xl md:text-3xl font-black math-glowing-text tracking-wider pt-2 border-t border-slate-800">
                                    CR = CI / RI
                                </div>
                                <div class="text-[11px] text-slate-400 mt-1 font-mono">
                                    CR adalah perbandingan indeks konsistensi (CI) terhadap Random Index (RI)
                                </div>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-xs">
                            <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed">
                                Nilai <strong>Random Index (RI)</strong> diambil dari tabel standar Saaty berdasarkan ukuran matriks ($n$).
                            </p>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-200/70 dark:border-slate-700/70 text-[11px] text-slate-500 dark:text-slate-400">
                        Syarat validasi mutlak: Penilaian dinyatakan konsisten jika $CR \le 0,1$ (10%).
                    </div>
                </div>

            </div>

            <!-- Mobile Dot Indicators (Khusus Layar Ponsel / md:hidden) -->
            <div class="flex md:hidden items-center justify-center space-x-1 pt-1 pb-2" id="formulaCarouselDots" aria-label="Indikator Slider Rumus">
                <button type="button" class="p-2 flex items-center justify-center focus:outline-none" onclick="scrollFormulaCard(0)" aria-label="Kartu Rumus 1">
                    <span class="formula-dot block w-6 h-2 rounded-full bg-[#98FBCB] transition-all duration-300"></span>
                </button>
                <button type="button" class="p-2 flex items-center justify-center focus:outline-none" onclick="scrollFormulaCard(1)" aria-label="Kartu Rumus 2">
                    <span class="formula-dot block w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700 hover:bg-[#98FBCB]/60 transition-all duration-300"></span>
                </button>
                <button type="button" class="p-2 flex items-center justify-center focus:outline-none" onclick="scrollFormulaCard(2)" aria-label="Kartu Rumus 3">
                    <span class="formula-dot block w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700 hover:bg-[#98FBCB]/60 transition-all duration-300"></span>
                </button>
                <button type="button" class="p-2 flex items-center justify-center focus:outline-none" onclick="scrollFormulaCard(3)" aria-label="Kartu Rumus 4">
                    <span class="formula-dot block w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700 hover:bg-[#98FBCB]/60 transition-all duration-300"></span>
                </button>
            </div>

            <!-- TABEL RANDOM INDEX (RI) REFERENSI SAATY LENGKAP (n=1 s.d. n=15) -->
            <div id="ri-table" class="glass-card p-6 sm:p-8 space-y-5">
                <div class="flex flex-col md:flex-row md:items-center justify-between pb-4 border-b border-slate-200/80 dark:border-slate-700/80 gap-3">
                    <div>
                        <span class="inline-flex items-center space-x-1.5 px-3 py-0.5 rounded-full bg-[#98FBCB]/25 text-teal-900 dark:text-[#98FBCB] border border-[#98FBCB]/50 text-[11px] font-bold uppercase tracking-wider">
                            Tabel Referensi Standar Lengkap
                        </span>
                        <h3 class="font-display font-extrabold text-xl sm:text-2xl text-slate-900 dark:text-white mt-1">
                            Tabel Nilai Random Index (RI) Thomas L. Saaty
                        </h3>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-400 max-w-md sm:text-right">
                        Nilai patokan indeks acak standar Saaty untuk matriks berordo <strong>n = 1 sampai n = 15</strong>. Digunakan sebagai penyebut pada rumus rasio konsistensi $CR = CI / RI$.
                    </p>
                </div>

                <!-- Tabel Glassmorphism n=1 sampai n=15 -->
                <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700/80">
                    <table class="w-full text-center text-xs font-mono">
                        <thead>
                            <tr class="bg-slate-100 dark:bg-slate-800/90 text-slate-800 dark:text-slate-200 font-bold border-b border-slate-200 dark:border-slate-700">
                                <th class="p-3 text-left font-sans font-extrabold text-slate-900 dark:text-white whitespace-nowrap bg-slate-200/60 dark:bg-slate-800">Ukuran Matriks (n)</th>
                                @for($i = 1; $i <= 15; $i++)
                                <th class="p-3 {{ in_array($i, [3, 4]) ? 'text-teal-900 dark:text-[#98FBCB] font-extrabold bg-[#98FBCB]/30 dark:bg-[#98FBCB]/20' : '' }} whitespace-nowrap">
                                    n = {{ $i }}
                                </th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800/70 text-slate-700 dark:text-slate-300">
                            @php
                                $riValues = [
                                    1 => '0,00', 2 => '0,00', 3 => '0,58', 4 => '0,90', 5 => '1,12',
                                    6 => '1,24', 7 => '1,32', 8 => '1,41', 9 => '1,45', 10 => '1,49',
                                    11 => '1,51', 12 => '1,48', 13 => '1,56', 14 => '1,57', 15 => '1,59'
                                ];
                            @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="p-3 text-left font-sans font-bold text-slate-900 dark:text-white bg-slate-50/60 dark:bg-slate-850/60 whitespace-nowrap">
                                    Nilai Random Index (RI)
                                </td>
                                @foreach($riValues as $n => $val)
                                <td class="p-3 {{ in_array($n, [3, 4]) ? 'text-teal-900 dark:text-[#98FBCB] font-black bg-[#98FBCB]/30 dark:bg-[#98FBCB]/20' : 'font-semibold' }}">
                                    {{ $val }}
                                </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between text-xs text-slate-500 dark:text-slate-400 gap-2 pt-1">
                    <p>
                        *Untuk matriks kriteria simulasi ini ($n=4$), nilai RI yang berlaku adalah <strong>0,90</strong>. Untuk matriks alternatif ($n=3$), nilai RI adalah <strong>0,58</strong>.
                    </p>
                    <span class="font-mono text-[11px] text-[#0F766E] dark:text-[#98FBCB] font-bold whitespace-nowrap">
                        Rentang: n=1 (0,00) s.d. n=15 (1,59)
                    </span>
                </div>
            </div>

            <!-- KARTU BESAR PENONJOLAN: Aturan Validasi Mutlak (CR <= 0.1) -->
            <div class="glass-card p-6 sm:p-8 border-2 border-[#98FBCB]">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                    <div class="lg:col-span-8 space-y-3.5 text-left">
                        <div class="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full bg-[#98FBCB]/25 text-teal-900 dark:text-[#98FBCB] border border-[#98FBCB]/50 text-xs font-bold uppercase tracking-wider">
                            <span>Aturan Validasi Mutlak Thomas L. Saaty</span>
                        </div>

                        <h3 class="font-display font-extrabold text-2xl sm:text-3xl text-slate-900 dark:text-white tracking-tight">
                            Batas Toleransi Rasio Konsistensi: <span class="text-[#0F766E] dark:text-[#98FBCB]">CR &le; 0,1 (10%)</span>
                        </h3>

                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                            Dalam metodologi AHP, hasil penilaian perbandingan berpasangan <strong>hanya dianggap sah dan konsisten</strong> jika nilai <em>Consistency Ratio</em> (CR) bernilai kurang dari atau sama dengan <strong>0,1 (10%)</strong>.
                        </p>

                        <div class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-300 dark:border-amber-700/60 text-xs text-amber-900 dark:text-amber-200 space-y-1">
                            <div class="font-bold flex items-center space-x-1.5">
                                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Peringatan Inkonsistensi (CR &gt; 0,1):</span>
                            </div>
                            <p class="text-[11px] text-amber-800 dark:text-amber-300">
                                Jika nilai <strong>CR &gt; 0,1</strong>, sistem secara otomatis menolak kalkulasi dengan kode status HTTP 422 dan pesan peringatan: <em>'Data tidak konsisten, harap ulangi penilaian'</em> karena pertimbangan penilaian mengandung kontradiksi logika.
                            </p>
                        </div>
                    </div>

                    <!-- Kolom Kanan Indikator Validitas -->
                    <div class="lg:col-span-4 text-center">
                        <div class="p-6 rounded-2xl bg-white/80 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 space-y-2.5">
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-[#98FBCB] flex items-center justify-center text-slate-950 font-black text-2xl shadow-sm">
                                &le; 10%
                            </div>
                            <h4 class="font-display font-extrabold text-base text-slate-900 dark:text-white">Standar Validitas AHP</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                Nilai CR &le; 0,1 memastikan keputusan bebas dari bias ekstrem dan inkonsistensi manusiawi.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- ==========================================
             4. BOTTOM CALL TO ACTION (CTA SIMULASI)
             ========================================== -->
        <section id="cta-bottom" class="text-center space-y-6 pt-6">
            <div class="glass-panel p-8 sm:p-12 relative overflow-hidden">
                <div class="max-w-2xl mx-auto space-y-4">
                    <span class="px-3.5 py-1 rounded-full bg-[#98FBCB]/25 text-[#0F766E] dark:text-[#98FBCB] border border-[#98FBCB]/50 text-xs font-bold uppercase tracking-wider">
                        Eksplorasi Interaktif
                    </span>

                    <h2 class="font-display font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white tracking-tight">
                        Coba Simulasi AHP Sekarang
                    </h2>

                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                        Rasakan pengalaman komputasi AHP multi-level interaktif dengan slider skala Saaty 9-1-9, live consistency checker, dan diagram batang 3D WebGL real-time.
                    </p>

                    <div class="pt-4 flex flex-wrap items-center justify-center gap-4">
                        <a href="/ahp" class="btn-primary neu-cta-btn px-9 py-4 text-base font-bold group">
                            <span>Mulai Simulasi Sekarang</span>
                            <span class="ml-2.5 w-6 h-6 rounded-full bg-slate-950/15 flex items-center justify-center text-slate-950 group-hover:translate-x-1 transition-transform">
                                &rarr;
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- ========================================== -->
    <!-- FOOTER UTAMA (DISCORD STYLE DENGAN WATERMARK RAKSASA) -->
    <!-- ========================================== -->
    <footer class="relative bg-[#090D16] text-white pt-16 sm:pt-20 pb-0 overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 pb-12 sm:pb-16">
                <!-- 1. KIRI: Identitas, Pilihan Bahasa, & Sosial Media Pengembang -->
                <div class="lg:col-span-5 space-y-6">
                    <!-- Brand & Logo -->
                    <div class="flex items-center space-x-3">
                        <svg class="w-8 h-8 text-[#98FBCB] drop-shadow-[0_0_8px_rgba(152,251,203,0.5)] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="4.5" r="2.2"/>
                            <path d="M10.2 5.8 L6.8 9.2"/>
                            <path d="M12 6.7 L12 9.7"/>
                            <path d="M13.8 5.8 L17.2 9.2"/>
                            <circle cx="5" cy="11" r="1.8"/>
                            <circle cx="12" cy="11.5" r="1.8"/>
                            <circle cx="19" cy="11" r="1.8"/>
                            <path d="M4.2 12.5 L3.2 16.5"/>
                            <path d="M5.8 12.5 L6.8 16.5"/>
                            <path d="M11.2 13 L9.8 16.5"/>
                            <path d="M12.8 13 L14.2 16.5"/>
                            <path d="M18.2 12.5 L17.2 16.5"/>
                            <path d="M19.8 12.5 L20.8 16.5"/>
                            <circle cx="3" cy="18" r="1.3"/>
                            <circle cx="7" cy="18" r="1.3"/>
                            <circle cx="9.5" cy="18" r="1.3"/>
                            <circle cx="14.5" cy="18" r="1.3"/>
                            <circle cx="17" cy="18" r="1.3"/>
                            <circle cx="21" cy="18" r="1.3"/>
                        </svg>
                        <span class="font-display font-black text-white text-2xl tracking-tight">Hierarchix</span>
                    </div>

                    <!-- Pilihan Bahasa (Language Selector Clean Dropdown) -->
                    <div class="space-y-1.5 max-w-xs">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Language</span>
                        <div class="relative inline-block w-full">
                            <button type="button" class="w-full inline-flex items-center justify-between px-3.5 py-2.5 rounded-lg bg-slate-900/90 hover:bg-slate-800 border border-slate-800 text-sm font-medium text-slate-200 transition-colors focus:outline-none focus:ring-1 focus:ring-[#98FBCB]" aria-label="Pilih Bahasa">
                                <span class="flex items-center space-x-2">
                                    <span class="text-base leading-none">🇮🇩</span>
                                    <span>Bahasa Indonesia</span>
                                </span>
                                <svg class="w-4 h-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Sosial Media Pengembang (Developer / Social) -->
                    <div class="space-y-2">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Developer / Social</span>
                        <div class="flex items-center space-x-4 text-slate-400">
                            <!-- GitHub -->
                            <a href="https://github.com" target="_blank" rel="noopener noreferrer" class="hover:text-[#98FBCB] hover:scale-110 transition-all p-1" aria-label="GitHub">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/>
                                </svg>
                            </a>
                            <!-- Email / Mail -->
                            <a href="mailto:developer@hierarchix.test" class="hover:text-[#98FBCB] hover:scale-110 transition-all p-1" aria-label="Email">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </a>
                            <!-- LinkedIn -->
                            <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" class="hover:text-[#98FBCB] hover:scale-110 transition-all p-1" aria-label="LinkedIn">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                            <!-- X / Twitter -->
                            <a href="https://x.com" target="_blank" rel="noopener noreferrer" class="hover:text-[#98FBCB] hover:scale-110 transition-all p-1" aria-label="X">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 2. KANAN: Tautan Multi-Kolom (Grid 3 Kolom) -->
                <div class="lg:col-span-7 grid grid-cols-2 sm:grid-cols-3 gap-8">
                    <!-- Kolom 1: Fitur -->
                    <div class="space-y-3.5">
                        <h4 class="text-xs font-bold text-[#98FBCB] tracking-wider uppercase font-mono">Fitur</h4>
                        <ul class="space-y-2.5 text-sm text-slate-400">
                            <li><a href="/ahp" class="hover:text-white transition-colors">Simulasi AHP</a></li>
                            <li><a href="/ahp#step1" class="hover:text-white transition-colors">Template Kasus</a></li>
                            <li><a href="/ahp#step2" class="hover:text-white transition-colors">Manajemen Kriteria</a></li>
                            <li><a href="/ahp#step3" class="hover:text-white transition-colors">Perbandingan Berpasangan</a></li>
                            <li><a href="/ahp#step5" class="hover:text-white transition-colors">Visualisasi 3D Bar</a></li>
                        </ul>
                    </div>

                    <!-- Kolom 2: Edukasi -->
                    <div class="space-y-3.5">
                        <h4 class="text-xs font-bold text-[#98FBCB] tracking-wider uppercase font-mono">Edukasi</h4>
                        <ul class="space-y-2.5 text-sm text-slate-400">
                            <li><a href="/#pengenalan" class="hover:text-white transition-colors">Tentang AHP</a></li>
                            <li><a href="/#pengenalan" class="hover:text-white transition-colors">Tahapan Algoritma</a></li>
                            <li><a href="/#formula" class="hover:text-white transition-colors">Rumus Matematika</a></li>
                            <li><a href="/#formula" class="hover:text-white transition-colors">Uji Konsistensi (CR)</a></li>
                            <li><a href="/#formula" class="hover:text-white transition-colors">Tabel RI Saaty</a></li>
                        </ul>
                    </div>

                    <!-- Kolom 3: Pengembang -->
                    <div class="space-y-3.5 col-span-2 sm:col-span-1">
                        <h4 class="text-xs font-bold text-[#98FBCB] tracking-wider uppercase font-mono">Pengembang</h4>
                        <ul class="space-y-2.5 text-sm text-slate-400">
                            <li><a href="https://github.com" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">GitHub Repo</a></li>
                            <li><a href="https://github.com" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">Portofolio</a></li>
                            <li><a href="/#hero" class="hover:text-white transition-colors">Dokumentasi</a></li>
                            <li><a href="https://laravel.com" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">Laravel Framework</a></li>
                            <li><span class="inline-flex items-center text-xs text-slate-500 font-mono">Lisensi MIT</span></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Baris Informasi Hak Cipta & Status Konsistensi -->
            <div class="border-t border-slate-800/80 pt-6 pb-4 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} Hierarchix. Sistem Pendukung Keputusan AHP 3D Interaktif.</p>
                <p class="mt-2 sm:mt-0 font-mono text-[11px] text-slate-400 flex items-center space-x-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#98FBCB] animate-pulse"></span>
                    <span>Validasi Ketat Consistency Ratio (CR &le; 0.1)</span>
                </p>
            </div>
        </div>

        <!-- 4. WATERMARK TEKS RAKSASA (ELEMEN KUNCI ALA DISCORD) -->
        <div class="w-full select-none pointer-events-none overflow-hidden flex items-end justify-center leading-none">
            <span class="text-[15vw] font-display font-black tracking-tighter text-white/[0.05] uppercase translate-y-[20%]">
                Hierarchix
            </span>
        </div>
    </footer>

    <!-- SKRIP INTERAKTIVITAS NAVBAR: DYNAMIC GLASSMORPHISM & SCROLLSPY -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const header = document.getElementById('mainHeader');
            const navLinks = document.querySelectorAll('.nav-link');
            const sections = [
                document.getElementById('hero'),
                document.getElementById('pengenalan'),
                document.getElementById('formula'),
                document.getElementById('cta-bottom')
            ].filter(Boolean);

            // 1. Dynamic Glassmorphism Handler (Transparan saat di atas, Glassmorphism solid saat discroll)
            function handleHeaderScroll() {
                if (!header) return;
                const scrollY = window.pageYOffset || document.documentElement.scrollTop;
                if (scrollY > 40) {
                    header.classList.remove('bg-white/40', 'dark:bg-[#090D16]/40', 'backdrop-blur-sm', 'border-transparent');
                    header.classList.add('bg-white/80', 'dark:bg-[#090D16]/90', 'backdrop-blur-md', 'shadow-sm', 'border-slate-200/50', 'dark:border-white/10');
                } else {
                    header.classList.remove('bg-white/80', 'dark:bg-[#090D16]/90', 'backdrop-blur-md', 'shadow-sm', 'border-slate-200/50', 'dark:border-white/10');
                    header.classList.add('bg-white/40', 'dark:bg-[#090D16]/40', 'backdrop-blur-sm', 'border-transparent');
                }
            }

            // 2. Active Menu Scrollspy Handler (Deteksi section aktif di viewport & update dot indicator)
            function handleScrollspy() {
                const scrollY = window.pageYOffset || document.documentElement.scrollTop;
                const windowHeight = window.innerHeight;
                const docHeight = document.documentElement.scrollHeight;
                const headerOffset = 160;

                let activeId = 'hero';

                // Jika sudah mendekati dasar halaman, aktifkan section terakhir (#cta-bottom)
                if (scrollY + windowHeight >= docHeight - 80) {
                    activeId = 'cta-bottom';
                } else {
                    sections.forEach(function (sec) {
                        const top = sec.offsetTop - headerOffset;
                        const height = sec.offsetHeight;
                        if (scrollY >= top && scrollY < top + height) {
                            activeId = sec.getAttribute('id');
                        }
                    });
                }

                // Update styling tautan navigasi dan dot indicator dengan kontras tinggi konsisten
                navLinks.forEach(function (link) {
                    const targetSection = link.getAttribute('data-section') || link.getAttribute('href').replace('#', '');
                    const dot = link.querySelector('.nav-dot');

                    if (targetSection === activeId) {
                        link.classList.add('text-teal-700', 'dark:text-[#98FBCB]');
                        link.classList.remove('text-slate-900', 'dark:text-slate-100');
                        if (dot) {
                            dot.classList.remove('opacity-0', 'scale-0');
                            dot.classList.add('opacity-100', 'scale-100');
                        }
                    } else {
                        link.classList.remove('text-teal-700', 'dark:text-[#98FBCB]');
                        link.classList.add('text-slate-900', 'dark:text-slate-100');
                        if (dot) {
                            dot.classList.remove('opacity-100', 'scale-100');
                            dot.classList.add('opacity-0', 'scale-0');
                        }
                    }
                });
            }

            // Scroll listener dengan requestAnimationFrame untuk performa 60fps
            let ticking = false;
            window.addEventListener('scroll', function () {
                if (!ticking) {
                    window.requestAnimationFrame(function () {
                        handleHeaderScroll();
                        handleScrollspy();
                        ticking = false;
                    });
                    ticking = true;
                }
            }, { passive: true });

            // Inisialisasi awal saat halaman dimuat
            handleHeaderScroll();
            handleScrollspy();

            // 3. Slider / Carousel Rumus Matematika (Khusus Mobile)
            const formulaContainer = document.getElementById('formulaCardsContainer');
            const formulaDots = document.querySelectorAll('.formula-dot');

            window.scrollFormulaCard = function(index) {
                if (!formulaContainer) return;
                const cards = formulaContainer.children;
                if (cards[index]) {
                    cards[index].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                }
            };

            if (formulaContainer && formulaDots.length > 0) {
                let scrollTimeout;
                formulaContainer.addEventListener('scroll', function () {
                    clearTimeout(scrollTimeout);
                    scrollTimeout = setTimeout(function () {
                        const scrollLeft = formulaContainer.scrollLeft;
                        const cardWidth = formulaContainer.clientWidth;
                        if (!cardWidth) return;
                        const activeIdx = Math.min(formulaDots.length - 1, Math.max(0, Math.round(scrollLeft / cardWidth)));
                        formulaDots.forEach((dot, idx) => {
                            if (idx === activeIdx) {
                                dot.className = 'formula-dot block w-6 h-2 rounded-full bg-[#98FBCB] transition-all duration-300';
                            } else {
                                dot.className = 'formula-dot block w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700 hover:bg-[#98FBCB]/60 transition-all duration-300';
                            }
                        });
                    }, 40);
                }, { passive: true });
            }
        });
    </script>

    <!-- SKRIP THREE.JS: SIMULASI 3D PLATONIC SOLIDS & ALGORITHMIC NODES (SCALE 1.5, FOV 55, Z 11.5) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('hero3DCanvas');
            const container = document.getElementById('hero3DContainer');

            if (!canvas || !container || typeof THREE === 'undefined') {
                return;
            }

            const width = container.clientWidth || 360;
            const height = container.clientHeight || 300;

            // 1. Scene (Transparan Penuh, Melebur Alami dengan Background Halaman)
            const scene = new THREE.Scene();

            // 2. Camera Setup (FOV Melebar 55° & Posisi Mundur Z 11.5 untuk Skala Megah Tanpa Terpotong)
            const camera = new THREE.PerspectiveCamera(55, width / height, 0.1, 100);
            camera.position.set(0, 0, 11.5);
            camera.lookAt(0, 0, 0);

            // 3. WebGL Renderer Transparan & Halus
            const renderer = new THREE.WebGLRenderer({
                canvas: canvas,
                alpha: true,
                antialias: true
            });
            renderer.setSize(width, height);
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
            renderer.setClearColor(0x000000, 0); // Background 100% transparan
            renderer.shadowMap.enabled = true;
            renderer.shadowMap.type = THREE.PCFSoftShadowMap;

            // 4. Pencahayaan Editorial Lembut & Gradasi Halus (Clay / Matte Aesthetic)
            const ambientLight = new THREE.AmbientLight(0xffffff, 0.70);
            scene.add(ambientLight);

            // Key Light: Menyorot dari sudut kanan-atas depan
            const keyLight = new THREE.DirectionalLight(0xffffff, 1.35);
            keyLight.position.set(6.0, 8.0, 7.0);
            keyLight.castShadow = true;
            keyLight.shadow.mapSize.width = 1024;
            keyLight.shadow.mapSize.height = 1024;
            scene.add(keyLight);

            // Fill Light: Aksen lembut Mint Green dari kiri bawah
            const mintFillLight = new THREE.PointLight(0x98fbcb, 1.1, 18);
            mintFillLight.position.set(-6.0, -3.5, 5.0);
            scene.add(mintFillLight);

            // Rim Light: Menegaskan kontur tepi gelap dari kanan belakang
            const tealRimLight = new THREE.PointLight(0x0f766e, 0.8, 16);
            tealRimLight.position.set(5.0, -4.0, -3.5);
            scene.add(tealRimLight);

            // 5. Generator Tekstur Garis Prosedural (Canvas 2D) untuk Bola Belang
            // A. Garis Vertikal Meridian
            function createVerticalStripedTexture() {
                const tCanvas = document.createElement('canvas');
                tCanvas.width = 512;
                tCanvas.height = 256;
                const ctx = tCanvas.getContext('2d');
                ctx.fillStyle = '#98FBCB'; // Mint
                ctx.fillRect(0, 0, 512, 256);
                ctx.fillStyle = '#0F766E'; // Deep Teal
                const numStripes = 16;
                const stripeW = 512 / (numStripes * 2);
                for (let x = 0; x < 512; x += stripeW * 2) {
                    ctx.fillRect(x, 0, stripeW, 256);
                }
                const tex = new THREE.CanvasTexture(tCanvas);
                tex.wrapS = THREE.RepeatWrapping;
                tex.wrapT = THREE.ClampToEdgeWrapping;
                return tex;
            }

            // B. Garis Horizontal Latitude
            function createHorizontalStripedTexture() {
                const tCanvas = document.createElement('canvas');
                tCanvas.width = 256;
                tCanvas.height = 512;
                const ctx = tCanvas.getContext('2d');
                ctx.fillStyle = '#2DD4BF'; // Teal-400
                ctx.fillRect(0, 0, 256, 512);
                ctx.fillStyle = '#042F2E'; // Deep Navy Teal
                const numStripes = 12;
                const stripeH = 512 / (numStripes * 2);
                for (let y = 0; y < 512; y += stripeH * 2) {
                    ctx.fillRect(0, y, 256, stripeH);
                }
                const tex = new THREE.CanvasTexture(tCanvas);
                tex.wrapS = THREE.ClampToEdgeWrapping;
                tex.wrapT = THREE.RepeatWrapping;
                return tex;
            }

            // 6. Materials (Matte Soft-Touch Clay / Plastic Look, High Roughness, Low Metalness)
            const matCentralIcosa = new THREE.MeshStandardMaterial({
                color: 0x0f4e48,
                roughness: 0.65,
                metalness: 0.10,
                flatShading: true
            });

            const matOctahedron = new THREE.MeshStandardMaterial({
                color: 0x98fbcb,
                roughness: 0.68,
                metalness: 0.08,
                flatShading: true
            });

            const matDodecahedron = new THREE.MeshStandardMaterial({
                color: 0x2dd4bf,
                roughness: 0.70,
                metalness: 0.08,
                flatShading: true
            });

            const matTorusKnot = new THREE.MeshStandardMaterial({
                color: 0x5eead4,
                roughness: 0.60,
                metalness: 0.10
            });

            const matVerticalStripedSphere = new THREE.MeshStandardMaterial({
                map: createVerticalStripedTexture(),
                roughness: 0.65,
                metalness: 0.08
            });

            const matHorizontalStripedSphere = new THREE.MeshStandardMaterial({
                map: createHorizontalStripedTexture(),
                roughness: 0.68,
                metalness: 0.08
            });

            const matMiniMint = new THREE.MeshStandardMaterial({
                color: 0x98fbcb,
                roughness: 0.65,
                metalness: 0.10
            });

            const matMiniTeal = new THREE.MeshStandardMaterial({
                color: 0x2dd4bf,
                roughness: 0.70,
                metalness: 0.08
            });

            const matMiniDeep = new THREE.MeshStandardMaterial({
                color: 0x0f766e,
                roughness: 0.75,
                metalness: 0.08
            });

            // 7. Group Objek Geometris 3D (Tingkatkan Skala Menjadi 1.5 Sesuai Instruksi)
            const clusterGroup = new THREE.Group();
            clusterGroup.scale.set(1.5, 1.5, 1.5);
            clusterGroup.position.set(0, 0, 0);
            scene.add(clusterGroup);

            // A. Bayangan Dasar Mengambang (Soft Ambient Shadow Disc)
            const shadowGeo = new THREE.CircleGeometry(2.0, 36);
            const shadowMat = new THREE.MeshBasicMaterial({
                color: 0x042f2e,
                transparent: true,
                opacity: 0.32
            });
            const shadowMesh = new THREE.Mesh(shadowGeo, shadowMat);
            shadowMesh.position.set(0.3, -1.8, -0.6);
            shadowMesh.rotation.set(-Math.PI / 2 + 0.3, 0, 0);
            shadowMesh.scale.set(1.6, 0.85, 1);
            clusterGroup.add(shadowMesh);

            // B. 1. Objek Sentral: IcosahedronGeometry (Menggantikan Kubus Utama)
            const centralGeo = new THREE.IcosahedronGeometry(1.6, 0);
            const centralMesh = new THREE.Mesh(centralGeo, matCentralIcosa);
            centralMesh.position.set(0.0, 0.25, 0.1);
            centralMesh.rotation.set(0.4, 0.6, 0.2);
            centralMesh.castShadow = true;
            centralMesh.receiveShadow = true;
            clusterGroup.add(centralMesh);

            // C. 2. Objek Sekunder: OctahedronGeometry (Menggantikan Kubus Kanan)
            const octaGeo = new THREE.OctahedronGeometry(1.2, 0);
            const octaMesh = new THREE.Mesh(octaGeo, matOctahedron);
            octaMesh.position.set(1.45, -0.25, 0.5);
            octaMesh.rotation.set(0.5, 0.7, -0.3);
            octaMesh.castShadow = true;
            octaMesh.receiveShadow = true;
            clusterGroup.add(octaMesh);

            // D. 3. Objek Tersier: DodecahedronGeometry (Platonic Solid Kiri Atas)
            const dodecaGeo = new THREE.DodecahedronGeometry(0.9, 0);
            const dodecaMesh = new THREE.Mesh(dodecaGeo, matDodecahedron);
            dodecaMesh.position.set(-1.35, 0.85, -0.4);
            dodecaMesh.rotation.set(0.3, 0.5, 0.1);
            dodecaMesh.castShadow = true;
            dodecaMesh.receiveShadow = true;
            clusterGroup.add(dodecaMesh);

            // E. 4. Simpul Visual Algoritma: TorusKnotGeometry (Kiri Bawah)
            const knotGeo = new THREE.TorusKnotGeometry(0.8, 0.25, 100, 16);
            const knotMesh = new THREE.Mesh(knotGeo, matTorusKnot);
            knotMesh.position.set(-1.15, -0.85, 0.4);
            knotMesh.rotation.set(0.8, 0.4, -0.5);
            knotMesh.castShadow = true;
            knotMesh.receiveShadow = true;
            clusterGroup.add(knotMesh);

            // F. 5. Bola Garis Vertikal (Meridian Stripes - Kanan Atas)
            const vertSphereGeo = new THREE.SphereGeometry(0.65, 36, 36);
            const vertSphere = new THREE.Mesh(vertSphereGeo, matVerticalStripedSphere);
            vertSphere.position.set(1.5, 1.0, -0.3);
            vertSphere.rotation.set(0.3, 0.8, -0.4);
            vertSphere.castShadow = true;
            vertSphere.receiveShadow = true;
            clusterGroup.add(vertSphere);

            // G. 6. Bola Garis Horizontal (Latitude Stripes - Bawah Tengah)
            const horizSphereGeo = new THREE.SphereGeometry(0.5, 36, 36);
            const horizSphere = new THREE.Mesh(horizSphereGeo, matHorizontalStripedSphere);
            horizSphere.position.set(0.35, -1.35, 0.6);
            horizSphere.rotation.set(0.4, 0.2, 0.1);
            horizSphere.castShadow = true;
            horizSphere.receiveShadow = true;
            clusterGroup.add(horizSphere);

            // H. 7. Bola Aksen Satelit Mengambang (Kedalaman Ruang)
            const mini1 = new THREE.Mesh(new THREE.SphereGeometry(0.18, 20, 20), matMiniMint);
            mini1.position.set(0.8, 1.55, -0.2);
            clusterGroup.add(mini1);

            const mini2 = new THREE.Mesh(new THREE.SphereGeometry(0.16, 20, 20), matMiniTeal);
            mini2.position.set(-1.45, 0.05, 0.2);
            clusterGroup.add(mini2);

            const mini3 = new THREE.Mesh(new THREE.SphereGeometry(0.20, 20, 20), matMiniDeep);
            mini3.position.set(1.25, -1.45, 0.2);
            clusterGroup.add(mini3);

            // 8. Data Konfigurasi Animasi Melayang (Floating & Rotation)
            const shapesData = [
                { mesh: centralMesh, basePos: { x: 0.0, y: 0.25, z: 0.1 }, floatSpeed: 1.1, floatAmp: 0.06, phase: 0.0, rotSpeed: { x: 0.003, y: 0.004, z: 0.002 } },
                { mesh: octaMesh, basePos: { x: 1.45, y: -0.25, z: 0.5 }, floatSpeed: 1.3, floatAmp: 0.07, phase: 1.2, rotSpeed: { x: -0.004, y: 0.003, z: 0.002 } },
                { mesh: dodecaMesh, basePos: { x: -1.35, y: 0.85, z: -0.4 }, floatSpeed: 1.0, floatAmp: 0.05, phase: 2.8, rotSpeed: { x: 0.003, y: -0.003, z: 0.002 } },
                { mesh: knotMesh, basePos: { x: -1.15, y: -0.85, z: 0.4 }, floatSpeed: 1.4, floatAmp: 0.07, phase: 0.8, rotSpeed: { x: 0.005, y: 0.004, z: -0.003 } },
                { mesh: vertSphere, basePos: { x: 1.5, y: 1.0, z: -0.3 }, floatSpeed: 1.5, floatAmp: 0.07, phase: 1.7, rotSpeed: { x: 0.003, y: 0.005, z: 0.002 } },
                { mesh: horizSphere, basePos: { x: 0.35, y: -1.35, z: 0.6 }, floatSpeed: 1.2, floatAmp: 0.06, phase: 3.5, rotSpeed: { x: 0.002, y: 0.004, z: -0.002 } },
                { mesh: mini1, basePos: { x: 0.8, y: 1.55, z: -0.2 }, floatSpeed: 1.9, floatAmp: 0.08, phase: 0.5, rotSpeed: { x: 0.003, y: 0.002, z: 0.002 } },
                { mesh: mini2, basePos: { x: -1.45, y: 0.05, z: 0.2 }, floatSpeed: 1.8, floatAmp: 0.08, phase: 2.5, rotSpeed: { x: -0.003, y: 0.003, z: 0.001 } },
                { mesh: mini3, basePos: { x: 1.25, y: -1.45, z: 0.2 }, floatSpeed: 2.0, floatAmp: 0.09, phase: 1.9, rotSpeed: { x: 0.003, y: -0.004, z: 0.002 } }
            ];

            // 9. Mouse Parallax Interaktif Halus
            let targetParallaxX = 0;
            let targetParallaxY = 0;

            window.addEventListener('mousemove', function (e) {
                const rect = container.getBoundingClientRect();
                const centerX = rect.left + rect.width / 2;
                const centerY = rect.top + rect.height / 2;
                targetParallaxX = ((e.clientX - centerX) / (window.innerWidth / 2)) * 0.15;
                targetParallaxY = ((e.clientY - centerY) / (window.innerHeight / 2)) * 0.15;
            });

            // 10. Render Loop: Animasi Mengambang Math.sin(), Rotasi Sumbu, & Mouse Parallax
            const clock = new THREE.Clock();

            function animate() {
                requestAnimationFrame(animate);
                const t = clock.getElapsedTime();

                // Animasi bentuk melayang naik-turun halus dan berputar pada sumbunya
                shapesData.forEach(item => {
                    item.mesh.position.y = item.basePos.y + Math.sin(t * item.floatSpeed + item.phase) * item.floatAmp;
                    item.mesh.position.x = item.basePos.x + Math.cos(t * item.floatSpeed * 0.7 + item.phase) * (item.floatAmp * 0.35);

                    item.mesh.rotation.x += item.rotSpeed.x;
                    item.mesh.rotation.y += item.rotSpeed.y;
                    item.mesh.rotation.z += item.rotSpeed.z;
                });

                // Efek bayangan dasar yang mengikuti ritme melayang
                shadowMesh.scale.x = 1.6 + Math.sin(t * 1.1) * 0.06;
                shadowMesh.scale.y = 0.85 + Math.sin(t * 1.1) * 0.03;

                // Parallax lerp halus pada seluruh klaster geometri
                clusterGroup.rotation.y += (targetParallaxX - clusterGroup.rotation.y) * 0.05;
                clusterGroup.rotation.x += (-targetParallaxY - clusterGroup.rotation.x) * 0.05;

                renderer.render(scene, camera);
            }

            animate();

            // 11. Resize Handler Responsif
            window.addEventListener('resize', () => {
                const newWidth = container.clientWidth;
                const newHeight = container.clientHeight || 300;
                if (!newWidth) return;
                camera.aspect = newWidth / newHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(newWidth, newHeight);
            });
        });
    </script>
</body>
</html>
