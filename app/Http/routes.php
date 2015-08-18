<?php

Route::get('/', function () {
    return view('home');
});

Route::get('findado', 'FindADOController@index');

Route::get('results', 'ResultsController@index');

Route::group(['prefix' => 'api/v1'], function() {

    /**
     * Locations
     *
     */
    Route::get('locations', 'LocationsController@index');
    Route::get('locations/random', 'LocationsController@random');
    Route::get('locations/search', 'LocationsController@search');
    Route::get('locations/{location}', 'LocationsController@show');


    /**
     * Physicians
     */
    Route::get('physicians/search', 'PhysiciansController@search');


    /**
     * Specialties
     *
     */
    Route::get('specialties/search', 'SpecialtiesController@search');


    /**
     * Testing
     *
     */
    Route::get('test/locations', 'LocationsController@testSearch');

});

Route::get('physicians/{id}', 'PhysiciansController@show');

