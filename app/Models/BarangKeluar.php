<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal_minta',
        'status',
        'keterangan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailBarangKeluars()
    {
        return $this->hasMany(DetailBarangKeluar::class);
    }

    public function bhps()
    {
        return $this->belongsToMany(Bhp::class, 'detail_barang_keluars')
            ->withPivot('jumlah')
            ->withTimestamps();
    }
}
