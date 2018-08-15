<?php

namespace App;

use App\Interfaces\PaymentInterface;

Class Payment implements PaymentInterface
{
	public function processSale(Payment $payment, array $param)
	{
		$payment->process();
	}
}