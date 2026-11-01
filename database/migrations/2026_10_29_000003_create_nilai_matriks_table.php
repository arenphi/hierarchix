<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nilai_matriks', function (Blueprint $table) {
            $table->id();
            $table->string('tipe_matriks')->comment("'kriteria' atau ID kriteria tertentu untuk matriks alternatif");
            $table->unsignedBigInteger('elemen_baris_id')->comment('ID elemen pada baris');
            $table->unsignedBigInteger('elemen_kolom_id')->comment('ID elemen pada kolom');
            $table->double('nilai_skala')->comment('Nilai perbandingan skala Saaty (1-9 atau pecahannya)');
            $table->timestamps();

            // Index untuk optimasi pencarian matriks perbandingan
            $table->index(['tipe_matriks', 'elemen_baris_id', 'elemen_kolom_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_matriks');
    }
};

