<?php

use Illuminate\Database\Seeder;

class new_formulasis_digital_media extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data_formula = array(
        	array('formulasi_desc' => 'Response Time FB Inbox', 'id_parameter' => '6', 'id' => '45'),
            array('formulasi_desc' => 'Response Time Twitter DM', 'id_parameter' => '6', 'id' => '46'),
            array('formulasi_desc' => 'Response Time Instagram', 'id_parameter' => '6', 'id' => '47'),
        );
        DB::table('formulasis')->insert($data_formula);
    }
}
