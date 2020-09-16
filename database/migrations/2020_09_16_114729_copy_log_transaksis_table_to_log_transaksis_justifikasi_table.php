<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// use DB;

class CopyLogTransaksisTableToLogTransaksisJustifikasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE TABLE log_transaksis_justifikasi LIKE log_transaksis');
        DB::statement('INSERT log_transaksis_justifikasi SELECT * FROM log_transaksis');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_transaksis_justifikasi');
    }
}
