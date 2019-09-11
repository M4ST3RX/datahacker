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
Route::get('/logout', function(){
    Auth::logout();
    return Redirect::route('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('api')->group(function(){
    Route::post('terminal', 'APIController@terminal');

    Route::post('taskmanager/delete', 'APIController@task_manager_delete');
    Route::post('taskmanager/finish', 'APIController@task_manager_finish');
    Route::get('taskmanager/all', 'APIController@task_manager_all');

    Route::get('computer/files', 'APIController@getFiles');

    Route::get('history/list', 'APIController@getHistoryList');
    Route::post('history/add', 'APIController@addToHistoryList');
});

Route::prefix('computer')->group(function(){
    Route::get('selector', 'ComputerController@selector');
    Route::get('play', 'ComputerController@play')->name('computer.play');
    Route::get('configure', 'ComputerController@configure')->name('computer.configure');
});
