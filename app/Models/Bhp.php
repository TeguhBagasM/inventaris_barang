<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bhp extends Model
{
    protected $fillable = ['nama', 'spesifikasi', 'tahun_pengadaan', 'stok', 'sumber_dana'];
}
