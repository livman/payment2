<?php

namespace App\Repositories;

use App\Interfaces\PaymentInterface;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use App\Interfaces\InputDataInterface;
use App\Repositories\PaypalPrepareService;

Class Paypal implements PaymentInterface
{

	private $_data;

	public function __construct()
	{

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

	public function processSale(InputDataInterface $dataInputInstance)
	{
		$param = $dataInputInstance->getDataInput();
		print_r($param); die;
		try {
			$response = $dataInputInstance->getService()->getCurlHandle()->request('POST', 
            	$dataInputInstance->getService()->getEndPointActionPayment(), 
            	[
            		'header' => $param['header'],
            		'json' => $param['body']
            	]);
			
		} catch (GuzzleException $e) {
			echo 'Message: ' .$e->getMessage(); die;
		}

		print_r($response); die;

		$jsonResponse = json_decode((string)$response->getBody(), true);

		$this->_data['saleInfo'] = $jsonResponse;

		return $this;
	}

	

}