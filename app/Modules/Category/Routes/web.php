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
    Route::get('/categories', 'CategoryController@index')->name('categories');
    // Search Category/Subcategory
    Route::any('/category/search', 'CategoryController@searchCategory')->name('searchcategory');
    // Add Category
    Route::any('/category/add', 'CategoryController@addCategory')->name('addcategory');
    // Category Exists
    Route::post('/category/exists', 'CategoryController@isCategoryExists')->name('categoryexists');
    // Edit Category
    Route::any('/category/update', 'CategoryController@updateCategory')->name('updatecategory');
    // Delete Category
    Route::get('/category/delete/{id}', 'CategoryController@deleteCategory')->name('deletecategory');
    // Add Subcategory
    Route::any('/subcategory/add', 'CategoryController@addSubcategory')->name('addsubcategory');
    // Edit Subcategory
    Route::any('/subcategory/update', 'CategoryController@updateSubcategory')->name('updatesubcategory');
    // Subcategory Exists
    Route::post('/subcategory/exists', 'CategoryController@isSubcategoryExists')->name('subcategoryexists');
    // Delete Subcategory
    Route::get('/subcategory/delete/{id}', 'CategoryController@deleteSubcategory')->name('deletesubcategory');
    
});
