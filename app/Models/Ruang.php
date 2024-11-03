<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_ruang',
        'gedung_id',
        'ukuran',
        'kondisi',
        'peruntukkan',
        'keterangan'
    ];

    // Relasi Many to One dengan Gedung
    public function gedung()
    {
        return $this->belongsTo(Gedung::class);
    }

    public function ruang()
    {
        return $this->hasMany(Barang::class);
    }

    public function barang()
    {
        return $this->belongsTo(Ruang::class);
    }
}
