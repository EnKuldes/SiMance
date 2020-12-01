<?php

use Illuminate\Database\Seeder;

class update_parameter_and_kpi_C4 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('parameters')->where('id', '12')->update(['parameter_desc' => 'ODS']);
        DB::table('formulasis')->where('id', '36')->update(['formulasi_desc' => 'ODS']);
        DB::table('kpi')->where('id', '12')->update(['target' => 92, 'bobot' => 35]);
    }
}
