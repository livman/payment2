<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


use App\Repositories\Braintree;
use App\Repositories\BraintreeDataInput;
use App\Payment;

class BraintreeTest extends TestCase
{
	private $_braintree;

    private $_payment;

	public function setUp()
    {
      //Initialize the test case
      $this->_braintree = new Braintree();

      //$this->_payment = new Payment($this->_braintree);
    }

    public function testDummy()
    {
        $this->assertTrue(true);
    }

    public function testInputParameter()
    {
        $input_object = new BraintreeDataInput();

        $input_object->prepareData(array(
            'c_firstname' => 'Brandon',
            'c_lastname' => 'Brandon',
            'c_phonenumber' => '025425412',
            'c_email' => 'brandon@gmail.com',
            'card_number' => '4554311335',
            'cvv' => '123',
            'exp_date' => '02/22'
        ));

        $res = $input_object->getDataInput();

        $this->assertTrue(isset($res['lastName']));
    }

    /*
    
    public function testProcessSale()
    {

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

        $this->assertTrue($this->_payment->processSale($info));

    }
    


    public function testLog()
    {
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

        $this->assertTrue($this->_payment->logRecord($data));
    }
    
    public function testGetPaymentProviderObject()
    {
        $res = getPaymentProvider(
            array('cardNumber' => '4111111111111111', 'currencyType' => 'THB'),
            array('USD', 'EUR', 'AUD')
        );

        $this->assertEquals('Braintree', $res);

        $res = getPaymentProvider(
            array('cardNumber' => '4111111111111111', 'currencyType' => 'USD'),
            array('USD', 'EUR', 'AUD')
        );

        $this->assertEquals('Paypal', $res);
    }

    public function testGenerateParam()
    {
        $res = $this->_braintree->generateParam(
            array(
                    'c_firstname' => 'firstname',
                    'c_lastname' => 'lastname',
                    'c_email' => 'aaa@gmail.com',
                    'c_phonenumber' => '02541254132',
                    'card_number' => '4111111111111111',
                    'exp_date' => '02/22',
                    'amount' => '10.5',
                    'cvv' => '123',
                )
        );

        $this->assertArrayHasKey('vault', $res);
    }

    public function testCreateVault()
    {
        $info['vault'] = array(
            'customer' => array(
                'firstName' => 'firstname',
                'lastName' => 'lastname',
                'email' => 'aaa@gmail.com',
                'phone' => '02541254132',
            ),
            'cc' => array(
                'number' => '4111111111111111',
                'holder' => 'dxxx xxxxx',
                'mm' => '02',
                'yy' => '22',
                'cvv' => '123',
            )
        );

        $info['sale'] = array('amount' => '10.8');

        $res = $this->_braintree->createVault($info);

        $this->assertNotEquals(0, $res);

    }

    */
    
}

