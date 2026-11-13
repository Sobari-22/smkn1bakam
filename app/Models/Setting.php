<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public $timestamps = true;

    /**
     * Get a setting value by key, with a fallback default.
     * Cached for 1 hour so we don't hit the DB on every request
     * (settings rarely change, and this is called from the layout on every page).
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        $settings = Cache::remember('settings.all', 3600, function () {
            return static::pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    /**
     * Create or update a setting value. Used by the admin toggle for things
     * like `ppdb_status` (open/closed) — the `saved` hook below already
     * clears the cache, so the new value is picked up on the very next
     * Setting::get() call, no manual cache-clearing needed at call sites.
     */
    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Jadwal SPMB yang tampil di halaman publik: daftar {label, value} yang
     * diatur admin (disimpan sebagai JSON di key `spmb_schedule`). Bila belum
     * pernah diatur, dipakai nilai lama `ppdb_jadwal_*` / default bawaan.
     */
    public static function spmbSchedule(): array
    {
        $raw = static::get('spmb_schedule');
        $decoded = filled($raw) ? json_decode($raw, true) : null;

        if (is_array($decoded) && $decoded !== []) {
            return array_values(array_filter(
                $decoded,
                fn ($i) => is_array($i) && filled($i['label'] ?? null) && filled($i['value'] ?? null)
            ));
        }

        $y = now()->year;

        return [
            ['label' => 'Pendaftaran', 'value' => static::get('ppdb_jadwal_daftar', "1–25 Agustus {$y}")],
            ['label' => 'Verifikasi Data', 'value' => static::get('ppdb_jadwal_verifikasi', '26–31 Agustus')],
            ['label' => 'Pengumuman', 'value' => static::get('ppdb_jadwal_pengumuman', "1 September {$y}")],
            ['label' => 'Daftar Ulang', 'value' => static::get('ppdb_jadwal_daftar_ulang', "8–12 September {$y}")],
        ];
    }

    public static function saveSpmbSchedule(array $items): void
    {
        $clean = array_values(array_map(fn ($i) => [
            'label' => trim($i['label']),
            'value' => trim($i['value']),
        ], $items));

        static::set('spmb_schedule', json_encode($clean, JSON_UNESCAPED_UNICODE));
    }

    public static function forgetCache(): void
    {
        Cache::forget('settings.all');
    }

    protected static function booted()
    {
        static::saved(fn () => static::forgetCache());
        static::deleted(fn () => static::forgetCache());
    }
}