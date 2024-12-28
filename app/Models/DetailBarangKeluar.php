<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailBarangKeluar extends Model
{
    protected $fillable = [
        'barang_keluar_id',
        'bhp_id',
        'jumlah'
    ];

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class);
    }

    public function bhp()
    {
        return $this->belongsTo(Bhp::class);
    }
}
