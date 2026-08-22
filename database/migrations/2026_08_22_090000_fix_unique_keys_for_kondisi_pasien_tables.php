<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fix unique-index untuk kondisi_pasien_rs dan kondisi_pasien_puskesmas.
 *
 * Sebelum: unique index ['nama', 'tanggal'] (tanpa kabupaten_id) — melarang
 *   nama fasyankes yang sama di kabupaten berbeda pada tanggal yang sama.
 *   Akibat: POST kedua lintas-kabupaten → SQLSTATE 23000 → HTTP 500.
 *   (reproduksi di dev 2026-08-22 oleh user puskris dengan nama "Kota"
 *    di 2 kabupaten — lihat storage/logs/laravel.log.)
 *
 * Sesudah: unique index ['nama', 'kabupaten_id', 'tanggal']. Composite-key
 *   ini sesuai domain (satu fasyankes per kabupaten per tanggal) dan cocok
 *   dengan storeRs()/storePuskesmas() yang sudah di-patch untuk include
 *   kabupaten_id di key updateOrCreate.
 *
 * Aman untuk data existing: migrasi ini DROP + ADD index saja, tidak
 *   menyentuh data. Kalau ada data bentrok karena rule lama (nama sama
 *   lintas-kabupaten, beda row), index baru akan gagal dibuat — kami
 *   tangani per kasus jika muncul saat migrate.
 */
return new class extends Migration
{
    public function up(): void
    {
        // kondisi_pasien_puskesmas
        Schema::table('kondisi_pasien_puskesmas', function (Blueprint $table) {
            $table->dropUnique('kondisi_pasien_puskesmas_nama_puskesmas_tanggal_unique');
            $table->unique(['nama_puskesmas', 'kabupaten_id', 'tanggal'],
                'kondisi_pasien_puskesmas_nama_pkm_kab_tanggal_unique');
        });

        // kondisi_pasien_rs
        Schema::table('kondisi_pasien_rs', function (Blueprint $table) {
            $table->dropUnique('kondisi_pasien_rs_nama_rs_tanggal_unique');
            $table->unique(['nama_rs', 'kabupaten_id', 'tanggal'],
                'kondisi_pasien_rs_nama_rs_kab_tanggal_unique');
        });
    }

    public function down(): void
    {
        Schema::table('kondisi_pasien_puskesmas', function (Blueprint $table) {
            $table->dropUnique('kondisi_pasien_puskesmas_nama_pkm_kab_tanggal_unique');
            $table->unique(['nama_puskesmas', 'tanggal'],
                'kondisi_pasien_puskesmas_nama_puskesmas_tanggal_unique');
        });

        Schema::table('kondisi_pasien_rs', function (Blueprint $table) {
            $table->dropUnique('kondisi_pasien_rs_nama_rs_kab_tanggal_unique');
            $table->unique(['nama_rs', 'tanggal'],
                'kondisi_pasien_rs_nama_rs_tanggal_unique');
        });
    }
};