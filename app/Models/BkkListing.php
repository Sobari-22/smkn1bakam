<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BkkListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'company_name', 'location', 'type',
        'description', 'qualifications', 'contact_info',
        'posted_at', 'expires_at',
    ];

    protected $casts = [
        'posted_at' => 'date',
        'expires_at' => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
        });
    }

    public function isNew(): bool
    {
        return $this->posted_at->diffInDays(now()) <= 14;
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'kerja_tetap' => 'Kerja Tetap',
            'magang' => 'Magang',
            'magang_rekrutmen' => 'Magang & Rekrutmen',
            default => $this->type,
        };
    }
}
