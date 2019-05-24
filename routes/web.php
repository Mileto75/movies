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

//get routes
Route::get('/', 'MoviesController@toonFilms')->name('toonFilms');
Route::get('/editMovie/{movieId}','MoviesController@editMovie')->name('editMovie');
Route::get('/newMovie','MoviesController@newMovie')->name('newMovie');
Route::get('/deleteMovie/{movieId}','MoviesController@deleteMovie')->name('deleteMovie');


//DB test routes
Route::get('/testModels','MoviesController@modelTester')->name('modelTester');
Route::get('/testQueryBuilder','MoviesController@queryBuilderTester')->name('qbuilderTester');
//Route::get('/home', 'HomeController@index')->name('home');


//authenticatie routes
Auth::routes();


//post routes
route::post('/updateMovie','MoviesController@updateMovie')->name('updateMovie');
route::post('/insertMovie','MoviesController@insertMovie')->name('insertMovie');