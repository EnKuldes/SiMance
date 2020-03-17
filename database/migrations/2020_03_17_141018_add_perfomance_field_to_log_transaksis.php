<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPerfomanceFieldToLogTransaksis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_transaksis', function (Blueprint $table) {
            $table->integer('perfomance')->default(0)->after('persetasi_bobot');
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
            $table->dropColumn('perfomance');
        });
    }
}
