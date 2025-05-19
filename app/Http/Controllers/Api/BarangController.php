<?php

namespace App\Http\Controllers\Api;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class BarangController extends Controller
{
    // Menampilkan daftar barang
    public function index()
    {
        $barangs = Barang::all();

        foreach ($barangs as $barang) {
            $barang->image_url = $barang->image ? asset('storage/images/' . $barang->image) : null;
        }

        return response()->json($barangs);
    }

    // Simpan data barang baru beserta gambar
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $validatedData['image'] = $filename;
        }

        $barang = Barang::create($validatedData);

        return response()->json($barang, 201);
    }

    // Update data barang dan gambar jika ada
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'harga' => 'sometimes|required|numeric',
            'stock' => 'sometimes|required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($barang->image && Storage::disk('public')->exists('images/' . $barang->image)) {
                Storage::disk('public')->delete('images/' . $barang->image);
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $validatedData['image'] = $filename;
        }

        $barang->update($validatedData);

        return response()->json($barang);
    }

    // Hapus data barang dan gambar
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);

        if ($barang->image && Storage::disk('public')->exists('images/' . $barang->image)) {
            Storage::disk('public')->delete('images/' . $barang->image);
        }

        $barang->delete();

        return response()->json(null, 204);
    }

    // Mengecek stok barang
    public function checkStock($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'message' => 'Barang tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'message' => 'Stok barang ditemukan.',
            'data' => [
                'id' => $barang->id,
                'name' => $barang->name,
                'stock' => $barang->stock,
                'image_url' => $barang->image ? asset('storage/images/' . $barang->image) : null,
            ]
        ], 200);
    }

    // Mengurangi stok jika barang dipesan
    
}
