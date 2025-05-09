<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananDetail extends Model
{
    use HasFactory;

    protected $fillable = ['pesanan_id', 'barang_id', 'quantity', 'unit', 'price'];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pesanan_id');
    }

}
