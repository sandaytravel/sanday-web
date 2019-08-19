<?php

use Illuminate\Http\Request;

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */
Route::group(['middleware' => 'api'], function() {
    // Normal Registration
    Route::post('/v1/customer_registration', 'Apiv1Controller@customerRegistration');
    // Normal Login
    Route::post('/v1/login', 'Apiv1Controller@simpleLogin');
    // Facebook Login
    Route::post('/v1/facebooklogin', 'Apiv1Controller@facebookLogin');
    // Change Password
    Route::post('/v1/change_password', 'Apiv1Controller@changePassword');
    // Forgot Password
    Route::post('/v1/forgotpassword', 'Apiv1Controller@forgotPassword');
    // Locations Listing
    Route::post('/v1/destinations', 'Apiv1Controller@getDestination');
    // View City
    Route::get('/v1/viewcity', 'Apiv1Controller@viewCity');
    //View Activity
    Route::get('/v1/viewactivity', 'Apiv1Controller@viewActivity');
    //Activity Review
    Route::get('/v1/activityreview', 'Apiv1Controller@activityReview');
    //Activity Review
    Route::post('/v1/reviewactivity', 'Apiv1Controller@reviewActivity');
    //update Profile image
    Route::post('/v1/updateprofilepic', 'Apiv1Controller@updateProfilepic');
    //update Profile detail
    Route::post('/v1/updateprofile', 'Apiv1Controller@updateProfile');
    //Logout
    Route::get('/v1/logout', 'Apiv1Controller@logout');
    // List Activity
    Route::post('/v1/activitylist', 'Apiv1Controller@getActivityList');
    // Get Explore
    Route::get('/v1/explore', 'Apiv1Controller@explore');
    // Add to Cart
    //Route::get('/v1/addtocart', 'Apiv1Controller@getCart');
    // List Get about us 
    Route::get('/v1/about-us', 'Apiv1Controller@getAboutus');
    // Add or remove Whishlist 
    Route::post('/v1/add_remove_whishlist', 'Apiv1Controller@addDeleteWhishlist');
    // List Whishlist 
    Route::get('/v1/whishlist', 'Apiv1Controller@wishList');
    // Add to Cart
    Route::post('/v1/addtocart', 'Apiv1Controller@addToCart');
    // Edit to Cart
    Route::post('/v1/editcart', 'Apiv1Controller@editCart');
    // Delete to Cart
    Route::post('/v1/deletecart', 'Apiv1Controller@deleteCart');
    // View to Cart
    Route::get('/v1/viewcart', 'Apiv1Controller@viewCart');
    // Place Order 
    Route::post('/v1/placeorder', 'Apiv1Controller@placeOrder');
    // View Order
    Route::get('/v1/vieworder', 'Apiv1Controller@viewOrder');
    // City or category search terms
    Route::post('/v1/search', 'Apiv1Controller@CityCategorySearch');
    // Get Profile Countries
    Route::get('/v1/getcountrylist', 'Apiv1Controller@countryList');
    //Customer notification
    Route::get('/v1/customernotification', 'Apiv1Controller@customerNotification');
    /*
    **
    *Merchant api start
    */

    // Merchant Login
    Route::post('/v1/merchantlogin', 'Apiv1Controller@merchantLogin');
    // Merchant Login
    Route::get('/v1/bookingnotification', 'Apiv1Controller@bookingNotification');
    // Merchant booking list
    Route::post('/v1/merchantbooking','Apiv1Controller@merchantBookinglist');
    // Merchant booking confrimation or cancelled
    Route::post('/v1/bookingstatus','Apiv1Controller@merchantBookingStatus');
    // Merchant booking Sales report
    Route::post('/v1/merchantbookingreport','Apiv1Controller@bookingSalesReport');
    // Merchant Activity List
    Route::post('/v1/merchantactivitylist','Apiv1Controller@merchantActivityList');
    // Categories List 
    Route::get('/v1/categorieslist', 'Apiv1Controller@categoriesList');
    // Add Booking Note by Merchant
    Route::post('/v1/add-booking-note', 'Apiv1Controller@addBookingNote');
    // Check Payment Status
    Route::post('/v1/checkpaymentstatus', 'Apiv1Controller@checkPaymentStatus');
});
