<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BarangController;
use App\Http\Middleware\Cors;
use App\Http\Controllers\Api\PesananController;

// Endpoint untuk pengecekan API aktif
Route::get('/ping', function () {
    return response()->json(['message' => 'API Laravel aktif.']);
});

// Apply middleware Cors untuk semua API route yang membutuhkan CORS
Route::middleware([Cors::class])->group(function () {
    // Route untuk GET dan POST /barang
    Route::get('/barang', [BarangController::class, 'index']);
    Route::post('/barang', [BarangController::class, 'store']);
});

// Route untuk menerima pesanan menggunakan POST
Route::post('/pesanan', [PesananController::class, 'store']);
