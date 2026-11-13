<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'content', 'excerpt', 'image',
    ];

    /**
     * Full public URL for the page's image.
     *
     * `image` is stored as a relative path on the `public` disk (e.g.
     * "pages/gedung-sekolah.jpg", set by the admin upload form). This
     * accessor turns that into a usable <img src>, so views can just do
     * $page->image_url — matches how profil.blade.php calls it.
     *
     * Returns null if no image is set, so callers can still fall back to
     * their own placeholder via `?? asset('images/placeholder.jpg')`.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }

        // Already a full URL (e.g. seeded with an external link) — use as-is.
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        return asset('storage/'.ltrim($this->image, '/'));
    }
}