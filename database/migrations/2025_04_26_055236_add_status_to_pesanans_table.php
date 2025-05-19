<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToPesanansTable extends Migration
{
    public function up()
    {
        Schema::table('pesanans', function (Blueprint $table) {
            if (!Schema::hasColumn('pesanans', 'status')) {
                $table->string('status')->default('pending');
            }
            if (!Schema::hasColumn('pesanans', 'total_harga')) {
                $table->decimal('total_harga', 10, 2)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('pesanans', function (Blueprint $table) {
            if (Schema::hasColumn('pesanans', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('pesanans', 'total_harga')) {
                $table->dropColumn('total_harga');
            }
        });
    }
}
