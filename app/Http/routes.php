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

Route::get('/', function () {
    return view('findado.index');
});


Route::get('/findado', 'FindADOController@index');

Route::group(['prefix' => 'api/v1'], function() {

    Route::get('locations/random', 'LocationsController@random');
    Route::get('locations/{location}', 'LocationsController@show');

});
