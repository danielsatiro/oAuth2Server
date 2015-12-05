<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Grupo de rotas protegidas
Route::group(['prefix' => 'api/v1', 'middleware' => 'oauth'], function() {
	//Rotas protegidas
	Route::get('users', 'UsersController@index');
	Route::get('users/{id}', 'UsersController@show');
});

//Grupo de rotas publicas
Route::group(['prefix' => 'api/v1'], function() {
	//Rota publica de teste
	Route::get('users-publico', 'UsersController@index');
	//Rota para pegar um access token
	Route::post('oauth/access-token', function(){
		return Response::json(Authorizer::issueAccessToken());
	});
});

Route::get('/', function () {
    return view('welcome');
});
