<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnomalyNoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anomaly_note', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('id_layanan');
            $table->tinyInteger('id_parameter');
            $table->tinyInteger('id_formulasi');
            $table->date('tanggal');
            $table->longText('note');
            $table->string('user_input');
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
        Schema::dropIfExists('anomaly_note');
    }
}
