<?php

namespace App\Repositories;
use App\Repositories\InputDataInterface;

Class PaypalDataInput implements InputDataInterface
{

	private $_data = array();

	private $_endpoint;

	public function prepareData(App\Repositories\Paypal $paypal_instance, array $input)
	{
		$this->_endpoint = env('PAYPAL_ENDPOINT');

		$header = array(
		    'Accept'          => 'application/json',
		    'Authorization'   => 'Bearer '. $paypal_instance->generateAccessToken(env('PAYPAL_AUTH_USER'), env('PAYPAL_AUTH_PASS')),
		);

		$body = array(
		    'intent' => 'sale',
		    'redirect_urls' => array(
		        'return_url' => env('PAYPAL_RETURN_URL'),
		        'cancel_url' => env('PAYPAL_CANCEL_URL')
		    ),
		    'payer' => array(
		        'payment_method' => 'paypal'
		    ),
		    'transactions' => array(
		        '0' => array(
		            'amount' => array(
		                'total' => $input['amount'],
		                'currency' => $input['currency']
		            )
		        )
		    )
		);

		$this->_data = array(
			'header' => $header,
			'body' => $body
		);

		return $this;
	}

	public function getDataInput()
	{
		return $this->_data;
	}

}