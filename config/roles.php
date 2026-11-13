<?php

/*
|--------------------------------------------------------------------------
| Role & Hak Akses Panel Admin
|--------------------------------------------------------------------------
| 'permissions' memetakan role -> daftar kunci akses. Tanda '*' = semua akses.
| Kunci akses dipakai oleh middleware `permission:<kunci>` di routes/admin.php
| dan oleh sidebar (layouts/admin.blade.php).
|
| Kunci 'profil' (Profil Sekolah) tidak diberikan ke role mana pun selain
| superadmin ('*'), sama seperti 'akademik' dan 'pengaturan'.
|
| Menambah role baru cukup menambah satu baris di 'labels' dan 'permissions'.
*/
return [
    'labels' => [
        'superadmin'   => 'Superadmin',
        'humas'        => 'Humas',
        'panitia_ppdb' => 'Panitia SPMB',
        'admin_perpus' => 'Admin Perpustakaan',
        'admin_bk'     => 'Admin BK',
        'guru'         => 'Guru',
    ],

    'permissions' => [
        'superadmin'   => ['*'],
        'humas'        => ['berita', 'galeri', 'pesan'],   // berita + moderasi komentar, galeri, pesan masuk
        'panitia_ppdb' => ['ppdb'],
        'admin_perpus' => ['elibrary'],
        'admin_bk'     => ['bkk', 'tracer'],
        'guru'         => ['erapor'],
    ],
];
