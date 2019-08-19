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
    Route::get('/dashboard', 'UsersController@dashboard')->name('dashboard');
    // List of users
    Route::get('systemusers', 'UsersController@index')->name('users');
    // Search  user
    Route::any('systemusers/search', 'UsersController@search')->name('search');
    // Add user
    Route::any('systemusers/add', 'UsersController@add')->name('adduser');
    // Edit user
    Route::any('systemusers/edit/{id}', 'UsersController@edit')->name('edituser');

    Route::post('systemusers/update/{id}', 'UsersController@update')->name('update');
    // Delete user
    Route::get('systemusers/delete/{id}', 'UsersController@delete')->name('deleteuser');
    // Delete multiple user
    Route::post('systemusers/multipledelete', 'UsersController@multipleDelete')->name('multipledelete');
    // Change user status
    Route::put('systemusers/changestatus', 'UsersController@changeStatus')->name('changestatus');
    // Check already exist user
    Route::post('systemusers/exists', 'UsersController@exists')->name('exists');
    // Check email already exist
    Route::post('systemusers/emailexists', 'UsersController@emailExists')->name('emailexists');
    // Change User Password
    Route::post('systemusers/changepassword', 'UsersController@changepassword')->name('changepassword');

    // List of all customers
    Route::get('customers', 'UsersController@customers')->name('customers');
    // Search  customer
    Route::any('customer/search', 'UsersController@searchCustomer')->name('searchcustomer');
    // Delete Customer
    Route::get('customer/delete/{id}', 'UsersController@deleteCustomer')->name('deletecustomer');
    //View customer
    Route::get('customer/view/{id}', 'UsersController@viewCustomer')->name('viewcustomer');
    // //View customers orders
    // Route::get('customer/vieworders/{id}', 'UsersController@viewCustomerOrders')->name('viewcustomerorders');
    //Merchant get list 
    Route::get('merchantusers', 'UsersController@listMerchant')->name('merchantlist');
    //Merchant get Search 
    Route::any('merchant/search', 'UsersController@serachMerchant')->name('searchmerchant');
    // Merchant Add get
    Route::get('merchant/add', 'UsersController@addMerchant')->name('getmerchant');
    // Merchant Create form post
    Route::post('merchant/add', 'UsersController@addMerchant')->name('addmerchant');
    // Edit Merchant user 
    Route::get('merchant/edit/{id}', 'UsersController@editMerchant')->name('editmerchant');
    // update Merchant user 
    Route::post('merchant/update/{id}', 'UsersController@editMerchant')->name('updatemerchant');
    // Delete Merchant user 
    ROute::get('merchant/delete/{id}', 'UsersController@deleteMerchant')->name('deletemerchant');
    // Change Merchant user status
    //Merchant Change paasword post
    // Route::post('/merchant/forgotpass', 'Auth\LoginController@postMerchantForgotPassword')->name('postMerchantForgotPassword');
    Route::post('/merchant/changepassword', 'UsersController@changePassMerchant')->name('postchangepasswordmerchant');
    //Mercahnt Change password 
    Route::get('/merchant/changepassword', 'UsersController@changePassMerchant')->name('changepasswordmerchant');

    Route::put('merchantuser/changestatus', 'UsersController@merchantChangeStatus')->name('merchantchangestatus');
    // Change/Create Merchant Password by Admin
    Route::post('merchantuser/createpassword', 'UsersController@createPassword')->name('createpassword');
});

//Merchant create password
Route::any('merchant/create-password/{id}', 'UsersController@merchantCreatePassword')->name('merchant-create-password');
