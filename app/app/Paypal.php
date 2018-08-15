<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Session;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class Paypal extends Model
{
	private $_client;

	private $_header;

	private $_auth;

	private $_endpoint;

	private $_data;

	protected $table = 'paypal';

	public function __construct()
	{
		$this->_client = new Client();

		$this->_endpoint = env('PAYPAL_ENDPOINT');
	}

	public function setAuth($auth = array())
	{
		$this->_auth = $auth;
	}

	public function getAuth()
	{
		return $this->_auth;
	}

	public function getAccessToken(Paypal $paypal)
	{
		return (isset($paypal->_data['access_token'])) ? $paypal->_data['access_token'] : '';
	}

	public function generateAccessToken(Paypal $paypal)
	{
		try {
			$response = $paypal->_client->request('POST', 
            	$paypal->_endpoint .'/oauth2/token', 
            	[ 
             		'headers' => array(
	             		'Accept'          => 'application/json',
	             		'Accept-Language' => 'en_US',
	             	),
            		'auth' => $paypal->_auth,
                 	'form_params' => array(
                 		'grant_type' => 'client_credentials'
                 	)
            	]);
			
		} catch (GuzzleException $e) {
			echo 'Message: ' .$e->getMessage(); die;
		}

		// convert response json to object
		$jsonResponse = json_decode((string)$response->getBody(), true);

		$paypal->_data['access_token'] = $jsonResponse['access_token'];

		//Session::put('paypal_access_token', $paypal->_data['access_token']);

		return $paypal;
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

	public function getPaymentInfoUrl(Paypal $paypal)
	{
		$paymentInfo_url = '';

		if (! isset($paypal->_data['saleInfo']) ) return $paymentInfo_url;

		foreach($paypal->_data['saleInfo']['links'] as $item)
		{
			if( strtolower($item['method']) == 'get' )
			{
				$paymentInfo_url = $item['href'];
				break;
			}
		}

		return $paymentInfo_url;
	}

	public function getPaymentInfo(Paypal $paypal)
	{

		try {
			$response = $paypal->_client->request('GET', 
            	$paypal->getPaymentInfoUrl(), 
            	[ 
             		'headers' => array(
	             		'Accept'          => 'application/json',
	             		'Authorization'   => 'Bearer '. $this->_data['access_token'],
	             	)
            	]);
			
		} catch (GuzzleException $e) {
			echo 'Message: ' .$e->getMessage(); die;
		}

		
		return json_decode((string)$response->getBody(), true);

	}

	public function setCurlHeader(Paypal $paypal, $header = array())
	{
		$paypal->_header = $header;

		return true;
	}

	public function getCurlHeader(Paypal $paypal)
	{
		return (isset($paypal->_header)) ? $paypal->_header : array();
	}

	public function processSale(Paypal $paypal, $param = array())
	{
		if( !$paypal->getAuth() )
		{
			return false;
		}

		try {
			$response = $paypal->_client->request('POST', 
            	$paypal->_endpoint .'/payments/payment', 
            	[ 
             		'headers' => $param['header'],
                 	'json' => $param['body']
            	]);
			
		} catch (GuzzleException $e) {
			echo 'Message: ' .$e->getMessage(); die;
		}

		$jsonResponse = json_decode((string)$response->getBody(), true);

		$paypal->_data['saleInfo'] = $jsonResponse;

		return $paypal;

	}
    
}
