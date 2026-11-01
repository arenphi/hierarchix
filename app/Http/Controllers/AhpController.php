<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

/**
 * AhpController
 *
 * Mengimplementasikan mesin pemrosesan Analytic Hierarchy Process (AHP)
 * berdasarkan metodologi Thomas L. Saaty untuk Sistem Pendukung Keputusan (SPK).
 */
class AhpController extends Controller
{
    /**
     * Tabel Random Consistency Index (RI) standar Saaty (n = 1 s.d. 15).
     */
    protected const RANDOM_INDEX = [
        1  => 0.00,
        2  => 0.00,
        3  => 0.58,
        4  => 0.90,
        5  => 1.12,
        6  => 1.24,
        7  => 1.32,
        8  => 1.41,
        9  => 1.45,
        10 => 1.49,
        11 => 1.51,
        12 => 1.48,
        13 => 1.56,
        14 => 1.57,
        15 => 1.59,
    ];

    /**
     * Batas ambang rasio konsistensi (CR).
     * Maksimal 10% (0.10) agar matriks perbandingan dianggap valid dan konsisten.
     */
    protected const CR_THRESHOLD = 0.10;

    /**
     * Menampilkan antarmuka utama SPK AHP Interaktif.
     *
     * @return View
     */
    public function index(): View
    {
        $defaultCriteria = [
            ['kode_kriteria' => 'C1', 'nama_kriteria' => 'Prestasi Akademik (IPK)'],
            ['kode_kriteria' => 'C2', 'nama_kriteria' => 'Karya Ilmiah & Inovasi'],
            ['kode_kriteria' => 'C3', 'nama_kriteria' => 'Aktivitas Organisasi & Kepemimpinan'],
            ['kode_kriteria' => 'C4', 'nama_kriteria' => 'Keahlian Bahasa & Komunikasi'],
        ];

        $defaultAlternatif = [
            ['kode_alternatif' => 'A1', 'nama_alternatif' => 'Ahmad Fauzi'],
            ['kode_alternatif' => 'A2', 'nama_alternatif' => 'Budi Santoso'],
            ['kode_alternatif' => 'A3', 'nama_alternatif' => 'Citra Dewi'],
        ];

        if (Schema::hasTable('kriterias')) {
            $kriterias = Kriteria::all();
            if ($kriterias->isEmpty()) {
                foreach ($defaultCriteria as $c) {
                    Kriteria::create($c);
                }
                $kriterias = Kriteria::all();
            }
        } else {
            $kriterias = collect(array_map(function ($item, $idx) {
                return (object) array_merge($item, ['id' => $idx + 1]);
            }, $defaultCriteria, array_keys($defaultCriteria)));
        }

        if (Schema::hasTable('alternatifs')) {
            $alternatifs = Alternatif::all();
            if ($alternatifs->isEmpty()) {
                foreach ($defaultAlternatif as $a) {
                    Alternatif::create($a);
                }
                $alternatifs = Alternatif::all();
            }
        } else {
            $alternatifs = collect(array_map(function ($item, $idx) {
                return (object) array_merge($item, ['id' => $idx + 1]);
            }, $defaultAlternatif, array_keys($defaultAlternatif)));
        }

        $criteriaLabels = $kriterias->pluck('nama_kriteria')->toArray();
        $criteriaCodes = $kriterias->pluck('kode_kriteria')->toArray();
        $alternativesNames = $alternatifs->pluck('nama_alternatif')->toArray();
        $alternativesCodes = $alternatifs->pluck('kode_alternatif')->toArray();
        $alternativesData = $alternatifs->map(function ($a) use ($kriterias) {
            return [
                'name' => $a->nama_alternatif,
                'scores' => array_fill(0, $kriterias->count(), 80),
            ];
        })->values()->toArray();

        return view('ahp.index', compact(
            'kriterias',
            'alternatifs',
            'criteriaLabels',
            'criteriaCodes',
            'alternativesNames',
            'alternativesCodes',
            'alternativesData'
        ));
    }

