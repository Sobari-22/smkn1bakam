<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\User;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();

        News::create([
            'user_id' => $admin->id,
            'title' => 'Pendaftaran PPDB SMKN 1 Bakam Tahun Ajaran 2024/2025 Dibuka!',
            'slug' => Str::slug('Pendaftaran PPDB SMKN 1 Bakam Tahun Ajaran 2024/2025 Dibuka!'),
            'content' => 'Diberitahukan kepada seluruh calon siswa/siswi bahwa Penerimaan Peserta Didik Baru (PPDB) telah resmi dibuka. Silakan mendaftar melalui website ini secara online.',
            'category' => 'Pengumuman',
            'published_at' => now(),
        ]);

        News::create([
            'user_id' => $admin->id,
            'title' => 'Siswa TKJ Raih Juara 1 LKS Tingkat Provinsi',
            'slug' => Str::slug('Siswa TKJ Raih Juara 1 LKS Tingkat Provinsi'),
            'content' => 'Selamat kepada siswa konsentrasi TKJ yang telah berhasil meraih juara 1 dalam Lomba Kompetensi Siswa (LKS) bidang IT Network Systems.',
            'category' => 'Prestasi',
            'published_at' => now(),
        ]);
    }
}
