<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_alat',
        'merk',
        'spesifikasi',
        'no_seri',
        'jumlah',
        'satuan',
        'tahun_perolehan',
        'sumber_dana'
    ];
}
