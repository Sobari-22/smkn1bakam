<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds fields needed for the Program Keahlian detail modal (kuota,
     * kompetensi keahlian checklist, prospek karir tags) — kept as a
     * separate migration rather than editing the original concentrations
     * migration you already ran.
     */
    public function up(): void
    {
        Schema::table('concentrations', function (Blueprint $table) {
            // 'name' stays the short code (TKJ, TSM, TKR) used in badges;
            // 'title' is the full label shown as heading, e.g. "Teknik Komputer Jaringan".
            $table->string('title')->nullable()->after('name');
            $table->unsignedInteger('quota')->default(0)->after('description');
            $table->json('competencies')->nullable()->after('quota'); // ["Instalasi Jaringan LAN/WAN", ...]
            $table->json('career_prospects')->nullable()->after('competencies'); // ["Network Engineer", ...]
        });
    }

    public function down(): void
    {
        Schema::table('concentrations', function (Blueprint $table) {
            $table->dropColumn(['quota', 'competencies', 'career_prospects']);
        });
    }
};
