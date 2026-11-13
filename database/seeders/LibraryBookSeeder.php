<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LibraryBook;

class LibraryBookSeeder extends Seeder
{
    public function run(): void
    {
        LibraryBook::create([
            'no_induk_buku' => 'B001',
            'judul' => 'Pemrograman Web Dasar',
            'pengarang' => 'Andi Pratama',
            'penerbit' => 'Informatika Bandung',
            'tahun_terbit' => 2022,
            'klasifikasi' => '005.13',
            'jumlah' => 5,
        ]);

        LibraryBook::create([
            'no_induk_buku' => 'B002',
            'judul' => 'Motor Bensin 4 Langkah',
            'pengarang' => 'Budi Santoso',
            'penerbit' => 'Erlangga',
            'tahun_terbit' => 2020,
            'klasifikasi' => '629.25',
            'jumlah' => 3,
        ]);
    }
}