<?php

namespace Tests\Feature;

use App\Box;

use App\TestPaypal;
use App\TestBraintree;

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

    public function testXXX()
    {
        $x = new TestPaypal();

        $x->processSale($x, array());

        $this->assertTrue(true);
    }

    public function testYYY()
    {
        $x = new TestBraintree();

        $x->processSale($x, array());

        $this->assertTrue(true);
    }
}
