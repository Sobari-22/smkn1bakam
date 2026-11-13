<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TracerResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'nisn', 'tahun_lulus', 'alumni_nama', 'program_id', 'concentration_id',
        'no_hp', 'domisili', 'status_kegiatan',
        'nama_instansi', 'bidang_instansi', 'posisi_jabatan', 'kesesuaian_kerja', 'lama_tunggu_kerja', 'via_bkk',
        'nama_kampus', 'program_studi', 'jenjang', 'kuliah_linier',
        'jenis_usaha', 'bidang_usaha', 'lama_usaha', 'jumlah_tenaga_kerja',
        'alasan_belum',
        'evaluasi_kesiapan', 'saran',
    ];

    protected $casts = [
        'kuliah_linier' => 'boolean',
        'via_bkk' => 'boolean',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function concentration()
    {
        return $this->belongsTo(Concentration::class);
    }

    public static function statusLabels(): array
    {
        return [
            'bekerja' => 'Bekerja',
            'kuliah' => 'Kuliah',
            'wiraswasta' => 'Wiraswasta',
            'bekerja_kuliah' => 'Bekerja sambil Kuliah',
            'belum' => 'Belum Bekerja/Kuliah',
        ];
    }

    public static function waitTimeLabels(): array
    {
        return [
            'kurang_1_bulan' => '< 1 bulan',
            '1_3_bulan' => '1–3 bulan',
            '3_6_bulan' => '3–6 bulan',
            'lebih_6_bulan' => '> 6 bulan',
        ];
    }
}
