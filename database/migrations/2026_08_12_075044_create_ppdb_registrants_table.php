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
        Schema::create('ppdb_registrants', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique(); // Nomor registrasi otomatis
            $table->string('full_name');
            $table->enum('gender', ['L', 'P']);
            $table->string('nik');
            $table->string('nisn');
            $table->string('no_kk');
            $table->text('address'); // Domisili
            $table->foreignId('program_id')->constrained();
            $table->foreignId('concentration_id')->nullable()->constrained();
            $table->boolean('has_kip')->default(false);
            $table->boolean('has_kis')->default(false);
            $table->boolean('has_pkh')->default(false);
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_registrants');
    }
};
