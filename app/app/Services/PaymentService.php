<?php

namespace App\Services;

use App\Payment;

class PaymentService
{
	public function __construct()
	{

	}

	public function create(array $param)
	{
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

		$paymentClass = 'App\\Repositories\\'. $paymentProviderType;

		$payment = new Payment(new $paymentClass);

		return $payment->processSale($payment->reformatParam($param));
	}

}