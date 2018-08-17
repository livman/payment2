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

    private $_payment;

	public function setUp()
    {
        //Initialize the test case
        $this->_paypal = new Paypal();

        $this->_payment = new Payment($this->_paypal);
    }

    public function testDummy()
    {
        $this->assertTrue(true);
    }

    public function testGenerateParam()
    {
        $res = $this->_paypal->generateParam(
            array(
                    'amount' => '10.5',
                    'currency' => 'USD',
                )
        );

        $this->assertArrayHasKey('body', $res);
    }

    public function testGenerateAccessToken()
    {
        $this->assertNotNull($this->_paypal->generateAccessToken());
    }

    
    public function testProcessSale()
    {

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

        $res = $this->_payment->processSale(array(
            'header' => $header,
            'body' => $body
        ));

        $this->assertNotNull($this->_paypal->getApprovalUrl());

    }
    


}

