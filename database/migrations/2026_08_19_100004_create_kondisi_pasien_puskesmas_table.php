<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kondisi_pasien_puskesmas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kabupaten_id')->constrained('kabupaten')->cascadeOnDelete();
            $table->string('nama_puskesmas', 255);
            $table->date('tanggal');
            $table->integer('merah')->default(0);
            $table->integer('kuning')->default(0);
            $table->integer('hijau')->default(0);
            $table->integer('hitam')->default(0);
            $table->integer('total_pasien')->default(0);
            $table->text('diagnosis')->nullable();
            $table->string('sumber_data', 255)->nullable();
            $table->timestamps();
            $table->unique(['nama_puskesmas', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kondisi_pasien_puskesmas');
    }
};
