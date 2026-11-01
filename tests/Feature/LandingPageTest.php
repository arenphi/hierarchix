<?php

namespace Tests\Feature;

use Tests\TestCase;

class LandingPageTest extends TestCase
{
    /**
     * Test halaman landing berhasil dimuat dengan status 200
     * dan memiliki komponen kunci sesuai instruksi.
     */
    public function test_landing_page_loads_successfully(): void
    {
        $response = $this->get('/landing');

        $response->assertStatus(200);

        // 1. Hero Section & Header Nav (3D Canvas Floating Platonic Solids & Navbar Hierarchix)
        $response->assertSee('Sistem Pendukung Keputusan AHP 3D');
        $response->assertSee('Hierarchix');
        $response->assertSee('Beranda');
        $response->assertSee('Pengenalan');
        $response->assertSee('Rumus');
        $response->assertSee('Simulasi');
        $response->assertSee('Mulai Simulasi');
        $response->assertSee('neu-cta-btn');
        $response->assertSee('/ahp');
        $response->assertSee('hero3DCanvas');
        $response->assertSee('hero3DContainer');
        $response->assertSee('three.min.js');
        $response->assertSee('IcosahedronGeometry');
        $response->assertSee('OctahedronGeometry');
        $response->assertSee('DodecahedronGeometry');
        $response->assertSee('TorusKnotGeometry');

        // 2. Edukasi & Pengenalan AHP (Thomas L. Saaty & 3 Levels Hierarchy)
        $response->assertSee('Thomas L. Saaty');
        $response->assertSee('Tujuan (Goal)');
        $response->assertSee('Kriteria');
        $response->assertSee('Alternatif');
        $response->assertSee('Dekomposisi Hierarki');
        $response->assertSee('Perbandingan Berpasangan');
        $response->assertSee('Uji Konsistensi');
        $response->assertSee('Visualisasi');
        $response->assertSee('glass-card');
        $response->assertSee('reveal-on-scroll');

        // 3. Highlight Rumus Matematika (Terminal Box, Normalisasi, Eigen Vector, λmax, CI, CR & Tabel RI 1..15)
        $response->assertSee('math-terminal-box');
        $response->assertSee('Matriks & Normalisasi Kolom', false);
        $response->assertSee('Bobot Prioritas Rata-Rata');
        $response->assertSee('&lambda;<sub>max</sub> = (1 / n)', false);
        $response->assertSee('Pencarian Lambda Maksimum');
        $response->assertSee('CI = (&lambda;<sub>max</sub> - n) / (n - 1)', false);
        $response->assertSee('CR = CI / RI', false);
        $response->assertSee('Tabel Nilai Random Index (RI) Thomas L. Saaty');
        $response->assertSee('n = 15');
        $response->assertSee('1,59');
        $response->assertSee('1 / 3,833 = 0,261');
        $response->assertSee('0,786 / 3 = 0,262');
        $response->assertSee('CR \le 0,1', false);
        $response->assertSee('Data tidak konsisten, harap ulangi penilaian');

        // 4. Footer & Bottom CTA assertions
        $response->assertSee('Coba Simulasi AHP');

        // 5. Mint Green (#98FBCB), Dark Mode Otomatis OS & Tanpa Switch Button
        $response->assertSee('#98FBCB');
        $response->assertSee('dark:bg-slate-900');
        $response->assertSee('prefers-color-scheme');
        $response->assertDontSee('themeToggleBtn');
    }
}

