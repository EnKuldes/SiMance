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
            $table->string('satuan');
            $table->integer('target');
            $table->integer('bobot');
            $table->integer('realisasi');
            $table->integer('achievement');
            $table->integer('persetasi_bobot');
            // Nilai Acgievments didapat dari Realiasi / Target
            // Persentasi Bobot didapat achievemenst * bobots
            //$table->enum('status',['current','old'])->default('current');
            $table->date('log_date');
            $table->timestamps();
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
