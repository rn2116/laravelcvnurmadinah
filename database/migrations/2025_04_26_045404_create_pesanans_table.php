<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::create('pesanans', function (Blueprint $table) {
        $table->id();
        $table->string('nama_toko');
        $table->string('alamat');
        $table->string('no_hp');
        $table->json('orders'); // Menyimpan data pesanan dalam format JSON
        $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
