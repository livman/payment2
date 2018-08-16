<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


use App\Repositories\Braintree;
use App\Payment;

class BraintreeTest extends TestCase
{
	private $_braintree;

	public function setUp()
    {
      //Initialize the test case
      $this->_braintree = new Braintree();
    }


    public function testProcessSale()
    {
        $payment = new Payment($this->_braintree);

        $info['vault'] = array(
            'customer' => array(
                'firstName' => 'Brandon',
                'lastName' => 'Boyd',
                'email' => 'brandon@gmail.com',
                'phone' => '0821458725',
            ),
            'cc' => array(
                'number' => '4111111111111111',
                'holder' => 'Brandon Boyd',
                'mm' => '02',
                'yy' => '22',
                'cvv' => '123',
            )
        );

        $info['sale'] = array('amount' => '10.5');

        $this->assertTrue($payment->processSale($info));

    }


    public function testLog()
    {
        $payment = new Payment($this->_braintree);

        $data = array(
            'merchantId' => '123456',
            'firstName' => 'Brandon',
            'lastName' => 'Boyd',
            'email' => 'test@gmail.com',
            'cardType' => 'Visa',
            'expirationDate' => 'sdgsggrr',
            'created_at' => time(),
            'updated_at' => time(),
        );

        $this->assertTrue($payment->logRecord($data));
    }
    


}

