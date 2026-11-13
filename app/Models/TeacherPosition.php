<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherPosition extends Model
{
    protected $fillable = ['teacher_id', 'position_key', 'class_label'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /** Definisi jabatan dari config/struktur.php (array kosong bila kuncinya sudah tidak ada). */
    public function definition(): array
    {
        return config('struktur.positions.'.$this->position_key, []);
    }

    /** Nama jabatan, ditambah kelas untuk wali kelas: "Wali kelas TKJ (XI TKJ 1)". */
    public function getLabelAttribute(): string
    {
        $label = $this->definition()['label'] ?? $this->position_key;

        return filled($this->class_label) ? $label.' ('.$this->class_label.')' : $label;
    }
}
