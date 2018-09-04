<?php

namespace App\Repositories;
use App\Interfaces\InputDataInterface;
use App\Repositories\PaypalPrepareService;

Class PaypalDataInput implements InputDataInterface
{

	private $_data = array();

	private $_service;


	public function __construct()
	{
		$this->_service = new PaypalPrepareService();
	}

	public function prepareData(array $input)
	{
		$header = array(
		    'Accept'          => 'application/json',
		    'Authorization'   => 'Bearer '. $this->_service->getAccessToken(),
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

	public function getService()
	{
		return $this->_service;
	}

	public function getDataInput()
	{
		return $this->_data;
	}

}