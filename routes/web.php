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
Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
Route::get('/cc-147', 'HomeController@cc_147')->name('cc-147');
Route::get('/digital-media', 'HomeController@digital_media')->name('digital-media');
Route::get('/c4', 'HomeController@c4')->name('c4');
Route::get('/myindihome', 'HomeController@myindihome')->name('myindihome');