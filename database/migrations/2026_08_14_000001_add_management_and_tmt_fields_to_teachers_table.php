<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->date('tmt')->nullable()->after('nip'); // Tanggal Mulai Tugas
            $table->boolean('is_management')->default(false)->after('is_active');
            $table->unsignedInteger('order')->default(0)->after('is_management');
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn(['tmt', 'is_management', 'order']);
        });
    }
};
