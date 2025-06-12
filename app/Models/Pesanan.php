<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id','nama_toko', 'alamat', 'no_hp', 'orders'
];


    // Mengatur format tanggal jika diperlukan
    protected $dates = ['created_at', 'updated_at'];

    public function details()
{
    return $this->hasMany(PesananDetail::class, 'pesanan_id');
}


}