    /**
     * Menangani kalkulasi AHP secara menyeluruh:
     * 1. Menerima input matriks perbandingan berpasangan (kriteria maupun alternatif).
     * 2. Menormalisasi matriks (membagi tiap sel dengan jumlah total kolom).
     * 3. Menghitung bobot prioritas (Eigen Vector) dari rata-rata baris normalisasi.
     * 4. Menghitung Lambda Max (λmax), Consistency Index (CI), dan Consistency Ratio (CR).
     * 5. Validasi ketat jika CR > 0.1 -> Tolak dengan HTTP 422.
     * 6. Jika valid (CR <= 0.1) -> Simpan kalkulasi, lakukan sintesis prioritas global alternatif,
     *    dan kembalikan JSON lengkap untuk visualisasi 3D.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function calculate(Request $request): JsonResponse
    {
        // 1. Validasi input request dasar
        $validator = Validator::make($request->all(), [
            'matrix' => ['required', 'array', 'min:1'],
            'matrix.*' => ['required', 'array'],
            'criteria' => ['nullable', 'array'],
            'criteria.*' => ['string'],
            'alternatives' => ['nullable', 'array'],
            'alternative_matrices' => ['nullable', 'array'],
            'alternative_scores' => ['nullable', 'array'],
        ], [
            'matrix.required' => 'Matriks perbandingan berpasangan wajib dikirim.',
            'matrix.array' => 'Format matriks harus berupa array 2 dimensi.',
            'matrix.min' => 'Ukuran matriks minimal 1x1.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi format input gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $rawMatrix = $request->input('matrix');
        $n = count($rawMatrix);

        // Validasi matriks bujur sangkar (n x n) & nilai numerik positif
        $matrixError = $this->validateSquareMatrix($rawMatrix, $n);
        if ($matrixError !== null) {
            return response()->json([
                'status' => 'error',
                'message' => $matrixError,
            ], 422);
        }

        // Konversi ke tipe data float murni
        $matrix = array_map(function ($row) {
            return array_map(fn($v) => (float) $v, $row);
        }, $rawMatrix);

        // Siapkan label kriteria (default K1, K2, ... Kn jika tidak disertakan)
        $criteriaLabels = $request->input('criteria');
        if (!is_array($criteriaLabels) || count($criteriaLabels) !== $n) {
            $criteriaLabels = [];
            for ($i = 0; $i < $n; $i++) {
                $criteriaLabels[] = 'K' . ($i + 1);
            }
        }

        // 2. Evaluasi Matriks Kriteria (Normalisasi, Eigen Vector, λmax, CI, RI, CR)
        $criteriaEval = $this->evaluatePairwiseMatrix($matrix, $n);

        // 3. Validasi Ketat Rasio Konsistensi (CR > 0.1 atau 10%)
        if ($criteriaEval['cr'] > self::CR_THRESHOLD) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak konsisten, harap ulangi penilaian',
                'is_consistent' => false,
                'metrics' => [
                    'matrix_size' => $n,
                    'lambda_max' => round($criteriaEval['lambda_max'], 4),
                    'ci' => round($criteriaEval['ci'], 4),
                    'ri' => $criteriaEval['ri'],
                    'cr' => round($criteriaEval['cr'], 4),
                    'cr_percentage' => round($criteriaEval['cr'] * 100, 2) . '%',
                    'threshold' => '10% (0.1)',
                ],
                'detail' => [
                    'criteria_labels' => $criteriaLabels,
                    'pairwise_matrix' => $this->roundMatrix($matrix, 4),
                    'column_sums' => array_map(fn($v) => round($v, 4), $criteriaEval['column_sums']),
                    'normalized_matrix' => $this->roundMatrix($criteriaEval['normalized_matrix'], 4),
                    'priority_vector' => array_map(fn($v) => round($v, 4), $criteriaEval['priority_vector']),
                ],
            ], 422);
        }

        // 4. Matriks Kriteria Konsisten (CR <= 0.1) -> Bentuk Bobot Prioritas Kriteria
        $priorityVector = $criteriaEval['priority_vector'];
        $criteriaWeights = [];
        foreach ($criteriaLabels as $index => $label) {
            $criteriaWeights[] = [
                'code' => 'C' . ($index + 1),
                'name' => $label,
                'weight' => round($priorityVector[$index], 4),
                'percentage' => round($priorityVector[$index] * 100, 2) . '%',
            ];
        }

        // 5. Sintesis Skor Prioritas Global Alternatif
        $altSynthesisResult = $this->synthesizeAlternatives(
            $request,
            $priorityVector,
            $criteriaLabels,
            $n
        );

        // Jika terdapat inkonsistensi pada salah satu matriks perbandingan alternatif
        if (isset($altSynthesisResult['inconsistent']) && $altSynthesisResult['inconsistent'] === true) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak konsisten, harap ulangi penilaian',
                'is_consistent' => false,
                'failed_at' => $altSynthesisResult['failed_criterion'] ?? 'Alternatif',
                'metrics' => $altSynthesisResult['metrics'] ?? null,
            ], 422);
        }

        $rankedAlternatives = $altSynthesisResult['ranking'];

        // 6. Simpan hasil perhitungan (ke Database jika tabel tersedia, atau ke Cache/Session)
        $this->saveCalculationRecord([
            'criteria_matrix' => $matrix,
            'criteria_labels' => $criteriaLabels,
            'priority_vector' => $priorityVector,
            'metrics' => [
                'lambda_max' => $criteriaEval['lambda_max'],
                'ci' => $criteriaEval['ci'],
                'ri' => $criteriaEval['ri'],
                'cr' => $criteriaEval['cr'],
            ],
            'ranked_alternatives' => $rankedAlternatives,
        ]);

        // 7. Siapkan struktur data grafik 3D WebGL (Three.js) & 2D
        $chartData = $this->buildVisualChartData($criteriaWeights, $rankedAlternatives);

        // 8. Kembalikan respons JSON sukses lengkap
        return response()->json([
            'status' => 'success',
            'message' => 'Perhitungan AHP berhasil dan matriks perbandingan konsisten.',
            'is_consistent' => true,
            'metrics' => [
                'matrix_size' => $n,
                'lambda_max' => round($criteriaEval['lambda_max'], 4),
                'ci' => round($criteriaEval['ci'], 4),
                'ri' => $criteriaEval['ri'],
                'cr' => round($criteriaEval['cr'], 4),
                'cr_percentage' => round($criteriaEval['cr'] * 100, 2) . '%',
                'threshold' => '10% (0.1)',
            ],
            'criteria_weights' => $criteriaWeights,
            'priority_vector' => array_map(fn($v) => round($v, 4), $priorityVector),
            'alternatives' => $rankedAlternatives,
            'chart_data' => $chartData,
            'detail' => [
                'criteria_labels' => $criteriaLabels,
                'pairwise_matrix' => $this->roundMatrix($matrix, 4),
                'column_sums' => array_map(fn($v) => round($v, 4), $criteriaEval['column_sums']),
                'normalized_matrix' => $this->roundMatrix($criteriaEval['normalized_matrix'], 4),
                'priority_vector' => array_map(fn($v) => round($v, 4), $priorityVector),
                'synthesis_matrix' => $altSynthesisResult['synthesis_matrix'] ?? null,
                'alternative_evaluations' => $altSynthesisResult['alternative_evaluations'] ?? null,
            ],
        ], 200);
    }

    /**
     * Evaluasi satu matriks perbandingan berpasangan ukuran n x n:
     * - Jumlah kolom
     * - Normalisasi
     * - Eigen Vector (bobot prioritas)
     * - Lambda Max (λmax)
     * - Consistency Index (CI)
     * - Random Index (RI)
     * - Consistency Ratio (CR)
     *
     * @param array $matrix
     * @param int $n
     * @return array
     */
    public function evaluatePairwiseMatrix(array $matrix, int $n): array
    {
        // Langkah 1: Total kolom
        $columnSums = $this->calculateColumnSums($matrix, $n);

        // Langkah 2: Normalisasi
        $normalizedMatrix = $this->normalizeMatrix($matrix, $columnSums, $n);

        // Langkah 3: Bobot prioritas (Eigen Vector)
        $priorityVector = $this->calculatePriorityVector($normalizedMatrix, $n);

        // Langkah 4: Lambda Max (λmax)
        $lambdaMax = $this->calculateLambdaMax($matrix, $priorityVector, $n);

        // Langkah 5: Consistency Index (CI)
        $ci = $this->calculateConsistencyIndex($lambdaMax, $n);

        // Langkah 6: Random Index (RI) & Consistency Ratio (CR)
        $ri = $this->getRandomIndex($n);
        $cr = $this->calculateConsistencyRatio($ci, $ri, $n);

        return [
            'column_sums' => $columnSums,
            'normalized_matrix' => $normalizedMatrix,
            'priority_vector' => $priorityVector,
            'lambda_max' => $lambdaMax,
            'ci' => $ci,
            'ri' => $ri,
            'cr' => $cr,
        ];
    }

