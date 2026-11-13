<?php

/*
|--------------------------------------------------------------------------
| Struktur Organisasi SMKN 1 Bakam
|--------------------------------------------------------------------------
| Daftar jabatan pada bagan struktur organisasi sekolah. Posisi, atasan, dan
| jenis garis ditetapkan di sini (mengikuti bagan sekolah), sehingga admin
| cukup memilih jabatan di form Guru & Staf dan posisinya di bagan terisi
| otomatis.
|
| Kunci jabatan (mis. 'wk_kesiswaan') disimpan di tabel `teacher_positions`,
| jadi JANGAN diubah setelah ada data. Label dan teks boleh diubah bebas.
|
|   label     Nama jabatan yang tampil
|   group     Pengelompokan di dropdown form
|   place     Keterangan posisi pada bagan (tampil di form)
|   line      'komando' (garis tegas) | 'koordinasi' (garis putus-putus)
|   teaches   false = tidak mengajar (mata pelajaran & "mengajar di" disembunyikan)
|   single    true  = hanya boleh dipegang satu orang
|   class     true  = ada isian kelas (untuk wali kelas)
|   area      Letak di bagan: top | tu | wakasek | unit | kk | dewan
|   parent    Kunci jabatan atasan (untuk area unit dan kk)
|   col       Kolom: 1-4 untuk wakasek; 'tkr' atau 'tkj' untuk kk
*/

