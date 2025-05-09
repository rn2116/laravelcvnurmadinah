<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $fillable = ['nama_barang', 'jumlah'];

    public function details()
    {
        return $this->hasMany(PesananDetail::class, 'pesanan_id');
    }

}
