<?php

use Illuminate\Database\Seeder;

class add_new_user_admin_to_Users extends Seeder
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
	            'name' => 'Admin',
	            'username' => 'admin',
	            'password' => bcrypt('4dm1n@user'),
	            'layanan' => 1,
	            'email' => 'admin@dummy.com',
	        )
        );
        DB::table('users')->insert($data_user);
    }
}
