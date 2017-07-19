<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/',    		['as' => 'home', 	'uses'	=> 'PagesController@home']);

Route::get("obras/get_random_obra","ObrasController@getRandomObra");
Route::resource('/obras', "ObrasController");

Route::get('comentarios/novas_threads', "ComentariosController@getNewThreads");
Route::get('comentarios/atualizar_threads', "ComentariosController@updateCommentsCount");