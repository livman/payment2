<?php

namespace App\Repositories;

use App\Interfaces\PrepareServiceInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

Class PaypalPrepareService implements PrepareServiceInterface
{
	//private $_endpoint = env('PAYPAL_ENDPOINT');

	//private $_user = env('PAYPAL_AUTH_USER');

	//private $_pass = env('PAYPAL_AUTH_PASS');

	private $_curlHandler;

	private $_endpoint_access_token;

	private $_endpoint_payment;

	private $_access_token;

	public function __construct()
	{
		$this->_curlHandler = new Client();

		$this->_endpoint_access_token = env('PAYPAL_ENDPOINT') .'/oauth2/token';

		$this->_endpoint_payment = env('PAYPAL_ENDPOINT') .'/payments/payment';

		$this->generateAccessToken();
	}

	public function getCurlHandle()
	{
		return $this->_curlHandler;
	}

	public function getEndPointRequestAccessToken()
	{
		return $this->_endpoint_access_token;
	}

	public function getEndPointActionPayment()
	{
		return $this->_endpoint_payment;
	}

	public function prepareService()
	{
		return $this;
	}

	public function getAccessToken()
	{
		return (isset($this->_access_token)) ? $this->_access_token : '';
	}

	public function generateAccessToken()
	{
		try {
			$response = $this->_curlHandler->request('POST', 
            	$this->_endpoint_access_token, 
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

		$this->_access_token = $jsonResponse['access_token'];
	}	

}