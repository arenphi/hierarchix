<?php

namespace Tests\Feature;

use Tests\TestCase;

class AhpControllerTest extends TestCase
{
    /**
     * Test perhitungan AHP dengan matriks yang konsisten (CR < 0.1).
     * Harus mengembalikan respons JSON 200, status success, bobot prioritas,
     * skor akhir alternatif, dan chart_data.
     */
    public function test_ahp_calculation_succeeds_with_consistent_matrix(): void
    {
        $payload = [
            'criteria' => ['Kualitas', 'Harga', 'Pelayanan'],
            'matrix' => [
                [1.0, 3.0, 5.0],
                [0.3333333333, 1.0, 3.0],
                [0.2, 0.3333333333, 1.0],
            ],
            'alternatives' => [
                [
                    'name' => 'Supplier Alpha',
                    'scores' => [85, 75, 90],
                ],
                [
                    'name' => 'Supplier Beta',
                    'scores' => [90, 65, 80],
                ],
                [
                    'name' => 'Supplier Gamma',
                    'scores' => [75, 90, 85],
                ],
            ],
        ];

        $response = $this->postJson('/ahp/calculate', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'is_consistent' => true,
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'is_consistent',
                'metrics' => [
                    'matrix_size',
                    'lambda_max',
                    'ci',
                    'ri',
                    'cr',
                    'cr_percentage',
                    'threshold',
                ],
                'criteria_weights' => [
                    '*' => ['code', 'name', 'weight', 'percentage'],
                ],
                'priority_vector',
                'alternatives' => [
                    '*' => ['code', 'name', 'final_score', 'percentage', 'rank'],
                ],
                'chart_data' => [
                    'threejs_3d' => [
                        'type',
                        'bars',
                    ],
                    'alternatives' => [
                        'labels',
                        'datasets' => [
                            '*' => ['label', 'data', 'backgroundColor', 'borderColor', 'borderWidth'],
                        ],
                    ],
                    'criteria' => [
                        'labels',
                        'datasets' => [
                            '*' => ['label', 'data', 'backgroundColor', 'borderColor', 'borderWidth'],
                        ],
                    ],
                ],
                'detail' => [
                    'criteria_labels',
                    'pairwise_matrix',
                    'column_sums',
                    'normalized_matrix',
                    'priority_vector',
                ],
            ]);

        $responseData = $response->json();

        // Pastikan nilai CR kurang dari 0.1
        $this->assertLessThan(0.10, $responseData['metrics']['cr']);

        // Pastikan total bobot prioritas mendekati 1.0 (100%)
        $totalWeight = array_sum($responseData['priority_vector']);
        $this->assertEqualsWithDelta(1.0, $totalWeight, 0.01);

