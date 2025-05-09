<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PesananController;
use App\Http\Controllers\Api\BarangController;
use App\Http\Middleware\Cors;
use App\Http\Controllers\Api\NotaController;


// Endpoint untuk cek API aktif
Route::get('/ping', function () {
    return response()->json(['message' => 'API aktif!']);
});

Route::middleware([Cors::class])->group(function () {
    Route::get('/barang', [BarangController::class, 'index']);
    Route::post('/barang', [BarangController::class, 'store']);
    Route::put('/barang/{id}', [BarangController::class, 'update']); 
    Route::delete('/barang/{id}', [BarangController::class, 'destroy']); 
    Route::post('/pesanan', [PesananController::class, 'store']);
    Route::get('/barang/{id}/check-stock', [BarangController::class, 'checkStock']);
    Route::get('/pesanan', [PesananController::class, 'index']);
    Route::delete('/pesanan/{id}', [PesananController::class, 'destroy']);
    Route::put('/pesanan/{id}', [PesananController::class, 'update']);
    Route::put('/pesanan/{id}/status', [PesananController::class, 'updateStatus']);
    Route::get('/pesanan/{id}/nota', [PesananController::class, 'showNota']);
    Route::get('/nota/{id}', [NotaController::class, 'show']);
});

