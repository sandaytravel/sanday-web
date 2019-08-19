<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
    // Ignores notices and reports all other kinds... and warnings
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    // error_reporting(E_ALL ^ E_WARNING); // Maybe this is enough
}
// Load webview for payment
Route::get('/makepayment/{id}', 'Apiv1Controller@makePayment')->name('makepayment');
/* IPN Notify */
Route::post('payment/ipnNotify', 'Apiv1Controller@ipnNotify');
// Load webview for bookig voucher
Route::get('/voucher/{id}', 'Apiv1Controller@viewVoucher')->name('voucher');
/* Return Url */
Route::any('curltest', 'Auth\LoginController@curlTest')->name('curltest');

Route::get('/', 'Auth\LoginController@showLoginForm')->name('loginform');
Route::post('/postlogin', 'Auth\LoginController@postLogin')->name('postlogin');

Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/thankyou', function (){
    return view('thankyou');
})->name('thankyou');

Route::get('/forgotpass', function () {
    return view('auth.forgotpassword');
})->name('forgotpass');

Route::post('/postforgotpassword', 'Auth\LoginController@postForgotPassword')->name('postForgotPassword');

Route::get('/resetpassword/{id}', 'Auth\LoginController@showResetPasswordForm')->name('resetpasswordform');

Route::post('/postresetpassword', 'Auth\LoginController@postResetPassword')->name('postresetpassword');

Route::get('vendor/register', 'Auth\RegisterController@registerVendor')->name('registerVendor');

/*------Merchant login -----------*/
Route::get('/merchant/login', 'Auth\LoginController@showMerchantLoginForm')->name('merchantloginform');
//Mercahnt post login 
Route::post('/merchant/postlogin' , 'Auth\LoginController@merchantPostLogin')->name('merchantpostlogin');
//Mercahnt Forgot password 
Route::get('/merchant/forgotpass' , function (){
        return view('auth.merchantforgotpassword');
})->name('merchantforgotpassword');
//Merchant forgot paasword post
Route::post('/merchant/forgotpass', 'Auth\LoginController@postMerchantForgotPassword')->name('postMerchantForgotPassword');

//Merchant rest password
Route::get('/merchant/resetpassword/{id}', 'Auth\LoginController@mercahntResetPasswordForm')->name('mercahntresetpasswordform');
//Merchant rest password post
Route::post('/merchant/postresetpassword', 'Auth\LoginController@merchantpostResetPassword')->name('merchantpostResetPassword');
//Merchant logout 
Route::get('/merchant/logout', 'Auth\LoginController@merchantLogout')->name('merchantlogout');
/*------End Merchant login -----------*/
Auth::routes();


Route::get('/home', 'HomeController@index')->name('home');