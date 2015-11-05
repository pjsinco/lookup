<?php

Route::get('/', function () {
    return view('home');
});

Route::get('findado', 'FindADOController@index');

Route::get('results', 'ResultsController@index');

Route::get('try-this-one', 'LocationsController@tryThisOne');

Route::group(['prefix' => 'api/v1'], function() {

    /**
     * Authentication
     *
     */
    Route::resource('authenticate', 'AuthenticateController', ['only' => ['index']]);
    Route::post('authenticate', 'AuthenticateController@authenticate');

    /**
     * Locations
     *
     */
    Route::get('locations', 'LocationsController@index');
    Route::get('locations/random', 'LocationsController@random');
    Route::get('locations/search', 'LocationsController@search');
    Route::get('locations/zip', 'LocationsController@searchByZip');
    Route::get('locations/{location}', 'LocationsController@show');


    /**
     * Physicians
     */
    Route::get('physicians/search', 'PhysiciansController@search');
    Route::get('physicians/{id}', 'PhysiciansController@show');


    /**
     * Specialties
     *
     */
    Route::get('specialties', 'SpecialtiesController@index');
    Route::get('specialties/search', 'SpecialtiesController@search');
    Route::get('specialties/{code}', 'SpecialtiesController@show');


    /**
     * Testing
     *
     */
    Route::get('test/locations', 'LocationsController@testSearch');
    Route::get('test/locations', 'LocationsController@withinDistance');
    Route::get('distances', 'PhysiciansController@withinDistance');

});

Route::get('physicians/search', 'PhysiciansController@search');

Route::post('physician', 'PhysiciansController@store');
//Route::get('physician/{id}', 'PhysiciansController@show');
Route::get('physician/{id}/edit', 'PhysiciansController@edit');
Route::patch('physician/{id}', 'PhysiciansController@update');