    /**
     * Menghitung total jumlah nilai pada setiap kolom matriks.
     * S_j = sum_{i=0}^{n-1} a_{ij}
     *
     * @param array $matrix
     * @param int $n
     * @return array
     */
    public function calculateColumnSums(array $matrix, int $n): array
    {
        $columnSums = array_fill(0, $n, 0.0);

        for ($j = 0; $j < $n; $j++) {
            for ($i = 0; $i < $n; $i++) {
                $columnSums[$j] += $matrix[$i][$j];
            }
        }

        return $columnSums;
    }

    /**
     * Normalisasi matriks dengan membagi setiap sel dengan total jumlah pada kolom yang bersesuaian.
     * r_{ij} = a_{ij} / S_j
     *
     * @param array $matrix
     * @param array $columnSums
     * @param int $n
     * @return array
     */
    public function normalizeMatrix(array $matrix, array $columnSums, int $n): array
    {
        $normalizedMatrix = [];

        for ($i = 0; $i < $n; $i++) {
            $normalizedMatrix[$i] = [];
            for ($j = 0; $j < $n; $j++) {
                $colSum = $columnSums[$j] > 0 ? $columnSums[$j] : 1.0;
                $normalizedMatrix[$i][$j] = $matrix[$i][$j] / $colSum;
            }
        }

        return $normalizedMatrix;
    }

