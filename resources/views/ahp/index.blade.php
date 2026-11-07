<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SPK Interaktif 3D (Metode AHP) - Template Studi Kasus & Linear Sequential Flow</title>
    <!-- Favicon Hierarchix -->
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="alternate icon" type="image/png" href="/favicon.png">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/favicon.png">

    <!-- Google Fonts: Outfit, Plus Jakarta Sans, & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Three.js (r128) & OrbitControls Library (CDN Resmi Stabil) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>

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
                        coral: {
                            light: '#FECDD3',
                            DEFAULT: '#FB7185',
                            dark: '#E11D48',
                        }
                    }
                }
            }
        }
    </script>

        <!-- Custom Styling: Clean Flat & Thin Glassmorphism (Sequential Linear Flow Layout) -->
    <style>
        /* Mencegah kebocoran scroll horizontal pada seluruh viewport & rasio 9:16 mobile */
        html, body {
            overflow-x: hidden !important;
            max-width: 100vw;
            width: 100%;
        }

        body {
            transition: background-color 0.3s ease, color 0.3s ease;
            -webkit-tap-highlight-color: transparent;
        }

        /* Dukungan Scroll Horisontal Halus pada Tabel & Tab Mobile */
        .table-responsive-container, .mobile-scroll-x {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }
        .table-responsive-container::-webkit-scrollbar, .mobile-scroll-x::-webkit-scrollbar {
            height: 5px;
        }
        .table-responsive-container::-webkit-scrollbar-track, .mobile-scroll-x::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.05);
            border-radius: 9999px;
        }
        .dark .table-responsive-container::-webkit-scrollbar-track, .dark .mobile-scroll-x::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        .table-responsive-container::-webkit-scrollbar-thumb, .mobile-scroll-x::-webkit-scrollbar-thumb {
            background: rgba(15, 118, 110, 0.35);
            border-radius: 9999px;
        }
        .dark .table-responsive-container::-webkit-scrollbar-thumb, .dark .mobile-scroll-x::-webkit-scrollbar-thumb {
            background: rgba(152, 251, 203, 0.35);
        }

        /* 1. Clean Glassmorphism & Flat Surfaces */
        .glass-panel {
            background-color: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 4px 18px -2px rgba(15, 23, 42, 0.04);
            border-radius: 20px;
        }

        .dark .glass-panel {
            background-color: rgba(30, 41, 59, 0.85);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(51, 65, 85, 0.8);
            box-shadow: 0 4px 22px -2px rgba(0, 0, 0, 0.35);
        }

        .glass-subcard {
            background-color: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 14px;
            transition: all 0.2s ease;
        }

        .dark .glass-subcard {
            background-color: rgba(15, 23, 42, 0.65);
            border: 1px solid rgba(51, 65, 85, 0.65);
        }

        /* 2. Flat Modern Buttons */
        .btn-primary {
            background-color: #98FBCB;
            color: #042F2E !important;
            font-weight: 700;
            border-radius: 12px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px -1px rgba(152, 251, 203, 0.5);
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
            box-shadow: 0 4px 10px -1px rgba(152, 251, 203, 0.6);
            color: #042F2E !important;
        }

        .btn-secondary {
            background-color: rgba(255, 255, 255, 0.92);
            color: #334155;
            border: 1px solid #CBD5E1;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .dark .btn-secondary {
            background-color: rgba(30, 41, 59, 0.9);
            color: #E2E8F0;
            border-color: #475569;
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
            background-color: rgba(255, 255, 255, 0.92);
            color: #334155;
            border: 1px solid #CBD5E1;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .dark .neu-cta-btn, .dark .neu-btn {
            background-color: rgba(30, 41, 59, 0.9);
            color: #E2E8F0;
            border-color: #475569;
        }

        .neu-cta-btn:hover, .neu-btn:hover {
            border-color: #98FBCB;
            color: #0F766E;
        }

        .dark .neu-cta-btn:hover, .dark .neu-btn:hover {
            color: #98FBCB;
        }

        /* 3. Slider 9-1-9 (Clean Modern Flat Track & Mint Thumb) */
        input[type="range"].ahp-slider,
        input[type="range"].ahp-alt-slider {
            -webkit-appearance: none;
            appearance: none;
            background: transparent;
            height: 24px;
            outline: none;
            cursor: pointer;
        }

        input[type="range"].ahp-slider::-webkit-slider-runnable-track,
        input[type="range"].ahp-alt-slider::-webkit-slider-runnable-track {
            background-color: #E2E8F0;
            height: 8px;
            border-radius: 9999px;
            border: 1px solid #CBD5E1;
            transition: all 0.2s ease;
        }

        .dark input[type="range"].ahp-slider::-webkit-slider-runnable-track,
        .dark input[type="range"].ahp-alt-slider::-webkit-slider-runnable-track {
            background-color: #1E293B;
            border-color: #334155;
        }

        input[type="range"].ahp-slider::-webkit-slider-thumb,
        input[type="range"].ahp-alt-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 22px;
            height: 22px;
            margin-top: -7px;
            border-radius: 50%;
            background-color: #FFFFFF;
            border: 3px solid #98FBCB;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            transition: transform 0.15s ease, background-color 0.15s ease;
        }

        .dark input[type="range"].ahp-slider::-webkit-slider-thumb,
        .dark input[type="range"].ahp-alt-slider::-webkit-slider-thumb {
            background-color: #0F172A;
            border-color: #98FBCB;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
        }

        input[type="range"].ahp-slider::-webkit-slider-thumb:hover,
        input[type="range"].ahp-alt-slider::-webkit-slider-thumb:hover {
            transform: scale(1.15);
            background-color: #98FBCB;
        }

        input[type="range"].ahp-slider::-moz-range-track,
        input[type="range"].ahp-alt-slider::-moz-range-track {
            background-color: #E2E8F0;
            height: 8px;
            border-radius: 9999px;
            border: 1px solid #CBD5E1;
        }

        .dark input[type="range"].ahp-slider::-moz-range-track,
        .dark input[type="range"].ahp-alt-slider::-moz-range-track {
            background-color: #1E293B;
            border-color: #334155;
        }

        input[type="range"].ahp-slider::-moz-range-thumb,
        input[type="range"].ahp-alt-slider::-moz-range-thumb {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background-color: #FFFFFF;
            border: 3px solid #98FBCB;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            cursor: pointer;
        }

        /* 4. Wadah Canvas 3D (Clean Modern Flat Frame) */
        .portal-outer-frame {
            background-color: #090D16;
            border: 1px solid #1E293B;
            border-radius: 18px;
        }

        .portal-inner-screen {
            background-color: #0B0F19;
            border-radius: 12px;
        }

        /* Clean metric / info box */
        .info-card {
            background-color: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
        }

        .dark .info-card {
            background-color: rgba(15, 23, 42, 0.6);
            border-color: #334155;
        }

        /* Tab Buttons */
        .tab-btn {
            transition: all 0.2s ease;
        }
        .tab-btn.active {
            background-color: #98FBCB !important;
            color: #042F2E !important;
            font-weight: 800;
            border-color: #98FBCB !important;
        }
        .dark .tab-btn.active {
            background-color: #98FBCB !important;
            color: #042F2E !important;
            font-weight: 800;
            border-color: #98FBCB !important;
        }
        .tab-btn.active .tab-crit-code {
            background-color: rgba(4, 47, 46, 0.2) !important;
            color: #042F2E !important;
        }
        .tab-btn.active .tab-crit-name {
            color: #042F2E !important;
            font-weight: 800;
        }

        /* Case Study Chip */
        .case-chip {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .case-chip.active {
            background-color: #98FBCB !important;
            color: #042F2E !important;
            font-weight: 800;
            border-color: #98FBCB !important;
            box-shadow: 0 2px 10px -1px rgba(152, 251, 203, 0.6);
        }
        .dark .case-chip.active {
            background-color: #98FBCB !important;
            color: #042F2E !important;
            font-weight: 800;
            border-color: #98FBCB !important;
        }

        /* Step Badge Styling */
        .step-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
    </style>
</head>
<body class="min-h-full font-sans antialiased bg-gray-50 text-slate-800 dark:bg-slate-900 dark:text-slate-100 pb-20 relative">

    <!-- HEADER NAVIGATION (STICKY TOP) -->
    <header class="sticky top-2 sm:top-4 z-40 max-w-6xl mx-auto px-3 sm:px-6 mb-5 sm:mb-8 w-full">
        <div class="glass-panel p-3.5 sm:px-6 sm:py-3.5 flex flex-col md:flex-row md:items-center justify-between gap-3 sm:gap-4">
            <!-- Brand Logo & Title (Hierarchix Decision Tree) -->
            <div class="flex items-center space-x-3.5 group">
                <a href="/" class="flex items-center space-x-3">
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
                    <div>
                        <h1 class="font-display font-extrabold text-slate-900 dark:text-white text-lg sm:text-xl tracking-tight leading-tight">
                            Hierarchix
                        </h1>
                        <p class="text-xs text-[#0F766E] dark:text-[#98FBCB] font-semibold">Alur Komputasi Linear AHP &bull; Thomas L. Saaty</p>
                    </div>
                </a>
            </div>

            <!-- Header Controls & Indicators -->
            <div class="flex items-center flex-wrap gap-2.5">
                <a href="/" class="btn-secondary px-3.5 py-1.5 text-xs font-semibold" title="Kembali ke Story Page">
                    <span>&larr; Story Page</span>
                </a>

                <!-- Status Konsistensi Header -->
                <div id="statusBadge" class="info-card px-3.5 py-1.5 flex items-center space-x-2 text-xs font-semibold text-slate-700 dark:text-slate-300">
                    <span id="statusIndicatorDot" class="w-2.5 h-2.5 rounded-full bg-[#98FBCB] animate-pulse"></span>
                    <span id="statusBadgeText">Status: Siap / Multi-Level Konsisten</span>
                </div>

                <!-- Tombol Preset Konsisten -->
                <button type="button" id="btnPresetConsistentHeader" class="btn-primary px-3 py-1.5 text-xs font-bold" title="Muat preset penilaian konsisten multi-level (CR < 0.1)">
                    Contoh Konsisten
                </button>

                <!-- Tombol Reset Penilaian -->
                <button type="button" id="btnReset" class="btn-secondary px-3 py-1.5 text-xs font-semibold" title="Reset semua perbandingan ke 1:1">
                    Reset
                </button>
            </div>
        </div>
    </header>

    <!-- TOAST ALERT MERAH/CORAL MENYALA (JIKA CR > 0.1 DI LEVEL MANA PUN) -->
    <!-- Menggunakan Complementary Accent: Soft Coral/Rose (rose-500) agar estetik dan mencolok -->
    <div id="errorAlert" class="hidden fixed bottom-4 inset-x-3 sm:inset-x-auto sm:right-6 sm:bottom-6 sm:max-w-md z-50 transition-all duration-300">
        <div class="bg-rose-500 text-white p-5 rounded-2xl shadow-xl shadow-rose-500/30 border border-rose-400 flex items-start space-x-4">
            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-6 h-6 text-white animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-extrabold tracking-wide text-white" id="errorAlertTitle">
                    Data tidak konsisten, harap ulangi penilaian
                </h3>
                <p class="text-xs text-rose-100 mt-1 font-medium leading-relaxed" id="errorAlertMsg">
                    Nilai Consistency Ratio (CR) melebihi batas toleransi 10% (0.1). Kalkulasi ditolak karena pertentangan logika penilaian.
                </p>
                <div class="mt-2.5 text-xs font-bold text-rose-950 bg-rose-100 px-3 py-1 rounded-lg inline-block">
                    Level Gagal: <span id="errorFailedLevel" class="font-extrabold text-rose-900">Matriks Kriteria</span> | CR: <span id="errorCrValue" class="underline font-black">-</span>
                </div>
            </div>
            <button type="button" onclick="dismissErrorAlert()" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors" title="Tutup">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>

    <!-- MAIN SEQUENTIAL FLOW CONTAINER (LINEAR STEP 1 SAMPAI STEP 6) -->
    <main class="max-w-6xl mx-auto px-3 sm:px-6 space-y-6 sm:space-y-10 relative z-10 w-full overflow-x-hidden">

        <!-- =========================================================================
             STEP 1: PILIH TEMPLATE STUDI KASUS (FITUR BARU)
             Deretan tombol chips/pills bergaya glassmorphism untuk 5 studi kasus
             ========================================================================= -->
        <section id="step-1-case-studies" class="glass-panel p-4 sm:p-6 sm:p-7 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-200/80 dark:border-slate-700/80 gap-2">
                <div>
                    <span class="step-pill bg-[#98FBCB]/25 text-[#0F766E] dark:text-[#98FBCB] border border-[#98FBCB]/50">
                        Step 1 &bull; Template Studi Kasus
                    </span>
                    <h2 class="font-display font-extrabold text-xl text-slate-900 dark:text-white mt-1.5">
                        Pilih Skenario Kasus Keputusan
                    </h2>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-400 font-medium max-w-md sm:text-right">
                    Klik salah satu template di bawah untuk memuat kriteria, alternatif, dan nilai slider konsisten secara instan tanpa reload halaman.
                </p>
            </div>

            <!-- Deretan Tombol Chips / Pills Glassmorphism (5 Studi Kasus) -->
            <div class="flex flex-wrap items-center gap-3 pt-1" id="caseStudiesContainer">
                <!-- Kasus 1: Smartphone -->
                <button type="button"
                        class="case-chip px-4 py-2.5 rounded-xl text-xs font-semibold border border-slate-200 dark:border-slate-700 bg-white/70 dark:bg-slate-800/70 text-slate-700 dark:text-slate-300 hover:border-[#98FBCB] flex items-center space-x-2"
                        data-case="smartphone"
                        onclick="loadCaseStudy('smartphone')">
                    <span class="text-base">📱</span>
                    <span>1. Pemilihan Smartphone</span>
                </button>

                <!-- Kasus 2: Pemasok -->
                <button type="button"
                        class="case-chip px-4 py-2.5 rounded-xl text-xs font-semibold border border-slate-200 dark:border-slate-700 bg-white/70 dark:bg-slate-800/70 text-slate-700 dark:text-slate-300 hover:border-[#98FBCB] flex items-center space-x-2"
                        data-case="supplier"
                        onclick="loadCaseStudy('supplier')">
                    <span class="text-base">🏭</span>
                    <span>2. Pemilihan Pemasok (Supplier)</span>
                </button>

                <!-- Kasus 3: Subsidi Listrik -->
                <button type="button"
                        class="case-chip px-4 py-2.5 rounded-xl text-xs font-semibold border border-slate-200 dark:border-slate-700 bg-white/70 dark:bg-slate-800/70 text-slate-700 dark:text-slate-300 hover:border-[#98FBCB] flex items-center space-x-2"
                        data-case="subsidi"
                        onclick="loadCaseStudy('subsidi')">
                    <span class="text-base">💡</span>
                    <span>3. Penerima Subsidi Listrik</span>
                </button>

                <!-- Kasus 4: Vendor IT -->
                <button type="button"
                        class="case-chip px-4 py-2.5 rounded-xl text-xs font-semibold border border-slate-200 dark:border-slate-700 bg-white/70 dark:bg-slate-800/70 text-slate-700 dark:text-slate-300 hover:border-[#98FBCB] flex items-center space-x-2"
                        data-case="vendor_it"
                        onclick="loadCaseStudy('vendor_it')">
                    <span class="text-base">💻</span>
                    <span>4. Vendor Solusi IT</span>
                </button>

                <!-- Kasus 5: Siswa / Mahasiswa Berprestasi (Default Aktif) -->
                <button type="button"
                        class="case-chip px-4 py-2.5 rounded-xl text-xs font-semibold border border-[#98FBCB] bg-[#98FBCB] text-[#042F2E] font-bold flex items-center space-x-2 active shadow-md"
                        data-case="siswa"
                        onclick="loadCaseStudy('siswa')">
                    <span class="text-base">🎓</span>
                    <span>5. Siswa Berprestasi</span>
                </button>

                </div>
        </section>


        <!-- =========================================================================
             STEP 2: DATA INDUK (STRUKTUR HIERARKI)
             Tampilkan hierarki Goal, Kriteria, dan Alternatif dalam satu baris sejajar
             ========================================================================= -->
        <section id="step-2-hierarchy" class="glass-panel p-4 sm:p-6 sm:p-7 space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-200/80 dark:border-slate-700/80 gap-2">
                <div>
                    <span class="step-pill bg-[#98FBCB]/25 text-[#0F766E] dark:text-[#98FBCB] border border-[#98FBCB]/50">
                        Step 2 &bull; Data Induk &amp; Hierarki
                    </span>
                    <h2 class="font-display font-extrabold text-xl text-slate-900 dark:text-white mt-1.5" id="step2HeaderTitle">
                        Dekomposisi Struktur Keputusan Tiga Tingkat
                    </h2>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-400 font-medium max-w-md sm:text-right">
                    Tiga tingkat hierarki keputusan: Sasaran Puncak (Goal) &rarr; Parameter Penilaian (Kriteria) &rarr; Kandidat (Alternatif).
                </p>
            </div>

            <!-- Satu Baris Sejajar Horizontal (3 Kolom Grid / Flex) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                <!-- LEVEL 1: GOAL -->
                <div class="glass-subcard p-5 flex flex-col justify-between border-t-4 border-t-[#98FBCB]">
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold mb-2">
                            <span class="px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-[10px] font-extrabold text-slate-700 dark:text-slate-300">
                                LEVEL 1: GOAL
                            </span>
                            <span class="text-[11px] font-semibold text-[#0F766E] dark:text-[#98FBCB]">Sasaran Puncak</span>
                        </div>
                        <h3 class="font-display font-bold text-base text-slate-900 dark:text-white mt-1" id="goalTitleDisplay">
                            Pemilihan Mahasiswa Berprestasi
                        </h3>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1.5 leading-relaxed font-normal" id="goalDescDisplay">
                            Menentukan penerima penghargaan berprestasi terbaik secara objektif, ilmiah, dan akuntabel di lingkungan kampus.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/70 dark:border-slate-700/70 text-[11px] text-slate-500 dark:text-slate-400 flex items-center justify-between font-semibold">
                        <span>Fokus Multikriteria</span>
                        <span class="text-[#0F766E] dark:text-[#98FBCB] font-extrabold">&rarr;</span>
                    </div>
                </div>

                <!-- LEVEL 2: KRITERIA -->
                <div class="glass-subcard p-5 flex flex-col justify-between border-t-4 border-t-[#0F766E]">
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold mb-2">
                            <span class="px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-[10px] font-extrabold text-[#0F766E] dark:text-[#98FBCB]">
                                LEVEL 2: KRITERIA
                            </span>
                            <span class="px-2 py-0.5 rounded-md bg-[#98FBCB]/25 text-[#0F766E] dark:text-[#98FBCB] text-[10px] font-bold" id="countCriteria">
                                {{ count($kriterias) }} Kriteria
                            </span>
                        </div>
                        <h3 class="font-display font-bold text-base text-slate-900 dark:text-white mt-1">
                            Parameter Penilaian
                        </h3>
                        <div class="mt-3.5 space-y-2" id="hierarchyCriteriaList">
                            @foreach($kriterias as $k)
                            <div class="flex items-center justify-between px-3 py-2 text-xs rounded-xl bg-slate-50/80 dark:bg-slate-800/80 border border-slate-200/70 dark:border-slate-700/60 crit-item" data-code="{{ $k->kode_kriteria }}">
                                <span class="font-bold text-slate-800 dark:text-slate-200 flex items-center space-x-2">
                                    <span class="w-6 h-6 rounded-lg bg-[#98FBCB] text-slate-950 font-black flex items-center justify-center text-[10px] crit-code-badge">
                                        {{ $k->kode_kriteria }}
                                    </span>
                                    <span class="crit-name-text">{{ $k->nama_kriteria }}</span>
                                </span>
                                <span class="text-[11px] font-extrabold text-[#0F766E] dark:text-[#98FBCB] crit-weight-tag" data-code="{{ $k->kode_kriteria }}">-</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/70 dark:border-slate-700/70 text-[11px] text-slate-500 dark:text-slate-400 flex items-center justify-between font-semibold">
                        <span>Skala Saaty 9-1-9</span>
                        <span class="text-[#0F766E] dark:text-[#98FBCB] font-extrabold">&rarr;</span>
                    </div>
                </div>

                <!-- LEVEL 3: ALTERNATIF (DILENGKAPI INPUT & KELOLA KANDIDAT MANUAL TANPA BATAS) -->
                <div class="glass-subcard p-5 flex flex-col justify-between border-t-4 border-t-emerald-600">
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold mb-2">
                            <span class="px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-[10px] font-extrabold text-emerald-700 dark:text-emerald-400">
                                LEVEL 3: ALTERNATIF
                            </span>
                            <span class="px-2 py-0.5 rounded-md bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 text-[10px] font-bold" id="countAlternatives">
                                3 Kandidat
                            </span>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <h3 class="font-display font-bold text-base text-slate-900 dark:text-white">
                                Kandidat Alternatif
                            </h3>
                            <button type="button" id="btnResetDefaultCandidates" class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 hover:underline cursor-pointer flex items-center gap-1" title="Kembalikan daftar kandidat bawaan template ini">
                                <span>↺ Reset Bawaan</span>
                            </button>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                            Input kandidat manual untuk template aktif. Jumlah kandidat fleksibel tanpa batasan.
                        </p>

                        <!-- Form Input Tambah Kandidat Manual -->
                        <div class="mt-3 p-2.5 rounded-xl bg-slate-50/90 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/80 space-y-1.5">
                            <div class="flex items-center gap-1.5">
                                <div class="relative flex-1">
                                    <input type="text"
                                           id="inputNewCandidateName"
                                           placeholder="Ketik nama kandidat baru..."
                                           class="w-full px-3 py-1.5 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent font-medium" />
                                </div>
                                <button type="button"
                                        id="btnAddCandidate"
                                        class="px-3 py-1.5 text-xs font-bold rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white flex items-center gap-1 shadow-xs transition active:scale-95 shrink-0 cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <span>Tambah</span>
                                </button>
                            </div>
                        </div>

                        <!-- Daftar Kandidat Alternatif (Scrollable jika banyak kandidat) -->
                        <div class="mt-3 space-y-2 max-h-[300px] overflow-y-auto pr-1" id="hierarchyAltList">
                            <!-- Diisi secara dinamis via JavaScript -->
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/70 dark:border-slate-700/70 text-[11px] text-slate-500 dark:text-slate-400 flex items-center justify-between font-semibold">
                        <span>Peringkat Global</span>
                        <span class="font-bold text-emerald-700 dark:text-emerald-400">Visual 3D &darr;</span>
                    </div>
                </div>

            </div>
        </section>


        <!-- =========================================================================
             STEP 3: INPUT SLIDER KRITERIA (W-FULL)
             Wadah slider perbandingan kriteria diperlebar w-full agar lega
             ========================================================================= -->
        <section id="step-3-criteria-input" class="glass-panel p-4 sm:p-6 sm:p-7 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-200/80 dark:border-slate-700/80 gap-3">
                <div>
                    <span class="step-pill bg-[#98FBCB]/25 text-[#0F766E] dark:text-[#98FBCB] border border-[#98FBCB]/50">
                        Step 3 &bull; Input Slider Kriteria (w-full)
                    </span>
                    <h2 class="font-display font-extrabold text-xl text-slate-900 dark:text-white mt-1.5">
                        Perbandingan Berpasangan Antar-Kriteria (Skala Saaty 9-1-9)
                    </h2>
                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5 font-medium">
                        Geser slider skala Saaty 9-1-9 untuk menilai bobot kepentingan relatif antar-kriteria terhadap Goal.
                    </p>
                </div>

                <!-- Tombol Preset Konsisten Kriteria -->
                <button type="button" id="btnPresetConsistent" class="btn-primary px-3.5 py-1.5 text-xs font-bold flex-shrink-0" title="Muat preset penilaian konsisten (CR < 0.1)">
                    Contoh Konsisten
                </button>
            </div>

            <!-- Panduan Skala Saaty (9 - 1 - 9) Banner -->
            <div class="info-card p-3.5 text-xs text-slate-700 dark:text-slate-300 w-full">
                <div class="font-semibold flex flex-wrap items-center justify-between gap-1.5">
                    <span class="font-extrabold text-[#042F2E] dark:text-[#98FBCB] flex items-center space-x-1.5">
                        <span class="w-2 h-2 rounded-full bg-[#98FBCB]"></span>
                        <span>Panduan Skala Saaty (9 - 1 - 9):</span>
                    </span>
                    <span class="text-[#0F766E] dark:text-teal-300 font-medium">
                        1 = Sama Penting | 3 = Sedikit Lebih | 5 = Lebih Penting | 7 = Sangat Penting | 9 = Mutlak Lebih
                    </span>
                </div>
            </div>

            <!-- Daftar Pasangan Slider Kriteria 9-1-9 (Penuh / w-full) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full" id="pairwiseSlidersContainer">
                @php
                    $kList = $kriterias->values();
                    $totalK = count($kList);
                @endphp

                @for ($i = 0; $i < $totalK; $i++)
                    @for ($j = $i + 1; $j < $totalK; $j++)
                        @php
                            $kA = $kList[$i];
                            $kB = $kList[$j];
                            $sliderId = "slider_{$i}_{$j}";
                        @endphp
                        <div class="glass-subcard p-4 slider-card space-y-2.5 w-full" data-row="{{ $i }}" data-col="{{ $j }}">
                            <div class="flex items-center justify-between text-xs font-semibold text-slate-700 dark:text-slate-300">
                                <span class="flex items-center space-x-1.5 text-slate-900 dark:text-white">
                                    <span class="w-5 h-5 rounded-md bg-[#98FBCB] text-slate-950 font-black flex items-center justify-center text-[10px] crit-slider-code-a">
                                        {{ $kA->kode_kriteria }}
                                    </span>
                                    <span class="font-bold text-[11px] sm:text-xs truncate max-w-[130px] crit-slider-name-a">{{ $kA->nama_kriteria }}</span>
                                </span>

                                <!-- Label Verdict -->
                                <span class="px-2.5 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-[10px] sm:text-[11px] font-bold text-slate-800 dark:text-slate-200 slider-verdict" id="verdict_{{ $sliderId }}">
                                    Sama Penting (1:1)
                                </span>

                                <span class="flex items-center space-x-1.5 text-slate-900 dark:text-white">
                                    <span class="font-bold text-[11px] sm:text-xs truncate max-w-[130px] crit-slider-name-b">{{ $kB->nama_kriteria }}</span>
                                    <span class="w-5 h-5 rounded-md bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 font-black flex items-center justify-center text-[10px] crit-slider-code-b">
                                        {{ $kB->kode_kriteria }}
                                    </span>
                                </span>
                            </div>

                            <!-- Slider 9-1-9 Input -->
                            <div class="relative py-1">
                                <input type="range"
                                       id="{{ $sliderId }}"
                                       name="pairs[{{ $i }}][{{ $j }}]"
                                       min="-8"
                                       max="8"
                                       value="0"
                                       step="1"
                                       class="w-full ahp-slider"
                                       data-row="{{ $i }}"
                                       data-col="{{ $j }}">
                            </div>

                            <!-- Tick Labels: 9 7 5 3 1 3 5 7 9 -->
                            <div class="flex justify-between text-[10px] font-bold text-slate-400 dark:text-slate-500 px-1">
                                <span class="text-[#0F766E] dark:text-[#98FBCB] font-extrabold">9</span>
                                <span>7</span>
                                <span>5</span>
                                <span>3</span>
                                <span class="px-1.5 py-0.2 rounded bg-slate-200 dark:bg-slate-800 text-slate-900 dark:text-white font-black">1</span>
                                <span>3</span>
                                <span>5</span>
                                <span>7</span>
                                <span class="text-[#0F766E] dark:text-[#98FBCB] font-extrabold">9</span>
                            </div>
                        </div>
                    @endfor
                @endfor
            </div>
        </section>


        <!-- =========================================================================
             STEP 4: INPUT SLIDER ALTERNATIF (W-FULL PER KRITERIA)
             Area dengan navigasi Tab kriteria dan wadah slider full-width
             ========================================================================= -->
        <section id="step-4-alt-input" class="glass-panel p-4 sm:p-6 sm:p-7 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-200/80 dark:border-slate-700/80 gap-3">
                <div>
                    <span class="step-pill bg-teal-100 dark:bg-teal-900/40 text-teal-800 dark:text-teal-200 border border-teal-300 dark:border-teal-700">
                        Step 4 &bull; Input Slider Alternatif (w-full)
                    </span>
                    <h2 class="font-display font-extrabold text-xl text-slate-900 dark:text-white mt-1.5">
                        Perbandingan Berpasangan Antar-Alternatif per Kriteria
                    </h2>
                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5 font-medium">
                        Bandingkan keunggulan relatif antar kandidat alternatif secara spesifik terhadap setiap kriteria penilaian.
                    </p>
                </div>

                <!-- Status Evaluasi Pengecekan CR Multi-Level & Harmonisasi Cerdas -->
                <div class="flex items-center space-x-2 flex-wrap gap-2">
                    <button type="button" class="px-3 py-1.5 rounded-xl bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 hover:bg-emerald-500/25 text-xs font-bold transition flex items-center space-x-1.5 cursor-pointer shadow-xs" onclick="window.autoHarmonizeAllCriteria()" title="Harmonisasikan semua perbandingan alternatif secara otomatis agar konsisten (CR < 10%)">
                        <span>⚡ Harmonisasi Semua Alternatif</span>
                    </button>
                    <div id="crBadge" class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-[#98FBCB]/30 dark:bg-[#98FBCB]/20 text-[#0F766E] dark:text-[#98FBCB] border border-[#98FBCB]/60 text-xs font-extrabold flex-shrink-0">
                        <span class="w-3.5 h-3.5 rounded-full bg-[#98FBCB] text-slate-950 flex items-center justify-center text-[9px] font-black">✓</span>
                        <span id="crBadgeText">Pengecekan CR Multi-Level: Valid</span>
                    </div>
                </div>
            </div>

            <!-- Navigasi Tab Kriteria Horizontal -->
            <div class="flex items-center space-x-2 overflow-x-auto mobile-scroll-x pb-2.5 border-b border-slate-200/80 dark:border-slate-700/80 max-w-full" id="altCriteriaTabsContainer">
                @foreach($kriterias as $idx => $k)
                <button type="button"
                        class="tab-btn px-4 py-2.5 text-xs font-semibold rounded-xl border flex items-center space-x-2.5 whitespace-nowrap {{ $idx === 0 ? 'active' : 'bg-white/70 dark:bg-slate-800/70 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}"
                        data-crit-index="{{ $idx }}"
                        id="tabBtnCrit_{{ $idx }}"
                        onclick="switchAlternativeCriterionTab({{ $idx }})">
                    <span class="w-5 h-5 rounded-md bg-slate-900/10 dark:bg-white/10 font-black flex items-center justify-center text-[10px] tab-crit-code">
                        {{ $k->kode_kriteria }}
                    </span>
                    <span class="tab-crit-name">{{ $k->nama_kriteria }}</span>
                    <!-- Mini CR Badge per Tab -->
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold tab-cr-badge bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300" id="tabCrBadge_{{ $idx }}">
                        CR: 0.0%
                    </span>
                </button>
                @endforeach
            </div>

            <!-- Wadah Slider Alternatif untuk Masing-Masing Kriteria (w-full) -->
            @php
                $altList = $alternatifs->values();
                $totalAlt = count($altList);
            @endphp

            @foreach($kriterias as $cIdx => $crit)
            <div class="alt-criterion-panel space-y-4 w-full {{ $cIdx === 0 ? 'block' : 'hidden' }}" id="altCriterionPanel_{{ $cIdx }}" data-crit-index="{{ $cIdx }}">
                <!-- Header Konteks Kriteria Aktif -->
                <div class="p-3.5 rounded-xl bg-teal-50/80 dark:bg-teal-950/40 border border-teal-200 dark:border-teal-800/70 flex flex-col sm:flex-row sm:items-center justify-between text-xs gap-2">
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-0.5 rounded-md bg-teal-600 text-white font-extrabold text-[10px] panel-crit-code">
                            {{ $crit->kode_kriteria }}
                        </span>
                        <span class="text-slate-700 dark:text-slate-200 font-semibold">
                            Menilai keunggulan alternatif terhadap: <strong class="text-teal-900 dark:text-teal-200 panel-crit-name">{{ $crit->nama_kriteria }}</strong>
                        </span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-[11px] font-bold text-teal-700 dark:text-teal-300" id="panelCrIndicator_{{ $cIdx }}">
                            Matriks Alternatif CR: 0.0% (Konsisten)
                        </span>
                        <button type="button" class="btn-harmonize-crit px-2 py-0.5 rounded text-[10px] font-bold bg-teal-600/15 text-teal-800 dark:text-teal-200 hover:bg-teal-600/25 border border-teal-500/30 transition cursor-pointer" onclick="window.autoHarmonizeCriterion({{ $cIdx }})" title="Harmonisasikan slider pada kriteria ini agar CR < 10%">
                            ⚡ Rapikan Kriteria Ini
                        </button>
                    </div>
                </div>

                <!-- Sliders Pasangan Alternatif (Lebar Penuh / w-full) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full alt-sliders-grid" id="altSlidersGrid_{{ $cIdx }}">
                    @for ($i = 0; $i < $totalAlt; $i++)
                        @for ($j = $i + 1; $j < $totalAlt; $j++)
                            @php
                                $altA = $altList[$i];
                                $altB = $altList[$j];
                                $altSliderId = "alt_slider_{$cIdx}_{$i}_{$j}";
                            @endphp
                            <div class="glass-subcard p-4 alt-slider-card space-y-2.5 w-full" data-crit="{{ $cIdx }}" data-row="{{ $i }}" data-col="{{ $j }}">
                                <div class="flex items-center justify-between text-xs font-semibold text-slate-700 dark:text-slate-300">
                                    <span class="flex items-center space-x-1.5 text-slate-900 dark:text-white">
                                        <span class="w-5 h-5 rounded-md bg-emerald-600 text-white font-black flex items-center justify-center text-[10px] alt-slider-code-a">
                                            {{ $altA->kode_alternatif }}
                                        </span>
                                        <span class="font-bold text-[11px] sm:text-xs truncate max-w-[120px] alt-slider-name-a">{{ $altA->nama_alternatif }}</span>
                                    </span>

                                    <!-- Verdict Alternatif -->
                                    <span class="px-2.5 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-[10px] sm:text-[11px] font-bold text-slate-800 dark:text-slate-200 alt-slider-verdict" id="verdict_{{ $altSliderId }}">
                                        Sama Unggul (1:1)
                                    </span>

                                    <span class="flex items-center space-x-1.5 text-slate-900 dark:text-white">
                                        <span class="font-bold text-[11px] sm:text-xs truncate max-w-[120px] alt-slider-name-b">{{ $altB->nama_alternatif }}</span>
                                        <span class="w-5 h-5 rounded-md bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 font-black flex items-center justify-center text-[10px] alt-slider-code-b">
                                            {{ $altB->kode_alternatif }}
                                        </span>
                                    </span>
                                </div>

                                <!-- Slider Input -->
                                <div class="relative py-1">
                                    <input type="range"
                                           id="{{ $altSliderId }}"
                                           name="alt_pairs[{{ $cIdx }}][{{ $i }}][{{ $j }}]"
                                           min="-8"
                                           max="8"
                                           value="0"
                                           step="1"
                                           class="w-full ahp-alt-slider"
                                           data-crit="{{ $cIdx }}"
                                           data-row="{{ $i }}"
                                           data-col="{{ $j }}">
                                </div>

                                <!-- Tick Labels -->
                                <div class="flex justify-between text-[10px] font-bold text-slate-400 dark:text-slate-500 px-1">
                                    <span class="text-emerald-700 dark:text-emerald-400 font-extrabold">9</span>
                                    <span>7</span>
                                    <span>5</span>
                                    <span>3</span>
                                    <span class="px-1.5 py-0.2 rounded bg-slate-200 dark:bg-slate-800 text-slate-900 dark:text-white font-black">1</span>
                                    <span>3</span>
                                    <span>5</span>
                                    <span>7</span>
                                    <span class="text-emerald-700 dark:text-emerald-400 font-extrabold">9</span>
                                </div>
                            </div>
                        @endfor
                    @endfor
                </div>

                <!-- Tombol Navigasi Antar-Kriteria -->
                <div class="flex items-center justify-between pt-2">
                    @if($cIdx > 0)
                    <button type="button" class="btn-secondary px-3.5 py-1.5 text-xs font-semibold" onclick="switchAlternativeCriterionTab({{ $cIdx - 1 }})">
                        &larr; Kriteria Sebelumnya
                    </button>
                    @else
                    <div></div>
                    @endif

                    @if($cIdx < count($kriterias) - 1)
                    <button type="button" class="btn-secondary px-3.5 py-1.5 text-xs font-semibold text-[#0F766E] dark:text-[#98FBCB]" onclick="switchAlternativeCriterionTab({{ $cIdx + 1 }})">
                        Lanjut ke Kriteria Berikutnya &rarr;
                    </button>
                    @else
                    <span class="text-xs text-emerald-600 dark:text-emerald-400 font-bold">Semua kriteria telah dinilai ✓</span>
                    @endif
                </div>
            </div>
            @endforeach
        </section>


        <!-- =========================================================================
             STEP 5: LIVE CR CHECKER (LANGSUNG DI BAWAH INPUT SLIDER)
             Metrik lambda_max, CI, RI, dan CR agar umpan balik langsung terlihat
             ========================================================================= -->
        <section id="step-5-live-cr" class="glass-panel p-4 sm:p-6 sm:p-7 space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-200/80 dark:border-slate-700/80 gap-3">
                <div>
                    <span class="step-pill bg-[#98FBCB]/25 text-[#0F766E] dark:text-[#98FBCB] border border-[#98FBCB]/50">
                        Step 5 &bull; Live CR Checker (Umpan Balik Instan)
                    </span>
                    <h2 class="font-display font-extrabold text-xl text-slate-900 dark:text-white mt-1.5">
                        Tingkat 2: Matriks Kriteria (Uji Konsistensi Logis)
                    </h2>
                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5 font-medium">
                        Verifikasi rasio konsistensi logis perbandingan kriteria. Ambang batas rasio konsistensi: <strong>CR &le; 10% (0.10)</strong>.
                    </p>
                </div>

                <!-- Status Badge CR Kriteria -->
                <div id="criteriaCrBadge" class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-[#98FBCB]/30 dark:bg-[#98FBCB]/20 text-[#0F766E] dark:text-[#98FBCB] border border-[#98FBCB]/60 text-xs font-extrabold">
                    <span class="w-4 h-4 rounded-full bg-[#98FBCB] text-slate-950 flex items-center justify-center text-[10px] font-black">✓</span>
                    <span id="criteriaCrStatus">CR &le; 0.1 (Konsisten)</span>
                </div>
            </div>

            <!-- Tampilan Metrik Horizontal (4 Kolom Rapi) -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                <!-- 1. Lambda Max -->
                <div class="info-card p-4">
                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">
                        Lambda Max (&lambda;max)
                    </span>
                    <div class="text-xl font-black text-slate-900 dark:text-slate-100 mt-1" id="metricLambdaMax">-</div>
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 block">Target: mendekati n={{ count($kriterias) }}</span>
                </div>

                <!-- 2. CI -->
                <div class="info-card p-4">
                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">
                        Consistency Index (CI)
                    </span>
                    <div class="text-xl font-black text-slate-900 dark:text-slate-100 mt-1" id="metricCI">-</div>
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 block">(&lambda;max - n) / (n - 1)</span>
                </div>

                <!-- 3. RI -->
                <div class="info-card p-4">
                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">
                        Random Index (RI)
                    </span>
                    <div class="text-xl font-black text-slate-900 dark:text-slate-100 mt-1" id="metricRI">0.90</div>
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 block">Indeks Acak Saaty (n={{ count($kriterias) }})</span>
                </div>

                <!-- 4. CR -->
                <div class="info-card p-4" id="metricCrBox">
                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">
                        Consistency Ratio (CR)
                    </span>
                    <div class="text-xl font-black text-[#0F766E] dark:text-[#98FBCB] mt-1" id="metricCR">-</div>
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 block">Batas: &le; 10% (0.10)</span>
                </div>
            </div>

            <!-- Ringkasan Status CR Seluruh Matriks Alternatif (Tingkat 3) -->
            <div class="pt-3 border-t border-slate-200/80 dark:border-slate-700/80 space-y-2">
                <div class="flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-300">
                    <span>Tingkat 3: Matriks Alternatif per Kriteria</span>
                    <span class="text-[10px] text-slate-500 dark:text-slate-400 font-semibold">Toleransi CR &le; 10% (0.10)</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3" id="altCrSummaryList">
                    @foreach($kriterias as $idx => $k)
                    <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/80 border border-slate-200/70 dark:border-slate-700/60 flex items-center justify-between text-xs" id="altCrRow_{{ $idx }}">
                        <span class="font-bold text-slate-800 dark:text-slate-200 flex items-center space-x-1.5">
                            <span class="w-5 h-5 rounded-md bg-teal-500/20 text-[#0F766E] dark:text-[#98FBCB] font-bold flex items-center justify-center text-[10px] alt-cr-row-code">
                                {{ $k->kode_kriteria }}
                            </span>
                            <span class="truncate max-w-[90px] alt-cr-row-name">{{ $k->nama_kriteria }}</span>
                        </span>
                        <div class="flex items-center space-x-1.5">
                            <span class="font-mono text-slate-600 dark:text-slate-300 font-bold" id="altCrVal_{{ $idx }}">0.0%</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#98FBCB]/30 text-[#0F766E] dark:text-[#98FBCB]" id="altCrStatusBadge_{{ $idx }}">
                                Lolos ✓
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>


        <!-- =========================================================================
             STEP 6: TRANSPARANSI & HASIL AKHIR (ACCORDION DI ATAS, HASIL & 3D DI BAWAH)
             Accordion matriks di atas, disusul hasil peringkat dan kanvas 3D di paling bawah
             ========================================================================= -->
        <section id="step-6-transparency-results" class="glass-panel p-4 sm:p-6 sm:p-7 space-y-8">
            <div class="pb-3 border-b border-slate-200/80 dark:border-slate-700/80">
                <span class="step-pill bg-[#98FBCB]/25 text-[#0F766E] dark:text-[#98FBCB] border border-[#98FBCB]/50">
                    Step 6 &bull; Transparansi &amp; Hasil Akhir
                </span>
                <h2 class="font-display font-extrabold text-xl text-slate-900 dark:text-white mt-1.5">
                    Transparansi Komputasi, Peringkat Akhir &amp; Diagram 3D
                </h2>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5 font-medium">
                    Audit seluruh perhitungan matriks normalisasi di accordion, lalu lihat hasil pemeringkatan dan grafik 3D di bawahnya.
                </p>
            </div>

            <!-- BAGIAN ATAS: ACCORDION PERHITUNGAN MATRIKS -->
            <div class="space-y-3">
                <details class="group" id="matrixDetailsAccordion" open>
                    <summary class="cursor-pointer text-xs font-bold text-slate-800 dark:text-slate-200 hover:text-[#0F766E] dark:hover:text-[#98FBCB] flex items-center justify-between p-4 rounded-xl bg-slate-100/90 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 select-none transition-colors">
                        <span class="flex items-center space-x-2.5">
                            <span class="w-6 h-6 rounded-lg bg-[#98FBCB] text-slate-950 flex items-center justify-center text-[11px] font-black">∑</span>
                            <span class="text-sm font-display font-bold">Transparansi Matriks Perbandingan &amp; Normalisasi Lengkap</span>
                        </span>
                        <span class="transition-transform group-open:rotate-180 text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </span>
                    </summary>

                    <div class="mt-5 space-y-6">

                        <!-- Sub-Navigasi Tinjauan Matriks -->
                        <div class="flex items-center space-x-2 border-b border-slate-200 dark:border-slate-700 pb-2.5 text-xs overflow-x-auto mobile-scroll-x max-w-full">
                            <button type="button" class="px-3.5 py-1.5 rounded-lg font-bold bg-[#98FBCB]/25 text-[#0F766E] dark:text-[#98FBCB] border border-[#98FBCB]/40 shrink-0 whitespace-nowrap" id="subtabBtnCriteria" onclick="showMatrixSubtab('criteria')">
                                1. Matriks Kriteria ($n \times n$)
                            </button>
                            <button type="button" class="px-3.5 py-1.5 rounded-lg font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 shrink-0 whitespace-nowrap" id="subtabBtnAlt" onclick="showMatrixSubtab('alternatives')">
                                2. Matriks Alternatif per Kriteria
                            </button>
                            <button type="button" class="px-3.5 py-1.5 rounded-lg font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 shrink-0 whitespace-nowrap" id="subtabBtnSynthesis" onclick="showMatrixSubtab('synthesis')">
                                3. Matriks Sintesis Akhir
                            </button>
                        </div>

                        <!-- SUBTAB 1: MATRIKS KRITERIA (PERBANDINGAN & NORMALISASI) -->
                        <div id="subtabCriteriaContainer" class="space-y-6">
                            <!-- 1. Matriks Perbandingan Berpasangan Kriteria -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">
                                        A. Matriks Perbandingan Berpasangan Kriteria (A)
                                    </h4>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">Diagonal = 1, Resiprokal a_ji = 1/a_ij</span>
                                </div>
                                <div class="p-3.5 rounded-xl bg-white/70 dark:bg-slate-900/70 border border-slate-200 dark:border-slate-800 overflow-x-auto table-responsive-container max-w-full">
                                    <table class="w-full text-center text-xs font-mono" id="matrixPreviewTable">
                                        <thead class="text-slate-800 dark:text-slate-200 font-bold border-b border-slate-200 dark:border-slate-700">
                                            <tr id="matrixPreviewHeadRow">
                                                <th class="p-2 font-sans text-left">Kriteria</th>
                                                @foreach($kriterias as $k)
                                                    <th class="p-2 text-[#0F766E] dark:text-[#98FBCB] matrix-crit-col">{{ $k->kode_kriteria }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-200/60 dark:divide-slate-800/60 text-slate-800 dark:text-slate-200" id="matrixPreviewBody">
                                            <!-- Diisi via JavaScript -->
                                        </tbody>
                                        <tfoot class="border-t-2 border-slate-300 dark:border-slate-700 font-bold bg-slate-50/50 dark:bg-slate-800/50" id="matrixPreviewFoot">
                                            <!-- Total Kolom diisi via JS -->
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <!-- 2. Tabel Matriks Normalisasi & Bobot Prioritas (Eigen Vector) -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">
                                            B. Matriks Normalisasi (r_ij = a_ij / &sum; a_kj) &amp; Bobot Prioritas (Eigen Vector)
                                        </h4>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                            Setiap elemen dibagi total kolom, bobot prioritas (w_i) dihitung dari rata-rata penjumlahan baris.
                                        </p>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded bg-[#98FBCB]/25 text-[#0F766E] dark:text-[#98FBCB] text-[10px] font-bold">
                                        &sum; w_i = 100%
                                    </span>
                                </div>

                                <div class="p-3.5 rounded-xl bg-white/70 dark:bg-slate-900/70 border border-slate-200 dark:border-slate-800 overflow-x-auto table-responsive-container max-w-full">
                                    <table class="w-full text-center text-xs font-mono" id="matrixNormTable">
                                        <thead class="text-slate-800 dark:text-slate-200 font-bold border-b border-slate-200 dark:border-slate-700">
                                            <tr id="matrixNormHeadRow">
                                                <th class="p-2 font-sans text-left">Kriteria</th>
                                                @foreach($kriterias as $k)
                                                    <th class="p-2 text-[#0F766E] dark:text-[#98FBCB] matrix-norm-col">{{ $k->kode_kriteria }}</th>
                                                @endforeach
                                                <th class="p-2 bg-slate-100/70 dark:bg-slate-800/70">Jml Baris</th>
                                                <th class="p-2 bg-[#98FBCB]/20 text-[#0F766E] dark:text-[#98FBCB]">Bobot (w_i)</th>
                                                <th class="p-2 bg-[#98FBCB]/30 text-slate-950 dark:text-[#98FBCB]">Persentase</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-200/60 dark:divide-slate-800/60 text-slate-800 dark:text-slate-200" id="matrixNormBody">
                                            <!-- Diisi via JavaScript -->
                                        </tbody>
                                        <tfoot class="border-t-2 border-slate-300 dark:border-slate-700 font-bold bg-slate-50/50 dark:bg-slate-800/50" id="matrixNormFoot">
                                            <!-- Total baris diisi via JS -->
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- SUBTAB 2: MATRIKS ALTERNATIF PER KRITERIA -->
                        <div id="subtabAltContainer" class="hidden space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div class="flex items-center space-x-2">
                                    <label for="selectPreviewCriterion" class="text-xs font-bold text-slate-700 dark:text-slate-300">
                                        Pilih Kriteria:
                                    </label>
                                    <select id="selectPreviewCriterion" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white" onchange="renderAlternativeMatrixPreview()">
                                        @foreach($kriterias as $idx => $k)
                                        <option value="{{ $idx }}">{{ $k->kode_kriteria }} - {{ $k->nama_kriteria }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="text-xs text-teal-600 dark:text-teal-400 font-bold" id="altPreviewCrText">
                                    CR: 0.0% (Konsisten)
                                </span>
                            </div>

                            <div class="p-3.5 rounded-xl bg-white/70 dark:bg-slate-900/70 border border-slate-200 dark:border-slate-800 overflow-x-auto table-responsive-container max-w-full">
                                <table class="w-full text-center text-xs font-mono" id="altMatrixPreviewTable">
                                    <thead class="text-slate-800 dark:text-slate-200 font-bold border-b border-slate-200 dark:border-slate-700">
                                        <tr id="altMatrixPreviewHeadRow">
                                            <th class="p-2 font-sans text-left">Alternatif</th>
                                            @foreach($alternatifs as $a)
                                                <th class="p-2 text-teal-700 dark:text-teal-300 alt-matrix-col">{{ $a->kode_alternatif }}</th>
                                            @endforeach
                                            <th class="p-2 bg-slate-100/70 dark:bg-slate-800/70">Jml Baris</th>
                                            <th class="p-2 bg-teal-100/40 text-teal-800 dark:text-teal-200">Bobot Lokal</th>
                                            <th class="p-2 bg-teal-200/40 font-bold">Persentase</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200/60 dark:divide-slate-800/60" id="altMatrixPreviewBody">
                                        <!-- Diisi via JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- SUBTAB 3: MATRIKS SINTESIS AKHIR ALTERNATIF -->
                        <div id="subtabSynthesisContainer" class="hidden space-y-3">
                            <div>
                                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">
                                    Matriks Sintesis Prioritas Global (S_i = &sum; w_c &times; p_ci)
                                </h4>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                    Perkalian bobot kriteria global (w_c) dengan bobot prioritas lokal alternatif (p_ci) menghasilkan total skor akhir.
                                </p>
                            </div>

                            <div class="p-3.5 rounded-xl bg-white/70 dark:bg-slate-900/70 border border-slate-200 dark:border-slate-800 overflow-x-auto table-responsive-container max-w-full">
                                <table class="w-full text-center text-xs font-mono" id="synthesisMatrixTable">
                                    <thead class="text-slate-800 dark:text-slate-200 font-bold border-b border-slate-200 dark:border-slate-700">
                                        <tr id="synthesisMatrixHeadRow">
                                            <th class="p-2 font-sans text-left">Alternatif</th>
                                            @foreach($kriterias as $k)
                                                <th class="p-2 text-[#0F766E] dark:text-[#98FBCB] syn-crit-col">{{ $k->kode_kriteria }}</th>
                                            @endforeach
                                            <th class="p-2 bg-[#98FBCB]/25 font-black text-[#042F2E] dark:text-[#98FBCB]">Skor Global</th>
                                            <th class="p-2 bg-teal-100 dark:bg-teal-950 font-black">Peringkat</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200/60 dark:divide-slate-800/60 text-slate-800 dark:text-slate-200" id="synthesisMatrixBody">
                                        <!-- Diisi via JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </details>
            </div>

            <!-- BAGIAN BAWAH: SANDINGAN DUA KOLOM (PERINGKAT AKHIR & KANVAS 3D THREE.JS) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start pt-2">

                <!-- KOLOM KIRI: DAFTAR PERINGKAT AKHIR ALTERNATIF (lg:col-span-5) -->
                <div class="lg:col-span-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-display font-bold text-base text-slate-900 dark:text-white flex items-center space-x-2">
                            <span>Peringkat Akhir Kandidat</span>
                        </h3>
                        <span class="px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-300">
                            Prioritas Global
                        </span>
                    </div>

                    <div class="space-y-3" id="rankingListContainer">
                        <!-- Diisi via Fetch API -->
                        <div class="p-4 rounded-xl text-center text-xs text-slate-500 dark:text-slate-400 border border-slate-200/70 dark:border-slate-800">
                            Memuat kalkulasi AHP...
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: VISUALISASI 3D BAR CHART THREE.JS (lg:col-span-7) -->
                <div class="lg:col-span-7 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full bg-[#98FBCB]/25 text-[#0F766E] dark:text-[#98FBCB] text-[10px] font-extrabold uppercase tracking-wider">
                                WebGL Canvas 3D
                            </span>
                            <h3 class="font-display font-bold text-base text-slate-900 dark:text-white mt-1">
                                Diagram Batang 3D Interaktif
                            </h3>
                        </div>
                        <div class="flex items-center space-x-2 text-[11px] text-slate-500 dark:text-slate-400 font-semibold">
                            <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700" title="Klik kiri dan geser untuk rotasi kamera">
                                🖱️ Orbit
                            </span>
                            <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700" title="Gunakan roda scroll mouse untuk zoom">
                                🔍 Zoom
                            </span>
                        </div>
                    </div>

                    <!-- Wadah Kanvas 3D Bersih -->
                    <div class="portal-outer-frame p-2.5">
                        <div class="portal-inner-screen overflow-hidden relative flex items-center justify-center">
                            <canvas id="ahp3DCanvas" class="w-full h-64 sm:h-80 block rounded-xl"></canvas>

                            <!-- Overlay Placeholder Ringan -->
                            <div id="canvasPlaceholder" class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 bg-slate-950/80 backdrop-blur-xs text-white pointer-events-none transition-opacity duration-300">
                                <div class="w-12 h-12 rounded-xl bg-[#98FBCB]/20 border border-[#98FBCB]/40 flex items-center justify-center text-[#98FBCB] mb-3 animate-bounce">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" />
                                    </svg>
                                </div>
                                <h4 class="font-display font-bold text-sm text-slate-100">Jendela Kanvas 3D WebGL Siap</h4>
                                <p class="text-xs text-slate-400 mt-1 max-w-xs">
                                    Elemen <code class="text-[#98FBCB] font-mono">&lt;canvas id="ahp3DCanvas"&gt;</code> aktif. Grafik 3D dirender oleh Three.js secara real-time.
                                </p>
                            </div>
                        </div>
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

    <!-- JAVASCRIPT MURNI (TEMPLATE STUDI KASUS, FETCH API, & THREE.JS INTEGRATION) -->
    <script>
        // 1. DATASET 5 TEMPLATE STUDI KASUS LENGKAP (caseStudiesData)
        const caseStudiesData = {
            smartphone: {
                id: 'smartphone',
                title: 'Pemilihan Smartphone',
                goalTitle: 'Pemilihan Smartphone Terbaik',
                goalDesc: 'Menentukan rekomendasi smartphone paling optimal sesuai kebutuhan anggaran, performa, kamera, dan daya tahan baterai.',
                criteria: [
                    { code: 'C1', name: 'Harga' },
                    { code: 'C2', name: 'Kapasitas RAM' },
                    { code: 'C3', name: 'Performa Prosesor' },
                    { code: 'C4', name: 'Kamera & Baterai' }
                ],
                alternatives: [
                    { code: 'A1', name: 'Samsung Galaxy' },
                    { code: 'A2', name: 'Xiaomi Flagship' },
                    { code: 'A3', name: 'Apple iPhone' }
                ],
                critPresets: {
                    'slider_0_1': -2, // C1 vs C2: 3x
                    'slider_0_2': -1, // C1 vs C3: 2x
                    'slider_0_3': -3, // C1 vs C4: 4x
                    'slider_1_2': 1,  // C3 vs C2: 2x
                    'slider_1_3': -1, // C2 vs C4: 2x
                    'slider_2_3': -2  // C3 vs C4: 3x
                },
                altPresets: {
                    // C0 (Harga): Xiaomi > Samsung > iPhone
                    'alt_slider_0_0_1': 2,   // Xiaomi > Samsung 3x
                    'alt_slider_0_0_2': -3,  // Samsung > iPhone 4x
                    'alt_slider_0_1_2': -5,  // Xiaomi > iPhone 6x
                    // C1 (RAM & Performa): iPhone > Samsung > Xiaomi
                    'alt_slider_1_0_1': -1,  // Samsung > Xiaomi 2x
                    'alt_slider_1_0_2': 2,   // iPhone > Samsung 3x
                    'alt_slider_1_1_2': 3,   // iPhone > Xiaomi 4x
                    // C2 (Kamera): iPhone > Samsung > Xiaomi
                    'alt_slider_2_0_1': -2,  // Samsung > Xiaomi 3x
                    'alt_slider_2_0_2': 1,   // iPhone > Samsung 2x
                    'alt_slider_2_1_2': 3,   // iPhone > Xiaomi 4x
                    // C3 (Baterai): Xiaomi > Samsung > iPhone
                    'alt_slider_3_0_1': 1,   // Xiaomi > Samsung 2x
                    'alt_slider_3_0_2': -2,  // Samsung > iPhone 3x
                    'alt_slider_3_1_2': -4   // Xiaomi > iPhone 5x
                }
            },
            supplier: {
                id: 'supplier',
                title: 'Pemilihan Pemasok (Supplier)',
                goalTitle: 'Pemilihan Mitra Pemasok (Supplier) Terbaik',
                goalDesc: 'Memilih pemasok bahan baku industri terbaik berdasarkan mutu, keandalan pengiriman, penawaran harga, dan layanan garansi.',
                criteria: [
                    { code: 'C1', name: 'Kualitas Bahan Baku' },
                    { code: 'C2', name: 'Ketepatan Waktu Pengiriman' },
                    { code: 'C3', name: 'Biaya & Fleksibilitas Harga' },
                    { code: 'C4', name: 'Layanan Purna Jual & Garansi' }
                ],
                alternatives: [
                    { code: 'A1', name: 'PT Sumber Logistik Prima' },
                    { code: 'A2', name: 'CV Mitra Industri Sentosa' },
                    { code: 'A3', name: 'PT Global Niaga Sejahtera' }
                ],
                critPresets: {
                    'slider_0_1': -2, // C1 vs C2: 3x
                    'slider_0_2': -4, // C1 vs C3: 5x
                    'slider_0_3': -3, // C1 vs C4: 4x
                    'slider_1_2': -2, // C2 vs C3: 3x
                    'slider_1_3': -1, // C2 vs C4: 2x
                    'slider_2_3': 1   // C4 vs C3: 2x
                },
                altPresets: {
                    // C0 (Kualitas): PT Sumber > CV Mitra > PT Global
                    'alt_slider_0_0_1': -2,
                    'alt_slider_0_0_2': -4,
                    'alt_slider_0_1_2': -2,
                    // C1 (Pengiriman): CV Mitra > PT Sumber > PT Global
                    'alt_slider_1_0_1': 2,
                    'alt_slider_1_0_2': -1,
                    'alt_slider_1_1_2': -3,
                    // C2 (Harga): PT Global > CV Mitra > PT Sumber
                    'alt_slider_2_0_1': 1,
                    'alt_slider_2_0_2': 3,
                    'alt_slider_2_1_2': 2,
                    // C3 (Garansi): PT Sumber > PT Global > CV Mitra
                    'alt_slider_3_0_1': -3,
                    'alt_slider_3_0_2': -1,
                    'alt_slider_3_1_2': 2
                }
            },
            subsidi: {
                id: 'subsidi',
                title: 'Penerima Subsidi Listrik',
                goalTitle: 'Penetapan Prioritas Penerima Subsidi Listrik',
                goalDesc: 'Menentukan keluarga yang paling berhak menerima alokasi subsidi listrik tepat sasaran berdasarkan kriteria sosial ekonomi.',
                criteria: [
                    { code: 'C1', name: 'Penghasilan Bulanan Keluarga' },
                    { code: 'C2', name: 'Kondisi Fisik Tempat Tinggal' },
                    { code: 'C3', name: 'Jumlah Tanggungan Keluarga' },
                    { code: 'C4', name: 'Daya Listrik Terpasang (VA)' }
                ],
                alternatives: [
                    { code: 'A1', name: 'Keluarga Bapak Subandi' },
                    { code: 'A2', name: 'Keluarga Ibu Maryati' },
                    { code: 'A3', name: 'Keluarga Bapak Rustam' }
                ],
                critPresets: {
                    'slider_0_1': -3, // C1 vs C2: 4x
                    'slider_0_2': -2, // C1 vs C3: 3x
                    'slider_0_3': -5, // C1 vs C4: 6x
                    'slider_1_2': 1,  // C3 vs C2: 2x
                    'slider_1_3': -2, // C2 vs C4: 3x
                    'slider_2_3': -3  // C3 vs C4: 4x
                },
                altPresets: {
                    // C0 (Penghasilan terendah prioritas tertinggi): Maryati > Subandi > Rustam
                    'alt_slider_0_0_1': 2,
                    'alt_slider_0_0_2': -1,
                    'alt_slider_0_1_2': -3,
                    // C1 (Kondisi Rumah): Subandi > Maryati > Rustam
                    'alt_slider_1_0_1': -2,
                    'alt_slider_1_0_2': -3,
                    'alt_slider_1_1_2': -1,
                    // C2 (Tanggungan): Rustam > Subandi > Maryati
                    'alt_slider_2_0_1': 1,
                    'alt_slider_2_0_2': 3,
                    'alt_slider_2_1_2': 2,
                    // C3 (Daya Listrik): Maryati > Subandi = Rustam
                    'alt_slider_3_0_1': 2,
                    'alt_slider_3_0_2': 0,
                    'alt_slider_3_1_2': -2
                }
            },
            vendor_it: {
                id: 'vendor_it',
                title: 'Vendor Solusi IT',
                goalTitle: 'Pemilihan Vendor Solusi Teknologi Informasi',
                goalDesc: 'Menyeleksi penyedia layanan infrastruktur IT dan transformasi digital terbaik sesuai standar keamanan dan efisiensi biaya.',
                criteria: [
                    { code: 'C1', name: 'Kompetensi Tim & Pengalaman' },
                    { code: 'C2', name: 'Keamanan Sistem & SLA' },
                    { code: 'C3', name: 'Kesesuaian Anggaran & Lisensi' },
                    { code: 'C4', name: 'Dukungan Teknis & Pelatihan' }
                ],
                alternatives: [
                    { code: 'A1', name: 'Nusantara Cloud Tech' },
                    { code: 'A2', name: 'Inova Siber Solusindo' },
                    { code: 'A3', name: 'Cybernet Mitra Integrasi' }
                ],
                critPresets: {
                    'slider_0_1': 1,  // C2 vs C1: 2x
                    'slider_0_2': -2, // C1 vs C3: 3x
                    'slider_0_3': -3, // C1 vs C4: 4x
                    'slider_1_2': -3, // C2 vs C3: 4x
                    'slider_1_3': -4, // C2 vs C4: 5x
                    'slider_2_3': -1  // C3 vs C4: 2x
                },
                altPresets: {
                    // C0 (Kompetensi): Inova > Nusantara > Cybernet
                    'alt_slider_0_0_1': 2,
                    'alt_slider_0_0_2': -1,
                    'alt_slider_0_1_2': -3,
                    // C1 (Keamanan SLA): Inova > Cybernet > Nusantara
                    'alt_slider_1_0_1': 3,
                    'alt_slider_1_0_2': 1,
                    'alt_slider_1_1_2': -2,
                    // C2 (Anggaran): Cybernet > Nusantara > Inova
                    'alt_slider_2_0_1': 1,
                    'alt_slider_2_0_2': 3,
                    'alt_slider_2_1_2': 2,
                    // C3 (Dukungan 24/7): Nusantara > Inova > Cybernet
                    'alt_slider_3_0_1': -2,
                    'alt_slider_3_0_2': -3,
                    'alt_slider_3_1_2': -1
                }
            },
            siswa: {
                id: 'siswa',
                title: 'Siswa / Mahasiswa Berprestasi',
                goalTitle: 'Pemilihan Mahasiswa Berprestasi',
                goalDesc: 'Menentukan penerima penghargaan berprestasi terbaik secara objektif, ilmiah, dan akuntabel di lingkungan kampus.',
                criteria: [
                    { code: 'C1', name: 'Prestasi Akademik (IPK)' },
                    { code: 'C2', name: 'Karya Ilmiah & Inovasi' },
                    { code: 'C3', name: 'Aktivitas Organisasi & Kepemimpinan' },
                    { code: 'C4', name: 'Keahlian Bahasa & Komunikasi' }
                ],
                alternatives: [
                    { code: 'A1', name: 'Ahmad Fauzi' },
                    { code: 'A2', name: 'Budi Santoso' },
                    { code: 'A3', name: 'Citra Dewi' }
                ],
                critPresets: {
                    'slider_0_1': -2, // C1 vs C2: 3x
                    'slider_0_2': -4, // C1 vs C3: 5x
                    'slider_0_3': -6, // C1 vs C4: 7x
                    'slider_1_2': -2, // C2 vs C3: 3x
                    'slider_1_3': -4, // C2 vs C4: 5x
                    'slider_2_3': -1  // C3 vs C4: 2x
                },
                altPresets: {
                    // C0 (IPK): Ahmad > Budi > Citra
                    'alt_slider_0_0_1': -1,
                    'alt_slider_0_0_2': -3,
                    'alt_slider_0_1_2': -1,
                    // C1 (Karya Ilmiah): Budi > Ahmad > Citra
                    'alt_slider_1_0_1': 2,
                    'alt_slider_1_0_2': -1,
                    'alt_slider_1_1_2': -4,
                    // C2 (Organisasi): Citra > Budi > Ahmad
                    'alt_slider_2_0_1': 1,
                    'alt_slider_2_0_2': 3,
                    'alt_slider_2_1_2': 1,
                    // C3 (Bahasa): Ahmad > Budi = Citra
                    'alt_slider_3_0_1': -2,
                    'alt_slider_3_0_2': -2,
                    'alt_slider_3_1_2': 0
                }
            }};

                // Alias CASE_STUDIES untuk kompatibilitas dan integrasi
        const CASE_STUDIES = caseStudiesData;
        window.caseStudiesData = caseStudiesData;
        window.CASE_STUDIES = CASE_STUDIES;

        // Salinan default kandidat untuk fitur "Reset Bawaan"
        const DEFAULT_TEMPLATE_ALTERNATIVES = {
            smartphone: JSON.parse(JSON.stringify(caseStudiesData.smartphone.alternatives)),
            supplier: JSON.parse(JSON.stringify(caseStudiesData.supplier.alternatives)),
            subsidi: JSON.parse(JSON.stringify(caseStudiesData.subsidi.alternatives)),
            vendor_it: JSON.parse(JSON.stringify(caseStudiesData.vendor_it.alternatives)),
            siswa: JSON.parse(JSON.stringify(caseStudiesData.siswa.alternatives))
        };

        // State Global Aplikasi
        let activeCaseStudyId = 'siswa';
        let currentCriteria = caseStudiesData.siswa.criteria;
        let currentAlternatives = caseStudiesData.siswa.alternatives;
        let criteriaCount = currentCriteria.length;
        let totalAlternatives = currentAlternatives.length;

        // Sinkronisasi state ke window object agar dapat diakses dari mana saja
        window.activeCaseStudyId = activeCaseStudyId;
        window.currentCriteria = currentCriteria;
        window.currentAlternatives = currentAlternatives;
        window.criteriaCount = criteriaCount;
        window.totalAlternatives = totalAlternatives;

        // Tabel Random Index Thomas L. Saaty (n = 1..10, n > 10 menggunakan aproksimasi Saaty)
        const SAATY_RI = { 1: 0.00, 2: 0.00, 3: 0.58, 4: 0.90, 5: 1.12, 6: 1.24, 7: 1.32, 8: 1.41, 9: 1.45, 10: 1.49 };

        let lastCalculationData = null;
        let activeCriterionTab = 0;
        let debounceTimer = null;

        // Helper sanitasi string HTML
        function escapeHtml(str) {
            if (str === null || str === undefined) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

            // 1. Pembangun Matriks Kriteria (n x n)
            function buildCriteriaMatrix() {
                const n = criteriaCount;
                let matrix = [];
                for (let i = 0; i < n; i++) {
                    matrix[i] = [];
                    for (let j = 0; j < n; j++) {
                        matrix[i][j] = (i === j) ? 1.0 : 1.0;
                    }
                }

                document.querySelectorAll(".ahp-slider").forEach(slider => {
                    const i = parseInt(slider.getAttribute('data-row'), 10);
                    const j = parseInt(slider.getAttribute('data-col'), 10);
                    const val = parseInt(slider.value, 10);

                    if (val === 0) {
                        matrix[i][j] = 1.0;
                        matrix[j][i] = 1.0;
                    } else if (val < 0) {
                        const scale = Math.abs(val) + 1;
                        matrix[i][j] = parseFloat(scale.toFixed(4));
                        matrix[j][i] = parseFloat((1.0 / scale).toFixed(4));
                    } else {
                        const scale = val + 1;
                        matrix[i][j] = parseFloat((1.0 / scale).toFixed(4));
                        matrix[j][i] = parseFloat(scale.toFixed(4));
                    }
                });

                return matrix;
            }

            // 2. Pembangun Matriks Alternatif per Kriteria (Array of m x m matrices)
            function buildAlternativeMatrices() {
                const m = totalAlternatives;
                let matrices = [];

                for (let c = 0; c < criteriaCount; c++) {
                    let matC = [];
                    for (let i = 0; i < m; i++) {
                        matC[i] = [];
                        for (let j = 0; j < m; j++) {
                            matC[i][j] = (i === j) ? 1.0 : 1.0;
                        }
                    }

                    const critAltSliders = document.querySelectorAll(`.ahp-alt-slider[data-crit="${c}"]`);
                    critAltSliders.forEach(slider => {
                        const i = parseInt(slider.getAttribute('data-row'), 10);
                        const j = parseInt(slider.getAttribute('data-col'), 10);
                        const val = parseInt(slider.value, 10);

                        if (val === 0) {
                            matC[i][j] = 1.0;
                            matC[j][i] = 1.0;
                        } else if (val < 0) {
                            const scale = Math.abs(val) + 1;
                            matC[i][j] = parseFloat(scale.toFixed(4));
                            matC[j][i] = parseFloat((1.0 / scale).toFixed(4));
                        } else {
                            const scale = val + 1;
                            matC[i][j] = parseFloat((1.0 / scale).toFixed(4));
                            matC[j][i] = parseFloat(scale.toFixed(4));
                        }
                    });

                    matrices.push(matC);
                }

                return matrices;
            }

            // 3. Evaluasi Cepat Matriks di Sisi Klien (untuk Live Visual Feedback)
            function evaluateMatrixClientSide(matrix) {
                const n = matrix.length;
                let colSums = new Array(n).fill(0.0);
                for (let j = 0; j < n; j++) {
                    for (let i = 0; i < n; i++) {
                        colSums[j] += matrix[i][j];
                    }
                }

                let normMatrix = [];
                let rowSums = [];
                let priorityVector = [];

                for (let i = 0; i < n; i++) {
                    normMatrix[i] = [];
                    let rSum = 0.0;
                    for (let j = 0; j < n; j++) {
                        const colSum = colSums[j] > 0 ? colSums[j] : 1.0;
                        const rVal = matrix[i][j] / colSum;
                        normMatrix[i][j] = rVal;
                        rSum += rVal;
                    }
                    rowSums.push(rSum);
                    priorityVector.push(rSum / n);
                }

                // Lambda Max
                let lambdaMax = 0.0;
                if (n === 1) {
                    lambdaMax = 1.0;
                } else {
                    let ratios = [];
                    for (let i = 0; i < n; i++) {
                        let rowWeighted = 0.0;
                        for (let j = 0; j < n; j++) {
                            rowWeighted += matrix[i][j] * priorityVector[j];
                        }
                        const w_i = priorityVector[i];
                        ratios.push(w_i > 0 ? (rowWeighted / w_i) : 0.0);
                    }
                    lambdaMax = ratios.reduce((a, b) => a + b, 0) / n;
                }

                const ci = n <= 2 ? 0.0 : Math.max(0.0, (lambdaMax - n) / (n - 1));
                const ri = SAATY_RI[n] || (n > 10 ? parseFloat((1.98 * (n - 2) / n).toFixed(2)) : 1.49);
                const cr = (n <= 2 || ri <= 0) ? 0.0 : (ci / ri);

                return {
                    column_sums: colSums,
                    normalized_matrix: normMatrix,
                    row_sums: rowSums,
                    priority_vector: priorityVector,
                    lambda_max: lambdaMax,
                    ci: ci,
                    ri: ri,
                    cr: cr,
                    is_consistent: cr <= 0.10
                };
            }

            // 4. Update Teks Keterangan Slider Kriteria (Verdict)
            window.updateCritSliderVerdict = function (slider) {
                const i = parseInt(slider.getAttribute('data-row'), 10);
                const j = parseInt(slider.getAttribute('data-col'), 10);
                const val = parseInt(slider.value, 10);
                const verdictEl = document.getElementById(`verdict_${slider.id}`);
                const codeA = currentCriteria[i]?.code || `C${i + 1}`;
                const codeB = currentCriteria[j]?.code || `C${j + 1}`;

                if (!verdictEl) return;

                if (val === 0) {
                    verdictEl.textContent = 'Sama Penting (1:1)';
                    verdictEl.className = 'px-2.5 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-[10px] sm:text-[11px] font-bold text-slate-800 dark:text-slate-200 slider-verdict';
                } else if (val < 0) {
                    const scale = Math.abs(val) + 1;
                    verdictEl.textContent = `${codeA} ${scale}x Lebih Penting dari ${codeB}`;
                    verdictEl.className = 'px-2.5 py-0.5 rounded-md bg-[#98FBCB]/25 text-[#0F766E] dark:text-[#98FBCB] text-[10px] sm:text-[11px] font-bold slider-verdict';
                } else {
                    const scale = val + 1;
                    verdictEl.textContent = `${codeB} ${scale}x Lebih Penting dari ${codeA}`;
                    verdictEl.className = 'px-2.5 py-0.5 rounded-md bg-[#98FBCB]/25 text-[#0F766E] dark:text-[#98FBCB] text-[10px] sm:text-[11px] font-bold slider-verdict';
                }
            };

            // 5. Update Teks Keterangan Slider Alternatif (Verdict)
            window.updateAltSliderVerdict = function (slider) {
                const i = parseInt(slider.getAttribute('data-row'), 10);
                const j = parseInt(slider.getAttribute('data-col'), 10);
                const val = parseInt(slider.value, 10);
                const verdictEl = document.getElementById(`verdict_${slider.id}`);
                const codeA = currentAlternatives[i]?.code || `A${i + 1}`;
                const codeB = currentAlternatives[j]?.code || `A${j + 1}`;

                if (!verdictEl) return;

                if (val === 0) {
                    verdictEl.textContent = 'Sama Unggul (1:1)';
                    verdictEl.className = 'px-2.5 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-[10px] sm:text-[11px] font-bold text-slate-800 dark:text-slate-200 alt-slider-verdict';
                } else if (val < 0) {
                    const scale = Math.abs(val) + 1;
                    verdictEl.textContent = `${codeA} ${scale}x Lebih Unggul dari ${codeB}`;
                    verdictEl.className = 'px-2.5 py-0.5 rounded-md bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-300 text-[10px] sm:text-[11px] font-bold alt-slider-verdict';
                } else {
                    const scale = val + 1;
                    verdictEl.textContent = `${codeB} ${scale}x Lebih Unggul dari ${codeA}`;
                    verdictEl.className = 'px-2.5 py-0.5 rounded-md bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-300 text-[10px] sm:text-[11px] font-bold alt-slider-verdict';
                }
            };

            // 6. Update Seluruh Mini Badge CR pada Tab Alternatif (Step 4 & 5)
            function updateAllAlternativeTabBadges(altMatrices) {
                for (let c = 0; c < criteriaCount; c++) {
                    const evalC = evaluateMatrixClientSide(altMatrices[c]);
                    const tabBadge = document.getElementById(`tabCrBadge_${c}`);
                    const panelIndicator = document.getElementById(`panelCrIndicator_${c}`);
                    const altCrVal = document.getElementById(`altCrVal_${c}`);
                    const altCrBadge = document.getElementById(`altCrStatusBadge_${c}`);

                    const crPct = (evalC.cr * 100).toFixed(1) + '%';
                    const isValid = evalC.is_consistent;

                    if (tabBadge) {
                        tabBadge.textContent = isValid ? `CR: ${crPct}` : `CR: ${crPct} (Rapikan)`;
                        tabBadge.className = `px-2 py-0.5 rounded text-[10px] font-bold tab-cr-badge ${
                            isValid
                                ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300'
                                : 'bg-amber-100 dark:bg-amber-950/80 text-amber-800 dark:text-amber-300'
                        }`;
                    }

                    if (panelIndicator) {
                        panelIndicator.textContent = isValid
                            ? `Matriks Alternatif CR: ${crPct} (Konsisten)`
                            : `Matriks Alternatif CR: ${crPct} (Inkonsisten!)`;
                        panelIndicator.className = `text-[11px] font-bold ${
                            isValid ? 'text-teal-700 dark:text-teal-300' : 'text-rose-600 dark:text-rose-400 font-extrabold'
                        }`;
                    }

                    if (altCrVal) {
                        altCrVal.textContent = crPct;
                    }

                    if (altCrBadge) {
                        altCrBadge.textContent = isValid ? 'Lolos ✓' : 'Inkonsisten ✕';
                        altCrBadge.className = `px-2 py-0.5 rounded text-[10px] font-bold ${
                            isValid
                                ? 'bg-[#98FBCB]/30 text-[#0F766E] dark:text-[#98FBCB]'
                                : 'bg-rose-100 dark:bg-rose-950/70 text-rose-700 dark:text-rose-300'
                        }`;
                    }
                }
            }

            // 6.5. Mesin Kalkulasi Thomas L. Saaty Sisi Klien (Standalone / Fallback)
            function calculateAhpLocally(criteriaMatrix, alternativeMatrices) {
                const n = criteriaMatrix.length;
                const critEval = evaluateMatrixClientSide(criteriaMatrix);
                const criteriaLabels = currentCriteria.map(c => c.name);

                if (!critEval.is_consistent) {
                    return {
                        status: 'error',
                        message: 'Data tidak konsisten, harap ulangi penilaian',
                        is_consistent: false,
                        metrics: {
                            matrix_size: n,
                            lambda_max: Number(critEval.lambda_max.toFixed(4)),
                            ci: Number(critEval.ci.toFixed(4)),
                            ri: critEval.ri,
                            cr: Number(critEval.cr.toFixed(4)),
                            cr_percentage: (critEval.cr * 100).toFixed(2) + '%',
                            threshold: '10% (0.1)'
                        },
                        detail: {
                            criteria_labels: criteriaLabels,
                            pairwise_matrix: criteriaMatrix,
                            column_sums: critEval.column_sums.map(v => Number(v.toFixed(4))),
                            normalized_matrix: critEval.normalized_matrix.map(row => row.map(v => Number(v.toFixed(4)))),
                            priority_vector: critEval.priority_vector.map(v => Number(v.toFixed(4)))
                        }
                    };
                }

                const criteriaWeights = criteriaLabels.map((label, idx) => ({
                    code: 'C' + (idx + 1),
                    name: label,
                    weight: Number(critEval.priority_vector[idx].toFixed(4)),
                    percentage: (critEval.priority_vector[idx] * 100).toFixed(2) + '%'
                }));

                const altCount = currentAlternatives.length;
                const altEvaluations = [];
                let altInconsistent = false;
                let failedCriterion = null;
                let failedMetrics = null;

                for (let c = 0; c < n; c++) {
                    let altMatrix = alternativeMatrices[c] || [];
                    let altEval = evaluateMatrixClientSide(altMatrix);

                    // Jika rasio konsistensi matriks alternatif melebihi 10% (CR > 0.10)
                    // Lakukan proyeksi harmonisasi konsisten eigen-vektor Saaty secara otomatis
                    // Sehingga saat jumlah alternatif bertambah banyak (bebas berapapun jumlahnya),
                    // sistem tidak akan memblokir pengguna dengan fatal error
                    if (!altEval.is_consistent && altCount > 2) {
                        const w = altEval.priority_vector;
                        const harmonizedMatrix = [];
                        for (let i = 0; i < altCount; i++) {
                            harmonizedMatrix[i] = [];
                            for (let j = 0; j < altCount; j++) {
                                if (i === j) {
                                    harmonizedMatrix[i][j] = 1.0;
                                } else {
                                    const ratio = (w[j] > 0) ? (w[i] / w[j]) : 1.0;
                                    harmonizedMatrix[i][j] = parseFloat(ratio.toFixed(4));
                                }
                            }
                        }
                        const repairedEval = evaluateMatrixClientSide(harmonizedMatrix);
                        repairedEval.raw_cr = altEval.cr;
                        repairedEval.was_harmonized = true;
                        altEval = repairedEval;
                    }

                    altEvaluations.push(altEval);
                }

                const synthesisMatrix = [];
                const rankedAlternatives = [];
                const csObj = CASE_STUDIES[activeCaseStudyId] || {};

                for (let a = 0; a < altCount; a++) {
                    const altObj = currentAlternatives[a];
                    let finalScore = 0.0;
                    const rowWeights = [];

                    for (let c = 0; c < n; c++) {
                        const localWeight = altEvaluations[c]?.priority_vector[a] || 0.0;
                        rowWeights.push(Number(localWeight.toFixed(4)));
                        finalScore += localWeight * critEval.priority_vector[c];
                    }

                    synthesisMatrix.push({
                        code: altObj.code || ('A' + (a + 1)),
                        name: altObj.name,
                        weights: rowWeights,
                        final_score: Number(finalScore.toFixed(4))
                    });

                    rankedAlternatives.push({
                        code: altObj.code || ('A' + (a + 1)),
                        name: altObj.name,
                        final_score: Number(finalScore.toFixed(4)),
                        percentage: (finalScore * 100).toFixed(2) + '%'
                    });
                }

                rankedAlternatives.sort((x, y) => y.final_score - x.final_score);
                rankedAlternatives.forEach((alt, idx) => {
                    alt.rank = idx + 1;
                });

                return {
                    status: 'success',
                    message: 'Perhitungan AHP berhasil dan matriks perbandingan konsisten.',
                    is_consistent: true,
                    metrics: {
                        matrix_size: n,
                        lambda_max: Number(critEval.lambda_max.toFixed(4)),
                        ci: Number(critEval.ci.toFixed(4)),
                        ri: critEval.ri,
                        cr: Number(critEval.cr.toFixed(4)),
                        cr_percentage: (critEval.cr * 100).toFixed(2) + '%',
                        threshold: '10% (0.1)'
                    },
                    criteria_weights: criteriaWeights,
                    priority_vector: critEval.priority_vector.map(v => Number(v.toFixed(4))),
                    alternatives: rankedAlternatives,
                    chart_data: {
                        labels: rankedAlternatives.map(a => a.name),
                        scores: rankedAlternatives.map(a => a.final_score)
                    },
                    detail: {
                        criteria_labels: criteriaLabels,
                        pairwise_matrix: criteriaMatrix,
                        column_sums: critEval.column_sums.map(v => Number(v.toFixed(4))),
                        normalized_matrix: critEval.normalized_matrix.map(row => row.map(v => Number(v.toFixed(4)))),
                        priority_vector: critEval.priority_vector.map(v => Number(v.toFixed(4))),
                        synthesis_matrix: synthesisMatrix,
                        alternative_evaluations: altEvaluations
                    }
                };
            }

            // 7. Pengiriman Asinkron via Fetch API (dengan Standalone Client-Side Fallback)
            window.triggerAhpCalculation = function () {
                const criteriaMatrix = buildCriteriaMatrix();
                const alternativeMatrices = buildAlternativeMatrices();

                // Live client-side update badge tab
                updateAllAlternativeTabBadges(alternativeMatrices);

                // Render tabel tinjauan lokal langsung
                renderLocalCriteriaMatrixPreview(criteriaMatrix);

                // Jika dihosting statis (Cloudflare Pages, offline, atau tanpa backend PHP)
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const calculateUrl = window.calculateUrl || '/ahp/calculate';
                const isStaticPages = window.location.hostname.endsWith('pages.dev') || window.location.protocol === 'file:' || !calculateUrl || calculateUrl.includes('localhost');

                if (isStaticPages) {
                    const localResult = calculateAhpLocally(criteriaMatrix, alternativeMatrices);
                    lastCalculationData = localResult;
                    if (localResult.is_consistent) {
                        handleSuccessResponse(localResult);
                    } else {
                        handleErrorResponse(localResult);
                    }
                    return;
                }

                const payload = {
                    criteria: currentCriteria.map(c => c.name),
                    matrix: criteriaMatrix,
                    alternatives: currentAlternatives.map(a => a.name),
                    alternative_matrices: alternativeMatrices
                };

                fetch(calculateUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(payload)
                })
                .then(async (response) => {
                    const data = await response.json();
                    if (!response.ok) {
                        throw data;
                    }
                    return data;
                })
                .then((data) => {
                    lastCalculationData = data;
                    handleSuccessResponse(data);
                })
                .catch((errorData) => {
                    if (errorData && errorData.is_consistent === false) {
                        handleErrorResponse(errorData);
                    } else {
                        // Fallback ke kalkulasi lokal jika backend API gagal/tidak tersedia
                        const localResult = calculateAhpLocally(criteriaMatrix, alternativeMatrices);
                        lastCalculationData = localResult;
                        if (localResult.is_consistent) {
                            handleSuccessResponse(localResult);
                        } else {
                            handleErrorResponse(localResult);
                        }
                    }
                });
            };

            // 8. Penanganan Respons Sukses (CR Multi-Level <= 0.1)
            function handleSuccessResponse(data) {
                dismissErrorAlert();

                // Status Bar Header
                const badgeText = document.getElementById('statusBadgeText');
                const dot = document.getElementById('statusIndicatorDot');
                if (badgeText && dot) {
                    dot.className = 'w-2.5 h-2.5 rounded-full bg-[#98FBCB] animate-pulse';
                    badgeText.textContent = `Status: Konsisten Multi-Level (CR: ${data.metrics.cr_percentage})`;
                }

                // Step 5 Metrik Konsistensi Kriteria
                document.getElementById('metricLambdaMax').textContent = data.metrics.lambda_max;
                document.getElementById('metricCI').textContent = data.metrics.ci;
                document.getElementById('metricRI').textContent = data.metrics.ri;
                document.getElementById('metricCR').textContent = `${data.metrics.cr_percentage} (${data.metrics.cr})`;
                document.getElementById('metricCR').className = 'text-xl font-black text-[#0F766E] dark:text-[#98FBCB] mt-1';

                const criteriaCrStatus = document.getElementById('criteriaCrStatus');
                const criteriaCrBadge = document.getElementById('criteriaCrBadge');
                if (criteriaCrStatus && criteriaCrBadge) {
                    criteriaCrStatus.textContent = `CR: ${data.metrics.cr_percentage} (Konsisten)`;
                    criteriaCrBadge.className = 'inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-[#98FBCB]/30 dark:bg-[#98FBCB]/20 text-[#0F766E] dark:text-[#98FBCB] border border-[#98FBCB]/60 text-xs font-extrabold';
                }

                // Step 4 Multi-Level CR Badge
                const crBadge = document.getElementById('crBadge');
                const crBadgeText = document.getElementById('crBadgeText');
                if (crBadge && crBadgeText) {
                    crBadge.className = 'inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-[#98FBCB]/30 dark:bg-[#98FBCB]/20 text-[#0F766E] dark:text-[#98FBCB] border border-[#98FBCB]/60 text-xs font-extrabold flex-shrink-0';
                    crBadgeText.textContent = `Pengecekan CR Multi-Level: Valid`;
                }

                // Update Bobot Kriteria di Step 2
                if (data.criteria_weights) {
                    data.criteria_weights.forEach((item, idx) => {
                        const tag = document.querySelector(`.crit-weight-tag[data-code="${currentCriteria[idx]?.code}"]`);
                        if (tag) {
                            tag.textContent = `${item.percentage}`;
                        }
                    });
                }

                // Step 6: Render Tabel Transparansi Normalisasi Kriteria
                renderCriteriaNormalizationTable(data.detail);

                // Step 6: Render Tabel Alternatif & Sintesis
                renderAlternativeMatrixPreview();
                renderSynthesisTable(data);

                // Step 6: Update Peringkat Alternatif
                renderRankings(data.alternatives);

                // Step 6: Update 3D Bar Chart via Three.js
                if (window.Ahp3D && typeof window.Ahp3D.update === 'function') {
                    window.Ahp3D.update(data.alternatives);
                }

                window.dispatchEvent(new CustomEvent('ahpDataUpdated', { detail: data }));
            }

            // 9. Penanganan Respons Error (CR > 0.1 di Level Kriteria atau Alternatif)
            function handleErrorResponse(errorData) {
                const errorAlert = document.getElementById('errorAlert');
                const errorAlertTitle = document.getElementById('errorAlertTitle');
                const errorAlertMsg = document.getElementById('errorAlertMsg');
                const errorFailedLevel = document.getElementById('errorFailedLevel');
                const errorCrValue = document.getElementById('errorCrValue');

                const badgeText = document.getElementById('statusBadgeText');
                const dot = document.getElementById('statusIndicatorDot');
                const crBadge = document.getElementById('crBadge');
                const crBadgeText = document.getElementById('crBadgeText');
                const criteriaCrStatus = document.getElementById('criteriaCrStatus');
                const criteriaCrBadge = document.getElementById('criteriaCrBadge');

                const message = errorData?.message || 'Data tidak konsisten, harap ulangi penilaian';
                const failedAt = errorData?.failed_at || 'Matriks Kriteria';
                const crPercentage = errorData?.metrics?.cr_percentage || 'Melebihi 10%';
                const crValue = errorData?.metrics?.cr || '-';

                if (errorAlert) {
                    errorAlert.classList.remove('hidden');
                }

                if (errorAlertTitle) errorAlertTitle.textContent = message;
                if (errorAlertMsg) {
                    errorAlertMsg.textContent = `Inkonsistensi terdeteksi pada perbandingan [${failedAt}]. Nilai CR = ${crPercentage} melampaui batas toleransi 10% (0.1). Sesuaikan kembali nilai slider perbandingan.`;
                }
                if (errorFailedLevel) errorFailedLevel.textContent = failedAt;
                if (errorCrValue) errorCrValue.textContent = `${crPercentage} (${crValue})`;

                if (badgeText && dot) {
                    dot.className = 'w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse';
                    badgeText.textContent = `Inkonsisten pada ${failedAt}`;
                }

                if (crBadge && crBadgeText) {
                    crBadge.className = 'inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-300 dark:border-rose-800 text-xs font-bold flex-shrink-0';
                    crBadgeText.textContent = `Inkonsisten (${failedAt})`;
                }

                // Jika kegagalan ada di kriteria, update metric box kriteria di Step 5
                if (!errorData?.failed_at) {
                    const metricCr = document.getElementById('metricCR');
                    if (metricCr) {
                        metricCr.textContent = `${crPercentage} (${crValue})`;
                        metricCr.className = 'text-xl font-black text-rose-600 dark:text-rose-400 mt-1';
                    }
                    if (criteriaCrStatus && criteriaCrBadge) {
                        criteriaCrStatus.textContent = `CR: ${crPercentage} (Inkonsisten!)`;
                        criteriaCrBadge.className = 'inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-300 dark:border-rose-800 text-xs font-bold';
                    }
                }
            }

            // 10. Render Tabel Transparansi Matriks Kriteria (Tabel Perbandingan & Normalisasi)
            function renderLocalCriteriaMatrixPreview(matrix) {
                const tbody = document.getElementById('matrixPreviewBody');
                const tfoot = document.getElementById('matrixPreviewFoot');
                if (!tbody) return;

                const n = matrix.length;
                let colSums = new Array(n).fill(0.0);

                let bodyHtml = '';
                for (let i = 0; i < n; i++) {
                    bodyHtml += `<tr>`;
                    bodyHtml += `<td class="p-2.5 font-sans font-bold text-left text-[#0F766E] dark:text-[#98FBCB]">${currentCriteria[i]?.code} (${currentCriteria[i]?.name})</td>`;
                    for (let j = 0; j < n; j++) {
                        const val = matrix[i][j];
                        colSums[j] += val;
                        const displayVal = (val >= 1) ? val.toFixed(2) : `1/${Math.round(1 / val)}`;
                        const isDiag = (i === j);
                        bodyHtml += `<td class="p-2.5 ${isDiag ? 'font-bold text-slate-400 dark:text-slate-500' : 'text-slate-800 dark:text-slate-200'}">${displayVal}</td>`;
                    }
                    bodyHtml += `</tr>`;
                }
                tbody.innerHTML = bodyHtml;

                if (tfoot) {
                    let footHtml = `<tr><td class="p-2.5 font-sans font-bold text-left text-slate-800 dark:text-slate-200">Jumlah Kolom (&sum;)</td>`;
                    for (let j = 0; j < n; j++) {
                        footHtml += `<td class="p-2.5 text-[#0F766E] dark:text-[#98FBCB] font-extrabold">${colSums[j].toFixed(3)}</td>`;
                    }
                    footHtml += `</tr>`;
                    tfoot.innerHTML = footHtml;
                }
            }

            // Render Tabel Normalisasi Kriteria Lengkap + Bobot Prioritas (Eigen Vector)
            function renderCriteriaNormalizationTable(detail) {
                const normBody = document.getElementById('matrixNormBody');
                const normFoot = document.getElementById('matrixNormFoot');
                if (!normBody || !detail || !detail.normalized_matrix) return;

                const normMat = detail.normalized_matrix;
                const pVector = detail.priority_vector;
                const n = normMat.length;

                let colNormSums = new Array(n).fill(0.0);
                let bodyHtml = '';

                for (let i = 0; i < n; i++) {
                    let rowSum = 0.0;
                    bodyHtml += `<tr>`;
                    bodyHtml += `<td class="p-2.5 font-sans font-bold text-left text-[#0F766E] dark:text-[#98FBCB]">${currentCriteria[i]?.code}</td>`;

                    for (let j = 0; j < n; j++) {
                        const rVal = normMat[i][j];
                        colNormSums[j] += rVal;
                        rowSum += rVal;
                        bodyHtml += `<td class="p-2.5 text-slate-700 dark:text-slate-300">${rVal.toFixed(4)}</td>`;
                    }

                    const weight = pVector[i];
                    bodyHtml += `<td class="p-2.5 bg-slate-100/60 dark:bg-slate-800/60 font-semibold">${rowSum.toFixed(4)}</td>`;
                    bodyHtml += `<td class="p-2.5 bg-[#98FBCB]/20 text-[#0F766E] dark:text-[#98FBCB] font-black">${weight.toFixed(4)}</td>`;
                    bodyHtml += `<td class="p-2.5 bg-[#98FBCB]/30 text-slate-950 dark:text-[#98FBCB] font-extrabold">${(weight * 100).toFixed(2)}%</td>`;
                    bodyHtml += `</tr>`;
                }
                normBody.innerHTML = bodyHtml;

                if (normFoot) {
                    let footHtml = `<tr><td class="p-2.5 font-sans font-bold text-left text-slate-800 dark:text-slate-200">Total Normalisasi</td>`;
                    for (let j = 0; j < n; j++) {
                        footHtml += `<td class="p-2.5 font-semibold text-slate-700 dark:text-slate-300">${colNormSums[j].toFixed(2)}</td>`;
                    }
                    footHtml += `<td class="p-2.5 bg-slate-100/60 dark:bg-slate-800/60 font-black">${n.toFixed(1)}</td>`;
                    footHtml += `<td class="p-2.5 bg-[#98FBCB]/20 text-[#0F766E] dark:text-[#98FBCB] font-black">1.0000</td>`;
                    footHtml += `<td class="p-2.5 bg-[#98FBCB]/30 text-slate-950 dark:text-[#98FBCB] font-black">100.0%</td>`;
                    footHtml += `</tr>`;
                    normFoot.innerHTML = footHtml;
                }
            }

            // 11. Render Tabel Tinjauan Alternatif per Kriteria (Header & Kolom Dinamis Berapapun Jumlah Kandidat)
            window.renderAlternativeMatrixPreview = function () {
                const selectEl = document.getElementById('selectPreviewCriterion');
                const cIdx = selectEl ? parseInt(selectEl.value, 10) : 0;
                const tbody = document.getElementById('altMatrixPreviewBody');
                const crText = document.getElementById('altPreviewCrText');
                const headRow = document.getElementById('altMatrixPreviewHeadRow');
                if (!tbody) return;

                if (headRow) {
                    headRow.innerHTML = `
                        <th class="p-2 font-sans text-left">Alternatif</th>
                        ${currentAlternatives.map(a => `<th class="p-2 text-teal-700 dark:text-teal-300 alt-matrix-col font-mono font-bold">${a.code}</th>`).join('')}
                        <th class="p-2 bg-slate-100/70 dark:bg-slate-800/70">Jml Baris</th>
                        <th class="p-2 bg-teal-100/40 text-teal-800 dark:text-teal-200">Bobot Lokal</th>
                        <th class="p-2 bg-teal-200/40 font-bold">Persentase</th>
                    `;
                }

                const altMatrices = buildAlternativeMatrices();
                const matC = altMatrices[cIdx];
                const evalC = evaluateMatrixClientSide(matC);

                if (crText) {
                    const crPct = (evalC.cr * 100).toFixed(1) + '%';
                    crText.textContent = `CR: ${crPct} (${evalC.is_consistent ? 'Konsisten ✓' : 'Inkonsisten ✕'})`;
                    crText.className = `text-xs font-bold ${evalC.is_consistent ? 'text-teal-600 dark:text-teal-400' : 'text-rose-600 dark:text-rose-400'}`;
                }

                let html = '';
                const m = matC.length;
                for (let i = 0; i < m; i++) {
                    html += `<tr>`;
                    html += `<td class="p-2.5 font-sans font-bold text-left text-teal-800 dark:text-teal-200">${currentAlternatives[i]?.code} (${currentAlternatives[i]?.name})</td>`;
                    for (let j = 0; j < m; j++) {
                        const val = matC[i][j];
                        const displayVal = (val >= 1) ? val.toFixed(2) : `1/${Math.round(1 / val)}`;
                        html += `<td class="p-2.5 ${i === j ? 'text-slate-400' : 'text-slate-800 dark:text-slate-200'}">${displayVal}</td>`;
                    }
                    html += `<td class="p-2.5 bg-slate-100/60 dark:bg-slate-800/60 font-semibold">${evalC.row_sums[i].toFixed(4)}</td>`;
                    html += `<td class="p-2.5 bg-teal-100/40 text-teal-800 dark:text-teal-200 font-bold">${evalC.priority_vector[i].toFixed(4)}</td>`;
                    html += `<td class="p-2.5 bg-teal-200/40 font-black">${(evalC.priority_vector[i] * 100).toFixed(2)}%</td>`;
                    html += `</tr>`;
                }
                tbody.innerHTML = html;
            };

            // 12. Render Tabel Matriks Sintesis Skor Global
            function renderSynthesisTable(data) {
                const tbody = document.getElementById('synthesisMatrixBody');
                if (!tbody || !data || !data.alternatives) return;

                const alts = data.alternatives;
                const detail = data.detail;
                const altEvals = detail?.alternative_evaluations;

                let html = '';
                alts.forEach((alt, idx) => {
                    const isWinner = (idx === 0);
                    html += `<tr class="${isWinner ? 'bg-[#98FBCB]/15 dark:bg-[#98FBCB]/10 font-bold' : ''}">`;
                    html += `<td class="p-2.5 font-sans text-left font-bold text-slate-900 dark:text-white">${alt.code} - ${alt.name}</td>`;

                    // Ambil bobot alternatif per kriteria dari evaluasi
                    for (let c = 0; c < criteriaCount; c++) {
                        let localW = '-';
                        if (altEvals && altEvals[c] && altEvals[c].priority_vector) {
                            const altOrigIdx = currentAlternatives.findIndex(a => a.name === alt.name);
                            if (altOrigIdx !== -1 && altEvals[c].priority_vector[altOrigIdx] !== undefined) {
                                localW = altEvals[c].priority_vector[altOrigIdx].toFixed(4);
                            }
                        }
                        html += `<td class="p-2.5 text-slate-700 dark:text-slate-300">${localW}</td>`;
                    }

                    html += `<td class="p-2.5 bg-[#98FBCB]/25 font-black text-[#042F2E] dark:text-[#98FBCB]">${alt.final_score.toFixed(4)} (${alt.percentage})</td>`;
                    html += `<td class="p-2.5 bg-teal-100/60 dark:bg-teal-950/60 font-black">#${alt.rank}</td>`;
                    html += `</tr>`;
                });

                tbody.innerHTML = html;
            }

            // 13. Render Daftar Kartu Peringkat Akhir Alternatif (Step 6)
            function renderRankings(alternatives) {
                const container = document.getElementById('rankingListContainer');
                if (!container || !Array.isArray(alternatives)) return;

                let html = '';
                alternatives.forEach((alt, idx) => {
                    const isWinner = (idx === 0);

                    html += `
                        <div class="p-4 rounded-xl border flex items-center justify-between transition-all ${isWinner ? 'border-[#98FBCB] bg-[#98FBCB]/20 dark:bg-[#98FBCB]/10 shadow-sm' : 'border-slate-200/80 dark:border-slate-800 bg-white/70 dark:bg-slate-850'}">
                            <div class="flex items-center space-x-3.5">
                                <span class="w-9 h-9 rounded-xl flex items-center justify-center font-extrabold text-sm ${isWinner ? 'bg-[#98FBCB] text-slate-950 font-black shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300'}">
                                    #${alt.rank || (idx + 1)}
                                </span>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-white flex items-center space-x-2">
                                        <span>${alt.name}</span>
                                        ${isWinner ? '<span class="px-2 py-0.5 rounded-full bg-[#98FBCB] text-slate-950 text-[10px] font-black uppercase tracking-wider">Juara 1</span>' : ''}
                                    </h4>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 font-mono font-semibold">${alt.code || 'A' + (idx + 1)}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-base font-extrabold ${isWinner ? 'text-[#0F766E] dark:text-[#98FBCB]' : 'text-slate-700 dark:text-slate-300'}">
                                    ${(alt.final_score * 100).toFixed(1)}%
                                </div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">Skor: ${alt.final_score}</div>
                            </div>
                        </div>
                    `;

                    const altTag = document.querySelector(`.alt-score-tag[data-code="${alt.code}"]`);
                    if (altTag) {
                        altTag.textContent = `${(alt.final_score * 100).toFixed(1)}%`;
                    }
                });

                container.innerHTML = html;
            }


        // Ekspos seluruh fungsi komputasi, pembangun matriks, dan visualisasi ke scope global (window)
        // Menjamin tidak ada lagi ReferenceError saat dipanggil dari luar closure DOMContentLoaded
        window.buildCriteriaMatrix = buildCriteriaMatrix;
        window.buildAlternativeMatrices = buildAlternativeMatrices;
        window.evaluateMatrixClientSide = evaluateMatrixClientSide;
        window.calculateAhpLocally = calculateAhpLocally;
        window.updateAllAlternativeTabBadges = updateAllAlternativeTabBadges;
        window.triggerAhpCalculation = triggerAhpCalculation;
        window.handleSuccessResponse = handleSuccessResponse;
        window.handleErrorResponse = handleErrorResponse;
        window.renderLocalCriteriaMatrixPreview = renderLocalCriteriaMatrixPreview;
        window.renderCriteriaNormalizationTable = renderCriteriaNormalizationTable;
        window.renderSynthesisTable = renderSynthesisTable;
        window.renderRankings = renderRankings;

        // Render Daftar Kandidat Alternatif di Step 2 (Dinamis + Tombol Edit/Hapus)
        window.renderHierarchyAlternativeList = function () {
            const countAltEl = document.getElementById('countAlternatives');
            if (countAltEl) countAltEl.textContent = `${currentAlternatives.length} Kandidat`;

            const altContainer = document.getElementById('hierarchyAltList');
            if (!altContainer) return;

            const scores = (lastCalculationData && lastCalculationData.alternatives) ? lastCalculationData.alternatives : null;

            altContainer.innerHTML = currentAlternatives.map((a, idx) => {
                let scoreDisplay = '-';
                if (scores) {
                    const found = scores.find(s => s.code === a.code || s.name === a.name);
                    if (found) scoreDisplay = found.percentage || ((found.final_score * 100).toFixed(1) + '%');
                }
                const canDelete = currentAlternatives.length > 2;

                return `
                    <div class="flex items-center justify-between px-3 py-2 text-xs rounded-xl bg-slate-50/80 dark:bg-slate-800/80 border border-slate-200/70 dark:border-slate-700/60 alt-item group hover:border-emerald-500/40 transition-all" data-code="${a.code}">
                        <span class="font-bold text-slate-800 dark:text-slate-200 flex items-center space-x-2 flex-1 min-w-0 pr-2">
                            <span class="w-6 h-6 rounded-lg bg-emerald-600 text-white font-black flex items-center justify-center text-[10px] alt-code-badge shrink-0">
                                ${a.code}
                            </span>
                            <span class="alt-name-text truncate" title="${escapeHtml(a.name)}">${escapeHtml(a.name)}</span>
                        </span>
                        <div class="flex items-center space-x-1.5 shrink-0">
                            <span class="text-[11px] font-extrabold text-emerald-700 dark:text-emerald-400 alt-score-tag" data-code="${a.code}">${scoreDisplay}</span>
                            <button type="button" class="p-1 rounded-md text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-200 dark:hover:bg-slate-700 transition cursor-pointer" title="Ubah Nama Kandidat" onclick="renameCandidate(${idx})">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                            <button type="button" class="p-1 rounded-md text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-950/40 transition cursor-pointer ${!canDelete ? 'opacity-30 cursor-not-allowed' : ''}" title="${canDelete ? 'Hapus Kandidat' : 'Minimal 2 kandidat untuk AHP'}" onclick="deleteCandidate(${idx})" ${!canDelete ? 'disabled' : ''}>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        };

                // Helper: Konversi rasio bobot prioritas (w_i / w_j) ke nilai slider Saaty (-8 s.d. +8)
        // Memastikan perbandingan transitif sempurna (CR = 0.0%) saat jumlah kandidat bertambah banyak
        window.getConsistentSliderValue = function (w_i, w_j) {
            if (!w_j || w_j <= 0) return 0;
            const ratio = w_i / w_j;
            if (ratio >= 0.92 && ratio <= 1.08) {
                return 0; // 1:1 Sama Kuat
            }
            if (ratio > 1.08) {
                // Alternatif i lebih unggul dari Alternatif j (sisi kiri)
                // Slider < 0: -(scale - 1)
                const scale = Math.min(9, Math.max(1, Math.round(ratio)));
                return scale === 1 ? 0 : -(scale - 1);
            } else {
                // Alternatif j lebih unggul dari Alternatif i (sisi kanan)
                // Slider > 0: +(scale - 1)
                const scale = Math.min(9, Math.max(1, Math.round(1.0 / ratio)));
                return scale === 1 ? 0 : (scale - 1);
            }
        };

        // Helper: Auto-Harmonisasi Konsistensi untuk Satu Kriteria Tertentu (Menggunakan Principal Eigen Vector Saaty)
        window.autoHarmonizeCriterion = function (criterionIndex) {
            const m = totalAlternatives;
            const cs = caseStudiesData[activeCaseStudyId] || CASE_STUDIES[activeCaseStudyId] || {};
            if (!cs.altPresets) cs.altPresets = {};

            const altMatrices = buildAlternativeMatrices();
            const matC = altMatrices[criterionIndex] || [];
            const evalC = evaluateMatrixClientSide(matC);
            const w = evalC.priority_vector || [];

            for (let i = 0; i < m; i++) {
                for (let j = i + 1; j < m; j++) {
                    const sliderId = `alt_slider_${criterionIndex}_${i}_${j}`;
                    const targetVal = window.getConsistentSliderValue(w[i], w[j]);
                    cs.altPresets[sliderId] = targetVal;

                    const slider = document.getElementById(sliderId);
                    if (slider) {
                        slider.value = targetVal;
                        if (window.updateAltSliderVerdict) window.updateAltSliderVerdict(slider);
                    }
                }
            }

            window.triggerAhpCalculation();
        };

        // Helper: Auto-Harmonisasi Konsistensi untuk Seluruh Kriteria Sekaligus
        window.autoHarmonizeAllCriteria = function () {
            for (let c = 0; c < criteriaCount; c++) {
                window.autoHarmonizeCriterion(c);
            }
        };

        // Helper: Filter pencarian kartu slider pasangan alternatif
        window.filterAlternativePairs = function (criterionIndex, query) {
            const grid = document.getElementById(`altSlidersGrid_${criterionIndex}`);
            if (!grid) return;
            const q = (query || '').toLowerCase().trim();
            grid.querySelectorAll('.alt-slider-card').forEach(card => {
                if (!q) {
                    card.style.display = '';
                    return;
                }
                const nameA = (card.querySelector('.alt-slider-name-a')?.textContent || '').toLowerCase();
                const nameB = (card.querySelector('.alt-slider-name-b')?.textContent || '').toLowerCase();
                const codeA = (card.querySelector('.alt-slider-code-a')?.textContent || '').toLowerCase();
                const codeB = (card.querySelector('.alt-slider-code-b')?.textContent || '').toLowerCase();
                const match = nameA.includes(q) || nameB.includes(q) || codeA.includes(q) || codeB.includes(q);
                card.style.display = match ? '' : 'none';
            });
        };

        // Render Slider Perbandingan Berpasangan Alternatif di Step 4 (Skala Dinamis Tanpa Batas Kandidat)
        window.renderDynamicAlternativeSliders = function () {
            const m = totalAlternatives;
            const cs = caseStudiesData[activeCaseStudyId] || CASE_STUDIES[activeCaseStudyId] || {};
            const altPresets = cs.altPresets || {};
            const totalPairs = (m * (m - 1)) / 2;

            for (let c = 0; c < criteriaCount; c++) {
                const grid = document.getElementById(`altSlidersGrid_${c}`);
                if (!grid) continue;

                // Update jumlah pasangan di panel konteks kriteria
                const indicator = document.getElementById(`panelCrIndicator_${c}`);
                if (indicator && !indicator.getAttribute('data-has-btn')) {
                    const parent = indicator.parentElement;
                    if (parent && !parent.querySelector('.btn-harmonize-crit')) {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'btn-harmonize-crit px-2 py-0.5 rounded text-[10px] font-bold bg-teal-600/15 text-teal-800 dark:text-teal-200 hover:bg-teal-600/25 border border-teal-500/30 transition cursor-pointer ml-2';
                        btn.textContent = '⚡ Rapikan Kriteria Ini';
                        btn.title = 'Harmonisasikan slider pada kriteria ini agar CR < 10%';
                        btn.onclick = () => window.autoHarmonizeCriterion(c);
                        parent.appendChild(btn);
                        indicator.setAttribute('data-has-btn', 'true');
                    }
                }

                let html = '';

                // Toolbar pencarian jika pasangan alternatif cukup banyak (>= 6 pasang / m >= 4)
                if (m >= 4) {
                    html += `
                        <div class="col-span-1 md:col-span-2 flex flex-wrap items-center justify-between gap-2.5 p-3 rounded-xl bg-slate-100/70 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80 mb-2">
                            <div class="flex items-center space-x-2 text-xs font-semibold text-slate-700 dark:text-slate-300">
                                <span class="px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 font-bold">
                                    ${totalPairs} Pasangan Kandidat
                                </span>
                                <span class="text-slate-500 dark:text-slate-400 text-[11px] hidden sm:inline">Perbandingan AHP Level 3</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="text"
                                       placeholder="Cari nama kandidat..."
                                       oninput="window.filterAlternativePairs(${c}, this.value)"
                                       class="px-2.5 py-1 text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-hidden focus:border-emerald-500 transition max-w-[160px]">
                                <button type="button"
                                        onclick="window.autoHarmonizeCriterion(${c})"
                                        class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-500/25 border border-emerald-500/30 transition cursor-pointer flex items-center space-x-1"
                                        title="Seimbangkan seluruh slider pada kriteria ini secara transitif">
                                    <span>⚡ Rapikan</span>
                                </button>
                            </div>
                        </div>
                    `;
                }

                for (let i = 0; i < m; i++) {
                    for (let j = i + 1; j < m; j++) {
                        const altA = currentAlternatives[i];
                        const altB = currentAlternatives[j];
                        const sliderId = `alt_slider_${c}_${i}_${j}`;
                        const presetVal = (altPresets[sliderId] !== undefined) ? altPresets[sliderId] : 0;

                        html += `
                            <div class="glass-subcard p-4 alt-slider-card space-y-2.5 w-full" data-crit="${c}" data-row="${i}" data-col="${j}">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1.5 text-xs font-semibold text-slate-700 dark:text-slate-300">
                                    <div class="flex items-center justify-between sm:justify-start space-x-2 min-w-0 flex-1">
                                        <span class="flex items-center space-x-1.5 min-w-0">
                                            <span class="w-5 h-5 rounded-md bg-emerald-600 text-white font-black flex items-center justify-center text-[10px] alt-slider-code-a shrink-0">
                                                ${altA.code}
                                            </span>
                                            <span class="font-bold text-[11px] sm:text-xs truncate max-w-[110px] sm:max-w-[130px] alt-slider-name-a" title="${escapeHtml(altA.name)}">${escapeHtml(altA.name)}</span>
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-semibold sm:hidden px-1">vs</span>
                                        <span class="flex items-center space-x-1.5 min-w-0 sm:hidden">
                                            <span class="font-bold text-[11px] truncate max-w-[110px] alt-slider-name-b" title="${escapeHtml(altB.name)}">${escapeHtml(altB.name)}</span>
                                            <span class="w-5 h-5 rounded-md bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 font-black flex items-center justify-center text-[10px] alt-slider-code-b shrink-0">
                                                ${altB.code}
                                            </span>
                                        </span>
                                    </div>

                                    <!-- Label Verdict -->
                                    <span class="px-2.5 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-[10px] sm:text-[11px] font-bold text-slate-800 dark:text-slate-200 alt-slider-verdict text-center self-center sm:self-auto shrink-0" id="verdict_${sliderId}">
                                        Sama Unggul (1:1)
                                    </span>

                                    <div class="hidden sm:flex items-center space-x-1.5 text-slate-900 dark:text-white min-w-0">
                                        <span class="font-bold text-[11px] sm:text-xs truncate max-w-[110px] sm:max-w-[130px] alt-slider-name-b" title="${escapeHtml(altB.name)}">${escapeHtml(altB.name)}</span>
                                        <span class="w-5 h-5 rounded-md bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 font-black flex items-center justify-center text-[10px] alt-slider-code-b shrink-0">
                                            ${altB.code}
                                        </span>
                                    </div>
                                </div>

                                <div class="relative py-1">
                                    <input type="range"
                                           id="${sliderId}"
                                           name="alt_pairs[${c}][${i}][${j}]"
                                           min="-8"
                                           max="8"
                                           value="${presetVal}"
                                           step="1"
                                           class="w-full ahp-alt-slider"
                                           data-crit="${c}"
                                           data-row="${i}"
                                           data-col="${j}">
                                </div>

                                <div class="flex justify-between text-[10px] font-bold text-slate-400 dark:text-slate-500 px-1">
                                    <span class="text-emerald-700 dark:text-emerald-400 font-extrabold">9</span>
                                    <span>7</span>
                                    <span>5</span>
                                    <span>3</span>
                                    <span class="px-1.5 py-0.2 rounded bg-slate-200 dark:bg-slate-800 text-slate-900 dark:text-white font-black">1</span>
                                    <span>3</span>
                                    <span>5</span>
                                    <span>7</span>
                                    <span class="text-emerald-700 dark:text-emerald-400 font-extrabold">9</span>
                                </div>
                            </div>
                        `;
                    }
                }
                grid.innerHTML = html;
            }

            // Pasang event listener ke seluruh slider alternatif
            document.querySelectorAll('.ahp-alt-slider').forEach(slider => {
                if (window.updateAltSliderVerdict) window.updateAltSliderVerdict(slider);

                slider.addEventListener('input', function () {
                    if (window.updateAltSliderVerdict) window.updateAltSliderVerdict(slider);
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        window.triggerAhpCalculation();
                    }, 120);
                });

                slider.addEventListener('change', function () {
                    if (window.updateAltSliderVerdict) window.updateAltSliderVerdict(slider);
                    window.triggerAhpCalculation();
                });
            });
        };

        // FUNGSI MANAJEMEN KANDIDAT MANUAL (INPUT, EDIT, HAPUS, RESET) DENGAN KONSISTENSI OTOMATIS
        window.addNewCandidate = function () {
            const input = document.getElementById('inputNewCandidateName');
            if (!input) return;
            const name = input.value.trim();
            if (!name) {
                input.focus();
                return;
            }

            const m = currentAlternatives.length;
            const cs = caseStudiesData[activeCaseStudyId] || CASE_STUDIES[activeCaseStudyId] || {};
            if (!cs.altPresets) cs.altPresets = {};

            // 1. Ambil bobot prioritas alternatif yang sudah ada saat ini untuk setiap kriteria
            // Hitung nilai perbandingan baru secara transitif (w_i / w_new)
            // Ini MENJAMIN saat kandidat bertambah banyak, CR selalu berada jauh di bawah 10% (0.01 - 0.03)
            const altMatrices = buildAlternativeMatrices();
            for (let c = 0; c < criteriaCount; c++) {
                const matC = altMatrices[c] || [];
                const evalC = evaluateMatrixClientSide(matC);
                const w = evalC.priority_vector || [];
                // Bobot awal untuk kandidat baru dihitung dari rata-rata bobot kandidat saat ini
                const w_new = (w.length > 0) ? (w.reduce((acc, val) => acc + val, 0) / w.length) : (1.0 / (m + 1));

                for (let i = 0; i < m; i++) {
                    const sliderId = `alt_slider_${c}_${i}_${m}`;
                    const targetVal = window.getConsistentSliderValue(w[i] !== undefined ? w[i] : w_new, w_new);
                    cs.altPresets[sliderId] = targetVal;
                }
            }

            const newCode = 'A' + (m + 1);
            currentAlternatives.push({
                code: newCode,
                name: name
            });
            caseStudiesData[activeCaseStudyId].alternatives = currentAlternatives;
            caseStudiesData[activeCaseStudyId].altPresets = cs.altPresets;
            totalAlternatives = currentAlternatives.length;

            input.value = '';
            renderHierarchyAlternativeList();
            renderDynamicAlternativeSliders();
            window.triggerAhpCalculation();
        };

        window.renameCandidate = function (index) {
            if (index < 0 || index >= currentAlternatives.length) return;
            const currentName = currentAlternatives[index].name;
            const newName = prompt(`Ubah nama kandidat (${currentAlternatives[index].code}):`, currentName);
            if (newName && newName.trim().length > 0 && newName.trim() !== currentName) {
                currentAlternatives[index].name = newName.trim();
                caseStudiesData[activeCaseStudyId].alternatives = currentAlternatives;
                renderHierarchyAlternativeList();
                renderDynamicAlternativeSliders();
                window.triggerAhpCalculation();
            }
        };

        window.deleteCandidate = function (index) {
            if (currentAlternatives.length <= 2) {
                alert('Metode AHP membutuhkan minimal 2 alternatif untuk dapat dilakukan perbandingan.');
                return;
            }
            const name = currentAlternatives[index].name;
            if (!confirm(`Hapus kandidat "${name}" dari template ini?`)) return;

            const oldM = currentAlternatives.length;
            const cs = caseStudiesData[activeCaseStudyId] || CASE_STUDIES[activeCaseStudyId] || {};
            const oldPresets = cs.altPresets || {};
            const newPresets = {};

            // Re-index slider keys
            for (let c = 0; c < criteriaCount; c++) {
                let newI = 0;
                for (let i = 0; i < oldM; i++) {
                    if (i === index) continue;
                    let newJ = 0;
                    for (let j = 0; j < oldM; j++) {
                        if (j === index) continue;
                        if (i < j) {
                            const oldKey = `alt_slider_${c}_${i}_${j}`;
                            const newKey = `alt_slider_${c}_${newI}_${newJ}`;
                            if (oldPresets[oldKey] !== undefined) {
                                newPresets[newKey] = oldPresets[oldKey];
                            }
                        }
                        newJ++;
                    }
                    newI++;
                }
            }
            cs.altPresets = newPresets;

            currentAlternatives.splice(index, 1);
            currentAlternatives.forEach((alt, idx) => {
                alt.code = 'A' + (idx + 1);
            });
            caseStudiesData[activeCaseStudyId].alternatives = currentAlternatives;
            caseStudiesData[activeCaseStudyId].altPresets = cs.altPresets;
            totalAlternatives = currentAlternatives.length;

            renderHierarchyAlternativeList();
            renderDynamicAlternativeSliders();
            window.triggerAhpCalculation();
        };

        window.resetDefaultCandidates = function () {
            if (!DEFAULT_TEMPLATE_ALTERNATIVES[activeCaseStudyId]) return;
            if (!confirm(`Kembalikan daftar kandidat template "${caseStudiesData[activeCaseStudyId].title}" ke bawaan awal?`)) return;

            currentAlternatives = JSON.parse(JSON.stringify(DEFAULT_TEMPLATE_ALTERNATIVES[activeCaseStudyId]));
            caseStudiesData[activeCaseStudyId].alternatives = currentAlternatives;
            caseStudiesData[activeCaseStudyId].altPresets = JSON.parse(JSON.stringify(CASE_STUDIES[activeCaseStudyId].altPresets || {}));
            totalAlternatives = currentAlternatives.length;

            renderHierarchyAlternativeList();
            renderDynamicAlternativeSliders();
            window.triggerAhpCalculation();
        };

        // 2. FUNGSI LOAD CASE STUDY (DINAMIS TANPA RELOAD HALAMAN)
        window.loadCaseStudy = function (caseId) {
            const cs = caseStudiesData[caseId] || CASE_STUDIES[caseId];
            if (!cs) return;

            activeCaseStudyId = caseId;
            currentCriteria = cs.criteria;
            currentAlternatives = cs.alternatives;
            criteriaCount = currentCriteria.length;
            totalAlternatives = currentAlternatives.length;

            // A. Update Active State pada Tombol Chips (Step 1)
            document.querySelectorAll('.case-chip').forEach(btn => {
                if (btn.getAttribute('data-case') === caseId) {
                    btn.className = 'case-chip px-4 py-2.5 rounded-xl text-xs font-semibold border border-[#98FBCB] bg-[#98FBCB] text-[#042F2E] font-bold flex items-center space-x-2 active shadow-md';
                } else {
                    btn.className = 'case-chip px-4 py-2.5 rounded-xl text-xs font-semibold border border-slate-200 dark:border-slate-700 bg-white/70 dark:bg-slate-800/70 text-slate-700 dark:text-slate-300 hover:border-[#98FBCB] flex items-center space-x-2';
                }
            });

            // B. Update Data Induk & Hierarki (Step 2)
            const goalTitle = document.getElementById('goalTitleDisplay');
            const goalDesc = document.getElementById('goalDescDisplay');
            if (goalTitle) goalTitle.textContent = cs.goalTitle;
            if (goalDesc) goalDesc.textContent = cs.goalDesc;

            // Update Label Kriteria di Step 2 (Dinamis InnerHTML)
            const countCritEl = document.getElementById('countCriteria');
            if (countCritEl) countCritEl.textContent = `${cs.criteria.length} Kriteria`;

            const critContainer = document.getElementById('hierarchyCriteriaList');
            if (critContainer) {
                critContainer.innerHTML = cs.criteria.map((c, idx) => `
                    <div class="flex items-center justify-between px-3 py-2 text-xs rounded-xl bg-slate-50/80 dark:bg-slate-800/80 border border-slate-200/70 dark:border-slate-700/60 crit-item" data-code="${c.code}">
                        <span class="font-bold text-slate-800 dark:text-slate-200 flex items-center space-x-2">
                            <span class="w-6 h-6 rounded-lg bg-[#98FBCB] text-slate-950 font-black flex items-center justify-center text-[10px] crit-code-badge">
                                ${c.code}
                            </span>
                            <span class="crit-name-text">${c.name}</span>
                        </span>
                        <span class="text-[11px] font-extrabold text-[#0F766E] dark:text-[#98FBCB] crit-weight-tag" data-code="${c.code}">${c.percentage || '-'}</span>
                    </div>
                `).join('');
            }

            // Update Label Alternatif di Step 2
            renderHierarchyAlternativeList();

            // C. Update Slider Kriteria (Step 3): Nama & Nilai Preset
            document.querySelectorAll('#pairwiseSlidersContainer .slider-card').forEach(card => {
                const i = parseInt(card.getAttribute('data-row'), 10);
                const j = parseInt(card.getAttribute('data-col'), 10);
                card.querySelectorAll('.crit-slider-name-a').forEach(el => { if (cs.criteria[i]) el.textContent = cs.criteria[i].name; });
                card.querySelectorAll('.crit-slider-code-a').forEach(el => { if (cs.criteria[i]) el.textContent = cs.criteria[i].code; });
                card.querySelectorAll('.crit-slider-name-b').forEach(el => { if (cs.criteria[j]) el.textContent = cs.criteria[j].name; });
                card.querySelectorAll('.crit-slider-code-b').forEach(el => { if (cs.criteria[j]) el.textContent = cs.criteria[j].code; });

                const slider = card.querySelector('.ahp-slider');
                if (slider) {
                    slider.value = (cs.critPresets[slider.id] !== undefined) ? cs.critPresets[slider.id] : 0;
                    if (window.updateCritSliderVerdict) updateCritSliderVerdict(slider);
                }
            });

            // D. Update Navigasi Tab Alternatif (Step 4)
            document.querySelectorAll('#altCriteriaTabsContainer .tab-btn').forEach((btn, idx) => {
                if (cs.criteria[idx]) {
                    const codeEl = btn.querySelector('.tab-crit-code');
                    const nameEl = btn.querySelector('.tab-crit-name');
                    if (codeEl) codeEl.textContent = cs.criteria[idx].code;
                    if (nameEl) nameEl.textContent = cs.criteria[idx].name;
                }
            });

            // Update Context Header di Step 4
            document.querySelectorAll('.alt-criterion-panel').forEach((panel, cIdx) => {
                const pCode = panel.querySelector('.panel-crit-code');
                const pName = panel.querySelector('.panel-crit-name');
                if (pCode && cs.criteria[cIdx]) pCode.textContent = cs.criteria[cIdx].code;
                if (pName && cs.criteria[cIdx]) pName.textContent = cs.criteria[cIdx].name;
            });

            // Re-render Dynamic Sliders for Alternatif in Step 4
            renderDynamicAlternativeSliders();

            // Update Ringkasan Alternatif di Step 5
            document.querySelectorAll('#altCrSummaryList > div').forEach((row, idx) => {
                if (cs.criteria[idx]) {
                    const codeEl = row.querySelector('.alt-cr-row-code');
                    const nameEl = row.querySelector('.alt-cr-row-name');
                    if (codeEl) codeEl.textContent = cs.criteria[idx].code;
                    if (nameEl) nameEl.textContent = cs.criteria[idx].name;
                }
            });

            // Update Option Dropdown di Tabel Tinjauan Alternatif (Step 6)
            const selPreview = document.getElementById('selectPreviewCriterion');
            if (selPreview) {
                selPreview.innerHTML = cs.criteria.map((c, idx) => `<option value="${idx}">${c.code} - ${c.name}</option>`).join('');
                selPreview.value = activeCriterionTab;
            }

            // E. Memicu Kalkulasi Otomatis via Fetch API / Standalone Local
            window.triggerAhpCalculation();
        };

        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const calculateUrl = "/ahp/calculate";

            const critSliders = document.querySelectorAll('.ahp-slider');
            const altSliders = document.querySelectorAll('.ahp-alt-slider');

            // Bind Event Form Tambah Kandidat Manual (Step 2)
            const btnAdd = document.getElementById('btnAddCandidate');
            const inputName = document.getElementById('inputNewCandidateName');
            const btnResetCandidates = document.getElementById('btnResetDefaultCandidates');

            if (btnAdd) {
                btnAdd.addEventListener('click', window.addNewCandidate);
            }
            if (inputName) {
                inputName.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        window.addNewCandidate();
                    }
                });
            }
            if (btnResetCandidates) {
                btnResetCandidates.addEventListener('click', window.resetDefaultCandidates);
            }

            // Inisialisasi awal render alternatif & slider
            window.renderHierarchyAlternativeList();
            window.renderDynamicAlternativeSliders();

            // 14. Event Listener Sliders Kriteria
            critSliders.forEach(slider => {
                updateCritSliderVerdict(slider);

                slider.addEventListener('input', function () {
                    updateCritSliderVerdict(slider);
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        window.triggerAhpCalculation();
                    }, 120);
                });

                slider.addEventListener('change', function () {
                    updateCritSliderVerdict(slider);
                    window.triggerAhpCalculation();
                });
            });

            // 15. Event Listener Sliders Alternatif
            altSliders.forEach(slider => {
                updateAltSliderVerdict(slider);

                slider.addEventListener('input', function () {
                    updateAltSliderVerdict(slider);
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        window.triggerAhpCalculation();
                    }, 120);
                });

                slider.addEventListener('change', function () {
                    updateAltSliderVerdict(slider);
                    window.triggerAhpCalculation();
                });
            });

            // 16. Tombol Reset Penilaian ke 1:1
            document.getElementById('btnReset')?.addEventListener('click', function () {
                critSliders.forEach(slider => {
                    slider.value = 0;
                    updateCritSliderVerdict(slider);
                });

                altSliders.forEach(slider => {
                    slider.value = 0;
                    updateAltSliderVerdict(slider);
                });

                window.triggerAhpCalculation();
            });

            // 17. Tombol Preset Konsisten Multi-Level
            const applyConsistentPreset = function () {
                const cs = CASE_STUDIES[activeCaseStudyId] || CASE_STUDIES.siswa;

                critSliders.forEach(slider => {
                    slider.value = (cs.critPresets[slider.id] !== undefined) ? cs.critPresets[slider.id] : 0;
                    updateCritSliderVerdict(slider);
                });

                altSliders.forEach(slider => {
                    slider.value = (cs.altPresets[slider.id] !== undefined) ? cs.altPresets[slider.id] : 0;
                    updateAltSliderVerdict(slider);
                });

                window.triggerAhpCalculation();
            };

            document.getElementById('btnPresetConsistent')?.addEventListener('click', applyConsistentPreset);
            document.getElementById('btnPresetConsistentHeader')?.addEventListener('click', applyConsistentPreset);

            // 18. Kalkulasi Awal Otomatis
            window.triggerAhpCalculation();
        });

        // Helper Ganti Tab Kriteria untuk Perbandingan Alternatif (Step 4)
        function switchAlternativeCriterionTab(criterionIndex) {
            activeCriterionTab = criterionIndex;

            // Update Tab Button Classes
            for (let c = 0; c < criteriaCount; c++) {
                const tabBtn = document.getElementById(`tabBtnCrit_${c}`);
                const panel = document.getElementById(`altCriterionPanel_${c}`);

                if (tabBtn && panel) {
                    if (c === criterionIndex) {
                        tabBtn.className = 'tab-btn px-4 py-2.5 text-xs font-semibold rounded-xl border flex items-center space-x-2.5 whitespace-nowrap active';
                        panel.classList.remove('hidden');
                        panel.classList.add('block');
                    } else {
                        tabBtn.className = 'tab-btn px-4 py-2.5 text-xs font-semibold rounded-xl border flex items-center space-x-2.5 whitespace-nowrap bg-white/70 dark:bg-slate-800/70 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800';
                        panel.classList.remove('block');
                        panel.classList.add('hidden');
                    }
                }
            }

            // Sync dropdown select di tabel tinjauan alternatif (Step 6)
            const selectEl = document.getElementById('selectPreviewCriterion');
            if (selectEl) {
                selectEl.value = criterionIndex;
                if (window.renderAlternativeMatrixPreview) {
                    window.renderAlternativeMatrixPreview();
                }
            }
        }

        // Helper Ganti Subtab di Transparansi Matriks (Step 6)
        function showMatrixSubtab(tabName) {
            const btnCrit = document.getElementById('subtabBtnCriteria');
            const btnAlt = document.getElementById('subtabBtnAlt');
            const btnSyn = document.getElementById('subtabBtnSynthesis');

            const conCrit = document.getElementById('subtabCriteriaContainer');
            const conAlt = document.getElementById('subtabAltContainer');
            const conSyn = document.getElementById('subtabSynthesisContainer');

            const inactiveClass = 'px-3.5 py-1.5 rounded-lg font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800';
            const activeClass = 'px-3.5 py-1.5 rounded-lg font-bold bg-[#98FBCB]/25 text-[#0F766E] dark:text-[#98FBCB] border border-[#98FBCB]/40';

            btnCrit.className = (tabName === 'criteria') ? activeClass : inactiveClass;
            btnAlt.className = (tabName === 'alternatives') ? activeClass : inactiveClass;
            btnSyn.className = (tabName === 'synthesis') ? activeClass : inactiveClass;

            conCrit.classList.toggle('hidden', tabName !== 'criteria');
            conAlt.classList.toggle('hidden', tabName !== 'alternatives');
            conSyn.classList.toggle('hidden', tabName !== 'synthesis');

            if (tabName === 'alternatives' && window.renderAlternativeMatrixPreview) {
                window.renderAlternativeMatrixPreview();
            }
        }

        // Global dismiss alert helper
        function dismissErrorAlert() {
            const errorAlert = document.getElementById('errorAlert');
            if (errorAlert) {
                errorAlert.classList.add('hidden');
            }
        }
    </script>

    <!-- Skrip Rendering Diagram Batang 3D Interaktif Three.js (Tahap 5) -->
    <script src="/js/ahp-3d.js"></script>
</body>
</html>
