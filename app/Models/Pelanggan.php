<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'alamat',
        'phone',
    ];

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class);
    }
}
