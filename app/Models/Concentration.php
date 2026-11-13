<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concentration extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id', 'name', 'title', 'slug', 'description',
        'quota', 'competencies', 'career_prospects',
        'image', 'overview', 'advantages', 'further_studies',
    ];

    protected $casts = [
        'competencies' => 'array',
        'career_prospects' => 'array',
        'advantages' => 'array',
        'further_studies' => 'array',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}