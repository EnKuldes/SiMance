<?php

use Illuminate\Database\Seeder;

class new_formulasis_digital_media_rasio_sales_parameters extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data_formula = array(
        	array('formulasi_desc' => 'Migrasi Paket', 'id_parameter' => '8', 'id' => '50'),
            array('formulasi_desc' => 'Upgrade Speed', 'id_parameter' => '8', 'id' => '51'),
        );
        DB::table('formulasis')->insert($data_formula);
    }
}
