<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracer_responses', function (Blueprint $table) {
            $table->id();

            // Identity / verification key (no login — see planning doc):
            // nisn + tahun_lulus + alumni_nama together identify a unique alumnus.
            $table->string('nisn');
            $table->year('tahun_lulus');
            $table->string('alumni_nama');
            $table->foreignId('program_id')->nullable()->constrained('programs')->nullOnDelete();
            $table->foreignId('concentration_id')->nullable()->constrained('concentrations')->nullOnDelete();
            $table->string('no_hp')->nullable();
            $table->string('domisili')->nullable();

            $table->enum('status_kegiatan', ['bekerja', 'kuliah', 'wiraswasta', 'bekerja_kuliah', 'belum']);

            // Dynamic fields depending on status_kegiatan — kept as nullable
            // columns (not JSON) so aggregate stats can be queried directly with SQL.
            $table->string('nama_instansi')->nullable();
            $table->string('bidang_instansi')->nullable();
            $table->string('posisi_jabatan')->nullable();
            $table->enum('kesesuaian_kerja', ['sangat_sesuai', 'sesuai', 'kurang_sesuai', 'tidak_sesuai'])->nullable();
            $table->enum('lama_tunggu_kerja', ['kurang_1_bulan', '1_3_bulan', '3_6_bulan', 'lebih_6_bulan'])->nullable();
            $table->boolean('via_bkk')->default(false); // placed through a BKK-listed employer

            $table->string('nama_kampus')->nullable();
            $table->string('program_studi')->nullable();
            $table->string('jenjang')->nullable();
            $table->boolean('kuliah_linier')->nullable();

            $table->string('jenis_usaha')->nullable();
            $table->string('bidang_usaha')->nullable();
            $table->string('lama_usaha')->nullable();
            $table->unsignedInteger('jumlah_tenaga_kerja')->nullable();

            $table->string('alasan_belum')->nullable();

            $table->unsignedTinyInteger('evaluasi_kesiapan')->nullable(); // 1-5
            $table->text('saran')->nullable();

            $table->timestamps();

            $table->unique(['nisn', 'tahun_lulus']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracer_responses');
    }
};
