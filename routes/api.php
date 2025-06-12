<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\PesananController;
use App\Http\Controllers\Api\NotaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Middleware\Cors;
use App\Models\Barang;
use App\Models\User;
use App\Models\Pesanan;


// Ping endpoint untuk cek API aktif
Route::get('/ping', function () {
    return response()->json(['message' => 'API aktif!']);
});


// =========================
// 🔓 AUTH - Tanpa login
// =========================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// ================================
// 🌐 CORS Middleware Group (Public)
// ================================


// ==============================
// 🔐 Authenticated User Routes
// ==============================
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // HANYA user login yang boleh memesan barang
    Route::post('/pesanan', [PesananController::class, 'store']);
    Route::get('/pesanan/me', [PesananController::class, 'getMyOrders']);
    Route::get('/pesanan/{id}', [PesananController::class, 'show']);

     // BARANG
    Route::get('/barang', [BarangController::class, 'index']);
    Route::post('/barang', [BarangController::class, 'store']);
    Route::put('/barang/{id}', [BarangController::class, 'update']);
    Route::delete('/barang/{id}', [BarangController::class, 'destroy']);
    Route::get('/barang/{id}/check-stock', [BarangController::class, 'checkStock']);
    Route::get('/barang/{id}', [BarangController::class, 'show']);
    Route::middleware('auth:sanctum')->get('/admin/dashboard', function () {
        return response()->json([
            'total_produk' => Barang::count(),
            'total_user' => User::count(),
            'total_pesanan' => Pesanan::count(),
        ]);
    });
    Route::middleware('auth:sanctum')->get('/admin/users', [AuthController::class, 'showAllUsers']);
    Route::get('/pesanan', [PesananController::class, 'index']);
    Route::put('/pesanan/{id}', [PesananController::class, 'update']);
    Route::put('/pesanan/{id}/status', [PesananController::class, 'updateStatus']);
    Route::delete('/pesanan/{id}', [PesananController::class, 'destroy']);
    Route::get('/pesanan/{id}/nota', [PesananController::class, 'showNota']);

    // NOTA
    Route::get('/nota/{id}', [NotaController::class, 'show']);
});





