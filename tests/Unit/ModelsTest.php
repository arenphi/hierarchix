<?php

namespace Tests\Unit;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\NilaiMatriks;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Mass Assignment pada Model Kriteria.
     */
    public function test_kriteria_model_mass_assignment(): void
    {
        $kriteria = Kriteria::create([
            'kode_kriteria' => 'C1',
            'nama_kriteria' => 'Kualitas Produk',
            'bobot_prioritas' => 0.6334,
        ]);

        $this->assertDatabaseHas('kriterias', [
            'id' => $kriteria->id,
            'kode_kriteria' => 'C1',
            'nama_kriteria' => 'Kualitas Produk',
            'bobot_prioritas' => 0.6334,
        ]);

        $this->assertEquals('C1', $kriteria->kode_kriteria);
        $this->assertIsFloat($kriteria->bobot_prioritas);
    }

    /**
     * Test Mass Assignment pada Model Alternatif.
     */
    public function test_alternatif_model_mass_assignment(): void
    {
        $alternatif = Alternatif::create([
            'kode_alternatif' => 'A1',
            'nama_alternatif' => 'Supplier Alpha',
            'skor_global' => 0.3389,
        ]);

        $this->assertDatabaseHas('alternatifs', [
            'id' => $alternatif->id,
            'kode_alternatif' => 'A1',
            'nama_alternatif' => 'Supplier Alpha',
            'skor_global' => 0.3389,
        ]);

        $this->assertEquals('A1', $alternatif->kode_alternatif);
        $this->assertIsFloat($alternatif->skor_global);
    }

    /**
     * Test Mass Assignment pada Model NilaiMatriks.
     */
    public function test_nilai_matriks_model_mass_assignment(): void
    {
        $nilaiMatriks = NilaiMatriks::create([
            'tipe_matriks' => 'kriteria',
            'elemen_baris_id' => 1,
            'elemen_kolom_id' => 2,
            'nilai_skala' => 3.0,
        ]);

        $this->assertDatabaseHas('nilai_matriks', [
            'id' => $nilaiMatriks->id,
            'tipe_matriks' => 'kriteria',
            'elemen_baris_id' => 1,
            'elemen_kolom_id' => 2,
            'nilai_skala' => 3.0,
        ]);

        $this->assertEquals('kriteria', $nilaiMatriks->tipe_matriks);
        $this->assertIsFloat($nilaiMatriks->nilai_skala);
    }
}

