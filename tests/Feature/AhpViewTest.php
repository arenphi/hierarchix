<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AhpViewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test halaman utama /ahp berhasil dimuat dengan status 200
     * dan memiliki komponen kunci sesuai PRD.
     */
    public function test_ahp_index_page_loads_successfully(): void
    {
        $response = $this->get('/ahp');

        $response->assertStatus(200);

        // Memastikan elemen kanvas 3D sesuai PRD ada
        $response->assertSee('ahp3DCanvas');

        // Memastikan struktur hierarki (Goal, Kriteria, Alternatif) ada
        $response->assertSee('LEVEL 1: GOAL');
        $response->assertSee('LEVEL 2: KRITERIA');
        $response->assertSee('LEVEL 3: ALTERNATIF');

        // Memastikan slider 9-1-9 kriteria dan slider perbandingan alternatif ada
        $response->assertSee('ahp-slider');
        $response->assertSee('ahp-alt-slider');
        $response->assertSee('min="-8"', false);
        $response->assertSee('max="8"', false);
        $response->assertSee('Perbandingan Berpasangan Antar-Alternatif per Kriteria');
        $response->assertSee('altCriterionPanel_');

        // Memastikan Pengecekan CR Multi-Level ada
        $response->assertSee('Pengecekan CR Multi-Level');
        $response->assertSee('Tingkat 2: Matriks Kriteria');
        $response->assertSee('Tingkat 3: Matriks Alternatif per Kriteria');
        $response->assertSee('altCrSummaryList');

        // Memastikan Transparansi Matriks Normalisasi & Bobot Prioritas (Eigen Vector) ada
        $response->assertSee('Transparansi Matriks Perbandingan &amp; Normalisasi Lengkap', false);
        $response->assertSee('matrixNormTable');
        $response->assertSee('Bobot (w_i)', false);
        $response->assertSee('Eigen Vector');
        $response->assertSee('synthesisMatrixTable');

        // Memastikan skrip Fetch API & pesan validasi inkonsistensi ada
        $response->assertSee('Data tidak konsisten, harap ulangi penilaian');

        // Memastikan pustaka Three.js, OrbitControls, dan skrip ahp-3d.js termuat
        $response->assertSee('three.min.js');
        $response->assertSee('OrbitControls.js');
        $response->assertSee('ahp-3d.js');

        // Memastikan komponen Flat Design & Glassmorphism serta 3D Portal ada
        $response->assertSee('glass-panel');
        $response->assertSee('portal-outer-frame');

        // Memastikan Fitur Baru Template Studi Kasus (Step 1 s.d. Step 6 Sequential Flow)
        $response->assertSee('Step 1 &bull; Template Studi Kasus', false);
        $response->assertSee('Pemilihan Smartphone');
        $response->assertSee('Pemilihan Pemasok (Supplier)');
        $response->assertSee('Penerima Subsidi Listrik');
        $response->assertSee('Vendor Solusi IT');
        $response->assertSee('Siswa Berprestasi');
        $response->assertSee('loadCaseStudy');
        $response->assertSee('caseStudiesData');
        $response->assertSee('CASE_STUDIES');

        // Memastikan skema warna Mint (#98FBCB), Teal (#0F766E), Emerald, dan Complementary Rose/Coral
        $response->assertSee('#98FBCB');
        $response->assertSee('#0F766E');
        $response->assertSee('rose-500');

        // Memastikan warna Primary Mint Green (#98FBCB) & Dark Mode responsif tanpa tombol switch
        $response->assertSee('#98FBCB');
        $response->assertSee('dark:bg-slate-900');
        $response->assertSee('prefers-color-scheme');
        $response->assertDontSee('themeToggleBtn');
    }

    /**
     * Test halaman root / juga mengarah ke view AHP.
     */
    public function test_root_page_loads_ahp(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('SPK Interaktif 3D');
    }
}

