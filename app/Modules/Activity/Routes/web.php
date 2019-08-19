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
    // List of activities
    Route::get('/activity', 'ActivityController@index')->name('activity');
    // Search activity
    Route::any('/activity/search', 'ActivityController@searchActivity')->name('searchactivity');
    // Search Category and location activity 
    Route::any('/activity/cat-location-search', 'ActivityController@catLocationserach')->name('catlocationserach');
    // Add activity
    Route::any('/activity/add', 'ActivityController@addActivity')->name('addactivity');
    // Update activity
    Route::any('/activity/update/{id}', 'ActivityController@editActivityPolicy')->name('updateactivity');
    // Get Subcategories
    Route::post('/activity/getsubcategory', 'ActivityController@getSubcategories')->name('getsubcategory');
    // Get Categories from country
    Route::post('/activity/getcategory', 'ActivityController@getCategories')->name('getcategory');
    // Add Activity Policy
    Route::post('/activity/addpolicy', 'ActivityController@addActivityPolicy')->name('addactivitypolicy');
    // Check General Policy Exists
    Route::post('/activitypolicy/exists', 'ActivityController@checkPolicyExists')->name('checkpolicyexists');
    // Change Activity Status
    Route::put('/activity/changestatus', 'ActivityController@changeStatus')->name('changeactivitystatus');
    // Delete Activity
    Route::get('/activity/delete/{id}', 'ActivityController@deleteActivity')->name('deleteactivity');
    // Delete multiple Activity
    Route::post('/activity/multipledelete', 'ActivityController@multipleDelete')->name('multipledeleteactivity');
    // Multiple activity status active inactive
    Route::post('/activity/multipstatus', 'ActivityController@multipleStatus')->name('multiplestatusactivity');
    // Remove Activity Package
    Route::post('/activity/removepacakage', 'ActivityController@removePackage')->name('remove_activity_package');
    // Set package configuration
    Route::any('/activity/set-package-configuration/{id}', 'ActivityController@setPackageConfiguration')->name('set_package_configuration');
    // Remove Package Quantity
    Route::post('/activity/removepacakagequantity', 'ActivityController@removePackageQuantity')->name('remove_package_quantity');
   //admin approve or decline activity #endregion
   Route::any('/activity/approvedecline/{id}/{approvedecline}','ActivityController@approveDeclineActivity')->name('approvedeclineactivity');
});
