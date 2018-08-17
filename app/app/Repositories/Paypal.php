<?php

namespace App\Repositories;

use App\Interfaces\PaymentInterface;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

Class Paypal implements PaymentInterface
{

	private $_auth;

	private $_curlHandler;

	private $_endpoint;

	private $_data;

	public function __construct()
	{
		$this->_curlHandler = new Client();

		$this->_endpoint = env('PAYPAL_ENDPOINT');
	}

	public function logRecord(array $param)
	{
		
	}

	public function setAuth(array $auth)
	{
		$this->_auth = $auth;
	}

	public function getAuth()
	{
		return array(env('PAYPAL_AUTH_USER'), env('PAYPAL_AUTH_PASS'));
	}

	public function generateParam(array $param)
	{
		$this->setAuth(
			array(
		    env('PAYPAL_AUTH_USER'), 
		    env('PAYPAL_AUTH_PASS')
		));

		$accessToken = $this->generateAccessToken();

		$header = array(
		    'Accept'          => 'application/json',
		    'Authorization'   => 'Bearer '. $accessToken,
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
		                'total' => $param['amount'],
		                'currency' => $param['currency']
		            )
		        )
		    )
		);

		return array(
	            'header' => $header,
	            'body' => $body
	        );
	}

	public function getApprovalUrl()
	{
		$approval_url = '';

		if (! isset($this->_data['saleInfo']) ) return $approval_url;

		foreach($this->_data['saleInfo']['links'] as $item)
		{
			if( strtolower($item['method']) == 'redirect' )
			{
				$approval_url = $item['href'];
				break;
			}
		}

		return $approval_url;
	}

	public function processSale(array $param)
	{
		if( !$this->getAuth() )
		{
			return false;
		}

		try {
			$response = $this->_curlHandler->request('POST', 
            	$this->_endpoint .'/payments/payment', 
            	[ 
             		'headers' => $param['header'],
                 	'json' => $param['body']
            	]);
			
		} catch (GuzzleException $e) {
			echo 'Message: ' .$e->getMessage(); die;
		}

		$jsonResponse = json_decode((string)$response->getBody(), true);

		$this->_data['saleInfo'] = $jsonResponse;

		return $this;
	}

	public function getAccessToken()
	{
		return (isset($this->_data['access_token'])) ? $this->_data['access_token'] : '';
	}

	public function generateAccessToken()
	{
		try {
			$response = $this->_curlHandler->request('POST', 
            	$this->_endpoint .'/oauth2/token', 
            	[ 
             		'headers' => array(
	             		'Accept'          => 'application/json',
	             		'Accept-Language' => 'en_US',
	             	),
            		'auth' => array(env('PAYPAL_AUTH_USER'), env('PAYPAL_AUTH_PASS')),
                 	'form_params' => array(
                 		'grant_type' => 'client_credentials'
                 	)
            	]);
			
		} catch (GuzzleException $e) {
			echo 'Message: ' .$e->getMessage(); die;
		}

		// convert response json to object
		$jsonResponse = json_decode((string)$response->getBody(), true);

		$this->_data['access_token'] = $jsonResponse['access_token'];

		return $this->_data['access_token'];
	}

}