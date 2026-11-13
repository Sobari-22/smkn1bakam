<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('concentrations', function (Blueprint $table) {
            $table->string('image')->nullable()->after('description');
            $table->text('overview')->nullable()->after('image');
            $table->json('advantages')->nullable()->after('overview');
            $table->json('further_studies')->nullable()->after('advantages');
        });
    }

    public function down(): void
    {
        Schema::table('concentrations', function (Blueprint $table) {
            $table->dropColumn(['image', 'overview', 'advantages', 'further_studies']);
        });
    }
};
