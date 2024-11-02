<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_gedung',
        'luas_gedung',
        'tahun_perolehan',
        'nilai_bangunan',
        'jumlah_ruang',
        'peruntukkan',
        'gambar',
        'keterangan'
    ];

    // Relasi One to Many dengan Ruang
    public function ruangs()
    {
        return $this->hasMany(Ruang::class);
    }
}
