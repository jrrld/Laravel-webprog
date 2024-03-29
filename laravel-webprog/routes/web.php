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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::group(['prefix' => 'admin'], function(){
	Route::get('/','AdminController@index')->name('admin.index');

	Route::resource('province', 'ProvinceController');
	Route::get('/province-table/', 'ProvinceController@table')->name('province.table');
    Route::get('/province/{id}/barangays', 'ProvinceController@showBarangays')->name('province.showBarangays');
	// Route::get('/province-table/{id}', 'ProvinceController@tableProvince')->name('province.tableProvince');

	Route::resource('municipality', 'MunicipalityController');
	Route::get('/municipality-table/', 'MunicipalityController@table')->name('municipality.table');
	Route::get('/municipality-table/{id}', 'MunicipalityController@tableProvince')->name('official.tableProvince');

	Route::resource('barangay', 'BarangayController');
	Route::get('/barangay-table/', 'BarangayController@table')->name('barangay.table');
	Route::get('/barangay-table/{id}', 'BarangayController@tableProvince')->name('barangay.tableProvince');
	Route::get('/barangay/query/{province}/{municipality}', 'BarangayController@tableMunicipality')->name('barangay.tableMunicipality');
	Route::get('/barangay/query/{province}/{municipality}/{barangay}', 'BarangayController@tableBarangay')->name('barangay.tableBarangay');

	Route::resource('destination', 'DestinationController');
	Route::get('/destination/create/{province}', 'DestinationController@getMunicipality')->name('destination.getMunicipality');
	Route::get('/destination/create/{province}/{municipality}', 'DestinationController@getBarangay')->name('destination.getBarangay');

	Route::get('/destination/query/{province}/{municipality}', 'DestinationController@tableMunicipality')->name('destination.tableMunicipality');
	Route::get('/destination/query/{province}/{municipality}/{barangay}', 'DestinationController@tableBarangay')->name('destination.tableBarangay');

	Route::get('/destination-table/', 'DestinationController@table')->name('destination.table');
	Route::get('/destination-table/{id}', 'DestinationController@tableProvince')->name('destination.tableProvince');

	Route::resource('official', 'OfficialController');
	Route::get('/official-table/', 'OfficialController@table')->name('official.table');
	Route::get('/official-table/{id}', 'OfficialController@tableProvince')->name('official.tableProvince');

	Route::resource('event', 'EventController');
	Route::get('/event-table/', 'EventController@table')->name('event.table');
	Route::get('/event-table/{id}', 'EventController@tableProvince')->name('event.tableProvince');
	Route::get('/event/query/{province}/{municipality}', 'EventController@tableMunicipality')->name('event.tableMunicipality');

	Route::resource('article', 'Articlecontroller');
	Route::get('/article-table/', 'Articlecontroller@table')->name('article.table');
	Route::get('/article-table/{id}', 'Articlecontroller@tableProvince')->name('article.tableProvince');
	Route::get('/article/query/{province}/{municipality}', 'Articlecontroller@tableMunicipality')->name('article.tableMunicipality');

});
Route::get('/shit',function(){
	return "Earl";
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => '/'], function(){
	Route::get('/','WebController@index')->name('home');
	Route::get('destinations','WebController@destinations')->name('destinations');
	Route::get('provinces','WebController@provinces')->name('provinces');
	Route::get('gallery','WebController@gallery')->name('gallery');
	Route::get('about','WebController@about')->name('about');
	Route::get('updates','WebController@updates')->name('updates');
	Route::get('events','WebController@events')->name('events');
});


