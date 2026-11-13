<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('file_path'); // image file, or video thumbnail if type = video
            $table->string('video_url')->nullable(); // set when type = video (e.g. YouTube embed link)
            $table->enum('type', ['photo', 'video'])->default('photo');
            $table->string('category')->nullable(); // e.g. "Praktikum", "Kegiatan Sekolah", "Prestasi"
            $table->date('event_date')->nullable();
            $table->unsignedInteger('order')->default(0); // manual display order, set by admin
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
