<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = ['nama_barang', 'pelanggan_id', 'kuantitas', 'harga', 'id_barang', 'data_barang', 'data_jasa'];

    protected $casts = [
        'nama_barang' => 'array',
        'data_barang' => 'array',
        'data_jasa' => 'array',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }
    
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
