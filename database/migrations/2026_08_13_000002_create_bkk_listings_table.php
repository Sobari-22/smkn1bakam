<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bkk_listings', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // e.g. "Mekanik Sepeda Motor"
            $table->string('company_name'); // e.g. "Bengkel Honda AHASS Bakam"
            $table->string('location'); // e.g. "Bakam, Bangka Barat"
            $table->enum('type', ['kerja_tetap', 'magang', 'magang_rekrutmen'])->default('kerja_tetap');
            $table->text('description')->nullable();
            $table->text('qualifications')->nullable();
            $table->string('contact_info')->nullable(); // phone / email / external link to apply
            $table->date('posted_at');
            $table->date('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bkk_listings');
    }
};
