<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Sarana & Prasarana (dikelola superadmin di dashboard).
     * Data awal diisi dari daftar yang sebelumnya tertulis langsung di
     * halaman Profil, jadi setelah migrate halaman publik tidak kosong.
     */
    public function up(): void
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('icon', 16)->nullable();
            $table->string('name');
            $table->string('qty', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        $defaults = [
            ['🖥️', 'Laboratorium Komputer', '2 Lab', '60 unit PC dengan koneksi internet fiber optik dan software terbaru.'],
            ['🔌', 'Lab Jaringan (TKJ)', '1 Lab', 'Dilengkapi perangkat Cisco, switch manageable, dan server rack nyata.'],
            ['📚', 'Perpustakaan Digital', '1 Unit', 'Koleksi 3.000+ buku fisik dan akses ke ribuan e-book online.'],
            ['🔬', 'Lab IPA', '1 Lab', 'Peralatan laboratorium kimia, fisika, dan biologi yang memadai.'],
            ['🔧', 'Bengkel Otomotif', '1 Unit', 'Bengkel praktik TSM & TKR lengkap dengan unit kendaraan latih.'],
            ['⚽', 'Lapangan Olahraga', '2 Lapangan', 'Lapangan futsal & bola voli untuk kegiatan ekstrakurikuler.'],
            ['🍽️', 'Kantin Sekolah', '1 Area', 'Area kantin bersih dan nyaman dengan harga terjangkau bagi siswa.'],
            ['🕌', 'Musholla', '1 Unit', 'Tempat ibadah yang nyaman untuk seluruh warga sekolah.'],
        ];

        $now = now();

        foreach ($defaults as $i => [$icon, $name, $qty, $description]) {
            DB::table('facilities')->insert([
                'icon' => $icon,
                'name' => $name,
                'qty' => $qty,
                'description' => $description,
                'order' => $i + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
