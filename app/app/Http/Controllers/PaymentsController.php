<?php

namespace App\Http\Controllers;

//use Braintree_Transaction;
//use Braintree_Customer;

use Illuminate\Http\Request;

//use App\Paypal as Paypal;
//use App\Braintree as Braintree;

use App\Payment;
use App\Repositories\Paypal;
use App\Repositories\Braintree;


class PaymentsController extends Controller
{
	private $_payment;

	public function payment_process(Request $request)
	{
		$param = $request->input();

		$currency = strtoupper($param['currency']);
		$cardNumber = $param['card_number'];

		// Validate card
		if ( !luhn_check($cardNumber) ) {
			throw new Exception('Invalid Card');
		}

		$paymentProviderType = getPaymentProvider(
			array('cardNumber' => $cardNumber, 'currencyType' => $currency),
			array('USD', 'EUR', 'AUD')
		);

		// Generate sting to create object
		$class = 'App\\Repositories\\'. $paymentProviderType;

		$paymentProvider = new $class;


		$this->_payment = new Payment($paymentProvider);
		$res = $this->_payment->processSale($this->_payment->reformatParam($param));

		if( $res === true )
		{
			$data['success'] = $res;
			return response()->view('success', $data, 200);
		}

		return redirect($paymentProvider->getApprovalUrl());

	}

}
