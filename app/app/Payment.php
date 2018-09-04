<?php

namespace App;

use App\Interfaces\PaymentInterface;
use App\Interfaces\InputDataInterface;

Class Payment
{
	private $_payment;

	private $_input_data;

	public function __construct(
		PaymentInterface $payment,
		InputDataInterface $input_data
	)
	{
		$this->_payment = $payment;
		$this->_input_data = $input_data;
	}

	public function reformatParam(array $param)
	{
		return $this->_input_data->prepareData($param);
	}

	public function processSale(InputDataInterface $input_data)
	{
		return $this->_payment->processSale($input_data);
	}

	public function logRecord(array $param)
	{
		return $this->_payment->logRecord($param);
	}


}