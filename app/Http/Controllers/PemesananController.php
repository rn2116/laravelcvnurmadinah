<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use Illuminate\Support\Facades\DB;

class PemesananController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_toko' => 'required|string',
            'alamat' => 'required|string',
            'no_hp' => 'required|string',
            'orders' => 'required|array',
            'orders.*.id' => 'required|integer',
            'orders.*.name' => 'required|string',
            'orders.*.quantity' => 'required|integer|min:1',
            'orders.*.unit' => 'required|string',
            'orders.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Simpan data pesanan utama
            $pesanan = Pesanan::create([
                'nama_toko' => $validated['nama_toko'],
                'alamat' => $validated['alamat'],
                'no_hp' => $validated['no_hp'],
            ]);

            // Simpan setiap item detail ke tabel pesanan_details
            foreach ($validated['orders'] as $order) {
                PesananDetail::create([
                    'pesanan_id' => $pesanan->id,
                    'nama_barang' => $order['name'],
                    'jumlah' => $order['quantity'],
                    'satuan' => $order['unit'],
                    'harga_satuan' => $order['price'],
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Pesanan berhasil disimpan!',
                'pesanan_id' => $pesanan->id
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Gagal menyimpan pesanan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}