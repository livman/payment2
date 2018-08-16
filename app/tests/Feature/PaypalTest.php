<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;



use App\Repositories\Paypal;
use App\Repositories\Braintree;
use App\Payment;

class PaypalTest extends TestCase
{
	private $_paypal;

	public function setUp()
    {
      //Initialize the test case
      $this->_paypal = new Paypal();
    }


    public function testProcessSale()
    {
        $payment = new Payment($this->_paypal);


        $this->_paypal->setAuth(array(
            'AZlu1oYTlPMRpATYdqUlTZzPEMVn-PgHQsCC-uauXR-tZ3GDYL8gZCzAZaGAMmHCADHlWKGD5XCmZ7zQ', 
            'EFgGUwexWJDqmDUUzJFlUGRiyHq48FcT9IgQI2iD4Jb9GovBfNXpFPCfe2dDWTLHgCNAd2j5dJUqgD54'
        ));
        $accessToken = $this->_paypal->generateAccessToken();

        $header = array(
            'Accept'          => 'application/json',
            'Authorization'   => 'Bearer '. $accessToken,
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

        $res = $payment->processSale(array(
            'header' => $header,
            'body' => $body
        ));

        $this->assertNotNull($this->_paypal->getApprovalUrl());

    }
    


}

