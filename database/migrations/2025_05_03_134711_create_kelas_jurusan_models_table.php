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
        Schema::create('ta_kelas_jurusan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas', 50);
            $table->string('kode_jurusan', 10);
            $table->char('kode_kelas', 3);
            $table->unsignedBigInteger('id_ta');
            $table->timestamps();

            $table->foreign('kode_jurusan')->references('kode_jurusan')->on('jurusan')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_ta')->references('id')->on('tahun_ajaran')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('kode_kelas')->references('kode_kelas')->on('kelas')->onUpdate('cascade')->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ta_kelas_jurusan');
    }
};