    /**
     * Menghitung bobot prioritas (Eigen Vector) dengan mencari nilai rata-rata
     * dari hasil penjumlahan baris normalisasi.
     * w_i = (1 / n) * sum_{j=0}^{n-1} r_{ij}
     *
     * @param array $normalizedMatrix
     * @param int $n
     * @return array
     */
    public function calculatePriorityVector(array $normalizedMatrix, int $n): array
    {
        $priorityVector = [];

        for ($i = 0; $i < $n; $i++) {
            $rowSum = array_sum($normalizedMatrix[$i]);
            $priorityVector[$i] = $rowSum / $n;
        }

        return $priorityVector;
    }

    /**
     * Menghitung nilai Lambda Maksimum (λmax) berdasarkan rumus perkalian matriks
     * awal dengan Eigen Vector (A * W = X), lalu menghitung rata-rata rasio (X_i / W_i).
     *
     * @param array $matrix
     * @param array $priorityVector
     * @param int $n
     * @return float
     */
    public function calculateLambdaMax(array $matrix, array $priorityVector, int $n): float
    {
        if ($n === 1) {
            return 1.0;
        }

        $lambdaRatios = [];

        for ($i = 0; $i < $n; $i++) {
            $rowWeightedSum = 0.0;
            for ($j = 0; $j < $n; $j++) {
                $rowWeightedSum += $matrix[$i][$j] * $priorityVector[$j];
            }

            $w_i = $priorityVector[$i];
            if ($w_i > 0) {
                $lambdaRatios[] = $rowWeightedSum / $w_i;
            } else {
                $lambdaRatios[] = 0.0;
            }
        }

        return array_sum($lambdaRatios) / count($lambdaRatios);
    }

    /**
     * Menghitung nilai Consistency Index (CI).
     * Rumus: CI = (λmax - n) / (n - 1)
     *
     * @param float $lambdaMax
     * @param int $n
     * @return float
     */
    public function calculateConsistencyIndex(float $lambdaMax, int $n): float
    {
        if ($n <= 2) {
            return 0.0;
        }

        $ci = ($lambdaMax - $n) / ($n - 1);

        // Tangani presisi floating point agar tidak bernilai negatif semu
        return max(0.0, $ci);
    }

    /**
     * Mengambil nilai Random Index (RI) berdasarkan patokan tabel Thomas L. Saaty.
     *
     * @param int $n
     * @return float
     */
    public function getRandomIndex(int $n): float
    {
        if (isset(self::RANDOM_INDEX[$n])) {
            return self::RANDOM_INDEX[$n];
        }

        return 1.59;
    }

