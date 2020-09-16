<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// use DB;

class CopyDailyTransaksisTableToDailyTransaksisJustifikasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE TABLE daily_transaksis_justifikasi LIKE daily_transaksis');
        DB::statement('INSERT daily_transaksis_justifikasi SELECT * FROM daily_transaksis');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_transaksis_justifikasi');
    }
}
