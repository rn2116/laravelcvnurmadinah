<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesananDetailsTable extends Migration
{
    public function up()
{
    Schema::create('pesanan_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pesanan_id')->constrained()->onDelete('cascade');
        $table->foreignId('barang_id')->constrained()->onDelete('cascade');
        $table->string('nama_barang');
        $table->decimal('harga', 10, 2);
        $table->integer('jumlah');
        $table->string('satuan');
        $table->timestamps();
    });
}


    public function down()
    {
        Schema::dropIfExists('pesanan_details');
    }
}