        // Pastikan alternatif diurutkan berdasarkan peringkat (rank 1, 2, 3)
        $this->assertEquals(1, $responseData['alternatives'][0]['rank']);
        $this->assertEquals(2, $responseData['alternatives'][1]['rank']);
        $this->assertEquals(3, $responseData['alternatives'][2]['rank']);
    }

    /**
     * Test validasi ketat: Jika Consistency Ratio (CR) > 0.1 (10%),
     * harus mengembalikan HTTP 422, status error, dan pesan
     * 'Data tidak konsisten, harap ulangi penilaian'.
     */
    public function test_ahp_fails_with_inconsistent_matrix(): void
    {
        $payload = [
            'criteria' => ['K1', 'K2', 'K3'],
            'matrix' => [
                [1.0, 9.0, 0.1111],
                [0.1111, 1.0, 9.0],
                [9.0, 0.1111, 1.0],
            ],
        ];

        $response = $this->postJson('/ahp/calculate', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'message' => 'Data tidak konsisten, harap ulangi penilaian',
                'is_consistent' => false,
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'is_consistent',
                'metrics' => [
                    'matrix_size',
                    'lambda_max',
                    'ci',
                    'ri',
                    'cr',
                    'cr_percentage',
                ],
                'detail',
            ]);

        $responseData = $response->json();
        $this->assertGreaterThan(0.10, $responseData['metrics']['cr']);
    }

    /**
     * Test validasi input jika matriks tidak bujur sangkar atau kosong.
     */
    public function test_ahp_rejects_non_square_matrix(): void
    {
        $payload = [
            'matrix' => [
                [1.0, 2.0],
                [0.5, 1.0, 3.0],
            ],
        ];

        $response = $this->postJson('/ahp/calculate', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
            ]);
    }

    /**
     * Test validasi input jika nilai diagonal bukan 1.
     */
    public function test_ahp_rejects_invalid_diagonal(): void
    {
        $payload = [
            'matrix' => [
                [2.0, 3.0],
                [0.3333, 1.0],
            ],
        ];

        $response = $this->postJson('/ahp/calculate', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
            ]);
    }

    /**
     * Test endpoint Random Index table di routes/web.php.
     */
    public function test_random_index_table_endpoint(): void
    {
        $response = $this->getJson('/ahp/random-index');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ])
            ->assertJsonStructure([
                'status',
                'description',
                'random_index',
            ]);
    }

    /**
     * Test endpoint contoh data (sample-data) di routes/web.php.
     */
    public function test_sample_data_endpoint(): void
    {
        $response = $this->getJson('/ahp/sample-data');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'consistent_sample',
                'inconsistent_sample',
            ]);
    }

    /**
     * Test perhitungan AHP Multi-Level dengan matriks perbandingan alternatif
     * berpasangan untuk setiap kriteria (CR <= 0.1 di semua level).
     */
    public function test_ahp_calculation_succeeds_with_multi_level_alternative_pairwise_matrices(): void
    {
        $payload = [
            'criteria' => ['IPK', 'Karya Ilmiah', 'Organisasi'],
            'matrix' => [
                [1.0, 3.0, 5.0],
                [0.3333, 1.0, 3.0],
                [0.2, 0.3333, 1.0],
            ],
            'alternatives' => ['Ahmad', 'Budi', 'Citra'],
            'alternative_matrices' => [
                // Kriteria 1 (IPK): Ahmad > Budi > Citra (Konsisten CR = 0.0)
                [
                    [1.0, 2.0, 4.0],
                    [0.5, 1.0, 2.0],
                    [0.25, 0.5, 1.0],
                ],
                // Kriteria 2 (Karya Ilmiah): Budi > Ahmad > Citra (Konsisten CR <= 0.03)
                [
                    [1.0, 0.3333, 2.0],
                    [3.0, 1.0, 5.0],
                    [0.5, 0.2, 1.0],
                ],
                // Kriteria 3 (Organisasi): Citra > Budi > Ahmad (Konsisten CR = 0.0)
                [
                    [1.0, 0.5, 0.25],
                    [2.0, 1.0, 0.5],
                    [4.0, 2.0, 1.0],
                ],
            ],
        ];

        $response = $this->postJson('/ahp/calculate', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'is_consistent' => true,
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'is_consistent',
                'metrics',
                'criteria_weights',
                'priority_vector',
                'alternatives' => [
                    '*' => ['code', 'name', 'final_score', 'percentage', 'rank'],
                ],
                'detail' => [
                    'alternative_evaluations' => [
                        '*' => [
                            'criterion',
                            'pairwise_matrix',
                            'column_sums',
                            'lambda_max',
                            'ci',
                            'ri',
                            'cr',
                            'priority_vector',
                            'normalized_matrix',
                        ],
                    ],
                ],
            ]);

        $responseData = $response->json();
        $this->assertCount(3, $responseData['detail']['alternative_evaluations']);
        $this->assertEquals(1, $responseData['alternatives'][0]['rank']);
    }

    /**
     * Test validasi kegagalan CR pada tingkat alternatif:
     * Matriks kriteria konsisten, tetapi salah satu matriks alternatif tidak konsisten (CR > 0.1).
     * Sistem harus mengembalikan HTTP 422 dan mengidentifikasi kriteria yang gagal di 'failed_at'.
     */
    public function test_ahp_calculation_fails_when_alternative_matrix_is_inconsistent(): void
    {
        $payload = [
            'criteria' => ['IPK', 'Karya Ilmiah', 'Organisasi'],
            'matrix' => [
                [1.0, 3.0, 5.0],
                [0.3333, 1.0, 3.0],
                [0.2, 0.3333, 1.0],
            ],
            'alternatives' => ['Ahmad', 'Budi', 'Citra'],
            'alternative_matrices' => [
                // Kriteria 1 (IPK): Konsisten
                [
                    [1.0, 2.0, 4.0],
                    [0.5, 1.0, 2.0],
                    [0.25, 0.5, 1.0],
                ],
                // Kriteria 2 (Karya Ilmiah): SANGAT INKONSISTEN (Ahmad > Budi 9x, Budi > Citra 9x, tapi Citra > Ahmad 9x!)
                [
                    [1.0, 9.0, 0.1111],
                    [0.1111, 1.0, 9.0],
                    [9.0, 0.1111, 1.0],
                ],
                // Kriteria 3 (Organisasi): Konsisten
                [
                    [1.0, 0.5, 0.25],
                    [2.0, 1.0, 0.5],
                    [4.0, 2.0, 1.0],
                ],
            ],
        ];

        $response = $this->postJson('/ahp/calculate', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'message' => 'Data tidak konsisten, harap ulangi penilaian',
                'is_consistent' => false,
                'failed_at' => 'Karya Ilmiah',
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'is_consistent',
                'failed_at',
                'metrics' => [
                    'criterion',
                    'cr',
                    'cr_percentage',
                ],
            ]);
    }
}
