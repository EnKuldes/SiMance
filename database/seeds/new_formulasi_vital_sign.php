<?php

use Illuminate\Database\Seeder;

class new_formulasi_vital_sign extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data_formula = array(
            array('formulasi_desc' => 'LAPUL', 'id_parameter' => 1, 'id' => 1),
        	array('formulasi_desc' => 'GAUL', 'id_parameter' => 2, 'id' => 2),
        	array('formulasi_desc' => 'Media Case', 'id_parameter' => 3, 'id' => 3),
        	array('formulasi_desc' => 'Total Tiket', 'id_parameter' => 4, 'id' => 4),
        	array('formulasi_desc' => 'LAPUL', 'id_parameter' => 5, 'id' => 5),
        	array('formulasi_desc' => 'GAUL', 'id_parameter' => 6, 'id' => 6),
        	array('formulasi_desc' => 'Viral Case', 'id_parameter' => 7, 'id' => 7),
        	array('formulasi_desc' => 'Total Tiket', 'id_parameter' => 8, 'id' => 8),
        );
        DB::table('formulasi_vital_sign')->insert($data_formula);
    }
}
