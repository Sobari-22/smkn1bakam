<?php

namespace Database\Seeders;

use App\Models\BkkListing;
use App\Models\Concentration;
use App\Models\Program;
use App\Models\TracerResponse;
use Illuminate\Database\Seeder;

class BkkSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedListings();
        $this->seedTracerResponses();
    }

    private function seedListings(): void
    {
        $listings = [
            ['title' => 'Mekanik Sepeda Motor', 'company_name' => 'Bengkel Honda AHASS Bakam', 'location' => 'Bakam, Bangka Barat', 'type' => 'kerja_tetap', 'posted_at' => '2026-08-08'],
            ['title' => 'Teknisi IT Support', 'company_name' => 'PT Timah Tbk — Divisi IT', 'location' => 'Pangkalpinang, Babel', 'type' => 'kerja_tetap', 'posted_at' => '2026-08-05'],
            ['title' => 'Mekanik Kendaraan Ringan', 'company_name' => 'Dealer Daihatsu Bangka Barat', 'location' => 'Muntok, Bangka Barat', 'type' => 'magang_rekrutmen', 'posted_at' => '2026-08-03'],
            ['title' => 'Network Technician (Magang)', 'company_name' => 'PT Telkom Indonesia — Witel Babel', 'location' => 'Pangkalpinang, Babel', 'type' => 'magang', 'posted_at' => '2026-07-28'],
            ['title' => 'Staf Administrasi IT', 'company_name' => 'RSUD Kabupaten Bangka Barat', 'location' => 'Muntok, Bangka Barat', 'type' => 'kerja_tetap', 'posted_at' => '2026-07-20'],
        ];

        foreach ($listings as $listing) {
            BkkListing::updateOrCreate(
                ['title' => $listing['title'], 'company_name' => $listing['company_name']],
                $listing
            );
        }
    }

    /**
     * Seeds sample alumni responses whose aggregate numbers match the
     * approved Figma design (142 total; 68 bekerja / 21 kuliah / 18
     * wiraswasta / 14 bekerja_kuliah / 21 belum; 72% kesesuaian; wait-time
     * buckets 22/28/14/4; 34 placed via BKK; per-year trend 2023: 38 total
     * /24 bekerja, 2024: 52/31, 2025: 52/35).
     */
    private function seedTracerResponses(): void
    {
        if (TracerResponse::count() > 0) {
            return; // avoid duplicating on re-seed
        }

        $tkj = Concentration::where('slug', 'tkj')->first();
        $tsm = Concentration::where('slug', 'tsm')->first();
        $tkr = Concentration::where('slug', 'tkr')->first();
        $concentrations = array_filter([$tkj, $tsm, $tkr]);

        $yearCounts = ['2023' => 38, '2024' => 52, '2025' => 52]; // sums to 142
        $statusPool = [
            ...array_fill(0, 68, 'bekerja'),
            ...array_fill(0, 21, 'kuliah'),
            ...array_fill(0, 18, 'wiraswasta'),
            ...array_fill(0, 14, 'bekerja_kuliah'),
            ...array_fill(0, 21, 'belum'),
        ];
        shuffle($statusPool);

        $waitPool = [
            ...array_fill(0, 22, 'kurang_1_bulan'),
            ...array_fill(0, 28, '1_3_bulan'),
            ...array_fill(0, 14, '3_6_bulan'),
            ...array_fill(0, 4, 'lebih_6_bulan'),
        ];
        shuffle($waitPool);

        $i = 0;
        $waitIndex = 0;
        $viaBkkRemaining = 34;

        foreach ($yearCounts as $year => $count) {
            for ($n = 0; $n < $count; $n++) {
                $status = $statusPool[$i] ?? 'belum';
                $i++;

                $isWorking = in_array($status, ['bekerja', 'bekerja_kuliah']);
                $kesesuaian = null;
                $waitTime = null;
                $viaBkk = false;

                if ($isWorking) {
                    // ~72% kesesuaian across working alumni
                    $kesesuaian = (mt_rand(1, 100) <= 72)
                        ? (mt_rand(0, 1) ? 'sangat_sesuai' : 'sesuai')
                        : (mt_rand(0, 1) ? 'kurang_sesuai' : 'tidak_sesuai');

                    if ($waitIndex < count($waitPool)) {
                        $waitTime = $waitPool[$waitIndex];
                        $waitIndex++;
                    }

                    if ($viaBkkRemaining > 0 && mt_rand(1, 100) <= 30) {
                        $viaBkk = true;
                        $viaBkkRemaining--;
                    }
                }

                $concentration = $concentrations ? $concentrations[array_rand($concentrations)] : null;

                TracerResponse::create([
                    'nisn' => (string) mt_rand(1000000000, 9999999999),
                    'tahun_lulus' => $year,
                    'alumni_nama' => 'Alumni '.$year.'-'.($n + 1),
                    'program_id' => $concentration?->program_id,
                    'concentration_id' => $concentration?->id,
                    'domisili' => 'Bangka Barat',
                    'status_kegiatan' => $status,
                    'nama_instansi' => $isWorking ? 'Mitra Industri Lokal' : null,
                    'kesesuaian_kerja' => $kesesuaian,
                    'lama_tunggu_kerja' => $waitTime,
                    'via_bkk' => $viaBkk,
                    'nama_kampus' => $status === 'kuliah' || $status === 'bekerja_kuliah' ? 'Politeknik/Universitas Setempat' : null,
                    'kuliah_linier' => in_array($status, ['kuliah', 'bekerja_kuliah']) ? (bool) mt_rand(0, 1) : null,
                    'jenis_usaha' => $status === 'wiraswasta' ? 'Usaha Mandiri' : null,
                ]);
            }
        }
    }
}
