<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Kriteria
 *
 * Mewakili entitas kriteria dalam hierarki SPK AHP.
 *
 * @property int $id
 * @property string $kode_kriteria
 * @property string $nama_kriteria
 * @property float|null $bobot_prioritas
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Kriteria extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'kriterias';

    /**
     * Atribut yang dapat diisi secara massal (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_kriteria',
        'nama_kriteria',
        'bobot_prioritas',
    ];

    /**
     * Konversi tipe atribut (casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'bobot_prioritas' => 'float',
    ];
}

