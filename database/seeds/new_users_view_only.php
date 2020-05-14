<?php

use Illuminate\Database\Seeder;

class new_users_view_only extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data_user = array(
        	array(
	            'name' => 'View CC 147',
	            'username' => 'view_cc147',
	            'password' => bcrypt('u53r_cc147'),
	            'layanan' => 1,
	            'level' => 0,
	            'email' => 'view_cc147@dummy.com',
	        )
	        , array(
	            'name' => 'View Digital Media',
	            'username' => 'view_dm',
	            'password' => bcrypt('u53r_dm'),
	            'layanan' => 2,
	            'level' => 0,
	            'email' => 'view_dm@dummy.com',
	        )
	        , array(
	            'name' => 'View C4',
	            'username' => 'view_c4',
	            'password' => bcrypt('u53r_c4'),
	            'layanan' => 3,
	            'level' => 0,
	            'email' => 'view_c4@dummy.com',
	        )
	        , array(
	            'name' => 'View My Indihome',
	            'username' => 'view_myIh',
	            'password' => bcrypt('u53r_myIh'),
	            'layanan' => 4,
	            'level' => 0,
	            'email' => 'view_myIh@dummy.com',
	        )
        );
        DB::table('users')->insert($data_user);
    }
}
