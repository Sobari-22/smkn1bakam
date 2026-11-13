<?php

namespace Database\Seeders;

use App\Models\LibraryBook;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class LibrarySeeder extends Seeder
{
    public function run(): void
    {
        // "Tentang Perpustakaan" content, sourced from the school's official
        // PERPUSTAKAAN_SMKN_1_BAKAM.docx profile document. Stored as one
        // HTML blob (edited later via Dashboard Admin > Manajemen Halaman
        // Statis), styled to match the E-Library page design.
        Page::updateOrCreate(
            ['slug' => 'tentang-perpustakaan'],
            [
                'title' => 'Tentang Perpustakaan',
                'content' => <<<'HTML'
<p>Perpustakaan SMK Negeri 1 Bakam (SKANSABA) berdiri sejak tahun 2014, sekitar satu tahun setelah SMK Negeri 1 Bakam diresmikan. Sejak berdirinya, berbagai sumber belajar seperti buku paket, alat peraga, dan media pembelajaran mulai ditata untuk mendukung kegiatan akademik dan pengembangan diri seluruh warga sekolah.</p>
<blockquote data-role="visi">
<strong>VISI</strong>
<p>"Menjadikan Perpustakaan SMK Negeri 1 Bakam unggul dalam pelayanan dan penyediaan sumber-sumber informasi ilmu pengetahuan yang dapat memenuhi proses belajar mengajar."</p>
</blockquote>
<div data-role="misi">
<strong>MISI</strong>
<ol>
<li>Mengadakan berbagai koleksi buku dan koleksi lainnya yang dapat menunjang proses belajar mengajar</li>
<li>Memaksimalkan manajemen pengelolaan perpustakaan yang lebih efektif</li>
<li>Mengupayakan penggolongan buku dan koleksi lainnya merujuk pada standar nasional (sistem DDC)</li>
<li>Memotivasi para pengguna perpustakaan agar frekuensi kunjungan meningkat</li>
</ol>
</div>
<p><em>Motto: "Buku Yang Bermanfaat Merupakan Teman Yang Sejati"</em></p>
HTML,
            ]
        );

        Setting::updateOrCreate(['key' => 'library_hours'], ['value' => '08.00–14.00 (Sen–Sab)']);
        Setting::updateOrCreate(['key' => 'library_officer'], ['value' => 'Nurfatria Wita Atma, S.Pd — Ruang Perpustakaan']);

        $books = [
            ['no_induk_buku' => '2024-001', 'judul' => 'Jaringan Komputer (Edisi Revisi)', 'pengarang' => 'Forouzan, B.A.', 'penerbit' => 'McGraw-Hill', 'tahun_terbit' => 2023, 'klasifikasi' => '004.6', 'jumlah' => 5],
            ['no_induk_buku' => '2024-002', 'judul' => 'Cisco Networking Essentials', 'pengarang' => 'Troy McMillan', 'penerbit' => 'Sybex', 'tahun_terbit' => 2022, 'klasifikasi' => '004.6', 'jumlah' => 3],
            ['no_induk_buku' => '2024-003', 'judul' => 'Teknik Sepeda Motor Modern', 'pengarang' => 'Budi Santoso', 'penerbit' => 'Erlangga', 'tahun_terbit' => 2024, 'klasifikasi' => '629.2', 'jumlah' => 8],
            ['no_induk_buku' => '2024-004', 'judul' => 'Sistem Injeksi Bahan Bakar Elektronik', 'pengarang' => 'Daryanto', 'penerbit' => 'Alfabeta', 'tahun_terbit' => 2023, 'klasifikasi' => '629.2', 'jumlah' => 6],
            ['no_induk_buku' => '2024-005', 'judul' => 'Kelistrikan Otomotif', 'pengarang' => 'Supriyadi, T.', 'penerbit' => 'Yudhistira', 'tahun_terbit' => 2022, 'klasifikasi' => '629.2', 'jumlah' => 4],
            ['no_induk_buku' => '2024-006', 'judul' => 'Linux Administration Handbook', 'pengarang' => 'Evi Nemeth', 'penerbit' => 'Pearson', 'tahun_terbit' => 2023, 'klasifikasi' => '005.4', 'jumlah' => 2],
            ['no_induk_buku' => '2024-007', 'judul' => 'Bahasa Indonesia untuk SMK', 'pengarang' => 'Tim Kemdikbud', 'penerbit' => 'Kemdikbud', 'tahun_terbit' => 2024, 'klasifikasi' => '410', 'jumlah' => 15],
            ['no_induk_buku' => '2024-008', 'judul' => 'Matematika Teknik SMK Kelas X', 'pengarang' => 'Kasmina', 'penerbit' => 'Erlangga', 'tahun_terbit' => 2024, 'klasifikasi' => '510', 'jumlah' => 12],
            ['no_induk_buku' => '2024-009', 'judul' => 'Keamanan Jaringan & Firewall', 'pengarang' => 'William Stallings', 'penerbit' => 'Pearson', 'tahun_terbit' => 2022, 'klasifikasi' => '005.8', 'jumlah' => 4],
            ['no_induk_buku' => '2024-010', 'judul' => 'Pendidikan Agama Islam SMK', 'pengarang' => 'Tim Kemdikbud', 'penerbit' => 'Kemdikbud', 'tahun_terbit' => 2023, 'klasifikasi' => '200', 'jumlah' => 20],
            ['no_induk_buku' => '2024-011', 'judul' => 'K3 Bengkel Otomotif', 'pengarang' => 'R. Suyanto', 'penerbit' => 'Alfabeta', 'tahun_terbit' => 2023, 'klasifikasi' => '629.2', 'jumlah' => 7],
            ['no_induk_buku' => '2024-012', 'judul' => 'Pengantar Cloud Computing', 'pengarang' => 'Thomas Erl', 'penerbit' => 'Prentice Hall', 'tahun_terbit' => 2022, 'klasifikasi' => '004.6', 'jumlah' => 3],
        ];

        foreach ($books as $book) {
            LibraryBook::updateOrCreate(['no_induk_buku' => $book['no_induk_buku']], $book);
        }
    }
}
