<?php

use App\Http\Controllers\AhpController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AHP API Routes
|--------------------------------------------------------------------------
|
| Route untuk menangani proses perhitungan Analytic Hierarchy Process (AHP).
| Endpoint ini menerima matriks perbandingan berpasangan, melakukan normalisasi,
| menghitung bobot prioritas (Eigen Vector), memeriksa konsistensi (CR <= 0.1),
| dan mengembalikan skor akhir alternatif serta data grafik.
|
*/

Route::prefix('ahp')->name('api.ahp.')->group(function () {
    // Endpoint utama untuk kalkulasi AHP
    Route::post('/calculate', [AhpController::class, 'calculate'])->name('calculate');

    // Endpoint referensi tabel Random Consistency Index (RI)
    Route::get('/random-index', [AhpController::class, 'randomIndexTable'])->name('random-index');

    // Endpoint contoh dataset konsisten dan tidak konsisten untuk testing
    Route::get('/sample-data', [AhpController::class, 'sampleData'])->name('sample-data');
});

