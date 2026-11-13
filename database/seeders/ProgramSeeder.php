<?php

namespace Database\Seeders;

use App\Models\Concentration;
use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $tjkt = Program::updateOrCreate(
            ['slug' => 'teknik-jaringan-komputer-dan-telekomunikasi'],
            [
                'name' => 'Teknik Jaringan Komputer dan Telekomunikasi',
                'description' => 'Program keahlian yang mempersiapkan lulusan terampil di bidang instalasi, konfigurasi, dan pemeliharaan jaringan komputer serta infrastruktur telekomunikasi modern.',
                'accreditation' => 'A',
            ]
        );

        Concentration::updateOrCreate(
            ['slug' => 'tkj'],
            [
                'program_id' => $tjkt->id,
                'name' => 'TKJ',
                'title' => 'Teknik Komputer Jaringan',
                'description' => 'Fokus pada jaringan komputer lokal dan wide area, administrasi server, dan keamanan jaringan.',
                'quota' => 72,
                'competencies' => [
                    'Instalasi Jaringan LAN/WAN',
                    'Konfigurasi Router & Switch (Cisco)',
                    'Administrasi Server Linux',
                    'Keamanan Jaringan & Firewall',
                    'Cloud Computing Dasar',
                ],
                'career_prospects' => [
                    'Network Engineer',
                    'IT Support Specialist',
                    'System Administrator',
                    'Wirausaha Jasa IT',
                ],
            ]
        );

        $otomotif = Program::updateOrCreate(
            ['slug' => 'teknik-otomotif'],
            [
                'name' => 'Teknik Otomotif',
                'description' => 'Program keahlian yang mencetak teknisi otomotif profesional dengan kompetensi perawatan, perbaikan, dan diagnosa kendaraan bermotor sesuai standar industri.',
                'accreditation' => 'B',
            ]
        );

        Concentration::updateOrCreate(
            ['slug' => 'tsm'],
            [
                'program_id' => $otomotif->id,
                'name' => 'TSM',
                'title' => 'Teknik Sepeda Motor',
                'description' => 'Spesialisasi pada perawatan dan perbaikan sepeda motor berbahan bakar konvensional maupun injeksi.',
                'quota' => 36,
                'competencies' => [
                    'Tune-up & Servis Sepeda Motor',
                    'Sistem Injeksi Bahan Bakar',
                    'Sistem Kelistrikan Sepeda Motor',
                    'Diagnosa Mesin',
                    'K3 Bengkel',
                ],
                'career_prospects' => [
                    'Teknisi Bengkel Resmi',
                    'Mekanik Otomotif',
                    'Wirausaha Bengkel',
                    'Inspektur Kendaraan',
                ],
            ]
        );

        Concentration::updateOrCreate(
            ['slug' => 'tkr'],
            [
                'program_id' => $otomotif->id,
                'name' => 'TKR',
                'title' => 'Teknik Kendaraan Ringan',
                'description' => 'Spesialisasi pada perawatan dan perbaikan kendaraan ringan roda empat (mobil) berbahan bakar bensin dan diesel.',
                'quota' => 36,
                'competencies' => [
                    'Tune-up Mesin Konvensional & EFI',
                    'Sistem Kelistrikan Kendaraan Ringan',
                    'Sistem Rem & Suspensi',
                    'Diagnosa Menggunakan Scan Tool',
                    'K3 Bengkel',
                ],
                'career_prospects' => [
                    'Teknisi Bengkel Resmi',
                    'Mekanik Otomotif',
                    'Wirausaha Bengkel',
                    'Quality Control Kendaraan',
                ],
            ]
        );
    }
}
