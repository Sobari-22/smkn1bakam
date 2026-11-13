<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpdbRegistrant extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_number', 'full_name', 'gender', 'nik', 'nisn', 'no_kk',
        'address', 'program_id', 'concentration_id',
        'has_kip', 'has_kis', 'has_pkh', 'status',
    ];

    protected $casts = [
        'has_kip' => 'boolean',
        'has_kis' => 'boolean',
        'has_pkh' => 'boolean',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function concentration()
    {
        return $this->belongsTo(Concentration::class);
    }

    public static function generateRegistrationNumber(): string
    {
        $year = now()->format('Y');
        $last = static::whereYear('created_at', now()->year)->count() + 1;

        return $year.str_pad((string) $last, 3, '0', STR_PAD_LEFT);
    }
}
