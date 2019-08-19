<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | This file is where you may define all of the routes that are handled
  | by your module. Just tell Laravel the URIs it should respond
  | to using a Closure or controller method. Build something great!
  |
 */

Route::group(['middleware' => 'auth'], function () {
    // Country Listing
    Route::get('/locations', 'LocationsController@locations')->name('locations');
    // Search Location
    Route::any('/location/search', 'LocationsController@searchLocation')->name('search_location');
    // Check continent exists or not
    Route::post('/continent/exists', 'LocationsController@continentExists')->name('continent_exists');
    // Add Countinent
    Route::any('/countitnent/add', 'LocationsController@addContinent')->name('addcontinent');
    // Update Countinent
    Route::post('/countitnent/update', 'LocationsController@updateContinent')->name('updatecontinent');
    // Delete Countinent
    Route::get('/countitnent/delete/{id}', 'LocationsController@deleteContinent')->name('deletecontinent');
    // Check country exists or not
    Route::post('/country/exists', 'LocationsController@countryExists')->name('country_exists');
    // Add Country
    Route::any('/country/add', 'LocationsController@addCountry')->name('addcountry');
    // Add Country
    Route::post('/country/update', 'LocationsController@updateCountry')->name('updatecountry');
    // Delete Country
    Route::get('/country/delete/{id}', 'LocationsController@deleteCountry')->name('deletecountry');
    // Add City
    Route::any('/city/add', 'LocationsController@addCity')->name('addcity');
    // Edit City
    Route::any('/city/edit/{id}', 'LocationsController@editCity')->name('editcity');
    // Delete City
    Route::get('/city/delete/{id}', 'LocationsController@deleteCity')->name('deletecity');
});
