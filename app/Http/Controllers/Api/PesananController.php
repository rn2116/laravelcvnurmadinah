<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $pesanan = Pesanan::with('details')->paginate($perPage);

        return response()->json($pesanan);
    }

    public function store(Request $request)
    {
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

        DB::beginTransaction();

        try {
            $pesanan = Pesanan::create([
                'nama_toko' => $validated['nama_toko'],
                'alamat' => $validated['alamat'],
                'no_hp' => $validated['no_hp'],
                'orders' => json_encode($validated['orders']),
            ]);

            foreach ($validated['orders'] as $order) {
                $barang = Barang::findOrFail($order['id']);

                if ($barang->stock < $order['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'message' => "Stok barang '{$barang->name}' tidak cukup.",
                    ], 400);
                }

                $barang->decrement('stock', $order['quantity']);

                $pesanan->details()->create([
                    'barang_id' => $barang->id,
                    'nama_barang' => $order['name'],
                    'harga' => $order['price'],
                    'jumlah' => $order['quantity'],
                    'satuan' => $order['unit'],
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Pesanan berhasil disimpan.',
                'pesanan' => $pesanan->load('details'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menyimpan pesanan.',
                'error' => $e->getMessage(),
            ], 500);
        }
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

    public function show($id)
{
    $pesanan = Pesanan::find($id);

    if (!$pesanan) {
        return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
    }

    return response()->json($pesanan);
}


}