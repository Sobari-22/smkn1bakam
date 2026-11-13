<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'accreditation', 'image'];

    public function concentrations()
    {
        return $this->hasMany(Concentration::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function ppdbRegistrants()
    {
        return $this->hasMany(PpdbRegistrant::class);
    }
}
