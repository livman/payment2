<?php

namespace App;

use App\Payment;

class TestPaypal extends Payment
{
	public function process()
	{
		echo 'Process Payapl >>>';
	}

}
