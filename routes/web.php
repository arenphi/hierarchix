<?php

use App\Http\Controllers\AhpController;
use Illuminate\Support\Facades\Route;

// Halaman utama langsung mengarah ke modul SPK AHP
Route::get('/', [AhpController::class, 'index'])->name('home');

// Halaman Story Page / Edukasi AHP (Landing Page)
Route::get('/landing', function () {
    return view('landing');
})->name('landing');

/*
|--------------------------------------------------------------------------
| Web Routes - Modul SPK AHP
|--------------------------------------------------------------------------
|
| Routing untuk antarmuka dan mesin pemrosesan Analytic Hierarchy Process (AHP).
| Menerima input data matriks perbandingan berpasangan (kriteria maupun alternatif),
| melakukan normalisasi, menghitung bobot prioritas (Eigen Vector),
| menguji rasio konsistensi (CR <= 0.1), menyimpan kalkulasi,
| dan menyintesis skor prioritas global alternatif untuk dirender ke grafik 3D.
|
*/
Route::prefix('ahp')->name('ahp.')->group(function () {
    // Halaman antarmuka interaktif SPK AHP
    Route::get('/', [AhpController::class, 'index'])->name('index');

    // Endpoint utama kalkulasi AHP
    Route::post('/calculate', [AhpController::class, 'calculate'])->name('calculate');

    // Endpoint referensi tabel Random Consistency Index (RI) standar Saaty
    Route::get('/random-index', [AhpController::class, 'randomIndexTable'])->name('random-index');

    // Endpoint dataset contoh untuk pengujian antarmuka dan slider
    Route::get('/sample-data', [AhpController::class, 'sampleData'])->name('sample-data');
});
