<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImageToBarangTable extends Migration
{
    public function up()
    {
        Schema::table('barangs', function (Blueprint $table) {
            if (!Schema::hasColumn('barangs', 'image')) {
                $table->string('image')->nullable()->after('price');
            }
        });
    }

    public function down()
    {
        Schema::table('barangs', function (Blueprint $table) {
            if (Schema::hasColumn('barangs', 'image')) {
                $table->dropColumn('image');
            }
        });
    }
}
