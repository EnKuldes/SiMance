<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_transaksis', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('id_layanan');
            $table->tinyInteger('id_parameter');
            $table->tinyInteger('id_formulasi');
            $table->decimal('nilai', 8, 2);
            $table->date('tanggal');
            $table->string('user_input');
            # created_at sebagai tanggal nya
            $table->timestamps();
            //$table->unique(['id_layanan', 'id_parameter', 'id_formulasi', 'tanggal'], 'unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_transaksis');
    }
}
