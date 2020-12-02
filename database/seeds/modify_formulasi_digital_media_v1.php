<?php

use Illuminate\Database\Seeder;

class modify_formulasi_digital_media_v1 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('formulasis')->where('id_parameter', '6')->update(['is_enabled' => '1']);
        DB::table('formulasis')->whereIn('id', ['49','45', '46'])->update(['is_enabled' => '0']);
        DB::table('formulasis')->where('id', '16')->update(['formulasi_desc' => 'Response Time FB']);
        DB::table('formulasis')->where('id', '17')->update(['formulasi_desc' => 'Response Time Twitter']);
        DB::table('formulasis')->updateOrInsert(
        	['formulasi_desc' => 'Response Time Whatsapp', 'id_parameter' => '6'],
        	['is_enabled' => '1']
        );
    }
}
