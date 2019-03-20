<?php

//定义路由
Route::group(['middleware' => ['web']], function () {
	Route::get('authorize/callback', 'App\Http\Controllers\SsoClientController@callback');
	Route::get('logout', 'App\Http\Controllers\SsoClientController@logout');
});