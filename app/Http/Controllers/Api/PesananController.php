<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_toko' => 'required|string',
            'alamat' => 'required|string',
            'no_hp' => 'required|string',
            'orders' => 'required|array',
            'orders.*.id' => 'required|integer',
            'orders.*.name' => 'required|string',
            'orders.*.price' => 'required|numeric',
            'orders.*.quantity' => 'required|integer',
            'orders.*.unit' => 'required|string',
        ]);

        DB::beginTransaction(); // Mulai transaksi

        try {
            // Simpan pesanan ke tabel pesanan
            $pesanan = Pesanan::create([
                'nama_toko' => $validated['nama_toko'],
                'alamat' => $validated['alamat'],
                'no_hp' => $validated['no_hp'],
                'orders' => json_encode($validated['orders']),
            ]);

            // Kurangi stok untuk setiap barang yang dipesan
            foreach ($validated['orders'] as $order) {
                $barang = Barang::find($order['id']);

                if ($barang) {
                    // Kurangi stok
                    $barang->decrement('stock', $order['quantity']);

                    // Opsional: kalau mau cek jangan sampai stok minus
                    if ($barang->stock < 0) {
                        // Rollback kalau ada masalah
                        DB::rollBack();
                        return response()->json([
                            'message' => "Stok barang '{$barang->name}' tidak cukup.",
                        ], 400);
                    }
                }
            }

            DB::commit(); // Commit transaksi
            return response()->json([
                'message' => 'Pesanan berhasil diterima!',
                'pesanan_id' => $pesanan->id,
                'data' => $validated
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback kalau ada error
            return response()->json([
                'message' => 'Terjadi kesalahan saat memproses pesanan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {
        // Mendapatkan parameter halaman dari request (default 10 item per halaman)
        $perPage = $request->get('per_page', 10); // default 10

        $pesanan = Pesanan::paginate($perPage); // Menggunakan pagination

        return response()->json([
            'message' => 'Daftar pesanan',
            'data' => $pesanan
        ]);
    }


    public function destroy($id)
    {
        $pesanan = Pesanan::find($id);

        if (!$pesanan) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan.'
            ], 404);
        }

        $pesanan->delete();

        return response()->json([
            'message' => 'Pesanan berhasil dihapus.'
        ]);
    }

    public function update(Request $request, $id)
    {
        $pesanan = Pesanan::find($id);

        if (!$pesanan) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan.'
            ], 404);
        }

        $validated = $request->validate([
            'nama_toko' => 'sometimes|required|string',
            'alamat' => 'sometimes|required|string',
            'no_hp' => 'sometimes|required|string',
            'orders' => 'sometimes|required|array',
            'orders.*.id' => 'required_with:orders|integer',
            'orders.*.name' => 'required_with:orders|string',
            'orders.*.price' => 'required_with:orders|numeric',
            'orders.*.quantity' => 'required_with:orders|integer',
            'orders.*.unit' => 'required_with:orders|string',
        ]);

        $pesanan->update([
            'nama_toko' => $validated['nama_toko'] ?? $pesanan->nama_toko,
            'alamat' => $validated['alamat'] ?? $pesanan->alamat,
            'no_hp' => $validated['no_hp'] ?? $pesanan->no_hp,
            'orders' => isset($validated['orders']) ? json_encode($validated['orders']) : $pesanan->orders,
        ]);

        return response()->json([
            'message' => 'Pesanan berhasil diupdate!',
            'data' => $pesanan
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $pesanan = Pesanan::find($id);

        if (!$pesanan) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan.'
            ], 404);
        }

        $validated = $request->validate([
            'status' => 'required|string|in:pending,processing,completed,canceled',
        ]);

        $pesanan->status = $validated['status'];
        $pesanan->save();

        return response()->json([
            'message' => 'Status pesanan berhasil diperbarui!',
            'data' => $pesanan
        ]);
    }

}