<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananDetail extends Model
{
    protected $fillable = ['pesanan_id', 'barang_id', 'nama_barang', 'harga', 'jumlah', 'satuan'];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}
