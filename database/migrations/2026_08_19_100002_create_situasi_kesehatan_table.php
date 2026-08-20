<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('situasi_kesehatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kabupaten_id')->constrained('kabupaten')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('waktu', 32)->nullable();
            $table->bigInteger('populasi_terdampak')->default(0);
            $table->integer('meninggal')->default(0);
            $table->integer('luka_berat')->default(0);
            $table->integer('luka_ringan')->default(0);
            $table->integer('pengungsi')->default(0);
            $table->integer('titik_pengungsian')->default(0);
            $table->string('sumber_data', 255)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->unique(['kabupaten_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('situasi_kesehatan');
    }
};
