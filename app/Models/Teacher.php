<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'nip', 'tmt', 'position', 'subject', 'subjects', 'teaches_all_programs',
        'program_id', 'photo', 'is_active', 'is_management', 'order',
    ];

    protected $casts = [
        'tmt' => 'date',
        'subjects' => 'array',
        'teaches_all_programs' => 'boolean',
        'is_active' => 'boolean',
        'is_management' => 'boolean',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function positions()
    {
        return $this->hasMany(TeacherPosition::class);
    }

    public function concentrations()
    {
        return $this->belongsToMany(Concentration::class);
    }

    /** Daftar mata pelajaran (mendukung data lama yang masih berupa teks tunggal). */
    public function getSubjectListAttribute(): array
    {
        if (! empty($this->subjects)) {
            return array_values($this->subjects);
        }

        return filled($this->subject) ? [trim($this->subject)] : [];
    }

    /** "Semua program", atau nama konsentrasi: "TKJ, TKR". Null bila tidak diisi. */
    public function getTeachingLabelAttribute(): ?string
    {
        if ($this->teaches_all_programs) {
            return 'Semua program';
        }

        $names = $this->concentrations->pluck('name')->filter()->implode(', ');

        return $names !== '' ? $names : null;
    }

    /** Jabatan yang tidak mengajar (Kepala sekolah, tata usaha) menyembunyikan data mengajar. */
    public function getTeachesAttribute(): bool
    {
        foreach ($this->positions as $position) {
            if (($position->definition()['teaches'] ?? true) === false) {
                return false;
            }
        }

        return true;
    }
}