    /**
     * Menghitung nilai Consistency Ratio (CR).
     * Rumus: CR = CI / RI
     *
     * @param float $ci
     * @param float $ri
     * @param int $n
     * @return float
     */
    public function calculateConsistencyRatio(float $ci, float $ri, int $n): float
    {
        if ($n <= 2 || $ri <= 0.0) {
            return 0.0;
        }

        return $ci / $ri;
    }

    /**
     * Menyintesis bobot kriteria dan bobot alternatif untuk menghasilkan
     * skor prioritas global bagi setiap alternatif.
     *
     * @param Request $request
     * @param array $priorityVector
     * @param array $criteriaLabels
     * @param int $n
     * @return array
     */
    protected function synthesizeAlternatives(
        Request $request,
        array $priorityVector,
        array $criteriaLabels,
        int $n
    ): array {
        $rawAlternatives = $request->input('alternatives');
        $altPairwiseMatrices = $request->input('alternative_matrices');
        $altScoresMatrix = $request->input('alternative_scores');

        // Skenario 1: Matriks perbandingan berpasangan alternatif untuk setiap kriteria (AHP Hierarki Penuh)
        if (is_array($altPairwiseMatrices) && count($altPairwiseMatrices) === $n) {
            return $this->synthesizeFromPairwiseMatrices(
                $altPairwiseMatrices,
                $rawAlternatives,
                $priorityVector,
                $criteriaLabels,
                $n
            );
        }

        // Skenario 2: Alternatif dengan nilai matriks performa kriteria
        $parsedAlternatives = [];
        if (is_array($rawAlternatives) && !empty($rawAlternatives)) {
            if (isset($rawAlternatives[0]) && is_array($rawAlternatives[0]) && isset($rawAlternatives[0]['scores'])) {
                foreach ($rawAlternatives as $idx => $item) {
                    $name = $item['name'] ?? ('Alternatif ' . ($idx + 1));
                    $scores = array_map(fn($v) => (float)$v, (array)($item['scores'] ?? []));
                    if (count($scores) === $n) {
                        $parsedAlternatives[] = ['name' => $name, 'scores' => $scores];
                    }
                }
            } elseif (is_array($altScoresMatrix) && count($altScoresMatrix) === count($rawAlternatives)) {
                foreach ($rawAlternatives as $idx => $name) {
                    $scores = array_map(fn($v) => (float)$v, (array)($altScoresMatrix[$idx] ?? []));
                    if (count($scores) === $n) {
                        $parsedAlternatives[] = [
                            'name' => is_string($name) ? $name : ('Alternatif ' . ($idx + 1)),
                            'scores' => $scores,
                        ];
                    }
                }
            }
        }

        if (!empty($parsedAlternatives)) {
            $m = count($parsedAlternatives);
            $criterionSums = array_fill(0, $n, 0.0);
            for ($j = 0; $j < $n; $j++) {
                for ($i = 0; $i < $m; $i++) {
                    $criterionSums[$j] += $parsedAlternatives[$i]['scores'][$j];
                }
            }

            $results = [];
            $synthesisMatrix = [];

            for ($i = 0; $i < $m; $i++) {
                $finalScore = 0.0;
                $normalizedScores = [];

                for ($j = 0; $j < $n; $j++) {
                    $colSum = $criterionSums[$j] > 0 ? $criterionSums[$j] : 1.0;
                    $normalizedScore = $parsedAlternatives[$i]['scores'][$j] / $colSum;
                    $normalizedScores[] = round($normalizedScore, 4);

                    $finalScore += $priorityVector[$j] * $normalizedScore;
                }

                $results[] = [
                    'code' => 'A' . ($i + 1),
                    'name' => $parsedAlternatives[$i]['name'],
                    'final_score' => round($finalScore, 4),
                    'percentage' => round($finalScore * 100, 2) . '%',
                    'raw_scores' => $parsedAlternatives[$i]['scores'],
                    'normalized_scores' => $normalizedScores,
                ];

                $synthesisMatrix[] = [
                    'alternative' => $parsedAlternatives[$i]['name'],
                    'weights_product' => $normalizedScores,
                    'final_score' => round($finalScore, 4),
                ];
            }

            usort($results, fn($a, $b) => $b['final_score'] <=> $a['final_score']);
            foreach ($results as $rank => &$item) {
                $item['rank'] = $rank + 1;
            }
            unset($item);

            return [
                'ranking' => $results,
                'synthesis_matrix' => $synthesisMatrix,
            ];
        }

        // Skenario 3: Jika alternatif tidak dikirim secara terpisah,
        // gunakan item evaluasi kriteria sebagai representasi alternatif
        $fallbackRanking = [];
        foreach ($criteriaLabels as $i => $label) {
            $fallbackRanking[] = [
                'code' => 'A' . ($i + 1),
                'name' => 'Kandidat Alternatif ' . ($i + 1) . ' (' . $label . ')',
                'final_score' => round($priorityVector[$i], 4),
                'percentage' => round($priorityVector[$i] * 100, 2) . '%',
                'rank' => 0,
            ];
        }

        usort($fallbackRanking, fn($a, $b) => $b['final_score'] <=> $a['final_score']);
        foreach ($fallbackRanking as $rank => &$item) {
            $item['rank'] = $rank + 1;
        }
        unset($item);

        return [
            'ranking' => $fallbackRanking,
            'synthesis_matrix' => null,
        ];
    }

