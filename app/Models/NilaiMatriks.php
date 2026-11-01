<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model NilaiMatriks
 *
 * Menyimpan data nilai perbandingan berpasangan skala Saaty (1-9 atau pecahannya).
 *
 * @property int $id
 * @property string $tipe_matriks
 * @property int $elemen_baris_id
 * @property int $elemen_kolom_id
 * @property float $nilai_skala
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class NilaiMatriks extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'nilai_matriks';

    /**
     * Atribut yang dapat diisi secara massal (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tipe_matriks',
        'elemen_baris_id',
        'elemen_kolom_id',
        'nilai_skala',
    ];

    /**
     * Konversi tipe atribut (casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'elemen_baris_id' => 'integer',
        'elemen_kolom_id' => 'integer',
        'nilai_skala' => 'float',
    ];
}

