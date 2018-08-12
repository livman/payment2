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
    return view('braintreeForm');
});

Route::get('/payment/process', 'PaymentsController@process')->name('payment.process');
Route::post('/payment/payment_process', 'PaymentsController@payment_process')->name('payment.payment_process');
Route::get('/payment/paypal', 'PaymentsController@paypal');

Route::get('/payment', function () {

	$checkoutSession = session('checkout', array());

	if ( $checkoutSession['currency'] == 'USD' )
	{
		return view('braintreeForm');
	}
	elseif ( $checkoutSession['currency'] == 'THB' ) 
	{
		return redirect()->action('PaymentsController@paypal');
		
		//return view('paypalForm');
	}

    
});

Route::get('/validation', 'ValidationController@showform');
Route::post('/validation', 'ValidationController@validateform');


Route::get('/checkout', 'CheckoutController@showform');
Route::post('/checkout', 'CheckoutController@process');
