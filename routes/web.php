<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
# Disable karna sudah di pindahkan ke Auth Login
/*Route::get('/login-1', function () {
    return view('login');
});*/

# Disable
/*Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/cc-147', function () {
    return view('cc-147');
});

Route::get('/digital-media', function () {
    return view('digital-media');
});

Route::get('/c4', function () {
    return view('c4');
});

Route::get('/myindihome', function () {
    return view('myindihome');
});
*/
# Global COntroller?
Route::get('/', 'HomeController@index')->name('home');

Auth::routes([
	'register' => false, // Registration Routes...
	'reset' => false, // Password Reset Routes...
	'verify' => false, // Email Verification Routes...
	'confirm' => false, // Password Routes...
]);

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/dashboard', 'HomeController@index')->name('dashboard');
Route::get('/dashboard-verifikasi', 'HomeController@dashboard_verifikasi')->name('dashboard-verifikasi');
Route::get('/performance-system-verifikasi', 'HomeController@performance_system_verifikasi')->name('performance-system-verifikasi');
Route::get('/cc-147', 'HomeController@cc_147')->name('cc-147');
Route::get('/digital-media', 'HomeController@digital_media')->name('digital-media');
Route::get('/c4', 'HomeController@c4')->name('c4');
Route::get('/myindihome', 'HomeController@myindihome')->name('myindihome');
Route::get('/daily_admin', 'HomeController@daily_admin')->name('admin');
Route::get('/report', 'HomeController@report')->name('admin');
Route::post('/daily/save', 'HomeController@save_daily_input')->name('save_daily_input');
// Chain
Route::post('/list-parameter', 'HomeController@list_parameter')->name('chain');
Route::post('/list-formulasi', 'HomeController@list_formulasi')->name('chain');
Route::post('/list-date', 'HomeController@list_date')->name('chain');
//
Route::post('/get-current-kpi', 'HomeController@get_kpi')->name('get_information');
Route::post('/get-monthly-data', 'HomeController@get_monthly_data')->name('get_information');
Route::post('/get-perfomance-comparison', 'HomeController@get_perfomance_comparation')->name('get_information');
Route::post('/get-realisasi-monthly', 'HomeController@get_realisasi_monthly')->name('get_information');
Route::post('/get-kpi-information-progress', 'HomeController@get_kpi_information_progress')->name('get_information');
Route::post('/get-summary-layanan', 'HomeController@get_summary_layanan')->name('get_information');
Route::post('/get-list-anomaly', 'HomeController@get_list_anomaly')->name('get_information');
Route::post('/get-perfomance-system-verifikasi-layanan', 'HomeController@get_perfomance_system_verifikasi_layanan')->name('get_information');

// Tes PHPOffice/PHPWord
Route::get('generate-docx', 'HomeController@generateDocx');
