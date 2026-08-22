<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add `deleted_at` to 4 tables that are managed via /update UI,
     * so bulk delete becomes a soft-delete (recoverable) instead of
     * permanent hard delete.
     *
     * Affected models (see UpdateController::TABLES):
     *   - AnalisaRingkasan       -> analisa_ringkasan
     *   - SituasiKesehatan       -> situasi_kesehatan
     *   - KondisiPasienRs        -> kondisi_pasien_rs
     *   - KondisiPasienPuskesmas -> kondisi_pasien_puskesmas
     */
    public function up(): void
    {
        $tables = [
            'analisa_ringkasan',
            'situasi_kesehatan',
            'kondisi_pasien_rs',
            'kondisi_pasien_puskesmas',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->softDeletes();
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'analisa_ringkasan',
            'situasi_kesehatan',
            'kondisi_pasien_rs',
            'kondisi_pasien_puskesmas',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropSoftDeletes();
            });
        }
    }
};