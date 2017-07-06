<?php

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

Route::get('/', function () {
    return view('welcome');
});
Route::group(['prefix' => 'admin'], function(){

	Route::resource('province', 'ProvinceController');
	Route::get('/province-table/', 'ProvinceController@table')->name('province.table');

	Route::resource('municipality', 'MunicipalityController');
	Route::get('/municipality-table/', 'MunicipalityController@table')->name('municipality.table');

	Route::resource('barangay', 'BarangayController');
	Route::get('/barangay-table/', 'BarangayController@table')->name('barangay.table');

	Route::resource('destination', 'DestinationController');
	Route::get('/destination-table/', 'DestinationController@table')->name('destination.table');

	Route::resource('official', 'OfficialController');
	Route::get('/official-table/', 'OfficialController@table')->name('official.table');


});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
