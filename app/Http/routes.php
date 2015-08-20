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
    Route::get('locations/zip', 'LocationsController@searchByZip');
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
    Route::get('specialties', 'SpecialtiesController@index');


    /**
     * Testing
     *
     */
    Route::get('test/locations', 'LocationsController@testSearch');

    Route::get('text/locations', 'LocationsController@withinDistance');

});

Route::get('physicians/{id}', 'PhysiciansController@show');

Route::get('test/mssql', function() {

    $link = mssql_connect('sql05-1.aoanet.local', 'psinco_ro', 'Read5Only');
     
    if (!$link)
        die('Unable to connect!');
     
    if (!mssql_select_db('imis', $link))
        die('Unable to select database!');
     
    $result = mssql_query("SELECT full_name, address_1 FROM imis.dbo.vfindado WHERE last_name like 'Hub%'");
     
    while ($row = mssql_fetch_array($result)) {
        var_dump($row);
    }
     
    mssql_free_result($result);

});


