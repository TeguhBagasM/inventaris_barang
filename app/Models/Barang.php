<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;


    protected $guarded = [];

    public function kategoris()
    {
        return $this->belongsToMany(Kategori::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }
    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class);
    }
}
