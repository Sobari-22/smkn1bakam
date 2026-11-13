<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_induk_buku', 'no_inventaris_buku', 'judul', 'pengarang',
        'penerbit', 'tahun_terbit', 'klasifikasi', 'jumlah',
    ];
}
