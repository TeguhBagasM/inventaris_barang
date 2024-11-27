<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{

    protected $table = 'peminjamans';
    protected $fillable = [
        'user_id', 
        'tanggal_peminjaman', 
        'status', 
        'keterangan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailPeminjamans()
    {
        return $this->hasMany(DetailPeminjaman::class);
    }
}
