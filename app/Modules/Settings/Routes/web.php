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
    // Setting email templates
    Route::get('settings/emailtemplate', 'SettingsController@emailTemplates')->name('emailtemplate');
    // Edit Email Template
    Route::get('settings/emailtemplate/edit/{id}', 'SettingsController@editEmailTemplate')->name('editemailtemplate');
    // Update Email Template Settings
    Route::post('settings/emailtemplate/update', 'SettingsController@updateEmailTemplate')->name('updateemailtemplate');
    // General Policy
    Route::get('settings/general-policy', 'SettingsController@generalPolicy')->name('general_policy');
    // Add Policy
    Route::post('policy/add', 'SettingsController@addPolicy')->name('addpolicy');
    // Updated Policy
    Route::post('policy/update', 'SettingsController@updatePolicy')->name('updatepolicy');
    // Delete Policy
    Route::get('/policy/delete/{id}', 'SettingsController@deletePolicy')->name('deletepolicy');
    // About  us
    Route::get('settings/about-us', 'SettingsController@aboutUs')->name('aboutUs');

    Route::post('settings/about-us/update', 'SettingsController@updateAboutus')->name('updateAboutus');
    // Explore
    Route::get('settings/explore', 'SettingsController@explore')->name('explore');

    Route::post('settings/explore/update/{id}', 'SettingsController@updateExplore')->name('updateexplore');
    // Upload Explore Images
    Route::post('setting/uploadexploreimage', 'SettingsController@uploadExploreImage')->name('uploadexploreimage');
    // Remove Explore Image
    Route::post('setting/removeexploreimage', 'SettingsController@removeExploreImage')->name('removeexploreimage');
});
