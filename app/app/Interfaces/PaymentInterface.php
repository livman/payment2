<?php

namespace App\Interfaces;

use App\Payment;
use App\Interfaces\InputDataInterface;

interface PaymentInterface 
{

	public function processSale(InputDataInterface $input_data);

	//public function getResult();

	public function logRecord(array $param);
}