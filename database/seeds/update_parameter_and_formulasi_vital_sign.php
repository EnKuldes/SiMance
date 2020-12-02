<?php

use Illuminate\Database\Seeder;

class update_parameter_and_formulasi_vital_sign extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// Disable Formulasii dan Parameter Total Tiket
        DB::table('parameter_vital_sign')->where('id', '4')->update(['is_enabled' => '0']);
        DB::table('parameter_vital_sign')->where('id', '8')->update(['is_enabled' => '0']);
        DB::table('formulasi_vital_sign')->where('id', '4')->update(['is_enabled' => '0']);
        DB::table('formulasi_vital_sign')->where('id', '8')->update(['is_enabled' => '0']);

        $data_formula = array(
            array('formulasi_desc' => 'Total Tiket', 'id_parameter' => 1, 'id' => 9),
            array('formulasi_desc' => 'Total Tiket', 'id_parameter' => 2, 'id' => 10),
            array('formulasi_desc' => 'Total Tiket', 'id_parameter' => 5, 'id' => 11),
            array('formulasi_desc' => 'Total Tiket', 'id_parameter' => 6, 'id' => 12),
        );
        DB::table('formulasi_vital_sign')->insert($data_formula);

    }
}
