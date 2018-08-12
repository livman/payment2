<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class Paypal extends Model
{
	private $_client;

	private $_endpoint;

	private $_data;

	protected $table = 'paypal';

	public function __construct($isSandbox = 1)
	{
		$this->_client = new Client();

		$this->_endpoint = ($isSandbox == 1) ? 'https://api.sandbox.paypal.com/v1' : 'https://api.sandbox.paypal.com/v1';
	}

	protected function getAccessToken($auth = array())
	{
		try {
			$response = $this->_client->request('POST', 
            	$this->_endpoint .'/oauth2/token', 
            	[ 
             		'headers' => array(
	             		'Accept'          => 'application/json',
	             		'Accept-Language' => 'en_US',
	             	),
            		'auth' => $auth,
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

		return $this;
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


	public function processSale($param = array())
	{

		$this->getAccessToken($param['auth']);

		$body = array(
 			'intent' => 'sale',
 			'redirect_urls' => array(
 				'return_url' => 'http://br4ndon.online:8777/return_url.php',
 				'cancel_url' => 'http://br4ndon.online:8777/cancel_url.php'
 			),
 			'payer' => array(
 				'payment_method' => 'paypal'
 			),
 			'transactions' => array(
 				'0' => array(
 					'amount' => array(
 						'total' => $param['checkoutInfo']['amount'],
 						'currency' => $param['checkoutInfo']['currency']
 					)
 				)
 			)
 		);

		try {
			$response = $this->_client->request('POST', 
            	$this->_endpoint .'/payments/payment', 
            	[ 
             		'headers' => array(
	             		'Accept'          => 'application/json',
	             		'Authorization'   => 'Bearer '. $this->_data['access_token'],
	             	),
                 	'json' => $body
            	]);
			
		} catch (GuzzleException $e) {
			echo 'Message: ' .$e->getMessage(); die;
		}

		$jsonResponse = json_decode((string)$response->getBody(), true);

		$this->_data['saleInfo'] = $jsonResponse;

		return $this;

	}
    
}
