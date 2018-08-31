<?php

namespace App\Repositories;

use App\Interfaces\PaymentInterface;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use App\Repositories\PaypalDataInput;

Class Paypal implements PaymentInterface
{

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

	public function processSale(PaypalDataInput $dataInputInstance)
	{
		try {
			$response = $this->_curlHandler->request('POST', 
            	$this->_endpoint .'/payments/payment', $dataInputInstance->getDataInput());
			
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

	public function generateAccessToken($user, $pass)
	{
		try {
			$response = $this->_curlHandler->request('POST', 
            	$this->_endpoint .'/oauth2/token', 
            	[ 
             		'headers' => array(
	             		'Accept'          => 'application/json',
	             		'Accept-Language' => 'en_US',
	             	),
            		'auth' => array($user, $pass),
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