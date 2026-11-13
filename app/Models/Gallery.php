<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'file_path', 'video_url', 'type', 'category', 'event_date', 'order',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->latest('event_date');
    }
}
