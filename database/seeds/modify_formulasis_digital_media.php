<?php

use Illuminate\Database\Seeder;

class modify_formulasis_digital_media extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('formulasis')->where('id_parameter', '6')->update(['is_enabled' => '0']);
        DB::table('formulasis')->updateOrInsert(
        	['formulasi_desc' => 'Traffic', 'id_parameter' => '6'],
        	['is_enabled' => '1']
        );
        DB::table('formulasis')->updateOrInsert(
        	['formulasi_desc' => 'Response Time', 'id_parameter' => '6'],
        	['is_enabled' => '1']
        );
    }
}
