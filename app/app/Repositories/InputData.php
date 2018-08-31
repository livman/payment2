<?php

namespace App\Repositories;

use App\Interfaces\InputDataInterface;

Class InputData
{
	private $_inputInstance;

	public function __construct(InputDataInterface $inputInstance)
	{
		$this->_inputInstance = $inputInstance;
	}

	public function prepareData(array $input)
	{
		$this->_inputInstance->prepareData($input);
	}
}
