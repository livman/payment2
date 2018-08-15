<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Paypal;

class PaypalTest extends TestCase
{
	private $_paypal;

	public function setUp()
    {
      //Initialize the test case
      $this->_paypal = new Paypal();
    }

    public function testGenerateAccessToken()
    {
    	$this->_paypal->setAuth(array(
			'AZlu1oYTlPMRpATYdqUlTZzPEMVn-PgHQsCC-uauXR-tZ3GDYL8gZCzAZaGAMmHCADHlWKGD5XCmZ7zQ', 
			'EFgGUwexWJDqmDUUzJFlUGRiyHq48FcT9IgQI2iD4Jb9GovBfNXpFPCfe2dDWTLHgCNAd2j5dJUqgD54'
		));

    	$this->assertNotNull($this->_paypal->generateAccessToken($this->_paypal));
    }

    public function testGetAccessToken()
    {
    	$this->assertNotNull($this->_paypal->getAccessToken($this->_paypal));
    }


    public function testGetCurlHeader()
    {
    	$this->_paypal->setAuth(array(
			'AZlu1oYTlPMRpATYdqUlTZzPEMVn-PgHQsCC-uauXR-tZ3GDYL8gZCzAZaGAMmHCADHlWKGD5XCmZ7zQ', 
			'EFgGUwexWJDqmDUUzJFlUGRiyHq48FcT9IgQI2iD4Jb9GovBfNXpFPCfe2dDWTLHgCNAd2j5dJUqgD54'
		));
    	$this->_paypal->generateAccessToken($this->_paypal);

    	$res = $this->_paypal->setCurlHeader(
    		$this->_paypal,
    		array(
         		'Accept'          => 'application/json',
         		'Authorization'   => 'Bearer '. $this->_paypal->getAccessToken($this->_paypal),
         	)
    	);

    	$this->assertArrayHasKey('Accept', $this->_paypal->getCurlHeader($this->_paypal));
    	$this->assertArrayHasKey('Authorization', $this->_paypal->getCurlHeader($this->_paypal));
    }

    public function testProcessSale()
    {
    	$this->_paypal->setAuth(array(
			'AZlu1oYTlPMRpATYdqUlTZzPEMVn-PgHQsCC-uauXR-tZ3GDYL8gZCzAZaGAMmHCADHlWKGD5XCmZ7zQ', 
			'EFgGUwexWJDqmDUUzJFlUGRiyHq48FcT9IgQI2iD4Jb9GovBfNXpFPCfe2dDWTLHgCNAd2j5dJUqgD54'
		));
    	$this->_paypal->generateAccessToken($this->_paypal);

    	$header = array(
    		'Accept'          => 'application/json',
    		'Authorization'   => 'Bearer '. $this->_paypal->getAccessToken($this->_paypal),
    	);

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
 						'total' => '10.5',
 						'currency' => 'USD'
 					)
 				)
 			)
 		);

		$res = $this->_paypal->processSale($this->_paypal, array(
			'header' => $header,
			'body' => $body
		));

		$this->assertNotNull($this->_paypal->getPaymentInfoUrl($this->_paypal));

    }


}
