<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        return response()->json(Barang::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'stock' => 'required|integer',
            'price' => 'required|integer',
            'image' => 'nullable|string', // gambar opsional
        ]);

        $barang = Barang::create($validated);

        return response()->json([
            'message' => 'Barang berhasil ditambahkan',
            'data' => $barang
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'stock' => 'sometimes|integer',
            'price' => 'sometimes|integer',
            'image' => 'nullable|string',
        ]);

        $barang->update($validated);

        return response()->json([
            'message' => 'Barang berhasil diperbarui',
            'data' => $barang
        ], 200);
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return response()->json([
            'message' => 'Barang berhasil dihapus'
        ], 200);
    }

    public function checkStock($id)
    {
        // Cari barang berdasarkan ID
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'message' => 'Barang tidak ditemukan.'
            ], 404);
        }

        // Mengembalikan stok barang
        return response()->json([
            'message' => 'Stok barang ditemukan.',
            'data' => [
                'id' => $barang->id,
                'name' => $barang->name,
                'stock' => $barang->stock,
            ]
        ], 200);
    }
}