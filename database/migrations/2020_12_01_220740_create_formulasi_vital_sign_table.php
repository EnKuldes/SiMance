<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormulasiVitalSignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formulasi_vital_sign', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('id_parameter');
            $table->string('formulasi_desc');
            $table->enum('is_enabled',[0,1])->default(1);
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
        Schema::dropIfExists('formulasi_vital_sign');
    }
}
