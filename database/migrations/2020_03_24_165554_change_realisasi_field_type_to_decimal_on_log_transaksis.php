<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRealisasiFieldTypeToDecimalOnLogTransaksis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_transaksis', function (Blueprint $table) {
            $table->decimal('realisasi', 8, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_transaksis', function (Blueprint $table) {
            $table->dropColumn('realisasi');
        });
    }
}
