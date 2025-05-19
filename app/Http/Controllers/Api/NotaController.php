<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;

class NotaController extends Controller
{
    public function show($id)
    {
        $pesanan = Pesanan::with('details')->find($id);

        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        // Hitung total harga keseluruhan
        $totalHarga = $pesanan->details->sum(function ($item) {
            return $item->harga_satuan * $item->jumlah;
        });


        return response()->json([
            'pesanan_id' => $pesanan->id,
            'nama_toko' => $pesanan->nama_toko,
            'alamat' => $pesanan->alamat,
            'no_hp' => $pesanan->no_hp,
            'details' => $pesanan->details->map(function ($item) {
                return [
                    'nama_barang' => $item->nama_barang,
                    'jumlah' => $item->jumlah,
                    'satuan' => $item->unit,
                    'harga_satuan' => $item->harga_satuan,
                    'total_harga' => $item->harga_satuan * $item->jumlah,
                ];
            }),
            'total_harga_semua' => $totalHarga,
        ]);
    }
}
