<?php

namespace Tests\Feature;



use App\Repositories\Paypal;
use App\Repositories\Braintree;
use App\Payment;


use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BasicTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }


    public function testHasItemInBox()
    {
    //    $box = new Box(['cat', 'toy', 'torch']);

    //    $this->assertTrue($box->has('toy2'));
    //    $this->assertFalse($box->has('ball'));

        $this->assertTrue(true);
    }

/*
    public function testXXX()
    {
        $x = new Paypal();
        $payment = new Payment($x);

        $x->setAuth(array(
            'AZlu1oYTlPMRpATYdqUlTZzPEMVn-PgHQsCC-uauXR-tZ3GDYL8gZCzAZaGAMmHCADHlWKGD5XCmZ7zQ', 
            'EFgGUwexWJDqmDUUzJFlUGRiyHq48FcT9IgQI2iD4Jb9GovBfNXpFPCfe2dDWTLHgCNAd2j5dJUqgD54'
        ));



        $this->assertNotNull($x->generateAccessToken());


        $payment->processSale(array());

        $this->assertTrue(true);
    }
    */


}
