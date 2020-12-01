<?php

use Illuminate\Database\Seeder;

class new_paramater_vital_sign extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert ke tabel Parameters
        $data_parameter = array(
        	array('parameter_desc' => 'LAPUL', 'id_layanan' => 1, 'id' => 1),
        	array('parameter_desc' => 'GAUL', 'id_layanan' => 1, 'id' => 2),
        	array('parameter_desc' => 'Media Case', 'id_layanan' => 1, 'id' => 3),
        	array('parameter_desc' => 'Total Tiket', 'id_layanan' => 1, 'id' => 4),
        	array('parameter_desc' => 'LAPUL', 'id_layanan' => 2, 'id' => 5),
        	array('parameter_desc' => 'GAUL', 'id_layanan' => 2, 'id' => 6),
        	array('parameter_desc' => 'Viral Case', 'id_layanan' => 2, 'id' => 7),
        	array('parameter_desc' => 'Total Tiket', 'id_layanan' => 2, 'id' => 8),
        	
        );
        DB::table('parameter_vital_sign')->insert($data_parameter);
    }
}
