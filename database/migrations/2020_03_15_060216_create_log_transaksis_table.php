<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_transaksis', function (Blueprint $table) {
            $table->id();
            $table->integer('layanan')->unsigned()->change();
            // $table->foreign('layanan')->references('id')->on('layanans')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('parameter')->unsigned()->change();
            // $table->foreign('parameter')->references('id')->on('parameters')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('formulasi')->unsigned()->change();
            // $table->foreign('formulasi')->references('id')->on('formulasis')->onDelete('cascade')->onUpdate('cascade');
            $table->string('satuan');
            $table->integer('target');
            $table->integer('bobot');
            $table->enum('status',['current','old'])->default('current');
            $table->date('log_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_transaksis');
    }
}