return [

    'positions' => [

        // ---------- Pimpinan ----------
        'kepsek' => [
            'label' => 'Kepala sekolah', 'group' => 'Pimpinan', 'place' => 'Puncak bagan',
            'line' => 'komando', 'teaches' => false, 'single' => true, 'area' => 'top',
        ],
        'tu' => [
            'label' => 'Sub koord. tata usaha', 'group' => 'Pimpinan', 'place' => 'Di bawah Kepala sekolah',
            'line' => 'komando', 'teaches' => false, 'single' => true, 'area' => 'tu', 'parent' => 'kepsek',
        ],

        // ---------- Wakil kepala sekolah ----------
        'wk_kurikulum' => [
            'label' => 'Wakasek kurikulum', 'group' => 'Wakil kepala sekolah', 'place' => 'Di bawah Kepala sekolah',
            'line' => 'komando', 'single' => true, 'area' => 'wakasek', 'parent' => 'kepsek', 'col' => 1,
        ],
        'wk_sarpras' => [
            'label' => 'Wakasek sarana dan prasarana', 'group' => 'Wakil kepala sekolah', 'place' => 'Di bawah Kepala sekolah',
            'line' => 'komando', 'single' => true, 'area' => 'wakasek', 'parent' => 'kepsek', 'col' => 2,
        ],
        'wk_kesiswaan' => [
            'label' => 'Wakasek kesiswaan', 'group' => 'Wakil kepala sekolah', 'place' => 'Di bawah Kepala sekolah',
            'line' => 'komando', 'single' => true, 'area' => 'wakasek', 'parent' => 'kepsek', 'col' => 3,
        ],
        'wk_humas' => [
            'label' => 'Wakasek humas dan kerjasama industri', 'group' => 'Wakil kepala sekolah', 'place' => 'Di bawah Kepala sekolah',
            'line' => 'komando', 'single' => true, 'area' => 'wakasek', 'parent' => 'kepsek', 'col' => 4,
        ],

        // ---------- Unit di bawah Wakasek ----------
        'ka_perpus' => [
            'label' => 'Ka. perpustakaan dan media pembelajaran', 'group' => 'Unit di bawah Wakasek',
            'place' => 'Di bawah Wakasek sarana dan prasarana', 'line' => 'komando', 'single' => true, 'area' => 'unit', 'parent' => 'wk_sarpras',
        ],
        'pembina_budaya' => [
            'label' => 'Pembina harian budaya sekolah', 'group' => 'Unit di bawah Wakasek',
            'place' => 'Di bawah Wakasek sarana dan prasarana', 'line' => 'komando', 'single' => true, 'area' => 'unit', 'parent' => 'wk_sarpras',
        ],
        'pembina_osis' => [
            'label' => 'Pembina harian OSIS', 'group' => 'Unit di bawah Wakasek',
            'place' => 'Di bawah Wakasek kesiswaan', 'line' => 'komando', 'single' => true, 'area' => 'unit', 'parent' => 'wk_kesiswaan',
        ],
        'koordinator_bk' => [
            'label' => 'Koordinator BK dan BKK', 'group' => 'Unit di bawah Wakasek',
            'place' => 'Di bawah Wakasek kesiswaan', 'line' => 'komando', 'single' => true, 'area' => 'unit', 'parent' => 'wk_kesiswaan',
        ],
        'kepala_produksi' => [
            'label' => 'Kepala unit produksi', 'group' => 'Unit di bawah Wakasek',
            'place' => 'Di bawah Wakasek humas dan kerjasama industri', 'line' => 'komando', 'single' => true, 'area' => 'unit', 'parent' => 'wk_humas',
        ],
        'kepala_ict' => [
            'label' => 'Kepala unit operasional ICT', 'group' => 'Unit di bawah Wakasek',
            'place' => 'Di bawah Wakasek humas dan kerjasama industri', 'line' => 'komando', 'single' => true, 'area' => 'unit', 'parent' => 'wk_humas',
        ],

        // ---------- Kompetensi keahlian TKR ----------
        'ketua_kk_tkr' => [
            'label' => 'Ketua KK TKR', 'group' => 'Kompetensi keahlian TKR', 'place' => 'Kolom TKR, di bawah Kepala sekolah',
            'line' => 'koordinasi', 'single' => true, 'area' => 'kk', 'parent' => 'kepsek', 'col' => 'tkr',
        ],
        'ka_lab_tkr' => [
            'label' => 'Ka. lab TKR', 'group' => 'Kompetensi keahlian TKR', 'place' => 'Kolom TKR, di bawah Ketua KK TKR',
            'line' => 'komando', 'single' => true, 'area' => 'kk', 'parent' => 'ketua_kk_tkr', 'col' => 'tkr',
        ],
        'wali_tkr' => [
            'label' => 'Wali kelas TKR', 'group' => 'Kompetensi keahlian TKR', 'place' => 'Kolom TKR, di bawah Ketua KK TKR',
            'line' => 'komando', 'single' => false, 'class' => true, 'area' => 'kk', 'parent' => 'ketua_kk_tkr', 'col' => 'tkr',
        ],

        // ---------- Kompetensi keahlian TKJ ----------
        'ketua_kk_tkj' => [
            'label' => 'Ketua KK TKJ', 'group' => 'Kompetensi keahlian TKJ', 'place' => 'Kolom TKJ, di bawah Kepala sekolah',
            'line' => 'koordinasi', 'single' => true, 'area' => 'kk', 'parent' => 'kepsek', 'col' => 'tkj',
        ],
        'ka_lab_tkj' => [
            'label' => 'Ka. lab TKJ', 'group' => 'Kompetensi keahlian TKJ', 'place' => 'Kolom TKJ, di bawah Ketua KK TKJ',
            'line' => 'komando', 'single' => true, 'area' => 'kk', 'parent' => 'ketua_kk_tkj', 'col' => 'tkj',
        ],
        'wali_tkj' => [
            'label' => 'Wali kelas TKJ', 'group' => 'Kompetensi keahlian TKJ', 'place' => 'Kolom TKJ, di bawah Ketua KK TKJ',
            'line' => 'komando', 'single' => false, 'class' => true, 'area' => 'kk', 'parent' => 'ketua_kk_tkj', 'col' => 'tkj',
        ],

        // ---------- Lainnya ----------
        'pembina_ekskul' => [
            'label' => 'Pembina ekstrakurikuler', 'group' => 'Lainnya', 'place' => 'Kotak Dewan guru di dasar bagan',
            'line' => 'komando', 'single' => false, 'area' => 'dewan', 'parent' => 'kepsek',
        ],
    ],

    // Kotak lembaga pada bagan (bukan orang, jadi tidak ada di form guru)
    'static' => [
        'left' => 'Komite sekolah',
        'right' => 'Institusi pasangan',
        'bottom' => 'Dewan guru / pembina ekstrakurikuler',
    ],
];
