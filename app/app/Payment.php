<?php

namespace App;

use App\Interfaces\PaymentInterface;

Class Payment
{
	private $_payment;

	public function __construct(PaymentInterface $payment)
	{
		$this->_payment = $payment;
	}

	public function reformatParam(array $param)
	{
		return $this->_payment->generateParam($param);
	}

	public function processSale(array $param)
	{
		return $this->_payment->processSale($param);
	}


	public function logRecord(array $param)
	{
		return $this->_payment->logRecord($param);
	}


}