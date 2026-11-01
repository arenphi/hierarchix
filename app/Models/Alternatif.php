<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Alternatif
 *
 * Mewakili entitas alternatif dalam hierarki SPK AHP.
 *
 * @property int $id
 * @property string $kode_alternatif
 * @property string $nama_alternatif
 * @property float|null $skor_global
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Alternatif extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'alternatifs';

    /**
     * Atribut yang dapat diisi secara massal (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_alternatif',
        'nama_alternatif',
        'skor_global',
    ];

    /**
     * Konversi tipe atribut (casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'skor_global' => 'float',
    ];
}

