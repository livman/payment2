<?php

namespace App\Interfaces;

use App\Payment;

interface PaymentInterface 
{

	public function processSale(array $param);

	//public function getResult();

	public function logRecord(array $param);
}