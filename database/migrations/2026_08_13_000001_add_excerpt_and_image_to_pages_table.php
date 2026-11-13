<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds the two columns profil.blade.php (and the updated Page model's
     * image_url accessor) rely on but the original create_pages_table
     * migration didn't include yet.
     */
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            // Short quoted text, e.g. the Visi Sekolah quote block.
            $table->string('excerpt')->nullable()->after('content');

            // Relative path on the `public` disk (e.g. "pages/gedung.jpg"),
            // or a full external URL — see Page::getImageUrlAttribute().
            $table->string('image')->nullable()->after('excerpt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['excerpt', 'image']);
        });
    }
};