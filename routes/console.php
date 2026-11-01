<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('db:ensure-system-tables', function () {
    if (!Schema::hasTable('sessions')) {
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
        $this->info('Tabel sessions berhasil dibuat di MySQL.');
    } else {
        $this->info('Tabel sessions sudah ada.');
    }

    if (!Schema::hasTable('cache')) {
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });
        $this->info('Tabel cache berhasil dibuat di MySQL.');
    }

    if (!Schema::hasTable('cache_locks')) {
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });
        $this->info('Tabel cache_locks berhasil dibuat di MySQL.');
    }
})->purpose('Ensure system tables like sessions and cache exist');