    /**
     * Sintesis matriks perbandingan berpasangan alternatif untuk tiap kriteria.
     * Menguji konsistensi setiap matriks kriteria untuk alternatif.
     *
     * @param array $altPairwiseMatrices
     * @param array|null $altLabels
     * @param array $priorityVector
     * @param array $criteriaLabels
     * @param int $numCriteria
     * @return array
     */
    protected function synthesizeFromPairwiseMatrices(
        array $altPairwiseMatrices,
        ?array $altLabels,
        array $priorityVector,
        array $criteriaLabels,
        int $numCriteria
    ): array {
        $m = count($altPairwiseMatrices[0]);
        $altWeightsPerCriterion = [];
        $altEvaluations = [];

        // Evaluasi konsistensi untuk tiap matriks perbandingan alternatif pada kriteria c
        for ($c = 0; $c < $numCriteria; $c++) {
            $matrixC = $altPairwiseMatrices[$c];
            $err = $this->validateSquareMatrix($matrixC, $m);
            if ($err !== null) {
                return [
                    'inconsistent' => true,
                    'failed_criterion' => $criteriaLabels[$c] ?? "Kriteria " . ($c + 1),
                    'message' => $err,
                ];
            }

            $matrixFloat = array_map(fn($row) => array_map(fn($v) => (float)$v, $row), $matrixC);
            $eval = $this->evaluatePairwiseMatrix($matrixFloat, $m);

            if ($eval['cr'] > self::CR_THRESHOLD) {
                return [
                    'inconsistent' => true,
                    'failed_criterion' => $criteriaLabels[$c] ?? "Kriteria " . ($c + 1),
                    'metrics' => [
                        'criterion' => $criteriaLabels[$c] ?? "Kriteria " . ($c + 1),
                        'cr' => round($eval['cr'], 4),
                        'cr_percentage' => round($eval['cr'] * 100, 2) . '%',
                    ],
                ];
            }

            $altWeightsPerCriterion[$c] = $eval['priority_vector'];
            $altEvaluations[$c] = [
                'criterion' => $criteriaLabels[$c] ?? "Kriteria " . ($c + 1),
                'pairwise_matrix' => $this->roundMatrix($matrixFloat, 4),
                'column_sums' => array_map(fn($v) => round($v, 4), $eval['column_sums']),
                'lambda_max' => round($eval['lambda_max'], 4),
                'ci' => round($eval['ci'], 4),
                'ri' => $eval['ri'],
                'cr' => round($eval['cr'], 4),
                'cr_percentage' => round($eval['cr'] * 100, 2) . '%',
                'priority_vector' => array_map(fn($v) => round($v, 4), $eval['priority_vector']),
                'normalized_matrix' => $this->roundMatrix($eval['normalized_matrix'], 4),
            ];
        }

        // Sintesis prioritas global: S_i = sum_{c=0}^{n-1} w_c * altWeight_{c,i}
        $results = [];
        for ($i = 0; $i < $m; $i++) {
            $globalScore = 0.0;
            $altName = is_array($altLabels) && isset($altLabels[$i]) && is_string($altLabels[$i])
                ? $altLabels[$i]
                : 'Alternatif ' . ($i + 1);

            for ($c = 0; $c < $numCriteria; $c++) {
                $globalScore += $priorityVector[$c] * $altWeightsPerCriterion[$c][$i];
            }

            $results[] = [
                'code' => 'A' . ($i + 1),
                'name' => $altName,
                'final_score' => round($globalScore, 4),
                'percentage' => round($globalScore * 100, 2) . '%',
            ];
        }

        usort($results, fn($a, $b) => $b['final_score'] <=> $a['final_score']);
        foreach ($results as $rank => &$item) {
            $item['rank'] = $rank + 1;
        }
        unset($item);

        return [
            'ranking' => $results,
            'synthesis_matrix' => $altWeightsPerCriterion,
            'alternative_evaluations' => $altEvaluations,
        ];
    }

