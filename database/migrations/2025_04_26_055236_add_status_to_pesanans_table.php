<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToPesanansTable extends Migration
{
    public function up()
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->string('status')->default('pending'); // Menambahkan kolom status
            $table->decimal('total_harga', 10, 2)->nullable(); // Menambahkan kolom total harga
        });
    }

    public function down()
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('total_harga');
        });
    }
}
