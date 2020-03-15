<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        // Insert ke tabel Users
        $data_user = array(
        	array(
	            'name' => 'User CC 147',
	            'username' => 'user_cc147',
	            'password' => bcrypt('u53r_147'),
	            'layanan' => 1,
	            'email' => 'cc147@dummy.com',
	        ),array(
	            'name' => 'User Digital Media',
	            'username' => 'user_dm',
	            'password' => bcrypt('u53r_dm'),
	            'layanan' => 2,
	            'email' => 'dm@dummy.com',
	        ),array(
	            'name' => 'User C4',
	            'username' => 'user_c4',
	            'password' => bcrypt('u53r_c4'),
	            'layanan' => 3,
	            'email' => 'c4@dummy.com',
	        ),array(
	            'name' => 'User My Indihome',
	            'username' => 'user_myIh',
	            'password' => bcrypt('u53r_myIh'),
	            'layanan' => 4,
	            'email' => 'myIh@dummy.com',
	        )
        );
        DB::table('users')->insert($data_user);

        // Insert ke tabel Layanans
        $data_layanan = array(
        	array(
	            'id' => 1,
	            'layanan_desc' => 'CC 147',
	        ),array(
	            'id' => 2,
	            'layanan_desc' => 'Digital Media',
	        ),array(
	            'id' => 3,
	            'layanan_desc' => 'C4',
	        ),array(
	            'id' => 4,
	            'layanan_desc' => 'MyIndiHome',
	        )
        );
        DB::table('layanans')->insert($data_layanan);

        // Insert ke tabel Parameters
        $data_parameter = array(
        	array('parameter_desc' => 'SERVICE LEVEL', 'id_layanan' => 1, 'id' => 1),
        	array('parameter_desc' => 'FCR', 'id_layanan' => 1, 'id' => 2),
        	array('parameter_desc' => 'RASIO SALES', 'id_layanan' => 1, 'id' => 3),
        	array('parameter_desc' => 'CES ( by Customer )', 'id_layanan' => 1, 'id' => 4),
        	array('parameter_desc' => 'Quality Layanan', 'id_layanan' => 1, 'id' => 5),
        	array('parameter_desc' => 'SERVICE LEVEL', 'id_layanan' => 2, 'id' => 6),
        	array('parameter_desc' => 'FCR', 'id_layanan' => 2, 'id' => 7),
        	array('parameter_desc' => 'RASIO SALES', 'id_layanan' => 2, 'id' => 8),
        	array('parameter_desc' => 'CES ( by Customer )', 'id_layanan' => 2, 'id' => 9),
        	array('parameter_desc' => 'Quality Layanan', 'id_layanan' => 2, 'id' => 10),
        	array('parameter_desc' => 'SERVICE LEVEL CONSUME', 'id_layanan' => 3, 'id' => 11),
        	array('parameter_desc' => 'TTR 3 Jam', 'id_layanan' => 3, 'id' => 12),
        	array('parameter_desc' => 'Update Worklog', 'id_layanan' => 3, 'id' => 13),
        	array('parameter_desc' => 'Viral Case', 'id_layanan' => 3, 'id' => 14),
        	array('parameter_desc' => 'Download Apps', 'id_layanan' => 4, 'id' => 15),
        	array('parameter_desc' => 'Monthly Active User', 'id_layanan' => 4, 'id' => 16),
        	array('parameter_desc' => 'Rating Playstore', 'id_layanan' => 4, 'id' => 17),
        	array('parameter_desc' => 'ODS tiket via myIH', 'id_layanan' => 4, 'id' => 18)
        );
        DB::table('parameters')->insert($data_parameter);

        // Insert ke tabel Formulasis
        $data_formula = array(
        	array('formulasi_desc' => 'COF', 'id_parameter' => '1', 'id' => '1'),
            array('formulasi_desc' => 'Call W/ 20 Sec', 'id_parameter' => '1', 'id' => '2'),
            //array('formulasi_desc' => 'Total Incident Logic', 'id_parameter' => '2', 'id' => '3'),
            array('formulasi_desc' => 'Closed by Frontliner', 'id_parameter' => '2', 'id' => '4'),
            array('formulasi_desc' => 'Tiket Logic', 'id_parameter' => '2', 'id' => '5'),
            //array('formulasi_desc' => 'Total Transaksi', 'id_parameter' => '3', 'id' => '6'),
            array('formulasi_desc' => 'Transaksi Add On', 'id_parameter' => '3', 'id' => '7'),
            array('formulasi_desc' => 'Transaksi PSB', 'id_parameter' => '3', 'id' => '8'),
            array('formulasi_desc' => 'CWC REGIS', 'id_parameter' => '3', 'id' => '9'),
            //array('formulasi_desc' => 'Total Responden', 'id_parameter' => '4', 'id' => '10'),
            array('formulasi_desc' => 'Puas', 'id_parameter' => '4', 'id' => '11'),
            array('formulasi_desc' => 'Tidak Puas', 'id_parameter' => '4', 'id' => '12'),
            //array('formulasi_desc' => 'Total Agent', 'id_parameter' => '5', 'id' => '13'),
            array('formulasi_desc' => 'Jumlah Agent OK', 'id_parameter' => '5', 'id' => '14'),
            array('formulasi_desc' => 'Jumlah Agent NOK', 'id_parameter' => '5', 'id' => '15'),
            array('formulasi_desc' => 'Response Time FB', 'id_parameter' => '6', 'id' => '16'),
            array('formulasi_desc' => 'Response Time Twitter', 'id_parameter' => '6', 'id' => '17'),
            array('formulasi_desc' => 'Response Time Email', 'id_parameter' => '6', 'id' => '18'),
            array('formulasi_desc' => 'Response Time LC Wifi.id', 'id_parameter' => '6', 'id' => '19'),
            array('formulasi_desc' => 'Response Time LC IndiHome', 'id_parameter' => '6', 'id' => '20'),
            //array('formulasi_desc' => 'Total Incident Logic', 'id_parameter' => '7', 'id' => '21'),
            array('formulasi_desc' => 'Closed by Frontliner', 'id_parameter' => '7', 'id' => '22'),
            array('formulasi_desc' => 'Total Tiket Logic', 'id_parameter' => '7', 'id' => '23'),
            //array('formulasi_desc' => 'Total Transaksi', 'id_parameter' => '8', 'id' => '24'),
            array('formulasi_desc' => 'Transaksi Add On', 'id_parameter' => '8', 'id' => '25'),
            array('formulasi_desc' => 'Transaksi PSB', 'id_parameter' => '8', 'id' => '26'),
            array('formulasi_desc' => 'CWC REGIS', 'id_parameter' => '8', 'id' => '27'),
            //array('formulasi_desc' => 'Total Responden', 'id_parameter' => '9', 'id' => '28'),
            array('formulasi_desc' => 'Puas', 'id_parameter' => '9', 'id' => '29'),
            array('formulasi_desc' => 'Tidak Puas', 'id_parameter' => '9', 'id' => '30'),
            //array('formulasi_desc' => 'Total Agent', 'id_parameter' => '10', 'id' => '31'),
            array('formulasi_desc' => 'Jumlah Agent OK', 'id_parameter' => '10', 'id' => '32'),
            array('formulasi_desc' => 'Jumlah Agent NOK', 'id_parameter' => '10', 'id' => '33'),
            array('formulasi_desc' => 'WO', 'id_parameter' => '11', 'id' => '34'),
            array('formulasi_desc' => 'Consume / WO', 'id_parameter' => '11', 'id' => '35'),
            array('formulasi_desc' => 'TTR 3 Jam', 'id_parameter' => '12', 'id' => '36'),
            array('formulasi_desc' => 'Total Consume', 'id_parameter' => '13', 'id' => '37'),
            array('formulasi_desc' => 'Total Update Worklog', 'id_parameter' => '13', 'id' => '38'),
            array('formulasi_desc' => 'Total WO Viral Case', 'id_parameter' => '14', 'id' => '39'),
            array('formulasi_desc' => 'Total Consume Viral Case', 'id_parameter' => '14', 'id' => '40'),
            array('formulasi_desc' => 'Download Apps', 'id_parameter' => '15', 'id' => '41'),
            array('formulasi_desc' => 'Monthly Active User', 'id_parameter' => '16', 'id' => '42'),
            array('formulasi_desc' => 'Rating Playstore', 'id_parameter' => '17', 'id' => '43'),
            array('formulasi_desc' => 'ODS tiket via myIH', 'id_parameter' => '18', 'id' => '44')
        );
        DB::table('formulasis')->insert($data_formula);

        // Insert ke KPI
        $data_kpi = array(
            array('id_layanan' => '1', 'id_parameter' => '1', 'satuan' => '%', 'target' => '95', 'bobot' => '20'),
            array('id_layanan' => '1', 'id_parameter' => '2', 'satuan' => '%', 'target' => '90', 'bobot' => '25'),
            array('id_layanan' => '1', 'id_parameter' => '3', 'satuan' => '%', 'target' => '12', 'bobot' => '10'),
            array('id_layanan' => '1', 'id_parameter' => '4', 'satuan' => '%', 'target' => '90', 'bobot' => '25'),
            array('id_layanan' => '1', 'id_parameter' => '5', 'satuan' => '%', 'target' => '2', 'bobot' => '20'),
            array('id_layanan' => '2', 'id_parameter' => '6', 'satuan' => 'Minute', 'target' => '5', 'bobot' => '20'),
            array('id_layanan' => '2', 'id_parameter' => '7', 'satuan' => '%', 'target' => '90', 'bobot' => '25'),
            array('id_layanan' => '2', 'id_parameter' => '8', 'satuan' => '%', 'target' => '15', 'bobot' => '10'),
            array('id_layanan' => '2', 'id_parameter' => '9', 'satuan' => '%', 'target' => '90', 'bobot' => '25'),
            array('id_layanan' => '2', 'id_parameter' => '10', 'satuan' => '%', 'target' => '5', 'bobot' => '20'),
            array('id_layanan' => '3', 'id_parameter' => '11', 'satuan' => '%', 'target' => '98', 'bobot' => '30'),
            array('id_layanan' => '3', 'id_parameter' => '12', 'satuan' => '%', 'target' => '85', 'bobot' => '40'),
            array('id_layanan' => '3', 'id_parameter' => '13', 'satuan' => '%', 'target' => '98', 'bobot' => '20'),
            array('id_layanan' => '3', 'id_parameter' => '14', 'satuan' => '%', 'target' => '98', 'bobot' => '10'),
            array('id_layanan' => '4', 'id_parameter' => '15', 'satuan' => 'Mio', 'target' => '8200000', 'bobot' => '15'),
            array('id_layanan' => '4', 'id_parameter' => '16', 'satuan' => 'Mio', 'target' => '1200000', 'bobot' => '15'),
            array('id_layanan' => '4', 'id_parameter' => '17', 'satuan' => 'Star', 'target' => '4.1', 'bobot' => '30'),
            array('id_layanan' => '4', 'id_parameter' => '18', 'satuan' => '%', 'target' => '98', 'bobot' => '40')
        );
        DB::table('kpi')->insert($data_kpi);
    }
}
