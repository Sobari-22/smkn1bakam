<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Design's "Guru & Staf" table shows Mata Pelajaran and Status columns
     * not present in the original teachers migration — added here rather
     * than editing that migration directly.
     */
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('subject')->nullable()->after('position'); // Mata Pelajaran
            $table->boolean('is_active')->default(true)->after('photo');
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn(['subject', 'is_active']);
        });
    }
};
