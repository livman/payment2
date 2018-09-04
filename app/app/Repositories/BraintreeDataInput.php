<?php

namespace App\Repositories;
use App\Interfaces\InputDataInterface;
//use App\Interfaces\PaymentInterface;

Class BraintreeDataInput implements InputDataInterface
{

	private $_data = array();

	public function prepareData(array $input)
	{
		list($input['mm'], $input['yy']) = explode("/", $input['exp_date']);

		$this->_data = array(
			'firstName' => $input['c_firstname'],
			'lastName'  => $input['c_lastname'],
			'phone'     => $input['c_phonenumber'],
			'email'     => $input['c_email'],
			'creditCard' => array(
			  'number'          => $input['card_number'],
			  'cardholderName'  => $input['c_firstname'] .' '. $input['c_lastname'],
			  'expirationMonth' => $input['mm'],
			  'expirationYear'  => $input['yy'],
			  'cvv'             => $input['cvv'],
			)
		);

		return $this;

	}

	public function getDataInput()
	{
		return $this->_data;
	}

}