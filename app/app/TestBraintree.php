<?php

namespace App;

use App\Payment;

class TestBraintree extends Payment
{
	public function process()
	{
		echo 'Process Braintree >>>';
	}

}