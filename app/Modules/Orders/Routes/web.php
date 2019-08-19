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
        // List of all categories
        Route::get('/orders', 'OrdersController@index')->name('Orders');
        Route::any('/orders/search', 'OrdersController@searchOrder')->name('searchOrder');
        Route::any('/orders/orderstatus/{id}/{status}', 'OrdersController@orderStatusUpdate')->name('orderStatusUpdate');
        //Order View Details
        Route::get('/orders/View/{id}', 'OrdersController@orderView')->name('orderview');
});