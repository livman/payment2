<?php

namespace App\Repositories;

use App\Interfaces\PaymentInterface;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use App\Repositories\PaypalDataInput;
use App\Repositories\PaypalPrepareService;

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
			$response = $service->getCurlHandle()->request('POST', 
            	$dataInputInstance->getService()->getEndPointActionPayment(), $dataInputInstance->getDataInput());
			
		} catch (GuzzleException $e) {
			echo 'Message: ' .$e->getMessage(); die;
		}

		$jsonResponse = json_decode((string)$response->getBody(), true);

		$this->_data['saleInfo'] = $jsonResponse;

		return $this;
	}

	

}