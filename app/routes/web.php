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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/payment/process', 'PaymentsController@process')->name('payment.process');

Route::get('/validation', 'ValidationController@showform');
Route::post('/validation', 'ValidationController@validateform');


Route::get('/checkout', 'CheckoutController@showform');
Route::post('/checkout', 'CheckoutController@process');
