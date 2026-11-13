<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom `role` sebelumnya ENUM dengan 4 nilai tetap, sehingga role baru
     * (admin_bk, guru) akan ditolak database. Diubah jadi string biasa;
     * daftar role yang valid dikelola di config/roles.php.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 30)->default('humas')->change();
        });
    }

    public function down(): void
    {
        // Sengaja kosong: mengembalikan ke ENUM akan gagal bila sudah ada
        // user dengan role admin_bk / guru.
    }
};
