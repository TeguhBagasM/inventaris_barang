<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;


    protected $guarded = [];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class);
    }
    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class);
    }
}
