<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analisa_ringkasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kabupaten_id')->constrained('kabupaten')->cascadeOnDelete();
            $table->date('tanggal');
            $table->integer('korban_luka')->default(0);
            $table->integer('pasien_rs')->default(0);
            $table->integer('pasien_puskesmas')->default(0);
            $table->integer('total_pasien')->default(0);
            $table->string('pola_gap', 255)->nullable();
            $table->string('status', 255)->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->timestamps();
            $table->unique(['kabupaten_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analisa_ringkasan');
    }
};
