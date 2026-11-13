<?php

namespace Database\Seeders;

use App\Models\Gallery;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['title' => 'Praktik Instalasi Jaringan Siswa TKJ', 'category' => 'Praktikum', 'event_date' => '2026-08-05', 'file_path' => 'galeri/praktik-tkj.jpg'],
            ['title' => 'Workshop Tune-Up Sepeda Motor', 'category' => 'Kegiatan', 'event_date' => '2026-07-10', 'file_path' => 'galeri/workshop-tsm.jpg'],
            ['title' => 'Juara 1 LKS Tingkat Provinsi', 'category' => 'Prestasi', 'event_date' => '2026-08-05', 'file_path' => 'galeri/prestasi-lks.jpg'],
            ['title' => 'Praktik Industri Siswa Kelas XI', 'category' => 'Kegiatan', 'event_date' => '2026-07-21', 'file_path' => 'galeri/praktik-industri.jpg'],
            ['title' => 'Upacara Bendera HUT Kemerdekaan', 'category' => 'Kegiatan', 'event_date' => '2026-08-17', 'file_path' => 'galeri/upacara.jpg'],
            ['title' => 'Kunjungan Industri ke PT Timah Tbk', 'category' => 'Kegiatan', 'event_date' => '2026-06-15', 'file_path' => 'galeri/kunjungan-industri.jpg'],
        ];

        foreach ($items as $i => $item) {
            Gallery::updateOrCreate(
                ['title' => $item['title']],
                [...$item, 'type' => 'photo', 'order' => $i]
            );
        }
    }
}