    /**
     * Membentuk struktur data visualisasi grafik 3D (Three.js) dan 2D.
     * Menyertakan koordinat dimensi 3D (x, y, z height) untuk mempermudah rendering WebGL.
     *
     * @param array $criteriaWeights
     * @param array $rankedAlternatives
     * @return array
     */
    protected function buildVisualChartData(array $criteriaWeights, array $rankedAlternatives): array
    {
        $hexColors = [
            '#6366f1', '#06b6d4', '#10b981', '#f59e0b', '#ef4444',
            '#8b5cf6', '#ec4899', '#3b82f6', '#14b8a6', '#84cc16'
        ];

        // Struktur data 3D Bar Chart untuk Three.js
        $threeJsBars = [];
        $spacing = 2.0; // Jarak antar batang 3D pada sumbu X
        $maxBarHeight = 10.0; // Tinggi maksimal batang 3D

        $altLabels = [];
        $altScores = [];
        $altColors = [];

        foreach ($rankedAlternatives as $idx => $alt) {
            $color = $hexColors[$idx % count($hexColors)];
            $score = (float)$alt['final_score'];
            $height3D = max(0.5, round($score * $maxBarHeight, 2));

            $threeJsBars[] = [
                'rank' => $alt['rank'],
                'name' => $alt['name'],
                'score' => $score,
                'position' => [
                    'x' => ($idx - (count($rankedAlternatives) - 1) / 2) * $spacing,
                    'y' => $height3D / 2, // Pusat batang berada di setengah ketinggian
                    'z' => 0.0,
                ],
                'dimensions' => [
                    'width' => 1.2,
                    'height' => $height3D,
                    'depth' => 1.2,
                ],
                'color' => $color,
            ];

            $altLabels[] = $alt['name'];
            $altScores[] = $score;
            $altColors[] = $color;
        }

        // Data grafik kriteria
        $critLabels = [];
        $critWeights = [];
        $critColors = [];
        foreach ($criteriaWeights as $idx => $crit) {
            $critLabels[] = $crit['name'];
            $critWeights[] = (float)$crit['weight'];
            $critColors[] = $hexColors[$idx % count($hexColors)];
        }

        return [
            // Payload spesifik untuk Three.js WebGL 3D Canvas
            'threejs_3d' => [
                'type' => '3d_bar_chart',
                'bars' => $threeJsBars,
            ],
            // Payload standar 2D untuk Chart.js / ApexCharts
            'alternatives' => [
                'labels' => $altLabels,
                'datasets' => [
                    [
                        'label' => 'Skor Prioritas Global Alternatif',
                        'data' => $altScores,
                        'backgroundColor' => $altColors,
                        'borderColor' => $altColors,
                        'borderWidth' => 1,
                    ],
                ],
            ],
            'criteria' => [
                'labels' => $critLabels,
                'datasets' => [
                    [
                        'label' => 'Bobot Prioritas Kriteria (Eigen Vector)',
                        'data' => $critWeights,
                        'backgroundColor' => $critColors,
                        'borderColor' => $critColors,
                        'borderWidth' => 1,
                    ],
                ],
            ],
        ];
    }

