<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;
    protected $guarded = [];
    // Protected $table = 'kategoris';

    public function barangs()
{
    return $this->hasMany(Barang::class);
}
}





