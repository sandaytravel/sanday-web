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
    Route::group(['prefix' => 'merchant'], function () {
        Route::get('/', function () {
            dd('This is the Merchant module index page. Build something great!');
        });
        Route::get('/dashboard', 'MerchantController@dashboard')->name('merchantdashboard');
        // Update Merchant Profile
        Route::any('/update-profile/{id}', 'MerchantController@updateProfile')->name('update-profile');
        // Change Password
        Route::any('/change-password', 'MerchantController@changePassMerchant')->name('mchange-password');
        // My bookings
        Route::get('/bookings', 'MerchantController@bookingList')->name('booking-list');
        // Search bookings
        Route::any('/search-bookings', 'MerchantController@searchBookingList')->name('search-bookings');
        // Confirm Booking
        Route::get('/confirm-booking/{id}', 'MerchantController@confirmBooking')->name('confirm-booking');
        // Cancel Booking
        Route::get('/cancel-booking/{id}', 'MerchantController@cancelBooking')->name('cancel-booking');
        // View Booking
        Route::post('/view-booking', 'MerchantController@viewBooking')->name('view-booking');
    });
});