    /**
     * Menyimpan rekam hasil kalkulasi ke database (jika tabel sudah dimigrasi)
     * atau ke Cache/Session sistem untuk rekam jejak.
     *
     * @param array $data
     * @return void
     */
    protected function saveCalculationRecord(array $data): void
    {
        try {
            // Simpan ke Cache sistem agar riwayat perbandingan terakhir selalu tersedia instan
            Cache::put('last_ahp_calculation', $data, now()->addHours(6));

            // Jika tabel database nilai_matriks sudah dibuat pada Tahap 2, simpan secara persisten
            if (Schema::hasTable('nilai_matriks')) {
                DB::table('nilai_matriks')->insert([
                    'data_payload' => json_encode($data),
                    'cr_value' => $data['metrics']['cr'] ?? 0.0,
                    'is_consistent' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            // Logging kegagalan tanpa menghentikan aliran response
            logger()->warning('Gagal menyimpan rekam kalkulasi AHP: ' . $e->getMessage());
        }
    }

    /**
     * Validasi apakah matriks berukuran n x n dan bernilai angka positif dengan diagonal 1.
     *
     * @param array $matrix
     * @param int $n
     * @return string|null
     */
    protected function validateSquareMatrix(array $matrix, int $n): ?string
    {
        if ($n < 1) {
            return 'Ukuran matriks tidak boleh kosong.';
        }

        for ($i = 0; $i < $n; $i++) {
            if (!isset($matrix[$i]) || !is_array($matrix[$i])) {
                return "Baris ke-{$i} pada matriks tidak valid.";
            }

            if (count($matrix[$i]) !== $n) {
                return "Matriks harus berupa bujur sangkar ({$n}x{$n}). Baris ke-" . ($i + 1) . " memiliki " . count($matrix[$i]) . " kolom.";
            }

            for ($j = 0; $j < $n; $j++) {
                $val = $matrix[$i][$j];
                if (!is_numeric($val) || (float)$val <= 0) {
                    return "Nilai matriks baris ke-" . ($i + 1) . " kolom ke-" . ($j + 1) . " harus berupa angka positif lebih dari 0.";
                }

                // Elemen diagonal utama harus bernilai 1
                if ($i === $j && abs((float)$val - 1.0) > 0.0001) {
                    return "Perbandingan elemen terhadap dirinya sendiri (diagonal baris ke-" . ($i + 1) . ") harus bernilai 1.";
                }
            }
        }

        return null;
    }

    /**
     * Endpoint untuk mendapatkan tabel Random Consistency Index (RI) standar Saaty.
     *
     * @return JsonResponse
     */
    public function randomIndexTable(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'description' => 'Tabel Nilai Random Consistency Index (RI) Thomas L. Saaty',
            'random_index' => self::RANDOM_INDEX,
        ], 200);
    }

    /**
     * Endpoint untuk memuat dataset contoh (konsisten dan tidak konsisten).
     *
     * @return JsonResponse
     */
    public function sampleData(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Contoh dataset AHP untuk pengujian formulir slider dan WebGL 3D.',
            'consistent_sample' => [
                'criteria' => ['Kualitas', 'Harga', 'Pelayanan'],
                'matrix' => [
                    [1.0, 3.0, 5.0],
                    [0.3333, 1.0, 3.0],
                    [0.2, 0.3333, 1.0],
                ],
                'alternatives' => [
                    ['name' => 'Kandidat Mahasiswa Alpha', 'scores' => [85, 75, 90]],
                    ['name' => 'Kandidat Mahasiswa Beta', 'scores' => [90, 65, 80]],
                    ['name' => 'Kandidat Mahasiswa Gamma', 'scores' => [75, 90, 85]],
                ],
            ],
            'inconsistent_sample' => [
                'criteria' => ['K1', 'K2', 'K3'],
                'matrix' => [
                    [1.0, 9.0, 0.1111],
                    [0.1111, 1.0, 9.0],
                    [9.0, 0.1111, 1.0],
                ],
            ],
        ], 200);
    }

    /**
     * Pembulatan rekursif seluruh elemen matriks 2D ke presisi desimal tertentu.
     *
     * @param array $matrix
     * @param int $precision
     * @return array
     */
    protected function roundMatrix(array $matrix, int $precision = 4): array
    {
        return array_map(function ($row) use ($precision) {
            return array_map(fn($val) => round($val, $precision), $row);
        }, $matrix);
    }
}
