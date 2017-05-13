<?php

Auth::routes();
Route::get('home', 'Auth\LoginController@redirectAfterLogin');
Route::get('logout', 'Auth\LoginController@logout');
Route::get('manage/heyCheck', 'Front\FrontController@heyCheck');
Route::get('convert' , 'Front\TestController@convert');
Route::get('updater/{id}' , 'Front\TestController@updater');
//Route::get('postsConverter', 'Front\TestController@postsConverter');

//Route::group([
//	//'prefix' => "manage",
//	'middleware' => ['auth'],
//], function() {
//	Route::get('/' , 'HomeController@index');
//});

Route::group([
	'prefix'     => "manage",
	'middleware' => ['auth'],// 'is:admin'],
	'namespace'  => "Manage",
], function () {
	Route::get('/', 'HomeController@index');
	Route::get('/index', 'HomeController@index');
	Route::get('/account', 'HomeController@account');
	Route::post('/password', 'HomeController@changePassword');

	/*-----------------------------------------------
	| Accounts ...
	*/
	Route::group(['prefix' => "accounts", /*'middleware' => "can:accounts",*/], function () {
		Route::get('/update/{item_id}', 'AccountsController@update');
		Route::get('browse/{request_tab?}/{user_id?}', 'AccountsController@browse');
		Route::get('/act/{model_id}/{action}/{option?}', 'AccountsController@singleAction');
		Route::get('/create/{user_id}' , 'AccountsController@create');
		Route::group(['prefix' => 'save'], function () {
			Route::post('/' , 'AccountsController@save');
			Route::post('/delete', 'AccountsController@delete');
			Route::post('/undelete', 'AccountsController@undelete');
		});

	});


	/*-----------------------------------------------
	| Users ...
	*/
	Route::group(['prefix' => "users", 'middleware' => "can:users",], function () {
		Route::get('/update/{item_id}', 'UsersController@update');
		Route::get('browse/{role}/search/{keyword?}', 'UsersController@search');
		Route::get('browse/{role}/{request_tab?}', 'UsersController@browse');
		Route::get('create/{role}', 'UsersController@create');
		Route::get('/act/{model_id}/{action}/{option?}', 'UsersController@singleAction');

		Route::group(['prefix' => 'save'], function () {
			Route::post('/', 'UsersController@save');
			Route::post('/password', 'UsersController@savePassword');
			Route::post('/permits', 'UsersController@savePermits');
			Route::post('/role', 'UsersController@saveRole');
			Route::post('/delete', 'UsersController@delete');
			Route::post('/undelete', 'UsersController@undelete');
			Route::post('/destroy', 'UsersController@destroy');
		});
	});

	/*-----------------------------------------------
	| Settings ...
	*/
	Route::group(['prefix' => 'settings', 'middleware' => 'can:super'], function () {
		Route::get('/', 'SettingsController@index');
		Route::get('/tab/{request_tab?}', 'SettingsController@index');
		Route::get('/search', 'SettingsController@search');
		Route::get('/update/{model_id}', 'SettingsController@update');
		Route::get('/act/{model_id}/{action}/{option?}', 'SettingsController@singleAction');

		Route::group(['prefix' => 'save'], function () {
			Route::post('/', 'SettingsController@save');
			Route::post('/posttype', 'SettingsController@savePosttypeDownstream');
		});
	});


	/*-----------------------------------------------
	| Upstream ...
	*/
	Route::group(['prefix' => 'upstream', 'middleware' => 'is:developer'], function () {
		Route::get('/{request_tab?}', 'UpstreamController@index');
		Route::get('/{request_tab}/search/', 'UpstreamController@search');
		Route::get('/edit/{request_tab?}/{item_id?}/{parent_id?}', 'UpstreamController@editor');
		Route::get('/{request_tab}/{item_id}/{parent_id?}', 'UpstreamController@item');

		Route::group(['prefix' => 'save'], function () {
			Route::post('role', 'UpstreamController@saveRole');
			Route::post('state', 'UpstreamController@saveProvince');
			Route::post('city', 'UpstreamController@saveCity');
			Route::post('posttype', 'UpstreamController@savePosttype');
			Route::post('department', 'UpstreamController@saveDepartment');
			Route::post('category', 'UpstreamController@saveCategory');
			Route::post('downstream', 'UpstreamController@saveDownstream');
			Route::post('package', 'UpstreamController@savePackage');
			Route::post('login_as', 'UpstreamController@loginAs');
		});
	});
});

